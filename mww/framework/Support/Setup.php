<?php

namespace MWW\Support;

use MWW\Assets\EnqueueScripts;
use MWW\Shortcodes\ShortcodesRegistrar;

class Setup
{
    /**
     * Include App Helpers
     * You can override MWW Helpers here.
     */
    public function includeAppHelpers()
    {
        include_once(MWW_PATH . '/app/Support/helpers.php');
    }

    /**
     * Enqueues CSS and JS
     */
    public function loadAppAssets()
    {
        add_action('wp_loaded', function() {
            /** EnqueueScritps instance. Don't remove it. Used in included file. */
            $assets = mww('mww.assets');
            include_once(MWW_PATH . '/app/Support/assets.php');
        });
    }

    /**
     * Registers App Shortcodes
     */
    public function registerShortcodes()
    {
        mww('mww.shortcodes.registrar')->registerAll();
    }

    /**
     * Registers Theme Supports
     * @see https://wordpress.stackexchange.com/a/185578/27278
     */
    public function themeSupports(array $theme_supports)
    {
        add_action('after_setup_theme', function() use ($theme_supports) {
            foreach ($theme_supports as $theme_support) {
                if (is_string($theme_support)) {
                    add_theme_support($theme_support);
                }
            }
        });
    }

    /**
     * Removes WordPress emojis
     * https://wordpress.stackexchange.com/a/185578/27278
     */
    public function removeEmojis()
    {
          // all actions related to emojis
          remove_action('admin_print_styles', 'print_emoji_styles');
          remove_action('wp_head', 'print_emoji_detection_script', 7);
          remove_action('admin_print_scripts', 'print_emoji_detection_script');
          remove_action('wp_print_styles', 'print_emoji_styles');
          remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
          remove_filter('the_content_feed', 'wp_staticize_emoji');
          remove_filter('comment_text_rss', 'wp_staticize_emoji');

          // Remove DNS prefetch
          add_filter('emoji_svg_url', '__return_false');
    }

    /**
    *   Enables and registers custom image sizes
    */
    public function registerBootstrapImageSizes()
    {
        add_image_size('col-12', 1170, 9999, false);
        add_image_size('col-12-crop', 1170, 9999, true);

        add_image_size('col-6', 585, 9999, false);
        add_image_size('col-6-crop', 585, 9999, true);

        add_image_size('col-4', 390, 9999, false);
        add_image_size('col-4-crop', 390, 9999, true);

        add_image_size('col-3', 292, 9999, false);
        add_image_size('col-3-crop', 292, 9999, true);
    }
}
