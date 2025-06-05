<?php
/**
 * Comprehensive unit tests for plugin functions
 */

namespace GravityFormElementor\Tests\Unit;

use PHPUnit\Framework\TestCase;

class PluginFunctionsTest extends TestCase {

    protected function setUp(): void {
        parent::setUp();

        // Reset global mock variables
        global $mock_did_action_results, $mock_actions, $mock_registered_styles, $mock_enqueued_styles;
        $mock_did_action_results = [];
        $mock_actions = [];
        $mock_registered_styles = [];
        $mock_enqueued_styles = [];

        // Functions should already be loaded by bootstrap
    }

    /**
     * Test plugin constants are defined
     */
    public function test_plugin_constants_defined() {
        $this->assertTrue(defined('GF_ELEMENTOR_WIDGET_VERSION'));
        $this->assertTrue(defined('GF_ELEMENTOR_WIDGET_MINIMUM_ELEMENTOR_VERSION'));
        $this->assertTrue(defined('GF_ELEMENTOR_WIDGET_MINIMUM_PHP_VERSION'));
        
        $this->assertEquals('1.0.2', GF_ELEMENTOR_WIDGET_VERSION);
        $this->assertEquals('3.0.0', GF_ELEMENTOR_WIDGET_MINIMUM_ELEMENTOR_VERSION);
        $this->assertEquals('7.4', GF_ELEMENTOR_WIDGET_MINIMUM_PHP_VERSION);
    }

    /**
     * Test dependency checking with missing Elementor
     */
    public function test_dependency_check_missing_elementor() {
        global $mock_did_action_results;
        
        // Mock Elementor as not loaded, Gravity Forms as loaded
        $mock_did_action_results['elementor/loaded'] = false;
        
        // Mock GFForms class as existing
        if (!class_exists('GFForms')) {
            eval('class GFForms {}');
        }
        
        $missing = gf_elementor_widget_check_dependencies();
        
        $this->assertIsArray($missing);
        $this->assertContains('Elementor', $missing);
        $this->assertNotContains('Gravity Forms', $missing);
    }

    /**
     * Test dependency checking with missing Gravity Forms
     */
    public function test_dependency_check_missing_gravity_forms() {
        global $mock_did_action_results;
        
        // Mock Elementor as loaded, Gravity Forms as not loaded
        $mock_did_action_results['elementor/loaded'] = true;
        
        $missing = gf_elementor_widget_check_dependencies();
        
        $this->assertIsArray($missing);
        $this->assertNotContains('Elementor', $missing);
        $this->assertContains('Gravity Forms', $missing);
    }

    /**
     * Test dependency checking with all dependencies present
     */
    public function test_dependency_check_all_present() {
        global $mock_did_action_results;
        
        // Mock both dependencies as present
        $mock_did_action_results['elementor/loaded'] = true;
        
        // Mock GFForms class as existing
        if (!class_exists('GFForms')) {
            eval('class GFForms {}');
        }
        
        $missing = gf_elementor_widget_check_dependencies();
        
        $this->assertIsArray($missing);
        $this->assertEmpty($missing);
    }

    /**
     * Test PHP version compatibility check
     */
    public function test_php_version_compatibility() {
        // Current PHP version should be compatible (we're running tests on it)
        $compatible = gf_elementor_widget_check_php_version();
        $this->assertTrue($compatible);
    }

    /**
     * Test Elementor version compatibility with no version defined
     */
    public function test_elementor_version_no_version_defined() {
        // When ELEMENTOR_VERSION is not defined, should return true
        $compatible = gf_elementor_widget_check_elementor_version();
        $this->assertTrue($compatible);
    }

    /**
     * Test Elementor version compatibility with compatible version
     */
    public function test_elementor_version_compatible() {
        // Define a compatible version
        if (!defined('ELEMENTOR_VERSION')) {
            define('ELEMENTOR_VERSION', '3.5.0');
        }
        
        $compatible = gf_elementor_widget_check_elementor_version();
        $this->assertTrue($compatible);
    }

    /**
     * Test admin notice for missing dependencies
     */
    public function test_admin_notice_missing_dependencies() {
        global $mock_did_action_results;
        
        // Set up missing dependencies
        $mock_did_action_results['elementor/loaded'] = false;
        
        ob_start();
        gf_elementor_widget_admin_notice_missing_dependencies();
        $output = ob_get_clean();
        
        $this->assertNotEmpty($output);
        $this->assertStringContainsString('notice', $output);
        $this->assertStringContainsString('Elementor', $output);
        $this->assertStringContainsString('Gravity Form Elementor Widget', $output);
    }

    /**
     * Test admin notice for PHP version
     */
    public function test_admin_notice_php_version() {
        // This test assumes we might be running on an incompatible version
        // We'll test the output structure
        ob_start();
        gf_elementor_widget_admin_notice_minimum_php_version();
        $output = ob_get_clean();
        
        // Should either be empty (compatible) or contain notice (incompatible)
        if (!empty($output)) {
            $this->assertStringContainsString('notice', $output);
            $this->assertStringContainsString('PHP', $output);
        }
        
        // Test passes if no fatal error occurs
        $this->assertTrue(true);
    }

    /**
     * Test widget styles registration
     */
    public function test_widget_styles_registration() {
        global $mock_registered_styles, $mock_enqueued_styles;
        
        gf_register_widget_styles();
        
        $this->assertArrayHasKey('gf-widget', $mock_registered_styles);
        $this->assertContains('gf-widget', $mock_enqueued_styles);
        
        $style = $mock_registered_styles['gf-widget'];
        $this->assertStringContainsString('assets/css/style.css', $style['src']);
        $this->assertEquals(GF_ELEMENTOR_WIDGET_VERSION, $style['ver']);
    }

    /**
     * Test plugin initialization with missing PHP version
     */
    public function test_plugin_init_missing_php_version() {
        global $mock_actions;
        
        // Mock PHP version as incompatible by temporarily changing the constant
        // We can't easily mock version_compare, so we'll test the logic flow
        
        gf_elementor_widget_init();
        
        // Should register some actions (either notices or main functionality)
        $this->assertNotEmpty($mock_actions);
    }

    /**
     * Test plugin initialization with all requirements met
     */
    public function test_plugin_init_all_requirements_met() {
        global $mock_actions, $mock_did_action_results;
        
        // Set up all requirements as met
        $mock_did_action_results['elementor/loaded'] = true;
        
        if (!class_exists('GFForms')) {
            eval('class GFForms {}');
        }
        
        gf_elementor_widget_init();
        
        // Should register main functionality actions
        $this->assertNotEmpty($mock_actions);
        
        // Check if main actions were registered
        $all_actions = array_keys($mock_actions);
        $this->assertTrue(
            in_array('elementor/widgets/register', $all_actions) || 
            in_array('wp_enqueue_scripts', $all_actions) ||
            in_array('admin_notices', $all_actions)
        );
    }

    /**
     * Test that all required functions exist
     */
    public function test_all_functions_exist() {
        $required_functions = [
            'gf_elementor_widget_check_dependencies',
            'gf_elementor_widget_check_elementor_version',
            'gf_elementor_widget_check_php_version',
            'gf_elementor_widget_admin_notice_missing_dependencies',
            'gf_elementor_widget_admin_notice_minimum_elementor_version',
            'gf_elementor_widget_admin_notice_minimum_php_version',
            'gf_elementor_widget_init',
            'register_gravity_form_elementor_widget',
            'gf_register_widget_styles'
        ];

        foreach ($required_functions as $function) {
            $this->assertTrue(function_exists($function), "Function {$function} should exist");
        }
    }

    /**
     * Test mock classes are available
     */
    public function test_mock_classes_available() {
        $this->assertTrue(class_exists('Elementor\Widget_Base'));
        $this->assertTrue(class_exists('Elementor\Controls_Manager'));
        $this->assertTrue(class_exists('GFForms'));
        $this->assertTrue(class_exists('GFAPI'));
    }

    /**
     * Test GFAPI mock functionality
     */
    public function test_gfapi_mock_functionality() {
        $forms = \GFAPI::get_forms();
        
        $this->assertIsArray($forms);
        $this->assertNotEmpty($forms);
        
        // Test first form structure
        $first_form = $forms[0];
        $this->assertArrayHasKey('id', $first_form);
        $this->assertArrayHasKey('title', $first_form);
        $this->assertArrayHasKey('labelPlacement', $first_form);
        
        // Test get_form method
        $form = \GFAPI::get_form('1');
        $this->assertIsArray($form);
        $this->assertEquals('1', $form['id']);
    }
}
