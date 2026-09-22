<?php
/**
 * Shortcodes so any page (Gutenberg, Elementor text widget, or classic editor)
 * can drop in BrickPoint content without touching PHP.
 *
 * [bp_products] [bp_product_categories] [bp_videos] [bp_projects]
 * [bp_locations] [bp_contact_form] [bp_whatsapp]
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Products grid shortcode.
 *
 * @param array $atts Attributes.
 * @return string
 */
function brickpoint_shortcode_products( $atts ) {
	$atts = shortcode_atts(
		array(
			'category'   => '',
			'featured'   => 'no',
			'limit'      => 6,
			'columns'    => 3,
			'tablet'     => 2,
			'mobile'     => 1,
			'orderby'    => 'date',
			'order'      => 'DESC',
			'show_price' => 'yes',
			'show_desc'  => 'yes',
			'load_more'  => 'no',
			'title'      => '',
			'eyebrow'    => '',
		),
		$atts,
		'bp_products'
	);

	$args = array(
		'category'     => $atts['category'],
		'featured'     => in_array( strtolower( $atts['featured'] ), array( 'yes', 'true', '1' ), true ),
		'per_page'     => (int) $atts['limit'],
		'orderby'      => $atts['orderby'],
		'order'        => $atts['order'],
		'columns'      => (int) $atts['columns'],
		'columns_tablet' => (int) $atts['tablet'],
		'columns_mobile' => (int) $atts['mobile'],
		'show_price'   => in_array( strtolower( $atts['show_price'] ), array( 'yes', 'true', '1' ), true ),
		'show_excerpt' => in_array( strtolower( $atts['show_desc'] ), array( 'yes', 'true', '1' ), true ),
		'load_more'    => in_array( strtolower( $atts['load_more'] ), array( 'yes', 'true', '1' ), true ),
	);

	ob_start();

	if ( $atts['title'] || $atts['eyebrow'] ) {
		brickpoint_section_heading(
			array(
				'eyebrow' => $atts['eyebrow'],
				'title'   => $atts['title'],
			)
		);
	}

	brickpoint_render_product_grid( $args );

	return ob_get_clean();
}
add_shortcode( 'bp_products', 'brickpoint_shortcode_products' );

/**
 * Product categories shortcode.
 *
 * @param array $atts Attributes.
 * @return string
 */
function brickpoint_shortcode_product_categories( $atts ) {
	$atts = shortcode_atts(
		array(
			'limit'   => 12,
			'columns' => 4,
			'tablet'  => 3,
			'mobile'  => 2,
			'home'    => 'no',
			'title'   => '',
			'eyebrow' => '',
			'style'   => 'card',
		),
		$atts,
		'bp_product_categories'
	);

	ob_start();

	if ( $atts['title'] || $atts['eyebrow'] ) {
		brickpoint_section_heading(
			array(
				'eyebrow' => $atts['eyebrow'],
				'title'   => $atts['title'],
			)
		);
	}

	brickpoint_render_category_grid(
		array(
			'limit'          => (int) $atts['limit'],
			'columns'        => (int) $atts['columns'],
			'columns_tablet' => (int) $atts['tablet'],
			'columns_mobile' => (int) $atts['mobile'],
			'home_only'      => in_array( strtolower( $atts['home'] ), array( 'yes', 'true', '1' ), true ),
			'style'          => $atts['style'],
		)
	);

	return ob_get_clean();
}
add_shortcode( 'bp_product_categories', 'brickpoint_shortcode_product_categories' );

/**
 * Video grid shortcode.
 *
 * @param array $atts Attributes.
 * @return string
 */
function brickpoint_shortcode_videos( $atts ) {
	$atts = shortcode_atts(
		array(
			'category'  => '',
			'featured'  => 'no',
			'limit'     => 6,
			'columns'   => 3,
			'tablet'    => 2,
			'mobile'    => 1,
			'orderby'   => 'date',
			'filters'   => 'no',
			'load_more' => 'no',
			'title'     => '',
			'eyebrow'   => '',
			'desc'      => 'yes',
		),
		$atts,
		'bp_videos'
	);

	ob_start();

	if ( $atts['title'] || $atts['eyebrow'] ) {
		brickpoint_section_heading(
			array(
				'eyebrow' => $atts['eyebrow'],
				'title'   => $atts['title'],
			)
		);
	}

	brickpoint_render_video_grid(
		array(
			'category'       => $atts['category'],
			'featured'       => in_array( strtolower( $atts['featured'] ), array( 'yes', 'true', '1' ), true ),
			'per_page'       => (int) $atts['limit'],
			'columns'        => (int) $atts['columns'],
			'columns_tablet' => (int) $atts['tablet'],
			'columns_mobile' => (int) $atts['mobile'],
			'show_excerpt'   => in_array( strtolower( $atts['desc'] ), array( 'yes', 'true', '1' ), true ),
			'show_filter'    => in_array( strtolower( $atts['filters'] ), array( 'yes', 'true', '1' ), true ),
			'orderby'        => $atts['orderby'],
			'load_more'      => in_array( strtolower( $atts['load_more'] ), array( 'yes', 'true', '1' ), true ),
		)
	);

	return ob_get_clean();
}
add_shortcode( 'bp_videos', 'brickpoint_shortcode_videos' );

/**
 * Projects grid shortcode.
 *
 * @param array $atts Attributes.
 * @return string
 */
function brickpoint_shortcode_projects( $atts ) {
	$atts = shortcode_atts(
		array(
			'category' => '',
			'featured' => 'no',
			'limit'    => 6,
			'columns'  => 3,
			'tablet'   => 2,
			'mobile'   => 1,
			'title'    => '',
			'eyebrow'  => '',
		),
		$atts,
		'bp_projects'
	);

	ob_start();

	if ( $atts['title'] || $atts['eyebrow'] ) {
		brickpoint_section_heading(
			array(
				'eyebrow' => $atts['eyebrow'],
				'title'   => $atts['title'],
			)
		);
	}

	brickpoint_render_project_grid(
		array(
			'category'       => $atts['category'],
			'featured'       => in_array( strtolower( $atts['featured'] ), array( 'yes', 'true', '1' ), true ),
			'per_page'       => (int) $atts['limit'],
			'columns'        => (int) $atts['columns'],
			'columns_tablet' => (int) $atts['tablet'],
			'columns_mobile' => (int) $atts['mobile'],
		)
	);

	return ob_get_clean();
}
add_shortcode( 'bp_projects', 'brickpoint_shortcode_projects' );

/**
 * Locations grid shortcode.
 *
 * @param array $atts Attributes.
 * @return string
 */
function brickpoint_shortcode_locations( $atts ) {
	$atts = shortcode_atts(
		array(
			'columns' => 2,
			'tablet'  => 2,
			'mobile'  => 1,
			'video'   => 'yes',
			'limit'   => -1,
			'title'   => '',
			'eyebrow' => '',
		),
		$atts,
		'bp_locations'
	);

	ob_start();

	if ( $atts['title'] || $atts['eyebrow'] ) {
		brickpoint_section_heading(
			array(
				'eyebrow' => $atts['eyebrow'],
				'title'   => $atts['title'],
			)
		);
	}

	brickpoint_render_location_grid(
		array(
			'columns'        => (int) $atts['columns'],
			'columns_tablet' => (int) $atts['tablet'],
			'columns_mobile' => (int) $atts['mobile'],
			'show_video'     => in_array( strtolower( $atts['video'] ), array( 'yes', 'true', '1' ), true ),
			'per_page'       => (int) $atts['limit'],
		)
	);

	return ob_get_clean();
}
add_shortcode( 'bp_locations', 'brickpoint_shortcode_locations' );

/**
 * Contact form shortcode.
 *
 * @param array $atts Attributes.
 * @return string
 */
function brickpoint_shortcode_contact_form( $atts ) {
	$atts = shortcode_atts(
		array(
			'title'   => '',
			'text'    => '',
			'button'  => __( 'Send Inquiry', 'brickpoint' ),
			'context' => '',
		),
		$atts,
		'bp_contact_form'
	);

	ob_start();
	brickpoint_contact_form(
		array(
			'heading' => $atts['title'],
			'subtext' => $atts['text'],
			'button'  => $atts['button'],
			'context' => $atts['context'] ? $atts['context'] : __( 'Website inquiry form', 'brickpoint' ),
		)
	);

	return ob_get_clean();
}
add_shortcode( 'bp_contact_form', 'brickpoint_shortcode_contact_form' );

/**
 * WhatsApp button shortcode.
 *
 * @param array $atts Attributes.
 * @return string
 */
function brickpoint_shortcode_whatsapp( $atts ) {
	$atts = shortcode_atts(
		array(
			'label'   => __( 'WhatsApp Us', 'brickpoint' ),
			'message' => '',
			'style'   => 'whatsapp',
		),
		$atts,
		'bp_whatsapp'
	);

	return brickpoint_whatsapp_button(
		array(
			'label'   => $atts['label'],
			'message' => $atts['message'] ? $atts['message'] : brickpoint_general_inquiry_message(),
			'class'   => 'bp-btn bp-btn--' . sanitize_html_class( $atts['style'] ),
		)
	);
}
add_shortcode( 'bp_whatsapp', 'brickpoint_shortcode_whatsapp' );

/**
 * Single video embed shortcode: [bp_video id="123"] or [bp_video url="…"].
 *
 * @param array $atts Attributes.
 * @return string
 */
function brickpoint_shortcode_video( $atts ) {
	$atts = shortcode_atts(
		array(
			'id'      => 0,
			'url'     => '',
			'autoplay' => 'no',
			'poster'  => 0,
			'caption' => '',
		),
		$atts,
		'bp_video'
	);

	ob_start();

	brickpoint_inline_video(
		array(
			'video_id'  => (int) $atts['id'],
			'url'       => $atts['url'],
			'autoplay'  => in_array( strtolower( $atts['autoplay'] ), array( 'yes', 'true', '1' ), true ),
			'poster_id' => (int) $atts['poster'],
			'caption'   => $atts['caption'],
		)
	);

	return ob_get_clean();
}
add_shortcode( 'bp_video', 'brickpoint_shortcode_video' );

/**
 * Company facts strip shortcode (honest, editable stats only).
 *
 * @param array $atts Attributes.
 * @return string
 */
function brickpoint_shortcode_stats( $atts ) {
	$atts = shortcode_atts(
		array(
			'items' => '',
		),
		$atts,
		'bp_stats'
	);

	$items = array();

	foreach ( explode( '|', (string) $atts['items'] ) as $chunk ) {
		$parts = array_map( 'trim', explode( '::', $chunk ) );

		if ( count( $parts ) < 2 ) {
			continue;
		}

		$items[] = array(
			'icon'  => isset( $parts[2] ) ? $parts[2] : 'check',
			'title' => $parts[0],
			'text'  => $parts[1],
		);
	}

	if ( ! $items ) {
		$items = array(
			array(
				'icon'  => 'shield',
				'title' => __( 'Quality-focused supply', 'brickpoint' ),
				'text'  => __( 'Materials sourced from our own production and trusted suppliers.', 'brickpoint' ),
			),
			array(
				'icon'  => 'truck',
				'title' => __( 'Reliable delivery', 'brickpoint' ),
				'text'  => __( 'Loading and dispatch coordinated with your site schedule.', 'brickpoint' ),
			),
			array(
				'icon'  => 'factory',
				'title' => __( 'Multiple production locations', 'brickpoint' ),
				'text'  => __( 'Masha Allah Bricks Company, Fine Bricks Company and SS7 Bricks.', 'brickpoint' ),
			),
			array(
				'icon'  => 'layers',
				'title' => __( 'Construction material solutions', 'brickpoint' ),
				'text'  => __( 'From bricks and cement to steel, crush, sand and finishing items.', 'brickpoint' ),
			),
		);
	}

	ob_start();
	brickpoint_feature_grid( $items );

	return ob_get_clean();
}
add_shortcode( 'bp_stats', 'brickpoint_shortcode_stats' );
