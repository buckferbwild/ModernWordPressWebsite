<?php

namespace MWW\Routing;

use MWW\DI\Container;

class Router {
	/** @var RouteConditional $routeConditional */
	protected $routeConditional;

	/** @var bool $hit_route */
	protected $hit_route = false;

	public function __construct( RouteConditional $routeConditional ) {
		$this->routeConditional = $routeConditional;
	}

	public function setHitRoute( bool $hit_route ) {
		$this->hit_route = $hit_route;
	}

	public function getHitRoute(): bool {
		return $this->hit_route;
	}

	/**
	 *   Route a request in the application
	 */
	public function routeRequest() {
		require_once( __DIR__ . '/Libraries/wp-routes.php' );

		add_filter( 'mww/route/raw/respond', function ( array $new_routes ): array {
			if ( $new_routes[0][1] == '*' ) {
				return $new_routes;
			}

			// Normalize how we register router responses
			$new_routes[0][1] = sprintf(
				'/%s/',
				trim( $new_routes[0][1], '/' )
			);

			return $new_routes;
		} );

		add_filter( 'klein_die_handler', function () {
			// filter expects a callback
			return function ( $output ) {
				Container::make( Router::class )->setHitRoute( true );

				return $output;
			};
		} );

		/**
		 * For routes registered with wp_klein.
		 *
		 * @action do_parse_request
		 */
		add_action( 'wp-routes/register_routes', function () {
			include_once( MWW_PATH . '/routes/app.php' );
		} );

		/**
		 * For routes registered with conditional tags.
		 *
		 * Hooking at "wp" just to give some time for any code
		 * that wishes to filters the routes.
		 */
		add_action( 'wp', [ $this->routeConditional, 'dispatch' ], 25 );
	}
}
