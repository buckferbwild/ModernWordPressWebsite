<?php
/**
 * Enqueues application's CSS and JS
 */
add_action('wp_enqueue_scripts', function() use ($assets)
{
    /**
    *   CSS
    *   Files should be in public/css
    */
    $assets->enqueueStyle('style.css');

    /**
    *   JavaScript
    *   Files should be in public/js
    */
    $assets->enqueueJavascript('main.js');
});
