<?php

// See if wordpress is properly installed
defined( 'ABSPATH' ) || die( 'Wordpress is not installed properly.' );

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
 * Inject script on body
 * 
 * @return string
 */
function klyp_hook_body( $classes ) {
    $classes[] = '">' . get_option( 'klyp_body' ) . '<noscript></noscript novar="';

    return $classes;
}
add_filter('body_class', 'klyp_hook_body', PHP_INT_MAX);

/**
 * Inject script on footer
 * 
 * @return string
 */
function klyp_hook_footer() {
    echo get_option( 'klyp_footer' );
}
add_action('wp_footer', 'klyp_hook_footer');