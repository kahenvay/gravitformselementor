<?php
/**
 * Mock Gravity Forms classes for testing
 */

if (!class_exists('GFForms')) {
    class GFForms {
        public static $version = '2.7.0';
        
        public static function get_version() {
            return self::$version;
        }
    }
}

if (!class_exists('GFAPI')) {
    class GFAPI {
        
        public static function get_forms() {
            return [
                [
                    'id' => '1',
                    'title' => 'Contact Form',
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
                    'description' => 'Please fill out this form to contact us.'
                ],
                [
                    'id' => '2',
                    'title' => 'Newsletter Signup',
                    'labelPlacement' => 'left_label',
                    'descriptionPlacement' => 'above',
                    'subLabelPlacement' => 'above',
                    'requiredIndicator' => 'text',
                    'customRequiredIndicator' => '(Required)',
                    'validationSummary' => true,
                    'cssClass' => 'newsletter-form',
                    'enableHoneypot' => true,
                    'enableAnimation' => true,
                    'markupVersion' => 2,
                    'description' => 'Subscribe to our newsletter.'
                ]
            ];
        }
        
        public static function get_form($form_id) {
            $forms = self::get_forms();
            
            foreach ($forms as $form) {
                if ($form['id'] == $form_id) {
                    return $form;
                }
            }
            
            return false;
        }
        
        public static function get_form_title($form_id) {
            $form = self::get_form($form_id);
            return $form ? $form['title'] : '';
        }
    }
}

// Mock gravity_form function
if (!function_exists('gravity_form')) {
    function gravity_form($id, $display_title = true, $display_description = true, $display_inactive = false, $field_values = null, $ajax = false, $tabindex = 0, $echo = true) {
        $form = GFAPI::get_form($id);
        if (!$form) {
            return '';
        }
        
        $output = '<div class="gform_wrapper">';
        
        if ($display_title && !empty($form['title'])) {
            $output .= '<h3 class="gform_title">' . esc_html($form['title']) . '</h3>';
        }
        
        if ($display_description && !empty($form['description'])) {
            $output .= '<div class="gform_description">' . esc_html($form['description']) . '</div>';
        }
        
        $output .= '<form method="post" class="gform_form">';
        $output .= '<div class="gform_body">Mock form content for form ID: ' . $id . '</div>';
        $output .= '<div class="gform_footer"><input type="submit" value="Submit" class="gform_button button" /></div>';
        $output .= '</form>';
        $output .= '</div>';
        
        if ($echo) {
            echo $output;
            return '';
        }
        
        return $output;
    }
}
