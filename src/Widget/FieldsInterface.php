<?php

declare(strict_types=1);

namespace Yiisoft\Form\Widget;

use Yiisoft\Form\FormInterface;

interface FieldsInterface
{
    public function config(FormInterface $data, string $attribute, array $options = []): self;
    public function ariaAttribute(bool $value): self;
    public function errorCss(string $value): self;
    public function errorSummaryCss(string $value): self;
    public function inputCss(string $value): self;
    public function requiredCss(string $value): self;
    public function succesCss(string $value): self;
    public function template(string $value): self;
    public function validatingCss(string $value): self;
    public function validationStateOn(string $value): self;
}