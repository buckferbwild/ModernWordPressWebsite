<?php

namespace App\Templates\Pages;

use MWW\DI\Container;
use MWW\Templates\Template;

abstract class Page {
	public function __construct() {
		$this->template = Container::make( Template::class );
	}
}
