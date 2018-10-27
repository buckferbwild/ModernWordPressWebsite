<?php

namespace App;

use MWW\Router;
use MWW\Assets;

class Core
{
    /**
    *   Bootstraps the Website
    */
    public function run()
    {
        $this->setUp();

        // Route API Requests
        Router::singleton()->routeRequests('api.php', true);

        // Route Web Requests
        add_action('wp', function() {
            Router::singleton()->routeRequests('app.php');
        });
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
        require_once(MWW_PATH . '/framework/helpers.php');
    }

    /**
     * Enqueues CSS and JS
     */
    private function loadAssets()
    {
        if (!is_admin() && !is_wp_login()) {
            require_once('Assets.php');
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
