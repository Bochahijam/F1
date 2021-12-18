<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Field;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Yiisoft\Form\Field\InputText;
use Yiisoft\Form\Tests\Support\AssertTrait;
use Yiisoft\Form\Tests\Support\Form\InputTextForm;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Widget\WidgetFactory;

final class InputTextTest extends TestCase
{
    use AssertTrait;

    protected function setUp(): void
    {
        parent::setUp();
        WidgetFactory::initialize(new SimpleContainer());
    }

    public function testBase(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="inputtextform-name">Name</label>
        <input type="text" id="inputtextform-name" name="InputTextForm[name]" value placeholder="Typed your name here">
        <div>Input your full name.</div>
        <div>Value cannot be blank.</div>
        </div>
        HTML;

        $result = InputText::widget()
            ->attribute(InputTextForm::validated(), 'name')
            ->render();

        $this->assertStringContainsStringIgnoringLineEndings($expected, $result);
    }

    public function testWithoutContainer(): void
    {
        $expected = <<<'HTML'
        <label for="inputtextform-job">Job</label>
        <input type="text" id="inputtextform-job" name="InputTextForm[job]" value>
        HTML;

        $result = InputText::widget()
            ->attribute(InputTextForm::validated(), 'job')
            ->useContainer(false)
            ->render();

        $this->assertStringContainsStringIgnoringLineEndings($expected, $result);
    }

    public function testCustomContainerTag(): void
    {
        $expected = <<<'HTML'
        <section>
        <label for="inputtextform-job">Job</label>
        <input type="text" id="inputtextform-job" name="InputTextForm[job]" value>
        </section>
        HTML;

        $result = InputText::widget()
            ->attribute(InputTextForm::validated(), 'job')
            ->containerTag('section')
            ->render();

        $this->assertStringContainsStringIgnoringLineEndings($expected, $result);
    }

    public function testEmptyContainerTag(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Tag name cannot be empty.');
        InputText::widget()
            ->attribute(InputTextForm::validated(), 'job')
            ->containerTag('');
    }

    public function testContainerTagAttributes(): void
    {
        $expected = <<<'HTML'
        <div id="main" class="wrapper">
        <label for="inputtextform-job">Job</label>
        <input type="text" id="inputtextform-job" name="InputTextForm[job]" value>
        </div>
        HTML;

        $result = InputText::widget()
            ->attribute(InputTextForm::validated(), 'job')
            ->containerTagAttributes(['class' => 'wrapper', 'id' => 'main'])
            ->render();

        $this->assertStringContainsStringIgnoringLineEndings($expected, $result);
    }

    public function testCustomTemplate(): void
    {
        $expected = <<<'HTML'
        <div>
        <div class="wrap">
        <div>Input your full name.</div>
        <label for="inputtextform-name">Name</label>
        <div>Value cannot be blank.</div>
        <input type="text" id="inputtextform-name" name="InputTextForm[name]" value placeholder="Typed your name here">
        </div>
        </div>
        HTML;

        $result = InputText::widget()
            ->attribute(InputTextForm::validated(), 'name')
            ->template("<div class=\"wrap\">\n{hint}\n{label}\n{error}\n{input}\n</div>")
            ->render();

        $this->assertStringContainsStringIgnoringLineEndings($expected, $result);
    }

    public function testCustomInputId(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="CustomID">Job</label>
        <input type="text" id="CustomID" name="InputTextForm[job]" value>
        </div>
        HTML;

        $result = InputText::widget()
            ->attribute(InputTextForm::validated(), 'job')
            ->inputId('CustomID')
            ->render();

        $this->assertStringContainsStringIgnoringLineEndings($expected, $result);
    }

    public function testDoNotSetInputIdAttribute(): void
    {
        $expected = <<<'HTML'
        <div>
        <label>Job</label>
        <input type="text" name="InputTextForm[job]" value>
        </div>
        HTML;

        $result = InputText::widget()
            ->attribute(InputTextForm::validated(), 'job')
            ->setInputIdAttribute(false)
            ->render();

        $this->assertStringContainsStringIgnoringLineEndings($expected, $result);
    }

    public function testCustomLabelConfig(): void
    {
        $expected = <<<'HTML'
        <div>
        <label>Your job</label>
        <input type="text" id="inputtextform-job" name="InputTextForm[job]" value>
        </div>
        HTML;

        $result = InputText::widget()
            ->attribute(InputTextForm::validated(), 'job')
            ->labelConfig([
                'setForAttribute()' => [false],
                'content()' => ['Your job'],
            ])
            ->render();

        $this->assertStringContainsStringIgnoringLineEndings($expected, $result);
    }

    public function testCustomLabel(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="inputtextform-job">Your job</label>
        <input type="text" id="inputtextform-job" name="InputTextForm[job]" value>
        </div>
        HTML;

        $result = InputText::widget()
            ->attribute(InputTextForm::validated(), 'job')
            ->label('Your job')
            ->render();

        $this->assertStringContainsStringIgnoringLineEndings($expected, $result);
    }

    public function testCustomHintConfig(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="inputtextform-name">Name</label>
        <input type="text" id="inputtextform-name" name="InputTextForm[name]" value placeholder="Typed your name here">
        <b class="red">Input your full name.</b>
        </div>
        HTML;

        $result = InputText::widget()
            ->attribute(new InputTextForm(), 'name')
            ->hintConfig([
                'tag()' => ['b'],
                'tagAttributes()' => [['class' => 'red']],
            ])
            ->render();

        $this->assertStringContainsStringIgnoringLineEndings($expected, $result);
    }

    public function testCustomHint(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="inputtextform-name">Name</label>
        <input type="text" id="inputtextform-name" name="InputTextForm[name]" value placeholder="Typed your name here">
        <div>Custom hint.</div>
        </div>
        HTML;

        $result = InputText::widget()
            ->attribute(new InputTextForm(), 'name')
            ->hint('Custom hint.')
            ->render();

        $this->assertStringContainsStringIgnoringLineEndings($expected, $result);
    }

    public function testCustomErrorConfig(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="inputtextform-name">Name</label>
        <input type="text" id="inputtextform-name" name="InputTextForm[name]" value placeholder="Typed your name here">
        <div>Input your full name.</div>
        <b class="red">Value cannot be blank.</b>
        </div>
        HTML;

        $result = InputText::widget()
            ->attribute(InputTextForm::validated(), 'name')
            ->errorConfig([
                'tag()' => ['b'],
                'tagAttributes()' => [['class' => 'red']],
            ])
            ->render();

        $this->assertStringContainsStringIgnoringLineEndings($expected, $result);
    }

    public function testOverridePlaceholder(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="inputtextform-name">Name</label>
        <input type="text" id="inputtextform-name" name="InputTextForm[name]" value placeholder="Input your pretty name">
        <div>Input your full name.</div>
        <div>Value cannot be blank.</div>
        </div>
        HTML;

        $result = InputText::widget()
            ->attribute(InputTextForm::validated(), 'name')
            ->placeholder('Input your pretty name')
            ->render();

        $this->assertStringContainsStringIgnoringLineEndings($expected, $result);
    }

    public function testDoNotSetPlaceholder(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="inputtextform-name">Name</label>
        <input type="text" id="inputtextform-name" name="InputTextForm[name]" value>
        <div>Input your full name.</div>
        <div>Value cannot be blank.</div>
        </div>
        HTML;

        $result = InputText::widget()
            ->attribute(InputTextForm::validated(), 'name')
            ->usePlaceholder(false)
            ->render();

        $this->assertStringContainsStringIgnoringLineEndings($expected, $result);
    }

    public function testFormElementTagAttributes(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="inputtextform-job">Job</label>
        <input type="text" id="inputtextform-job" class="red" name="InputTextForm[job]" value>
        </div>
        HTML;

        $result = InputText::widget()
            ->attribute(InputTextForm::validated(), 'job')
            ->formElementTagAttributes(['class' => 'red'])
            ->render();

        $this->assertStringContainsStringIgnoringLineEndings($expected, $result);
    }

    public function testFormElementTagAttributesOverridePlaceholderFromForm(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="inputtextform-name">Name</label>
        <input type="text" id="inputtextform-name" name="InputTextForm[name]" value placeholder="Input your pretty name">
        <div>Input your full name.</div>
        <div>Value cannot be blank.</div>
        </div>
        HTML;

        $result = InputText::widget()
            ->attribute(InputTextForm::validated(), 'name')
            ->formElementTagAttributes(['placeholder' => 'Input your pretty name'])
            ->render();

        $this->assertStringContainsStringIgnoringLineEndings($expected, $result);
    }

    public function testFormElementTagAttributesOverrideIdFromForm(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="MyID">Name</label>
        <input type="text" id="MyID" name="InputTextForm[name]" value placeholder="Typed your name here">
        <div>Input your full name.</div>
        <div>Value cannot be blank.</div>
        </div>
        HTML;

        $result = InputText::widget()
            ->attribute(InputTextForm::validated(), 'name')
            ->formElementTagAttributes(['id' => 'MyID'])
            ->render();

        $this->assertStringContainsStringIgnoringLineEndings($expected, $result);
    }

    public function testInputIdOverrideIdFromTagAttributes(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="CustomID">Name</label>
        <input type="text" id="CustomID" name="InputTextForm[name]" value placeholder="Typed your name here">
        <div>Input your full name.</div>
        <div>Value cannot be blank.</div>
        </div>
        HTML;

        $result = InputText::widget()
            ->attribute(InputTextForm::validated(), 'name')
            ->inputId('CustomID')
            ->formElementTagAttributes(['id' => 'MyID'])
            ->render();

        $this->assertStringContainsStringIgnoringLineEndings($expected, $result);
    }
}
