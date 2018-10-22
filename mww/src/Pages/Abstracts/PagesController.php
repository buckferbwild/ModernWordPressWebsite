<?php

namespace MWW\Pages\Abstracts;

use MWW\Frontend\Template;

abstract class PagesController
{
    /**
     * \MWW\Frontend\Template instance
     */
    protected $template;

    public function __construct()
    {
        $this->template = new Template;
    }

    /**
     * Echo the page template
     */
    abstract public function output();
}
