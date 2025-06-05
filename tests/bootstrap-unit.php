<?php
/**
 * PHPUnit bootstrap file for Unit Tests only
 * This bootstrap does NOT load WordPress - it uses simple mocks instead
 */

// Composer autoloader
if (file_exists(dirname(__DIR__) . '/vendor/autoload.php')) {
    require_once dirname(__DIR__) . '/vendor/autoload.php';
}

// We'll use simple function mocking instead of Brain Monkey to avoid conflicts

// Define WordPress constants that our plugin expects
if (!defined('ABSPATH')) {
    define('ABSPATH', '/fake/wordpress/path/');
}

if (!defined('GF_ELEMENTOR_WIDGET_VERSION')) {
    define('GF_ELEMENTOR_WIDGET_VERSION', '1.0.2');
}

if (!defined('GF_ELEMENTOR_WIDGET_MINIMUM_ELEMENTOR_VERSION')) {
    define('GF_ELEMENTOR_WIDGET_MINIMUM_ELEMENTOR_VERSION', '3.0.0');
}

if (!defined('GF_ELEMENTOR_WIDGET_MINIMUM_PHP_VERSION')) {
    define('GF_ELEMENTOR_WIDGET_MINIMUM_PHP_VERSION', '7.4');
}

// Load our mock classes
require_once dirname(__DIR__) . '/tests/mocks/elementor-mock.php';
require_once dirname(__DIR__) . '/tests/mocks/gravity-forms-mock.php';

// Mock essential WordPress functions that our plugin uses
if (!function_exists('did_action')) {
    function did_action($hook) {
        // For testing, we'll control this via global variables
        global $mock_did_action_results;
        if (isset($mock_did_action_results[$hook])) {
            return $mock_did_action_results[$hook];
        }
        return false; // Default to false for testing missing dependencies
    }
}

if (!function_exists('esc_html__')) {
    function esc_html__($text, $domain = 'default') {
        return $text; // Just return the text for testing
    }
}

if (!function_exists('esc_html')) {
    function esc_html($text) {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('plugins_url')) {
    function plugins_url($path = '', $plugin = '') {
        return 'http://example.com/wp-content/plugins/' . $path;
    }
}

if (!function_exists('add_action')) {
    function add_action($hook, $function, $priority = 10, $accepted_args = 1) {
        global $mock_actions;
        if (!isset($mock_actions[$hook])) {
            $mock_actions[$hook] = [];
        }
        $mock_actions[$hook][] = $function;
        return true;
    }
}

if (!function_exists('wp_register_style')) {
    function wp_register_style($handle, $src, $deps = array(), $ver = false, $media = 'all') {
        global $mock_registered_styles;
        $mock_registered_styles[$handle] = ['src' => $src, 'deps' => $deps, 'ver' => $ver];
        return true;
    }
}

if (!function_exists('wp_enqueue_style')) {
    function wp_enqueue_style($handle, $src = '', $deps = array(), $ver = false, $media = 'all') {
        global $mock_enqueued_styles;
        $mock_enqueued_styles[] = $handle;
        return true;
    }
}

if (!function_exists('printf')) {
    function printf($format, ...$args) {
        echo sprintf($format, ...$args);
    }
}

if (!function_exists('sprintf')) {
    function sprintf($format, ...$args) {
        return \sprintf($format, ...$args);
    }
}

if (!function_exists('is_wp_error')) {
    function is_wp_error($thing) {
        return false; // For testing, assume no WP errors
    }
}

if (!function_exists('implode')) {
    function implode($glue, $pieces) {
        return \implode($glue, $pieces);
    }
}

// Load plugin functions by including the main file but preventing initialization
$plugin_file = dirname(__DIR__) . '/index.php';
if (file_exists($plugin_file)) {
    // Read the plugin file content
    $plugin_content = file_get_contents($plugin_file);

    // Remove the initialization calls to prevent them from running
    $plugin_content = preg_replace('/add_action\s*\(\s*[\'"]plugins_loaded[\'"].*?\);/s', '// Removed for testing', $plugin_content);

    // Remove constant definitions to prevent redefinition warnings
    $plugin_content = preg_replace('/define\s*\(\s*[\'"]GF_ELEMENTOR_WIDGET_.*?\);/s', '// Constant already defined', $plugin_content);

    // Evaluate the modified content to load functions
    eval('?>' . $plugin_content);
}
