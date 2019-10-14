<?php

namespace MWW\Routing;

use MWW\DI\Container;

class RouteConditional {
	/** @var array holds routes to be processed */
	protected $routes = [];

	/**
	 * Allows developers to filter routes before dispatching.
	 * Should be hooked at "wp" with priority 9 or less
	 */
	protected function filterRoutes() {
		$new_routes = apply_filters( 'mww_conditional_routes', [] );

		foreach ( $new_routes as $new_route ) {
			// Overriding existing route
			foreach ( $this->routes as $key => &$route ) {
				if ( $route['conditional_tag'] == $new_route['conditional_tag'] ) {
					$this->routes[ $key ] = $new_route;
					continue 2;
				}
			}
			// Adding new route
			$this->routes[] = $new_route;
		}
	}

	/**
	 * This is the entry point for adding a Route in MWW.
	 * It overrides WordPress templating behavior using template_include filter
	 *
	 * It accepts two parameters:
	 *
	 * @param string $conditional_tag One of WordPress's Conditional Tags. Example: "is_front_page".
	 * @param mixed $handler What to do when the conditional tag is met.
	 *
	 * $handler can be of three types:
	 *
	 * Array: ['App\Pages\HomeController', 'index']
	 * Will invoke the method "index" on the class "App\Pages\HomeController"
	 * The method must be public. Can be static or not.
	 *
	 * String: 'doSomething'
	 * Will invoke function doSomething();
	 *
	 * Closure: function() { echo 'Test'; }
	 * Will invoke the closure.
	 *
	 * In all cases, it must echo something!
	 *
	 * @see https://codex.wordpress.org/Plugin_API/Filter_Reference/template_include
	 */
	public function add( $conditional_tag, $handler ) {

		// Is it a Route with multiple conditions?
		if ( $conditional_tag instanceof Condition ) {
			// Yes. Let's assign each condition to this Handler.
			foreach ( $conditional_tag->getConditions() as $single_condition ) {
				$this->add( $single_condition, $handler );
			}

			// The Condition object has fulfilled it's role.
			return;
		}

		if ( $this->assertConditionalIsCallable( $conditional_tag ) ) {
			$this->enqueueRoute( $conditional_tag, $handler );
		} else {
			if ( is_array( $conditional_tag ) ) {
				$conditional_tag = array_shift( $conditional_tag );
			}
			$message = 'The conditional tag "' . $conditional_tag . '" used for routing does not exist.';
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				echo $message;
			}

			return;
		}
	}

	/**
	 *   Asserts a conditional tag function is callable
	 *
	 * @param mixed $conditional_tag can be either a string or an array
	 *
	 * @return bool
	 */
	private function assertConditionalIsCallable( $conditional_tag ) {
		if ( is_string( $conditional_tag ) ) {
			return function_exists( $conditional_tag );
		}
		if ( is_array( $conditional_tag ) ) {
			return function_exists( $conditional_tag[0] );
		}

		return false;
	}

	/**
	 * @param mixed $conditional_tag
	 * @param $handler
	 *
	 * @return bool
	 */
	private function enqueueRoute( $conditional_tag, $handler ) {
		$this->routes[] = [
			'conditional_tag' => $conditional_tag,
			'handler'         => $handler,
		];

		return true;
	}

	/**
	 * Dispatches a conditional route using template_include filter
	 */
	public function dispatch() {
		$this->sortRoutes();
		$this->filterRoutes();
		add_filter( 'template_include', function ( $original ) {
			// Only hit route once
			if ( Container::make( Router::class )->getHitRoute() ) {
				return $original;
			}

			foreach ( $this->routes as $route ) {

				// example: 'is_front_page'
				if ( is_string( $route['conditional_tag'] ) ) {
					if ( call_user_func( $route['conditional_tag'] ) !== true ) {
						continue;
					}
				}

				// example: ['is_singular', 'topic']
				if ( is_array( $route['conditional_tag'] ) && count( $route['conditional_tag'] ) === 2 ) {
					if ( call_user_func( $route['conditional_tag'][0], $route['conditional_tag'][1] ) !== true ) {
						continue;
					}
				}

				/** Sets the default action if given the string of a Class */
				if ( is_string( $route['handler'] ) && class_exists( $route['handler'] ) ) {
					$route['handler'] = [ $route['handler'], 'index' ];
				}

				$response = '';

				if ( is_array( $route['handler'] ) ) {
					try {
						$response = $this->processConditionalByArray( $route['handler'] );
					} catch ( \Exception $e ) {
						error_log( $e->getMessage() );
					}
				} elseif ( is_string( $route['handler'] ) && function_exists( $route['handler'] ) ) {
					try {
						$response = $this->processConditionalByString( $route['handler'] );
					} catch ( \Exception $e ) {
						error_log( $e->getMessage() );
					}
				} elseif ( $route['handler'] instanceof \Closure ) {
					try {
						$response = $this->processConditionalByClosure( $route['handler'] );
					} catch ( \Exception $e ) {
						error_log( $e->getMessage() );
					}
				} else {
					error_log( 'Routes should be either an array containing ["Class", "Metod"], a string containing a function name, or an anonymous function closure.' );
				}

				Container::make( Router::class )->setHitRoute( true );

				echo $response;

				return false;
			}

			// If no route found, continue with normal WordPress loading
			return $original;
		} );
	}

	/**
	 * Prioritize Routes where the conditional tags are arrays,
	 * which means they are more specific than strings.
	 *
	 * This avoid that a "is_page" route wrongly matches before
	 * a ["is_page", "foo"] when viewing the "Foo" page.
	 */
	private function sortRoutes() {
		$specific_routes = [];
		$generic_routes  = [];

		array_map( function ( $route ) use ( &$specific_routes, &$generic_routes ) {
			if ( is_array( $route['conditional_tag'] ) ) {
				array_push( $specific_routes, $route );
			} else {
				array_push( $generic_routes, $route );
			}
		}, $this->routes );

		$this->routes = array_merge( $specific_routes, $generic_routes );
	}

	/**
	 * Proccess a add that was called with an array, like so:
	 * ['\App\Pages\HomeController', 'index']
	 *
	 * It will return the output buffer of \App\Pages\HomeController::index();
	 *
	 * @param $handler
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function processConditionalByArray( array $handler ) {
		if ( count( $handler ) == 2 ) {
			$class  = $handler[0];
			$method = $handler[1];

			// 'App\Pages\Home'
			if ( is_string( $class ) ) {
				$classInstance = $this->getClassByString( $class );
			} elseif ( is_object( $class ) ) {
				$classInstance = $class;
			}

			$reflectionMethod = new \ReflectionMethod( $classInstance, $method );
			// Class exists. Does the method exists?
			if ( $reflectionMethod->isPublic() ) {
				ob_start();
				if ( $reflectionMethod->isStatic() ) {
					$classInstance::$method();
				} else {
					$classInstance->$method();
				}
				$response = ob_get_clean();
			} else {
				throw new \Exception( 'Could not call method ' . $method . ' on class ' . $class . '. Check if it exists and is public.' );
			}
		} else {
			throw new \Exception( 'If using an array for add method, it must contain an array with 2 items: Full path to the controller and method. Example: ["\App\Pages\HomeController", "index"]' );
		}

		return $response;
	}

	/**
	 * @param string $className
	 *
	 * @return mixed|null
	 * @throws \Exception
	 */
	private function getClassByString( string $className ) {
		if ( class_exists( $className ) ) {
			return new $className;
		}

		throw new \Exception( 'Class ' . $className . ' not found.' );
	}

	/**
	 * Proccess a add that was called with an string, like so:
	 * ['doSomething']
	 *
	 * It will return the output buffer of function doSomething() {};
	 *
	 * @param string $handler
	 *
	 * @return string
	 * @throws \Exception
	 */
	private function processConditionalByString( string $handler ) {
		ob_start();
		if ( call_user_func( $handler ) === false ) {
			throw new \Exception( 'Couldn\' execute function ' . $handler . '. Make sure the function is declared when the route is being called and it does not return FALSE.' );
		}

		return ob_get_clean();
	}

	/**
	 * Proccess a add that was called with an closure, like so:
	 * function() { echo 'Something' }
	 *
	 * It will return the output buffer of the closure.
	 *
	 * @param \Closure $handler
	 *
	 * @return string
	 * @throws \Exception
	 */
	private function processConditionalByClosure( \Closure $handler ) {
		ob_start();
		if ( call_user_func( $handler ) === false ) {
			throw new \Exception( 'Couldn\' execute the closure for a route. call_user_func returned false. Double-check the function and make sure it does not return FALSE.' );
		}

		return ob_get_clean();
	}
}
