<?php

namespace App\Pages;

use MWW\Pages\Page;

class Home extends Page
{
    public function index()
    {
        $this->template->include('pages.home');
    }
}
