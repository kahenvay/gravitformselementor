<?php
/**
 * Basic unit tests to verify testing setup works
 */

namespace GravityFormElementor\Tests\Unit;

use PHPUnit\Framework\TestCase;

class BasicTest extends TestCase {

    protected function setUp(): void {
        parent::setUp();
    }

    protected function tearDown(): void {
        parent::tearDown();
    }

    /**
     * Test that PHPUnit is working
     */
    public function test_phpunit_works() {
        $this->assertTrue(true);
        $this->assertEquals(2, 1 + 1);
        $this->assertIsString('hello world');
    }

    /**
     * Test that WordPress function mocks are working
     */
    public function test_wordpress_mocks_work() {
        // Test our simple WordPress function mocks
        $result = esc_html__('Test String', 'textdomain');
        $this->assertEquals('Test String', $result);

        $url = plugins_url('test.css');
        $this->assertStringContainsString('wp-content/plugins', $url);
    }

    /**
     * Test plugin constants are defined
     */
    public function test_plugin_constants_defined() {
        $this->assertTrue(defined('GF_ELEMENTOR_WIDGET_VERSION'));
        $this->assertTrue(defined('GF_ELEMENTOR_WIDGET_MINIMUM_ELEMENTOR_VERSION'));
        $this->assertTrue(defined('GF_ELEMENTOR_WIDGET_MINIMUM_PHP_VERSION'));
        
        // Test the actual values
        $this->assertEquals('1.0.2', GF_ELEMENTOR_WIDGET_VERSION);
        $this->assertEquals('3.0.0', GF_ELEMENTOR_WIDGET_MINIMUM_ELEMENTOR_VERSION);
        $this->assertEquals('7.4', GF_ELEMENTOR_WIDGET_MINIMUM_PHP_VERSION);
    }

    /**
     * Test mock classes are available
     */
    public function test_mock_classes_available() {
        // Test Elementor mocks
        $this->assertTrue(class_exists('Elementor\Widget_Base'));
        $this->assertTrue(class_exists('Elementor\Controls_Manager'));
        
        // Test Gravity Forms mocks
        $this->assertTrue(class_exists('GFForms'));
        $this->assertTrue(class_exists('GFAPI'));
    }

    /**
     * Test GFAPI mock functionality
     */
    public function test_gfapi_mock() {
        $forms = \GFAPI::get_forms();
        
        $this->assertIsArray($forms);
        $this->assertNotEmpty($forms);
        $this->assertArrayHasKey('id', $forms[0]);
        $this->assertArrayHasKey('title', $forms[0]);
    }

    /**
     * Test version comparison functionality
     */
    public function test_version_comparison() {
        // Test PHP version checking logic
        $this->assertTrue(version_compare('8.0', '7.4', '>'));
        $this->assertFalse(version_compare('7.3', '7.4', '>='));
        $this->assertTrue(version_compare('7.4', '7.4', '>='));
    }

    /**
     * Test array operations (common in WordPress plugins)
     */
    public function test_array_operations() {
        $test_array = ['key1' => 'value1', 'key2' => 'value2'];
        
        $this->assertArrayHasKey('key1', $test_array);
        $this->assertEquals('value1', $test_array['key1']);
        $this->assertContains('value2', $test_array);
    }

    /**
     * Test string operations (common in WordPress plugins)
     */
    public function test_string_operations() {
        $test_string = 'Gravity Form Elementor Widget';
        
        $this->assertStringContainsString('Gravity', $test_string);
        $this->assertStringContainsString('Elementor', $test_string);
        $this->assertStringStartsWith('Gravity', $test_string);
        $this->assertStringEndsWith('Widget', $test_string);
    }

    /**
     * Test that we can use WordPress functions
     */
    public function test_wordpress_function_usage() {
        // Test WordPress function mocks
        $this->assertTrue(wp_enqueue_style('test-style'));
        $this->assertTrue(add_action('init', 'my_function'));
        $this->assertTrue(wp_register_style('test', 'test.css'));

        // Test URL generation
        $url = plugins_url('test.css');
        $this->assertStringContainsString('test.css', $url);
    }

    /**
     * Test dependency checking logic (simplified)
     */
    public function test_dependency_checking_logic() {
        // Test the logic we'd use in dependency checking
        $required_plugins = ['Elementor', 'Gravity Forms'];
        $active_plugins = ['Elementor']; // Missing Gravity Forms
        
        $missing = array_diff($required_plugins, $active_plugins);
        
        $this->assertContains('Gravity Forms', $missing);
        $this->assertNotContains('Elementor', $missing);
        $this->assertCount(1, $missing);
    }
}
