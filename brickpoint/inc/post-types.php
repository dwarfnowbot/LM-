<?php
/**
 * Custom post types: Products, Videos, Projects, Locations and Inquiries.
 *
 * No WooCommerce anywhere - products are a first-class custom post type and
 * orders happen over WhatsApp / the contact form.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register every custom post type.
 *
 * @return void
 */
function brickpoint_register_post_types() {
	// ---------------------------------------------------------------- Products.
	register_post_type(
		'bp_product',
		array(
			'labels'              => array(
				'name'                  => _x( 'Products', 'post type general name', 'brickpoint' ),
				'singular_name'         => _x( 'Product', 'post type singular name', 'brickpoint' ),
				'menu_name'             => _x( 'Products', 'admin menu', 'brickpoint' ),
				'name_admin_bar'        => _x( 'Product', 'add new on admin bar', 'brickpoint' ),
				'add_new'               => __( 'Add Product', 'brickpoint' ),
				'add_new_item'          => __( 'Add New Product', 'brickpoint' ),
				'new_item'              => __( 'New Product', 'brickpoint' ),
				'edit_item'             => __( 'Edit Product', 'brickpoint' ),
				'view_item'             => __( 'View Product', 'brickpoint' ),
				'all_items'             => __( 'All Products', 'brickpoint' ),
				'search_items'          => __( 'Search Products', 'brickpoint' ),
				'not_found'             => __( 'No products found.', 'brickpoint' ),
				'not_found_in_trash'    => __( 'No products found in Trash.', 'brickpoint' ),
				'featured_image'        => __( 'Product Image', 'brickpoint' ),
				'set_featured_image'    => __( 'Set product image', 'brickpoint' ),
				'remove_featured_image' => __( 'Remove product image', 'brickpoint' ),
				'use_featured_image'    => __( 'Use as product image', 'brickpoint' ),
				'archives'              => __( 'Product Archive', 'brickpoint' ),
				'item_published'        => __( 'Product published.', 'brickpoint' ),
				'item_updated'          => __( 'Product updated.', 'brickpoint' ),
			),
			'description'         => __( 'BrickPoint products: bricks, SS7 bricks, cement, crush, sand, steel, pipes, chemicals, paints and more.', 'brickpoint' ),
			'public'              => true,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 20,
			'menu_icon'           => 'dashicons-screenoptions',
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'hierarchical'        => false,
			'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'page-attributes', 'custom-fields' ),
			'has_archive'         => 'products',
			'rewrite'             => array(
				'slug'       => 'products',
				'with_front' => false,
			),
			'show_in_rest'        => true,
			'rest_base'           => 'bp-products',
			'taxonomies'          => array( 'bp_product_category' ),
		)
	);

	// ------------------------------------------------------------------ Videos.
	register_post_type(
		'bp_video',
		array(
			'labels'           => array(
				'name'               => _x( 'Videos', 'post type general name', 'brickpoint' ),
				'singular_name'      => _x( 'Video', 'post type singular name', 'brickpoint' ),
				'menu_name'          => _x( 'Videos', 'admin menu', 'brickpoint' ),
				'add_new'            => __( 'Add Video', 'brickpoint' ),
				'add_new_item'       => __( 'Add New Video', 'brickpoint' ),
				'edit_item'          => __( 'Edit Video', 'brickpoint' ),
				'new_item'           => __( 'New Video', 'brickpoint' ),
				'view_item'          => __( 'View Video', 'brickpoint' ),
				'all_items'          => __( 'All Videos', 'brickpoint' ),
				'search_items'       => __( 'Search Videos', 'brickpoint' ),
				'not_found'          => __( 'No videos found.', 'brickpoint' ),
				'featured_image'     => __( 'Video Thumbnail', 'brickpoint' ),
				'set_featured_image' => __( 'Set video thumbnail', 'brickpoint' ),
			),
			'description'      => __( 'Video library: SS7 bricks, brick manufacturing, bhatta locations, projects, products and company videos.', 'brickpoint' ),
			'public'           => true,
			'publicly_queryable' => true,
			'show_ui'          => true,
			'show_in_menu'     => true,
			'show_in_rest'     => true,
			'rest_base'        => 'bp-videos',
			'menu_position'    => 21,
			'menu_icon'        => 'dashicons-format-video',
			'supports'         => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'page-attributes', 'custom-fields' ),
			'has_archive'      => 'videos',
			'rewrite'          => array(
				'slug'       => 'videos',
				'with_front' => false,
			),
			'taxonomies'       => array( 'bp_video_category' ),
			'capability_type'  => 'post',
			'map_meta_cap'     => true,
		)
	);

	// ---------------------------------------------------------------- Projects.
	register_post_type(
		'bp_project',
		array(
			'labels'           => array(
				'name'               => _x( 'Projects', 'post type general name', 'brickpoint' ),
				'singular_name'      => _x( 'Project', 'post type singular name', 'brickpoint' ),
				'menu_name'          => _x( 'Projects', 'admin menu', 'brickpoint' ),
				'add_new'            => __( 'Add Project', 'brickpoint' ),
				'add_new_item'       => __( 'Add New Project', 'brickpoint' ),
				'edit_item'          => __( 'Edit Project', 'brickpoint' ),
				'new_item'           => __( 'New Project', 'brickpoint' ),
				'view_item'          => __( 'View Project', 'brickpoint' ),
				'all_items'          => __( 'All Projects', 'brickpoint' ),
				'search_items'       => __( 'Search Projects', 'brickpoint' ),
				'not_found'          => __( 'No projects found.', 'brickpoint' ),
				'featured_image'     => __( 'Project Image', 'brickpoint' ),
				'set_featured_image' => __( 'Set project image', 'brickpoint' ),
			),
			'description'      => __( 'Project references and construction visuals. Mark illustrative visuals as "Illustrative construction reference".', 'brickpoint' ),
			'public'           => true,
			'publicly_queryable' => true,
			'show_ui'          => true,
			'show_in_menu'     => true,
			'show_in_rest'     => true,
			'rest_base'        => 'bp-projects',
			'menu_position'    => 22,
			'menu_icon'        => 'dashicons-building',
			'supports'         => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'page-attributes', 'custom-fields' ),
			'has_archive'      => 'projects',
			'rewrite'          => array(
				'slug'       => 'projects',
				'with_front' => false,
			),
			'taxonomies'       => array( 'bp_project_category' ),
			'capability_type'  => 'post',
			'map_meta_cap'     => true,
		)
	);

	// --------------------------------------------------------------- Locations.
	register_post_type(
		'bp_location',
		array(
			'labels'           => array(
				'name'               => _x( 'Locations', 'post type general name', 'brickpoint' ),
				'singular_name'      => _x( 'Location', 'post type singular name', 'brickpoint' ),
				'menu_name'          => _x( 'Locations', 'admin menu', 'brickpoint' ),
				'add_new'            => __( 'Add Location', 'brickpoint' ),
				'add_new_item'       => __( 'Add New Location', 'brickpoint' ),
				'edit_item'          => __( 'Edit Location', 'brickpoint' ),
				'new_item'           => __( 'New Location', 'brickpoint' ),
				'view_item'          => __( 'View Location', 'brickpoint' ),
				'all_items'          => __( 'All Locations', 'brickpoint' ),
				'search_items'       => __( 'Search Locations', 'brickpoint' ),
				'not_found'          => __( 'No locations found.', 'brickpoint' ),
				'featured_image'     => __( 'Location Image', 'brickpoint' ),
				'set_featured_image' => __( 'Set location image', 'brickpoint' ),
			),
			'description'      => __( 'BrickPoint companies, bhatta locations and the head office with real Google Maps links.', 'brickpoint' ),
			'public'           => true,
			'publicly_queryable' => true,
			'show_ui'          => true,
			'show_in_menu'     => true,
			'show_in_rest'     => true,
			'rest_base'        => 'bp-locations',
			'menu_position'    => 23,
			'menu_icon'        => 'dashicons-location-alt',
			'supports'         => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'page-attributes', 'custom-fields' ),
			'has_archive'      => 'locations',
			'rewrite'          => array(
				'slug'       => 'locations',
				'with_front' => false,
			),
			'capability_type'  => 'post',
			'map_meta_cap'     => true,
		)
	);

	// --------------------------------------------------------------- Inquiries.
	register_post_type(
		'bp_inquiry',
		array(
			'labels'          => array(
				'name'          => _x( 'Inquiries', 'post type general name', 'brickpoint' ),
				'singular_name' => _x( 'Inquiry', 'post type singular name', 'brickpoint' ),
				'menu_name'     => _x( 'Inquiries', 'admin menu', 'brickpoint' ),
				'edit_item'     => __( 'View Inquiry', 'brickpoint' ),
				'all_items'     => __( 'Inquiries', 'brickpoint' ),
				'search_items'  => __( 'Search Inquiries', 'brickpoint' ),
				'not_found'     => __( 'No inquiries yet.', 'brickpoint' ),
			),
			'description'     => __( 'Messages submitted through the contact and quotation form. Stored privately - never publicly viewable.', 'brickpoint' ),
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'show_in_rest'    => false,
			'menu_position'   => 24,
			'menu_icon'       => 'dashicons-email-alt',
			'supports'        => array( 'title', 'editor' ),
			'capability_type' => 'post',
			'map_meta_cap'    => true,
			'has_archive'     => false,
			'rewrite'         => false,
			'exclude_from_search' => true,
		)
	);
}
add_action( 'init', 'brickpoint_register_post_types', 5 );

/**
 * Stop a post type archive from shadowing a page with the same slug.
 *
 * WordPress resolves `/products/` to the product archive before it considers the
 * page called "Products", so the page the owner edits (and opens in Elementor)
 * would never be reachable. When a page already uses the archive slug, the page
 * wins and the archive is switched off - the page can show the same grid with
 * the [bp_products] shortcode.
 *
 * @param array  $args      Post type arguments.
 * @param string $post_type Post type name.
 * @return array
 */
function brickpoint_prevent_archive_slug_collision( $args, $post_type ) {
	if ( ! in_array( $post_type, array( 'bp_product', 'bp_video', 'bp_project', 'bp_location' ), true ) ) {
		return $args;
	}

	$slug = isset( $args['has_archive'] ) ? $args['has_archive'] : false;

	if ( ! is_string( $slug ) || '' === $slug ) {
		return $args;
	}

	$page = get_page_by_path( $slug );

	if ( $page instanceof WP_Post && 'publish' === $page->post_status ) {
		$args['has_archive'] = false;

		/*
		 * When the post type was already registered earlier in this request
		 * (the theme registers them on `init`, and the pages may have been
		 * created later in the same request) WordPress keeps the archive
		 * permastruct in memory. Drop it, otherwise a flush in this request
		 * would write the old archive rules again.
		 */
		if ( isset( $GLOBALS['wp_rewrite'] ) && $GLOBALS['wp_rewrite'] instanceof WP_Rewrite ) {
			$GLOBALS['wp_rewrite']->remove_permastruct( $post_type . '_archive' );
		}
	}

	return $args;
}
add_filter( 'register_post_type_args', 'brickpoint_prevent_archive_slug_collision', 10, 2 );

/**
 * Keep "Products", "Videos" and "Projects" archive columns readable.
 *
 * @param array  $columns Existing columns.
 * @param string $post_type Post type.
 * @return array
 */
function brickpoint_admin_columns( $columns, $post_type ) {
	if ( 'bp_product' === $post_type ) {
		$new = array();

		foreach ( $columns as $key => $label ) {
			$new[ $key ] = $label;

			if ( 'title' === $key ) {
				$new['bp_thumb']     = __( 'Image', 'brickpoint' );
				$new['bp_price']     = __( 'Price', 'brickpoint' );
				$new['bp_category']  = __( 'Category', 'brickpoint' );
				$new['bp_available'] = __( 'Availability', 'brickpoint' );
				$new['bp_featured']  = __( 'Featured', 'brickpoint' );
			}
		}

		return $new;
	}

	if ( 'bp_video' === $post_type ) {
		$new = array();

		foreach ( $columns as $key => $label ) {
			$new[ $key ] = $label;

			if ( 'title' === $key ) {
				$new['bp_thumb']    = __( 'Thumbnail', 'brickpoint' );
				$new['bp_source']   = __( 'Source', 'brickpoint' );
				$new['bp_duration'] = __( 'Duration', 'brickpoint' );
				$new['bp_featured'] = __( 'Featured', 'brickpoint' );
			}
		}

		return $new;
	}

	if ( 'bp_project' === $post_type ) {
		$new = array();

		foreach ( $columns as $key => $label ) {
			$new[ $key ] = $label;

			if ( 'title' === $key ) {
				$new['bp_thumb']    = __( 'Image', 'brickpoint' );
				$new['bp_location'] = __( 'Location', 'brickpoint' );
				$new['bp_status']   = __( 'Status', 'brickpoint' );
				$new['bp_featured'] = __( 'Featured', 'brickpoint' );
			}
		}

		return $new;
	}

	if ( 'bp_location' === $post_type ) {
		$new = array();

		foreach ( $columns as $key => $label ) {
			$new[ $key ] = $label;

			if ( 'title' === $key ) {
				$new['bp_thumb']  = __( 'Image', 'brickpoint' );
				$new['bp_branch'] = __( 'Company', 'brickpoint' );
				$new['bp_maps']   = __( 'Google Maps', 'brickpoint' );
			}
		}

		return $new;
	}

	return $columns;
}
add_filter( 'manage_posts_columns', 'brickpoint_admin_columns', 10, 2 );

/**
 * Render the custom admin columns.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 * @return void
 */
function brickpoint_admin_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'bp_thumb':
			if ( has_post_thumbnail( $post_id ) ) {
				echo wp_get_attachment_image( get_post_thumbnail_id( $post_id ), array( 64, 64 ), false, array( 'class' => 'bp-admin-thumb' ) );
			} else {
				echo '<span class="bp-admin-thumb bp-admin-thumb--empty" aria-hidden="true">—</span>';
			}
			break;

		case 'bp_price':
			$price = brickpoint_meta( $post_id, '_bp_price' );
			$label = brickpoint_meta( $post_id, '_bp_price_label' );

			if ( ! $price && ! $label ) {
				echo '&mdash;';
				break;
			}

			echo esc_html( trim( $label . ( $label && $price ? ' ' : '' ) . $price ) );
			break;

		case 'bp_category':
			$terms = get_the_terms( $post_id, 'bp_product_category' );

			if ( is_wp_error( $terms ) || ! $terms ) {
				echo '&mdash;';
				break;
			}

			echo esc_html( implode( ', ', wp_list_pluck( $terms, 'name' ) ) );
			break;

		case 'bp_available':
			$status = brickpoint_meta( $post_id, '_bp_availability' );
			echo $status ? esc_html( $status ) : '&mdash;';
			break;

		case 'bp_source':
			$source = brickpoint_meta( $post_id, '_bp_video_source', 'youtube' );
			$map    = array(
				'self'     => __( 'Self-hosted', 'brickpoint' ),
				'youtube'  => __( 'YouTube', 'brickpoint' ),
				'vimeo'    => __( 'Vimeo', 'brickpoint' ),
				'external' => __( 'External', 'brickpoint' ),
			);
			echo esc_html( isset( $map[ $source ] ) ? $map[ $source ] : $source );
			break;

		case 'bp_duration':
			$duration = brickpoint_meta( $post_id, '_bp_video_duration' );
			echo $duration ? esc_html( $duration ) : '&mdash;';
			break;

		case 'bp_location':
		case 'bp_branch':
			$value = 'bp_branch' === $column ? brickpoint_meta( $post_id, '_bp_location_company' ) : brickpoint_meta( $post_id, '_bp_project_location' );
			echo $value ? esc_html( $value ) : '&mdash;';
			break;

		case 'bp_status':
			$status = brickpoint_meta( $post_id, '_bp_project_status' );
			echo $status ? esc_html( $status ) : '&mdash;';
			break;

		case 'bp_maps':
			$map = brickpoint_meta( $post_id, '_bp_location_map' );
			echo $map ? '<span class="dashicons dashicons-yes-alt" aria-hidden="true"></span>' : '<span class="dashicons dashicons-warning" aria-hidden="true"></span>';
			break;

		case 'bp_featured':
			$is_featured = brickpoint_meta( $post_id, '_bp_featured' );
			echo $is_featured
				? '<span class="dashicons dashicons-star-filled" style="color:#c1440e" aria-hidden="true"><span class="screen-reader-text">' . esc_html__( 'Featured', 'brickpoint' ) . '</span></span>'
				: '&mdash;';
			break;
	}
}
add_action( 'manage_posts_custom_column', 'brickpoint_admin_column_content', 10, 2 );

/**
 * Make product prices sortable.
 *
 * @param array $columns Sortable columns.
 * @return array
 */
function brickpoint_sortable_columns( $columns ) {
	$columns['bp_price']     = '_bp_price';
	$columns['bp_available'] = '_bp_availability';

	return $columns;
}
add_filter( 'manage_edit-bp_product_sortable_columns', 'brickpoint_sortable_columns' );

/**
 * Handle the meta-based ordering in the admin list tables.
 *
 * @param WP_Query $query Query.
 * @return void
 */
function brickpoint_admin_orderby( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	$orderby = $query->get( 'orderby' );

	if ( in_array( $orderby, array( '_bp_price', '_bp_availability' ), true ) ) {
		$query->set( 'meta_key', $orderby );
		$query->set( 'orderby', 'meta_value' );
	}
}
add_action( 'pre_get_posts', 'brickpoint_admin_orderby' );

/**
 * Show the featured star / source hints in the posts list for quick scanning.
 *
 * @return void
 */
function brickpoint_admin_list_styles() {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

	if ( ! $screen || ! in_array( $screen->post_type, array( 'bp_product', 'bp_video', 'bp_project', 'bp_location' ), true ) ) {
		return;
	}

	echo '<style>.bp-admin-thumb{width:64px;height:64px;object-fit:cover;border-radius:6px;display:block}.bp-admin-thumb--empty{color:#8c8f94}.fixed .column-bp_thumb{width:78px}</style>';
}
add_action( 'admin_head', 'brickpoint_admin_list_styles' );
