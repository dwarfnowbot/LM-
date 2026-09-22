<?php
/**
 * AJAX endpoints: grid pagination, video filtering and the inquiry form.
 *
 * Every endpoint verifies a nonce and returns JSON only.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Verify the AJAX nonce or die with a JSON error.
 *
 * @return void
 */
function brickpoint_verify_ajax() {
	$nonce = isset( $_REQUEST['nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ) : '';

	if ( ! wp_verify_nonce( $nonce, 'brickpoint_nonce' ) ) {
		wp_send_json_error(
			array(
				'message' => __( 'Security check failed. Please reload the page.', 'brickpoint' ),
			),
			403
		);
	}
}

/**
 * Load more products / videos / projects.
 *
 * @return void
 */
function brickpoint_ajax_load_more() {
	brickpoint_verify_ajax();

	$type = isset( $_POST['type'] ) ? sanitize_key( wp_unslash( $_POST['type'] ) ) : 'product';
	$page = isset( $_POST['page'] ) ? max( 2, absint( wp_unslash( $_POST['page'] ) ) ) : 2;

	$raw_args = isset( $_POST['args'] ) ? wp_unslash( $_POST['args'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- decoded array is sanitised field-by-field below.

	$args = json_decode( (string) $raw_args, true );

	if ( ! is_array( $args ) ) {
		$args = array();
	}

	// Whitelist + sanitise incoming grid args.
	$args = array(
		'category'   => isset( $args['category'] ) ? sanitize_title( $args['category'] ) : '',
		'featured'   => ! empty( $args['featured'] ),
		'per_page'   => isset( $args['per_page'] ) ? max( 1, min( 48, absint( $args['per_page'] ) ) ) : 6,
		'orderby'    => isset( $args['orderby'] ) ? sanitize_key( $args['orderby'] ) : 'date',
		'order'      => isset( $args['order'] ) && 'ASC' === strtoupper( (string) $args['order'] ) ? 'ASC' : 'DESC',
		'columns'    => isset( $args['columns'] ) ? absint( $args['columns'] ) : 3,
		'columns_tablet' => isset( $args['columns_tablet'] ) ? absint( $args['columns_tablet'] ) : 2,
		'columns_mobile' => isset( $args['columns_mobile'] ) ? absint( $args['columns_mobile'] ) : 1,
		'show_excerpt'   => ! empty( $args['show_excerpt'] ),
		'product'    => isset( $args['product'] ) ? absint( $args['product'] ) : 0,
		'project'    => isset( $args['project'] ) ? absint( $args['project'] ) : 0,
		'location'   => isset( $args['location'] ) ? absint( $args['location'] ) : 0,
		'paged'      => $page,
	);

	ob_start();

	$found = 0;

	switch ( $type ) {
		case 'video':
			$query = brickpoint_video_query(
				array_merge(
					$args,
					array(
						'related_products'  => $args['product'] ? array( $args['product'] ) : array(),
						'related_projects'  => $args['project'] ? array( $args['project'] ) : array(),
						'related_locations' => $args['location'] ? array( $args['location'] ) : array(),
					)
				)
			);

			$found = (int) $query->post_count;

			while ( $query->have_posts() ) {
				$query->the_post();
				brickpoint_video_card( get_the_ID(), array( 'show_excerpt' => $args['show_excerpt'] ) );
			}

			wp_reset_postdata();
			$max = (int) $query->max_num_pages;
			break;

		case 'project':
			$query = brickpoint_project_query( $args );
			$found = (int) $query->post_count;

			while ( $query->have_posts() ) {
				$query->the_post();
				brickpoint_project_card( get_the_ID() );
			}

			wp_reset_postdata();
			$max = (int) $query->max_num_pages;
			break;

		case 'product':
		default:
			$query = brickpoint_product_query( $args );
			$found = (int) $query->post_count;

			while ( $query->have_posts() ) {
				$query->the_post();
				brickpoint_product_card( get_the_ID(), array( 'show_excerpt' => $args['show_excerpt'] ) );
			}

			wp_reset_postdata();
			$max = (int) $query->max_num_pages;
			break;
	}

	$html = ob_get_clean();

	wp_send_json_success(
		array(
			'html'  => $html,
			'page'  => $page,
			'max'   => $max,
			'count' => $found,
			'done'  => ( $page >= $max ) || 0 === $found,
		)
	);
}
add_action( 'wp_ajax_brickpoint_load_more', 'brickpoint_ajax_load_more' );
add_action( 'wp_ajax_nopriv_brickpoint_load_more', 'brickpoint_ajax_load_more' );

/**
 * Filter the video grid (category chips).
 *
 * @return void
 */
function brickpoint_ajax_filter_videos() {
	brickpoint_verify_ajax();

	$category = isset( $_POST['category'] ) ? sanitize_title( wp_unslash( $_POST['category'] ) ) : '';
	$per_page = isset( $_POST['per_page'] ) ? max( 1, min( 48, absint( wp_unslash( $_POST['per_page'] ) ) ) ) : 9;
	$featured = ! empty( $_POST['featured'] );
	$orderby  = isset( $_POST['orderby'] ) ? sanitize_key( wp_unslash( $_POST['orderby'] ) ) : 'date';

	$query = brickpoint_video_query(
		array(
			'category' => $category,
			'per_page' => $per_page,
			'featured' => $featured,
			'orderby'  => $orderby,
			'paged'    => 1,
		)
	);

	ob_start();

	while ( $query->have_posts() ) {
		$query->the_post();
		brickpoint_video_card( get_the_ID() );
	}

	$html = ob_get_clean();
	wp_reset_postdata();

	wp_send_json_success(
		array(
			'html'  => $html,
			'count' => (int) $query->post_count,
			'max'   => (int) $query->max_num_pages,
		)
	);
}
add_action( 'wp_ajax_brickpoint_filter_videos', 'brickpoint_ajax_filter_videos' );
add_action( 'wp_ajax_nopriv_brickpoint_filter_videos', 'brickpoint_ajax_filter_videos' );

/**
 * Contact / quotation form submission.
 *
 * @return void
 */
function brickpoint_ajax_contact() {
	brickpoint_verify_ajax();

	// The nonce was already verified by brickpoint_verify_ajax() above, so the
	// shared handler is told not to expect the front-end form nonce.
	$result = brickpoint_process_inquiry( wp_unslash( $_POST ), true ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitised inside brickpoint_process_inquiry().

	if ( $result['success'] ) {
		wp_send_json_success( $result );
	}

	wp_send_json_error( $result );
}
add_action( 'wp_ajax_brickpoint_contact', 'brickpoint_ajax_contact' );
add_action( 'wp_ajax_nopriv_brickpoint_contact', 'brickpoint_ajax_contact' );

/**
 * Live search suggestion for products (used by the header search).
 *
 * @return void
 */
function brickpoint_ajax_search_products() {
	brickpoint_verify_ajax();

	$term = isset( $_POST['term'] ) ? sanitize_text_field( wp_unslash( $_POST['term'] ) ) : '';

	if ( strlen( $term ) < 2 ) {
		wp_send_json_success( array( 'html' => '' ) );
	}

	$query = new WP_Query(
		array(
			'post_type'      => array( 'bp_product', 'bp_video', 'bp_project', 'post', 'page' ),
			's'              => $term,
			'posts_per_page' => 6,
			'no_found_rows'  => true,
		)
	);

	ob_start();

	if ( $query->have_posts() ) {
		echo '<ul class="bp-search-results__list">';

		while ( $query->have_posts() ) {
			$query->the_post();
			$type_object = get_post_type_object( get_post_type() );

			printf(
				'<li><a href="%1$s"><span class="bp-search-results__title">%2$s</span><span class="bp-search-results__type">%3$s</span></a></li>',
				esc_url( get_permalink() ),
				esc_html( get_the_title() ),
				esc_html( $type_object ? $type_object->labels->singular_name : __( 'Item', 'brickpoint' ) )
			);
		}

		echo '</ul>';
	} else {
		echo '<p class="bp-search-results__empty">' . esc_html__( 'No results found.', 'brickpoint' ) . '</p>';
	}

	wp_reset_postdata();

	wp_send_json_success( array( 'html' => ob_get_clean() ) );
}
add_action( 'wp_ajax_brickpoint_search', 'brickpoint_ajax_search_products' );
add_action( 'wp_ajax_nopriv_brickpoint_search', 'brickpoint_ajax_search_products' );
