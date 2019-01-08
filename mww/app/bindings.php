<?php
/** Bindings for dependency injection container */
use App\Bootstrap;

use MWW\Support\Setup;
use MWW\Routing\Router;
use MWW\Templating\Template;
use MWW\Assets\EnqueueScripts;
use MWW\Routing\RouteConditional;
use MWW\Shortcodes\ShortcodesRegistrar;

/** App */
MWW::singleton(Bootstrap::class, Bootstrap::class);

/** MWW */
MWW::singleton(Setup::class, Setup::class);
MWW::singleton(Router::class, Router::class);
MWW::singleton(Template::class, Template::class);
MWW::singleton(EnqueueScripts::class, EnqueueScripts::class);
MWW::singleton(RouteConditional::class, RouteConditional::class);
MWW::singleton(ShortcodesRegistrar::class, ShortcodesRegistrar::class);
