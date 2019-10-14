<?php

namespace App\Controller\Pages;

use MWW\Controller\Controller;

class NotFound_Controller extends Controller {

	public function index() {
		$this->render( 'pages.404' );
	}

}
