<?php

/**
 * If a route is not found here, it continues to WordPress regular loading process
 */

use MWW\Router;

Router::singleton()->add('GET', '/', ['App\Pages\HomeController', 'index']);

Router::singleton()->add('GET', '/test', function() {
    echo 'Test!';
});
