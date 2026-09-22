<?php
/**
 * Asset loading. Every style and script is registered and enqueued once,
 * conditionally, so no page pays for assets it does not use.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue front-end assets.
 *
 * @return void
 */
function brickpoint_enqueue_assets() {
	$version = BRICKPOINT_VERSION;
	/*
	 * The theme ships readable (unminified) assets, so the minified variants are
	 * only used when a build step has actually produced them. This prevents
	 * 404s for assets/css/main.min.css on a stock install.
	 */
	$minified = ! ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) && file_exists( BRICKPOINT_DIR . 'assets/css/main.min.css' );
	$suffix   = $minified ? '.min' : '';

	// Optional Google Fonts (can be disabled from the Customizer).
	if ( brickpoint_option( 'bp_load_google_fonts' ) ) {
		$heading = brickpoint_option( 'bp_font_heading' );
		$body    = brickpoint_option( 'bp_font_body' );
		$families = array();

		foreach ( array( $heading, $body ) as $family ) {
			$family = trim( (string) $family );

			if ( $family ) {
				$families[ $family ] = str_replace( ' ', '+', $family ) . ':wght@400;500;600;700;800';
			}
		}

		if ( $families ) {
			$url = add_query_arg(
				array(
					'family'  => implode( '&family=', array_values( $families ) ),
					'display' => 'swap',
				),
				'https://fonts.googleapis.com/css2'
			);

			wp_enqueue_style( 'brickpoint-fonts', $url, array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		}
	}

	// Theme stylesheet (header only - kept for child themes and WP conventions).
	wp_enqueue_style( 'brickpoint-style', get_stylesheet_uri(), array(), $version );

	// Core layers.
	wp_enqueue_style( 'brickpoint-main', BRICKPOINT_URI . "assets/css/main{$suffix}.css", array( 'brickpoint-style' ), $version );
	wp_enqueue_style( 'brickpoint-animations', BRICKPOINT_URI . "assets/css/animations{$suffix}.css", array( 'brickpoint-main' ), $version );
	wp_enqueue_style( 'brickpoint-responsive', BRICKPOINT_URI . "assets/css/responsive{$suffix}.css", array( 'brickpoint-main' ), $version );

	// RTL support.
	if ( is_rtl() ) {
		wp_enqueue_style( 'brickpoint-rtl', BRICKPOINT_URI . 'rtl.css', array( 'brickpoint-main' ), $version );
	}

	// Elementor front-end tweaks only when Elementor rendered the page.
	if ( did_action( 'elementor/loaded' ) ) {
		wp_enqueue_style( 'brickpoint-elementor', BRICKPOINT_URI . "assets/css/elementor{$suffix}.css", array( 'brickpoint-main' ), $version );
	}

	// Scripts.
	wp_enqueue_script( 'brickpoint-navigation', BRICKPOINT_URI . "assets/js/navigation{$suffix}.js", array(), $version, true );
	wp_enqueue_script( 'brickpoint-animations-js', BRICKPOINT_URI . "assets/js/animations{$suffix}.js", array(), $version, true );
	wp_enqueue_script( 'brickpoint-ajax', BRICKPOINT_URI . "assets/js/ajax{$suffix}.js", array(), $version, true );
	wp_enqueue_script( 'brickpoint-main-js', BRICKPOINT_URI . "assets/js/main{$suffix}.js", array( 'brickpoint-navigation', 'brickpoint-ajax' ), $version, true );

	if ( brickpoint_needs_video_assets() ) {
		wp_enqueue_script( 'brickpoint-video', BRICKPOINT_URI . "assets/js/video{$suffix}.js", array( 'brickpoint-main-js' ), $version, true );
	}

	if ( is_singular( 'bp_product' ) || is_post_type_archive( 'bp_product' ) || is_tax( 'bp_product_category' ) || brickpoint_has_products_shortcode() ) {
		wp_enqueue_script( 'brickpoint-product', BRICKPOINT_URI . "assets/js/product{$suffix}.js", array( 'brickpoint-main-js' ), $version, true );
	}

	// Localised runtime data.
	wp_localize_script(
		'brickpoint-ajax',
		'brickpointData',
		array(
			'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
			'nonce'        => wp_create_nonce( 'brickpoint_nonce' ),
			'whatsapp'     => brickpoint_whatsapp_number(),
			'greeting'     => brickpoint_option( 'bp_whatsapp_greeting' ),
			'closing'      => brickpoint_option( 'bp_whatsapp_closing' ),
			'defaultMsg'   => brickpoint_option( 'bp_whatsapp_default' ),
			'isRtl'        => is_rtl() ? '1' : '0',
			'reducedMotion' => '0',
			'i18n'         => array(
				'loading'     => __( 'Loading…', 'brickpoint' ),
				'loadMore'    => __( 'Load More', 'brickpoint' ),
				'noMore'      => __( 'No more items', 'brickpoint' ),
				'noResults'   => __( 'No items found', 'brickpoint' ),
				'error'       => __( 'Something went wrong. Please try again.', 'brickpoint' ),
				'sending'     => __( 'Sending…', 'brickpoint' ),
				'sent'        => __( 'Thank you - your inquiry has been sent.', 'brickpoint' ),
				'copied'      => __( 'Link copied', 'brickpoint' ),
				'requiredQty' => __( 'Please enter a quantity', 'brickpoint' ),
				'playVideo'   => __( 'Play video', 'brickpoint' ),
				'close'       => __( 'Close', 'brickpoint' ),
				'prev'        => __( 'Previous', 'brickpoint' ),
				'next'        => __( 'Next', 'brickpoint' ),
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'brickpoint_enqueue_assets' );

/**
 * Do we need the video library on this request?
 *
 * @return bool
 */
function brickpoint_needs_video_assets() {
	if ( is_singular( array( 'bp_video', 'bp_product', 'bp_project', 'bp_location' ) ) ) {
		return true;
	}

	if ( is_post_type_archive( array( 'bp_video', 'bp_project' ) ) || is_tax( array( 'bp_video_category', 'bp_project_category' ) ) ) {
		return true;
	}

	if ( is_front_page() || is_page_template( array( 'page-templates/page-videos.php', 'page-templates/page-locations.php' ) ) ) {
		return true;
	}

	return (bool) apply_filters( 'brickpoint_needs_video_assets', false );
}

/**
 * Cheap check for product widgets inside post content / Elementor data.
 *
 * @return bool
 */
function brickpoint_has_products_shortcode() {
	$post = get_post();

	if ( ! $post instanceof WP_Post ) {
		return false;
	}

	$needles = array( '[bp_products', '[bp_product_categories', 'bp-product-grid', 'elementor-widget-bp_product_grid' );

	foreach ( $needles as $needle ) {
		if ( false !== strpos( (string) $post->post_content, $needle ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Add defer to our own scripts (they are all DOM-ready safe).
 *
 * @param string $tag    Script tag.
 * @param string $handle Handle.
 * @return string
 */
function brickpoint_script_attributes( $tag, $handle ) {
	if ( 0 !== strpos( (string) $handle, 'brickpoint' ) ) {
		return $tag;
	}

	if ( false !== strpos( $tag, ' defer' ) || false !== strpos( $tag, ' async' ) ) {
		return $tag;
	}

	return str_replace( ' src=', ' defer src=', $tag );
}
add_filter( 'script_loader_tag', 'brickpoint_script_attributes', 10, 2 );

/**
 * Preconnect for Google Fonts when they are enabled.
 *
 * @param array  $urls          Resource hints.
 * @param string $relation_type Relation.
 * @return array
 */
function brickpoint_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type && brickpoint_option( 'bp_load_google_fonts' ) ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'brickpoint_resource_hints', 10, 2 );

/**
 * Editor assets so the block editor matches the front end.
 *
 * @return void
 */
function brickpoint_block_editor_assets() {
	if ( ! brickpoint_option( 'bp_load_google_fonts' ) ) {
		// The owner turned remote fonts off: keep the editor local too.
		return;
	}

	wp_enqueue_style(
		'brickpoint-editor-fonts',
		'https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700&family=Inter:wght@400;500;600;700&display=swap',
		array(),
		null
	); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
}
add_action( 'enqueue_block_editor_assets', 'brickpoint_block_editor_assets' );
