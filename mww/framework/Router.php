<?php

namespace MWW;

use Phroute\Phroute\RouteCollector;

class Router
{
    /** Phroute\Phroute\RouteCollector */
    private static $router;

    private $responded = false;

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
     * Routes the request to the appropriate Controller
     */
    public function routeRequests($templateInclude = true)
    {

        if ( ! $this->responded) {
            try {
                # NB. You can cache the return value from $router->getData() so you don't have to create the routes each request - massive speed gains
                $dispatcher = new \Phroute\Phroute\Dispatcher(self::$router->getData());

                $response = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

                // Loads a page on the web
                if ($templateInclude) {
                    add_filter('template_include', function () {
                        $this->dispatchResponse($response);
                        return false;
                    });
                } else {
                    // API response
                    $this->dispatchResponse($response);
                }


            } catch (\Phroute\Phroute\Exception\HttpRouteNotFoundException $e) {
                // If a Custom route is not found, continue with Native WordPress Routes
            }
        }
    }

    /**
     * Dispatches a response for a matched route
     *
     * @param $response
     */
    private function dispatchResponse($response)
    {
        $this->responded = true;
        echo $response;
    }

    /**
     * Add a route to the RouteCollector
     *
     * @see https://github.com/mrjgreen/phroute
     *
     * @param $method GET, POST, PUT, etc
     * @param $route string '/my-example-route'
     * @param $handler mixed
     */
    public function add($method, $route, $handler)
    {
        self::$router->addRoute(strtoupper($method), $route, $handler);
    }

    /**
     * Overrides WordPress templating behavior using template_include
     *
     * @see https://codex.wordpress.org/Conditional_Tags#Conditional_Tags_Index
     */
    public function conditional(string $conditional_tag, $handler)
    {
        try {
            $this->assertValidConditionalTag($conditional_tag);
        } catch(\Exception $e) {
            echo $e->getMessage();
            exit;
        }

        add_filter('template_include', function () use($handler) {
            if (is_array($handler)) {
                try {
                    $response = $this->processConditionalByArray($handler);
                } catch (\Exception $e) {
                    echo $e->getMessage();
                    exit;
                }
            } elseif (is_string($handler) && function_exists($handler)) {
                try {
                    $response = $this->processConditionalByString($handler);
                } catch (\Exception $e) {
                    echo $e->getMessage();
                    exit;
                }
            } elseif($handler instanceof \Closure) {
                try {
                    $response = $this->processConditionalByClosure($handler);
                } catch (\Exception $e) {
                    echo $e->getMessage();
                    exit;
                }
            } else {
                throw new \Exception('Routes should be either an array containing ["Class", "Metod"], a string containing a function name, or an anonymous function closure.');
            }
            $this->dispatchResponse($response);
            return false;
        });
    }

    /**
     * Proccess a conditional that was called with an array, like so:
     * ['\App\Pages\HomeController', 'index']
     *
     * It will return the output buffer of \App\Pages\HomeController::index();
     *
     * @param $handler
     * @throws \Exception
     * @return string
     */
    private function processConditionalByArray(array $handler)
    {
        if (count($handler) == 2) {
            if (class_exists(($handler[0]))) {
                $class = new $handler[0];
                $className = $handler[0];
                $method = $handler[1];
                // Class exists. Does the method exists?
                if ($this->isMethodPublic($class, $method)) {
                    ob_start();
                    // Is it static?
                    if ($this->isMethodStatic($class, $method)) {
                        $class::$method();
                    } else {
                        $class->$method();
                    }
                    $response = ob_get_clean();
                } else {
                    throw new \Exception('Could not call method ' . $handler[1] . ' on class ' . $handler[0] . '. Check if it exists and is public.');
                }
            } else {
                throw new \Exception('Class ' . $handler[0] . ' not found.');
            }
        } else {
            throw new \Exception('If using an array for conditional method, it must contain an array with 2 items: Full path to the controller and method. Example: ["\App\Pages\HomeController", "index"]');
        }

        return $response;
    }

    /**
     * Proccess a conditional that was called with an string, like so:
     * ['doSomething']
     *
     * It will return the output buffer of function doSomething() {};
     *
     * @param string $handler
     * @throws \Exception
     * @return string
     */
    private function processConditionalByString(string $handler)
    {
        ob_start();
        if (call_user_func($handler) === false) {
            throw new \Exception('Couldn\' execute function ' . $handler . '. Make sure the function is declared when the route is being called and it does not return FALSE.');
        }
        return ob_get_clean();
    }

    /**
     * Proccess a conditional that was called with an closure, like so:
     * function() { echo 'Something' }
     *
     * It will return the output buffer of the closure.
     *
     * @param \Closure $handler
     * @throws \Exception
     * @return string
     */
    private function processConditionalByClosure(\Closure $handler)
    {
        ob_start();
        if (call_user_func($handler) === false) {
            throw new \Exception('Couldn\' execute the anonymous for a route. call_user_func returned false. Double-check the function and make sure it does not return FALSE.');
        }
        return ob_get_clean();
    }

    /**
     * @param string $conditional_tag
     * @throws \Exception
     */
    private function assertValidConditionalTag(string $conditional_tag)
    {
        $conditional_tags = [
            'comments_open',
            'has_tag',
            'has_term',
            'in_category',
            'is_404',
            'is_admin',
            'is_archive',
            'is_attachment',
            'is_author',
            'is_category',
            'is_child_theme',
            'is_comments_popup',
            'is_customize_preview',
            'is_date',
            'is_day',
            'is_feed',
            'is_front_page',
            'is_home',
            'is_month',
            'is_multi_author',
            'is_multisite',
            'is_main_site',
            'is_page',
            'is_page_template',
            'is_paged',
            'is_preview',
            'is_rtl',
            'is_search',
            'is_single',
            'is_singular',
            'is_sticky',
            'is_super_admin',
            'is_tag',
            'is_tax',
            'is_time',
            'is_trackback',
            'is_year',
            'pings_open'
        ];
        if (!in_array($conditional_tag, $conditional_tags)) {
            throw new \Exception('Conditional tag "' . $conditional_tag . '" is not valid. Valid conditional tags are: ' . implode(', ', $conditional_tags));
        }
    }

    /**
     * Check if given method is public in given class
     *
     * @param $class
     * @param $method
     * @return boolean
     */
    private function isMethodPublic($class, $method)
    {
        $reflection = new \ReflectionMethod($class, $method);
        return $reflection->isPublic();
    }

    /**
     * Check if given method is static in given class
     *
     * @param $class
     * @param $method
     * @return boolean
     */
    private function isMethodStatic($class, $method)
    {
        $reflection = new \ReflectionMethod($class, $method);
        return $reflection->isStatic();
    }
}
