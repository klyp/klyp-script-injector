<?php
/**
 * Plugin Name: Klyp Script Injector
 * Plugin URI: https://github.com/klyp/klyp-script-injector
 * Description: This plugin allows you to inject scripts on header, body and footer.
 * Version: 1.0.0
 * Author: Klyp
 * Author URI: https://klyp.co
 * License: GPL2
 */

// See if wordpress is properly installed
defined( 'ABSPATH' ) || die( 'Wordpress is not installed properly.' );

// Defines
define( 'KLYP_SCRIPT_INJECTOR_FILE'     , __FILE__ );
define( 'KLYP_SCRIPT_INJECTOR_PATH'     , realpath( plugin_dir_path( KLYP_SCRIPT_INJECTOR_FILE ) ) . '/' );
define( 'KLYP_SCRIPT_INJECTOR_INC_PATH' , realpath( KLYP_SCRIPT_INJECTOR_PATH . 'inc/' ) . '/' );

// Required files
require( KLYP_SCRIPT_INJECTOR_INC_PATH . 'settings.php' );
require( KLYP_SCRIPT_INJECTOR_INC_PATH . 'options.php' );
require( KLYP_SCRIPT_INJECTOR_INC_PATH . 'functions.php' );
