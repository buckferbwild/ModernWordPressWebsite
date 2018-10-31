<?php

namespace App\Shortcodes\Abstracts;

/**
 * Class ShortcodeAbstract
 * @package App\Shortcodes\Abstracts
 *
 * Usually you do not need to modify this file.
 */
abstract class ShortcodeAbstract
{
    /**
     * Shortcode initialization
     */
    public function __construct() {
        add_shortcode( $this->shortcode_name() , array( $this, 'register_shortcode' ) );
    }

    /**
     * Basic shortcode configurations
     */
    public abstract function shortcode_name();

    /**
     * Register the shortcode in WordPress
     */
    public abstract function register_shortcode( $atts, $content = null );
}
