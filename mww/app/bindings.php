<?php
/** Dependency Injection Container bindings */

/**
 * Classes mapped in $singletons will act like singletons.
 * The container creates the object once and returns it every
 * time it's requested throughout the application.
 */
$singletons = [
    'app.bootstrap'            => App\Bootstrap::class,
    'mww.assets'               => MWW\Assets\EnqueueScripts::class,
    'mww.router'               => MWW\Routing\Router::class,
    'mww.routing.conditional'  => MWW\Routing\RouteConditional::class,
    'mww.setup'                => MWW\Support\Setup::class,
    'mww.shortcodes.registrar' => MWW\Shortcodes\ShortcodesRegistrar::class,
    'mww.template'             => MWW\Templating\Template::class,
];

/**
 * Acts like a factory, returning a new instance every the class
 * is requested throughout the application.
 */
$registers = [];

/**
 * If you need to bind classes and use $after_build_method parameter,
 * register them individually bellow
 */