<?php
/**
 * Elementor: BrickPoint Contact / Quotation Form.
 *
 * @package BrickPoint
 */

namespace BrickPoint\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/base.php';

use Elementor\Controls_Manager;

/**
 * Contact form widget.
 */
class Contact_Form extends Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'bp_contact_form';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Contact / Quotation Form', 'brickpoint' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-form-horizontal';
	}

	/**
	 * Controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Form', 'brickpoint' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'heading',
			array(
				'label'   => __( 'Heading', 'brickpoint' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Request a quotation', 'brickpoint' ),
			)
		);

		$this->add_control(
			'subtext',
			array(
				'label'   => __( 'Supporting text', 'brickpoint' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 2,
				'default' => __( 'Share your material list, quantity and delivery location - our team will confirm availability and rates.', 'brickpoint' ),
			)
		);

		$this->add_control(
			'button',
			array(
				'label'   => __( 'Button label', 'brickpoint' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Send Inquiry', 'brickpoint' ),
			)
		);

		$this->add_control(
			'context',
			array(
				'label'       => __( 'Context label (saved with the inquiry)', 'brickpoint' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Website inquiry form', 'brickpoint' ),
				'description' => __( 'Helps you see which page the inquiry came from.', 'brickpoint' ),
			)
		);

		$this->add_control(
			'show_fields',
			array(
				'label'       => __( 'Fields', 'brickpoint' ),
				'type'        => Controls_Manager::RAW_HTML,
				'raw'         => __( 'Name, Phone / WhatsApp, Email, Company, Required material, Quantity, Delivery location and Message. Field definitions can be extended with the <code>brickpoint_contact_fields</code> filter.', 'brickpoint' ),
				'content_classes' => 'elementor-descriptor',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			array(
				'label' => __( 'Style', 'brickpoint' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'form_bg',
			array(
				'label'     => __( 'Form background', 'brickpoint' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .bp-form' => 'background-color: {{VALUE}}; padding: 32px;',
				),
			)
		);

		$this->add_control(
			'field_bg',
			array(
				'label'     => __( 'Field background', 'brickpoint' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .bp-form input, {{WRAPPER}} .bp-form textarea' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'field_border',
			array(
				'label'     => __( 'Field border color', 'brickpoint' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .bp-form input, {{WRAPPER}} .bp-form textarea' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'btn_bg',
			array(
				'label'     => __( 'Submit button background', 'brickpoint' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#c1440e',
				'selectors' => array(
					'{{WRAPPER}} .bp-form .bp-btn--primary' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'btn_color',
			array(
				'label'     => __( 'Submit button text color', 'brickpoint' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .bp-form .bp-btn--primary' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'radius',
			array(
				'label'      => __( 'Field radius (px)', 'brickpoint' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 40,
					),
				),
				'default'    => array(
					'size' => 10,
				),
				'selectors'  => array(
					'{{WRAPPER}} .bp-form input, {{WRAPPER}} .bp-form textarea' => 'border-radius: {{SIZE}}px;',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	protected function render() {
		$s = $this->get_settings_for_display();

		brickpoint_contact_form(
			array(
				'heading' => $s['heading'],
				'subtext' => $s['subtext'],
				'button'  => $s['button'],
				'context' => $s['context'],
			)
		);
	}
}
