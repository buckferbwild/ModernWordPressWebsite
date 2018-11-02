<?php

namespace App\Pages;

use MWW\Pages\Page;

class NotFound extends Page
{
    public function index()
    {
        $this->template->include('header');
        $this->template->include('pages.404');
        $this->template->include('footer');
    }
}
