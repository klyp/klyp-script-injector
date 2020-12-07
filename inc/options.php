<?php

// See if wordpress is properly installed
defined( 'ABSPATH' ) || die( 'Wordpress is not installed properly.' );

/**
 * Create the option page
 * 
 * @return void
 */
function klyp_script_injector_options() {
    // Display options page
    require( KLYP_SCRIPT_INJECTOR_INC_PATH . 'options/index.php' );
}