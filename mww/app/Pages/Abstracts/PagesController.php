<?php

namespace App\Pages\Abstracts;

use MWW\Template;

abstract class PagesController
{
    /**
     * MWW\Template instance
     */
    protected $template;

    public function __construct()
    {
        $this->template = new Template;
    }
}
