<?php
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
        $results = GFAPI::get_forms();

        $options = [];

        foreach ($results as $result){
            $options[$result['id']] = $result['title'];
        }

        return $options;

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

        $this->add_control(
			'label_display',
			[
				'label' => esc_html__( 'Label Display', 'textdomain' ),
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
                    '{{WRAPPER}} .gform_wrapper .gfield_label:not(.gfield_consent_label)' => 'display: {{VALUE}};',
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

	protected function render() {
        $settings = $this->get_settings_for_display(); 

        $show_title = $settings['show_title'] == 'yes' ? 'true' : 'false';
        $show_description = $settings['show_description'] == 'yes' ? 'true' : 'false';
        $use_ajax = $settings['use_ajax'] == 'yes' ? 'true' : 'false';

		$consent_use_custom_checkbox = $settings['consent_use_custom_checkbox'] == 'yes' ? 'consent_gf_use_custom_checkbox' : '';
		$checkbox_use_custom_checkbox = $settings['checkbox_use_custom_checkbox'] == 'yes' ? 'checkbox_gf_use_custom_checkbox' : '';
		$radio_use_custom_radio = $settings['radio_use_custom_radio'] == 'yes' ? 'radio_gf_use_custom_radio' : '';
        $show_fieldset = $settings['show_fieldset'] == 'yes' ? '' : 'hide_fieldsets';
        
		?>





<div
    class="gf-widget <?php echo $consent_use_custom_checkbox; ?>  <?php echo $checkbox_use_custom_checkbox; ?>  <?php echo $radio_use_custom_radio; ?>  <?php echo $show_fieldset; ?>">
    <?php echo do_shortcode('[gravityform id="'.$settings['gravity_form'].'" title="'.$show_title.'" description="'.$show_description.'" ajax="'.$use_ajax.'"]'); ?>
</div>






<?php
	}

    

}