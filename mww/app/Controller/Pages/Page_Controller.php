<?php

namespace App\Controller\Pages;

use MWW\Controller\Controller;

class Page_Controller extends Controller {

	public function index() {
		$this->render( 'pages.page' );
	}

}
