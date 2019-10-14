<?php

namespace App\Controller\Pages;

use MWW\Controller\Controller;

class NotFound extends Controller {

	public function index() {
		$this->include( 'pages.404' );
	}

}
