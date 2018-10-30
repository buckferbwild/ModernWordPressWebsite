<?php

use MWW\Frontend;

/**
 * Enqueues application's CSS
 */
function enqueueAppCss()
{
    Frontend::enqueueStyle('style.css');
}
add_action('wp_enqueue_scripts', 'enqueueAppCss');

/**
 * Enqueues application's JavaScripts
 */
function enqueueAppJs()
{
    Frontend::enqueueJavascript('main.js');
}
add_action('wp_enqueue_scripts', 'enqueueAppJs');
