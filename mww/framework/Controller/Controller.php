<?php

namespace MWW\Controller;

use MWW\DI\Container;
use MWW\Templates\Template;

abstract class Controller {

	/** @var Template */
	private $template;

	public function __construct() {
		$this->template = Container::make( Template::class );
	}

	protected function include( string $file, array $data_noconflict = [], string $hook_to_fire = '' ) {
		$this->template->include( $file, $data_noconflict, $hook_to_fire );
	}

	protected function generate( string $file, array $data_noconflict = [], string $hook_to_fire = '' ): string {
		ob_start();
		$this->template->include( $file, $data_noconflict, $hook_to_fire );

		return ob_get_clean();
	}
}
