<?php

use MWW\Router;

/**
 * Routes to be interpreted right away
 */
Router::singleton()->add('GET', '/myapi', function() {
    echo 'This has run with small WordPress overhead';
});

//Router::singleton()->add('GET', '/', ['App\Pages\HomeController', 'index']);

/**
 * Routes to be interpreted at wp hook.
 * At this point, all plugins have loaded.
 */
add_action('wp', function() {
    Router::singleton()->add('GET', '/', ['\App\Pages\HomeController', 'index']);
});

/**
 * You can also use WordPress's Conditional Tags for routing
 *
 * First param is a valid WordPress Conditional Tag. Example: is_front_page, is_single, etc
 * @see https://codex.wordpress.org/Conditional_Tags
 *
 * Second param is the output.
 * Output can be generated by a class method, a closure or a function.
 *
 * Generate output by a Class Method:
 * @example Router::singleton()->conditional('is_front_page', ['App\Pages\HomeController', 'index']);
 *          Will call App\Pages\HomeController->index();
 *
 * Generate output by Closure:
 * @example Router::singleton()->conditional('is_front_page', function() {
 *              echo 'Home';
 *          });
 *          Will echo 'Home';
 *
 * Generate output by Function:
 * @example Router::singleton()->conditional('is_front_page', 'doSomething');
 *          Will call function doSomething()
 *
 */
add_action('parse_query', function() {
    // Add Routes using Conditional tags here
});