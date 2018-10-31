<?php

namespace MWW;

class Router
{
    /** @var array holds which conditional tags were already processed */
    protected $processed_conditional_tags = [];

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
        try {
            $this->assertValidConditionalTag($conditional_tag);
        } catch(\Exception $e) {
            echo $e->getMessage();
            exit;
        }
        if ($this->assertConditionalTagWasNotProcessed($conditional_tag)) {
            add_filter('template_include', function () use($conditional_tag, $handler) {
                if (call_user_func($conditional_tag)) {
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
                    } elseif ($handler instanceof \Closure) {
                        try {
                            $response = $this->processConditionalByClosure($handler);
                        } catch (\Exception $e) {
                            echo $e->getMessage();
                            exit;
                        }
                    } else {
                        throw new \Exception('Routes should be either an array containing ["Class", "Metod"], a string containing a function name, or an anonymous function closure.');
                    }
                    // Return response
                    echo $response;
                    return false;
                } else {
                    // Conditional tag returned false. Continue with WordPress loading...
                    return true;
                }
            });
        }
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
                $class = new $handler[0];
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
     * Assert that given conditional tag is valid
     *
     * @param string $conditional_tag
     * @throws \Exception
     * @see https://codex.wordpress.org/Conditional_Tags#Conditional_Tags_Index
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
            throw new \Exception('Conditional tag "' . $conditional_tag . '" is not valid. Valid add tags are: ' . implode(', ', $conditional_tags));
        }
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

    /**
     * Mark a conditional tag as processed
     *
     * @param string $conditional_tag
     * @return bool
     */
    private function assertConditionalTagWasNotProcessed(string $conditional_tag)
    {
        if (in_array($conditional_tag, $this->processed_conditional_tags)) {
            return false;
        }
        $this->processed_conditional_tags[] = $conditional_tag;
        return true;
    }
}
