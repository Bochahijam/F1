<?php

declare(strict_types=1);

namespace Yiisoft\Form\Widget;

use Yiisoft\Form\Widget\Attribute\GlobalAttributes;
use Yiisoft\Html\Html;
use Yiisoft\Html\Tag\Input;
use Yiisoft\Widget\Widget;

/**
 * The input element with a type attribute whose value is "image" represents either an image from which the UA enables a
 * user to interactively select a pair of coordinates and submit the form, or alternatively a button from which the user
 * can submit the form.
 *
 * @link https://www.w3.org/TR/2012/WD-html-markup-20120329/input.image.html#input.image
 */
final class Image extends GlobalAttributes
{
    /**
     * Provides a textual label for an alternative button for users and UAs who cannot use the image specified by the
     * src attribute.
     *
     * @param string $value
     *
     * @return static
     *
     * @link https://www.w3.org/TR/2012/WD-html-markup-20120329/input.image.html#input.image.attrs.alt
     */
    public function alt(string $value): self
    {
        $new = clone $this;
        $new->attributes['alt'] = $value;
        return $new;
    }

    /**
     * The height of the image, in CSS pixels.
     *
     * @param string $value
     *
     * @return static
     *
     *  @link https://www.w3.org/TR/2012/WD-html-markup-20120329/input.image.html#input.image.attrs.height
     */
    public function height(string $value): self
    {
        $new = clone $this;
        $new->attributes['height'] = $value;
        return $new;
    }

    /**
     * Specifies the location of an image.
     *
     * @param string $value The location of an image.
     *
     * @return static
     *
     * @link https://www.w3.org/TR/2012/WD-html-markup-20120329/input.image.html#input.image.attrs.src
     */
    public function src(string $value): self
    {
        $new = clone $this;
        $new->attributes['src'] = $value;
        return $new;
    }

    /**
     * The width of the image, in CSS pixels.
     *
     * @param string $value
     *
     * @return static
     *
     * @link https://www.w3.org/TR/2012/WD-html-markup-20120329/input.image.html#input.image.attrs.width
     */
    public function width(string $value): self
    {
        $new = clone $this;
        $new->attributes['width'] = $value;
        return $new;
    }

    /**
     * @return string the generated input tag.
     */
    protected function run(): string
    {
        $attributes = $this->build($this->attributes, '-image');
        return Input::tag()->type('image')->attributes($attributes)->render();
    }

    /**
     * Set build attributes for the widget.
     *
     * @param array $attributes $value
     * @param string $suffix The suffix of the attribute name.
     *
     * @return array
     */
    private function build(array $attributes, string $suffix): array
    {
        $id = Html::generateId('w') . $suffix;

        if (!array_key_exists('id', $attributes)) {
            $attributes['id'] = $id;
        }

        if (!array_key_exists('name', $attributes)) {
            $attributes['name'] = $id;
        }

        return $attributes;
    }
}
