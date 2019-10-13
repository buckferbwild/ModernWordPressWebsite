<?php
/** Bindings for dependency injection container */

use App\Bootstrap;

use MWW\DI\Container;
use MWW\Templates\Template;
use App\Templates\Pages\Page;

Container::singleton( Template::class, Template::class );
