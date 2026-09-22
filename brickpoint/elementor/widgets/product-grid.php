<?php
/**
 * Elementor: BrickPoint Product Grid.
 *
 * Fully dynamic - connected to the bp_product post type and
 * bp_product_category taxonomy.
 *
 * @package BrickPoint
 */

namespace BrickPoint\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/base.php';

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/**
 * Product grid widget.
 */
class Product_Grid extends Base {

	/**
	 * Widget name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'bp_product_grid';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Product Grid', 'brickpoint' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-products';
	}

	/**
	 * Register controls.
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
				'label'   => __( 'Product category', 'brickpoint' ),
				'type'    => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $this->category_options(),
				'default' => array(),
			)
		);

		$this->add_control(
			'featured_only',
			array(
				'label'        => __( 'Featured products only', 'brickpoint' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'related_to',
			array(
				'label'       => __( 'Show products related to current item', 'brickpoint' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => array(
					''      => __( 'No - use the query above', 'brickpoint' ),
					'auto'  => __( 'Auto (current product category)', 'brickpoint' ),
					'manual' => __( 'Manual selection', 'brickpoint' ),
				),
				'description' => __( 'Used inside a product template to show related products automatically.', 'brickpoint' ),
			)
		);

		$this->add_control(
			'manual_products',
			array(
				'label'     => __( 'Select products', 'brickpoint' ),
				'type'      => Controls_Manager::SELECT2,
				'multiple'  => true,
				'label_block' => true,
				'options'   => brickpoint_elementor_post_options( 'bp_product' ),
				'condition' => array( 'related_to' => 'manual' ),
			)
		);

		$this->add_control(
			'limit',
			array(
				'label'   => __( 'Number of products', 'brickpoint' ),
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
					'date'       => __( 'Date published', 'brickpoint' ),
					'title'      => __( 'Title', 'brickpoint' ),
					'menu_order' => __( 'Custom order (page attributes)', 'brickpoint' ),
					'price'      => __( 'Price', 'brickpoint' ),
					'featured'   => __( 'Featured first', 'brickpoint' ),
					'rand'       => __( 'Random', 'brickpoint' ),
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => __( 'Order', 'brickpoint' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => array(
					'DESC' => __( 'Descending', 'brickpoint' ),
					'ASC'  => __( 'Ascending', 'brickpoint' ),
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
					'none'       => __( 'None', 'brickpoint' ),
					'load_more'  => __( 'Load more button', 'brickpoint' ),
					'numbers'    => __( 'Numbered pagination', 'brickpoint' ),
				),
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
				'label'      => __( 'Gap between cards (px)', 'brickpoint' ),
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

		$this->add_control(
			'card_style',
			array(
				'label'   => __( 'Card style', 'brickpoint' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default' => __( 'Card with image on top', 'brickpoint' ),
					'flat'    => __( 'Flat (minimal)', 'brickpoint' ),
					'overlay' => __( 'Image overlay', 'brickpoint' ),
				),
			)
		);

		$this->add_control(
			'hover_effect',
			array(
				'label'   => __( 'Hover effect', 'brickpoint' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'lift',
				'options' => array(
					'lift'  => __( 'Lift + shadow', 'brickpoint' ),
					'zoom'  => __( 'Image zoom', 'brickpoint' ),
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
			'show_image'      => __( 'Product image', 'brickpoint' ),
			'show_category'   => __( 'Product category', 'brickpoint' ),
			'show_title'      => __( 'Product title', 'brickpoint' ),
			'show_excerpt'    => __( 'Short description', 'brickpoint' ),
			'show_price'      => __( 'Price', 'brickpoint' ),
			'show_price_label' => __( 'Price label', 'brickpoint' ),
			'show_unit'       => __( 'Unit', 'brickpoint' ),
			'show_availability' => __( 'Availability badge', 'brickpoint' ),
			'show_badge'      => __( 'Product badge', 'brickpoint' ),
			'show_details_btn' => __( 'View product button', 'brickpoint' ),
			'show_whatsapp'   => __( 'Order on WhatsApp button', 'brickpoint' ),
		);

		foreach ( $toggles as $key => $label ) {
			$this->add_control(
				$key,
				array(
					'label'        => $label,
					'type'         => Controls_Manager::SWITCHER,
					'return_value' => 'yes',
					'default'      => in_array( $key, array( 'show_badge' ), true ) ? '' : 'yes',
				)
			);
		}

		$this->end_controls_section();

		// ------------------------------------------------------------ Style.
		$this->start_controls_section(
			'style_card',
			array(
				'label' => __( 'Card Style', 'brickpoint' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->register_card_style( 'card', '{{WRAPPER}} .bp-product-card' );

		$this->add_responsive_control(
			'card_padding',
			array(
				'label'      => __( 'Content padding', 'brickpoint' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .bp-product-card__body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'image_ratio',
			array(
				'label'   => __( 'Image aspect ratio', 'brickpoint' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '4-3',
				'options' => array(
					'4-3'  => '4:3',
					'1-1'  => '1:1',
					'3-2'  => '3:2',
					'16-9' => '16:9',
				),
				'selectors' => array(
					'{{WRAPPER}} .bp-product-card .bp-media' => '--bp-media-ratio: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_text',
			array(
				'label' => __( 'Typography & Colors', 'brickpoint' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->register_text_style( 'title', __( 'Title', 'brickpoint' ), '{{WRAPPER}} .bp-product-card__title' );
		$this->register_text_style( 'cat', __( 'Category', 'brickpoint' ), '{{WRAPPER}} .bp-product-card__cat' );
		$this->register_text_style( 'desc', __( 'Description', 'brickpoint' ), '{{WRAPPER}} .bp-product-card__desc' );
		$this->register_text_style( 'price', __( 'Price', 'brickpoint' ), '{{WRAPPER}} .bp-price__value' );

		$this->end_controls_section();

		$this->start_controls_section(
			'style_buttons',
			array(
				'label' => __( 'Buttons', 'brickpoint' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'btn_typo',
				'label'    => __( 'Button typography', 'brickpoint' ),
				'selector' => '{{WRAPPER}} .bp-product-card .bp-btn',
			)
		);

		$this->add_control(
			'btn_bg',
			array(
				'label'     => __( 'Primary button background', 'brickpoint' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .bp-product-card .bp-btn--primary' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .bp-product-card .bp-btn--ghost'   => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'btn_color',
			array(
				'label'     => __( 'Primary button text color', 'brickpoint' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .bp-product-card .bp-btn--primary' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'wa_bg',
			array(
				'label'     => __( 'WhatsApp button background', 'brickpoint' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#25d366',
				'selectors' => array(
					'{{WRAPPER}} .bp-product-card .bp-btn--whatsapp' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
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

		$category = $s['category'];

		if ( ! is_array( $category ) ) {
			$category = $category ? array( $category ) : array();
		}

		$args = array(
			'categories'     => $category,
			'featured'       => 'yes' === $s['featured_only'],
			'per_page'       => (int) $s['limit'],
			'orderby'        => $s['orderby'],
			'order'          => $s['order'],
			// Responsive controls can be absent when a template ships partial
			// settings, so each value falls back to the widget default.
			'columns'        => isset( $s['columns'] ) && $s['columns'] ? max( 1, min( 6, (int) $s['columns'] ) ) : 3,
			'columns_tablet' => isset( $s['columns_tablet'] ) && $s['columns_tablet'] ? max( 1, min( 6, (int) $s['columns_tablet'] ) ) : 2,
			'columns_mobile' => isset( $s['columns_mobile'] ) && $s['columns_mobile'] ? max( 1, min( 6, (int) $s['columns_mobile'] ) ) : 1,
			'gap'            => isset( $s['gap']['size'] ) ? (int) $s['gap']['size'] : 28,
			'show_excerpt'   => 'yes' === $s['show_excerpt'],
			'show_price'     => 'yes' === $s['show_price'],
			'show_whatsapp'  => 'yes' === $s['show_whatsapp'],
			'show_details'   => 'yes' === $s['show_details_btn'],
			'load_more'      => 'load_more' === $s['pagination'],
			'pagination'     => 'numbers' === $s['pagination'],
		);

		// Related mode.
		if ( 'auto' === $s['related_to'] ) {
			$current = $this->current_post_id();

			if ( $current ) {
				$terms = get_the_terms( $current, 'bp_product_category' );
				$args['categories'] = ( $terms && ! is_wp_error( $terms ) ) ? wp_list_pluck( $terms, 'slug' ) : array();
				$args['exclude']    = array( $current );
			}
		} elseif ( 'manual' === $s['related_to'] ) {
			$ids = array_map( 'absint', (array) $s['manual_products'] );
			$args['include']   = $ids;
			$args['per_page']  = $ids ? count( $ids ) : 0;
		}

		printf(
			'<div class="bp-grid-cols bp-product-grid bp-product-grid--%1$s bp-product-grid--hover-%2$s" style="%3$s" data-bp-grid>',
			esc_attr( $s['card_style'] ),
			esc_attr( $s['hover_effect'] ),
			esc_attr( $this->grid_vars( $s ) )
		);

		$query = brickpoint_product_query( $args );

		if ( ! $query->have_posts() ) {
			echo '<p class="bp-empty">' . esc_html__( 'No products found for this query.', 'brickpoint' ) . '</p>';
			echo '</div>';

			return;
		}

		while ( $query->have_posts() ) {
			$query->the_post();

			brickpoint_product_card(
				get_the_ID(),
				array(
					'show_excerpt'  => $args['show_excerpt'],
					'show_price'    => $args['show_price'],
					'show_whatsapp' => $args['show_whatsapp'],
					'show_details'  => $args['show_details'],
					'style'         => $s['card_style'],
				)
			);
		}

		echo '</div>';

		if ( $args['load_more'] && $query->max_num_pages > 1 ) {
			printf(
				'<div class="bp-load-more" data-bp-load-more data-type="product" data-args="%1$s" data-page="1" data-max="%2$d">
					<button type="button" class="bp-btn bp-btn--primary bp-js-load-more"><span class="bp-btn__label">%3$s</span></button>
				</div>',
				esc_attr( wp_json_encode( brickpoint_grid_query_args( $args ) ) ),
				(int) $query->max_num_pages,
				esc_html__( 'Load More Products', 'brickpoint' )
			);
		}

		if ( $args['pagination'] ) {
			brickpoint_pagination( $query );
		}

		wp_reset_postdata();
	}
}
