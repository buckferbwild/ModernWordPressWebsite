<?php

namespace App\Templates\Pages;

class NotFound extends Page {
	public function index() {
		$this->template->include( 'pages.404' );
	}
}
