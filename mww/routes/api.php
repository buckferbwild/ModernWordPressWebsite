<?php
/**
 *  Routes in this file are processed as soon as the mu-plugin is loaded
 *  It means there's little WordPress overhead in these routes, but not
 *  everything is available. Ideal for APIs.
 */

use MWW\Router;

Router::add('GET', '/myapi', function() {
    echo 'API with little overhead!';
});

Router::add('GET', '/myapi/{id:i}', function($id) {
    echo 'Request ID: '.$id;
});
