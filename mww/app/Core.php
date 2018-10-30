<?php

namespace App;

use MWW\Frontend;

class Core
{
    /**
    *   Bootstraps the Website
    */
    public function run()
    {
        $this->setUp();

        // Do the magic here
        require_once(__DIR__ . '/Routes.php');
    }

    /**
     * Initial setUp
     */
    private function setUp()
    {
        $this->loadHelpers();
        $this->loadAssets();
        $this->registerMenu();
        Frontend::removeEmojis();
        Frontend::registerCustomImageSizes();
    }

    /**
     * Procedural Helper Functions
     */
    private function loadHelpers()
    {
        require_once(MWW_PATH . '/app/helpers.php');
    }

    /**
     * Enqueues CSS and JS
     */
    private function loadAssets()
    {
        if (!is_admin() && !is_wp_login()) {
            $assets = new Assets;
            add_action('wp_enqueue_scripts', [$assets, 'enqueueStyles']);
            add_action('wp_enqueue_scripts', [$assets, 'enqueueJavascripts']);
        }
    }

    /**
     * Register the menu
     * @see https://codex.wordpress.org/Navigation_Menus
     */
    private function registerMenu()
    {
        register_nav_menu('main-menu', __('Main Menu'));
    }
}
