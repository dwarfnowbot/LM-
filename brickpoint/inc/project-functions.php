<?php
/**
 * Projects and Locations helpers.
 *
 * Honesty rules baked in: illustrative visuals are labelled, and map buttons
 * only appear when a real Google Maps link is provided.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Query projects.
 *
 * @param array $args Args.
 * @return WP_Query
 */
function brickpoint_project_query( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'category' => '',
			'featured' => false,
			'per_page' => 6,
			'orderby'  => 'date',
			'order'    => 'DESC',
			'paged'    => 0,
		)
	);

	$query_args = array(
		'post_type'           => 'bp_project',
		'post_status'         => 'publish',
		'posts_per_page'      => (int) $args['per_page'],
		'ignore_sticky_posts' => true,
		'paged'               => $args['paged'] ? (int) $args['paged'] : max( 1, (int) get_query_var( 'paged' ) ),
	);

	if ( $args['category'] ) {
		$query_args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			array(
				'taxonomy' => 'bp_project_category',
				'field'    => 'slug',
				'terms'    => sanitize_title( $args['category'] ),
			),
		);
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

	if ( 'rand' === $args['orderby'] ) {
		$query_args['orderby'] = 'rand';
	} elseif ( 'menu_order' === $args['orderby'] ) {
		$query_args['orderby'] = 'menu_order';
		$query_args['order']   = 'ASC';
	} else {
		$query_args['orderby'] = 'date';
		$query_args['order']   = 'ASC' === strtoupper( $args['order'] ) ? 'ASC' : 'DESC';
	}

	/**
	 * Filter project query args.
	 *
	 * @param array $query_args Query args.
	 * @param array $args       Original args.
	 */
	return new WP_Query( apply_filters( 'brickpoint_project_query_args', $query_args, $args ) );
}

/**
 * Project card.
 *
 * @param int   $project_id Project ID.
 * @param array $args       Args.
 * @return void
 */
function brickpoint_project_card( $project_id = 0, $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'show_meta' => true,
			'show_cta'  => true,
		)
	);

	$project_id = $project_id ? (int) $project_id : get_the_ID();
	$location   = brickpoint_meta( $project_id, '_bp_project_location' );
	$status     = brickpoint_meta( $project_id, '_bp_project_status' );
	$illustrative = (bool) brickpoint_meta( $project_id, '_bp_illustrative' );
	$disclaimer = brickpoint_meta( $project_id, '_bp_project_disclaimer' );
	$terms      = get_the_terms( $project_id, 'bp_project_category' );
	$category   = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';

	$status_labels = array(
		'planning'  => __( 'Planning / concept', 'brickpoint' ),
		'ongoing'   => __( 'Ongoing', 'brickpoint' ),
		'completed' => __( 'Completed', 'brickpoint' ),
		'reference' => __( 'Reference visual', 'brickpoint' ),
	);

	echo '<article class="bp-project-card bp-reveal">';

	echo '<a class="bp-project-card__media bp-media" href="' . esc_url( get_permalink( $project_id ) ) . '">';

	if ( has_post_thumbnail( $project_id ) ) {
		echo wp_get_attachment_image(
			get_post_thumbnail_id( $project_id ),
			'bp-card',
			false,
			array(
				'class'   => 'bp-media__img',
				'loading' => 'lazy',
				'alt'     => get_the_title( $project_id ),
			)
		);
	} else {
		echo brickpoint_placeholder( '4x3' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	if ( $illustrative ) {
		echo '<span class="bp-badge bp-badge--note">' . esc_html__( 'Illustrative construction reference', 'brickpoint' ) . '</span>';
	}

	echo '</a>';

	echo '<div class="bp-project-card__body">';

	if ( $args['show_meta'] ) {
		echo '<div class="bp-project-card__meta">';

		if ( $category ) {
			echo '<span class="bp-chip">' . esc_html( $category ) . '</span>';
		}

		if ( $location ) {
			echo '<span class="bp-project-card__loc">' . brickpoint_icon( 'pin', array( 'size' => 14 ) ) . esc_html( $location ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo '</div>';
	}

	printf(
		'<h3 class="bp-project-card__title"><a href="%1$s">%2$s</a></h3>',
		esc_url( get_permalink( $project_id ) ),
		esc_html( get_the_title( $project_id ) )
	);

	echo '<p class="bp-project-card__text">' . esc_html( brickpoint_excerpt( 20, get_post_field( 'post_excerpt', $project_id ) ? get_post_field( 'post_excerpt', $project_id ) : get_post_field( 'post_content', $project_id ) ) ) . '</p>';

	if ( $args['show_meta'] && $status && isset( $status_labels[ $status ] ) ) {
		printf(
			'<span class="bp-status bp-status--%1$s">%2$s</span>',
			esc_attr( $status ),
			esc_html( $status_labels[ $status ] )
		);
	}

	if ( $disclaimer ) {
		echo '<p class="bp-disclaimer">' . esc_html( $disclaimer ) . '</p>';
	}

	if ( $args['show_cta'] ) {
		printf(
			'<a class="bp-link-arrow" href="%1$s">%2$s%3$s</a>',
			esc_url( get_permalink( $project_id ) ),
			esc_html__( 'View project', 'brickpoint' ),
			brickpoint_icon( 'arrow-right', array( 'size' => 16 ) )
		);
	}

	echo '</div></article>';
}

/**
 * Project grid renderer.
 *
 * @param array $args Args.
 * @return void
 */
function brickpoint_render_project_grid( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'columns'        => 3,
			'columns_tablet' => 2,
			'columns_mobile' => 1,
			'empty_text'     => __( 'No projects published yet. Add projects in WordPress → Projects.', 'brickpoint' ),
		)
	);

	$query = brickpoint_project_query( $args );

	if ( ! $query->have_posts() ) {
		echo '<p class="bp-empty">' . esc_html( $args['empty_text'] ) . '</p>';
		return;
	}

	printf(
		'<div class="bp-project-grid" style="--bp-cols:%1$d;--bp-cols-t:%2$d;--bp-cols-m:%3$d;">',
		(int) $args['columns'],
		(int) $args['columns_tablet'],
		(int) $args['columns_mobile']
	);

	while ( $query->have_posts() ) {
		$query->the_post();
		brickpoint_project_card( get_the_ID() );
	}

	echo '</div>';

	wp_reset_postdata();
}

/**
 * Location card with real Google Maps buttons.
 *
 * @param int   $location_id Location ID.
 * @param array $args        Args.
 * @return void
 */
function brickpoint_location_card( $location_id = 0, $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'show_video' => true,
			'show_products' => false,
		)
	);

	$location_id = $location_id ? (int) $location_id : get_the_ID();

	$company   = brickpoint_meta( $location_id, '_bp_location_company' );
	$address   = brickpoint_meta( $location_id, '_bp_location_address' );
	$map       = brickpoint_meta( $location_id, '_bp_location_map' );
	$directions = brickpoint_meta( $location_id, '_bp_location_directions' );
	$phone     = brickpoint_meta( $location_id, '_bp_location_phone' );
	$hours     = brickpoint_meta( $location_id, '_bp_location_hours' );
	$video_id  = (int) brickpoint_meta( $location_id, '_bp_location_video', 0 );
	$video_url = brickpoint_meta( $location_id, '_bp_location_video_url' );

	if ( ! $directions && $map ) {
		$directions = $map;
	}

	echo '<article class="bp-location-card bp-reveal">';

	echo '<div class="bp-location-card__media bp-media">';

	if ( has_post_thumbnail( $location_id ) ) {
		echo wp_get_attachment_image(
			get_post_thumbnail_id( $location_id ),
			'bp-card',
			false,
			array(
				'class'   => 'bp-media__img',
				'loading' => 'lazy',
				'alt'     => get_the_title( $location_id ),
			)
		);
	} else {
		echo brickpoint_placeholder( '4x3' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	echo '<span class="bp-location-card__pin" aria-hidden="true">' . brickpoint_icon( 'pin', array( 'size' => 20 ) ) . '</span>';
	echo '</div>';

	echo '<div class="bp-location-card__body">';

	if ( $company ) {
		echo '<p class="bp-eyebrow">' . esc_html( $company ) . '</p>';
	}

	printf(
		'<h3 class="bp-location-card__title"><a href="%1$s">%2$s</a></h3>',
		esc_url( get_permalink( $location_id ) ),
		esc_html( get_the_title( $location_id ) )
	);

	if ( $address ) {
		echo '<p class="bp-location-card__address">' . brickpoint_icon( 'pin', array( 'size' => 15 ) ) . esc_html( $address ) . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	if ( $hours ) {
		echo '<p class="bp-location-card__hours">' . brickpoint_icon( 'clock', array( 'size' => 15 ) ) . esc_html( $hours ) . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	$excerpt = brickpoint_excerpt( 18, get_post_field( 'post_excerpt', $location_id ) ? get_post_field( 'post_excerpt', $location_id ) : get_post_field( 'post_content', $location_id ) );

	if ( $excerpt ) {
		echo '<p class="bp-location-card__text">' . esc_html( $excerpt ) . '</p>';
	}

	echo '<div class="bp-location-card__actions">';

	if ( $map ) {
		printf(
			'<a class="bp-btn bp-btn--ghost bp-btn--sm" href="%1$s" target="_blank" rel="noopener noreferrer">%2$s<span class="bp-btn__label">%3$s</span></a>',
			esc_url( $map ),
			brickpoint_icon( 'pin', array( 'size' => 16 ) ),
			esc_html__( 'View on Google Maps', 'brickpoint' )
		);
	}

	if ( $directions ) {
		printf(
			'<a class="bp-btn bp-btn--ghost bp-btn--sm" href="%1$s" target="_blank" rel="noopener noreferrer">%2$s<span class="bp-btn__label">%3$s</span></a>',
			esc_url( $directions ),
			brickpoint_icon( 'arrow-right', array( 'size' => 16 ) ),
			esc_html__( 'Get Directions', 'brickpoint' )
		);
	}

	brickpoint_phone_button(
		array(
			'label'  => __( 'Call', 'brickpoint' ),
			'class'  => 'bp-btn bp-btn--ghost bp-btn--sm',
			'number' => $phone,
		)
	);

	echo brickpoint_whatsapp_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside helper.
		array(
			'label'   => __( 'WhatsApp', 'brickpoint' ),
			'message' => brickpoint_general_inquiry_message( get_the_title( $location_id ) ),
			'class'   => 'bp-btn bp-btn--whatsapp bp-btn--sm',
		)
	);

	echo '</div>';

	if ( $args['show_products'] ) {
		$product_ids = array_filter( array_map( 'absint', explode( ',', (string) brickpoint_meta( $location_id, '_bp_location_products' ) ) ) );

		if ( $product_ids ) {
			echo '<div class="bp-location-card__products"><span class="bp-label">' . esc_html__( 'Available here', 'brickpoint' ) . '</span><ul>';

			foreach ( $product_ids as $product_id ) {
				printf(
					'<li><a href="%1$s">%2$s</a></li>',
					esc_url( get_permalink( $product_id ) ),
					esc_html( get_the_title( $product_id ) )
				);
			}

			echo '</ul></div>';
		}
	}

	echo '</div>';

	if ( $args['show_video'] ) {
		$has_video = $video_id || $video_url;

		if ( $has_video ) {
			echo '<div class="bp-location-card__video">';
			brickpoint_inline_video(
				array(
					'video_id' => $video_id,
					'url'      => $video_url,
					'autoplay' => false,
					'class'    => 'bp-video--card',
					'poster_id' => get_post_thumbnail_id( $location_id ),
					'label'    => get_the_title( $location_id ),
				)
			);
			echo '</div>';
		}
	}

	echo '</article>';
}

/**
 * Locations query.
 *
 * @param array $args Args.
 * @return WP_Query
 */
function brickpoint_location_query( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'per_page' => -1,
			'paged'    => 1,
		)
	);

	$query_args = array(
		'post_type'      => 'bp_location',
		'post_status'    => 'publish',
		'posts_per_page' => (int) $args['per_page'],
		'orderby'        => 'meta_value_num',
		'meta_key'       => '_bp_location_order', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		'order'          => 'ASC',
		'paged'          => (int) $args['paged'],
		'no_found_rows'  => true,
	);

	/**
	 * Filter the locations query args.
	 *
	 * @param array $query_args Query args.
	 */
	return new WP_Query( apply_filters( 'brickpoint_location_query_args', $query_args ) );
}

/**
 * Locations grid renderer.
 *
 * @param array $args Args.
 * @return void
 */
function brickpoint_render_location_grid( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'columns'        => 2,
			'columns_tablet' => 2,
			'columns_mobile' => 1,
			'show_video'     => true,
			'show_products'  => false,
			'empty_text'     => __( 'No locations published yet. Add your bhatta and office locations in WordPress → Locations.', 'brickpoint' ),
		)
	);

	$query = brickpoint_location_query( $args );

	if ( ! $query->have_posts() ) {
		echo '<p class="bp-empty">' . esc_html( $args['empty_text'] ) . '</p>';
		return;
	}

	printf(
		'<div class="bp-location-grid" style="--bp-cols:%1$d;--bp-cols-t:%2$d;--bp-cols-m:%3$d;">',
		(int) $args['columns'],
		(int) $args['columns_tablet'],
		(int) $args['columns_mobile']
	);

	while ( $query->have_posts() ) {
		$query->the_post();

		brickpoint_location_card(
			get_the_ID(),
			array(
				'show_video'    => (bool) $args['show_video'],
				'show_products' => (bool) $args['show_products'],
			)
		);
	}

	echo '</div>';

	wp_reset_postdata();
}

/**
 * Default BrickPoint locations (real companies + real Google Maps links).
 *
 * @return array<int,array<string,mixed>>
 */
function brickpoint_default_locations() {
	return array(
		array(
			'title'   => __( 'Masha Allah Bricks Company – Ram Thaman', 'brickpoint' ),
			'company' => __( 'Masha Allah Bricks Company', 'brickpoint' ),
			'address' => __( 'Ram Thaman, Lahore District, Punjab, Pakistan', 'brickpoint' ),
			'map'     => 'https://maps.app.goo.gl/6Pi5BNTsxPRkn1cn7?g_st=awb',
			'coords'  => '',
			'order'   => 1,
			'excerpt' => __( 'Production and supply point for bricks and construction materials at Ram Thaman. Call or WhatsApp before visiting to confirm loading times.', 'brickpoint' ),
		),
		array(
			'title'   => __( 'Fine Bricks Company – Raja Jang', 'brickpoint' ),
			'company' => __( 'Fine Bricks Company', 'brickpoint' ),
			'address' => __( 'Raja Jang, Kasur District, Punjab, Pakistan', 'brickpoint' ),
			'map'     => 'https://maps.app.goo.gl/SDaxqVM55qXt66wW8?g_st=awb',
			'coords'  => '',
			'order'   => 2,
			'excerpt' => __( 'Bhatta location supplying bricks and bulk construction materials from Raja Jang.', 'brickpoint' ),
		),
		array(
			'title'   => __( 'Masha Allah Bricks Company – Sattoki', 'brickpoint' ),
			'company' => __( 'Masha Allah Bricks Company', 'brickpoint' ),
			'address' => __( 'Sattoki, Kasur District, Punjab, Pakistan', 'brickpoint' ),
			'map'     => 'https://www.google.com/maps?q=31.2328,74.3169424&z=17&hl=en',
			'coords'  => '31.2328, 74.3169424',
			'order'   => 3,
			'excerpt' => __( 'Brick production location at Sattoki with direct loading for project deliveries.', 'brickpoint' ),
		),
		array(
			'title'   => __( 'BrickPoint Office', 'brickpoint' ),
			'company' => __( 'BrickPoint', 'brickpoint' ),
			'address' => __( 'Lahore, Punjab, Pakistan', 'brickpoint' ),
			'map'     => 'https://maps.app.goo.gl/GACXw15YxyV4bK5t8?g_st=awb',
			'coords'  => '',
			'order'   => 4,
			'excerpt' => __( 'Sales office for quotations, order coordination and delivery scheduling.', 'brickpoint' ),
		),
	);
}

/**
 * Relationship helpers ------------------------------------------------------
 */

/**
 * Fetch posts of a type that reference a given post ID in a meta list field.
 *
 * @param string $post_type Post type.
 * @param string $meta_key  Meta key.
 * @param int    $post_id   Referenced post ID.
 * @param int    $limit     Limit.
 * @return WP_Query
 */
function brickpoint_query_referencing( $post_type, $meta_key, $post_id, $limit = 6, $paged = 0 ) {
	$query_args = array(
		'post_type'      => $post_type,
		'post_status'    => 'publish',
		'posts_per_page' => (int) $limit,
		'paged'          => $paged ? (int) $paged : max( 1, (int) get_query_var( 'paged' ) ),
		'no_found_rows'  => false,
		'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			array(
				'key'     => $meta_key,
				'value'   => (string) (int) $post_id,
				'compare' => 'LIKE',
			),
		),
	);

	return new WP_Query( $query_args );
}

/**
 * Related posts from the same taxonomy, excluding the current post.
 *
 * @param int    $post_id  Post ID.
 * @param string $taxonomy Taxonomy.
 * @param string $post_type Post type.
 * @param int    $limit    Limit.
 * @return WP_Query
 */
function brickpoint_query_related_by_term( $post_id, $taxonomy, $post_type, $limit = 3 ) {
	$terms = get_the_terms( $post_id, $taxonomy );
	$slugs = ( $terms && ! is_wp_error( $terms ) ) ? wp_list_pluck( $terms, 'slug' ) : array();

	$query_args = array(
		'post_type'      => $post_type,
		'post_status'    => 'publish',
		'posts_per_page' => (int) $limit,
		'post__not_in'   => array( (int) $post_id ),
		'no_found_rows'  => true,
	);

	if ( $slugs ) {
		$query_args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			array(
				'taxonomy' => $taxonomy,
				'field'    => 'slug',
				'terms'    => $slugs,
			),
		);
	}

	return new WP_Query( $query_args );
}
