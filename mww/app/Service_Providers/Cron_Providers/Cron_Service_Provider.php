<?php

namespace App\Service_Providers\Cron_Providers;

use MWW\DI\Context_Aware_Service_Provider;

class Cron_Service_Provider extends Context_Aware_Service_Provider {

	/**
	 * Service Providers in the context of WP Cron
	 *
	 * When it registers:
	 * During requests originated from wp-cron.php
	 */
	public static function should_register(): bool {
		return wp_doing_cron();
	}

	public function register(): void {

	}

}
