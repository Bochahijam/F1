<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Yiisoft\Form\FormModel;
use Yiisoft\Form\Tests\TestSupport\CustomFormErrors;
use Yiisoft\Form\Tests\TestSupport\Form\CustomFormNameForm;
use Yiisoft\Form\Tests\TestSupport\Form\DefaultFormNameForm;
use Yiisoft\Form\Tests\TestSupport\Form\FormWithNestedAttribute;
use Yiisoft\Form\Tests\TestSupport\Form\FormWithDeepNested;
use Yiisoft\Form\Tests\TestSupport\Form\LoginForm;
use Yiisoft\Form\Tests\TestSupport\Form\TypeForm;
use Yiisoft\Form\Tests\TestSupport\TestTrait;

require __DIR__ . '/TestSupport/Form/NonNamespacedForm.php';

final class FormModelTest extends TestCase
{
    use TestTrait;

    public function testAnonymousFormName(): void
    {
        $form = new class () extends FormModel {
        };
        $this->assertSame('', $form->getFormName());
    }

    public function testCustomFormName(): void
    {
        $form = new CustomFormNameForm();
        $this->assertSame('my-best-form-name', $form->getFormName());
    }

    public function testDefaultFormName(): void
    {
        $form = new DefaultFormNameForm();
        $this->assertSame('DefaultFormNameForm', $form->getFormName());
    }

    public function testGetAttributeHint(): void
    {
        $form = new LoginForm();

        $this->assertSame('Write your id or email.', $form->getAttributeHint('login'));
        $this->assertSame('Write your password.', $form->getAttributeHint('password'));
        $this->assertEmpty($form->getAttributeHint('noExist'));
    }

    public function testGetAttributeLabel(): void
    {
        $form = new LoginForm();

        $this->assertSame('Login:', $form->getAttributeLabel('login'));
        $this->assertSame('Testme', $form->getAttributeLabel('testme'));
    }

    public function testGetAttributesLabels(): void
    {
        $form = new LoginForm();

        $expected = [
            'login' => 'Login:',
            'password' => 'Password:',
            'rememberMe' => 'remember Me:',
        ];

        $this->assertSame($expected, $form->getAttributeLabels());
    }

    public function testGetNestedAttributeException(): void
    {
        $form = new FormWithNestedAttribute();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Attribute "profile" is not a nested attribute.');
        $form->getAttributeValue('profile.user');
    }

    public function testGetNestedAttributeHint(): void
    {
        $form = new FormWithNestedAttribute();

        $this->assertSame('Write your id or email.', $form->getAttributeHint('user.login'));
        $this->assertSame('Write your id or email.', $form->getAttributeHint('user', 'login'));
    }

    public function testGetNestedAttributeLabel(): void
    {
        $form = new FormWithNestedAttribute();

        $this->assertSame('Login:', $form->getAttributeLabel('user.login'));
        $this->assertSame('Login:', $form->getAttributeLabel('user', 'login'));
    }

    public function testGetNestedAttributePlaceHolder(): void
    {
        $form = new FormWithNestedAttribute();

        $this->assertSame('Type Usernamer or Email.', $form->getAttributePlaceHolder('user.login'));
        $this->assertSame('Type Usernamer or Email.', $form->getAttributePlaceHolder('user', 'login'));
    }

    public function testGetAttributePlaceHolder(): void
    {
        $form = new LoginForm();

        $this->assertSame('Type Usernamer or Email.', $form->getAttributePlaceHolder('login'));
        $this->assertSame('Type Password.', $form->getAttributePlaceHolder('password'));
        $this->assertEmpty($form->getAttributePlaceHolder('noExist'));
    }

    public function testGetAttributeValue(): void
    {
        $form = new LoginForm();

        $form->login('admin');
        $this->assertSame('admin', $form->getAttributeValue('login'));

        $form->password('123456');
        $this->assertSame('123456', $form->getAttributeValue('password'));

        $form->rememberMe(true);
        $this->assertSame(true, $form->getAttributeValue('rememberMe'));
    }

    public function testGetAttributeValueException(): void
    {
        $form = new LoginForm();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Undefined property: "Yiisoft\Form\Tests\TestSupport\Form\LoginForm::noExist".'
        );
        $form->getAttributeValue('noExist');
    }

    public function testGetAttributeValueWithNestedAttribute(): void
    {
        $form = new FormWithNestedAttribute();

        $form->setUserLogin('admin');
        $this->assertSame('admin', $form->getAttributeValue('user.login'));
        $this->assertSame('admin', $form->getAttributeValue('user', 'login'));
    }

    public function testHasAttribute(): void
    {
        $form = new LoginForm();

        $this->assertTrue($form->hasAttribute('login'));
        $this->assertTrue($form->hasAttribute('password'));
        $this->assertTrue($form->hasAttribute('rememberMe'));
        $this->assertFalse($form->hasAttribute('noExist'));
        $this->assertFalse($form->hasAttribute('extraField'));
    }

    public function testHasNestedAttribute(): void
    {
        $form = new FormWithNestedAttribute();

        $this->assertTrue($form->hasAttribute('user.login'));
        $this->assertTrue($form->hasAttribute('user.password'));
        $this->assertTrue($form->hasAttribute('user.rememberMe'));
        $this->assertTrue($form->hasAttribute('user', 'login'));
        $this->assertTrue($form->hasAttribute('user', 'password'));
        $this->assertTrue($form->hasAttribute('user', 'rememberMe'));
        $this->assertFalse($form->hasAttribute('noexist'));
    }

    public function testHasNestedAttributeException(): void
    {
        $form = new FormWithNestedAttribute();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Undefined property: "Yiisoft\Form\Tests\TestSupport\Form\LoginForm::noexist".');
        $form->hasAttribute('user.noexist');
    }

    public function testLoad(): void
    {
        $form = new LoginForm();

        $this->assertNull($form->getLogin());
        $this->assertNull($form->getPassword());
        $this->assertFalse($form->getRememberMe());

        $data = [
            'LoginForm' => [
                'login' => 'admin',
                'password' => '123456',
                'rememberMe' => true,
                'noExist' => 'noExist',
            ],
        ];

        $this->assertTrue($form->load($data));

        $this->assertSame('admin', $form->getLogin());
        $this->assertSame('123456', $form->getPassword());
        $this->assertSame(true, $form->getRememberMe());
    }

    public function testLoadFailedForm(): void
    {
        $form1 = new LoginForm();
        $form2 = new class () extends FormModel {
        };

        $data1 = [
            'LoginForm2' => [
                'login' => 'admin',
                'password' => '123456',
                'rememberMe' => true,
                'noExist' => 'noExist',
            ],
        ];
        $data2 = [];

        $this->assertFalse($form1->load($data1));
        $this->assertFalse($form1->load($data2));

        $this->assertTrue($form2->load($data1));
        $this->assertFalse($form2->load($data2));
    }

    public function testLoadWithEmptyScope(): void
    {
        $form = new class () extends FormModel {
            private int $int = 1;
            private string $string = 'string';
            private float $float = 3.14;
            private bool $bool = true;
        };
        $form->load([
            'int' => '2',
            'float' => '3.15',
            'bool' => 'false',
            'string' => 555,
        ], '');

        // check row data value.
        $this->assertIsString($form->getAttributeValue('int'));
        $this->assertIsString($form->getAttributeValue('float'));
        $this->assertIsString($form->getAttributeValue('bool'));
        $this->assertIsInt($form->getAttributeValue('string'));

        // chech cast data value.
        $this->assertIsInt($form->getAttributeCastValue('int'));
        $this->assertIsFloat($form->getAttributeCastValue('float'));
        $this->assertIsBool($form->getAttributeCastValue('bool'));
        $this->assertIsString($form->getAttributeCastValue('string'));
    }

    public function testLoadWithNestedAttribute(): void
    {
        $form = new FormWithNestedAttribute();

        $data = [
            'FormWithNestedAttribute' => [
                'user.login' => 'admin',
            ],
        ];

        $this->assertTrue($form->load($data));
        $this->assertSame('admin', $form->getUserLogin());
    }

    public function testLoadWithNestedAttributeArray(): void
    {
        $form = new FormWithNestedAttribute();

        $data = [
            'FormWithNestedAttribute' => [
                'user' => [
                    'login' => 'admin',
                ],
            ],
        ];

        $this->assertTrue($form->load($data));
        $this->assertSame('admin', $form->getUserLogin());
    }

    public function testNonNamespacedFormName(): void
    {
        $form = new \NonNamespacedForm();
        $this->assertSame('NonNamespacedForm', $form->getFormName());
    }

    public function testPublicAttributes(): void
    {
        $form = new class () extends FormModel {
            public int $int = 1;
        };

        // check row data value.
        $form->load(['int' => '2']);
        $this->assertSame('2', $form->getAttributeValue('int'));

        // chech cast data value.
        $form->setAttribute('int', 1);
        $this->assertSame(1, $form->getAttributeCastValue('int'));
    }

    public function testAttributeNames(): void
    {
        $form = new LoginForm();
        $this->assertSame(['login', 'password', 'rememberMe'], $form->attributes());

        $nestedForm = new FormWithNestedAttribute();
        $this->assertSame(['id', 'user'], $nestedForm->attributes());

        $typeForm = new TypeForm();
        $this->assertSame(
            ['array', 'bool', 'float', 'int', 'number', 'object', 'string', 'toCamelCase', 'toDate', 'toNull'],
            $typeForm->attributes(),
        );
    }

    public function testProtectedCollectAttributes(): void
    {
        $form = new class () extends FormModel {
            protected int $int = 1;

            public function collectAttributes(): array
            {
                return array_merge(parent::collectAttributes(), ['null' => 'null']);
            }
        };
        $this->assertSame(['int' => 'int', 'null' => 'null'], $form->collectAttributes());
    }

    public function testSetFormErrors(): void
    {
        $formErrors = new CustomFormErrors();
        $formModel = new LoginForm();

        $formModel->setFormErrors($formErrors);
        $this->assertSame($formErrors, $formModel->getFormErrors());
    }

    public function testDeepNestedForm(): void
    {
        $form = new FormWithDeepNested();
        $form->load([
            'intValue' => 1,
            'nestedForm.id' => 10,
            'nestedForm.user.login' => 'test',
            'nestedForm' => [
                'user' => [
                    'rememberMe' => true
                ]
            ]
        ], '');

        $form->setAttribute('personalForm.email', 'test@test.com');

        $this->assertTrue($form->hasAttribute('nestedForm', 'user', 'password'));
        $this->assertTrue($form->getAttributeValue('nestedForm.user.rememberMe'));
        $this->assertTrue($form->getAttributeValue('nestedForm', 'user', 'rememberMe'));
        $this->assertSame(1, $form->getAttributeValue('intValue'));
        $this->assertSame(10, $form->getAttributeValue('nestedForm.id'));
        $this->assertSame(10, $form->getAttributeValue('nestedForm', 'id'));
        $this->assertSame('test', $form->getAttributeValue('nestedForm.user.login'));
        $this->assertSame('test', $form->getAttributeValue('nestedForm', 'user', 'login'));
        $this->assertSame('test@test.com', $form->getAttributeValue('personalForm.email'));
        $this->assertSame('test@test.com', $form->getAttributeValue('personalForm', 'email'));
    }

    public function testSetAttribute(): void
    {
        $form = new class () extends FormModel {
            private $property;
        };

        $form->setAttribute('property', true);
        $this->assertSame(true, $form->getAttributeValue('property'));

        $form->setAttribute('property', 'string');
        $this->assertSame('string', $form->getAttributeValue('property'));

        $form->setAttribute('property', 0);
        $this->assertSame(0, $form->getAttributeValue('property'));

        $form->setAttribute('property', 1.2563);
        $this->assertSame(1.2563, $form->getAttributeValue('property'));

        $form->setAttribute('property', []);
        $this->assertSame([], $form->getAttributeValue('property'));
    }
}
