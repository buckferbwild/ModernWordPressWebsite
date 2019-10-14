<?php
/** Bindings for dependency injection container */

use MWW\DI\Container;
use MWW\Routing\Condition;
use MWW\Templates\Template;

Container::singleton( Template::class, Template::class );
Container::bind( Condition::class, Condition::class );
