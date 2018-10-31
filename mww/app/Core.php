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
        $this->routeRequest();
    }

    /**
     * Initial setUp
     */
    private function setUp()
    {
        $this->loadHelpers();
        $this->loadAssets();
        $this->registerMenu();
        $this->registerShortcodes();
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

    /**
     * Register shortcodes at App\Shortcodes
     */
    private function registerShortcodes()
    {
        new Shortcodes\ExampleShortcode;
    }

    /**
     * Routes the request in the application
     */
    private function routeRequest()
    {
        // Conditional Tag Routing (is_front_page, etc)
        include_once(MWW_PATH . '/routes/conditional.php');

        // Klein router (/something => echo 'something')
        require_once(MWW_PATH . '/framework/Routes/KleinRouter.php');
        add_filter('wp-routes/register_routes', function() {
            klein_with('', MWW_PATH . '/routes/klein.php');
        });
    }
}
