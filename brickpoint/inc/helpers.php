<?php
/**
 * BrickPoint helper API.
 *
 * Small, dependency-free functions used by templates, widgets and admin screens.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Single source of truth for every theme setting default.
 *
 * @return array<string,string>
 */
function brickpoint_default_options() {
	$defaults = array(
		// Brand / contact.
		'bp_brand_name'        => 'BrickPoint',
		'bp_phone'             => '03152850818',
		'bp_whatsapp'          => '923152850818',
		'bp_email'             => '',
		'bp_address'           => '',
		'bp_hours'             => 'Monday – Saturday, 9:00 am – 7:00 pm',

		// Social.
		'bp_facebook'          => 'https://www.facebook.com/brickpoint.pk/',
		'bp_instagram'         => 'https://www.instagram.com/brickpoint.pk/',
		'bp_twitter'           => 'https://x.com/BrickPointPK',
		'bp_tiktok'            => 'https://www.tiktok.com/@brickpoint.pk/',
		'bp_youtube'           => '',

		// Management (About page).
		'bp_ceo_name'          => 'Syed Iftikhar Haider',
		'bp_ceo_role'          => 'Chief Executive Officer',
		'bp_sales_name'        => 'Qasim Iqbal',
		'bp_sales_role'        => 'Sales Manager',

		// WhatsApp behaviour.
		'bp_whatsapp_greeting' => 'Assalam-o-Alaikum BrickPoint,',
		'bp_whatsapp_closing'  => 'Please share availability, delivery details, and final quotation. Thank you.',
		'bp_whatsapp_default'  => 'Assalam-o-Alaikum BrickPoint, I would like to inquire about your construction materials. Please share details.',

		// Hero.
		'bp_hero_eyebrow'      => 'Bricks • Cement • Crush • Sand • Steel',
		'bp_hero_title'        => "Building Strength.\nDelivering Quality.\nShaping Tomorrow.",
		'bp_hero_text'         => 'Premium bricks and reliable construction materials for homes, commercial developments, and large-scale building projects.',
		'bp_hero_cta1_label'   => 'Explore Products',
		'bp_hero_cta1_link'    => '',
		'bp_hero_cta2_label'   => 'Request a Quote',
		'bp_hero_cta2_link'    => '',
		'bp_hero_video'        => '',
		'bp_hero_poster'       => '',
		'bp_hero_video_youtube' => '',
		'bp_hero_overlay'      => 62,
		'bp_hero_video_radius' => 18,
		'bp_hero_badge'        => 'SS7 Bricks',

		// Layout / design tokens.
		'bp_color_ink'         => '#0e0f11',
		'bp_color_ink_soft'    => '#16181c',
		'bp_color_brick'       => '#c1440e',
		'bp_color_accent'      => '#e2571e',
		'bp_color_sand'        => '#f5f1ea',
		'bp_color_text'        => '#2b2f36',
		'bp_color_muted'       => '#6b7078',
		'bp_font_heading'      => 'Barlow Condensed',
		'bp_font_body'         => 'Inter',
		'bp_radius'            => 14,
		'bp_container'         => 1240,
		'bp_section_space'     => 96,
		'bp_anim_speed'        => 600,
		'bp_load_google_fonts' => 1,

		// Footer.
		'bp_footer_about'      => 'BrickPoint supplies premium bricks and construction materials for contractors, builders, developers and architects - backed by our own production companies.',
		'bp_footer_copyright'  => '© {year} BrickPoint. All rights reserved.',
		'bp_footer_note'       => 'Masha Allah Bricks Company • Fine Bricks Company • SS7 Bricks',
	);

	/**
	 * Filter the theme option defaults.
	 *
	 * @param array<string,string> $defaults Option defaults.
	 */
	return apply_filters( 'brickpoint_default_options', $defaults );
}

/**
 * Read a theme setting (Customizer) with a sane fallback.
 *
 * @param string $key     Setting key.
 * @param mixed  $default Optional override default.
 * @return mixed
 */
function brickpoint_option( $key, $default = null ) {
	$defaults = brickpoint_default_options();
	$fallback = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';

	if ( null !== $default ) {
		$fallback = $default;
	}

	$value = get_theme_mod( $key, $fallback );

	/**
	 * Filter a single theme setting.
	 *
	 * @param mixed  $value Setting value.
	 * @param string $key   Setting key.
	 */
	return apply_filters( 'brickpoint_option', $value, $key );
}

/**
 * Site logo (custom logo, falls back to the built-in wordmark).
 *
 * @param array $args Optional args: class, height.
 * @return string
 */
function brickpoint_logo( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'class'  => 'bp-logo',
			'height' => 44,
			'footer' => false,
		)
	);

	$is_footer = (bool) $args['footer'];

	if ( has_custom_logo() ) {
		$logo_id  = get_theme_mod( 'custom_logo' );
		$img_class = $is_footer ? $args['class'] . '__img bp-logo__img--footer' : $args['class'] . '__img';
		$logo_img = wp_get_attachment_image(
			$logo_id,
			'full',
			false,
			array(
				'class'    => $img_class,
				'alt'      => get_bloginfo( 'name' ),
				'loading'  => 'eager',
				'decoding' => 'async',
			)
		);

		// In the footer show only the image (no wordmark next to it) so the
		// brand column stays tidy on every screen width.
		if ( $is_footer ) {
			return sprintf(
				'<a class="%1$s" href="%2$s" rel="home">%3$s</a>',
				esc_attr( $args['class'] ),
				esc_url( home_url( '/' ) ),
				$logo_img
			);
		}

		return sprintf(
			'<a class="%1$s" href="%2$s" rel="home">%3$s</a>',
			esc_attr( $args['class'] ),
			esc_url( home_url( '/' ) ),
			$logo_img . brickpoint_logo_text()
		);
	}

	/*
	 * Built-in logo: a bundled, transparent BrickPoint logo image that works on
	 * every screen size (it scales with the header/footer via CSS) and stays
	 * visible on both the light header and the dark footer. Users can replace
	 * it with their own in Appearance → Customize → Site Identity.
	 */
	return sprintf(
		'<a class="%1$s" href="%2$s" rel="home"><img class="%1$s__img" src="%3$s" alt="%4$s" loading="eager" decoding="async" /></a>',
		esc_attr( $args['class'] ),
		esc_url( home_url( '/' ) ),
		esc_url( BRICKPOINT_URI . ( $is_footer ? 'assets/logo/logo-white.png' : 'assets/logo/logo.png' ) ),
		esc_attr( get_bloginfo( 'name' ) )
	);
}

/**
 * Wordmark block used inside the logo link.
 *
 * @return string
 */
function brickpoint_logo_text() {
	$name = brickpoint_option( 'bp_brand_name' );
	$name = $name ? $name : get_bloginfo( 'name' );
	$tag  = get_bloginfo( 'description' );

	$html  = '<span class="bp-logo__mark" aria-hidden="true">' . brickpoint_icon( 'brick', array( 'size' => 26 ) ) . '</span>';
	$html .= '<span class="bp-logo__text">';
	$html .= '<span class="bp-logo__name">' . esc_html( $name ) . '</span>';
	$html .= '<span class="bp-logo__tag">' . esc_html( $tag ? $tag : __( 'Construction Materials', 'brickpoint' ) ) . '</span>';
	$html .= '</span>';

	return $html;
}

/**
 * Normalise a phone number for tel: links.
 *
 * @param string $phone Optional number, defaults to the theme setting.
 * @return string
 */
function brickpoint_phone_raw( $phone = '' ) {
	$phone = $phone ? $phone : brickpoint_option( 'bp_phone' );
	$phone = preg_replace( '/[^0-9+]/', '', (string) $phone );

	if ( '' === $phone ) {
		return '';
	}

	// Local Pakistani format 03xx... -> +923xx...
	if ( 0 === strpos( $phone, '0' ) ) {
		$phone = '+92' . substr( $phone, 1 );
	} elseif ( 0 !== strpos( $phone, '+' ) ) {
		$phone = '+' . $phone;
	}

	return $phone;
}

/**
 * Pretty phone number for display.
 *
 * @return string
 */
function brickpoint_phone_display() {
	$phone = brickpoint_option( 'bp_phone' );

	if ( '' === $phone ) {
		return '';
	}

	// Format 03152850818 -> 0315 285 0818.
	if ( preg_match( '/^0(\d{3})(\d{3})(\d{4})$/', $phone, $m ) ) {
		return '0' . $m[1] . ' ' . $m[2] . ' ' . $m[3];
	}

	return $phone;
}

/**
 * WhatsApp number in international, digits-only format.
 *
 * @return string
 */
function brickpoint_whatsapp_number() {
	$number = brickpoint_option( 'bp_whatsapp' );
	$number = preg_replace( '/[^0-9]/', '', (string) $number );

	if ( '' === $number ) {
		$number = '923152850818';
	}

	return $number;
}

/**
 * Get a single inline SVG icon.
 *
 * @param string $name Icon key.
 * @param array  $args size, class, stroke width.
 * @return string
 */
function brickpoint_icon( $name, $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'size'  => 20,
			'class' => '',
		)
	);

	$icons = brickpoint_icon_library();

	if ( ! isset( $icons[ $name ] ) ) {
		return '';
	}

	$class = trim( 'bp-icon bp-icon--' . $name . ' ' . $args['class'] );

	return sprintf(
		'<svg class="%1$s" width="%2$d" height="%2$d" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg">%3$s</svg>',
		esc_attr( $class ),
		(int) $args['size'],
		$icons[ $name ]
	);
}

/**
 * Icon library. Simple, consistent 24x24 line/solid artwork - no icon fonts.
 *
 * @return array<string,string>
 */
function brickpoint_icon_library() {
	$s = 'stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"';

	static $icons = null;

	if ( null !== $icons ) {
		return $icons;
	}

	$icons = array(
		'brick'      => '<path ' . $s . ' d="M3 8h18v8H3z"/><path ' . $s . ' d="M9 8v8M15 8v8M3 12h6M9 12h6M15 12h6"/>',
		'phone'      => '<path ' . $s . ' d="M6.6 3.5h2.2l1.5 3.6-1.7 1.2a11 11 0 0 0 5.1 5.1l1.2-1.7 3.6 1.5v2.2a2 2 0 0 1-2.2 2A15.6 15.6 0 0 1 4.6 5.7a2 2 0 0 1 2-2.2Z"/>',
		'whatsapp'   => '<path fill="currentColor" d="M12 2a9.9 9.9 0 0 0-8.5 15.1L2 22l5-1.4A9.9 9.9 0 1 0 12 2Zm0 1.9a8 8 0 0 1 6.8 12.2l-.3.5.8 3-3.1-.8-.5.3A8 8 0 1 1 12 3.9Zm-3.3 4c-.2 0-.5.1-.7.4-.3.3-.9.9-.9 2s.7 2.2.8 2.4c.1.2 1.4 2.3 3.5 3.1 1.7.7 2.1.6 2.5.5.5 0 1.4-.5 1.6-1.1.2-.6.2-1 .1-1.2-.1-.1-.3-.2-.6-.3l-1.3-.6c-.2-.1-.4-.1-.5.1l-.6.8c-.1.2-.3.2-.5.1-.2-.1-.9-.3-1.7-1-.6-.6-1-1.2-1.1-1.4-.1-.2 0-.3.1-.4l.4-.5c.1-.2.2-.3.3-.5v-.4l-.6-1.4c-.1-.4-.3-.3-.5-.3Z"/>',
		'mail'       => '<rect ' . $s . ' x="3" y="5" width="18" height="14" rx="2"/><path ' . $s . ' d="m3.5 7 8.5 6 8.5-6"/>',
		'pin'        => '<path ' . $s . ' d="M12 21s7-5.3 7-11a7 7 0 1 0-14 0c0 5.7 7 11 7 11Z"/><circle ' . $s . ' cx="12" cy="10" r="2.6"/>',
		'clock'      => '<circle ' . $s . ' cx="12" cy="12" r="9"/><path ' . $s . ' d="M12 7.5V12l3 1.8"/>',
		'check'      => '<path ' . $s . ' d="m4.5 12.5 5 5 10-11"/>',
		'arrow-right' => '<path ' . $s . ' d="M4 12h15m0 0-6-6m6 6-6 6"/>',
		'arrow-left' => '<path ' . $s . ' d="M20 12H5m0 0 6-6m-6 6 6 6"/>',
		'arrow-up'   => '<path ' . $s . ' d="M12 20V4m0 0-6 6m6-6 6 6"/>',
		'play'       => '<path fill="currentColor" d="M8 5.5v13l11-6.5-11-6.5Z"/>',
		'pause'      => '<path fill="currentColor" d="M7 5h3.2v14H7zM13.8 5H17v14h-3.2z"/>',
		'close'      => '<path ' . $s . ' d="M6 6l12 12M18 6 6 18"/>',
		'menu'       => '<path ' . $s . ' d="M3.5 7h17M3.5 12h17M3.5 17h17"/>',
		'chevron-down' => '<path ' . $s . ' d="m6 9.5 6 6 6-6"/>',
		'chevron-right' => '<path ' . $s . ' d="m9.5 6 6 6-6 6"/>',
		'plus'       => '<path ' . $s . ' d="M12 5v14M5 12h14"/>',
		'minus'      => '<path ' . $s . ' d="M5 12h14"/>',
		'grid'       => '<rect ' . $s . ' x="3.5" y="3.5" width="7" height="7" rx="1.5"/><rect ' . $s . ' x="13.5" y="3.5" width="7" height="7" rx="1.5"/><rect ' . $s . ' x="3.5" y="13.5" width="7" height="7" rx="1.5"/><rect ' . $s . ' x="13.5" y="13.5" width="7" height="7" rx="1.5"/>',
		'layers'     => '<path ' . $s . ' d="m12 3 8 4.5-8 4.5-8-4.5L12 3Z"/><path ' . $s . ' d="m4 12 8 4.5 8-4.5M4 16.5 12 21l8-4.5"/>',
		'truck'      => '<path ' . $s . ' d="M3 16V6h10v10M13 10h4l3 3v3h-7"/><circle ' . $s . ' cx="7" cy="17.5" r="1.8"/><circle ' . $s . ' cx="17" cy="17.5" r="1.8"/>',
		'factory'    => '<path ' . $s . ' d="M3 20h18V9l-6 4V9l-6 4V6H3v14Z"/><path ' . $s . ' d="M7 16h2"/>',
		'shield'     => '<path ' . $s . ' d="M12 3l7 3v5.5c0 4.3-3 7.9-7 9.5-4-1.6-7-5.2-7-9.5V6l7-3Z"/><path ' . $s . ' d="m9 12 2 2 4-4"/>',
		'star'       => '<path ' . $s . ' d="m12 4 2.4 5 5.6.8-4 3.9 1 5.5-5-2.7-5 2.7 1-5.5-4-3.9L10 9l2-5Z"/>',
		'quote'      => '<path ' . $s . ' d="M9 6.5C6.5 8 5 10.4 5 13.4c0 2.4 1.4 4.1 3.4 4.1 1.8 0 3.1-1.3 3.1-3.1 0-1.7-1.2-3-2.9-3-.3 0-.6 0-.8.1.3-1.5 1.3-2.8 2.7-3.7L9 6.5Zm8 0c-2.5 1.5-4 3.9-4 6.9 0 2.4 1.4 4.1 3.4 4.1 1.8 0 3.1-1.3 3.1-3.1 0-1.7-1.2-3-2.9-3-.3 0-.6 0-.8.1.3-1.5 1.3-2.8 2.7-3.7L17 6.5Z"/>',
		'share'      => '<circle ' . $s . ' cx="18" cy="5.5" r="2.5"/><circle ' . $s . ' cx="6" cy="12" r="2.5"/><circle ' . $s . ' cx="18" cy="18.5" r="2.5"/><path ' . $s . ' d="m8.2 10.8 7.6-4M8.2 13.2l7.6 4"/>',
		'download'   => '<path ' . $s . ' d="M12 4v11m0 0-4-4m4 4 4-4M5 19h14"/>',
		'file'       => '<path ' . $s . ' d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8l-5-5Z"/><path ' . $s . ' d="M14 3v5h5"/>',
		'calendar'   => '<rect ' . $s . ' x="3.5" y="5" width="17" height="15" rx="2"/><path ' . $s . ' d="M3.5 10h17M8 3.5v3M16 3.5v3"/>',
		'facebook'   => '<path fill="currentColor" d="M13.5 21v-7.2h2.5l.4-2.9h-2.9V9.1c0-.8.2-1.4 1.5-1.4h1.5V5.1c-.3 0-1.2-.1-2.2-.1-2.2 0-3.7 1.3-3.7 3.8v2.1H8.1v2.9h2.5V21h2.9Z"/>',
		'instagram'  => '<rect ' . $s . ' x="3.5" y="3.5" width="17" height="17" rx="5"/><circle ' . $s . ' cx="12" cy="12" r="3.8"/><circle fill="currentColor" cx="17" cy="7" r="1.2"/>',
		'twitter'    => '<path fill="currentColor" d="M17.5 3h3l-6.6 7.6L21.5 21h-5.9l-4.2-5.5L6.3 21H3.2l7-8L2.9 3h6l3.9 5.2L17.5 3Zm-1 16h1.6L7.6 4.9H5.9L16.5 19Z"/>',
		'tiktok'     => '<path fill="currentColor" d="M14.2 3h2.6c.2 1.7 1.2 3 2.8 3.5v2.6a6 6 0 0 1-2.9-.9v5.9a5.6 5.6 0 1 1-5.6-5.6c.3 0 .5 0 .8.1v2.7a2.9 2.9 0 1 0 2.2 2.8V3Z"/>',
		'youtube'    => '<rect ' . $s . ' x="2.5" y="5.5" width="19" height="13" rx="4"/><path fill="currentColor" d="M11 9.2l4.2 2.8L11 14.8V9.2Z"/>',
		'linkedin'   => '<rect ' . $s . ' x="3.5" y="3.5" width="17" height="17" rx="3"/><path ' . $s . ' d="M8 10.5V16M8 7.6v.1M12 16v-3.2a1.8 1.8 0 0 1 3.6 0V16"/>',
	);

	return $icons;
}

/**
 * Social networks exposed across the theme.
 *
 * @return array<string,array<string,string>>
 */
function brickpoint_social_networks() {
	return array(
		'facebook'  => array(
			'label' => __( 'Facebook', 'brickpoint' ),
			'key'   => 'bp_facebook',
		),
		'instagram' => array(
			'label' => __( 'Instagram', 'brickpoint' ),
			'key'   => 'bp_instagram',
		),
		'twitter'   => array(
			'label' => __( 'X (Twitter)', 'brickpoint' ),
			'key'   => 'bp_twitter',
		),
		'tiktok'    => array(
			'label' => __( 'TikTok', 'brickpoint' ),
			'key'   => 'bp_tiktok',
		),
		'youtube'   => array(
			'label' => __( 'YouTube', 'brickpoint' ),
			'key'   => 'bp_youtube',
		),
	);
}

/**
 * Get every social profile that actually has a URL.
 *
 * @return array<string,array<string,string>>
 */
function brickpoint_get_social_links() {
	$links = array();

	foreach ( brickpoint_social_networks() as $slug => $network ) {
		$url = brickpoint_option( $network['key'] );
		$url = is_string( $url ) ? trim( $url ) : '';

		if ( ! $url ) {
			continue;
		}

		$links[ $slug ] = array(
			'label' => $network['label'],
			'url'   => $url,
			'icon'  => $slug,
		);
	}

	/**
	 * Filter the social link list.
	 *
	 * @param array $links Social links.
	 */
	return apply_filters( 'brickpoint_social_links', $links );
}

/**
 * Was this post designed with Elementor (and does it really have elements)?
 *
 * Pages built with Elementor must not be wrapped in the theme's narrow
 * `.bp-container`, otherwise full-width sections (hero, dark bands) would be
 * squeezed into a 1240px box.
 *
 * @param int $post_id Post ID.
 * @return bool
 */
function brickpoint_is_elementor_page( $post_id ) {
	if ( ! did_action( 'elementor/loaded' ) || ! class_exists( '\Elementor\Plugin' ) ) {
		return false;
	}

	$plugin = \Elementor\Plugin::$instance;

	if ( ! isset( $plugin->documents ) || ! method_exists( $plugin->documents, 'get' ) ) {
		return false;
	}

	$document = $plugin->documents->get( $post_id );

	if ( ! $document || ! method_exists( $document, 'is_built_with_elementor' ) ) {
		return false;
	}

	if ( ! $document->is_built_with_elementor() ) {
		return false;
	}

	return brickpoint_page_has_content( $post_id );
}

/**
 * Is the current request the Elementor editor's preview frame (or the editor itself)?
 *
 * @return bool
 */
function brickpoint_is_elementor_preview() {
	if ( ! did_action( 'elementor/loaded' ) || ! class_exists( '\\Elementor\\Plugin' ) ) {
		return false;
	}

	$plugin = \Elementor\Plugin::$instance;

	if ( isset( $plugin->editor ) && method_exists( $plugin->editor, 'is_edit_mode' ) && $plugin->editor->is_edit_mode() ) {
		return true;
	}

	if ( isset( $plugin->preview ) && method_exists( $plugin->preview, 'is_preview_mode' ) && $plugin->preview->is_preview_mode() ) {
		return true;
	}

	return false;
}

/**
 * Does a page really contain something to render?
 *
 * Used by the front page: an empty page is not "empty" to Elementor - simply
 * opening the Elementor editor on it sets `_elementor_edit_mode`, even when no
 * widget has been added yet, which would otherwise leave the homepage blank.
 *
 * @param int $post_id Post ID.
 * @return bool
 */
function brickpoint_page_has_content( $post_id ) {
	$post = get_post( $post_id );

	if ( ! $post instanceof WP_Post ) {
		return false;
	}

	if ( '' !== trim( (string) $post->post_content ) ) {
		return true;
	}

	// Elementor: only count it as content when real elements were saved.
	$raw = get_post_meta( $post_id, '_elementor_data', true );

	if ( is_string( $raw ) && '' !== trim( $raw ) && '[]' !== trim( $raw ) ) {
		return true;
	}

	if ( is_array( $raw ) && $raw ) {
		return true;
	}

	if ( did_action( 'elementor/loaded' ) && class_exists( '\Elementor\Plugin' ) ) {
		$document = \Elementor\Plugin::$instance->documents->get( $post_id );

		if ( $document && method_exists( $document, 'get_elements_data' ) && ! empty( $document->get_elements_data() ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Does a post with this exact title already exist?
 *
 * Replacement for the deprecated get_page_by_title().
 *
 * @param string $title     Post title.
 * @param string $post_type Post type.
 * @return int Post ID or 0.
 */
function brickpoint_post_id_by_title( $title, $post_type = 'post' ) {
	if ( ! $title ) {
		return 0;
	}

	$query = new WP_Query(
		array(
			'post_type'              => $post_type,
			'title'                  => $title,
			'post_status'            => array( 'publish', 'draft', 'pending', 'private', 'future' ),
			'posts_per_page'         => 1,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	return $query->posts ? (int) $query->posts[0] : 0;
}

/**
 * Best-effort page URL lookup by slug.
 *
 * @param string $slug Page slug.
 * @return string
 */
function brickpoint_page_url( $slug ) {
	$page = get_page_by_path( $slug );

	if ( $page instanceof WP_Post ) {
		return get_permalink( $page );
	}

	return '';
}

/**
 * Best URL for a section: the published page when it exists, otherwise the
 * post-type archive.
 *
 * BrickPoint ships pages for Videos, Projects and Locations. When that page is
 * published it is preferred over the (disabled) archive, so menus and buttons
 * always point at a working URL.
 *
 * @param string $slug      Page slug.
 * @param string $post_type Post type whose archive is the fallback.
 * @return string
 */
function brickpoint_page_or_archive_url( $slug, $post_type ) {
	$page_url = brickpoint_page_url( $slug );

	if ( $page_url ) {
		return $page_url;
	}

	$archive = get_post_type_archive_link( $post_type );

	return $archive ? $archive : '';
}

/**
 * Format a price for display without assuming a currency.
 *
 * @param string $price Raw price.
 * @return string
 */
function brickpoint_format_price( $price ) {
	$price = is_string( $price ) ? trim( $price ) : '';

	if ( '' === $price ) {
		return '';
	}

	// Only reformat plain numbers - leave "Rs 45,000 / 1000 pcs" style values alone.
	if ( is_numeric( $price ) ) {
		return number_format_i18n( (float) $price, ( floor( (float) $price ) === (float) $price ) ? 0 : 2 );
	}

	return $price;
}

/**
 * Resolve an image URL from an ID or URL string.
 *
 * @param mixed  $value Attachment ID or URL.
 * @param string $size  Image size.
 * @return string
 */
function brickpoint_image_url( $value, $size = 'large' ) {
	if ( is_numeric( $value ) ) {
		$url = wp_get_attachment_image_url( (int) $value, $size );
		return $url ? $url : '';
	}

	return is_string( $value ) ? $value : '';
}

/**
 * Render a responsive image with lazy loading defaults.
 *
 * @param int    $attachment_id Attachment ID.
 * @param string $size          Image size.
 * @param array  $attr          Extra attributes.
 * @return string
 */
function brickpoint_image( $attachment_id, $size = 'bp-card', $attr = array() ) {
	$attachment_id = (int) $attachment_id;

	if ( ! $attachment_id ) {
		return '';
	}

	$defaults = array(
		'loading'  => 'lazy',
		'decoding' => 'async',
	);

	$attr = wp_parse_args( $attr, $defaults );

	return wp_get_attachment_image( $attachment_id, $size, false, $attr );
}

/**
 * Placeholder media block used when no image exists yet.
 *
 * @param string $ratio Aspect ratio class suffix.
 * @return string
 */
function brickpoint_placeholder( $ratio = '4x3' ) {
	return '<span class="bp-media__placeholder bp-media__placeholder--' . esc_attr( $ratio ) . '" aria-hidden="true">' . brickpoint_icon( 'brick', array( 'size' => 46 ) ) . '</span>';
}

/**
 * Trimmed excerpt helper.
 *
 * @param int    $words Word count.
 * @param string $text  Optional raw text.
 * @return string
 */
/**
 * Decode a term name for output.
 *
 * WordPress stores "&" in term names as "&amp;"; escaped again on the way out
 * that would reach the visitor as "&amp;". Names are therefore decoded once
 * when they are read.
 *
 * @param string $name Term name.
 * @return string
 */
function brickpoint_term_name( $name ) {
	return wp_specialchars_decode( (string) $name, ENT_QUOTES );
}

/**
 * Decode term names on read, site-wide (front end and admin).
 *
 * @param WP_Term $term Term object.
 * @return WP_Term
 */
function brickpoint_decode_term_object( $term ) {
	if ( $term instanceof WP_Term ) {
		$term->name = brickpoint_term_name( $term->name );
	}

	return $term;
}
add_filter( 'get_term', 'brickpoint_decode_term_object', 20 );

/**
 * Decode term names in term lists.
 *
 * @param array $terms Terms.
 * @return array
 */
function brickpoint_decode_term_list( $terms ) {
	if ( is_array( $terms ) ) {
		foreach ( $terms as $term ) {
			if ( $term instanceof WP_Term ) {
				$term->name = brickpoint_term_name( $term->name );
			}
		}
	}

	return $terms;
}
add_filter( 'get_terms', 'brickpoint_decode_term_list', 20 );

add_filter(
	'single_term_title',
	static function ( $title ) {
		return brickpoint_term_name( $title );
	},
	20
);

function brickpoint_excerpt( $words = 22, $text = '' ) {
	if ( '' === $text ) {
		$text = get_the_excerpt();
	}

	return wp_trim_words( wp_strip_all_tags( $text ), (int) $words, '…' );
}

/**
 * Read a product/video/project meta value with a default.
 *
 * @param int    $post_id Post ID.
 * @param string $key     Meta key.
 * @param mixed  $default Fallback.
 * @return mixed
 */
function brickpoint_meta( $post_id, $key, $default = '' ) {
	$value = get_post_meta( (int) $post_id, $key, true );

	if ( '' === $value || null === $value || ( is_array( $value ) && ! $value ) ) {
		return $default;
	}

	return $value;
}

/**
 * Is this a BrickPoint content type archive/single view?
 *
 * @return bool
 */
function brickpoint_is_bp_content() {
	$types = array( 'bp_product', 'bp_video', 'bp_project', 'bp_location' );

	return is_singular( $types ) || is_post_type_archive( $types ) || is_tax( array( 'bp_product_category', 'bp_video_category', 'bp_project_category' ) );
}
