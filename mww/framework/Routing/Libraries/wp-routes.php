<?php
/**
 * This is a port of lucatume/wp-routes
 * @see https://github.com/lucatume/wp-routes
 */

require_once dirname( __FILE__ ) . '/wp_klein.php';

use MWW\DI\Container;
use MWW\Routing\Router;

if ( ! function_exists( 'wp_routes_do_parse_request' ) ) {
	/**
	 * Filters the request parsing process before WordPress does.
	 *
	 * @param bool $continue
	 * @param WP $wp
	 * @param string|array $extra_query_vars
	 *
	 * @return bool Either a bool for the `$continue` value or void if the parse request is stopped.
	 */
	function wp_routes_do_parse_request( $continue, WP $wp, $extra_query_vars ) {
		/**
		 * Allows plugin and theme developers to register custom routes to be handled before WordPress
		 * parses the request.
		 *
		 * @param bool $bool Whether or not to parse the request. Defaults to `true`.
		 * @param WP $this Current WordPress environment instance.
		 * @param array|string $extra_query_vars Extra passed query variables.
		 *
		 * @since 1.0.0
		 *
		 */
		do_action( 'wp-routes/register_routes', $continue, $wp, $extra_query_vars );

		/**
		 * If no echo was produced or the only echo produced is from "*" routes
		 * then continue and let WordPress handle the request; otherwise output the
		 * route output and `die`.
		 */
		add_filter( 'template_include', function ( $original ) {
			// Only hit route once
			if ( Container::make( Router::class )->getHitRoute() ) {
				return $original;
			}

			$output = klein_dispatch_or_continue();

			if ( ! empty( $output ) ) {
				echo $output;

				return false;
			}

			return $original;
		}, 50 );

		// if we got here WordPress should handle the request
		return $continue;
	}
}

add_filter( 'do_parse_request', 'wp_routes_do_parse_request', 1, 3 );
