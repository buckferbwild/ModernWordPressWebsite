<?php

namespace App\Pages;

use App\Pages\Abstracts\PagesController;

class NotFoundController extends PagesController
{
    public function index()
    {
        $this->template->include('partials.header');
        $this->template->include('pages.404');
        $this->template->include('partials.footer');
    }
}
