<?php
/** Bindings for dependency injection container */

use App\Bootstrap;

use MWW\DI\Container;
use MWW\Support\Setup;
use MWW\Routing\Router;
use MWW\Assets\EnqueueScripts;
use MWW\Routing\RouteConditional;
use MWW\Shortcodes\ShortcodesRegistrar;

/** App */
Container::singleton( Bootstrap::class, Bootstrap::class );

/** MWW */
Container::singleton( Setup::class, Setup::class );
Container::singleton( Router::class, Router::class );
Container::singleton( EnqueueScripts::class, EnqueueScripts::class );
Container::singleton( RouteConditional::class, RouteConditional::class );
Container::singleton( ShortcodesRegistrar::class, ShortcodesRegistrar::class );
