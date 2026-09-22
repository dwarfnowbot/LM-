<?php
/**
 * Elementor: BrickPoint Video Grid (with optional category filters).
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
 * Video grid widget.
 */
class Video_Grid extends Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'bp_video_grid';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Video Grid', 'brickpoint' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-video-playlist';
	}

	/**
	 * Controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->register_heading_controls( 'head', __( 'Section Heading', 'brickpoint' ) );

		// ------------------------------------------------------------ Query.
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
				'label'    => __( 'Video category', 'brickpoint' ),
				'type'     => Controls_Manager::SELECT2,
				'multiple' => true,
				'options'  => $this->video_category_options(),
			)
		);

		$this->add_control(
			'featured_only',
			array(
				'label'        => __( 'Featured videos only', 'brickpoint' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'relation',
			array(
				'label'   => __( 'Related to current item', 'brickpoint' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''         => __( 'No - use the query above', 'brickpoint' ),
					'product'  => __( 'Videos linked to this product', 'brickpoint' ),
					'project'  => __( 'Videos linked to this project', 'brickpoint' ),
					'location' => __( 'Videos linked to this location', 'brickpoint' ),
				),
			)
		);

		$this->add_control(
			'limit',
			array(
				'label'   => __( 'Number of videos', 'brickpoint' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 6,
				'min'     => 1,
				'max'     => 48,
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => __( 'Order by', 'brickpoint' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => array(
					'date'  => __( 'Date published', 'brickpoint' ),
					'title' => __( 'Title', 'brickpoint' ),
					'order' => __( 'Custom display order', 'brickpoint' ),
					'rand'  => __( 'Random', 'brickpoint' ),
				),
			)
		);

		$this->add_control(
			'pagination',
			array(
				'label'   => __( 'Pagination / Load more', 'brickpoint' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'none'      => __( 'None', 'brickpoint' ),
					'load_more' => __( 'Load more button', 'brickpoint' ),
					'numbers'   => __( 'Numbered pagination', 'brickpoint' ),
				),
			)
		);

		$this->add_control(
			'show_filters',
			array(
				'label'        => __( 'Show category filter buttons', 'brickpoint' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->end_controls_section();

		// ----------------------------------------------------------- Layout.
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
			'thumb_ratio',
			array(
				'label'     => __( 'Thumbnail aspect ratio', 'brickpoint' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '16-9',
				'options'   => array(
					'16-9' => '16:9',
					'4-3'  => '4:3',
					'1-1'  => '1:1',
					'9-16' => '9:16 (vertical)',
				),
				'selectors' => array(
					'{{WRAPPER}} .bp-video-card__media' => '--bp-media-ratio: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'image_fit',
			array(
				'label'     => __( 'Image fit', 'brickpoint' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'cover',
				'options'   => array(
					'cover'   => __( 'Cover', 'brickpoint' ),
					'contain' => __( 'Contain', 'brickpoint' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .bp-video-card .bp-media__img' => 'object-fit: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'hover',
			array(
				'label'   => __( 'Hover animation', 'brickpoint' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'zoom',
				'options' => array(
					'zoom'  => __( 'Image zoom', 'brickpoint' ),
					'lift'  => __( 'Lift', 'brickpoint' ),
					'both'  => __( 'Both', 'brickpoint' ),
					'none'  => __( 'None', 'brickpoint' ),
				),
			)
		);

		$this->end_controls_section();

		// ---------------------------------------------------------- Content.
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Card Content', 'brickpoint' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$toggles = array(
			'show_thumb'   => __( 'Thumbnail', 'brickpoint' ),
			'show_play'    => __( 'Play button', 'brickpoint' ),
			'show_title'   => __( 'Title', 'brickpoint' ),
			'show_desc'    => __( 'Short description', 'brickpoint' ),
			'show_cat'     => __( 'Category', 'brickpoint' ),
			'show_duration' => __( 'Duration', 'brickpoint' ),
			'show_date'    => __( 'Date', 'brickpoint' ),
			'show_featured_badge' => __( 'Featured badge', 'brickpoint' ),
			'show_button'  => __( 'Watch button', 'brickpoint' ),
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

		// ------------------------------------------------------------ Style.
		$this->start_controls_section(
			'style_section',
			array(
				'label' => __( 'Style', 'brickpoint' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->register_card_style( 'card', '{{WRAPPER}} .bp-video-card' );
		$this->register_text_style( 'title', __( 'Title', 'brickpoint' ), '{{WRAPPER}} .bp-video-card__title' );
		$this->register_text_style( 'cat', __( 'Category', 'brickpoint' ), '{{WRAPPER}} .bp-video-card__cat' );
		$this->register_text_style( 'desc', __( 'Description', 'brickpoint' ), '{{WRAPPER}} .bp-video-card__desc' );

		$this->add_control(
			'play_size',
			array(
				'label'      => __( 'Play button size (px)', 'brickpoint' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 32,
						'max' => 110,
					),
				),
				'default'    => array(
					'size' => 62,
				),
				'selectors'  => array(
					'{{WRAPPER}} .bp-video-card .bp-play' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
				),
			)
		);

		$this->add_control(
			'play_bg',
			array(
				'label'     => __( 'Play button background', 'brickpoint' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#c1440e',
				'selectors' => array(
					'{{WRAPPER}} .bp-video-card .bp-play' => 'background-color: {{VALUE}}; color:#fff;',
				),
			)
		);

		$this->add_control(
			'overlay_opacity',
			array(
				'label'     => __( 'Overlay opacity (%)', 'brickpoint' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range'     => array(
					'%' => array(
						'min' => 0,
						'max' => 90,
					),
				),
				'default'   => array(
					'size' => 35,
				),
				'selectors' => array(
					'{{WRAPPER}} .bp-video-card__overlay' => 'background: rgba(14,15,17, calc({{SIZE}} / 100));',
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

		$categories = (array) $s['category'];
		$current    = $this->current_post_id();

		$args = array(
			// An unset multi-select control casts to array( '' ) — drop empties
			// so the grid shows all videos instead of nothing.
			'categories'     => array_values( array_filter( array_map( 'sanitize_title', $categories ) ) ),
			'featured'       => 'yes' === $s['featured_only'],
			'per_page'       => (int) $s['limit'],
			'orderby'        => $s['orderby'],
			'columns'        => isset( $s['columns'] ) && $s['columns'] ? max( 1, min( 6, (int) $s['columns'] ) ) : 3,
			'columns_tablet' => isset( $s['columns_tablet'] ) && $s['columns_tablet'] ? max( 1, min( 6, (int) $s['columns_tablet'] ) ) : 2,
			'columns_mobile' => isset( $s['columns_mobile'] ) && $s['columns_mobile'] ? max( 1, min( 6, (int) $s['columns_mobile'] ) ) : 1,
			'show_excerpt'   => 'yes' === $s['show_desc'],
			'load_more'      => 'load_more' === $s['pagination'],
		);

		switch ( $s['relation'] ) {
			case 'product':
				$args['related_products'] = array( $current );
				break;
			case 'project':
				$args['related_projects'] = array( $current );
				break;
			case 'location':
				$args['related_locations'] = array( $current );
				break;
		}

		if ( 'yes' === $s['show_filters'] ) {
			$terms = get_terms(
				array(
					'taxonomy'   => 'bp_video_category',
					'hide_empty' => true,
				)
			);

			if ( ! is_wp_error( $terms ) && $terms ) {
				echo '<div class="bp-video-filters" data-bp-video-filters data-per-page="' . (int) $s['limit'] . '" data-orderby="' . esc_attr( $s['orderby'] ) . '">';

				printf(
					'<button type="button" class="bp-chip is-active" data-filter="">%s</button>',
					esc_html__( 'All Videos', 'brickpoint' )
				);

				foreach ( $terms as $term ) {
					printf(
						'<button type="button" class="bp-chip" data-filter="%1$s">%2$s</button>',
						esc_attr( $term->slug ),
						esc_html( $term->name )
					);
				}

				echo '</div>';
			}
		}

		echo '<div class="bp-video-grid-wrap" data-bp-video-grid-wrap>';

		$query = brickpoint_video_query( $args );

		if ( ! $query->have_posts() ) {
			echo '<p class="bp-empty">' . esc_html__( 'No videos found. Add videos in WordPress → Videos and assign them to a category.', 'brickpoint' ) . '</p>';
		} else {
			printf(
				'<div class="bp-grid-cols bp-video-grid" style="%1$s" data-bp-grid>',
				esc_attr( $this->grid_vars( $s ) )
			);

			while ( $query->have_posts() ) {
				$query->the_post();

				brickpoint_video_card(
					get_the_ID(),
					array(
						'show_excerpt' => 'yes' === $s['show_desc'],
						'show_meta'    => 'yes' === $s['show_cat'] || 'yes' === $s['show_duration'],
					)
				);
			}

			echo '</div>';
		}

		if ( 'load_more' === $s['pagination'] && $query->max_num_pages > 1 ) {
			printf(
				'<div class="bp-load-more" data-bp-load-more data-type="video" data-args="%1$s" data-page="1" data-max="%2$d">
					<button type="button" class="bp-btn bp-btn--primary bp-js-load-more"><span class="bp-btn__label">%3$s</span></button>
				</div>',
				esc_attr( wp_json_encode( brickpoint_grid_query_args( $args ) ) ),
				(int) $query->max_num_pages,
				esc_html__( 'Load More Videos', 'brickpoint' )
			);
		}

		if ( 'numbers' === $s['pagination'] ) {
			brickpoint_pagination( $query );
		}

		echo '</div>';

		wp_reset_postdata();
	}
}
