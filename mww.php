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

define( 'MWW_FOLDER', trailingslashit( '/mww' ) );
define( 'MWW_URL', trailingslashit( plugin_dir_url( __FILE__ ) . MWW_FOLDER ) );
define( 'MWW_PATH', trailingslashit( __DIR__ . MWW_FOLDER ) );

/** Composer Autoloader */
if ( file_exists( MWW_PATH . 'vendor/autoload.php' ) ) {
	require_once( MWW_PATH . 'vendor/autoload.php' );
} else {
	wp_die( sprintf(
			'<h1>MWW requires one more step to work!</h1><p>Run "<code>composer update</code>" in the MWW folder: <strong>"%s"</strong></p>',
			MWW_PATH )
	);
}

/** Registers helper functions */
require_once( MWW_PATH . 'framework/Support/helpers.php' );

/** Builds the Dependency Injection Container and registers Service Providers */
Container::registerBindings( MWW_PATH . 'framework/bindings.php' );
Container::registerBindings( MWW_PATH . 'app/bindings.php' );

/** Yahoo! */
Container::make( Bootstrap::class )->run();
