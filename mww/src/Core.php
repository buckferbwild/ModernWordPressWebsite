<?php

namespace MWW;

use MWW\Route;
use MWW\Frontend\Assets;
use MWW\Frontend\Navigation;

class Core
{
    /**
    *   Bootstraps the Website
    */
    public function run()
    {
        $this->setUp();

        // Do the magic here
        $route = new Route;
        add_action('parse_query', [$route, 'routeRequest'], 100);
    }

    /**
     * Initial setUp
     */
    private function setUp()
    {
        $this->loadHelpers();
        $this->loadAssets();
        $this->registerMenu();
        Assets::removeEmojis();
        Assets::registerCustomImageSizes();
    }

    /**
     * Procedural Helper Functions
     */
    private function loadHelpers()
    {
        require_once(MWW_PATH . '/src/helpers.php');
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
