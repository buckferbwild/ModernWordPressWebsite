<?php

namespace App\Controller\Pages;

use MWW\Controller\Controller;

class Home extends Controller {

	public function index() {
		$this->include( 'pages.home' );
	}

}
