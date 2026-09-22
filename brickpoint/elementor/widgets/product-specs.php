<?php
/**
 * Elementor: BrickPoint Product Specifications & Features.
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
 * Product specs widget.
 */
class Product_Specs extends Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'bp_product_specs';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Product Specs & Features', 'brickpoint' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-bullet-list';
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
				'description' => __( 'Leave empty inside a Single Product template.', 'brickpoint' ),
			)
		);

		$this->add_control(
			'specs_title',
			array(
				'label'   => __( 'Specifications heading', 'brickpoint' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Specifications', 'brickpoint' ),
			)
		);

		$this->add_control(
			'features_title',
			array(
				'label'   => __( 'Features heading', 'brickpoint' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Highlights', 'brickpoint' ),
			)
		);

		$this->add_control(
			'show_specs',
			array(
				'label'        => __( 'Show specifications table', 'brickpoint' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_features',
			array(
				'label'        => __( 'Show features list', 'brickpoint' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_description',
			array(
				'label'        => __( 'Show full description', 'brickpoint' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
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

		$this->register_text_style( 'label', __( 'Specification labels', 'brickpoint' ), '{{WRAPPER}} .bp-specs__table th' );
		$this->register_text_style( 'value', __( 'Specification values', 'brickpoint' ), '{{WRAPPER}} .bp-specs__table td' );
		$this->register_text_style( 'feature', __( 'Feature items', 'brickpoint' ), '{{WRAPPER}} .bp-features-list li' );

		$this->add_control(
			'row_border',
			array(
				'label'     => __( 'Row divider color', 'brickpoint' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .bp-specs__table tr' => 'border-bottom: 1px solid {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'check_color',
			array(
				'label'     => __( 'Feature check icon color', 'brickpoint' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#c1440e',
				'selectors' => array(
					'{{WRAPPER}} .bp-features-list svg' => 'color: {{VALUE}};',
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

		if ( 'yes' === $s['show_description'] ) {
			$content = get_post_field( 'post_content', $product_id );

			if ( $content ) {
				echo '<div class="bp-product-description">' . wp_kses_post( apply_filters( 'the_content', $content ) ) . '</div>';
			}
		}

		if ( 'yes' === $s['show_specs'] ) {
			brickpoint_product_specs(
				$product_id,
				array(
					'title' => $s['specs_title'],
				)
			);
		}

		if ( 'yes' === $s['show_features'] ) {
			brickpoint_product_features(
				$product_id,
				array(
					'title' => $s['features_title'],
				)
			);
		}
	}
}
