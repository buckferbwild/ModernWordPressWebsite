<?php

namespace App;

use MWW\Support\Setup;
use MWW\Routing\Router;

class Bootstrap
{
    /** @var Router $router */
    protected $router;

    /** @var Setup $setup */
    protected $setup;

    /**
     * Bootstrap constructor.
     *
     * @param Router $router
     * @param Setup  $setup
     */
    public function __construct(Router $router, Setup $setup)
    {
        $this->router = $router;
        $this->setup = $setup;
    }

    /**
     * Bootstraps the website
     * Sets it up and handle the request
     */
    public function run()
    {
        $this->setUp();
        $this->router->routeRequest();
    }

    /**
     * Initial Setup
     */
    private function setUp()
    {
        /** Includes app/Support/helpers.php file */
        $this->setup->includeAppHelpers();

        /** Enqueues Assets */
        $this->setup->loadAppAssets();

        /** Registers Shortcodes */
        $this->setup->registerShortcodes();

        /** Registers "theme supports" functions */
        $this->setup->themeSupports(['post-thumbnails']);

        /** Disable WordPress emojis loading (Optional - Uncomment to use) */
        $this->setup->removeEmojis();

        /** Registers a Main Menu (Optional - Uncomment to use) */
        //register_nav_menu('main-menu', __('Main Menu'));

        /** Register image sizes suitable for Bootstrap (Optional - Uncomment to use) */
        //$setup->registerBootstrapImageSizes();
    }
}
