<?php

namespace App\Service_Providers\Ajax_Providers;

use MWW\DI\Context_Aware_Service_Provider;

class Ajax_Service_Provider extends Context_Aware_Service_Provider {

	/**
	 * Service Providers exclusive to the context of an AJAX Request
	 *
	 * When it registers:
	 * During requests originated from wp-admin/admin-ajax.php
	 */
	public static function should_register(): bool {
		return wp_doing_ajax();
	}

	public function register(): void {

	}

}
