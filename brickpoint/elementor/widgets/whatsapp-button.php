<?php
/**
 * Elementor: BrickPoint WhatsApp Button.
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
 * WhatsApp button widget.
 */
class Whatsapp_Button extends Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'bp_whatsapp_button';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'WhatsApp Button', 'brickpoint' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-whatsapp';
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
				'label' => __( 'Button', 'brickpoint' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'label',
			array(
				'label'   => __( 'Button label', 'brickpoint' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Order on WhatsApp', 'brickpoint' ),
			)
		);

		$this->add_control(
			'context',
			array(
				'label'   => __( 'Message context', 'brickpoint' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'auto',
				'options' => array(
					'auto'    => __( 'Auto - product template uses the product details', 'brickpoint' ),
					'product' => __( 'Specific product', 'brickpoint' ),
					'general' => __( 'General / custom message', 'brickpoint' ),
				),
			)
		);

		$this->add_control(
			'product_id',
			array(
				'label'     => __( 'Product', 'brickpoint' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => brickpoint_elementor_post_options( 'bp_product' ),
				'condition' => array( 'context' => array( 'product', 'auto' ) ),
			)
		);

		$this->add_control(
			'message',
			array(
				'label'     => __( 'Custom message', 'brickpoint' ),
				'type'      => Controls_Manager::TEXTAREA,
				'rows'      => 4,
				'condition' => array( 'context' => 'general' ),
				'description' => __( 'Leave empty to use the default message from the Customizer.', 'brickpoint' ),
			)
		);

		$this->add_control(
			'number',
			array(
				'label'       => __( 'WhatsApp number override', 'brickpoint' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => '923152850818',
				'description' => __( 'Leave empty to use the number from Theme Settings.', 'brickpoint' ),
			)
		);

		$this->add_control(
			'style',
			array(
				'label'   => __( 'Style', 'brickpoint' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'whatsapp',
				'options' => array(
					'whatsapp' => __( 'WhatsApp green', 'brickpoint' ),
					'primary'  => __( 'Brand solid', 'brickpoint' ),
					'ghost'    => __( 'Outline', 'brickpoint' ),
				),
			)
		);

		$this->add_control(
			'size',
			array(
				'label'   => __( 'Size', 'brickpoint' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'lg',
				'options' => array(
					'sm' => __( 'Small', 'brickpoint' ),
					''   => __( 'Medium', 'brickpoint' ),
					'lg' => __( 'Large', 'brickpoint' ),
				),
			)
		);

		$this->add_control(
			'show_icon',
			array(
				'label'        => __( 'Show WhatsApp icon', 'brickpoint' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_phone',
			array(
				'label'        => __( 'Also show a phone call button', 'brickpoint' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'full_width',
			array(
				'label'        => __( 'Full width', 'brickpoint' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
				'selectors'    => array(
					'{{WRAPPER}} .bp-wa-wrap' => 'display:flex; flex-wrap:wrap; gap:12px;',
					'{{WRAPPER}} .bp-wa-wrap .bp-btn' => 'flex:1 1 100%; justify-content:center;',
				),
			)
		);

		$this->add_control(
			'align',
			array(
				'label'     => __( 'Alignment', 'brickpoint' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'left',
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'brickpoint' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'brickpoint' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'brickpoint' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .bp-wa-wrap' => 'text-align: {{VALUE}};',
				),
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
			'bg',
			array(
				'label'     => __( 'Background', 'brickpoint' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#25d366',
				'selectors' => array(
					'{{WRAPPER}} .bp-btn--whatsapp' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'color',
			array(
				'label'     => __( 'Text color', 'brickpoint' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .bp-btn--whatsapp' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'radius',
			array(
				'label'      => __( 'Border radius (px)', 'brickpoint' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .bp-btn' => 'border-radius: {{SIZE}}px;',
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

		$number   = $s['number'] ? $s['number'] : '';
		$context  = $s['context'];
		$product  = (int) $s['product_id'];

		if ( ! $product && 'auto' === $context ) {
			$current = $this->current_post_id();

			if ( 'bp_product' === get_post_type( $current ) ) {
				$product = $current;
			}
		}

		if ( 'product' === $context && $product ) {
			$message = brickpoint_product_inquiry_message( $product );
		} elseif ( $product ) {
			$message = brickpoint_product_inquiry_message( $product );
		} elseif ( $s['message'] ) {
			$message = $s['message'];
		} else {
			$message = brickpoint_general_inquiry_message( is_singular() ? get_the_title() : '' );
		}

		echo '<div class="bp-wa-wrap">';

		echo brickpoint_whatsapp_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside helper.
			array(
				'label'   => $s['label'] ? $s['label'] : __( 'WhatsApp Us', 'brickpoint' ),
				'message' => $message,
				'number'  => $number,
				'class'   => 'bp-btn bp-btn--' . sanitize_html_class( $s['style'] ) . ( $s['size'] ? ' bp-btn--' . sanitize_html_class( $s['size'] ) : '' ),
				'icon'    => 'yes' === $s['show_icon'],
			)
		);

		if ( 'yes' === $s['show_phone'] ) {
			brickpoint_phone_button(
				array(
					'label' => __( 'Call Now', 'brickpoint' ),
					'class' => 'bp-btn bp-btn--ghost' . ( $s['size'] ? ' bp-btn--' . sanitize_html_class( $s['size'] ) : '' ),
				)
			);
		}

		echo '</div>';
	}
}
