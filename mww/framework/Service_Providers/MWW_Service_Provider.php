<?php

namespace MWW\Service_Providers;

use MWW\DI\Service_Provider;
use MWW\Routing\RouteConditional;
use MWW\Routing\Router;

class MWW_Service_Provider extends Service_Provider {

	/**
	 * @inheritDoc
	 */
	public function register(): void {
		$this->container->singleton( Router::class, Router::class );
		$this->container->singleton( RouteConditional::class, RouteConditional::class );
	}

}
