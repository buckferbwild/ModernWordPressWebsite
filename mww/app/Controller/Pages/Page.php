<?php

namespace App\Controller\Pages;

use MWW\Controller\Controller;

class Page extends Controller {

	public function index() {
		$this->include( 'pages.page' );
	}

}
