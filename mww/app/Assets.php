<?php

use MWW\Assets;

/**
 * Enqueues application's CSS
 */
function enqueueAppCss()
{
    Assets::enqueueStyle('style.css');
}
add_action('wp_enqueue_scripts', 'enqueueAppCss');

/**
 * Enqueues application's JavaScripts
 */
function enqueueAppJs()
{
    Assets::enqueueJavascript('main.js');
}
add_action('wp_enqueue_scripts', 'enqueueAppJs');
