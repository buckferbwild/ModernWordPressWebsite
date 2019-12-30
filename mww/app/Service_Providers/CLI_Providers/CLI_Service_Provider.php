<?php

namespace App\Service_Providers\CLI_Providers;

use MWW\DI\Context_Aware_Service_Provider;

class CLI_Service_Provider extends Context_Aware_Service_Provider {

	/**
	 * Service Providers in the context of WP CLI
	 *
	 * When it registers:
	 * During requests originated WP CLI
	 */
	public static function should_register(): bool {
		return defined( 'WP_CLI' ) && WP_CLI === true;
	}

	public function register(): void {

	}

}
