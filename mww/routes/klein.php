<?php
/**
 *  We use lucatume/wp-browser here
 *  Documentantion and how-to-use:
 *  @see https://github.com/lucatume/wp-routes
 */
klein_respond('GET', '/example', function() {
    echo 'This is echoing!';
});
