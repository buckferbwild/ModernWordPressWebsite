<?php

use MWW\Container\MWW_Container;

/**
 * Debug Function
 *
 * @param mixed $debug
 * @example dd($var);
 * @example dd([$var1, $var2]);
 */
if (!function_exists('dd')) {
    function dd($debug)
    {
        echo '<pre>' . var_dump($debug) . '</pre>';
        exit;
    }
}

/**
 * Returns true if current page is WordPress login
 * @see https://wordpress.stackexchange.com/a/237285/27278
 */
if (!function_exists('is_wp_login')) {
    function is_wp_login()
    {
        $ABSPATH_MY = str_replace(array('\\','/'), DIRECTORY_SEPARATOR, ABSPATH);
        return ((in_array($ABSPATH_MY.'wp-login.php', get_included_files()) || in_array($ABSPATH_MY.'wp-register.php', get_included_files()) ) || (isset($_GLOBALS['pagenow']) && $GLOBALS['pagenow'] === 'wp-login.php') || $_SERVER['PHP_SELF']== '/wp-login.php');
    }
}

/**
 * Returns URL for MWW Public folder
 *
 * @param string $file Optionally, you can pass a public file to get it's URL
 */
if (!function_exists('mww_public')) {
    function mww_public(string $file = '')
    {
        if (!empty($file)) {
            return MWW_URL . '/public/' . $file;
        }
        return MWW_URL . '/public/';
    }
}

/**
 *  Wrapper for method include in Template class
 *
 *  @param string $file Name of the view to load
 *  @param array $data Data to be passed to the view
 */
if (!function_exists('include_view')) {
    function include_view(string $file, array $data = [])
    {
        $template = new MWW\Templating\Template;
        $template->include($file, $data);
    }
}

/**
*   Return wether string ends with string
*
*   @param $haystack full string
*   @param $needle checks if haystack ends in needle
*   @return bool
*/
if (!function_exists('endsWith')) {
    function endsWith(string $haystack, string $needle) {
        return substr($haystack,-strlen($needle))===$needle;
    }
}


/**
 * Return a instance of a class from the container, or the container itself.
 *
 * @param null $slug_or_class
 * @return mixed|MWW_Container
 */
if (!function_exists('mww')) {
    function mww($slug_or_class = null)
    {
        if ($slug_or_class === null) {
            return MWW_Container::init();
        } else {
            return MWW_Container::init()->make($slug_or_class);
        }
    }
}

/**
 * Registers a class into the DI container as a factory
 *
 * @param $slug
 * @param $class
 * @param array|null $after_build_methods
 */
if (!function_exists('mww_register')) {
    function mww_register($slug, $class, array $after_build_methods = null)
    {
        MWW_Container::init()->bind($slug, $class, $after_build_methods);
    }
}

/**
 * Registers a class into the DI container as singleton
 *
 * @param $slug
 * @param $class
 * @param array|null $after_build_methods
 */
if (!function_exists('mww_singleton')) {
    function mww_singleton($slug, $class, array $after_build_methods = null)
    {
        MWW_Container::init()->singleton($slug, $class, $after_build_methods);
    }
}

/**
 * Bulk register classes with mww_singleton
 *
 * @param array $singletons
 */
if (!function_exists('mww_singletons')) {
    function mww_singletons(array $singletons)
    {
        foreach ($singletons as $slug => $class) {
            mww_singleton($slug, $class);
        }
    }
}
/**
 * Bulk register classes with mww_register
 *
 * @param array $registers
 */
if (!function_exists('mww_registers')) {
    function mww_registers(array $registers)
    {
        foreach ($registers as $slug => $class) {
            mww_register($slug, $class);
        }
    }
}