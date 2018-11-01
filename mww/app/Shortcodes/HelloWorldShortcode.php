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
     * Call this shortcode using [hello_world text="Optional"]
     *
     * @param $atts
     * @param null $content
     */
    public function register_shortcode($atts, $content = null) {
        echo 'Hello ' . !empty($atts['text']) ? 'World!' : $atts['text'];
    }
}
