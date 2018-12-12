<?php

namespace MWW\Routing;

class Router
{
    /**
    *   Route a request in the application
    */
    public function routeRequest() {
        $this->loadConditional();
        $this->loadWPRoutes();
        mww('mww.routing.conditional')->dispatch();
    }

    /**
     * Load Conditional Router
     */
    private function loadConditional()
    {
        $conditional_file = MWW_PATH . '/routes/conditional.php';
        if (file_exists($conditional_file))
        {
            // Conditional Tag Routing (is_front_page, etc)
            /** @var $router RouteConditional instance - Don't remove it! Used in included file. */
            $router = mww('mww.routing.conditional');
            include_once($conditional_file);
        }
    }

    /**
     * Load WP-Routes Router
     */
    private function loadWPRoutes()
    {
        $klein_file = MWW_PATH . '/routes/klein.php';
        if (file_exists($klein_file))
        {
            // WP-Routes (/something => echo 'something')
            require_once(__DIR__ . '/Libraries/wp-routes.php');
            add_filter('wp-routes/register_routes', function() use ($klein_file) {
                klein_with('', $klein_file);
            });
        }
    }
}
