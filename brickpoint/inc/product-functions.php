<?php
/**
 * Product helpers: queries, cards, grids, categories, gallery, specs, related.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Product query builder used by the shortcode, the Elementor widget and AJAX.
 *
 * @param array $args Args: category, categories, featured, per_page, orderby, order, paged, product(s).
 * @return WP_Query
 */
function brickpoint_product_query( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'category'   => '',
			'categories' => array(),
			'featured'   => false,
			'per_page'   => 6,
			'orderby'    => 'date',
			'order'      => 'DESC',
			'paged'      => 0,
			'include'    => array(),
			'exclude'    => array(),
			'search'     => '',
		)
	);

	$query_args = array(
		'post_type'           => 'bp_product',
		'post_status'         => 'publish',
		'posts_per_page'      => (int) $args['per_page'],
		'ignore_sticky_posts' => true,
		'paged'               => $args['paged'] ? (int) $args['paged'] : max( 1, (int) get_query_var( 'paged' ) ),
	);

	$terms = array();

	if ( $args['category'] ) {
		$terms[] = sanitize_title( is_array( $args['category'] ) ? reset( $args['category'] ) : $args['category'] );
	}

	if ( ! empty( $args['categories'] ) ) {
		$terms = array_merge( $terms, array_map( 'sanitize_title', (array) $args['categories'] ) );
	}

	$terms = array_filter( array_unique( $terms ) );

	if ( $terms ) {
		$query_args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			array(
				'taxonomy'         => 'bp_product_category',
				'field'            => 'slug',
				'terms'            => $terms,
				'include_children' => true,
			),
		);
	}

	if ( ! empty( $args['include'] ) ) {
		$query_args['post__in'] = array_map( 'absint', (array) $args['include'] );
		$query_args['orderby']  = 'post__in';
	}

	if ( ! empty( $args['exclude'] ) ) {
		$query_args['post__not_in'] = array_map( 'absint', (array) $args['exclude'] );
	}

	if ( $args['search'] ) {
		$query_args['s'] = sanitize_text_field( $args['search'] );
	}

	if ( $args['featured'] ) {
		$query_args['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			array(
				'key'     => '_bp_featured',
				'value'   => '1',
				'compare' => '=',
			),
		);
	}

	switch ( $args['orderby'] ) {
		case 'title':
			$query_args['orderby'] = 'title';
			$query_args['order']   = 'DESC' === strtoupper( $args['order'] ) ? 'DESC' : 'ASC';
			break;

		case 'menu_order':
			$query_args['orderby'] = 'menu_order title';
			$query_args['order']   = 'ASC';
			break;

		case 'rand':
			$query_args['orderby'] = 'rand';
			break;

		case 'price':
			$query_args['meta_key'] = '_bp_price'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			$query_args['orderby']  = 'meta_value_num';
			$query_args['order']    = 'ASC' === strtoupper( $args['order'] ) ? 'ASC' : 'DESC';
			break;

		case 'featured':
			$query_args['meta_key'] = '_bp_featured'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			$query_args['orderby']  = 'meta_value';
			$query_args['order']    = 'DESC';
			break;

		default:
			$query_args['orderby'] = 'date';
			$query_args['order']   = 'ASC' === strtoupper( $args['order'] ) ? 'ASC' : 'DESC';
			break;
	}

	/**
	 * Filter product query args.
	 *
	 * @param array $query_args WP_Query args.
	 * @param array $args       Grid args.
	 */
	return new WP_Query( apply_filters( 'brickpoint_product_query_args', $query_args, $args ) );
}

/**
 * Product card.
 *
 * @param int   $product_id Product ID.
 * @param array $args       show_excerpt, show_price, show_cta, show_whatsapp, style.
 * @return void
 */
function brickpoint_product_card( $product_id = 0, $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'show_excerpt'  => true,
			'show_price'    => true,
			'show_whatsapp' => true,
			'show_details'  => true,
			'style'         => 'default',
			'quantity'      => false,
		)
	);

	$product_id = $product_id ? (int) $product_id : get_the_ID();
	$permalink  = get_permalink( $product_id );
	$terms      = get_the_terms( $product_id, 'bp_product_category' );
	$category   = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0] : null;
	$badge      = brickpoint_meta( $product_id, '_bp_badge' );
	$featured   = (bool) brickpoint_meta( $product_id, '_bp_featured' );
	$availability = brickpoint_availability_badge( $product_id );

	printf(
		'<article class="bp-product-card bp-product-card--%1$s bp-reveal" data-bp-product-card data-product-id="%2$d">',
		esc_attr( $args['style'] ),
		(int) $product_id
	);

	// Media.
	echo '<div class="bp-product-card__media bp-media">';

	printf( '<a class="bp-media__link" href="%1$s" aria-label="%2$s">', esc_url( $permalink ), esc_attr( get_the_title( $product_id ) ) );

	if ( has_post_thumbnail( $product_id ) ) {
		echo wp_get_attachment_image(
			get_post_thumbnail_id( $product_id ),
			'bp-card',
			false,
			array(
				'class'   => 'bp-media__img',
				'loading' => 'lazy',
				'alt'     => get_the_title( $product_id ),
			)
		);
	} else {
		echo brickpoint_placeholder( '4x3' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	echo '</a>';

	if ( $badge ) {
		echo '<span class="bp-badge bp-badge--product">' . esc_html( $badge ) . '</span>';
	} elseif ( $featured ) {
		echo '<span class="bp-badge bp-badge--featured">' . esc_html__( 'Featured', 'brickpoint' ) . '</span>';
	}

	if ( $availability['label'] ) {
		printf(
			'<span class="bp-badge bp-badge--availability %1$s">%2$s</span>',
			esc_attr( $availability['status'] ),
			esc_html( $availability['label'] )
		);
	}

	echo '</div>';

	// Body.
	echo '<div class="bp-product-card__body">';

	if ( $category instanceof WP_Term ) {
		printf(
			'<a class="bp-product-card__cat" href="%1$s">%2$s</a>',
			esc_url( get_term_link( $category ) ),
			esc_html( $category->name )
		);
	}

	printf(
		'<h3 class="bp-product-card__title"><a href="%1$s">%2$s</a></h3>',
		esc_url( $permalink ),
		esc_html( get_the_title( $product_id ) )
	);

	if ( $args['show_excerpt'] ) {
		$excerpt = has_excerpt( $product_id ) ? get_the_excerpt( $product_id ) : get_post_field( 'post_content', $product_id );
		$excerpt = brickpoint_excerpt( 18, $excerpt );

		if ( $excerpt ) {
			echo '<p class="bp-product-card__desc">' . esc_html( $excerpt ) . '</p>';
		}
	}

	if ( $args['show_price'] ) {
		echo '<div class="bp-product-card__price">' . brickpoint_price_html( $product_id ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper.
	}

	echo '<div class="bp-product-card__actions">';

	if ( $args['show_details'] ) {
		printf(
			'<a class="bp-btn bp-btn--ghost bp-btn--sm" href="%1$s">%2$s<span class="bp-btn__label">%3$s</span></a>',
			esc_url( $permalink ),
			brickpoint_icon( 'arrow-right', array( 'size' => 16 ) ),
			esc_html__( 'View Product', 'brickpoint' )
		);
	}

	if ( $args['show_whatsapp'] ) {
		echo brickpoint_whatsapp_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper.
			array(
				'label'   => __( 'Order on WhatsApp', 'brickpoint' ),
				'message' => brickpoint_product_inquiry_message( $product_id ),
				'class'   => 'bp-btn bp-btn--whatsapp bp-btn--sm',
				'product' => $product_id,
			)
		);
	}

	echo '</div></div></article>';
}

/**
 * Product grid renderer.
 *
 * @param array $args Query args + columns + load_more + show_* flags.
 * @return void
 */
function brickpoint_render_product_grid( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'columns'        => 3,
			'columns_tablet' => 2,
			'columns_mobile' => 1,
			'gap'            => 28,
			'show_excerpt'   => true,
			'show_price'     => true,
			'show_whatsapp'  => true,
			'show_details'   => true,
			'load_more'      => false,
			'pagination'     => false,
			'empty_text'     => __( 'No products found. Add products in WordPress → Products, or adjust the category filter.', 'brickpoint' ),
		)
	);

	$query = brickpoint_product_query( $args );

	if ( ! $query->have_posts() ) {
		echo '<p class="bp-empty">' . esc_html( $args['empty_text'] ) . '</p>';
		return;
	}

	printf(
		'<div class="bp-product-grid" style="--bp-cols:%1$d;--bp-cols-t:%2$d;--bp-cols-m:%3$d;--bp-gap:%4$dpx;" data-bp-grid>',
		(int) $args['columns'],
		(int) $args['columns_tablet'],
		(int) $args['columns_mobile'],
		(int) $args['gap']
	);

	while ( $query->have_posts() ) {
		$query->the_post();

		brickpoint_product_card(
			get_the_ID(),
			array(
				'show_excerpt'  => (bool) $args['show_excerpt'],
				'show_price'    => (bool) $args['show_price'],
				'show_whatsapp' => (bool) $args['show_whatsapp'],
				'show_details'  => (bool) $args['show_details'],
			)
		);
	}

	echo '</div>';

	if ( ! empty( $args['load_more'] ) && $query->max_num_pages > 1 ) {
		printf(
			'<div class="bp-load-more" data-bp-load-more data-type="product" data-args="%1$s" data-page="1" data-max="%2$d">
				<button type="button" class="bp-btn bp-btn--primary bp-js-load-more"><span class="bp-btn__label">%3$s</span></button>
			</div>',
			esc_attr( wp_json_encode( brickpoint_grid_query_args( $args ) ) ),
			(int) $query->max_num_pages,
			esc_html__( 'Load More Products', 'brickpoint' )
		);
	}

	if ( ! empty( $args['pagination'] ) ) {
		brickpoint_pagination( $query );
	}

	wp_reset_postdata();
}

/**
 * Reduce grid args to the JSON payload sent to admin-ajax.
 *
 * @param array $args Grid args.
 * @return array
 */
function brickpoint_grid_query_args( $args ) {
	return array(
		'category'       => isset( $args['category'] ) ? $args['category'] : '',
		'featured'       => ! empty( $args['featured'] ),
		'per_page'       => isset( $args['per_page'] ) ? (int) $args['per_page'] : 6,
		'orderby'        => isset( $args['orderby'] ) ? $args['orderby'] : 'date',
		'order'          => isset( $args['order'] ) ? $args['order'] : 'DESC',
		'columns'        => isset( $args['columns'] ) ? (int) $args['columns'] : 3,
		'columns_tablet' => isset( $args['columns_tablet'] ) ? (int) $args['columns_tablet'] : 2,
		'columns_mobile' => isset( $args['columns_mobile'] ) ? (int) $args['columns_mobile'] : 1,
		'show_excerpt'   => ! empty( $args['show_excerpt'] ),
		'product'        => isset( $args['product'] ) ? (int) $args['product'] : 0,
		'project'        => isset( $args['project'] ) ? (int) $args['project'] : 0,
		'location'       => isset( $args['location'] ) ? (int) $args['location'] : 0,
	);
}

/**
 * Product category cards.
 *
 * @param array $args limit, columns, home_only, style, show_whatsapp.
 * @return void
 */
function brickpoint_render_category_grid( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'limit'          => 12,
			'columns'        => 4,
			'columns_tablet' => 3,
			'columns_mobile' => 2,
			'home_only'      => false,
			'style'          => 'card',
			'show_whatsapp'  => true,
			'empty_text'     => __( 'No product categories found.', 'brickpoint' ),
		)
	);

	$terms = brickpoint_get_product_categories(
		array(
			'number'    => (int) $args['limit'] > 0 ? (int) $args['limit'] : 0,
			'home_only' => (bool) $args['home_only'],
		)
	);

	if ( ! $terms ) {
		echo '<p class="bp-empty">' . esc_html( $args['empty_text'] ) . '</p>';
		return;
	}

	printf(
		'<div class="bp-category-grid bp-category-grid--%1$s" style="--bp-cols:%2$d;--bp-cols-t:%3$d;--bp-cols-m:%4$d;">',
		esc_attr( $args['style'] ),
		(int) $args['columns'],
		(int) $args['columns_tablet'],
		(int) $args['columns_mobile']
	);

	foreach ( $terms as $term ) {
		brickpoint_category_card( $term, $args );
	}

	echo '</div>';
}

/**
 * One category card.
 *
 * @param WP_Term $term Term.
 * @param array   $args Args.
 * @return void
 */
function brickpoint_category_card( $term, $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'show_whatsapp' => true,
			'show_count'    => true,
			'style'         => 'card',
		)
	);

	$link  = get_term_link( $term );
	$icon  = get_term_meta( $term->term_id, '_bp_term_icon', true );
	$short = get_term_meta( $term->term_id, '_bp_term_short', true );
	$image = brickpoint_term_image_url( $term->term_id, 'bp-card' );
	$count = (int) $term->count;

	if ( is_wp_error( $link ) ) {
		return;
	}

	printf(
		'<article class="bp-category-card bp-category-card--%1$s bp-reveal" data-bp-category="%2$s">',
		esc_attr( $args['style'] ),
		esc_attr( $term->slug )
	);

	echo '<a class="bp-category-card__media bp-media" href="' . esc_url( $link ) . '">';

	if ( $image ) {
		printf(
			'<img class="bp-media__img" src="%1$s" alt="%2$s" loading="lazy" decoding="async" />',
			esc_url( $image ),
			esc_attr( $term->name )
		);
	} else {
		echo brickpoint_placeholder( '4x3' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	echo '<span class="bp-media__scrim" aria-hidden="true"></span></a>';

	echo '<div class="bp-category-card__body">';

	echo '<span class="bp-category-card__icon">' . brickpoint_icon( $icon ? $icon : 'brick', array( 'size' => 22 ) ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	printf(
		'<h3 class="bp-category-card__title"><a href="%1$s">%2$s</a></h3>',
		esc_url( $link ),
		esc_html( $term->name )
	);

	if ( $short ) {
		echo '<p class="bp-category-card__desc">' . esc_html( $short ) . '</p>';
	} elseif ( $term->description ) {
		echo '<p class="bp-category-card__desc">' . esc_html( brickpoint_excerpt( 14, $term->description ) ) . '</p>';
	}

	if ( $count && ! empty( $args['show_count'] ) ) {
		printf(
			'<span class="bp-category-card__count">%s</span>',
			esc_html(
				sprintf(
					/* translators: %d: number of products. */
					_n( '%d product', '%d products', $count, 'brickpoint' ),
					$count
				)
			)
		);
	}

	echo '<div class="bp-category-card__actions">';

	printf(
		'<a class="bp-link-arrow" href="%1$s">%2$s%3$s</a>',
		esc_url( $link ),
		esc_html__( 'Explore', 'brickpoint' ),
		brickpoint_icon( 'arrow-right', array( 'size' => 16 ) )
	);

	if ( $args['show_whatsapp'] ) {
		echo brickpoint_whatsapp_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper.
			array(
				'label'   => __( 'Ask on WhatsApp', 'brickpoint' ),
				'message' => brickpoint_term_inquiry_message( $term ),
				'class'   => 'bp-btn bp-btn--whatsapp bp-btn--xs',
			)
		);
	}

	echo '</div></div></article>';
}

/**
 * Product gallery: main image + thumbnails (no JS required to be useful).
 *
 * @param int   $product_id Product ID.
 * @param array $args       Args.
 * @return void
 */
function brickpoint_product_gallery( $product_id = 0, $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'lightbox' => true,
			'class'    => '',
		)
	);

	$product_id = $product_id ? (int) $product_id : get_the_ID();
	$ids        = array();

	if ( has_post_thumbnail( $product_id ) ) {
		$ids[] = (int) get_post_thumbnail_id( $product_id );
	}

	$gallery = brickpoint_sanitize_id_list( brickpoint_meta( $product_id, '_bp_gallery' ) );

	if ( $gallery ) {
		$ids = array_merge( $ids, array_map( 'absint', explode( ',', $gallery ) ) );
	}

	$ids = array_values( array_unique( array_filter( $ids ) ) );

	if ( ! $ids ) {
		echo '<div class="bp-gallery bp-gallery--empty">' . brickpoint_placeholder( '4x3' ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		return;
	}

	printf( '<div class="bp-gallery bp-js-gallery %s">', esc_attr( $args['class'] ) );

	echo '<div class="bp-gallery__main">';
	printf(
		'<img class="bp-gallery__img" src="%1$s" alt="%2$s" width="900" height="675" data-bp-gallery-main />',
		esc_url( wp_get_attachment_image_url( $ids[0], 'large' ) ),
		esc_attr( get_the_title( $product_id ) )
	);
	echo '</div>';

	if ( count( $ids ) > 1 ) {
		echo '<div class="bp-gallery__thumbs" role="list">';

		foreach ( $ids as $index => $image_id ) {
			$thumb = wp_get_attachment_image_url( $image_id, 'thumbnail' );
			$full  = wp_get_attachment_image_url( $image_id, 'large' );

			if ( ! $thumb ) {
				continue;
			}

			printf(
				'<button type="button" class="bp-gallery__thumb%1$s" role="listitem" data-bp-gallery-thumb data-src="%2$s" aria-label="%3$s" aria-current="%4$s">
					<img src="%5$s" alt="" loading="lazy" decoding="async" />
				</button>',
				0 === $index ? ' is-active' : '',
				esc_url( $full ),
				esc_attr( sprintf( /* translators: %d: image number. */ __( 'Show image %d', 'brickpoint' ), $index + 1 ) ),
				0 === $index ? 'true' : 'false',
				esc_url( $thumb )
			);
		}

		echo '</div>';
	}

	echo '</div>';
}

/**
 * Product specification table.
 *
 * @param int   $product_id Product ID.
 * @param array $args        Args.
 * @return void
 */
function brickpoint_product_specs( $product_id = 0, $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'title' => __( 'Specifications', 'brickpoint' ),
			'class' => '',
		)
	);

	$product_id = $product_id ? (int) $product_id : get_the_ID();
	$specs      = get_post_meta( $product_id, '_bp_specs', true );

	if ( ! is_array( $specs ) || ! $specs ) {
		return;
	}

	$rows = array_filter(
		$specs,
		function ( $row ) {
			return ! empty( $row['label'] ) || ! empty( $row['value'] );
		}
	);

	if ( ! $rows ) {
		return;
	}

	echo '<section class="bp-specs ' . esc_attr( $args['class'] ) . '">';

	if ( $args['title'] ) {
		echo '<h2 class="bp-section__title bp-section__title--sm">' . esc_html( $args['title'] ) . '</h2>';
	}

	echo '<table class="bp-specs__table"><tbody>';

	foreach ( $rows as $row ) {
		printf(
			'<tr><th scope="row">%1$s</th><td>%2$s</td></tr>',
			esc_html( isset( $row['label'] ) ? $row['label'] : '' ),
			esc_html( isset( $row['value'] ) ? $row['value'] : '' )
		);
	}

	echo '</tbody></table></section>';
}

/**
 * Product feature list.
 *
 * @param int   $product_id Product ID.
 * @param array $args        Args.
 * @return void
 */
function brickpoint_product_features( $product_id = 0, $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'title' => __( 'Highlights', 'brickpoint' ),
		)
	);

	$product_id = $product_id ? (int) $product_id : get_the_ID();
	$raw        = brickpoint_meta( $product_id, '_bp_features' );
	$lines      = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $raw ) ) );

	if ( ! $lines ) {
		return;
	}

	echo '<section class="bp-features-list">';

	if ( $args['title'] ) {
		echo '<h2 class="bp-section__title bp-section__title--sm">' . esc_html( $args['title'] ) . '</h2>';
	}

	echo '<ul>';

	foreach ( $lines as $line ) {
		echo '<li>' . brickpoint_icon( 'check', array( 'size' => 18 ) ) . '<span>' . esc_html( $line ) . '</span></li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	echo '</ul></section>';
}

/**
 * Related products block (manual relation first, then same category).
 *
 * @param int   $product_id Product ID.
 * @param array $args        Args.
 * @return void
 */
function brickpoint_related_products( $product_id = 0, $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'title'   => __( 'Related products', 'brickpoint' ),
			'limit'   => 3,
			'columns' => 3,
		)
	);

	$product_id = $product_id ? (int) $product_id : get_the_ID();

	$manual = array_filter( array_map( 'absint', explode( ',', (string) brickpoint_meta( $product_id, '_bp_related' ) ) ) );
	$manual = array_diff( $manual, array( $product_id ) );

	$args_query = array(
		'per_page'     => (int) $args['limit'],
		'columns'      => (int) $args['columns'],
		'show_excerpt' => false,
	);

	if ( $manual ) {
		$args_query['include'] = array_slice( $manual, 0, (int) $args['limit'] );
		$query                 = brickpoint_product_query( $args_query );
	} else {
		$terms = get_the_terms( $product_id, 'bp_product_category' );
		$slugs = ( $terms && ! is_wp_error( $terms ) ) ? wp_list_pluck( $terms, 'slug' ) : array();

		$args_query['categories'] = $slugs;
		$args_query['exclude']    = array( $product_id );

		$query = brickpoint_product_query( $args_query );
	}

	if ( ! $query->have_posts() ) {
		return;
	}

	echo '<section class="bp-related-products">';
	echo '<h2 class="bp-section__title">' . esc_html( $args['title'] ) . '</h2>';
	echo '<div class="bp-product-grid" style="--bp-cols:' . (int) $args['columns'] . ';--bp-cols-t:2;--bp-cols-m:1;" >';

	while ( $query->have_posts() ) {
		$query->the_post();

		brickpoint_product_card(
			get_the_ID(),
			array(
				'show_excerpt' => false,
			)
		);
	}

	echo '</div></section>';

	wp_reset_postdata();
}

/**
 * Products linked to a project / location.
 *
 * @param int    $post_id   Post ID.
 * @param string $meta_key  Meta key holding the IDs.
 * @param array  $args       Args.
 * @return void
 */
function brickpoint_linked_products( $post_id, $meta_key, $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'title'   => __( 'Materials used', 'brickpoint' ),
			'columns' => 3,
		)
	);

	$ids = array_filter( array_map( 'absint', explode( ',', (string) brickpoint_meta( $post_id, $meta_key ) ) ) );

	if ( ! $ids ) {
		return;
	}

	$query = brickpoint_product_query(
		array(
			'include'      => $ids,
			'per_page'     => 6,
			'show_excerpt' => false,
		)
	);

	if ( ! $query->have_posts() ) {
		return;
	}

	echo '<section class="bp-linked-products">';
	echo '<h2 class="bp-section__title">' . esc_html( $args['title'] ) . '</h2>';
	echo '<div class="bp-product-grid" style="--bp-cols:' . (int) $args['columns'] . ';--bp-cols-t:2;--bp-cols-m:1;">';

	while ( $query->have_posts() ) {
		$query->the_post();
		brickpoint_product_card( get_the_ID(), array( 'show_excerpt' => false ) );
	}

	echo '</div></section>';

	wp_reset_postdata();
}

/**
 * Product page action buttons (WhatsApp, call, quote, brochure).
 *
 * @param int   $product_id Product ID.
 * @param array $args        Args.
 * @return void
 */
function brickpoint_product_actions( $product_id = 0, $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'show_quantity' => true,
			'show_call'     => true,
			'show_quote'    => true,
			'show_brochure' => true,
			'class'         => '',
		)
	);

	$product_id = $product_id ? (int) $product_id : get_the_ID();
	$quote_url  = brickpoint_page_url( 'contact' );
	$brochure   = (int) brickpoint_meta( $product_id, '_bp_brochure', 0 );
	$brochure_url = $brochure ? wp_get_attachment_url( $brochure ) : brickpoint_meta( $product_id, '_bp_brochure_url' );

	echo '<div class="bp-product-actions ' . esc_attr( $args['class'] ) . '" data-bp-product-actions data-product="' . (int) $product_id . '">';

	if ( $args['show_quantity'] ) {
		echo '<div class="bp-qty"><label class="screen-reader-text" for="bp-qty-' . (int) $product_id . '">' . esc_html__( 'Quantity', 'brickpoint' ) . '</label>';
		printf(
			'<input type="text" id="bp-qty-%1$d" class="bp-qty__input" placeholder="%2$s" data-bp-qty />',
			(int) $product_id,
			esc_attr__( 'Quantity (optional)', 'brickpoint' )
		);
		echo '</div>';
	}

	echo brickpoint_whatsapp_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper.
		array(
			'label'   => __( 'Order on WhatsApp', 'brickpoint' ),
			'message' => brickpoint_product_inquiry_message( $product_id ),
			'class'   => 'bp-btn bp-btn--whatsapp bp-btn--lg',
			'product' => $product_id,
		)
	);

	if ( $args['show_quote'] && $quote_url ) {
		printf(
			'<a class="bp-btn bp-btn--primary bp-btn--lg" href="%1$s">%2$s<span class="bp-btn__label">%3$s</span></a>',
			esc_url( add_query_arg( 'product', rawurlencode( get_the_title( $product_id ) ), $quote_url ) ),
			brickpoint_icon( 'quote', array( 'size' => 18 ) ),
			esc_html__( 'Request Quotation', 'brickpoint' )
		);
	}

	if ( $args['show_call'] ) {
		brickpoint_phone_button(
			array(
				'label' => __( 'Call Now', 'brickpoint' ),
				'class' => 'bp-btn bp-btn--ghost bp-btn--lg',
			)
		);
	}

	if ( $args['show_brochure'] && $brochure_url ) {
		printf(
			'<a class="bp-btn bp-btn--ghost bp-btn--lg" href="%1$s" target="_blank" rel="noopener noreferrer" download>%2$s<span class="bp-btn__label">%3$s</span></a>',
			esc_url( $brochure_url ),
			brickpoint_icon( 'download', array( 'size' => 18 ) ),
			esc_html__( 'Brochure', 'brickpoint' )
		);
	}

	echo '</div>';
}

/**
 * Product "at a glance" meta list (category, unit, SKU, delivery area).
 *
 * @param int $product_id Product ID.
 * @return void
 */
function brickpoint_product_meta_list( $product_id = 0 ) {
	$product_id = $product_id ? (int) $product_id : get_the_ID();

	$terms    = get_the_terms( $product_id, 'bp_product_category' );
	$unit     = brickpoint_meta( $product_id, '_bp_unit' );
	$min      = brickpoint_meta( $product_id, '_bp_min_order' );
	$sku      = brickpoint_meta( $product_id, '_bp_sku' );
	$area     = brickpoint_meta( $product_id, '_bp_delivery_area' );
	$avail    = brickpoint_availability_badge( $product_id );

	$rows = array();

	if ( $terms && ! is_wp_error( $terms ) ) {
		$links = array();

		foreach ( $terms as $term ) {
			$links[] = '<a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a>';
		}

		$rows[ __( 'Category', 'brickpoint' ) ] = implode( ', ', $links );
	}

	if ( $avail['label'] ) {
		$rows[ __( 'Availability', 'brickpoint' ) ] = '<span class="bp-badge bp-badge--availability ' . esc_attr( $avail['status'] ) . '">' . esc_html( $avail['label'] ) . '</span>';
	}

	if ( $unit ) {
		$rows[ __( 'Unit', 'brickpoint' ) ] = esc_html( $unit );
	}

	if ( $min ) {
		$rows[ __( 'Minimum order', 'brickpoint' ) ] = esc_html( $min );
	}

	if ( $area ) {
		$rows[ __( 'Delivery area', 'brickpoint' ) ] = esc_html( $area );
	}

	if ( $sku ) {
		$rows[ __( 'Reference', 'brickpoint' ) ] = esc_html( $sku );
	}

	if ( ! $rows ) {
		return;
	}

	echo '<dl class="bp-product-meta">';

	foreach ( $rows as $label => $value ) {
		printf(
			'<div class="bp-product-meta__row"><dt>%1$s</dt><dd>%2$s</dd></div>',
			esc_html( $label ),
			wp_kses_post( $value )
		);
	}

	echo '</dl>';
}

/**
 * Products in the "SS7 Bricks" category (used by the SS7 page/section).
 *
 * @param int $limit Limit.
 * @return void
 */
function brickpoint_ss7_products( $limit = 4 ) {
	$terms = get_terms(
		array(
			'taxonomy'   => 'bp_product_category',
			'hide_empty' => false,
			'slug'       => array( 'ss7-bricks' ),
		)
	);

	$args = array(
		'per_page'     => (int) $limit,
		'columns'      => 4,
		'show_excerpt' => false,
	);

	if ( $terms && ! is_wp_error( $terms ) ) {
		$args['category'] = $terms[0]->slug;
	}

	brickpoint_render_product_grid( $args );
}
