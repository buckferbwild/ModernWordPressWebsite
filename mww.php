<?php
/*
 * Plugin Name: Modern WordPress Website
 * Plugin URI: https://github.com/Luc45/Modern-WordPress-Website
 * Description: This project is using Modern WordPress Website (MWW).
 * Version: 1.0.0
 * Author: Lucas Bustamante
 * Author URI: https://www.lucasbustamante.com.br
 * License: GPL2
 */

use App\Bootstrap;
use MWW\DI\Container;

define( 'MWW_FOLDER', '/mww' );
define( 'MWW_PATH', __DIR__ . MWW_FOLDER );
define( 'MWW_START', microtime( true ) );
define( 'MWW_URL', plugin_dir_url( __FILE__ ) . MWW_FOLDER );

/** Registers Composer Autoloader */
if ( file_exists( MWW_PATH . '/vendor/autoload.php' ) ) {
	require_once( MWW_PATH . '/vendor/autoload.php' );
} else {
	throw new Exception( 'You need to run "composer update" in the following folder "' . MWW_PATH . '" to get started.' );
}

/** Registers helper functions */
require_once( MWW_PATH . '/framework/Support/helpers.php' );

/** Builds the Dependency Injection Container and registers Service Providers */
Container::registerBindings( MWW_PATH . '/framework/bindings.php' );
Container::registerBindings( MWW_PATH . '/app/bindings.php' );

/** Yahoo! */
Container::make( Bootstrap::class )->run();
