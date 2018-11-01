<?php

namespace MWW\Routing;

use MWW\Routing\RouteConditional;

class Router
{
    /**
    *   Route a request in the application
    */
    public static function routeRequest() {
        self::loadConditional();
        self::loadWPRoutes();
    }

    /**
     * Load Conditional Router
     */
    private static function loadConditional()
    {
        $conditional_file = MWW_PATH . '/routes/conditional.php';
        if (file_exists($conditional_file))
        {
            // Conditional Tag Routing (is_front_page, etc)
            /** @var $router RouteConditional instance - Don't change it*/
            $router = new RouteConditional;
            include_once($conditional_file);
        }
    }

    /**
     * Load WP-Routes Router
     */
    private static function loadWPRoutes()
    {
        $klein_file = MWW_PATH . '/routes/klein.php';
        if (file_exists($klein_file))
        {
            // WP-Routes (/something => echo 'something')
            require_once(__DIR__ . '/Libraries/wp-routes.php');
            add_filter('wp-routes/register_routes', function() {
                klein_with('', $klein_file);
            });
        }
    }
}
