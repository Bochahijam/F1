<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Field;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Yiisoft\Form\Field\Base\InputData\PureInputData;
use Yiisoft\Form\Field\Telephone;
use Yiisoft\Form\ThemeContainer;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Widget\WidgetFactory;

final class TelephoneTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        WidgetFactory::initialize(new SimpleContainer());
        ThemeContainer::initialize();
    }

    public function testBase(): void
    {
        $inputData = new PureInputData(
            name: 'TelephoneForm[number]',
            value: '',
            id: 'telephoneform-number',
            label: 'Phone',
            hint: 'Enter your phone.',
        );

        $result = Telephone::widget()
            ->inputData($inputData)
            ->render();

        $expected = <<<HTML
            <div>
            <label for="telephoneform-number">Phone</label>
            <input type="tel" id="telephoneform-number" name="TelephoneForm[number]" value>
            <div>Enter your phone.</div>
            </div>
            HTML;

        $this->assertSame($expected, $result);
    }

    public function testMaxlength(): void
    {
        $result = Telephone::widget()
            ->name('phone')
            ->useContainer(false)
            ->hideLabel()
            ->maxlength(12)
            ->render();

        $this->assertSame(
            '<input type="tel" name="phone" maxlength="12">',
            $result
        );
    }

    public function testMinlength(): void
    {
        $result = Telephone::widget()
            ->name('phone')
            ->useContainer(false)
            ->hideLabel()
            ->minlength(7)
            ->render();

        $this->assertSame(
            '<input type="tel" name="phone" minlength="7">',
            $result
        );
    }

    public function testPattern(): void
    {
        $result = Telephone::widget()
            ->name('phone')
            ->useContainer(false)
            ->hideLabel()
            ->pattern('\d+')
            ->render();

        $this->assertSame(
            '<input type="tel" name="phone" pattern="\d+">',
            $result
        );
    }

    public function testReadonly(): void
    {
        $result = Telephone::widget()
            ->name('phone')
            ->useContainer(false)
            ->hideLabel()
            ->readonly()
            ->render();

        $this->assertSame(
            '<input type="tel" name="phone" readonly>',
            $result
        );
    }

    public function testRequired(): void
    {
        $result = Telephone::widget()
            ->name('phone')
            ->useContainer(false)
            ->hideLabel()
            ->required()
            ->render();

        $this->assertSame(
            '<input type="tel" name="phone" required>',
            $result
        );
    }

    public function testDisabled(): void
    {
        $result = Telephone::widget()
            ->name('phone')
            ->useContainer(false)
            ->hideLabel()
            ->disabled()
            ->render();

        $this->assertSame(
            '<input type="tel" name="phone" disabled>',
            $result
        );
    }

    public function testAriaDescribedBy(): void
    {
        $result = Telephone::widget()
            ->name('phone')
            ->useContainer(false)
            ->hideLabel()
            ->ariaDescribedBy('hint')
            ->render();

        $this->assertSame(
            '<input type="tel" name="phone" aria-describedby="hint">',
            $result
        );
    }

    public function testAriaLabel(): void
    {
        $result = Telephone::widget()
            ->name('phone')
            ->useContainer(false)
            ->hideLabel()
            ->ariaLabel('test')
            ->render();

        $this->assertSame(
            '<input type="tel" name="phone" aria-label="test">',
            $result
        );
    }

    public function testAutofocus(): void
    {
        $result = Telephone::widget()
            ->name('phone')
            ->useContainer(false)
            ->hideLabel()
            ->autofocus()
            ->render();

        $this->assertSame(
            '<input type="tel" name="phone" autofocus>',
            $result
        );
    }

    public function testTabIndex(): void
    {
        $result = Telephone::widget()
            ->name('phone')
            ->useContainer(false)
            ->hideLabel()
            ->tabIndex(5)
            ->render();

        $this->assertSame(
            '<input type="tel" name="phone" tabindex="5">',
            $result
        );
    }

    public function testSize(): void
    {
        $result = Telephone::widget()
            ->name('phone')
            ->useContainer(false)
            ->hideLabel()
            ->size(9)
            ->render();

        $this->assertSame(
            '<input type="tel" name="phone" size="9">',
            $result
        );
    }

    public function testInvalidValue(): void
    {
        $widget = Telephone::widget()->value(7);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Telephone field requires a string or null value.');
        $widget->render();
    }

    public function testImmutability(): void
    {
        $field = Telephone::widget();

        $this->assertNotSame($field, $field->maxlength(null));
        $this->assertNotSame($field, $field->minlength(null));
        $this->assertNotSame($field, $field->pattern(null));
        $this->assertNotSame($field, $field->readonly());
        $this->assertNotSame($field, $field->required());
        $this->assertNotSame($field, $field->disabled());
        $this->assertNotSame($field, $field->ariaDescribedBy(null));
        $this->assertNotSame($field, $field->ariaLabel(null));
        $this->assertNotSame($field, $field->autofocus());
        $this->assertNotSame($field, $field->tabIndex(null));
        $this->assertNotSame($field, $field->size(null));
    }
}
