<?php
/**
 *  We use lucatume/wp-routes here
 *  Documentantion and how-to-use:
 *  @see https://github.com/lucatume/wp-routes
 */
klein_respond('GET', '/custom-routes-example', function() {
    echo 'This is echoing!';
});
