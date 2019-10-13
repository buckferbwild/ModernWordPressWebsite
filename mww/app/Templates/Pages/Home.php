<?php

namespace App\Templates\Pages;

class Home extends Page {
	public function index() {
		$this->template->include( 'pages.home' );
	}
}
