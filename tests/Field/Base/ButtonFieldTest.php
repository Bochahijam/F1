<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Field\Base;

use PHPUnit\Framework\TestCase;
use Yiisoft\Form\Tests\Support\StubButtonField;
use Yiisoft\Html\Tag\Button as ButtonTag;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Widget\WidgetFactory;

final class ButtonFieldTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        WidgetFactory::initialize(new SimpleContainer());
    }

    public function testBase(): void
    {
        $result = StubButtonField::widget()
            ->content('Start')
            ->render();

        $expected = <<<HTML
                    <div>
                    <button type="button">Start</button>
                    </div>
                    HTML;

        $this->assertSame($expected, $result);
    }

    public function testButton(): void
    {
        $result = StubButtonField::widget()
            ->button(
                ButtonTag::tag()->content('Start')->class('primary')
            )
            ->render();

        $expected = <<<HTML
                    <div>
                    <button type="button" class="primary">Start</button>
                    </div>
                    HTML;

        $this->assertSame($expected, $result);
    }

    public function testAttributes(): void
    {
        $result = StubButtonField::widget()
            ->attributes([
                'data-key' => 'main',
                'class' => 'primary',
            ])
            ->attributes([
                'aria-label' => 'test',
            ])
            ->render();

        $expected = <<<HTML
                    <div>
                    <button type="button" class="primary" data-key="main" aria-label="test"></button>
                    </div>
                    HTML;

        $this->assertSame($expected, $result);
    }

    public function testReplaceAttributes(): void
    {
        $result = StubButtonField::widget()
            ->attributes([
                'data-key' => 'main',
                'class' => 'primary',
            ])
            ->replaceAttributes([
                'class' => 'red',
            ])
            ->render();

        $expected = <<<HTML
                    <div>
                    <button type="button" class="red"></button>
                    </div>
                    HTML;

        $this->assertSame($expected, $result);
    }

    public function testAriaDescribedBy(): void
    {
        $result = StubButtonField::widget()
            ->ariaDescribedBy('hint')
            ->render();

        $expected = <<<HTML
                    <div>
                    <button type="button" aria-describedby="hint"></button>
                    </div>
                    HTML;

        $this->assertSame($expected, $result);
    }

    public function testAriaLabel(): void
    {
        $result = StubButtonField::widget()
            ->ariaLabel('test')
            ->render();

        $expected = <<<HTML
                    <div>
                    <button type="button" aria-label="test"></button>
                    </div>
                    HTML;

        $this->assertSame($expected, $result);
    }

    public function testAutofocus(): void
    {
        $result = StubButtonField::widget()
            ->autofocus()
            ->render();

        $expected = <<<HTML
                    <div>
                    <button type="button" autofocus></button>
                    </div>
                    HTML;

        $this->assertSame($expected, $result);
    }

    public function testTabIndex(): void
    {
        $result = StubButtonField::widget()
            ->tabIndex(5)
            ->render();

        $expected = <<<HTML
                    <div>
                    <button type="button" tabindex="5"></button>
                    </div>
                    HTML;

        $this->assertSame($expected, $result);
    }

    public function testDisabled(): void
    {
        $result = StubButtonField::widget()
            ->disabled()
            ->render();

        $expected = <<<HTML
                    <div>
                    <button type="button" disabled></button>
                    </div>
                    HTML;

        $this->assertSame($expected, $result);
    }

    public function testForm(): void
    {
        $result = StubButtonField::widget()
            ->form('Post')
            ->render();

        $expected = <<<HTML
                    <div>
                    <button type="button" form="Post"></button>
                    </div>
                    HTML;

        $this->assertSame($expected, $result);
    }

    public function testImmutability(): void
    {
        $field = StubButtonField::widget();

        $this->assertNotSame($field, $field->button(null));
        $this->assertNotSame($field, $field->attributes([]));
        $this->assertNotSame($field, $field->replaceAttributes([]));
        $this->assertNotSame($field, $field->ariaDescribedBy(null));
        $this->assertNotSame($field, $field->ariaLabel(null));
        $this->assertNotSame($field, $field->autofocus());
        $this->assertNotSame($field, $field->tabIndex(null));
        $this->assertNotSame($field, $field->disabled());
        $this->assertNotSame($field, $field->form(null));
    }
}