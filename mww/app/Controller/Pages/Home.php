<?php

namespace App\Controller\Pages;

use App\Controller\Components\Post;
use MWW\Controller\Controller;

class Home extends Controller {

	public function index() {
		$posts = get_posts();

		$this->include( 'pages.home', [
			'home_posts' => Post::makeFromArrayOfPosts( $posts ),
		] );
	}

}
