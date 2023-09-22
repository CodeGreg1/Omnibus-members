<?php

namespace Modules\Pages\Services;

class PageSectionBuilder
{
    /**
     * The name of the section
     *
     * @var string
     */
    public $name;

    /**
     * The template of the section
     *
     * @var string
     */
    public $template;

    /**
     * The image of the section
     *
     * @var string
     */
    public $image;

    /**
     * Section has data
     *
     * @var bool
     */
    public $has_data;

    /**
     * Section builder class
     *
     * @var string
     */
    public $builder_class;

    /**
     * Section validators
     *
     * @var array
     */
    public $validators;

    /**
     * Section is default
     *
     * @var bool
     */
    public $default;

    public function __construct(
        $name,
        $template,
        $image,
        $has_data,
        $builder_class,
        $validators = [],
        $default = false
    ) {
        $this->name = $name;
        $this->template = $template;
        $this->image = $image;
        $this->has_data = $has_data;
        $this->builder_class = $builder_class;
        $this->validators = $validators;
        $this->default = $default;
    }
}