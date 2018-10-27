<?php

namespace App\Pages;

use App\Pages\Abstracts\PagesController;

class HomeController extends PagesController
{
    public function index()
    {
        $this->template->include('partials.header');
        $this->template->include('pages.home');
        $this->template->include('partials.footer');
    }
}
