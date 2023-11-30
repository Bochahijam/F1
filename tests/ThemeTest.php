<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests;

use PHPUnit\Framework\TestCase;
use Yiisoft\Form\Field\Base\InputData\FormModelInputData;
use Yiisoft\Form\Field;
use Yiisoft\Form\Field\Fieldset;
use Yiisoft\Form\Field\Part\Label;
use Yiisoft\Form\ThemeContainer;
use Yiisoft\Form\Field\ErrorSummary;
use Yiisoft\Form\Field\Text;
use Yiisoft\Form\Tests\Support\Form\ErrorSummaryForm;
use Yiisoft\Form\Tests\Support\Form\TextForm;
use Yiisoft\Form\YiiValidatorRulesEnricher;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Widget\WidgetFactory;

final class ThemeTest extends TestCase
{
    public function dataText(): array
    {
        return [
            [
                <<<'HTML'
                <div>
                <label for="textform-name">Name</label>
                <input type="text" id="textform-name" name="TextForm[name]" value placeholder="Typed your name here">
                <div>Input your full name.</div>
                <div>Value cannot be blank.</div>
                </div>
                HTML,
                [],
                'name',
            ],
            [
                <<<'HTML'
                <div>
                <label for="textform-company">Company</label>
                <input type="text" id="textform-company" name="TextForm[company]" value required>
                <div>Value cannot be blank.</div>
                </div>
                HTML,
                [
                    'enrichmentFromRules' => true,
                ],
                'company',
            ],
            [
                <<<'HTML'
                <section class="wrapper">
                <label for="textform-job">Job</label>
                <input type="text" id="textform-job" name="TextForm[job]" value>
                </section>
                HTML,
                [
                    'containerTag' => 'section',
                    'containerAttributes' => ['class' => 'wrapper'],
                ],
                'job',
            ],
            [
                <<<HTML
                <div class="wrapper">
                <label for="textform-job">Job</label>
                <input type="text" id="textform-job" name="TextForm[job]" value>
                </div>
                HTML,
                ['containerClass' => 'wrapper'],
                'job',
            ],
            [
                <<<HTML
                <div class="wrapper red">
                <label for="textform-job">Job</label>
                <input type="text" id="textform-job" name="TextForm[job]" value>
                </div>
                HTML,
                ['containerClass' => ['wrapper', 'red']],
                'job',
            ],
            [
                <<<HTML
                <div>
                <label for="textform-job">Job</label>
                <input type="text" id="textform-job" class="red" name="TextForm[job]" value>
                </div>
                HTML,
                ['inputClass' => 'red'],
                'job',
            ],
            [
                <<<HTML
                <div>
                <label for="textform-job">Job</label>
                <input type="text" id="textform-job" class="red blue" name="TextForm[job]" value>
                </div>
                HTML,
                ['inputClass' => ['red', 'blue']],
                'job',
            ],
            [
                <<<'HTML'
                <label for="textform-job">Job</label>
                <input type="text" id="textform-job" name="TextForm[job]" value>
                HTML,
                [
                    'useContainer' => false,
                ],
                'job',
            ],
            'common-template' => [
                <<<'HTML'
                <div>
                <div class="wrap">
                <div>Input your full name.</div>
                <label for="textform-name">Name</label>
                <div>Value cannot be blank.</div>
                <input type="text" id="textform-name" name="TextForm[name]" value placeholder="Typed your name here">
                </div>
                </div>
                HTML,
                [
                    'template' => "<div class=\"wrap\">\n{hint}\n{label}\n{error}\n{input}\n</div>",
                ],
                'name',
            ],
            [
                <<<'HTML'
                <div>
                <label>Job</label>
                <input type="text" class="form-control" name="TextForm[job]" value>
                </div>
                HTML,
                [
                    'setInputId' => false,
                    'inputAttributes' => ['class' => 'form-control'],
                ],
                'job',
            ],
            [
                <<<'HTML'
                <div>
                <label>Name</label>
                <input type="text" id="textform-name" name="TextForm[name]" value placeholder="Typed your name here">
                <div class="info">Input your full name.</div>
                <div class="red">Value cannot be blank.</div>
                </div>
                HTML,
                [
                    'labelConfig' => [
                        'setFor()' => [false],
                    ],
                    'hintConfig' => [
                        'attributes()' => [['class' => 'info']],
                    ],
                    'errorConfig' => [
                        'attributes()' => [['class' => 'red']],
                    ],
                ],
                'name',
            ],
            [
                <<<'HTML'
                <div>
                <label for="textform-name">Name</label>
                <input type="text" id="textform-name" name="TextForm[name]" value>
                <div>Input your full name.</div>
                <div>Value cannot be blank.</div>
                </div>
                HTML,
                [
                    'usePlaceholder' => false,
                ],
                'name',
            ],
            [
                <<<'HTML'
                <div>
                <label for="textform-name">Name</label>
                <input type="text" id="textform-name" name="TextForm[name]" value>
                <div>Input your full name.</div>
                <div>Value cannot be blank.</div>
                </div>
                HTML,
                [
                    'usePlaceholder' => false,
                ],
                'name',
            ],
            [
                <<<'HTML'
                <div class="main-wrapper">
                <label for="textform-job">Job</label>
                <input type="text" id="textform-job" name="TextForm[job]" value data-type="input-text">
                </div>
                HTML,
                [
                    'containerTag' => 'section',
                    'containerAttributes' => ['class' => 'wrapper'],
                    'inputAttributes' => ['data-type' => 'field'],
                    'fieldConfigs' => [
                        Text::class => [
                            'containerTag()' => ['div'],
                            'containerAttributes()' => [['class' => 'main-wrapper']],
                            'inputAttributes()' => [['data-type' => 'input-text']],
                        ],
                    ],
                ],
                'job',
            ],
            [
                <<<'HTML'
                <div class="wrapper valid">
                <label for="textform-job">Job</label>
                <input type="text" id="textform-job" name="TextForm[job]" value>
                </div>
                HTML,
                [
                    'validClass' => 'valid',
                    'containerAttributes' => ['class' => 'wrapper'],
                ],
                'job',
            ],
            [
                <<<'HTML'
                <div class="wrapper invalid">
                <label for="textform-company">Company</label>
                <input type="text" id="textform-company" name="TextForm[company]" value>
                <div>Value cannot be blank.</div>
                </div>
                HTML,
                [
                    'invalidClass' => 'invalid',
                    'containerAttributes' => ['class' => 'wrapper'],
                ],
                'company',
            ],
            [
                <<<'HTML'
                <div class="wrapper">
                <label for="textform-job">Job</label>
                <input type="text" id="textform-job" class="valid" name="TextForm[job]" value>
                </div>
                HTML,
                [
                    'inputValidClass' => 'valid',
                    'containerAttributes' => ['class' => 'wrapper'],
                ],
                'job',
            ],
            [
                <<<'HTML'
                <div class="wrapper">
                <label for="textform-company">Company</label>
                <input type="text" id="textform-company" class="invalid" name="TextForm[company]" value>
                <div>Value cannot be blank.</div>
                </div>
                HTML,
                [
                    'inputInvalidClass' => 'invalid',
                    'containerAttributes' => ['class' => 'wrapper'],
                ],
                'company',
            ],
            [
                <<<'HTML'
                <div>
                <label for="textform-job">Job</label>
                <div class="control"><input type="text" id="textform-job" name="TextForm[job]" value></div>
                </div>
                HTML,
                [
                    'inputContainerTag' => 'div',
                    'inputContainerAttributes' => ['class' => 'control'],
                ],
                'job',
            ],
            [
                <<<'HTML'
                <div>
                <label for="textform-job">Job</label>
                <div class="control"><input type="text" id="textform-job" name="TextForm[job]" value></div>
                </div>
                HTML,
                [
                    'inputContainerTag' => 'div',
                    'inputContainerClass' => 'control',
                ],
                'job',
            ],
            [
                <<<'HTML'
                <div>
                <label for="textform-job">Job</label>
                <div class="control red"><input type="text" id="textform-job" name="TextForm[job]" value></div>
                </div>
                HTML,
                [
                    'inputContainerTag' => 'div',
                    'inputContainerClass' => ['control', 'red'],
                ],
                'job',
            ],
        ];
    }

    /**
     * @dataProvider dataText
     */
    public function testText(string $expected, array $factoryParameters, string $attribute): void
    {
        $this->initializeThemeContainer($factoryParameters);

        $result = Text::widget()
            ->inputData(new FormModelInputData(TextForm::validated(), $attribute))
            ->render();

        $this->assertSame($expected, $result);
    }

    public function testTextWithCustomTheme(): void
    {
        WidgetFactory::initialize(new SimpleContainer());
        ThemeContainer::initialize([
            'custom-theme' => [
                'inputContainerTag' => 'div',
                'inputContainerClass' => ['control', 'red'],
            ],
        ]);

        $result = Text::widget(theme: 'custom-theme')
            ->inputData(new FormModelInputData(TextForm::validated(), 'job'))
            ->render();

        $this->assertSame(
            <<<'HTML'
                <div>
                <label for="textform-job">Job</label>
                <div class="control red"><input type="text" id="textform-job" name="TextForm[job]" value></div>
                </div>
                HTML,
            $result
        );
    }

    public function dataErrorSummary(): array
    {
        return [
            'base' => [
                <<<'HTML'
                <div>
                <p>Please fix the following errors:</p>
                <ul>
                <li>Value cannot be blank.</li>
                </ul>
                </div>
                HTML,
                [],
            ],
            'non-exists-common-methods' => [
                <<<'HTML'
                <div>
                <p>Please fix the following errors:</p>
                <ul>
                <li>Value cannot be blank.</li>
                </ul>
                </div>
                HTML,
                [
                    'template' => '{input}',
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataErrorSummary
     */
    public function testErrorSummary(string $expected, array $factoryParameters): void
    {
        $factoryParameters = array_merge(
            [
                'fieldConfigs' => [
                    ErrorSummary::class => [
                        'onlyAttributes()' => ['name'],
                    ],
                ],
            ],
            $factoryParameters
        );

        $this->initializeThemeContainer($factoryParameters);

        $result = Field::errorSummary(ErrorSummaryForm::validated())->render();

        $this->assertSame($expected, $result);
    }

    public function dataFieldSet(): array
    {
        return [
            'empty' => [
                <<<HTML
                <div>
                <fieldset>
                </fieldset>
                </div>
                HTML,
                [],
            ],
        ];
    }

    /**
     * @dataProvider dataFieldSet
     */
    public function testFieldSet(string $expected, array $factoryParameters): void
    {
        $this->initializeThemeContainer($factoryParameters);

        $result = Field::fieldset()->render();

        $this->assertSame($expected, $result);
    }

    public function testFieldSetWithOverrideTemplateBeginAndTemplateEnd(): void
    {
        $this->initializeThemeContainer([
            'templateBegin' => "before\n{input}",
            'templateEnd' => "{input}\nafter",
        ]);

        $field = Fieldset::widget();

        $result = $field->begin() . 'hello' . $field::end();

        $expected = <<<HTML
            <div>
            before
            <fieldset>hello</fieldset>
            after
            </div>
            HTML;

        $this->assertSame($expected, $result);
    }

    public function dataLabel(): array
    {
        return [
            'simple' => [
                <<<'HTML'
                <label for="textform-job">Job</label>
                HTML,
                [],
            ],
            'set-input-id-attribute-false' => [
                <<<'HTML'
                <label>Job</label>
                HTML,
                [
                    'labelConfig' => [
                        'useInputId()' => [false],
                    ],
                ],
            ],
            'set-for-attribute-false' => [
                <<<'HTML'
                <label>Job</label>
                HTML,
                [
                    'labelConfig' => [
                        'setFor()' => [false],
                    ],
                ],
            ],
            'label-class-string' => [
                '<label class="red" for="textform-job">Job</label>',
                ['labelClass' => 'red'],
            ],
            'label-class-array' => [
                '<label class="red blue" for="textform-job">Job</label>',
                ['labelClass' => ['red', 'blue']],
            ],
            'label-class-null' => [
                '<label for="textform-job">Job</label>',
                ['labelClass' => null],
            ],
        ];
    }

    /**
     * @dataProvider dataLabel
     */
    public function testLabel(string $expected, array $factoryParameters): void
    {
        $this->initializeThemeContainer($factoryParameters);

        $result = Label::widget()
            ->inputData(new FormModelInputData(new TextForm(), 'job'))
            ->render();

        $this->assertSame($expected, $result);
    }

    public function dataHint(): array
    {
        return [
            [
                <<<'HTML'
                <div>Input your full name.</div>
                HTML,
                [],
            ],
            [
                <<<'HTML'
                <b>Input your full name.</b>
                HTML,
                [
                    'hintConfig' => [
                        'tag()' => ['b'],
                    ],
                ],
            ],
            'hint-class-string' => [
                '<div class="red">Input your full name.</div>',
                ['hintClass' => 'red'],
            ],
            'hint-class-array' => [
                '<div class="red blue">Input your full name.</div>',
                ['hintClass' => ['red', 'blue']],
            ],
            'hint-class-null' => [
                '<div>Input your full name.</div>',
                ['hintClass' => null],
            ],
        ];
    }

    /**
     * @dataProvider dataHint
     */
    public function testHint(string $expected, array $factoryParameters): void
    {
        $this->initializeThemeContainer($factoryParameters);

        $result = Field::hint(new TextForm(), 'name')->render();

        $this->assertSame($expected, $result);
    }

    public function dataError(): array
    {
        return [
            [
                <<<'HTML'
                <div>Value cannot be blank.</div>
                HTML,
                [],
            ],
            [
                <<<'HTML'
                <b>Value cannot be blank.</b>
                HTML,
                [
                    'errorConfig' => [
                        'tag()' => ['b'],
                    ],
                ],
            ],
            'error-class-string' => [
                '<div class="red">Value cannot be blank.</div>',
                ['errorClass' => 'red'],
            ],
            'error-class-array' => [
                '<div class="red blue">Value cannot be blank.</div>',
                ['errorClass' => ['red', 'blue']],
            ],
            'error-class-null' => [
                '<div>Value cannot be blank.</div>',
                ['errorClass' => null],
            ],
        ];
    }

    /**
     * @dataProvider dataError
     */
    public function testError(string $expected, array $factoryParameters): void
    {
        $this->initializeThemeContainer($factoryParameters);

        $result = Field::error(TextForm::validated(), 'name')->render();

        $this->assertSame($expected, $result);
    }

    private function initializeThemeContainer(array $parameters): void
    {
        WidgetFactory::initialize(new SimpleContainer());
        ThemeContainer::initialize(
            ['default' => $parameters],
            defaultConfig: 'default',
            validationRulesEnricher: new YiiValidatorRulesEnricher(),
        );
    }
}
