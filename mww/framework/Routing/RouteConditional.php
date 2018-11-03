<?php

namespace MWW\Routing;

class RouteConditional
{
    /** @var array holds routes to be processed */
    protected $routes = [];

    /**
    *   Filters and processes the routes
    */
    public function __destruct()
    {
        add_action('wp', function() {
            $new_routes = apply_filters('mww_conditional_routes', []);
            foreach ($new_routes as $new_route) {
                foreach ($this->routes as $key => &$route) {
                    if ($route['conditional_tag'] == $new_route['conditional_tag']) {
                        $this->routes[$key] = $new_route;
                    }
                }
            }
        });
        $this->templateInclude();
    }

    /**
     * This is the entry point for adding a Route in MWW.
     * It overrides WordPress templating behavior using template_include filter
     *
     * It accepts two parameters:
     *
     * @param string $conditional_tag One of WordPress's Conditional Tags. Example: "is_front_page".
     * @param mixed $handler What to do when the conditional tag is met.
     *
     * $handler can be of three types:
     *
     * Array: ['App\Pages\HomeController', 'index']
     * Will invoke the method "index" on the class "App\Pages\HomeController"
     * The method must be public. Can be static or not.
     *
     * String: 'doSomething'
     * Will invoke function doSomething();
     *
     * Closure: function() { echo 'Test'; }
     * Will invoke the closure.
     *
     * In all cases, it must echo something!
     *
     * @see https://codex.wordpress.org/Plugin_API/Filter_Reference/template_include
     */
    public function add(string $conditional_tag, $handler)
    {
        $conditional_tag = $this->normalizeConditionalTag($conditional_tag);
        if ($this->assertConditionalIsCallable($conditional_tag)) {
            $this->enqueueRoute($conditional_tag, $handler);
        } else {
            $message = 'The conditional tag "' . $conditional_tag . '" used for routing does not exist.';
            if (defined('WP_DEBUG') && WP_DEBUG) {
                echo $message;
            }
            error_log('The conditional tag "' . $conditional_tag . '" used for routing does not exist.');
            return;
        }
    }

    /**
    *   Transforms is_page("Something") into ['is_page', 'something']
    *   or just return if it's a string already
    *
    *   @param string $conditional_tag
    *   @return mixed $conditional_tag
    */
    private function normalizeConditionalTag(string $conditional_tag)
    {
        if (strpos($conditional_tag, '(')) {
            // Normalize quotes
            str_replace('\'', '"', $conditional_tag);
            // Get function name
            $function_name = explode('(', $conditional_tag);
            $function_name = array_shift($function_name);
            // Get parameters
            if (preg_match('/"([^"]+)"/', $conditional_tag, $result)) {
                $parameter = $result[1];
            }
            $conditional_tag = [$function_name, $parameter];
        }
        return $conditional_tag;
    }

    /**
    *   Asserts a conditional tag function is callable
    *
    *   @param mixed $conditional_tag can be either a string or an array
    *   @return bool
    */
    private function assertConditionalIsCallable($conditional_tag)
    {
        if (is_array($conditional_tag)) {
            $conditional_tag = $conditional_tag[0];
        }
        return function_exists($conditional_tag);
    }

    /**
     * @param mixed $conditional_tag
     * @param $handler
     * @return bool
     */
    private function enqueueRoute($conditional_tag, $handler)
    {
        $this->routes[] = [
            'conditional_tag' => $conditional_tag,
            'handler' => $handler
        ];
        return true;
    }

    /**
     * @param string $conditional_tag
     * @param $handler
     */
    private function templateInclude()
    {
        add_filter('template_include', function ($original) {
            foreach ($this->routes as $route) {

                if (is_string($route['conditional_tag'])) {
                    $conditional_tag = $route['conditional_tag'];
                }

                if (is_array($route['conditional_tag'])) {
                    $conditional_tag = $route['conditional_tag'][0];
                }

                if (call_user_func($conditional_tag) !== true) {
                    continue;
                }

                $response = '';

                if (is_array($route['handler'])) {
                    try {
                        $response = $this->processConditionalByArray($route['handler']);
                    } catch (\Exception $e) {
                        error_log($e->getMessage());
                    }
                } elseif (is_string($route['handler']) && function_exists($route['handler'])) {
                    try {
                        $response = $this->processConditionalByString($route['handler']);
                    } catch (\Exception $e) {
                        error_log($e->getMessage());
                    }
                } elseif ($route['handler'] instanceof \Closure) {
                    try {
                        $response = $this->processConditionalByClosure($route['handler']);
                    } catch (\Exception $e) {
                        error_log($e->getMessage());
                    }
                } else {
                    error_log('Routes should be either an array containing ["Class", "Metod"], a string containing a function name, or an anonymous function closure.');
                }
                echo $response;
                return false;
            }
            // If no route found, continue with normal WordPress loading
            return $original;
        });
    }

    /**
     * Proccess a add that was called with an array, like so:
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
            $class = $handler[0];
            $method = $handler[1];
            if (class_exists(($class))) {
                $classInstance = new $class;
                $reflectionMethod = new \ReflectionMethod($classInstance, $method);
                // Class exists. Does the method exists?
                if ($reflectionMethod->isPublic()) {
                    ob_start();
                    if ($reflectionMethod->isStatic()) {
                        $classInstance::$method();
                    } else {
                        $classInstance->$method();
                    }
                    $response = ob_get_clean();
                } else {
                    throw new \Exception('Could not call method ' . $method . ' on class ' . $class . '. Check if it exists and is public.');
                }
            } else {
                throw new \Exception('Class ' . $class . ' not found.');
            }
        } else {
            throw new \Exception('If using an array for add method, it must contain an array with 2 items: Full path to the controller and method. Example: ["\App\Pages\HomeController", "index"]');
        }

        return $response;
    }

    /**
     * Proccess a add that was called with an string, like so:
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
     * Proccess a add that was called with an closure, like so:
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
            throw new \Exception('Couldn\' execute the closure for a route. call_user_func returned false. Double-check the function and make sure it does not return FALSE.');
        }
        return ob_get_clean();
    }
}
