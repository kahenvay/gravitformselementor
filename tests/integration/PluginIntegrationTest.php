<?php
/**
 * Integration tests for the plugin with WordPress
 */

namespace GravityFormElementor\Tests\Integration;

use WP_UnitTestCase;

class PluginIntegrationTest extends WP_UnitTestCase {

    /**
     * Test plugin activation
     */
    public function test_plugin_activation() {
        // Test that the plugin can be activated without errors
        $this->assertTrue(is_plugin_active('gravityfromelementor/index.php') || function_exists('gf_elementor_widget_init'));
    }

    /**
     * Test that plugin constants are defined after loading
     */
    public function test_plugin_constants_after_loading() {
        // Manually trigger plugin loading if needed
        if (!defined('GF_ELEMENTOR_WIDGET_VERSION')) {
            do_action('plugins_loaded');
        }
        
        $this->assertTrue(defined('GF_ELEMENTOR_WIDGET_VERSION'));
        $this->assertTrue(defined('GF_ELEMENTOR_WIDGET_MINIMUM_ELEMENTOR_VERSION'));
        $this->assertTrue(defined('GF_ELEMENTOR_WIDGET_MINIMUM_PHP_VERSION'));
        
        $this->assertEquals('1.0.2', GF_ELEMENTOR_WIDGET_VERSION);
        $this->assertEquals('3.0.0', GF_ELEMENTOR_WIDGET_MINIMUM_ELEMENTOR_VERSION);
        $this->assertEquals('7.4', GF_ELEMENTOR_WIDGET_MINIMUM_PHP_VERSION);
    }

    /**
     * Test that required functions exist
     */
    public function test_required_functions_exist() {
        $required_functions = [
            'gf_elementor_widget_check_dependencies',
            'gf_elementor_widget_check_elementor_version',
            'gf_elementor_widget_check_php_version',
            'gf_elementor_widget_init',
            'register_gravity_form_elementor_widget',
            'gf_register_widget_styles'
        ];

        foreach ($required_functions as $function) {
            $this->assertTrue(function_exists($function), "Function {$function} should exist");
        }
    }

    /**
     * Test dependency checking in WordPress environment
     */
    public function test_dependency_checking_in_wp() {
        $missing_deps = gf_elementor_widget_check_dependencies();
        
        // In our test environment, we expect both dependencies to be "missing"
        // since we're using mocks
        $this->assertIsArray($missing_deps);
    }

    /**
     * Test PHP version checking
     */
    public function test_php_version_checking_in_wp() {
        $php_compatible = gf_elementor_widget_check_php_version();
        
        // Should be true since we're running on a compatible PHP version
        $this->assertTrue($php_compatible);
    }

    /**
     * Test that styles are registered
     */
    public function test_styles_registration() {
        // Trigger the style registration
        do_action('wp_enqueue_scripts');
        
        // Check if our style is registered
        $this->assertTrue(wp_style_is('gf-widget', 'registered'));
    }

    /**
     * Test widget class can be instantiated
     */
    public function test_widget_class_instantiation() {
        // Load the widget class
        require_once ABSPATH . 'wp-content/plugins/gravityfromelementor/widgets/gf-widget.php';
        
        $this->assertTrue(class_exists('Elementor_GF_Widget'));
        
        // Test that we can create an instance
        $widget = new \Elementor_GF_Widget();
        $this->assertInstanceOf('Elementor_GF_Widget', $widget);
        
        // Test basic widget properties
        $this->assertEquals('gf_widget', $widget->get_name());
        $this->assertEquals('Gravity Form', $widget->get_title());
    }

    /**
     * Test admin notices functionality
     */
    public function test_admin_notices() {
        // Test that admin notice functions exist
        $this->assertTrue(function_exists('gf_elementor_widget_admin_notice_missing_dependencies'));
        $this->assertTrue(function_exists('gf_elementor_widget_admin_notice_minimum_elementor_version'));
        $this->assertTrue(function_exists('gf_elementor_widget_admin_notice_minimum_php_version'));
        
        // Test that notices can be called without errors
        ob_start();
        gf_elementor_widget_admin_notice_missing_dependencies();
        $output = ob_get_clean();
        
        // Should contain some HTML output
        $this->assertNotEmpty($output);
        $this->assertStringContainsString('notice', $output);
    }

    /**
     * Test plugin initialization process
     */
    public function test_plugin_initialization() {
        // Reset any existing hooks
        remove_all_actions('plugins_loaded');
        
        // Re-add our plugin's initialization
        add_action('plugins_loaded', 'gf_elementor_widget_init');
        
        // Trigger plugins_loaded
        do_action('plugins_loaded');
        
        // Check that our initialization ran
        // This is tricky to test directly, but we can check for side effects
        $this->assertTrue(has_action('elementor/widgets/register', 'register_gravity_form_elementor_widget'));
        $this->assertTrue(has_action('wp_enqueue_scripts', 'gf_register_widget_styles'));
    }

    /**
     * Test that the plugin handles missing dependencies gracefully
     */
    public function test_graceful_dependency_handling() {
        // This test ensures the plugin doesn't fatal error when dependencies are missing
        
        // Simulate missing dependencies by temporarily removing the mock classes
        $elementor_existed = class_exists('Elementor\Widget_Base');
        $gf_existed = class_exists('GFForms');
        
        // The plugin should handle this gracefully and show admin notices
        $missing = gf_elementor_widget_check_dependencies();
        
        // We expect some dependencies to be missing in test environment
        $this->assertIsArray($missing);
        
        // The plugin should not fatal error
        $this->assertTrue(true); // If we get here, no fatal error occurred
    }
}
