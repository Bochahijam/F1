<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Field;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Yiisoft\Form\Field\Base\InputData\PureInputData;
use Yiisoft\Form\Field\Range;
use Yiisoft\Form\Tests\Support\StringableObject;
use Yiisoft\Form\Tests\Support\StubValidationRulesEnricher;
use Yiisoft\Form\ThemeContainer;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Widget\WidgetFactory;

final class RangeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        WidgetFactory::initialize(new SimpleContainer());
        ThemeContainer::initialize();
    }

    public static function dataBase(): array
    {
        return [
            'base' => [
                <<<HTML
                <div>
                <label for="rangeform-volume">Volume level</label>
                <input type="range" id="rangeform-volume" name="RangeForm[volume]" value="23" min="1" max="100">
                </div>
                HTML,
                new PureInputData(
                    name: 'RangeForm[volume]',
                    value: 23,
                    label: 'Volume level',
                    id: 'rangeform-volume',
                ),
            ],
            'input-valid-class' => [
                <<<HTML
                <div>
                <input type="range" class="valid" name="main" min="1" max="100">
                </div>
                HTML,
                new PureInputData(name: 'main', validationErrors: []),
                ['inputValidClass' => 'valid', 'inputInvalidClass' => 'invalid'],
            ],
            'container-valid-class' => [
                <<<HTML
                <div class="valid">
                <input type="range" name="main" min="1" max="100">
                </div>
                HTML,
                new PureInputData(name: 'main', validationErrors: []),
                ['validClass' => 'valid', 'invalidClass' => 'invalid'],
            ],
        ];
    }

    #[DataProvider('dataBase')]
    public function testBase(string $expected, PureInputData $inputData, array $theme = []): void
    {
        ThemeContainer::initialize(
            configs: ['default' => $theme],
            defaultConfig: 'default',
        );

        $result = Range::widget()
            ->inputData($inputData)
            ->min(1)
            ->max(100)
            ->render();

        $this->assertSame($expected, $result);
    }

    public function testAddOutputAttributes(): void
    {
        $result = Range::widget()
            ->name('volume')
            ->value(23)
            ->showOutput()
            ->addOutputAttributes(['class' => 'red'])
            ->addOutputAttributes(['id' => 'UID'])
            ->render();

        $expected = <<<HTML_WRAP
<div>
<input type="range" name="volume" value="23" oninput="document.getElementById(&quot;UID&quot;).innerHTML=this.value">
<span id="UID" class="red">23</span>
</div>
HTML_WRAP;

        $this->assertSame($expected, $result);
    }

    public function testOutputAttributes(): void
    {
        $result = Range::widget()
            ->name('volume')
            ->value(23)
            ->showOutput()
            ->outputAttributes(['class' => 'red'])
            ->outputAttributes(['id' => 'UID'])
            ->render();

        $expected = <<<HTML_WRAP
<div>
<input type="range" name="volume" value="23" oninput="document.getElementById(&quot;UID&quot;).innerHTML=this.value">
<span id="UID">23</span>
</div>
HTML_WRAP;

        $this->assertSame($expected, $result);
    }

    public function testWithOutput(): void
    {
        $result = Range::widget()
            ->name('volume')
            ->value(23)
            ->min(1)
            ->max(100)
            ->showOutput()
            ->outputAttributes(['id' => 'UID'])
            ->render();

        $expected = <<<HTML_WRAP
<div>
<input type="range" name="volume" value="23" min="1" max="100" oninput="document.getElementById(&quot;UID&quot;).innerHTML=this.value">
<span id="UID">23</span>
</div>
HTML_WRAP;

        $this->assertSame($expected, $result);
    }

    public function testCustomOutputTag(): void
    {
        $result = Range::widget()
            ->name('volume')
            ->value(23)
            ->showOutput()
            ->outputTag('div')
            ->outputAttributes(['id' => 'UID'])
            ->render();

        $expected = <<<HTML_WRAP
<div>
<input type="range" name="volume" value="23" oninput="document.getElementById(&quot;UID&quot;).innerHTML=this.value">
<div id="UID">23</div>
</div>
HTML_WRAP;

        $this->assertSame($expected, $result);
    }

    public function testEmptyOutputTag(): void
    {
        $field = Range::widget();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The output tag name it cannot be empty value.');
        $field->outputTag('');
    }

    public static function dataMax(): array
    {
        return [
            'int' => [
                '<input type="range" name="count" max="42">',
                42,
            ],
            'string' => [
                '<input type="range" name="count" max="53">',
                '53',
            ],
            'float' => [
                '<input type="range" name="count" max="5.9">',
                '5.9',
            ],
            'Stringable' => [
                '<input type="range" name="count" max="7">',
                new StringableObject('7'),
            ],
            'null' => [
                '<input type="range" name="count">',
                null,
            ],
        ];
    }

    #[DataProvider('dataMax')]
    public function testMax(string $expected, $value): void
    {
        $result = Range::widget()
            ->name('count')
            ->hideLabel()
            ->useContainer(false)
            ->max($value)
            ->render();

        $this->assertSame($expected, $result);
    }

    public static function dataMin(): array
    {
        return [
            'int' => [
                '<input type="range" name="count" min="42">',
                42,
            ],
            'string' => [
                '<input type="range" name="count" min="53">',
                '53',
            ],
            'float' => [
                '<input type="range" name="count" min="5.9">',
                '5.9',
            ],
            'Stringable' => [
                '<input type="range" name="count" min="7">',
                new StringableObject('7'),
            ],
            'null' => [
                '<input type="range" name="count">',
                null,
            ],
        ];
    }

    #[DataProvider('dataMin')]
    public function testMin(string $expected, $value): void
    {
        $result = Range::widget()
            ->name('count')
            ->hideLabel()
            ->useContainer(false)
            ->min($value)
            ->render();

        $this->assertSame($expected, $result);
    }

    public static function dataStep(): array
    {
        return [
            'int' => [
                '<input type="range" name="count" step="42">',
                42,
            ],
            'string' => [
                '<input type="range" name="count" step="53">',
                '53',
            ],
            'float' => [
                '<input type="range" name="count" step="5.9">',
                '5.9',
            ],
            'Stringable' => [
                '<input type="range" name="count" step="7">',
                new StringableObject('7'),
            ],
            'null' => [
                '<input type="range" name="count">',
                null,
            ],
        ];
    }

    #[DataProvider('dataStep')]
    public function testStep(string $expected, $value): void
    {
        $result = Range::widget()
            ->name('count')
            ->hideLabel()
            ->useContainer(false)
            ->step($value)
            ->render();

        $this->assertSame($expected, $result);
    }

    public function testList(): void
    {
        $result = Range::widget()
            ->name('count')
            ->hideLabel()
            ->useContainer(false)
            ->list('TheList')
            ->render();

        $this->assertSame(
            '<input type="range" name="count" list="TheList">',
            $result
        );
    }

    public function testDisabled(): void
    {
        $result = Range::widget()
            ->name('count')
            ->hideLabel()
            ->useContainer(false)
            ->disabled()
            ->render();

        $this->assertSame(
            '<input type="range" name="count" disabled>',
            $result
        );
    }

    public function testAriaDescribedBy(): void
    {
        $result = Range::widget()
            ->name('count')
            ->hideLabel()
            ->useContainer(false)
            ->ariaDescribedBy('hint')
            ->render();

        $this->assertSame(
            '<input type="range" name="count" aria-describedby="hint">',
            $result
        );
    }

    public function testAriaLabel(): void
    {
        $result = Range::widget()
            ->name('count')
            ->hideLabel()
            ->useContainer(false)
            ->ariaLabel('test')
            ->render();

        $this->assertSame(
            '<input type="range" name="count" aria-label="test">',
            $result
        );
    }

    public function testAutofocus(): void
    {
        $result = Range::widget()
            ->name('count')
            ->hideLabel()
            ->useContainer(false)
            ->autofocus()
            ->render();

        $this->assertSame(
            '<input type="range" name="count" autofocus>',
            $result
        );
    }

    public function testTabIndex(): void
    {
        $result = Range::widget()
            ->name('count')
            ->hideLabel()
            ->useContainer(false)
            ->tabIndex(5)
            ->render();

        $this->assertSame(
            '<input type="range" name="count" tabindex="5">',
            $result
        );
    }

    public function testInvalidValue(): void
    {
        $field = Range::widget()->value([]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Range field requires a string, numeric or null value.');
        $field->render();
    }

    public function testEnrichFromValidationRulesEnabled(): void
    {
        ThemeContainer::initialize(
            validationRulesEnricher: new StubValidationRulesEnricher([
                'inputAttributes' => ['data-test' => 1],
            ]),
        );

        $html = Range::widget()->enrichFromValidationRules()->render();

        $expected = <<<HTML
            <div>
            <input type="range" data-test="1">
            </div>
            HTML;

        $this->assertSame($expected, $html);
    }

    public function testEnrichFromValidationRulesDisabled(): void
    {
        ThemeContainer::initialize(
            validationRulesEnricher: new StubValidationRulesEnricher([
                'inputAttributes' => ['data-test' => 1],
            ]),
        );

        $html = Range::widget()->render();

        $expected = <<<HTML
            <div>
            <input type="range">
            </div>
            HTML;

        $this->assertSame($expected, $html);
    }

    public function testInvalidClassesWithCustomError(): void
    {
        $inputData = new PureInputData('company', '');

        $result = Range::widget()
            ->invalidClass('invalidWrap')
            ->inputValidClass('validWrap')
            ->inputInvalidClass('invalid')
            ->inputValidClass('valid')
            ->inputData($inputData)
            ->error('Value cannot be blank.')
            ->render();

        $expected = <<<HTML
            <div class="invalidWrap">
            <input type="range" class="invalid" name="company" value>
            <div>Value cannot be blank.</div>
            </div>
            HTML;

        $this->assertSame($expected, $result);
    }

    public function testImmutability(): void
    {
        $field = Range::widget();

        $this->assertNotSame($field, $field->addOutputAttributes([]));
        $this->assertNotSame($field, $field->outputAttributes([]));
        $this->assertNotSame($field, $field->outputTag('div'));
        $this->assertNotSame($field, $field->showOutput());
        $this->assertNotSame($field, $field->tabIndex(null));
        $this->assertNotSame($field, $field->autofocus());
        $this->assertNotSame($field, $field->ariaLabel(null));
        $this->assertNotSame($field, $field->ariaDescribedBy(null));
        $this->assertNotSame($field, $field->disabled());
        $this->assertNotSame($field, $field->list(null));
        $this->assertNotSame($field, $field->step(null));
        $this->assertNotSame($field, $field->min(null));
        $this->assertNotSame($field, $field->max(null));
    }
}
