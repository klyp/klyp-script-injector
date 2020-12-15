<?php

// See if wordpress is properly installed
defined( 'ABSPATH' ) || die( 'Wordpress is not installed properly.' );

global $wp_version;

/**
 * Inject script on header
 * 
 * @return string
 */
function klyp_hook_head() {
    echo get_option('klyp_head');
}
add_action('wp_head', 'klyp_hook_head');

/**
 * Inject script on body for version 5.2.0 and up
 * 
 * @return string
 */
function klyp_hook_body_v5() {
    echo get_option( 'klyp_body' );
}

/**
 * Inject script on body for version less than 5.2.0
 * 
 * @return string
 */
function klyp_hook_body( $classes ) {
    $classes[] = '">' . get_option( 'klyp_body' ) . '<noscript></noscript novar="';

    return $classes;
}

// making sure backward compatible
if ($wp_version > 5.2) {
    add_filter('wp_body_open', 'klyp_hook_body_v5', PHP_INT_MAX);
} else {
    add_filter('body_class', 'klyp_hook_body', PHP_INT_MAX);
}

/**
 * Inject script on footer
 * 
 * @return string
 */
function klyp_hook_footer() {
    echo get_option( 'klyp_footer' );
}
add_action('wp_footer', 'klyp_hook_footer');