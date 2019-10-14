<?php

namespace App\Controller\Pages;

use MWW\Controller\Controller;

class Home_Controller extends Controller {

	public function index() {
		$this->render( 'pages.home' );
	}

}
