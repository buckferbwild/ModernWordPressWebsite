<?php

namespace App\Pages;

class HomeController extends Abstracts\PagesController
{
    public function index()
    {
        $this->template->include('partials.header');
        $this->template->include('pages.home');
        $this->template->include('partials.footer');
    }
}
