<?php
/**
 * Plugin Name: Gravity Form Elementor Widget
 * Description: Adds a customisable widget for Gravity Forms
 * Version:     1.0.1
 * Author:      Ulysse Coates
 * Author URI:  https://ulyssecoates.com
 * Text Domain: elementor-addon
 */

add_action( 'elementor/widgets/register', 'register_gravity_form_elementor_widget' );

function register_gravity_form_elementor_widget( $widgets_manager ) {

	require_once( __DIR__ . '/widgets/gf-widget.php' );
	$widgets_manager->register( new \Elementor_GF_Widget() );

}

function gf_register_widget_styles() {
	wp_register_style( 'gf-widget', plugins_url( 'assets/css/style.css', __FILE__ ) );;
    wp_enqueue_style( 'gf-widget', plugins_url( 'assets/css/style.css', __FILE__ ) );

}
add_action( 'wp_enqueue_scripts', 'gf_register_widget_styles' );