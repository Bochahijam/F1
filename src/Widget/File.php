<?php

declare(strict_types=1);

namespace Yiisoft\Form\Widget;

use Stringable;
use Yiisoft\Form\Widget\Attribute\GlobalAttributes;
use Yiisoft\Html\Html;
use Yiisoft\Html\Tag\Input;

/**
 * The input element with a type attribute whose value is "file" represents a list of file items, each consisting of a
 * file name, a file type, and a file body (the contents of the file).
 *
 * @link https://www.w3.org/TR/2012/WD-html-markup-20120329/input.file.html#input.file
 */
final class File extends AbstractWidget
{
    use GlobalAttributes;

    private array $hiddenAttributes = [];
    /** @var bool|float|int|string|Stringable|null */
    private $uncheckValue = null;

    /**
     * The accept attribute value is a string that defines the file types the file input should accept. This string is
     * a comma-separated list of unique file type specifiers. Because a given file type may be identified in more than
     * one manner, it's useful to provide a thorough set of type specifiers when you need files of a given format.
     *
     * @param string $value
     *
     * @return static
     *
     * @link https://html.spec.whatwg.org/multipage/input.html#attr-input-accept
     */
    public function accept(string $value): self
    {
        $new = clone $this;
        $new->attributes['accept'] = $value;
        return $new;
    }

    /**
     * The HTML attributes for tag hidden uncheckValue. The following special options are recognized.
     *
     * @param array $value
     *
     * @return static
     *
     * See {@see \Yiisoft\Html\Html::renderTagAttributes()} for details on how attributes are being rendered.
     */
    public function hiddenAttributes(array $value): self
    {
        $new = clone $this;
        $new->hiddenAttributes = $value;
        return $new;
    }

    /**
     * When the multiple Boolean attribute is specified, the file input allows the user to select more than one file.
     *
     * @param bool $value
     *
     * @return static
     *
     * @link https://html.spec.whatwg.org/multipage/input.html#attr-input-multiple
     */
    public function multiple(bool $value = true): self
    {
        $new = clone $this;
        $new->attributes['multiple'] = $value;
        return $new;
    }

    /**
     * @param bool|float|int|string|Stringable|null $value Value that corresponds to "unchecked" state of the input.
     *
     * @return static
     */
    public function uncheckValue($value): self
    {
        $new = clone $this;
        $new->uncheckValue = is_bool($value) ? (int) $value : $value;
        return $new;
    }

    /**
     * Generates a file input tag for the given form attribute.
     *
     * @return string the generated input tag.
     */
    protected function run(): string
    {
        $new = clone $this;

        /** @link https://www.w3.org/TR/2012/WD-html-markup-20120329/input.file.html#input.file.attrs.name */
        $name = $new->getName();
        $hiddenInput = '';

        /**
         * Add a hidden field so that if a form only has a file field, we can still use isset($body[$formClass]) to
         * detect if the input is submitted.
         * The hidden input will be assigned its own set of html attributes via `$hiddenAttributes`.
         * This provides the possibility to interact with the hidden field via client script.
         *
         * Note: For file-field-only form with `disabled` option set to `true` input submitting detection won't work.
         */
        if ($new->uncheckValue !== null) {
            $hiddenInput = Input::hidden($name, $new->uncheckValue)->attributes($new->hiddenAttributes)->render();
        }

        return $hiddenInput .
            Input::file(Html::getArrayableName($name))
                ->attributes($new->attributes)
                ->id($new->getId())
                ->value(null)
                ->render();
    }
}
