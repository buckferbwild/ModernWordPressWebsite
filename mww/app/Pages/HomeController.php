<?php

namespace App\Pages;

use App\Pages\Abstracts\PagesController;

class HomeController extends PagesController
{
    public function index()
    {
        $this->template->include('partials.header');
        $this->template->include('pages.home', ['page' => get_post(2)]);
        $this->template->include('partials.footer');
    }
}
