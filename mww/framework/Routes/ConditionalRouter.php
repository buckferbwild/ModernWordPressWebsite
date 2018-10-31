<?php

namespace MWW\Routes;

class ConditionalRouter
{
    /** @var array holds routes to be processed */
    protected $routes = [];

    public function __destruct()
    {
        $this->routes = apply_filters('mww_conditional_routes', $this->routes);
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
        if (!function_exists($conditional_tag)) {
            error_log('The conditional tag "' . $conditional_tag . '"" used for routing does not exist.');
            return;
        }
        $this->enqueueRoute($conditional_tag, $handler);
    }

    /**
     * @param string $conditional_tag
     * @param $handler
     * @return bool
     */
    private function enqueueRoute(string $conditional_tag, $handler)
    {
        $this->routes[$conditional_tag] = $handler;
        return true;
    }

    /**
     * @param string $conditional_tag
     * @param $handler
     */
    private function templateInclude()
    {
        add_filter('template_include', function ($original) {
            foreach ($this->routes as $conditional_tag => $handler) {
                if (call_user_func($conditional_tag)) {
                    $response = '';
                    if (is_array($handler)) {
                        try {
                            $response = $this->processConditionalByArray($handler);
                        } catch (\Exception $e) {
                            error_log($e->getMessage());
                        }
                    } elseif (is_string($handler) && function_exists($handler)) {
                        try {
                            $response = $this->processConditionalByString($handler);
                        } catch (\Exception $e) {
                            error_log($e->getMessage());
                        }
                    } elseif ($handler instanceof \Closure) {
                        try {
                            $response = $this->processConditionalByClosure($handler);
                        } catch (\Exception $e) {
                            error_log($e->getMessage());
                        }
                    } else {
                        error_log('Routes should be either an array containing ["Class", "Metod"], a string containing a function name, or an anonymous function closure.');
                    }
                    echo $response;
                    return true;
                }
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
            if (class_exists(($handler[0]))) {
                $class = $handler[0];
                $method = $handler[1];
                $classInstance = new $class;
                $reflection = new \ReflectionMethod($classInstance, $method);
                // Class exists. Does the method exists?
                if ($reflection->isPublic()) {
                    ob_start();
                    if ($reflection->isStatic()) {
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

    /**
     * Check if given method is public in given class
     *
     * @param $class
     * @param $method
     * @return bool
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
     * @return bool
     */
    private function isMethodStatic($class, $method)
    {
        $reflection = new \ReflectionMethod($class, $method);
        return $reflection->isStatic();
    }
}
