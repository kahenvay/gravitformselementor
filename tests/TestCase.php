<?php
/**
 * Base test case class with common utilities
 */

namespace GravityFormElementor\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

abstract class TestCase extends PHPUnitTestCase {

    /**
     * Assert that a string contains HTML elements
     */
    protected function assertContainsHtml($html, $needle, $message = '') {
        $this->assertStringContainsString($needle, $html, $message);
    }

    /**
     * Assert that HTML is valid
     */
    protected function assertValidHtml($html, $message = '') {
        // Basic HTML validation - check for balanced tags
        $dom = new \DOMDocument();
        $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        
        $this->assertNotEmpty($dom->documentElement, $message ?: 'HTML should be valid');
    }

    /**
     * Create mock Gravity Form data
     */
    protected function createMockGravityForm($id = '1', $overrides = []) {
        $defaults = [
            'id' => $id,
            'title' => 'Test Form ' . $id,
            'description' => 'Test form description',
            'labelPlacement' => 'top_label',
            'descriptionPlacement' => 'below',
            'subLabelPlacement' => 'below',
            'requiredIndicator' => 'asterisk',
            'customRequiredIndicator' => '',
            'validationSummary' => false,
            'cssClass' => '',
            'enableHoneypot' => false,
            'enableAnimation' => false,
            'markupVersion' => 2,
            'fields' => []
        ];

        return array_merge($defaults, $overrides);
    }

    /**
     * Create mock Elementor widget settings
     */
    protected function createMockWidgetSettings($overrides = []) {
        $defaults = [
            'gravity_form' => '1',
            'show_title' => 'yes',
            'show_description' => 'yes',
            'use_ajax' => 'yes',
            'label_display' => 'auto',
            'sub_label_display' => 'none',
            'inherit_form_settings' => 'yes',
            'override_label_placement' => 'no',
            'override_description_placement' => 'no',
            'override_sublabel_placement' => 'no'
        ];

        return array_merge($defaults, $overrides);
    }

    /**
     * Assert that a WordPress hook is registered
     */
    protected function assertHookRegistered($hook, $function = null, $priority = 10) {
        global $wp_filter;
        
        $this->assertArrayHasKey($hook, $wp_filter, "Hook '{$hook}' should be registered");
        
        if ($function) {
            $found = false;
            foreach ($wp_filter[$hook]->callbacks as $priority_callbacks) {
                foreach ($priority_callbacks as $callback) {
                    if (is_string($callback['function']) && $callback['function'] === $function) {
                        $found = true;
                        break 2;
                    }
                }
            }
            $this->assertTrue($found, "Function '{$function}' should be registered for hook '{$hook}'");
        }
    }

    /**
     * Assert that a style is registered/enqueued
     */
    protected function assertStyleRegistered($handle, $enqueued = false) {
        global $wp_styles;
        
        if (!$wp_styles) {
            $wp_styles = new \WP_Styles();
        }
        
        $this->assertTrue(
            isset($wp_styles->registered[$handle]),
            "Style '{$handle}' should be registered"
        );
        
        if ($enqueued) {
            $this->assertContains(
                $handle,
                $wp_styles->queue,
                "Style '{$handle}' should be enqueued"
            );
        }
    }

    /**
     * Assert that a script is registered/enqueued
     */
    protected function assertScriptRegistered($handle, $enqueued = false) {
        global $wp_scripts;
        
        if (!$wp_scripts) {
            $wp_scripts = new \WP_Scripts();
        }
        
        $this->assertTrue(
            isset($wp_scripts->registered[$handle]),
            "Script '{$handle}' should be registered"
        );
        
        if ($enqueued) {
            $this->assertContains(
                $handle,
                $wp_scripts->queue,
                "Script '{$handle}' should be enqueued"
            );
        }
    }

    /**
     * Capture output from a callable
     */
    protected function captureOutput(callable $callback) {
        ob_start();
        call_user_func($callback);
        return ob_get_clean();
    }

    /**
     * Create a reflection method and make it accessible
     */
    protected function getPrivateMethod($object, $methodName) {
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method;
    }

    /**
     * Get a private property value
     */
    protected function getPrivateProperty($object, $propertyName) {
        $reflection = new \ReflectionClass($object);
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        return $property->getValue($object);
    }

    /**
     * Set a private property value
     */
    protected function setPrivateProperty($object, $propertyName, $value) {
        $reflection = new \ReflectionClass($object);
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }
}
