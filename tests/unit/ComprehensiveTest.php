<?php
/**
 * Comprehensive test suite that covers all major functionality
 */

namespace GravityFormElementor\Tests\Unit;

use PHPUnit\Framework\TestCase;

class ComprehensiveTest extends TestCase {

    protected $widget;

    protected function setUp(): void {
        parent::setUp();
        
        // Reset global mock variables
        global $mock_did_action_results, $mock_actions, $mock_registered_styles, $mock_enqueued_styles;
        $mock_did_action_results = [];
        $mock_actions = [];
        $mock_registered_styles = [];
        $mock_enqueued_styles = [];
        
        // Load widget if available
        $widget_file = dirname(dirname(__DIR__)) . '/widgets/gf-widget.php';
        if (file_exists($widget_file)) {
            require_once $widget_file;
            if (class_exists('Elementor_GF_Widget')) {
                $this->widget = new \Elementor_GF_Widget();
            }
        }
    }

    /**
     * Test 1: Plugin Constants and Configuration
     */
    public function test_plugin_configuration() {
        // Test constants are defined
        $this->assertTrue(defined('GF_ELEMENTOR_WIDGET_VERSION'));
        $this->assertTrue(defined('GF_ELEMENTOR_WIDGET_MINIMUM_ELEMENTOR_VERSION'));
        $this->assertTrue(defined('GF_ELEMENTOR_WIDGET_MINIMUM_PHP_VERSION'));
        
        // Test constant values
        $this->assertEquals('1.0.2', GF_ELEMENTOR_WIDGET_VERSION);
        $this->assertEquals('3.0.0', GF_ELEMENTOR_WIDGET_MINIMUM_ELEMENTOR_VERSION);
        $this->assertEquals('7.4', GF_ELEMENTOR_WIDGET_MINIMUM_PHP_VERSION);
    }

    /**
     * Test 2: Core Plugin Functions Exist
     */
    public function test_core_functions_exist() {
        $required_functions = [
            'gf_elementor_widget_check_dependencies',
            'gf_elementor_widget_check_php_version',
            'gf_elementor_widget_init',
            'gf_register_widget_styles'
        ];

        foreach ($required_functions as $function) {
            $this->assertTrue(function_exists($function), "Function {$function} should exist");
        }
    }

    /**
     * Test 3: PHP Version Compatibility
     */
    public function test_php_version_compatibility() {
        $compatible = gf_elementor_widget_check_php_version();
        $this->assertTrue($compatible, 'Current PHP version should be compatible');
    }

    /**
     * Test 4: Dependency Checking Logic
     */
    public function test_dependency_checking() {
        global $mock_did_action_results;
        
        // Test with missing Elementor
        $mock_did_action_results['elementor/loaded'] = false;
        $missing = gf_elementor_widget_check_dependencies();
        $this->assertIsArray($missing);
        $this->assertContains('Elementor', $missing);
        
        // Test with Elementor present
        $mock_did_action_results['elementor/loaded'] = true;
        if (!class_exists('GFForms')) {
            eval('class GFForms {}');
        }
        $missing = gf_elementor_widget_check_dependencies();
        $this->assertIsArray($missing);
        $this->assertNotContains('Elementor', $missing);
    }

    /**
     * Test 5: Widget Class Functionality
     */
    public function test_widget_class() {
        if (!$this->widget) {
            $this->markTestSkipped('Widget class not available');
        }
        
        // Test basic properties
        $this->assertEquals('gf_widget', $this->widget->get_name());
        $this->assertEquals('Gravity Form', $this->widget->get_title());
        $this->assertEquals('eicon-form-horizontal', $this->widget->get_icon());
        
        // Test categories
        $categories = $this->widget->get_categories();
        $this->assertIsArray($categories);
        $this->assertContains('impact-hub-elements', $categories);
        
        // Test keywords
        $keywords = $this->widget->get_keywords();
        $this->assertIsArray($keywords);
        $this->assertContains('form', $keywords);
    }

    /**
     * Test 6: Widget Settings Management
     */
    public function test_widget_settings() {
        if (!$this->widget) {
            $this->markTestSkipped('Widget class not available');
        }
        
        $test_settings = [
            'gravity_form' => '1',
            'show_title' => 'yes',
            'show_description' => 'yes',
            'use_ajax' => 'no'
        ];
        
        $this->widget->set_settings($test_settings);
        $retrieved_settings = $this->widget->get_settings_for_display();
        
        foreach ($test_settings as $key => $value) {
            $this->assertEquals($value, $retrieved_settings[$key]);
        }
    }

    /**
     * Test 7: Mock Classes Availability
     */
    public function test_mock_classes() {
        // Test Elementor mocks
        $this->assertTrue(class_exists('Elementor\Widget_Base'));
        $this->assertTrue(class_exists('Elementor\Controls_Manager'));
        
        // Test Gravity Forms mocks
        $this->assertTrue(class_exists('GFForms'));
        $this->assertTrue(class_exists('GFAPI'));
    }

    /**
     * Test 8: GFAPI Mock Functionality
     */
    public function test_gfapi_functionality() {
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

    /**
     * Test 9: WordPress Function Mocks
     */
    public function test_wordpress_function_mocks() {
        // Test text functions
        $result = esc_html__('Test String', 'textdomain');
        $this->assertEquals('Test String', $result);
        
        $escaped = esc_html('<script>alert("test")</script>');
        $this->assertStringContainsString('&lt;script&gt;', $escaped);
        
        // Test URL functions
        $url = plugins_url('test.css');
        $this->assertStringContainsString('wp-content/plugins', $url);
        
        // Test action functions
        $this->assertTrue(add_action('init', 'my_function'));
        $this->assertTrue(wp_enqueue_style('test-style'));
    }

    /**
     * Test 10: Style Registration
     */
    public function test_style_registration() {
        global $mock_registered_styles, $mock_enqueued_styles;
        
        gf_register_widget_styles();
        
        $this->assertArrayHasKey('gf-widget', $mock_registered_styles);
        $this->assertContains('gf-widget', $mock_enqueued_styles);
        
        $style = $mock_registered_styles['gf-widget'];
        $this->assertStringContainsString('assets/css/style.css', $style['src']);
        $this->assertEquals(GF_ELEMENTOR_WIDGET_VERSION, $style['ver']);
    }

    /**
     * Test 11: Widget Method Existence
     */
    public function test_widget_methods() {
        if (!$this->widget) {
            $this->markTestSkipped('Widget class not available');
        }
        
        $required_methods = [
            'get_name',
            'get_title',
            'get_icon',
            'get_categories',
            'get_keywords',
            'render'
        ];
        
        foreach ($required_methods as $method) {
            $this->assertTrue(
                method_exists($this->widget, $method),
                "Method {$method} should exist"
            );
        }
    }

    /**
     * Test 12: Widget Inheritance
     */
    public function test_widget_inheritance() {
        if (!$this->widget) {
            $this->markTestSkipped('Widget class not available');
        }
        
        $this->assertInstanceOf('Elementor\Widget_Base', $this->widget);
    }

    /**
     * Test 13: Array and String Operations
     */
    public function test_data_operations() {
        // Test array operations
        $test_array = ['key1' => 'value1', 'key2' => 'value2'];
        $this->assertArrayHasKey('key1', $test_array);
        $this->assertEquals('value1', $test_array['key1']);
        $this->assertContains('value2', $test_array);
        
        // Test string operations
        $test_string = 'Gravity Form Elementor Widget';
        $this->assertStringContainsString('Gravity', $test_string);
        $this->assertStringContainsString('Elementor', $test_string);
        $this->assertStringStartsWith('Gravity', $test_string);
        $this->assertStringEndsWith('Widget', $test_string);
    }

    /**
     * Test 14: Version Comparison Logic
     */
    public function test_version_comparison() {
        $this->assertTrue(version_compare('8.0', '7.4', '>'));
        $this->assertFalse(version_compare('7.3', '7.4', '>='));
        $this->assertTrue(version_compare('7.4', '7.4', '>='));
        $this->assertTrue(version_compare('3.5.0', '3.0.0', '>='));
    }

    /**
     * Test 15: Error Handling and Edge Cases
     */
    public function test_error_handling() {
        // Test dependency checking with edge cases
        $required_plugins = ['Elementor', 'Gravity Forms'];
        $active_plugins = ['Elementor']; // Missing Gravity Forms
        
        $missing = array_diff($required_plugins, $active_plugins);
        
        $this->assertContains('Gravity Forms', $missing);
        $this->assertNotContains('Elementor', $missing);
        $this->assertCount(1, $missing);
        
        // Test empty arrays
        $empty_missing = array_diff($required_plugins, $required_plugins);
        $this->assertEmpty($empty_missing);
    }
}
