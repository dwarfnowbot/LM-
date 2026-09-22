<?php
/**
 * Elementor: BrickPoint Product Price / Availability.
 *
 * Works on a product template (auto-detects the current product) or with a
 * manually selected product.
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
 * Product price widget.
 */
class Product_Price extends Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'bp_product_price';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Product Price & Availability', 'brickpoint' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-price-list';
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
				'label' => __( 'Content', 'brickpoint' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'product_id',
			array(
				'label'       => __( 'Product', 'brickpoint' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => brickpoint_elementor_post_options( 'bp_product' ),
				'description' => __( 'Leave empty to use the product being viewed (best inside a Single Product template).', 'brickpoint' ),
			)
		);

		$toggles = array(
			'show_price'      => __( 'Price', 'brickpoint' ),
			'show_price_label' => __( 'Price label', 'brickpoint' ),
			'show_unit'       => __( 'Unit', 'brickpoint' ),
			'show_availability' => __( 'Availability badge', 'brickpoint' ),
			'show_meta'       => __( 'Meta list (category, minimum order, delivery area, reference)', 'brickpoint' ),
		);

		foreach ( $toggles as $key => $label ) {
			$this->add_control(
				$key,
				array(
					'label'        => $label,
					'type'         => Controls_Manager::SWITCHER,
					'return_value' => 'yes',
					'default'      => 'yes',
				)
			);
		}

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			array(
				'label' => __( 'Style', 'brickpoint' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->register_text_style( 'price', __( 'Price', 'brickpoint' ), '{{WRAPPER}} .bp-price__value', array( 'default' => '#c1440e' ) );
		$this->register_text_style( 'label', __( 'Price label', 'brickpoint' ), '{{WRAPPER}} .bp-price__label' );
		$this->register_text_style( 'unit', __( 'Unit', 'brickpoint' ), '{{WRAPPER}} .bp-price__unit' );
		$this->register_text_style( 'meta', __( 'Meta labels', 'brickpoint' ), '{{WRAPPER}} .bp-product-meta dt' );

		$this->add_control(
			'badge_bg',
			array(
				'label'     => __( 'Availability badge background', 'brickpoint' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .bp-product-meta .bp-badge, {{WRAPPER}} .bp-price-block .bp-badge' => 'background-color: {{VALUE}};',
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

		$product_id = (int) $s['product_id'];
		$current    = $this->current_post_id();

		if ( ! $product_id && 'bp_product' === get_post_type( $current ) ) {
			$product_id = $current;
		}

		if ( ! $product_id ) {
			printf(
				'<p class="bp-empty">%s</p>',
				esc_html__( 'Choose a product, or place this widget inside a Single Product template.', 'brickpoint' )
			);

			return;
		}

		$availability = brickpoint_availability_badge( $product_id );

		echo '<div class="bp-price-block">';

		if ( 'yes' === $s['show_price'] || 'yes' === $s['show_price_label'] ) {
			echo brickpoint_price_html( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper.
				$product_id,
				array(
					'hide_label' => 'yes' !== $s['show_price_label'],
					'hide_unit'  => 'yes' !== $s['show_unit'],
				)
			);
		}

		if ( 'yes' === $s['show_availability'] && $availability['label'] ) {
			printf(
				'<span class="bp-badge bp-badge--availability %1$s">%2$s</span>',
				esc_attr( $availability['status'] ),
				esc_html( $availability['label'] )
			);
		}

		echo '</div>';

		if ( 'yes' === $s['show_meta'] ) {
			brickpoint_product_meta_list( $product_id );
		}
	}
}
