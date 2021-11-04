<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests;

use PHPUnit\Framework\TestCase;
use Yiisoft\Form\FormModel;
use Yiisoft\Form\Helper\HtmlFormErrors;
use Yiisoft\Form\Tests\TestSupport\Form\FormWithNestedAttribute;
use Yiisoft\Form\Tests\TestSupport\Form\LoginForm;
use Yiisoft\Form\Tests\TestSupport\Validator\ValidatorMock;
use Yiisoft\Validator\ValidatorInterface;

use function str_repeat;

require __DIR__ . '/TestSupport/Form/NonNamespacedForm.php';

final class FormModelTest extends TestCase
{
    public function testAnonymousFormName(): void
    {
        $form = new class () extends FormModel {};
        $this->assertEquals('', $form->getFormName());
    }

    public function testDefaultFormName(): void
    {
        $form = new DefaultFormNameForm();
        $this->assertEquals('DefaultFormNameForm', $form->getFormName());
    }

    public function testNonNamespacedFormName(): void
    {
        $form = new \NonNamespacedForm();
        $this->assertEquals('NonNamespacedForm', $form->getFormName());
    }

    public function testCustomFormName(): void
    {
        $form = new CustomFormNameForm();
        $this->assertEquals('my-best-form-name', $form->getFormName());
    }

    public function testUnknownPropertyType(): void
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

    public function testGetAttributeValue(): void
    {
        $form = new LoginForm();

        $form->login('admin');
        $this->assertEquals('admin', $form->getAttributeValue('login'));

        $form->password('123456');
        $this->assertEquals('123456', $form->getAttributeValue('password'));

        $form->rememberMe(true);
        $this->assertEquals(true, $form->getAttributeValue('rememberMe'));
    }

    public function testGetAttributeValueWithNestedAttribute(): void
    {
        $form = new FormWithNestedAttribute();

        $form->setUserLogin('admin');
        $this->assertEquals('admin', $form->getAttributeValue('user.login'));
    }

    public function testGetAttributeHint(): void
    {
        $form = new LoginForm();

        $this->assertEquals('Write your id or email.', $form->getAttributeHint('login'));
        $this->assertEquals('Write your password.', $form->getAttributeHint('password'));
        $this->assertEmpty($form->getAttributeHint('noExist'));
    }

    public function testGetNestedAttributeHint(): void
    {
        $form = new FormWithNestedAttribute();

        $this->assertEquals('Write your id or email.', $form->getAttributeHint('user.login'));
    }

    public function testGetAttributeLabel(): void
    {
        $form = new LoginForm();

        $this->assertEquals('Login:', $form->getAttributeLabel('login'));
        $this->assertEquals('Testme', $form->getAttributeLabel('testme'));
    }

    public function testGetNestedAttributeLabel(): void
    {
        $form = new FormWithNestedAttribute();

        $this->assertEquals('Login:', $form->getAttributeLabel('user.login'));
    }

    public function testAttributesLabels(): void
    {
        $form = new LoginForm();

        $expected = [
            'login' => 'Login:',
            'password' => 'Password:',
            'rememberMe' => 'remember Me:',
        ];

        $this->assertEquals($expected, $form->getAttributeLabels());
    }

    public function testGetAttributePlaceHolder(): void
    {
        $form = new LoginForm();

        $this->assertEquals('Type Usernamer or Email.', $form->getAttributePlaceHolder('login'));
        $this->assertEquals('Type Password.', $form->getAttributePlaceHolder('password'));
        $this->assertEmpty($form->getAttributePlaceHolder('noExist'));
    }

    public function testGetNestedAttributePlaceHolder(): void
    {
        $form = new FormWithNestedAttribute();

        $this->assertEquals('Type Usernamer or Email.', $form->getAttributePlaceHolder('user.login'));
    }

    public function testErrorSummary(): void
    {
        $form = new LoginForm();

        $data = [
            'LoginForm' => [
                'login' => 'admin@.com',
                'password' => '123456',
            ],
        ];

        $expected = [
            'login' => 'This value is not a valid email address.',
            'password' => 'Is too short.',
        ];

        $validator = $this->createValidatorMock();

        $this->assertTrue($form->load($data));
        $this->assertFalse($validator->validate($form)->isValid());

        $this->assertEquals(
            $expected,
            HtmlFormErrors::getErrorSummary($form, false)
        );

        $expected = [
            'This value is not a valid email address.',
            'Is too short.',
        ];

        $this->assertEquals(
            $expected,
            HtmlFormErrors::getErrorSummary($form, true)
        );
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

        $this->assertEquals('admin', $form->getLogin());
        $this->assertEquals('123456', $form->getPassword());
        $this->assertEquals(true, $form->getRememberMe());
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
        $this->assertEquals('admin', $form->getUserLogin());
    }

    public function testFailedLoadForm(): void
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
        $this->assertIsInt($form->getAttributeValue('int'));
        $this->assertIsFloat($form->getAttributeValue('float'));
        $this->assertIsBool($form->getAttributeValue('bool'));
        $this->assertIsString($form->getAttributeValue('string'));
    }

    public function testAddError(): void
    {
        $form = new LoginForm();
        $errorMessage = 'Invalid password.';

        $form->getFormErrors()->addError('password', $errorMessage);

        $this->assertTrue(HtmlFormErrors::hasErrors($form));
        $this->assertEquals($errorMessage, HtmlFormErrors::getFirstError($form, 'password'));
    }

    public function testAddAndGetErrorForNonExistingAttribute(): void
    {
        $form = new LoginForm();
        $errorMessage = 'Invalid username and/or password.';

        $form->getFormErrors()->addError('form', $errorMessage);

        $this->assertTrue(HtmlFormErrors::hasErrors($form));
        $this->assertEquals($errorMessage, $form->getFormErrors()->getFirstError('form'));
    }

    public function testValidatorRules(): void
    {
        $validator = $this->createValidatorMock();
        $form = new LoginForm();

        $form->login('');
        $validator->validate($form);

        $this->assertEquals(
            ['Value cannot be blank.'],
            HtmlFormErrors::getError($form, 'login')
        );

        $form->login('x');
        $validator->validate($form);
        $this->assertEquals(
            ['Is too short.'],
            HtmlFormErrors::getError($form, 'login')
        );

        $form->login(str_repeat('x', 60));
        $validator->validate($form);
        $this->assertEquals(
            'Is too long.',
            HtmlFormErrors::getFirstError($form, 'login')
        );

        $form->login('admin@.com');
        $validator->validate($form);
        $this->assertEquals(
            'This value is not a valid email address.',
            HtmlFormErrors::getFirstError($form, 'login')
        );
    }

    public function testPublicAttributes(): void
    {
        $form = new class () extends FormModel {
            public int $int = 1;
        };
        $form->load(['int' => '2']);
        $this->assertSame(2, $form->getAttributeValue('int'));

        $form->setAttribute('int', 1);
        $this->assertSame(1, $form->getAttributeValue('int'));
    }

    private function createValidatorMock(): ValidatorInterface
    {
        return new ValidatorMock();
    }
}

final class DefaultFormNameForm extends FormModel
{
}

final class CustomFormNameForm extends FormModel
{
    public function getFormName(): string
    {
        return 'my-best-form-name';
    }
}
