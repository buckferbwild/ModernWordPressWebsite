<?php

namespace MWW;

use Phroute\Phroute\RouteCollector;

class Router
{
    /** Phroute\Phroute\RouteCollector */
    private static $router;

    /**
     * Singleton
     *
     * Call this method to get an instance of Route
     *
     * @return Router
     */
    public static function singleton()
    {
        static $inst = null;
        if ($inst === null) {
            self::$router = new RouteCollector;
            $inst = new static;
        }
        return $inst;
    }
    /**
     * Singleton
     *
     * Private constructor
     */
    private function __construct()
    {
    }

    /**
    *   Register a Routes file
    */
    public function registerRoutes(string $file) {
        require_once(MWW_PATH . '/routes/' . $file);
    }

    /**
     * Routes the request to the appropriate Controller
     */
    public function routeRequests()
    {
        try {
            # NB. You can cache the return value from $router->getData() so you don't have to create the routes each request - massive speed gains
            $dispatcher = new \Phroute\Phroute\Dispatcher(self::$router->getData());

            $response = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

            add_filter('template_include', function () {
                echo $response;
                return false;
            });

        } catch (\Phroute\Phroute\Exception\HttpRouteNotFoundException $e) {
            // If a Custom route is not found, continue with Native WordPress Routes
        }
    }

    public function add($method, $route, $handler)
    {
        self::$router->addRoute(strtoupper($method), $route, $handler);
    }
}
