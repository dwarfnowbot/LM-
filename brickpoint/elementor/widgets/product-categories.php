<?php
/**
 * Elementor: BrickPoint Product Categories.
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
 * Product categories widget.
 */
class Product_Categories extends Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'bp_product_categories';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Product Categories', 'brickpoint' );
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
		$this->register_heading_controls( 'head', __( 'Section Heading', 'brickpoint' ) );

		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Categories', 'brickpoint' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'home_only',
			array(
				'label'        => __( 'Only categories marked “show on homepage”', 'brickpoint' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'limit',
			array(
				'label'   => __( 'Number of categories', 'brickpoint' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 12,
				'min'     => 1,
				'max'     => 40,
			)
		);

		$this->add_control(
			'show_whatsapp',
			array(
				'label'        => __( 'WhatsApp inquiry button', 'brickpoint' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_count',
			array(
				'label'        => __( 'Product count', 'brickpoint' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'layout_section',
			array(
				'label' => __( 'Layout', 'brickpoint' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->register_column_controls( 'columns' );

		$this->add_control(
			'style',
			array(
				'label'   => __( 'Card style', 'brickpoint' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'card',
				'options' => array(
					'card'  => __( 'Image card', 'brickpoint' ),
					'icon'  => __( 'Compact icon tile', 'brickpoint' ),
					'wide'  => __( 'Wide rows', 'brickpoint' ),
				),
			)
		);

		$this->add_control(
			'image_ratio',
			array(
				'label'     => __( 'Image aspect ratio', 'brickpoint' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '4-3',
				'options'   => array(
					'4-3'  => '4:3',
					'1-1'  => '1:1',
					'3-2'  => '3:2',
					'16-9' => '16:9',
				),
				'selectors' => array(
					'{{WRAPPER}} .bp-category-card .bp-media' => '--bp-media-ratio: {{VALUE}};',
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

		$this->register_card_style( 'card', '{{WRAPPER}} .bp-category-card' );
		$this->register_text_style( 'title', __( 'Category name', 'brickpoint' ), '{{WRAPPER}} .bp-category-card__title' );
		$this->register_text_style( 'desc', __( 'Description', 'brickpoint' ), '{{WRAPPER}} .bp-category-card__desc' );

		$this->add_control(
			'icon_color',
			array(
				'label'     => __( 'Icon color', 'brickpoint' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .bp-category-card__icon' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_bg',
			array(
				'label'     => __( 'Icon background', 'brickpoint' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .bp-category-card__icon' => 'background-color: {{VALUE}};',
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

		$this->render_heading( $s, 'head' );

		$terms = brickpoint_get_product_categories(
			array(
				'number'    => (int) $s['limit'],
				'home_only' => 'yes' === $s['home_only'],
			)
		);

		if ( ! $terms ) {
			echo '<p class="bp-empty">' . esc_html__( 'No product categories yet. Add them in Products → Product Categories.', 'brickpoint' ) . '</p>';
			return;
		}

		printf(
			'<div class="bp-grid-cols bp-category-grid bp-category-grid--%1$s" style="%2$s">',
			esc_attr( $s['style'] ),
			esc_attr( $this->grid_vars( $s ) )
		);

		foreach ( $terms as $term ) {
			brickpoint_category_card(
				$term,
				array(
					'show_whatsapp' => 'yes' === $s['show_whatsapp'],
					'show_count'    => 'yes' === $s['show_count'],
					'style'         => $s['style'],
				)
			);
		}

		echo '</div>';
	}
}
