<?php

namespace App;

class Bootstrap
{
    /**
     * Bootstraps the website
     * Sets it up and handle the request
     */
    public function run()
    {
        $this->setUp();
        mww('mww.router')->routeRequest();
    }

    /**
     * Initial Setup
     */
    private function setUp()
    {
        $setup = mww('mww.setup');

        /** Includes app/Support/helpers.php file */
        $setup->includeAppHelpers();

        /** Enqueues Assets */
        $setup->loadAppAssets();

        /** Registers Shortcodes */
        $setup->registerShortcodes();

        /** Registers "theme supports" functions */
        $setup->themeSupports(['post-thumbnails']);

        /** Disable WordPress emojis loading (Optional - Uncomment to use) */
        //$setup->removeEmojis();

        /** Registers a Main Menu (Optional - Uncomment to use) */
        //register_nav_menu('main-menu', __('Main Menu'));

        /** Register image sizes suitable for Bootstrap (Optional - Uncomment to use) */
        //$setup->registerBootstrapImageSizes();
    }
}
