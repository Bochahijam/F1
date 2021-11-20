<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Widget;

use PHPUnit\Framework\TestCase;
use Yiisoft\Form\Tests\TestSupport\TestTrait;
use Yiisoft\Form\Widget\Field;
use Yiisoft\Html\Html;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Widget\WidgetFactory;

final class FieldImageTest extends TestCase
{
    use TestTrait;

    public function testAlt(): void
    {
        $expected = <<<'HTML'
        <div>
        <input type="image" id="image-1" name="image-1" alt="Submit">
        </div>
        HTML;
        $this->assertEqualsWithoutLE($expected, Field::widget()->image(['alt' => 'Submit'])->render());
    }

    public function testAttributes(): void
    {
        $expected = <<<'HTML'
        <div>
        <input type="image" id="image-1" class="test-class" name="image-1">
        </div>
        HTML;
        $this->assertEqualsWithoutLE($expected, Field::widget()->image(['class' => 'test-class'])->render());
    }

    public function testAutoIdPrefix(): void
    {
        $expected = <<<'HTML'
        <div>
        <input type="image" id="s-1" name="s-1">
        </div>
        HTML;
        $this->assertEqualsWithoutLE($expected, Field::widget()->image([], ['autoIdPrefix()' => ['s-']])->render());
    }

    public function testId(): void
    {
        $expected = <<<'HTML'
        <div>
        <input type="image" id="test-id" name="test-id">
        </div>
        HTML;
        $this->assertEqualsWithoutLE($expected, Field::widget()->image(['id' => 'test-id'])->render());
    }

    public function testHeight(): void
    {
        $expected = <<<'HTML'
        <div>
        <input type="image" id="image-1" name="image-1" height="20">
        </div>
        HTML;
        $this->assertEqualsWithoutLE($expected, Field::widget()->image(['height' => '20'])->render());
    }

    public function testName(): void
    {
        $expected = <<<'HTML'
        <div>
        <input type="image" id="image-1" name="test-name">
        </div>
        HTML;
        $this->assertEqualsWithoutLE($expected, Field::widget()->image(['name' => 'test-name'])->render());
    }

    public function testRender(): void
    {
        $expected = <<<'HTML'
        <div>
        <input type="image" id="image-1" name="image-1">
        </div>
        HTML;
        $this->assertEqualsWithoutLE($expected, Field::widget()->image()->render());
    }

    public function testSrc(): void
    {
        $expected = <<<'HTML'
        <div>
        <input type="image" id="image-1" name="image-1" src="img_submit.gif">
        </div>
        HTML;
        $this->assertEqualsWithoutLE($expected, Field::widget()->image(['src' => 'img_submit.gif'])->render());
    }

    public function testWidth(): void
    {
        $expected = <<<'HTML'
        <div>
        <input type="image" id="image-1" name="image-1" width="20px">
        </div>
        HTML;
        $this->assertEqualsWithoutLE($expected, Field::widget()->image(['width' => '20px'])->render());
    }

    protected function setUp(): void
    {
        parent::setUp();
        WidgetFactory::initialize(new SimpleContainer(), []);
        $this->setInaccessibleProperty(new Html(), 'generateIdCounter', []);
    }
}
