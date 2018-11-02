<?php

namespace App\Pages;

use MWW\Pages\Page;

class Home extends Page
{
    public function index()
    {
        $this->template->include('header');
        $this->template->include('pages.home');
        $this->template->include('footer');
    }
}
