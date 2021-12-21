<?php

declare(strict_types=1);

namespace Yiisoft\Form\Field\Base;

interface PlaceholderInterface
{
    public function placeholder(?string $placeholder): self;

    public function usePlaceholder(bool $use): self;
}
