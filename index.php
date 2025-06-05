<?php
/**
 * Plugin Name: Gravity Form Elementor Widget
 * Description: Adds a customisable widget for Gravity Forms
 * Version:     1.0.2
 * Author:      Impact Hub
 * Author URI:  https://impacthub.net
 * Text Domain: elementor-addon
 * Requires Plugins: elementor, gravityforms
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants
define( 'GF_ELEMENTOR_WIDGET_VERSION', '1.0.2' );
define( 'GF_ELEMENTOR_WIDGET_MINIMUM_ELEMENTOR_VERSION', '3.0.0' );
define( 'GF_ELEMENTOR_WIDGET_MINIMUM_PHP_VERSION', '7.4' );

/**
 * Check if required dependencies are active
 */
function gf_elementor_widget_check_dependencies() {
    $missing_dependencies = array();

    // Check if Elementor is active
    if ( ! did_action( 'elementor/loaded' ) ) {
        $missing_dependencies[] = 'Elementor';
    }

    // Check if Gravity Forms is active
    if ( ! class_exists( 'GFForms' ) ) {
        $missing_dependencies[] = 'Gravity Forms';
    }

    return $missing_dependencies;
}

/**
 * Check Elementor version compatibility
 */
function gf_elementor_widget_check_elementor_version() {
    if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, GF_ELEMENTOR_WIDGET_MINIMUM_ELEMENTOR_VERSION, '<' ) ) {
        return false;
    }
    return true;
}

/**
 * Check PHP version compatibility
 */
function gf_elementor_widget_check_php_version() {
    if ( version_compare( PHP_VERSION, GF_ELEMENTOR_WIDGET_MINIMUM_PHP_VERSION, '<' ) ) {
        return false;
    }
    return true;
}

/**
 * Admin notice for missing dependencies
 */
function gf_elementor_widget_admin_notice_missing_dependencies() {
    $missing_dependencies = gf_elementor_widget_check_dependencies();

    if ( ! empty( $missing_dependencies ) ) {
        $message = sprintf(
            /* translators: 1: Plugin name 2: Dependency names */
            esc_html__( '"%1$s" requires the following plugins to be installed and activated: %2$s.', 'elementor-addon' ),
            '<strong>' . esc_html__( 'Gravity Form Elementor Widget', 'elementor-addon' ) . '</strong>',
            '<strong>' . implode( ', ', $missing_dependencies ) . '</strong>'
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%s</p></div>', $message );
    }
}

/**
 * Admin notice for Elementor version compatibility
 */
function gf_elementor_widget_admin_notice_minimum_elementor_version() {
    if ( ! gf_elementor_widget_check_elementor_version() ) {
        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-addon' ),
            '<strong>' . esc_html__( 'Gravity Form Elementor Widget', 'elementor-addon' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'elementor-addon' ) . '</strong>',
            GF_ELEMENTOR_WIDGET_MINIMUM_ELEMENTOR_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%s</p></div>', $message );
    }
}

/**
 * Admin notice for PHP version compatibility
 */
function gf_elementor_widget_admin_notice_minimum_php_version() {
    if ( ! gf_elementor_widget_check_php_version() ) {
        $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-addon' ),
            '<strong>' . esc_html__( 'Gravity Form Elementor Widget', 'elementor-addon' ) . '</strong>',
            '<strong>' . esc_html__( 'PHP', 'elementor-addon' ) . '</strong>',
            GF_ELEMENTOR_WIDGET_MINIMUM_PHP_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%s</p></div>', $message );
    }
}

/**
 * Initialize the plugin
 */
function gf_elementor_widget_init() {
    // Check PHP version
    if ( ! gf_elementor_widget_check_php_version() ) {
        add_action( 'admin_notices', 'gf_elementor_widget_admin_notice_minimum_php_version' );
        return;
    }

    // Check for missing dependencies
    $missing_dependencies = gf_elementor_widget_check_dependencies();
    if ( ! empty( $missing_dependencies ) ) {
        add_action( 'admin_notices', 'gf_elementor_widget_admin_notice_missing_dependencies' );
        return;
    }

    // Check Elementor version
    if ( ! gf_elementor_widget_check_elementor_version() ) {
        add_action( 'admin_notices', 'gf_elementor_widget_admin_notice_minimum_elementor_version' );
        return;
    }

    // All checks passed, initialize the plugin
    add_action( 'elementor/widgets/register', 'register_gravity_form_elementor_widget' );
    add_action( 'wp_enqueue_scripts', 'gf_register_widget_styles' );
}

/**
 * Register the Gravity Form widget with Elementor
 */
function register_gravity_form_elementor_widget( $widgets_manager ) {
    require_once( __DIR__ . '/widgets/gf-widget.php' );
    $widgets_manager->register( new \Elementor_GF_Widget() );
}

/**
 * Register and enqueue widget styles
 */
function gf_register_widget_styles() {
    wp_register_style( 'gf-widget', plugins_url( 'assets/css/style.css', __FILE__ ), array(), GF_ELEMENTOR_WIDGET_VERSION );
    wp_enqueue_style( 'gf-widget' );
}

// Initialize the plugin
add_action( 'plugins_loaded', 'gf_elementor_widget_init' );