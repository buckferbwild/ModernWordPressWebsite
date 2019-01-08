<?php

namespace MWW\Pages;

use MWW\Templating\Template;

abstract class Page
{
    public function __construct()
    {
        $this->template = \MWW::make(Template::class);
    }
}
