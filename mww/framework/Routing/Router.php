<?php

namespace MWW\Routing;

class Router
{
    /** @var RouteConditional $routeConditional */
    protected $routeConditional;

    public function __construct(RouteConditional $routeConditional)
    {
        $this->routeConditional = $routeConditional;
    }

    /**
    *   Route a request in the application
    */
    public function routeRequest() {
        // Filter and Dispatch Klein routes

        require_once(__DIR__ . '/Libraries/wp-routes.php');
        $this->loadWPRoutes();

        // Filter and Dispatch Conditional routes
        $this->loadConditional();
        add_action('wp', [$this->routeConditional, 'dispatch'], 25);
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
            $router = $this->routeConditional;
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
            add_filter('wp-routes/register_routes', function() use ($klein_file) {
                include_once($klein_file);
            });
        }
    }
}
