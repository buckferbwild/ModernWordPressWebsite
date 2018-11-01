<?php

namespace MWW\Pages;

use MWW\Templating\Template;

abstract class Page
{
    /**
     * Template instance
     */
    protected $template;

    public function __construct()
    {
        $this->template = new Template;
    }
}
