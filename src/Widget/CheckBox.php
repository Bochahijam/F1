<?php

declare(strict_types=1);

namespace Yiisoft\Form\Widget;

use Yiisoft\Html\Html;
use Yiisoft\Widget\Widget;

final class CheckBox extends Widget
{
    use Options\Common;
    use Options\Input;

    /**
     * Generates a checkbox tag together with a label for the given form attribute.
     *
     * This method will generate the "checked" tag attribute according to the form attribute value.
     *
     * @return string the generated checkbox tag.
     */
    public function run(): string
    {
        $new = clone $this;

        if (!empty($new->getId())) {
            $new->options['id'] = $new->getId();
        }

        return Html::checkBox($new->getNameAndRemoveItFromOptions(), $new->getBooleanValueAndAddItToOptions(), $new->options);
    }
}
