<?php
/**
 *  Routes in this file are processed as soon as at "shutdown" hook, so
 *  we give time for WordPress and it's plugins to process everything they
 *  want to process.
 *
 *  If a route is not found here, it continues with WordPress regular loading process.
 */

use MWW\Router;

Router::singleton()->add('GET', '/', ['App\Pages\HomeController', 'index']);

Router::singleton()->add('GET', '/test', function() {
    echo 'Test!';
});
