<?php

namespace App\Shortcodes;

use MWW\Shortcodes\Shortcode;

/**
 * Class HelloWorldShortcode
 * @package App\Shortcodes
 */
class HelloWorldShortcode extends Shortcode {

    /** Shortcode handler */
    public $shortcode = 'hello_world';

    /**
     * Call this shortcode using [hello_world name="Optional"]
     *
     * @param $atts
     * @param null $content
     */
    public function register_shortcode($atts, $content = null) {
        ob_start();
        $name = !empty($atts['name']) ? $atts['name'] : 'World';
        echo 'Hello ' . $name;
        return ob_get_clean();
    }
}
