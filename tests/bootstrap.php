<?php
/**
 * PHPUnit bootstrap file for Gravity Form Elementor Widget tests
 */

// Composer autoloader
if (file_exists(dirname(__DIR__) . '/vendor/autoload.php')) {
    require_once dirname(__DIR__) . '/vendor/autoload.php';
}

// WordPress test environment
$_tests_dir = getenv('WP_TESTS_DIR');

if (!$_tests_dir) {
    $_tests_dir = rtrim(sys_get_temp_dir(), '/\\') . '/wordpress-tests-lib';
}

if (!file_exists($_tests_dir . '/includes/functions.php')) {
    echo "Could not find $_tests_dir/includes/functions.php, have you run bin/install-wp-tests.sh ?" . PHP_EOL; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    exit(1);
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin() {
    // Load Elementor (mock or real)
    if (!class_exists('Elementor\Plugin')) {
        require_once dirname(__DIR__) . '/tests/mocks/elementor-mock.php';
    }
    
    // Load Gravity Forms (mock or real)
    if (!class_exists('GFForms')) {
        require_once dirname(__DIR__) . '/tests/mocks/gravity-forms-mock.php';
    }
    
    // Load our plugin
    require dirname(__DIR__) . '/index.php';
}

tests_add_filter('muplugins_loaded', '_manually_load_plugin');

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';

// Initialize Brain Monkey for unit tests
if (class_exists('Brain\Monkey\setUp')) {
    Brain\Monkey\setUp();
}
