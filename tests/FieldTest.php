<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests;

use PHPUnit\Framework\TestCase;
use Yiisoft\Form\Field;
use Yiisoft\Form\Tests\Support\AssertTrait;
use Yiisoft\Form\Tests\Support\Form\CheckboxForm;
use Yiisoft\Form\Tests\Support\Form\DateForm;
use Yiisoft\Form\Tests\Support\Form\DateTimeLocalForm;
use Yiisoft\Form\Tests\Support\Form\EmailForm;
use Yiisoft\Form\Tests\Support\Form\HiddenForm;
use Yiisoft\Form\Tests\Support\Form\NumberForm;
use Yiisoft\Form\Tests\Support\Form\PasswordForm;
use Yiisoft\Form\Tests\Support\Form\TextForm;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Widget\WidgetFactory;

final class FieldTest extends TestCase
{
    use AssertTrait;

    protected function setUp(): void
    {
        parent::setUp();
        WidgetFactory::initialize(new SimpleContainer());
    }

    public function testCheckbox(): void
    {
        $result = Field::checkbox(new CheckboxForm(), 'blue')->render();
        $this->assertStringContainsStringIgnoringLineEndings(
            <<<HTML
            <div>
            <input type="hidden" name="CheckboxForm[blue]" value="0"><label><input type="checkbox" id="checkboxform-blue" name="CheckboxForm[blue]" value="1"> Blue color</label>
            </div>
            HTML,
            $result
        );
    }

    public function testDate(): void
    {
        $result = Field::date(new DateForm(), 'birthday')->render();
        $this->assertStringContainsStringIgnoringLineEndings(
            <<<HTML
            <div>
            <label for="dateform-birthday">Your birthday</label>
            <input type="date" id="dateform-birthday" name="DateForm[birthday]" value="1996-12-19">
            <div>Birthday date.</div>
            </div>
            HTML,
            $result
        );
    }

    public function testDateTimeLocal(): void
    {
        $result = Field::dateTimeLocal(new DateTimeLocalForm(), 'partyDate')->render();
        $this->assertStringContainsStringIgnoringLineEndings(
            <<<HTML
            <div>
            <label for="datetimelocalform-partydate">Date of party</label>
            <input type="datetime-local" id="datetimelocalform-partydate" name="DateTimeLocalForm[partyDate]" value="2017-06-01T08:30">
            <div>Party date.</div>
            </div>
            HTML,
            $result
        );
    }

    public function testEmail(): void
    {
        $result = Field::email(new EmailForm(), 'main')->render();
        $this->assertStringContainsStringIgnoringLineEndings(
            <<<HTML
            <div>
            <label for="emailform-main">Main email</label>
            <input type="email" id="emailform-main" name="EmailForm[main]" value>
            <div>Email for notifications.</div>
            </div>
            HTML,
            $result
        );
    }

    public function testHidden(): void
    {
        $result = Field::hidden(new HiddenForm(), 'key')->render();
        $this->assertSame(
            '<input type="hidden" id="hiddenform-key" name="HiddenForm[key]" value="x100">',
            $result
        );
    }

    public function testNumber(): void
    {
        $result = Field::number(new NumberForm(), 'age')->render();
        $this->assertStringContainsStringIgnoringLineEndings(
            <<<HTML
            <div>
            <label for="numberform-age">Your age</label>
            <input type="number" id="numberform-age" name="NumberForm[age]" value="42">
            <div>Full years.</div>
            </div>
            HTML,
            $result
        );
    }

    public function testPassword(): void
    {
        $result = Field::password(new PasswordForm(), 'old')->render();
        $this->assertStringContainsStringIgnoringLineEndings(
            <<<HTML
            <div>
            <label for="passwordform-old">Old password</label>
            <input type="password" id="passwordform-old" name="PasswordForm[old]" value>
            <div>Enter your old password.</div>
            </div>
            HTML,
            $result
        );
    }

    public function testText(): void
    {
        $result = Field::text(new TextForm(), 'job')->render();
        $this->assertStringContainsStringIgnoringLineEndings(
            <<<HTML
            <div>
            <label for="textform-job">Job</label>
            <input type="text" id="textform-job" name="TextForm[job]" value>
            </div>
            HTML,
            $result
        );
    }

    public function testLabel(): void
    {
        $result = Field::label(new TextForm(), 'job')->render();
        $this->assertSame('<label for="textform-job">Job</label>', $result);
    }

    public function testHint(): void
    {
        $result = Field::hint(TextForm::validated(), 'name')->render();
        $this->assertSame('<div>Input your full name.</div>', $result);
    }

    public function testError(): void
    {
        $result = Field::error(TextForm::validated(), 'name')->render();
        $this->assertSame('<div>Value cannot be blank.</div>', $result);
    }
}