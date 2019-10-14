<?php

namespace App\Controller\Components;

use MWW\Controller\Controller;
use WP_Post;

abstract class Component extends Controller {

	/**
	 * Returns a rendered component
	 *
	 * @param WP_Post|null $post
	 * @param array $data
	 *
	 * @return string
	 */
	abstract public function makeFrom( ?WP_Post $post = null, array $data = [] ): string;

	/**
	 * Helper method to return an array of rendered components from an array of Posts
	 *
	 * @param array $posts
	 * @param array $data
	 *
	 * @return array
	 */
	public static function makeFromArrayOfPosts( array $posts, array $data = [] ): array {
		$components = [];

		foreach ( $posts as $post ) {
			if ( ! $post instanceof WP_Post ) {
				continue;
			}

			$component    = get_called_class();
			$component    = new $component;
			$components[] = $component->makeFrom( $post, $data );
		}

		return $components;
	}

}
