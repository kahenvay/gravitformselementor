<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Elementor_GF_Widget extends \Elementor\Widget_Base {

    // public function __construct($data = [], $args = null) {
    //     parent::__construct($data, $args);
    
    //     if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
    //         wp_register_script('gf-widget-js', plugins_url('../assets/js/gf-widget.js', __FILE__), ['jquery'], '1.0.0', true);
    //         wp_enqueue_script('gf-widget-js');
    //     } 
    //         wp_register_script('gf-widget-js', plugins_url('../assets/js/gf-widget.js', __FILE__), ['jquery'], '1.0.0', true);
    //         wp_enqueue_script('gf-widget-js');
    // }

	public function get_name() {
		return 'gf_widget';
	}

	public function get_title() {
		return esc_html__( 'Gravity Form', 'elementor-addon' );
	}

    

	public function get_icon() {
		return 'eicon-form-horizontal';
	}

	public function get_categories() {
		return ['impact-hub-elements','gravity-forms'];
	}

	public function get_keywords() {
		return ['impact-hub-elements','form', 'gravity'];
	}

    private function get_forms_select_options(){
        // Check if GFAPI class exists
        if ( ! class_exists( 'GFAPI' ) ) {
            return array(
                '' => esc_html__( 'Gravity Forms not available', 'elementor-addon' )
            );
        }

        $results = GFAPI::get_forms();

        $options = array();

        if ( is_array( $results ) ) {
            foreach ( $results as $result ) {
                if ( isset( $result['id'] ) && isset( $result['title'] ) ) {
                    $options[ $result['id'] ] = $result['title'];
                }
            }
        }

        // If no forms found, provide a helpful message
        if ( empty( $options ) ) {
            $options = array(
                '' => esc_html__( 'No forms found', 'elementor-addon' )
            );
        }

        return $options;
    }

    /**
     * Get form settings from Gravity Forms
     * This method retrieves the form object and extracts relevant settings
     * that can be inherited or overridden in the Elementor widget
     */
    private function get_form_settings( $form_id = null ) {
        // Use the selected form ID if not provided
        if ( ! $form_id ) {
            $settings = $this->get_settings_for_display();
            $form_id = $settings['gravity_form'] ?? '';
        }

        // Return empty array if no form selected or GFAPI not available
        if ( empty( $form_id ) || ! class_exists( 'GFAPI' ) ) {
            return array();
        }

        $form = GFAPI::get_form( $form_id );

        if ( ! $form || is_wp_error( $form ) ) {
            return array();
        }

        // Extract relevant form settings for widget customization
        return array(
            'labelPlacement' => $form['labelPlacement'] ?? 'top_label',
            'descriptionPlacement' => $form['descriptionPlacement'] ?? 'below',
            'subLabelPlacement' => $form['subLabelPlacement'] ?? 'below',
            'requiredIndicator' => $form['requiredIndicator'] ?? 'asterisk',
            'customRequiredIndicator' => $form['customRequiredIndicator'] ?? '',
            'validationSummary' => $form['validationSummary'] ?? false,
            'cssClass' => $form['cssClass'] ?? '',
            'enableHoneypot' => $form['enableHoneypot'] ?? false,
            'enableAnimation' => $form['enableAnimation'] ?? false,
            'markupVersion' => $form['markupVersion'] ?? 1,
            'title' => $form['title'] ?? '',
            'description' => $form['description'] ?? ''
        );
    }

    /**
     * Get human-readable labels for form settings
     */
    private function get_form_setting_labels() {
        return array(
            'labelPlacement' => array(
                'top_label' => esc_html__( 'Above inputs', 'elementor-addon' ),
                'left_label' => esc_html__( 'Left aligned', 'elementor-addon' ),
                'right_label' => esc_html__( 'Right aligned', 'elementor-addon' )
            ),
            'descriptionPlacement' => array(
                'above' => esc_html__( 'Above inputs', 'elementor-addon' ),
                'below' => esc_html__( 'Below inputs', 'elementor-addon' )
            ),
            'subLabelPlacement' => array(
                'above' => esc_html__( 'Above inputs', 'elementor-addon' ),
                'below' => esc_html__( 'Below inputs', 'elementor-addon' )
            ),
            'requiredIndicator' => array(
                'text' => esc_html__( 'Text "(Required)"', 'elementor-addon' ),
                'asterisk' => esc_html__( 'Asterisk "*"', 'elementor-addon' ),
                'custom' => esc_html__( 'Custom indicator', 'elementor-addon' )
            )
        );
    }

    protected function register_main_controls(){
        $this->start_controls_section(
            'section_title',
            [
                'label' => esc_html__( 'Gravity Form', 'elementor-addon' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

         $this->add_control(
			'gravity_form',
			[
				'label' => esc_html__( 'Form', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => $this->get_forms_select_options(),
			]
		);

        // Form Settings Integration Notice
        $this->add_control(
            'form_settings_notice',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '<div style="background: #e8f4fd; padding: 10px; border-left: 3px solid #0073aa; margin: 10px 0;">
                    <strong>' . esc_html__( 'Form Settings Integration', 'elementor-addon' ) . '</strong><br>
                    ' . esc_html__( 'This widget can inherit settings from your Gravity Form or allow you to override them. When you override a setting, it will be clearly marked.', 'elementor-addon' ) . '
                </div>',
                'condition' => [
                    'gravity_form!' => '',
                ],
            ]
        );

        $this->add_control(
			'show_title',
			[
				'label' => esc_html__( 'Show Title', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'textdomain' ),
				'label_off' => esc_html__( 'Hide', 'textdomain' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

        $this->add_control(
			'title_margin',
			[
				'label' => esc_html__( 'Title margin', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px',
					'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .gform_wrapper .gform_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition' => [
                    'show_title' => 'yes',
                ],
			]
		);

        $this->add_control(
			'show_description',
			[
				'label' => esc_html__( 'Show Descritpion', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'textdomain' ),
				'label_off' => esc_html__( 'Hide', 'textdomain' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

        $this->add_control(
			'description_margin',
			[
				'label' => esc_html__( 'Description margin', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 14.4,
					'left' => 0,
					'unit' => 'px',
					'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .gform_wrapper .gform_description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition' => [
                    'show_description' => 'yes',
                ],
			]
		);

        $this->add_control(
			'use_ajax',
			[
				'label' => esc_html__( 'Use Ajax', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'textdomain' ),
				'label_off' => esc_html__( 'Hide', 'textdomain' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

        // Notice about form settings integration
        $this->add_control(
            'label_styling_notice',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '<div style="background: #e8f4fd; padding: 8px; border-left: 3px solid #0073aa; margin: 8px 0; font-size: 11px;">
                    <strong>' . esc_html__( 'Label Placement vs Display:', 'elementor-addon' ) . '</strong><br>' .
                    esc_html__( '• Label Placement (above) controls the layout structure (above, left, right of inputs)', 'elementor-addon' ) . '<br>' .
                    esc_html__( '• Label Display (below) controls the CSS display property (block, inline, etc.)', 'elementor-addon' ) . '<br>' .
                    esc_html__( '• Use "Auto" for Label Display unless you need specific CSS behavior', 'elementor-addon' ) . '
                </div>',
                'condition' => [
                    'gravity_form!' => '',
                ],
            ]
        );

        $this->add_control(
            'label_integration_notice',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '<div style="background: #fff3cd; padding: 8px; border-left: 3px solid #ffc107; margin: 8px 0; font-size: 11px;">
                    <strong>' . esc_html__( 'Form Settings Integration:', 'elementor-addon' ) . '</strong> ' .
                    esc_html__( 'Label placement is currently controlled by your Gravity Form settings. Enable "Override Label Placement" above to customize it.', 'elementor-addon' ) . '
                </div>',
                'condition' => [
                    'gravity_form!' => '',
                    'inherit_form_settings' => 'yes',
                    'override_label_placement!' => 'yes',
                ],
            ]
        );

        $this->add_control(
			'label_display',
			[
				'label' => esc_html__( 'Label Display', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'auto',
                'options' => [
                    'auto' => esc_html__( 'Auto (Recommended)', 'elementor-addon' ),
                    'none' => esc_html__( 'None (Hide Labels)', 'elementor-pro' ),
                    'block' => esc_html__( 'Block', 'elementor-pro' ),
                    'inline-block' => esc_html__( 'Inline-Block', 'elementor-pro' ),
                    'inline' => esc_html__( 'Inline', 'elementor-pro' ),
                    'flex' => esc_html__( 'Flex', 'elementor-pro' ),
                    'inline-flex' => esc_html__( 'Inline-flex', 'elementor-pro' ),
                ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .gform_wrapper .gfield_label:not(.gfield_consent_label)' => 'display: {{VALUE}};',
                ],
                'condition' => [
                    'label_display!' => 'auto',
                ],
                'description' => esc_html__( 'Override the CSS display property for labels. "Auto" uses the optimal display based on label placement. Only change this if you need specific display behavior.', 'elementor-addon' ),
			]
		);

        $this->add_control(
			'sub_label_display',
			[
				'label' => esc_html__( 'Sub Label Display', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__( 'None', 'elementor-pro' ),
                    'block' => esc_html__( 'Block', 'elementor-pro' ),
                    'inline-block' => esc_html__( 'Inline-Block', 'elementor-pro' ),
                    'inline' => esc_html__( 'Inline', 'elementor-pro' ),
                    'flex' => esc_html__( 'Flex', 'elementor-pro' ),
                    'inline-flex' => esc_html__( 'Inline-flex', 'elementor-pro' ),
                ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .gform_wrapper .gform-field-label.gform-field-label--type-sub' => 'display: {{VALUE}};',
                ],
			]
		);

        $this->add_control(
			'label_margin',
			[
				'label' => esc_html__( 'Label margin', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 14.4,
					'left' => 0,
					'unit' => 'px',
					'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .gform_wrapper  label:not(.gform-field-label--type-inline)' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition' => [
                    'label_display!' => 'none',
                ],
			]
		);

        $this->fieldset_constrols();


        $this->end_controls_section();
    }

    /**
     * Register Form Settings Integration Controls
     * This section allows users to inherit or override Gravity Forms settings
     */
    protected function register_form_settings_controls(){
        $this->start_controls_section(
            'form_settings_section',
            [
                'label' => esc_html__( 'Form Settings Integration', 'elementor-addon' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'gravity_form!' => '',
                ],
            ]
        );

        // Master toggle for inheriting form settings
        $this->add_control(
            'inherit_form_settings',
            [
                'label' => esc_html__( 'Inherit Form Settings', 'elementor-addon' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'elementor-addon' ),
                'label_off' => esc_html__( 'No', 'elementor-addon' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => esc_html__( 'When enabled, the widget will use settings from your Gravity Form. You can still override individual settings below.', 'elementor-addon' ),
            ]
        );

        // Label Placement Override
        $this->add_control(
            'override_label_placement',
            [
                'label' => esc_html__( 'Override Label Placement', 'elementor-addon' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'elementor-addon' ),
                'label_off' => esc_html__( 'No', 'elementor-addon' ),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'inherit_form_settings' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'label_placement_override',
            [
                'label' => esc_html__( 'Label Placement', 'elementor-addon' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'top_label' => esc_html__( 'Above inputs', 'elementor-addon' ),
                    'left_label' => esc_html__( 'Left aligned', 'elementor-addon' ),
                    'right_label' => esc_html__( 'Right aligned', 'elementor-addon' ),
                ],
                'default' => 'top_label',
                'condition' => [
                    'inherit_form_settings' => 'yes',
                    'override_label_placement' => 'yes',
                ],
            ]
        );

        // Description Placement Override
        $this->add_control(
            'override_description_placement',
            [
                'label' => esc_html__( 'Override Description Placement', 'elementor-addon' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'elementor-addon' ),
                'label_off' => esc_html__( 'No', 'elementor-addon' ),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'inherit_form_settings' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'description_placement_override',
            [
                'label' => esc_html__( 'Description Placement', 'elementor-addon' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'above' => esc_html__( 'Above inputs', 'elementor-addon' ),
                    'below' => esc_html__( 'Below inputs', 'elementor-addon' ),
                ],
                'default' => 'below',
                'condition' => [
                    'inherit_form_settings' => 'yes',
                    'override_description_placement' => 'yes',
                ],
            ]
        );

        // Sub-Label Placement Override
        $this->add_control(
            'override_sublabel_placement',
            [
                'label' => esc_html__( 'Override Sub-Label Placement', 'elementor-addon' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'elementor-addon' ),
                'label_off' => esc_html__( 'No', 'elementor-addon' ),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => esc_html__( 'Affects fields with multiple inputs like Name fields', 'elementor-addon' ),
                'condition' => [
                    'inherit_form_settings' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'sublabel_placement_override',
            [
                'label' => esc_html__( 'Sub-Label Placement', 'elementor-addon' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'above' => esc_html__( 'Above inputs', 'elementor-addon' ),
                    'below' => esc_html__( 'Below inputs', 'elementor-addon' ),
                ],
                'default' => 'below',
                'condition' => [
                    'inherit_form_settings' => 'yes',
                    'override_sublabel_placement' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register Advanced Form Settings Controls
     * Additional form-level settings that can be inherited or overridden
     */
    protected function register_advanced_form_settings_controls(){
        $this->start_controls_section(
            'advanced_form_settings_section',
            [
                'label' => esc_html__( 'Advanced Form Settings', 'elementor-addon' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'gravity_form!' => '',
                ],
            ]
        );

        // Required Indicator Override
        $this->add_control(
            'override_required_indicator',
            [
                'label' => esc_html__( 'Override Required Indicator', 'elementor-addon' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'elementor-addon' ),
                'label_off' => esc_html__( 'No', 'elementor-addon' ),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'inherit_form_settings' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'required_indicator_override',
            [
                'label' => esc_html__( 'Required Indicator', 'elementor-addon' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'text' => esc_html__( 'Text "(Required)"', 'elementor-addon' ),
                    'asterisk' => esc_html__( 'Asterisk "*"', 'elementor-addon' ),
                    'custom' => esc_html__( 'Custom indicator', 'elementor-addon' ),
                ],
                'default' => 'asterisk',
                'condition' => [
                    'inherit_form_settings' => 'yes',
                    'override_required_indicator' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'custom_required_indicator',
            [
                'label' => esc_html__( 'Custom Required Indicator', 'elementor-addon' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '*',
                'placeholder' => esc_html__( 'Enter custom indicator', 'elementor-addon' ),
                'condition' => [
                    'inherit_form_settings' => 'yes',
                    'override_required_indicator' => 'yes',
                    'required_indicator_override' => 'custom',
                ],
            ]
        );

        // Validation Summary Override
        $this->add_control(
            'override_validation_summary',
            [
                'label' => esc_html__( 'Override Validation Summary', 'elementor-addon' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'elementor-addon' ),
                'label_off' => esc_html__( 'No', 'elementor-addon' ),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => esc_html__( 'Show validation errors summary at top of form', 'elementor-addon' ),
                'condition' => [
                    'inherit_form_settings' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'validation_summary_override',
            [
                'label' => esc_html__( 'Show Validation Summary', 'elementor-addon' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'elementor-addon' ),
                'label_off' => esc_html__( 'No', 'elementor-addon' ),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'inherit_form_settings' => 'yes',
                    'override_validation_summary' => 'yes',
                ],
            ]
        );

        // Animation Override
        $this->add_control(
            'override_animation',
            [
                'label' => esc_html__( 'Override Animation Settings', 'elementor-addon' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'elementor-addon' ),
                'label_off' => esc_html__( 'No', 'elementor-addon' ),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => esc_html__( 'Enable slide animations for conditional logic', 'elementor-addon' ),
                'condition' => [
                    'inherit_form_settings' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'animation_override',
            [
                'label' => esc_html__( 'Enable Animations', 'elementor-addon' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'elementor-addon' ),
                'label_off' => esc_html__( 'No', 'elementor-addon' ),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'inherit_form_settings' => 'yes',
                    'override_animation' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function register_input_controls(){
        $this->start_controls_section(
            'input_section',
            [
                'label' => esc_html__( 'Inputs Styling', 'textdomain' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
			'inputs_margin',
			[
				'label' => esc_html__( 'Input margin', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px',
					'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .gform_wrapper .ginput_container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'inputs_padding',
			[
				'label' => esc_html__( 'Input padding', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 16,
					'right' => 32,
					'bottom' => 16,
					'left' => 32,
					'unit' => 'px',
					'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .gform_wrapper input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'input_border',
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .gform_wrapper input',
                'default' => 'solid'
			]
		);

		$this->add_responsive_control(
            'input_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'default' => [
                    'top' => 50,
                    'right' => 50,
                    'bottom' => 50,
                    'left' => 50,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gform_wrapper input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );

		$this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'input_typography', 
                'global' => [

                ],
                'selector' => '{{WRAPPER}} .gform_wrapper input',

            ]
        );


		$this->add_control(
            'input_text_color',
            [
                'label' => esc_html__( 'Text Color', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'global' => [

                ],
                'selectors' => [
                    '{{WRAPPER}} .gform_wrapper input' => 'color: {{VALUE}};',
                ],
            ]
        );

		$this->add_control(
            'input_color',
            [
                'label' => esc_html__( 'Background Color', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'global' => [

                ],
                'selectors' => [
                    '{{WRAPPER}} .gform_wrapper input' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function register_name_controls(){
        $this->start_controls_section(
            'name_section',
            [
                'label' => esc_html__( 'Names Styling', 'textdomain' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
			'names_margin',
			[
				'label' => esc_html__( 'Input margin', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px',
					'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .gfield--type-name input' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'names_padding',
			[
				'label' => esc_html__( 'Input padding', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 16,
					'right' => 32,
					'bottom' => 16,
					'left' => 32,
					'unit' => 'px',
					'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .gfield--type-name input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'name_border',
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .gfield--type-name input',
                'default' => 'solid'
			]
		);

		$this->add_responsive_control(
            'name_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'default' => [
                    'top' => 50,
                    'right' => 50,
                    'bottom' => 50,
                    'left' => 50,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gfield--type-name input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );

		$this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography', 
                'global' => [

                ],
                'selector' => '{{WRAPPER}} .gfield--type-name input',

            ]
        );


		$this->add_control(
            'name_text_color',
            [
                'label' => esc_html__( 'Text Color', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'global' => [

                ],
                'selectors' => [
                    '{{WRAPPER}} .gfield--type-name input' => 'color: {{VALUE}};',
                ],
            ]
        );

		$this->add_control(
            'name_color',
            [
                'label' => esc_html__( 'Background Color', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'global' => [

                ],
                'selectors' => [
                    '{{WRAPPER}} .gfield--type-name input' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function register_emails_controls(){
        $this->start_controls_section(
            'email_section',
            [
                'label' => esc_html__( 'Emails Styling', 'textdomain' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
			'emails_margin',
			[
				'label' => esc_html__( 'Input margin', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px',
					'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .gfield--type-email input' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'emails_padding',
			[
				'label' => esc_html__( 'Input padding', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 16,
					'right' => 32,
					'bottom' => 16,
					'left' => 32,
					'unit' => 'px',
					'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .gfield--type-email input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'email' => 'email_border',
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .gfield--type-email input',
                'default' => 'solid'
			]
		);

		$this->add_responsive_control(
            'email_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'default' => [
                    'top' => 50,
                    'right' => 50,
                    'bottom' => 50,
                    'left' => 50,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gfield--type-email input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );

		$this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'email' => 'email_typography', 
                'global' => [

                ],
                'selector' => '{{WRAPPER}} .gfield--type-email input',

            ]
        );


		$this->add_control(
            'email_text_color',
            [
                'label' => esc_html__( 'Text Color', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'global' => [

                ],
                'selectors' => [
                    '{{WRAPPER}} .gfield--type-email input' => 'color: {{VALUE}};',
                ],
            ]
        );

		$this->add_control(
            'email_color',
            [
                'label' => esc_html__( 'Background Color', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'global' => [

                ],
                'selectors' => [
                    '{{WRAPPER}} .gfield--type-email input' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function register_textarea_controls(){
        $this->start_controls_section(
            'textarea_section',
            [
                'label' => esc_html__( 'Textareas Styling', 'textdomain' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
			'textareas_margin',
			[
				'label' => esc_html__( 'Textarea margin', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px',
					'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .gform_wrapper .ginput_container_textarea' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'textareas_padding',
			[
				'label' => esc_html__( 'Textarea padding', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 16,
					'right' => 32,
					'bottom' => 16,
					'left' => 32,
					'unit' => 'px',
					'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .gform_wrapper textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'textarea_border',
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .gform_wrapper textarea',
			]
		);

		$this->add_responsive_control(
            'textarea_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'default' => [
                    'top' => 50,
                    'right' => 50,
                    'bottom' => 50,
                    'left' => 50,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gform_wrapper textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );

		$this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'textarea_typography',
                'global' => [

                ],
                'selector' => '{{WRAPPER}} .gform_wrapper textarea',
            ]
        );


		$this->add_control(
            'textarea_text_color',
            [
                'label' => esc_html__( 'Text Color', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'global' => [

                ],
                'selectors' => [
                    '{{WRAPPER}} .gform_wrapper textarea' => 'color: {{VALUE}};',
                ],
            ]
        );

		$this->add_control(
            'textarea_color',
            [
                'label' => esc_html__( 'Background Color', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'global' => [

                ],
                'selectors' => [
                    '{{WRAPPER}} .gform_wrapper textarea' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();


    }

	protected function register_select_controls(){
        $this->start_controls_section(
            'select_section',
            [
                'label' => esc_html__( 'Select Styling', 'textdomain' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

		

  		$this->add_control(
			'arrow_display',
			[
				'label' => esc_html__( 'Arrow Display', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'auto',
                'options' => [
                    'none' => esc_html__( 'None', 'elementor-pro' ),
                    'auto' => esc_html__( 'Auto', 'elementor-pro' ),
                ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .gform_wrapper select' => 'appearance: {{VALUE}};',
                ],
			]
		);

        $this->add_control(
			'selects_margin',
			[
				'label' => esc_html__( 'Select margin', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px',
					'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .gform_wrapper .ginput_container_select' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'selects_padding',
			[
				'label' => esc_html__( 'Select padding', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 16,
					'right' => 32,
					'bottom' => 16,
					'left' => 32,
					'unit' => 'px',
					'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .gform_wrapper select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'select_border',
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .gform_wrapper select',
			]
		);

		$this->add_responsive_control(
            'select_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'default' => [
                    'top' => 50,
                    'right' => 50,
                    'bottom' => 50,
                    'left' => 50,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gform_wrapper select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );

		$this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'select_typography',
                'global' => [

                ],
                'selector' => '{{WRAPPER}} .gform_wrapper select',
            ]
        );


		$this->add_control(
            'select_text_color',
            [
                'label' => esc_html__( 'Text Color', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'global' => [

                ],
                'selectors' => [
                    '{{WRAPPER}} .gform_wrapper select' => 'color: {{VALUE}};',
                ],
            ]
        );

		$this->add_control(
            'select_color',
            [
                'label' => esc_html__( 'Background Color', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'global' => [

                ],
                'selectors' => [
                    '{{WRAPPER}} .gform_wrapper select' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();


    }

    protected function register_submit_controls(){
        $this->start_controls_section(
            'submit_section',
            [
                'label' => esc_html__( 'Submit Styling', 'textdomain' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
		$this->add_control(
			'submit_margin',
			[
				'label' => esc_html__( 'Submit margin', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px',
					'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .gform_wrapper .gform_footer' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'submit_padding',
			[
				'label' => esc_html__( 'Submit padding', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 16,
					'right' => 32,
					'bottom' => 16,
					'left' => 32,
					'unit' => 'px',
					'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .gform_wrapper input[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'submit_border',
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .gform_wrapper input[type="submit"]',
			]
		);

		$this->add_responsive_control(
            'submit_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'default' => [
                    'top' => 50,
                    'right' => 50,
                    'bottom' => 50,
                    'left' => 50,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gform_wrapper input[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );

		$this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'submit_typography',
                'global' => [

                ],
                'selector' => '{{WRAPPER}} .gform_wrapper input[type="submit"]',
            ]
        );


		$this->add_control(
            'subit_text_color',
            [
                'label' => esc_html__( 'Text Color', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'global' => [

                ],
                'selectors' => [
                    '{{WRAPPER}} .gform_wrapper input[type="submit"]' => 'color: {{VALUE}};',
                ],
            ]
        );

		$this->add_control(
            'subit_color',
            [
                'label' => esc_html__( 'Background Color', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'global' => [

                ],
                'selectors' => [
                    '{{WRAPPER}} .gform_wrapper input[type="submit"]' => 'background-color: {{VALUE}};',
                ],
            ]
        );

		$this->end_controls_section();
    }

	// TODO : more options for syling
	// replace checkbox for more customisation
	// replace caret for more customisation


	protected function register_consent_controls(){
        $this->start_controls_section(
            'consent_section',
            [
                'label' => esc_html__( 'Consent Styling', 'textdomain' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

		$this->add_control(
            'consent_use_custom_checkbox',
            [
                'label' => esc_html__( 'Use custom checkbox', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'elementor-pro' ),
                'label_off' => esc_html__( 'Hide', 'elementor-pro' ),
                'default' => 'yes',

            ]
        );

		// $this->add_control(
		// 	'checkbox_width',
		// 	[
		// 		'label' => esc_html__( 'Cehckbox Width', 'textdomain' ),
		// 		'type' => \Elementor\Controls_Manager::SLIDER,
		// 		'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
		// 		'range' => [
		// 			'px' => [
		// 				'min' => 0,
		// 				'max' => 1000,
		// 				'step' => 5,
		// 			],
		// 			'%' => [
		// 				'min' => 0,
		// 				'max' => 100,
		// 			],
		// 		],
		// 		'default' => [
		// 			'unit' => 'px',
		// 			'size' => 32,
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .gform_wrapper .consent_gf_use_custom_checkbox .gfield--type-consent [type="checkbox"] + label:before' => 'width: {{SIZE}}{{UNIT}};',
		// 		],
		// 	]
		// );

		// $this->add_control(
		// 	'check_height',
		// 	[
		// 		'label' => esc_html__( 'Cehckbox Width', 'textdomain' ),
		// 		'type' => \Elementor\Controls_Manager::SLIDER,
		// 		'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
		// 		'range' => [
		// 			'px' => [
		// 				'min' => 0,
		// 				'max' => 1000,
		// 				'step' => 5,
		// 			],
		// 			'%' => [
		// 				'min' => 0,
		// 				'max' => 100,
		// 			],
		// 		],
		// 		'default' => [
		// 			'unit' => 'px',
		// 			'size' => 32,
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .gform_wrapper .consent_gf_use_custom_checkbox .gfield--type-consent [type="checkbox"] + label:after' => 'height: calc {{SIZE}}{{UNIT}};',
		// 		],
		// 	]
		// );

		// $this->add_control(
		// 	'check_width',
		// 	[
		// 		'label' => esc_html__( 'Cehckbox Width', 'textdomain' ),
		// 		'type' => \Elementor\Controls_Manager::SLIDER,
		// 		'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
		// 		'range' => [
		// 			'px' => [
		// 				'min' => 0,
		// 				'max' => 1000,
		// 				'step' => 5,
		// 			],
		// 			'%' => [
		// 				'min' => 0,
		// 				'max' => 100,
		// 			],
		// 		],
		// 		'default' => [
		// 			'unit' => 'px',
		// 			'size' => 32,
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .gform_wrapper .consent_gf_use_custom_checkbox .gfield--type-consent [type="checkbox"] + label:after' => 'width: calc {{SIZE}}{{UNIT}};',
		// 		],
		// 	]
		// );

		// $this->add_control(
		// 	'checkbox_height',
		// 	[
		// 		'label' => esc_html__( 'Cehckbox Width', 'textdomain' ),
		// 		'type' => \Elementor\Controls_Manager::SLIDER,
		// 		'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
		// 		'range' => [
		// 			'px' => [
		// 				'min' => 0,
		// 				'max' => 1000,
		// 				'step' => 5,
		// 			],
		// 			'%' => [
		// 				'min' => 0,
		// 				'max' => 100,
		// 			],
		// 		],
		// 		'default' => [
		// 			'unit' => 'px',
		// 			'size' => 32,
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .gform_wrapper .consent_gf_use_custom_checkbox .gfield--type-consent [type="checkbox"] + label:before' => 'height: {{SIZE}}{{UNIT}};',
		// 		],
		// 	]
		// );

		

		$this->add_control(
            'consent_checkbox_background',
            [
                'label' => esc_html__( 'Checkbox Background Color', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'global' => [

                ],
                'selectors' => [
                    '{{WRAPPER}} .gform_wrapper .consent_gf_use_custom_checkbox .gfield--type-consent [type="checkbox"] + label:before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

		$this->add_control(
            'consent_check_background',
            [
                'label' => esc_html__( 'Check Color', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'global' => [

                ],
                'selectors' => [
                    '{{WRAPPER}} .gform_wrapper .consent_gf_use_custom_checkbox .gfield--type-consent [type="checkbox"] + label:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'consent_checkbox_border',
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .gform_wrapper .consent_gf_use_custom_checkbox .gfield--type-consent [type="checkbox"] + label:before ',
				'condition' => [
                    'consent_use_custom_checkbox' => 'yes',
                ],
			]
		);

		$this->add_responsive_control(
            'consent_checkbox_border_radius',
            [
                'label' => esc_html__( 'Checkbox Border Radius', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gform_wrapper .consent_gf_use_custom_checkbox .gfield--type-consent [type="checkbox"] + label:before ' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .gform_wrapper .consent_gf_use_custom_checkbox .gfield--type-consent [type="checkbox"] + label:after ' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
				'condition' => [
                    'consent_use_custom_checkbox' => 'yes',
                ],
            ]
        );


		// $this->add_control(
		// 	'legend_display',
		// 	[
		// 		'label' => esc_html__( 'Label Display', 'textdomain' ),
        //         'type' => \Elementor\Controls_Manager::SELECT,
        //         'default' => 'none',
        //         'options' => [
        //             'none' => esc_html__( 'None', 'elementor-pro' ),
        //             'block' => esc_html__( 'Block', 'elementor-pro' ),
        //         ],
        //         'separator' => 'before',
        //         'selectors' => [
        //             '{{WRAPPER}} .gform_wrapper .gfield--type-consent' => 'display: {{VALUE}};',
        //         ],
		// 	]
		// );
        
		$this->add_control(
			'consent_margin',
			[
				'label' => esc_html__( 'Consent margin', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px',
					'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .gform_wrapper .gfield--type-consent' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// $this->add_control(
		// 	'consent_padding',
		// 	[
		// 		'label' => esc_html__( 'Wrapper padding ', 'textdomain' ),
		// 		'type' => \Elementor\Controls_Manager::DIMENSIONS,
		// 		'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
		// 		'default' => [
		// 			'top' => 0,
		// 			'right' => 0,
		// 			'bottom' => 0,
		// 			'left' => 0,
		// 			'unit' => 'px',
		// 			'isLinked' => false,
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .gform_wrapper .gfield--type-consent' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		// 		],
		// 	]
		// );

		

		$this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'consent_typography',
                'global' => [

                ],
                'selector' => '{{WRAPPER}} .gform_wrapper .gfield--type-consent .gfield_consent_label',
            ]
        );

		

		$this->end_controls_section();
    }

	protected function register_checkbox_controls(){
        $this->start_controls_section(
            'checkbox_section',
            [
                'label' => esc_html__( 'Checkbox Styling', 'textdomain' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

		$this->add_control(
            'checkbox_use_custom_checkbox',
            [
                'label' => esc_html__( 'Use custom checkbox', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'elementor-pro' ),
                'label_off' => esc_html__( 'Hide', 'elementor-pro' ),
                'default' => 'yes',

            ]
        );


		$this->add_control(
            'checkbox_checkbox_background',
            [
                'label' => esc_html__( 'Checkbox Background Color', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'global' => [

                ],
                'selectors' => [
                    '{{WRAPPER}} .gform_wrapper .checkbox_gf_use_custom_checkbox .gfield--type-checkbox [type="checkbox"] + label:before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

		$this->add_control(
            'checkbox_check_background',
            [
                'label' => esc_html__( 'Check Color', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'global' => [

                ],
                'selectors' => [
                    '{{WRAPPER}} .gform_wrapper .checkbox_gf_use_custom_checkbox .gfield--type-checkbox [type="checkbox"] + label:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'checkbox_checkbox_border',
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .gform_wrapper .checkbox_gf_use_custom_checkbox .gfield--type-checkbox [type="checkbox"] + label:before ',
				'condition' => [
                    'checkbox_use_custom_checkbox' => 'yes',
                ],
			]
		);

		$this->add_responsive_control(
            'checkbox_checkbox_border_radius',
            [
                'label' => esc_html__( 'Checkbox Border Radius', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gform_wrapper .checkbox_gf_use_custom_checkbox .gfield--type-checkbox [type="checkbox"] + label:before ' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .gform_wrapper .checkbox_gf_use_custom_checkbox .gfield--type-checkbox [type="checkbox"] + label:after ' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
				'condition' => [
                    'checkbox_use_custom_checkbox' => 'yes',
                ],
            ]
        );


        
		$this->add_control(
			'checkbox_margin',
			[
				'label' => esc_html__( 'Checkbox margin', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px',
					'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .gform_wrapper .gfield--type-checkbox' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

	
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'wrapper_checkbox_border',
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .gform_wrapper .gfield--type-checkbox ',
			]
		);

		$this->add_responsive_control(
            'wrapper_checkbox_border_radius',
            [
                'label' => esc_html__( 'Wrapper Border Radius', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'default' => [
                    'top' => 20,
                    'right' => 20,
                    'bottom' => 20,
                    'left' => 20,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gform_wrapper .gfield--type-checkbox ' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ] 
        );

		$this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'checkbox_typography',
                'global' => [

                ],
                'selector' => '{{WRAPPER}} .gform_wrapper .gfield--type-checkbox .gfield_consent_label',
            ]
        );

		

		$this->end_controls_section();
    }

	protected function register_radio_controls(){
        $this->start_controls_section(
            'radio_section',
            [
                'label' => esc_html__( 'Radio Styling', 'textdomain' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

		$this->add_control(
            'radio_use_custom_radio',
            [
                'label' => esc_html__( 'Use custom radio', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'elementor-pro' ),
                'label_off' => esc_html__( 'Hide', 'elementor-pro' ),
                'default' => 'yes',

            ]
        );


		$this->add_control(
            'radio_radio_background',
            [
                'label' => esc_html__( 'Radio Background Color', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'global' => [

                ],
                'selectors' => [
                    '{{WRAPPER}} .gform_wrapper .radio_gf_use_custom_radio .gfield--type-radio [type="radio"] + label:before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

		$this->add_control(
            'radio_check_background',
            [
                'label' => esc_html__( 'Check Color', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'global' => [

                ],
                'selectors' => [
                    '{{WRAPPER}} .gform_wrapper .radio_gf_use_custom_radio .gfield--type-radio [type="radio"] + label:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'radio_radio_border',
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .gform_wrapper .radio_gf_use_custom_radio .gfield--type-radio [type="radio"] + label:before ',
				'condition' => [
                    'radio_use_custom_radio' => 'yes',
                ],
			]
		);

		$this->add_responsive_control(
            'radio_radio_border_radius',
            [
                'label' => esc_html__( 'Radio Border Radius', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'default' => [
                    'top' => 100,
                    'right' => 100,
                    'bottom' => 100,
                    'left' => 100,
                    'unit' => '%',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gform_wrapper .radio_gf_use_custom_radio .gfield--type-radio [type="radio"] + label:before ' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .gform_wrapper .radio_gf_use_custom_radio .gfield--type-radio [type="radio"] + label:after ' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
				'condition' => [
                    'radio_use_custom_radio' => 'yes',
                ],
            ]
        );


        
		$this->add_control(
			'radio_margin',
			[
				'label' => esc_html__( 'Radio margin', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px',
					'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .gform_wrapper .gfield--type-radio' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

	
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'wrapper_radio_border',
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .gform_wrapper .gfield--type-radio ',
			]
		);

		$this->add_responsive_control(
            'wrapper_radio_border_radius',
            [
                'label' => esc_html__( 'Wrapper Border Radius', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gform_wrapper .gfield--type-radio ' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ] 
        );

		$this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'radio_typography',
                'global' => [

                ],
                'selector' => '{{WRAPPER}} .gform_wrapper .gfield--type-radio .gfield_consent_label',
            ]
        );

		

		$this->end_controls_section();
    }

    protected function register_section_controls(){
        $this->start_controls_section(
            'section_section',
            [
                'label' => esc_html__( 'Section Styling', 'textdomain' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
			'sections_margin',
			[
				'label' => esc_html__( 'Section margin', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px',
					'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .gform_wrapper .gsection' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'sections_padding',
			[
				'label' => esc_html__( 'Section padding', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px',
					'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .gform_wrapper .gsection_title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'section_border',
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .gform_wrapper.gravity-theme .gsection',
                'default' => 'solid'
			]
		);

		$this->add_responsive_control(
            'section_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gform_wrapper .gsection' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );

		$this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'section_typography', 
                'global' => [

                ],
                'selector' => '{{WRAPPER}} .gform_wrapper .gsection_title',

            ]
        );


		$this->add_control(
            'section_text_color',
            [
                'label' => esc_html__( 'Text Color', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'global' => [

                ],
                'selectors' => [
                    '{{WRAPPER}} .gform_wrapper .gsection_title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'section_hr_color',
            [
                'label' => esc_html__( 'Border Color', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'global' => [

                ],
                'selectors' => [
                    '{{WRAPPER}} .gform_wrapper.gravity-theme .gsection' => 'border-color: {{VALUE}};',
                ],
            ]
        );

		$this->add_control(
            'section_color',
            [
                'label' => esc_html__( 'Background Color', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'global' => [

                ],
                'selectors' => [
                    '{{WRAPPER}} .gform_wrapper .gsection' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function fieldset_constrols(){
        // $this->start_controls_section(
        //     'fieldset_section_title',
        //     [
        //         'label' => esc_html__( 'Fieldsets', 'elementor-addon' ),
        //         'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        //     ]
        // );

        $this->add_control(
			'show_fieldset',
			[
				'label' => esc_html__( 'Show Fieldset Borders', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'textdomain' ),
				'label_off' => esc_html__( 'Hide', 'textdomain' ),
				'return_value' => 'yes',
				'default' => 'no',
                'separator' => 'before',
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'wrapper_consent_border',
				
				'selector' => '{{WRAPPER}} .gform_wrapper fieldset ',
                'condition' => [
                    'show_fieldset' => 'yes',
                ],
			]
		);

		$this->add_responsive_control(
            'wrapper_consent_border_radius',
            [
                'label' => esc_html__( 'Wrapper Border Radius', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gform_wrapper fieldset ' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
                'condition' => [
                    'show_fieldset' => 'yes',
                ],
            ]
        );
        // $this->end_controls_section();
    }

    protected function register_controls() {

		$this->register_main_controls();
        $this->register_form_settings_controls();
        $this->register_advanced_form_settings_controls();
        $this->register_input_controls();
        $this->register_emails_controls();
        $this->register_name_controls();
        $this->register_textarea_controls();
		$this->register_select_controls();
		$this->register_consent_controls();
		$this->register_checkbox_controls();
		$this->register_radio_controls();
        $this->register_submit_controls();
        $this->register_section_controls();


	}

    /**
     * Apply form settings overrides using Gravity Forms hooks
     * This method modifies the form object before rendering to apply widget overrides
     */
    protected function apply_form_settings_overrides( $settings ) {
        // Only apply overrides if form settings integration is enabled
        if ( $settings['inherit_form_settings'] !== 'yes' ) {
            return;
        }

        $form_id = $settings['gravity_form'];

        // Hook into Gravity Forms to modify form settings
        add_filter( 'gform_pre_render_' . $form_id, function( $form ) use ( $settings ) {
            return $this->modify_form_settings( $form, $settings );
        });

        add_filter( 'gform_pre_validation_' . $form_id, function( $form ) use ( $settings ) {
            return $this->modify_form_settings( $form, $settings );
        });

        add_filter( 'gform_pre_submission_filter_' . $form_id, function( $form ) use ( $settings ) {
            return $this->modify_form_settings( $form, $settings );
        });

        add_filter( 'gform_admin_pre_render_' . $form_id, function( $form ) use ( $settings ) {
            return $this->modify_form_settings( $form, $settings );
        });

        // Handle required indicator override with field content filter
        if ( $settings['override_required_indicator'] === 'yes' && ! empty( $settings['required_indicator_override'] ) ) {
            add_filter( 'gform_field_content_' . $form_id, function( $content, $field, $value, $lead_id, $form_id ) use ( $settings ) {
                return $this->modify_required_indicator_content( $content, $field, $settings );
            }, 10, 5 );
        }
    }

    /**
     * Modify form settings based on widget overrides
     */
    protected function modify_form_settings( $form, $settings ) {
        // Apply label placement override
        if ( $settings['override_label_placement'] === 'yes' && ! empty( $settings['label_placement_override'] ) ) {
            $form['labelPlacement'] = $settings['label_placement_override'];
        }

        // Apply description placement override
        if ( $settings['override_description_placement'] === 'yes' && ! empty( $settings['description_placement_override'] ) ) {
            $form['descriptionPlacement'] = $settings['description_placement_override'];
        }

        // Apply sub-label placement override
        if ( $settings['override_sublabel_placement'] === 'yes' && ! empty( $settings['sublabel_placement_override'] ) ) {
            $form['subLabelPlacement'] = $settings['sublabel_placement_override'];
        }

        // Note: Required indicator override is handled separately via gform_field_content filter
        // This is because the required indicator content is generated during field rendering

        // Apply validation summary override
        if ( $settings['override_validation_summary'] === 'yes' ) {
            $form['validationSummary'] = $settings['validation_summary_override'] === 'yes';
        }

        // Apply animation override
        if ( $settings['override_animation'] === 'yes' ) {
            $form['enableAnimation'] = $settings['animation_override'] === 'yes';
        }

        return $form;
    }

    /**
     * Modify required indicator content in field HTML
     * This method handles the required indicator override by modifying the actual HTML content
     */
    protected function modify_required_indicator_content( $content, $field, $settings ) {
        // Only modify if field is required and we have an override setting
        if ( ! $field->isRequired || empty( $settings['required_indicator_override'] ) ) {
            return $content;
        }

        // Get the new required indicator text
        $new_indicator = '';
        switch ( $settings['required_indicator_override'] ) {
            case 'text':
                $new_indicator = '(Required)';
                break;
            case 'asterisk':
                $new_indicator = '*';
                break;
            case 'custom':
                $new_indicator = ! empty( $settings['custom_required_indicator'] ) ? $settings['custom_required_indicator'] : '*';
                break;
            default:
                return $content; // No valid override, return original content
        }

        // Replace the required indicator in the content
        // Look for the gfield_required span and replace its content
        $pattern = '/<span class="gfield_required[^"]*"[^>]*>.*?<\/span>/';
        $replacement = '<span class="gfield_required gfield_required_' . esc_attr( $settings['required_indicator_override'] ) . '">' . esc_html( $new_indicator ) . '</span>';

        $modified_content = preg_replace( $pattern, $replacement, $content );

        // Add debug comment in editor mode
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
            $modified_content = '<!-- GF Widget: Required indicator overridden to "' . esc_html( $new_indicator ) . '" -->' . $modified_content;
        }

        return $modified_content;
    }

    /**
     * Get CSS classes for form settings overrides
     */
    protected function get_form_override_classes( $settings ) {
        $classes = array();

        // Add classes to indicate which settings are being overridden
        if ( $settings['inherit_form_settings'] === 'yes' ) {
            $classes[] = 'gf-widget-inherits-settings';

            if ( $settings['override_label_placement'] === 'yes' ) {
                $classes[] = 'gf-widget-override-labels';
            }

            if ( $settings['override_description_placement'] === 'yes' ) {
                $classes[] = 'gf-widget-override-descriptions';
            }

            if ( $settings['override_sublabel_placement'] === 'yes' ) {
                $classes[] = 'gf-widget-override-sublabels';
            }

            if ( $settings['override_required_indicator'] === 'yes' ) {
                $classes[] = 'gf-widget-override-required';
            }

            if ( $settings['override_validation_summary'] === 'yes' ) {
                $classes[] = 'gf-widget-override-validation';
            }

            if ( $settings['override_animation'] === 'yes' ) {
                $classes[] = 'gf-widget-override-animation';
            }
        }

        return implode( ' ', $classes );
    }

    /**
     * Get the effective label placement considering form settings and overrides
     */
    protected function get_effective_label_placement( $settings, $form_settings ) {
        // If form settings integration is enabled and label placement is overridden
        if ( $settings['inherit_form_settings'] === 'yes' && $settings['override_label_placement'] === 'yes' ) {
            return $settings['label_placement_override'];
        }

        // If form settings integration is enabled but not overridden, use form setting
        if ( $settings['inherit_form_settings'] === 'yes' && ! empty( $form_settings['labelPlacement'] ) ) {
            return $form_settings['labelPlacement'];
        }

        // Default fallback
        return 'top_label';
    }

	protected function render() {
        $settings = $this->get_settings_for_display();

        // Check if Gravity Forms is available
        if ( ! class_exists( 'GFForms' ) || ! function_exists( 'do_shortcode' ) ) {
            echo '<div class="gf-widget-error">';
            echo '<p>' . esc_html__( 'Gravity Forms is not available. Please install and activate Gravity Forms.', 'elementor-addon' ) . '</p>';
            echo '</div>';
            return;
        }

        // Check if a form is selected
        if ( empty( $settings['gravity_form'] ) ) {
            echo '<div class="gf-widget-error">';
            echo '<p>' . esc_html__( 'Please select a Gravity Form in the widget settings.', 'elementor-addon' ) . '</p>';
            echo '</div>';
            return;
        }

        // Apply form settings overrides before rendering
        $this->apply_form_settings_overrides( $settings );

        // Get form settings for display information
        $form_settings = $this->get_form_settings( $settings['gravity_form'] );

        $show_title = $settings['show_title'] == 'yes' ? 'true' : 'false';
        $show_description = $settings['show_description'] == 'yes' ? 'true' : 'false';
        $use_ajax = $settings['use_ajax'] == 'yes' ? 'true' : 'false';

		$consent_use_custom_checkbox = $settings['consent_use_custom_checkbox'] == 'yes' ? 'consent_gf_use_custom_checkbox' : '';
		$checkbox_use_custom_checkbox = $settings['checkbox_use_custom_checkbox'] == 'yes' ? 'checkbox_gf_use_custom_checkbox' : '';
		$radio_use_custom_radio = $settings['radio_use_custom_radio'] == 'yes' ? 'radio_gf_use_custom_radio' : '';
        $show_fieldset = $settings['show_fieldset'] == 'yes' ? '' : 'hide_fieldsets';

        // Get override classes
        $override_classes = $this->get_form_override_classes( $settings );

        // Get label placement for CSS targeting
        $label_placement = $this->get_effective_label_placement( $settings, $form_settings );

		?>





        <?php if ( $settings['inherit_form_settings'] === 'yes' && ! empty( $override_classes ) ): ?>
            <!-- Form Settings Override Notice (only visible in editor) -->
            <?php if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ): ?>
                <div class="gf-widget-override-notice" style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 10px; margin-bottom: 15px; border-radius: 4px; font-size: 12px;">
                    <strong><?php esc_html_e( 'Form Settings Overrides Active:', 'elementor-addon' ); ?></strong><br>
                    <?php if ( $settings['override_label_placement'] === 'yes' ): ?>
                        • <?php esc_html_e( 'Label Placement', 'elementor-addon' ); ?>: <?php echo esc_html( $settings['label_placement_override'] ); ?><br>
                    <?php endif; ?>
                    <?php if ( $settings['override_description_placement'] === 'yes' ): ?>
                        • <?php esc_html_e( 'Description Placement', 'elementor-addon' ); ?>: <?php echo esc_html( $settings['description_placement_override'] ); ?><br>
                    <?php endif; ?>
                    <?php if ( $settings['override_sublabel_placement'] === 'yes' ): ?>
                        • <?php esc_html_e( 'Sub-Label Placement', 'elementor-addon' ); ?>: <?php echo esc_html( $settings['sublabel_placement_override'] ); ?><br>
                    <?php endif; ?>
                    <?php if ( $settings['override_required_indicator'] === 'yes' ): ?>
                        • <?php esc_html_e( 'Required Indicator', 'elementor-addon' ); ?>: <?php echo esc_html( $settings['required_indicator_override'] ); ?><br>
                    <?php endif; ?>
                    <?php if ( $settings['override_validation_summary'] === 'yes' ): ?>
                        • <?php esc_html_e( 'Validation Summary', 'elementor-addon' ); ?>: <?php echo $settings['validation_summary_override'] === 'yes' ? esc_html__( 'Enabled', 'elementor-addon' ) : esc_html__( 'Disabled', 'elementor-addon' ); ?><br>
                    <?php endif; ?>
                    <?php if ( $settings['override_animation'] === 'yes' ): ?>
                        • <?php esc_html_e( 'Animations', 'elementor-addon' ); ?>: <?php echo $settings['animation_override'] === 'yes' ? esc_html__( 'Enabled', 'elementor-addon' ) : esc_html__( 'Disabled', 'elementor-addon' ); ?><br>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

<div class="gf-widget <?php echo esc_attr( $consent_use_custom_checkbox ); ?> <?php echo esc_attr( $checkbox_use_custom_checkbox ); ?> <?php echo esc_attr( $radio_use_custom_radio ); ?> <?php echo esc_attr( $show_fieldset ); ?> <?php echo esc_attr( $override_classes ); ?>" data-label-placement="<?php echo esc_attr( $label_placement ); ?>">
    <?php echo do_shortcode( '[gravityform id="' . esc_attr( $settings['gravity_form'] ) . '" title="' . esc_attr( $show_title ) . '" description="' . esc_attr( $show_description ) . '" ajax="' . esc_attr( $use_ajax ) . '"]' ); ?>
</div>

<?php if ( $settings['label_display'] === 'auto' ): ?>
<script>
// Handle auto label display based on label placement
(function() {
    const widget = document.querySelector('[data-label-placement="<?php echo esc_js( $label_placement ); ?>"]');
    if (widget) {
        const labels = widget.querySelectorAll('.gfield_label:not(.gfield_consent_label)');
        const placement = widget.getAttribute('data-label-placement');

        labels.forEach(function(label) {
            // Remove any existing display style set by Elementor
            label.style.removeProperty('display');

            // Let CSS handle the display based on label placement
            switch(placement) {
                case 'left_label':
                case 'right_label':
                    // CSS flexbox handles this
                    break;
                case 'top_label':
                default:
                    // Default block display for top labels
                    break;
            }
        });
    }
})();
</script>
<?php endif; ?>






<?php
	}

    

}