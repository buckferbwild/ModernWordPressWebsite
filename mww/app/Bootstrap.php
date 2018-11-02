<?php

namespace App;

use MWW\Frontend;
use MWW\Routing\Router;
use MWW\Support\Setup;

class Bootstrap
{
    /**
    *   Bootstraps the Website
    */
    public function run()
    {
        $this->setUp();
        Router::routeRequest();
    }

    /**
     * Initial setUp
     */
    private function setUp()
    {
        Setup::includeAppHelpers();
        Setup::includeMWWHelpers();
        Setup::loadAppAssets();
        Setup::registerShortcodes();
        Setup::themeSupports(['post-thumbnails']);

        // Optional
        //Setup::removeEmojis();
        //register_nav_menu('main-menu', __('Main Menu'));
        //Setup::registerBootstrapImageSizes();
    }
}
