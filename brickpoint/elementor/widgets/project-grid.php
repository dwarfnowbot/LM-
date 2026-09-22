<?php
/**
 * Elementor: BrickPoint Projects Grid.
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
 * Projects grid widget.
 */
class Project_Grid extends Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'bp_project_grid';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Projects Grid', 'brickpoint' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-gallery-masonry';
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
				'label' => __( 'Query', 'brickpoint' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'category',
			array(
				'label'   => __( 'Project category', 'brickpoint' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->project_category_options(),
			)
		);

		$this->add_control(
			'featured_only',
			array(
				'label'        => __( 'Featured projects only', 'brickpoint' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'limit',
			array(
				'label'   => __( 'Number of projects', 'brickpoint' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 6,
				'min'     => 1,
				'max'     => 24,
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => __( 'Order by', 'brickpoint' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => array(
					'date'       => __( 'Date published', 'brickpoint' ),
					'menu_order' => __( 'Custom order', 'brickpoint' ),
					'rand'       => __( 'Random', 'brickpoint' ),
				),
			)
		);

		$this->add_control(
			'show_meta',
			array(
				'label'        => __( 'Show location / status / disclaimer', 'brickpoint' ),
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

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => __( 'Gap (px)', 'brickpoint' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 80,
					),
				),
				'default'    => array(
					'size' => 28,
				),
				'selectors'  => array(
					'{{WRAPPER}} .bp-grid-cols' => '--bp-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
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
					'{{WRAPPER}} .bp-project-card .bp-media' => '--bp-media-ratio: {{VALUE}};',
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

		$this->register_card_style( 'card', '{{WRAPPER}} .bp-project-card' );
		$this->register_text_style( 'title', __( 'Project title', 'brickpoint' ), '{{WRAPPER}} .bp-project-card__title' );
		$this->register_text_style( 'text', __( 'Description', 'brickpoint' ), '{{WRAPPER}} .bp-project-card__text' );

		$this->add_control(
			'chip_bg',
			array(
				'label'     => __( 'Category chip background', 'brickpoint' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .bp-chip' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'note_color',
			array(
				'label'     => __( 'Illustrative label color', 'brickpoint' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .bp-badge--note, {{WRAPPER}} .bp-disclaimer' => 'color: {{VALUE}};',
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

		$query = brickpoint_project_query(
			array(
				'category' => $s['category'],
				'featured' => 'yes' === $s['featured_only'],
				'per_page' => (int) $s['limit'],
				'orderby'  => $s['orderby'],
			)
		);

		if ( ! $query->have_posts() ) {
			printf(
				'<p class="bp-empty">%s</p>',
				esc_html__( 'No projects yet. Add them in WordPress → Projects. Mark concept visuals with the “illustrative” checkbox so visitors are not misled.', 'brickpoint' )
			);

			return;
		}

		printf(
			'<div class="bp-grid-cols bp-project-grid" style="%s">',
			esc_attr( $this->grid_vars( $s ) )
		);

		while ( $query->have_posts() ) {
			$query->the_post();

			brickpoint_project_card(
				get_the_ID(),
				array(
					'show_meta' => 'yes' === $s['show_meta'],
				)
			);
		}

		echo '</div>';

		wp_reset_postdata();
	}
}
