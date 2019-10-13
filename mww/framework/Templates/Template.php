<?php

namespace MWW\Templates;

class Template {
	/**
	 * Includes a template file
	 *
	 * @param string $file Name of the view to load
	 * @param array $data_noconflict Data to be passed to the view
	 * @param string $hook_to_fire Fires this hook upon loading this template
	 */
	public function include( string $file, array $data_noconflict = [], string $hook_to_fire = '' ) {
		// Globals from load_template function from wp-includes/template.php
		global $posts, $post, $wp_did_header, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;

		// Warn for conflicting vars
		if ( ! empty( $data_noconflict ) ) {
			$this->warnForGlobalVarConflicts( $data_noconflict, $file );
		}

		// Extract without conflict
		extract( $data_noconflict, EXTR_PREFIX_SAME, 'mww' );

		// Sanitize search
		if ( isset( $s ) ) {
			$s = esc_attr( $s );
		}

		// Allows for subdirectory includes, such as "partials.header"
		$file = str_replace( '.', '/', $file );

		$file_path = MWW_PATH . '/views/' . $file . '.blade.php';

		if ( file_exists( $file_path ) ) {
			if ( ! empty( $hook ) ) {
				do_action( $hook );
			}

			// Create \Illuminate\View\Factory
			$view = ViewFactory::create();

			// Pass all previously defined to view
			$view->share( get_defined_vars() );

			// Construct and render view
			echo $view->make( $file )->render();
		} else {
			$message = 'Error loading view: ' . $file;
			error_log( $message );
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				echo $message . '<br>';
				if ( endswith( $file, '/php' ) ) {
					echo 'Maybe you meant to add <strong>' . rtrim( $file, '/php' ) . '</strong> instead?<br>';
				}
			}
		}
	}

	/**
	 * Warns for conflicts with $data and WordPress globals
	 *
	 * @param array $data data being passed to the view
	 * @param string $file file to which this data is being passed
	 *
	 * @todo test warnForGlobalVarConflicts
	 */
	private function warnForGlobalVarConflicts( array $data, string $file ) {
		$globals = [
			'posts',
			'post',
			'wp_did_header',
			'wp_query',
			'wp_rewrite',
			'wpdb',
			'wp_version',
			'wp',
			'id',
			'comment',
			'user_ID',
		];
		foreach ( $data as $key => $value ) {
			if ( in_array( $key, $globals ) ) {
				$message = 'You must rename variable "' . $key . '", as it conflicts with existing WordPress global $' . $key . ' (being passed to view ' . $file . ')';
				error_log( $message );
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					echo $message;
				}
			}
		}
	}
}
