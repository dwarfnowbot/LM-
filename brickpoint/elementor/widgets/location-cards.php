<?php
/**
 * Elementor: BrickPoint Location Cards.
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
 * Location cards widget.
 */
class Location_Cards extends Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'bp_location_cards';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Location Cards', 'brickpoint' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-google-maps';
	}

	/**
	 * Controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->register_heading_controls( 'head', __( 'Section Heading', 'brickpoint' ) );

		$this->start_controls_section(
			'query_section',
			array(
				'label' => __( 'Locations', 'brickpoint' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'limit',
			array(
				'label'   => __( 'Number of locations', 'brickpoint' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => -1,
				'min'     => -1,
				'max'     => 40,
			)
		);

		$this->add_control(
			'show_video',
			array(
				'label'        => __( 'Location video (if set)', 'brickpoint' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_products',
			array(
				'label'        => __( 'Show “Available here” product list', 'brickpoint' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'show_address',
			array(
				'label'        => __( 'Address', 'brickpoint' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_hours',
			array(
				'label'        => __( 'Opening hours', 'brickpoint' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_buttons',
			array(
				'label'        => __( 'Google Maps / Directions / Call / WhatsApp buttons', 'brickpoint' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'notice',
			array(
				'label'       => __( 'Compliance note', 'brickpoint' ),
				'type'        => Controls_Manager::RAW_HTML,
				'raw'         => __( 'Map buttons only render when a real Google Maps link is saved on the location. The theme never displays a fake map.', 'brickpoint' ),
				'content_classes' => 'elementor-descriptor',
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

		$this->add_responsive_control(
			'image_ratio',
			array(
				'label'     => __( 'Image aspect ratio', 'brickpoint' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '1-1',
				'options'   => array(
					'1-1'  => '1:1 (square)',
					'4-3'  => '4:3',
					'16-9' => '16:9',
				),
				'selectors' => array(
					'{{WRAPPER}} .bp-location-card .bp-media' => '--bp-media-ratio: {{VALUE}};',
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

		$this->register_card_style( 'card', '{{WRAPPER}} .bp-location-card' );
		$this->register_text_style( 'title', __( 'Location name', 'brickpoint' ), '{{WRAPPER}} .bp-location-card__title' );
		$this->register_text_style( 'company', __( 'Company name', 'brickpoint' ), '{{WRAPPER}} .bp-location-card .bp-eyebrow' );
		$this->register_text_style( 'address', __( 'Address', 'brickpoint' ), '{{WRAPPER}} .bp-location-card__address' );

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

		$query = brickpoint_location_query(
			array(
				'per_page' => (int) $s['limit'],
			)
		);

		if ( ! $query->have_posts() ) {
			printf(
				'<p class="bp-empty">%s</p>',
				esc_html__( 'No locations published yet. Add your bhattas and office in WordPress → Locations.', 'brickpoint' )
			);

			return;
		}

		// Visibility toggles are expressed as modifier classes on the grid
		// (styled in assets/css/main.css) so no inline <style> is printed.
		$classes = array( 'bp-grid-cols', 'bp-location-grid' );

		if ( 'yes' !== $s['show_address'] ) {
			$classes[] = 'bp-location-grid--no-address';
		}

		if ( 'yes' !== $s['show_hours'] ) {
			$classes[] = 'bp-location-grid--no-hours';
		}

		if ( 'yes' !== $s['show_buttons'] ) {
			$classes[] = 'bp-location-grid--no-buttons';
		}

		printf(
			'<div class="%1$s" style="%2$s">',
			esc_attr( implode( ' ', $classes ) ),
			esc_attr( $this->grid_vars( $s ) )
		);

		while ( $query->have_posts() ) {
			$query->the_post();

			brickpoint_location_card(
				get_the_ID(),
				array(
					'show_video'    => 'yes' === $s['show_video'],
					'show_products' => 'yes' === $s['show_products'],
				)
			);
		}

		echo '</div>';

		wp_reset_postdata();
	}
}
