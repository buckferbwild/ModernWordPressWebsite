<?php

namespace MWW\Shortcodes;

/**
 * Class Shortcode
 * @package MWW\Shortcodes;
 */
abstract class Shortcode {
	/**
	 * Shortcode initialization
	 */
	public function __construct() {
		add_shortcode( $this->shortcode, [ $this, 'register_shortcode' ] );
	}

	/**
	 * Register the shortcode in WordPress
	 */
	public abstract function register_shortcode( $atts, $content = null );
}
