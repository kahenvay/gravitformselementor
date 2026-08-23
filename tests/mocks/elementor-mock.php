<?php
/**
 * Mock Elementor classes for testing
 */

namespace Elementor {
    
    if (!class_exists('Widget_Base')) {
        class Widget_Base {
            protected $settings = [];
            protected $registered_controls = [];
            
            public function __construct($data = [], $args = null) {
                // Mock constructor
            }
            
            public function get_settings_for_display() {
                return $this->settings;
            }
            
            public function set_settings($settings) {
                $this->settings = $settings;
            }
            
            public function start_controls_section($id, $args) {
                // Mock method
            }
            
            public function end_controls_section() {
                // Mock method
            }
            
            public function add_control($id, $args) {
                $this->registered_controls[$id] = $args;
            }

            public function add_responsive_control($id, $args) {
                $this->registered_controls[$id] = $args;
            }

            public function add_group_control($type, $args) {
                if (isset($args['name'])) {
                    $this->registered_controls[$args['name']] = $args;
                }
            }

            public function get_registered_controls() {
                return $this->registered_controls;
            }
        }
    }
    
    if (!class_exists('Controls_Manager')) {
        class Controls_Manager {
            const TAB_CONTENT = 'content';
            const TAB_STYLE = 'style';
            const TAB_ADVANCED = 'advanced';
            const SELECT = 'select';
            const SWITCHER = 'switcher';
            const DIMENSIONS = 'dimensions';
            const SLIDER = 'slider';
            const RAW_HTML = 'raw_html';
            const COLOR = 'color';
        }
    }

    if (!class_exists('Group_Control_Border')) {
        class Group_Control_Border {
            public static function get_type() {
                return 'border';
            }
        }
    }

    if (!class_exists('Group_Control_Typography')) {
        class Group_Control_Typography {
            public static function get_type() {
                return 'typography';
            }
        }
    }
    
    if (!class_exists('Plugin')) {
        class Plugin {
            public static $instance;
            public $editor;
            
            public function __construct() {
                $this->editor = new \stdClass();
                $this->editor->is_edit_mode = function() { return false; };
            }
            
            public static function instance() {
                if (is_null(self::$instance)) {
                    self::$instance = new self();
                }
                return self::$instance;
            }
        }
        
        Plugin::$instance = Plugin::instance();
    }
}
