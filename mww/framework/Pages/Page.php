<?php

namespace MWW\Pages;

abstract class Page
{
    /**
     * Template instance
     */
    protected $template;

    public function __construct()
    {
        $this->template = mww('mww.template');
    }
}
