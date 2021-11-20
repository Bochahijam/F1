<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Widget;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Yiisoft\Form\Tests\TestSupport\Form\TypeForm;
use Yiisoft\Form\Tests\TestSupport\TestTrait;
use Yiisoft\Form\Widget\Field;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Widget\WidgetFactory;

final class FieldTelephoneTest extends TestCase
{
    use TestTrait;

    public function testMaxLength(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="typeform-string">String</label>
        <input type="tel" id="typeform-string" name="TypeForm[string]" maxlength="10" placeholder="Typed your text string.">
        <div>Write your text string.</div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->for($this->formModel, 'string')->telephone(['maxlength' => 10])->render(),
        );
    }

    public function testMinLength(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="typeform-string">String</label>
        <input type="tel" id="typeform-string" name="TypeForm[string]" minlength="4" placeholder="Typed your text string.">
        <div>Write your text string.</div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->for($this->formModel, 'string')->telephone(['minlength' => 4])->render(),
        );
    }

    public function testPattern(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="typeform-string">String</label>
        <input type="tel" id="typeform-string" name="TypeForm[string]" pattern="[789][0-9]{9}" placeholder="Typed your text string.">
        <div>Write your text string.</div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->for($this->formModel, 'string')->telephone(['pattern' => '[789][0-9]{9}'])->render(),
        );
    }

    public function testPlaceholder(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="typeform-string">String</label>
        <input type="tel" id="typeform-string" name="TypeForm[string]" placeholder="PlaceHolder Text">
        <div>Write your text string.</div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->for($this->formModel, 'string')->telephone(['placeholder' => 'PlaceHolder Text'])->render(),
        );
    }

    public function testRender(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="typeform-string">String</label>
        <input type="tel" id="typeform-string" name="TypeForm[string]" placeholder="Typed your text string.">
        <div>Write your text string.</div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->for($this->formModel, 'string')->telephone()->render(),
        );
    }

    public function testValue(): void
    {
        // value null
        $expected = <<<'HTML'
        <div>
        <label for="typeform-tonull">To Null</label>
        <input type="tel" id="typeform-tonull" name="TypeForm[toNull]">
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->for($this->formModel, 'toNull')->telephone()->render(),
        );

        // telephone as string, "+71234567890"
        $this->formModel->setAttribute('string', '+71234567890');
        $expected = <<<'HTML'
        <div>
        <label for="typeform-string">String</label>
        <input type="tel" id="typeform-string" name="TypeForm[string]" value="+71234567890" placeholder="Typed your text string.">
        <div>Write your text string.</div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->for($this->formModel, 'string')->telephone()->render(),
        );

        // telephone as numeric string, "71234567890"
        $this->formModel->setAttribute('string', '71234567890');
        $expected = <<<'HTML'
        <div>
        <label for="typeform-string">String</label>
        <input type="tel" id="typeform-string" name="TypeForm[string]" value="71234567890" placeholder="Typed your text string.">
        <div>Write your text string.</div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->for($this->formModel, 'string')->telephone()->render(),
        );

        // telephone as integer, 71234567890
        $this->formModel->setAttribute('int', '71234567890');
        $expected = <<<'HTML'
        <div>
        <label for="typeform-int">Int</label>
        <input type="tel" id="typeform-int" name="TypeForm[int]" value="71234567890">
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->for($this->formModel, 'int')->telephone()->render(),
        );
    }

    public function testValueException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Telephone widget must be a string, numeric or null.');
        Field::widget()->for($this->formModel, 'array')->telephone()->render();
    }

    protected function setUp(): void
    {
        parent::setUp();
        WidgetFactory::initialize(new SimpleContainer(), []);
        $this->createFormModel(TypeForm::class);
    }
}
