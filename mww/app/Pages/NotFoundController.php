<?php

namespace App\Pages;

class NotFoundController extends Abstracts\PagesController
{
    public function output()
    {
        $this->template->include('partials.header');
        $this->template->include('pages.404');
        $this->template->include('partials.footer');
    }
}
