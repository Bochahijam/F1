<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\TestSupport\Widget;

use Yiisoft\Form\Widget\Attribute\CommonAttribute;
use Yiisoft\Form\Widget\Attribute\DateAttributes;
use Yiisoft\Html\Html;
use Yiisoft\Widget\Widget;

final class AttributeWidget extends Widget
{
    use CommonAttribute;
    use DateAttributes;

    protected function run(): string
    {
        $new = clone $this;

        return '<test' . Html::renderTagAttributes($new->attributes) . '>';
    }
}
