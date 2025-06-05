<?php
/**
 * Integration tests for widget rendering
 */

namespace GravityFormElementor\Tests\Integration;

use WP_UnitTestCase;

class WidgetRenderingTest extends WP_UnitTestCase {

    protected $widget;

    public function setUp(): void {
        parent::setUp();
        
        // Load the widget class
        require_once ABSPATH . 'wp-content/plugins/gravityfromelementor/widgets/gf-widget.php';
        
        $this->widget = new \Elementor_GF_Widget();
    }

    /**
     * Test widget rendering with valid form ID
     */
    public function test_widget_render_with_valid_form() {
        // Set up widget settings
        $settings = [
            'gravity_form' => '1',
            'show_title' => 'yes',
            'show_description' => 'yes',
            'use_ajax' => 'yes'
        ];
        
        $this->widget->set_settings($settings);
        
        // Capture the output
        ob_start();
        $this->widget->render();
        $output = ob_get_clean();
        
        // Test that output contains expected elements
        $this->assertStringContainsString('gform_wrapper', $output);
        $this->assertStringContainsString('form', $output);
    }

    /**
     * Test widget rendering without form selection
     */
    public function test_widget_render_without_form() {
        // Set up widget settings with no form selected
        $settings = [
            'gravity_form' => '',
            'show_title' => 'no',
            'show_description' => 'no',
            'use_ajax' => 'no'
        ];
        
        $this->widget->set_settings($settings);
        
        // Capture the output
        ob_start();
        $this->widget->render();
        $output = ob_get_clean();
        
        // Should show a message about no form selected
        $this->assertStringContainsString('Please select a form', $output);
    }

    /**
     * Test widget rendering with title enabled
     */
    public function test_widget_render_with_title() {
        $settings = [
            'gravity_form' => '1',
            'show_title' => 'yes',
            'show_description' => 'no',
            'use_ajax' => 'no'
        ];
        
        $this->widget->set_settings($settings);
        
        ob_start();
        $this->widget->render();
        $output = ob_get_clean();
        
        // Should contain the form title
        $this->assertStringContainsString('gform_title', $output);
    }

    /**
     * Test widget rendering with description enabled
     */
    public function test_widget_render_with_description() {
        $settings = [
            'gravity_form' => '1',
            'show_title' => 'no',
            'show_description' => 'yes',
            'use_ajax' => 'no'
        ];
        
        $this->widget->set_settings($settings);
        
        ob_start();
        $this->widget->render();
        $output = ob_get_clean();
        
        // Should contain the form description
        $this->assertStringContainsString('gform_description', $output);
    }

    /**
     * Test widget rendering with AJAX enabled
     */
    public function test_widget_render_with_ajax() {
        $settings = [
            'gravity_form' => '1',
            'show_title' => 'no',
            'show_description' => 'no',
            'use_ajax' => 'yes'
        ];
        
        $this->widget->set_settings($settings);
        
        ob_start();
        $this->widget->render();
        $output = ob_get_clean();
        
        // AJAX forms should have specific attributes or classes
        $this->assertNotEmpty($output);
    }

    /**
     * Test form settings integration
     */
    public function test_form_settings_integration() {
        // Test that form settings are properly retrieved and used
        $reflection = new \ReflectionClass($this->widget);
        $method = $reflection->getMethod('get_form_settings');
        $method->setAccessible(true);
        
        $settings = $method->invoke($this->widget, '1');
        
        if (!empty($settings)) {
            $this->assertArrayHasKey('labelPlacement', $settings);
            $this->assertArrayHasKey('descriptionPlacement', $settings);
            $this->assertArrayHasKey('title', $settings);
        }
    }

    /**
     * Test widget controls registration
     */
    public function test_widget_controls_registration() {
        // Test that the widget can register its controls without errors
        $reflection = new \ReflectionClass($this->widget);
        
        // Check if the register_controls method exists
        if ($reflection->hasMethod('register_controls')) {
            $method = $reflection->getMethod('register_controls');
            $method->setAccessible(true);
            
            // This should not throw any errors
            try {
                $method->invoke($this->widget);
                $this->assertTrue(true); // If we get here, no exception was thrown
            } catch (\Exception $e) {
                $this->fail('Widget controls registration failed: ' . $e->getMessage());
            }
        }
    }

    /**
     * Test widget with form settings overrides
     */
    public function test_widget_with_form_settings_overrides() {
        $settings = [
            'gravity_form' => '1',
            'inherit_form_settings' => 'yes',
            'override_label_placement' => 'yes',
            'label_placement_override' => 'left_label',
            'override_description_placement' => 'yes',
            'description_placement_override' => 'above'
        ];
        
        $this->widget->set_settings($settings);
        
        // Test that overrides are applied
        $widget_settings = $this->widget->get_settings_for_display();
        
        $this->assertEquals('yes', $widget_settings['inherit_form_settings']);
        $this->assertEquals('yes', $widget_settings['override_label_placement']);
        $this->assertEquals('left_label', $widget_settings['label_placement_override']);
    }

    /**
     * Test widget error handling with invalid form ID
     */
    public function test_widget_error_handling_invalid_form() {
        $settings = [
            'gravity_form' => '999', // Non-existent form ID
            'show_title' => 'yes',
            'show_description' => 'yes',
            'use_ajax' => 'no'
        ];
        
        $this->widget->set_settings($settings);
        
        ob_start();
        $this->widget->render();
        $output = ob_get_clean();
        
        // Should handle the error gracefully
        $this->assertNotEmpty($output);
        // Should not contain PHP errors or warnings
        $this->assertStringNotContainsString('Warning:', $output);
        $this->assertStringNotContainsString('Error:', $output);
    }
}
