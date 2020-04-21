<?php

declare(strict_types=1);

namespace Yiisoft\Form\Widget;

use Yiisoft\Factory\Exceptions\InvalidConfigException;
use Yiisoft\Form\Exception\InvalidArgumentException;
use Yiisoft\Widget\Widget;

final class HiddenInput extends Widget
{
    use Collection\Options;

    /**
     * Generates a hidden input tag for the given form attribute.
     *
     * @throws InvalidArgumentException
     * @throws InvalidConfigException
     *
     * @return string the generated input tag.
     */
    public function run(): string
    {
        return Input::widget()
            ->type('hidden')
            ->data($this->data)
            ->attribute($this->attribute)
            ->options($this->options)
            ->run();
    }
}
