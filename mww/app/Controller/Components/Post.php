<?php

namespace App\Controller\Components;

use WP_Post;

class Post extends Component {

	public function makeFrom( ?WP_Post $post = null, array $data = [] ): string {
		$title   = get_the_title( $post );
		$excerpt = get_the_excerpt( $post );
		$url     = get_post_permalink( $post );

		return $this->generate( 'partials.post', [
			'title'   => esc_html( $title ),
			'excerpt' => esc_html( $excerpt ),
			'url'     => esc_url( $url ),
		] );
	}

}
