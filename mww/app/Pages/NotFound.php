<?php

namespace App\Pages;

use MWW\Pages\Page;

class NotFound extends Page {
	public function index() {
		$this->template->include( 'pages.404' );
	}
}
