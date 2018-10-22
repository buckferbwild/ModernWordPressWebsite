<?php
/*
 * Plugin Name: Modern WordPress Website
 * Plugin URI: https://github.com/Luc45/Modern-WordPress-Website
 * Description: This project is using Modern WordPress Website (MWW).
 * Version: 1.0
 * Author: Lucas Bustamante
 * Author URI: https://www.lucasbustamante.com.br
 * License: GPL2
 */

/** This file contains the bare-minimum code to run the Core class. */
use MWW\Core;

/**
 *  Subfolder in mu-plugins folder that holds the project.
 *  @see https://codex.wordpress.org/Must_Use_Plugins
 */
define('MWW_FOLDER', '/mww');
define('MWW_PATH', __DIR__ . MWW_FOLDER);
define('MWW_URL', plugin_dir_url(__FILE__) . MWW_FOLDER);

/** Register the Autoloader */
if (file_exists(MWW_PATH .'/vendor/autoload.php')) {
    require_once(MWW_PATH .'/vendor/autoload.php');
} else {
    throw new Exception('You need to run "composer update" in the following folder "' . MWW_PATH . '" to get started.');
}

/** Run the Application */
$mww = new Core();
$mww->run();
