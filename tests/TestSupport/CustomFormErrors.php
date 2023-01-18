<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\TestSupport;

use Yiisoft\Form\FormErrorsInterface;

final class CustomFormErrors implements FormErrorsInterface
{
    public function __construct(
        private array $attributesErrors = [],
    ) {
    }

    public function addError(string $attribute, string $error): void
    {
        $this->attributesErrors[$attribute][] = $error;
    }

    public function getAllErrors(): array
    {
        return $this->attributesErrors;
    }

    public function getErrors(string $attribute): array
    {
        return $this->attributesErrors[$attribute] ?? [];
    }

    public function getErrorSummary(array $onlyAttributes = []): array
    {
        $errors = $this->getAllErrors();

        if ($onlyAttributes !== []) {
            $errors = array_intersect_key($errors, array_flip($onlyAttributes));
        }

        return $this->renderErrorSummary($errors);
    }

    public function getErrorSummaryFirstErrors(): array
    {
        return $this->renderErrorSummary([$this->getFirstErrors()]);
    }

    public function getFirstError(string $attribute): string
    {
        if (empty($this->attributesErrors[$attribute])) {
            return '';
        }

        return reset($this->attributesErrors[$attribute]);
    }

    public function getFirstErrors(): array
    {
        if (empty($this->attributesErrors)) {
            return [];
        }

        $errors = [];

        foreach ($this->attributesErrors as $name => $es) {
            if (!empty($es)) {
                $errors[$name] = reset($es);
            }
        }

        return $errors;
    }

    public function hasErrors(?string $attribute = null): bool
    {
        return $attribute === null ? !empty($this->attributesErrors) : isset($this->attributesErrors[$attribute]);
    }

    public function clear(?string $attribute = null): void
    {
        if ($attribute !== null) {
            unset($this->attributesErrors[$attribute]);
        } else {
            $this->attributesErrors = [];
        }
    }

    private function renderErrorSummary(array $errors): array
    {
        $lines = [];

        /** @var string[] errors */
        foreach ($errors as $error) {
            $lines = array_merge($lines, $error);
        }

        return $lines;
    }
}
