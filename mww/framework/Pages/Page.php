<?php

namespace MWW\Pages;

use MWW\DI\Container;
use MWW\Templating\Template;

abstract class Page {
	public function __construct() {
		$this->template = Container::make( Template::class );
	}
}
