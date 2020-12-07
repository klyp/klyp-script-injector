<?php

// See if wordpress is properly installed
defined( 'ABSPATH' ) || die( 'Wordpress is not installed properly.' );

/**
 * Create menu under settings
 * 
 * @return void
 */
function klyp_script_injector_menu() {
    add_options_page( 'Klyp Script Injector', 'Klyp Script Injector', 'manage_options', 'klyp-script-injector', 'klyp_script_injector_options' );
}
add_action( 'admin_menu', 'klyp_script_injector_menu' );


/**
 * Register Plugin settings
 * 
 * @return void
 */
function register_klyp_script_injector() {
    //register our settings
    define('SCRIPT_GROUP', 'klyp-script-injector-scripts');
    register_setting(SCRIPT_GROUP, 'klyp_head');
    register_setting(SCRIPT_GROUP, 'klyp_body');
    register_setting(SCRIPT_GROUP, 'klyp_footer');
}
add_action( 'admin_init', 'register_klyp_script_injector' );
