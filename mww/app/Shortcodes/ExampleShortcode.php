<?php

namespace App\Shortcodes;

/**
 * Class ExampleShortcode
 * @package App\Shortcodes
 */
class ExampleShortcode extends Abstracts\ShortcodeAbstract {

    /** The name of this shortcode */
    private $shortcode = 'example_shortcode';

    /** @return string $this->shortcode */
    public function shortcode_name() {
        return $this->shortcode;
    }

    /**
     * Call this shortcode using [example_shortcode text="Optional"]
     *
     * @param $atts
     * @param null $content
     */
    public function register_shortcode($atts, $content = null) {
        echo 'I\'m an Example Shortcode!';
        if (!empty($atts['text'])) {
            echo '<br> And look, I have this text with me: ' . $atts['text'];
        }
    }
}
