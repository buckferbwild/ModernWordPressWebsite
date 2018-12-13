<?php
/**
 * Dependency Injection Container bindings
 */
mww_singletons([
    'app.bootstrap'            => 'App\Bootstrap',
    'mww.assets'               => 'MWW\Assets\EnqueueScripts',
    'mww.router'               => 'MWW\Routing\Router',
    'mww.routing.conditional'  => 'MWW\Routing\RouteConditional',
    'mww.setup'                => 'MWW\Support\Setup',
    'mww.shortcodes.registrar' => 'MWW\Shortcodes\ShortcodesRegistrar',
    'mww.template'             => 'MWW\Templating\Template',
]);