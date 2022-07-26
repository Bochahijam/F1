<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\TestSupport\Form;

use Yiisoft\Form\FormModel;
use Yiisoft\Validator\Rule\Email;
use Yiisoft\Validator\Rule\HasLength;
use Yiisoft\Validator\Rule\Regex;
use Yiisoft\Validator\Rule\Required;

final class PersonalForm extends FormModel
{
    private string $email = '';
    private string $name = '';
    private string $password = '';

    public function customError(): string
    {
        return 'This is custom error message.';
    }

    public function customErrorWithIcon(): string
    {
        return '(&#10006;) This is custom error message.';
    }

    public function getAttributeHints(): array
    {
        return [
            'name' => 'Write your first name.',
        ];
    }

    public function getRules(): array
    {
        return [
            'email' => [new Email()],
            'name' => [new Required(), new HasLength(min: 4, lessThanMinMessage: 'Is too short.')],
            'password' => [
                new Required(),
                new Regex(
                    '/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/',
                    message: 'Must contain at least one number and one uppercase and lowercase letter, and at least ' .
                    '8 or more characters.'
                ),
            ],
        ];
    }
}
