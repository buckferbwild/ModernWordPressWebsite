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

use MWW\Container\MWW_Container;

/**
 *  Subfolder in mu-plugins folder that holds the project.
 *  @see https://codex.wordpress.org/Must_Use_Plugins
 */
define('MWW_FOLDER', '/mww');
define('MWW_PATH', __DIR__ . MWW_FOLDER);
define('MWW_URL', plugin_dir_url(__FILE__) . MWW_FOLDER);

/** Registers Composer Autoloader */
if (file_exists(MWW_PATH .'/vendor/autoload.php')) {
    require_once(MWW_PATH .'/vendor/autoload.php');
} else {
    throw new Exception('You need to run "composer update" in the following folder "' . MWW_PATH . '" to get started.');
}

/** Registers helper functions */
require_once(MWW_PATH . '/framework/Support/helpers.php');

/** Initializes the Container and registers app/bindings.php */
MWW_Container::init()->registerBindings();

/** Yahoo! */
mww('app.bootstrap')->run();