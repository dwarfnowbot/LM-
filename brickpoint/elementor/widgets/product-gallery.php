<?php
/**
 * Elementor: BrickPoint Product Gallery.
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
 * Product gallery widget.
 */
class Product_Gallery extends Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'bp_product_gallery';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Product Gallery', 'brickpoint' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-gallery-grid';
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
				'label' => __( 'Gallery', 'brickpoint' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'product_id',
			array(
				'label'       => __( 'Product', 'brickpoint' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => brickpoint_elementor_post_options( 'bp_product' ),
				'description' => __( 'Leave empty inside a Single Product template to use the product being viewed.', 'brickpoint' ),
			)
		);

		$this->add_control(
			'thumbs_position',
			array(
				'label'   => __( 'Thumbnails position', 'brickpoint' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'bottom',
				'options' => array(
					'bottom' => __( 'Below the main image', 'brickpoint' ),
					'left'   => __( 'Left of the main image', 'brickpoint' ),
					'none'   => __( 'Hidden', 'brickpoint' ),
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

		$this->add_responsive_control(
			'radius',
			array(
				'label'      => __( 'Radius (px)', 'brickpoint' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 48,
					),
				),
				'default'    => array(
					'size' => 16,
				),
				'selectors'  => array(
					'{{WRAPPER}} .bp-gallery__main, {{WRAPPER}} .bp-gallery__thumb' => 'border-radius: {{SIZE}}px; overflow: hidden;',
				),
			)
		);

		$this->add_responsive_control(
			'thumb_size',
			array(
				'label'      => __( 'Thumbnail size (px)', 'brickpoint' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 48,
						'max' => 160,
					),
				),
				'default'    => array(
					'size' => 92,
				),
				'selectors'  => array(
					'{{WRAPPER}} .bp-gallery__thumb' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
				),
			)
		);

		$this->add_control(
			'active_border',
			array(
				'label'     => __( 'Active thumbnail color', 'brickpoint' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#c1440e',
				'selectors' => array(
					'{{WRAPPER}} .bp-gallery__thumb.is-active' => 'border-color: {{VALUE}}; box-shadow: 0 0 0 2px {{VALUE}};',
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
				esc_html__( 'Select a product, or place this widget in a Single Product template.', 'brickpoint' )
			);

			return;
		}

		brickpoint_product_gallery(
			$product_id,
			array(
				'class' => 'bp-gallery--thumbs-' . sanitize_html_class( $s['thumbs_position'] ),
			)
		);
	}
}
