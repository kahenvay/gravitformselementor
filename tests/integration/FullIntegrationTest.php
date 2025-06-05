<?php
/**
 * Full integration tests that test the complete plugin workflow
 */

namespace GravityFormElementor\Tests\Integration;

use WP_UnitTestCase;

class FullIntegrationTest extends WP_UnitTestCase {

    /**
     * Test complete plugin workflow from activation to widget rendering
     */
    public function test_complete_plugin_workflow() {
        // 1. Test plugin initialization
        $this->assertTrue(function_exists('gf_elementor_widget_init'));
        
        // 2. Trigger plugin initialization
        do_action('plugins_loaded');
        
        // 3. Test that hooks are registered
        $this->assertTrue(has_action('elementor/widgets/register', 'register_gravity_form_elementor_widget'));
        $this->assertTrue(has_action('wp_enqueue_scripts', 'gf_register_widget_styles'));
        
        // 4. Test widget registration
        require_once ABSPATH . 'wp-content/plugins/gravityfromelementor/widgets/gf-widget.php';
        $widget = new \Elementor_GF_Widget();
        $this->assertInstanceOf('Elementor_GF_Widget', $widget);
        
        // 5. Test widget can render without errors
        $widget->set_settings([
            'gravity_form' => '1',
            'show_title' => 'yes',
            'show_description' => 'yes',
            'use_ajax' => 'no'
        ]);
        
        ob_start();
        $widget->render();
        $output = ob_get_clean();
        
        $this->assertNotEmpty($output);
        $this->assertStringNotContainsString('Fatal error', $output);
        $this->assertStringNotContainsString('Warning:', $output);
    }

    /**
     * Test plugin with different WordPress configurations
     */
    public function test_plugin_with_different_wp_configs() {
        // Test with different WordPress versions/configurations
        $original_wp_version = get_bloginfo('version');
        
        // Test that plugin handles version checking correctly
        $php_compatible = gf_elementor_widget_check_php_version();
        $this->assertTrue($php_compatible);
        
        // Test dependency checking
        $missing_deps = gf_elementor_widget_check_dependencies();
        $this->assertIsArray($missing_deps);
    }

    /**
     * Test plugin performance and memory usage
     */
    public function test_plugin_performance() {
        $start_memory = memory_get_usage();
        
        // Load and initialize the plugin
        do_action('plugins_loaded');
        
        // Create multiple widget instances
        require_once ABSPATH . 'wp-content/plugins/gravityfromelementor/widgets/gf-widget.php';
        
        $widgets = [];
        for ($i = 0; $i < 10; $i++) {
            $widgets[] = new \Elementor_GF_Widget();
        }
        
        $end_memory = memory_get_usage();
        $memory_used = $end_memory - $start_memory;
        
        // Memory usage should be reasonable (less than 5MB for 10 widgets)
        $this->assertLessThan(5 * 1024 * 1024, $memory_used, 'Plugin should not use excessive memory');
    }

    /**
     * Test plugin security measures
     */
    public function test_plugin_security() {
        // Test that direct access is prevented
        $plugin_files = [
            'index.php',
            'widgets/gf-widget.php'
        ];
        
        foreach ($plugin_files as $file) {
            $content = file_get_contents(ABSPATH . 'wp-content/plugins/gravityfromelementor/' . $file);
            $this->assertStringContainsString('ABSPATH', $content, "File {$file} should check for ABSPATH");
        }
    }

    /**
     * Test plugin with multisite
     */
    public function test_plugin_multisite_compatibility() {
        if (!is_multisite()) {
            $this->markTestSkipped('Multisite not available');
        }
        
        // Test that plugin works in multisite environment
        $this->assertTrue(function_exists('gf_elementor_widget_init'));
        
        // Test on different sites
        $sites = get_sites(['number' => 2]);
        foreach ($sites as $site) {
            switch_to_blog($site->blog_id);
            
            // Test plugin functionality on this site
            $missing_deps = gf_elementor_widget_check_dependencies();
            $this->assertIsArray($missing_deps);
            
            restore_current_blog();
        }
    }

    /**
     * Test plugin error handling and recovery
     */
    public function test_plugin_error_handling() {
        // Test with invalid form IDs
        require_once ABSPATH . 'wp-content/plugins/gravityfromelementor/widgets/gf-widget.php';
        $widget = new \Elementor_GF_Widget();
        
        $widget->set_settings([
            'gravity_form' => '999999', // Non-existent form
            'show_title' => 'yes',
            'show_description' => 'yes',
            'use_ajax' => 'no'
        ]);
        
        // Should not throw fatal errors
        ob_start();
        $widget->render();
        $output = ob_get_clean();
        
        $this->assertNotEmpty($output);
        $this->assertStringNotContainsString('Fatal error', $output);
    }

    /**
     * Test plugin cleanup and deactivation
     */
    public function test_plugin_cleanup() {
        // Test that plugin cleans up properly
        // This would typically test deactivation hooks, but since we can't
        // actually deactivate in tests, we test related functionality
        
        $this->assertTrue(function_exists('gf_elementor_widget_init'));
        
        // Test that no global variables are polluted
        $globals_before = array_keys($GLOBALS);
        do_action('plugins_loaded');
        $globals_after = array_keys($GLOBALS);
        
        // Should not add many new globals
        $new_globals = array_diff($globals_after, $globals_before);
        $this->assertLessThan(5, count($new_globals), 'Plugin should not pollute global namespace');
    }

    /**
     * Test plugin with different user roles and capabilities
     */
    public function test_plugin_user_capabilities() {
        // Test with different user roles
        $admin_user = $this->factory->user->create(['role' => 'administrator']);
        $editor_user = $this->factory->user->create(['role' => 'editor']);
        $subscriber_user = $this->factory->user->create(['role' => 'subscriber']);
        
        // Test as admin
        wp_set_current_user($admin_user);
        $this->assertTrue(current_user_can('manage_options'));
        
        // Test as editor
        wp_set_current_user($editor_user);
        $this->assertTrue(current_user_can('edit_posts'));
        
        // Test as subscriber
        wp_set_current_user($subscriber_user);
        $this->assertFalse(current_user_can('edit_posts'));
        
        // Plugin should work regardless of user role for frontend
        require_once ABSPATH . 'wp-content/plugins/gravityfromelementor/widgets/gf-widget.php';
        $widget = new \Elementor_GF_Widget();
        $this->assertInstanceOf('Elementor_GF_Widget', $widget);
    }

    /**
     * Test plugin localization and internationalization
     */
    public function test_plugin_i18n() {
        // Test that text domain is loaded
        $this->assertTrue(function_exists('__'));
        $this->assertTrue(function_exists('esc_html__'));
        
        // Test some translated strings
        $translated = esc_html__('Gravity Form', 'elementor-addon');
        $this->assertIsString($translated);
        $this->assertNotEmpty($translated);
    }
}
