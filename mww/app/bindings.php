<?php
/**
 * Dependency Injection Container bindings
 */

use App\Bootstrap;
use MWW\Assets\EnqueueScripts;
use MWW\Routing\RouteConditional;
use MWW\Routing\Router;
use MWW\Shortcodes\ShortcodesRegistrar;
use MWW\Support\Setup;
use MWW\Templating\Template;

mww_singleton('app.bootstrap', Bootstrap::class);
mww_singleton('mww.assets', EnqueueScripts::class);
mww_singleton('mww.router', Router::class);
mww_singleton('mww.routing.conditional', RouteConditional::class);
mww_singleton('mww.setup', Setup::class);
mww_singleton('mww.shortcodes.registrar', ShortcodesRegistrar::class);
mww_singleton('mww.template', Template::class);