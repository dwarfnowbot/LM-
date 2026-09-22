<?php
/**
 * BrickPoint Customizer settings.
 *
 * Everything the site owner may want to change without touching PHP:
 * brand, contact, social links, WhatsApp text, hero video, global design tokens
 * and footer content.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the Customizer panel.
 *
 * @param WP_Customize_Manager $wp_customize Customizer.
 * @return void
 */
function brickpoint_customize_register( $wp_customize ) {
	$defaults = brickpoint_default_options();

	// ------------------------------------------------------------- Sanitizers.
	$sanitize_text     = 'sanitize_text_field';
	$sanitize_textarea = 'wp_kses_post';
	$sanitize_url      = 'esc_url_raw';
	$sanitize_hex      = 'sanitize_hex_color';
	$sanitize_int      = 'absint';
	$sanitize_checkbox = function ( $value ) {
		return $value ? 1 : 0;
	};

	$wp_customize->add_panel(
		'brickpoint_panel',
		array(
			'title'       => __( 'BrickPoint Theme', 'brickpoint' ),
			'description' => __( 'Brand, contact details, hero video, social links, design tokens and footer content.', 'brickpoint' ),
			'priority'    => 5,
		)
	);

	/**
	 * Helper to add a setting + control in one call.
	 *
	 * @param string $id      Setting id.
	 * @param string $section Section id.
	 * @param string $label   Label.
	 * @param string $type    Control type.
	 * @param mixed  $default Default value.
	 * @param callable $sanitize Sanitizer.
	 * @param array  $extra   Extra control args.
	 * @return void
	 */
	$add = function ( $id, $section, $label, $type = 'text', $default = '', $sanitize = 'sanitize_text_field', $extra = array() ) use ( $wp_customize ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $default,
				'sanitize_callback' => $sanitize,
				'transport'         => 'refresh',
			)
		);

		$args = wp_parse_args(
			$extra,
			array(
				'label'   => $label,
				'section' => $section,
			)
		);

		switch ( $type ) {
			case 'color':
				$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $id, $args ) );
				break;

			case 'image':
				$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $id, $args ) );
				break;

			case 'media':
				$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, $id, wp_parse_args( array( 'mime_type' => 'video' ), $args ) ) );
				break;

			case 'textarea':
				$args['type'] = 'textarea';
				$wp_customize->add_control( $id, $args );
				break;

			case 'select':
			case 'range':
			case 'checkbox':
				$args['type'] = $type;
				$wp_customize->add_control( $id, $args );
				break;

			case 'url':
				$args['type'] = 'url';
				$wp_customize->add_control( $id, $args );
				break;

			default:
				$args['type'] = 'text';
				$wp_customize->add_control( $id, $args );
				break;
		}
	};

	// ------------------------------------------------------- Brand & contact.
	$wp_customize->add_section(
		'brickpoint_brand',
		array(
			'title'       => __( 'Brand & Contact', 'brickpoint' ),
			'description' => __( 'Logo and favicon are managed in WordPress → Site Identity. These fields power the header top bar, footer and buttons.', 'brickpoint' ),
			'panel'       => 'brickpoint_panel',
		)
	);

	$add( 'bp_brand_name', 'brickpoint_brand', __( 'Brand name', 'brickpoint' ), 'text', $defaults['bp_brand_name'], $sanitize_text );
	$add( 'bp_phone', 'brickpoint_brand', __( 'Phone number (displayed)', 'brickpoint' ), 'text', $defaults['bp_phone'], $sanitize_text, array( 'description' => __( 'Example: 03152850818', 'brickpoint' ) ) );
	$add( 'bp_whatsapp', 'brickpoint_brand', __( 'WhatsApp number (international, digits only)', 'brickpoint' ), 'text', $defaults['bp_whatsapp'], $sanitize_text, array( 'description' => __( 'Example: 923152850818 - used in every WhatsApp link.', 'brickpoint' ) ) );
	$add( 'bp_email', 'brickpoint_brand', __( 'Email address', 'brickpoint' ), 'text', $defaults['bp_email'], 'sanitize_email' );
	$add( 'bp_address', 'brickpoint_brand', __( 'Office / head office address', 'brickpoint' ), 'textarea', $defaults['bp_address'], $sanitize_textarea );
	$add( 'bp_hours', 'brickpoint_brand', __( 'Opening hours', 'brickpoint' ), 'text', $defaults['bp_hours'], $sanitize_text );

	// Management.
	$add( 'bp_ceo_name', 'brickpoint_brand', __( 'CEO name', 'brickpoint' ), 'text', $defaults['bp_ceo_name'], $sanitize_text );
	$add( 'bp_ceo_role', 'brickpoint_brand', __( 'CEO role label', 'brickpoint' ), 'text', $defaults['bp_ceo_role'], $sanitize_text );
	$add( 'bp_sales_name', 'brickpoint_brand', __( 'Sales manager name', 'brickpoint' ), 'text', $defaults['bp_sales_name'], $sanitize_text );
	$add( 'bp_sales_role', 'brickpoint_brand', __( 'Sales manager role label', 'brickpoint' ), 'text', $defaults['bp_sales_role'], $sanitize_text );

	// ---------------------------------------------------------------- Social.
	$wp_customize->add_section(
		'brickpoint_social',
		array(
			'title'       => __( 'Social Links', 'brickpoint' ),
			'description' => __( 'Leave a field empty to hide that network everywhere (header, footer, share bar).', 'brickpoint' ),
			'panel'       => 'brickpoint_panel',
		)
	);

	foreach ( brickpoint_social_networks() as $slug => $network ) {
		$add( $network['key'], 'brickpoint_social', $network['label'], 'url', isset( $defaults[ $network['key'] ] ) ? $defaults[ $network['key'] ] : '', $sanitize_url );
	}

	// -------------------------------------------------------------- WhatsApp.
	$wp_customize->add_section(
		'brickpoint_whatsapp',
		array(
			'title'       => __( 'WhatsApp Inquiry', 'brickpoint' ),
			'description' => __( 'Copy used to build prefilled WhatsApp messages. Product name, category, price and unit are added automatically.', 'brickpoint' ),
			'panel'       => 'brickpoint_panel',
		)
	);

	$add( 'bp_whatsapp_greeting', 'brickpoint_whatsapp', __( 'Greeting line', 'brickpoint' ), 'text', $defaults['bp_whatsapp_greeting'], $sanitize_text );
	$add( 'bp_whatsapp_closing', 'brickpoint_whatsapp', __( 'Closing line', 'brickpoint' ), 'textarea', $defaults['bp_whatsapp_closing'], $sanitize_textarea );
	$add( 'bp_whatsapp_default', 'brickpoint_whatsapp', __( 'Default message (no product context)', 'brickpoint' ), 'textarea', $defaults['bp_whatsapp_default'], $sanitize_textarea );

	// ------------------------------------------------------------------ Hero.
	$wp_customize->add_section(
		'brickpoint_hero',
		array(
			'title'       => __( 'Homepage Hero', 'brickpoint' ),
			'description' => __( 'Used by the default homepage. If you build the homepage in Elementor, use the "BP Hero" widget instead.', 'brickpoint' ),
			'panel'       => 'brickpoint_panel',
		)
	);

	$add( 'bp_hero_eyebrow', 'brickpoint_hero', __( 'Eyebrow text', 'brickpoint' ), 'text', $defaults['bp_hero_eyebrow'], $sanitize_text );
	$add( 'bp_hero_title', 'brickpoint_hero', __( 'Headline (one line per row)', 'brickpoint' ), 'textarea', $defaults['bp_hero_title'], $sanitize_textarea );
	$add( 'bp_hero_text', 'brickpoint_hero', __( 'Supporting paragraph', 'brickpoint' ), 'textarea', $defaults['bp_hero_text'], $sanitize_textarea );
	$add( 'bp_hero_cta1_label', 'brickpoint_hero', __( 'Primary button label', 'brickpoint' ), 'text', $defaults['bp_hero_cta1_label'], $sanitize_text );
	$add( 'bp_hero_cta1_link', 'brickpoint_hero', __( 'Primary button link', 'brickpoint' ), 'url', $defaults['bp_hero_cta1_link'], $sanitize_url, array( 'description' => __( 'Defaults to the Products page.', 'brickpoint' ) ) );
	$add( 'bp_hero_cta2_label', 'brickpoint_hero', __( 'Secondary button label', 'brickpoint' ), 'text', $defaults['bp_hero_cta2_label'], $sanitize_text );
	$add( 'bp_hero_cta2_link', 'brickpoint_hero', __( 'Secondary button link', 'brickpoint' ), 'url', $defaults['bp_hero_cta2_link'], $sanitize_url, array( 'description' => __( 'Defaults to the Contact page.', 'brickpoint' ) ) );
	$add( 'bp_hero_badge', 'brickpoint_hero', __( 'Hero video badge label', 'brickpoint' ), 'text', $defaults['bp_hero_badge'], $sanitize_text );

	$wp_customize->add_setting( 'bp_hero_video', array( 'default' => '', 'sanitize_callback' => $sanitize_int ) );
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'bp_hero_video',
			array(
				'label'     => __( 'Hero video (self-hosted MP4, muted loop)', 'brickpoint' ),
				'section'   => 'brickpoint_hero',
				'mime_type' => 'video',
			)
		)
	);

	$add( 'bp_hero_video_youtube', 'brickpoint_hero', __( 'or hero video URL (YouTube/Vimeo/MP4)', 'brickpoint' ), 'url', '', $sanitize_url, array( 'description' => __( 'Loads muted and looped after the page is interactive, so it never blocks rendering.', 'brickpoint' ) ) );

	$wp_customize->add_setting( 'bp_hero_poster', array( 'default' => '', 'sanitize_callback' => $sanitize_int ) );
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'brickpoint_hero_poster',
			array(
				'label'       => __( 'Video poster image', 'brickpoint' ),
				'description' => __( 'Shown before the video loads and on slow connections. Recommended.', 'brickpoint' ),
				'section'     => 'brickpoint_hero',
				'settings'    => 'bp_hero_poster',
				'mime_type'   => 'image',
			)
		)
	);

	$add(
		'bp_hero_overlay',
		'brickpoint_hero',
		__( 'Video overlay opacity (%)', 'brickpoint' ),
		'range',
		$defaults['bp_hero_overlay'],
		$sanitize_int,
		array(
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 95,
				'step' => 5,
			),
		)
	);

	$add(
		'bp_hero_video_radius',
		'brickpoint_hero',
		__( 'Video border radius (px)', 'brickpoint' ),
		'range',
		$defaults['bp_hero_video_radius'],
		$sanitize_int,
		array(
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 48,
				'step' => 2,
			),
		)
	);

	// ------------------------------------------------------------ Design system.
	$wp_customize->add_section(
		'brickpoint_design',
		array(
			'title'       => __( 'Design System', 'brickpoint' ),
			'description' => __( 'Global colors, typography and spacing. These feed CSS variables used by the whole theme and the Elementor widgets.', 'brickpoint' ),
			'panel'       => 'brickpoint_panel',
		)
	);

	$colors = array(
		'bp_color_ink'      => __( 'Deep charcoal (dark surfaces)', 'brickpoint' ),
		'bp_color_ink_soft' => __( 'Soft charcoal (cards on dark)', 'brickpoint' ),
		'bp_color_brick'    => __( 'Brick red (primary)', 'brickpoint' ),
		'bp_color_accent'   => __( 'Accent orange', 'brickpoint' ),
		'bp_color_sand'     => __( 'Light neutral / sand', 'brickpoint' ),
		'bp_color_text'     => __( 'Body text', 'brickpoint' ),
		'bp_color_muted'    => __( 'Muted text', 'brickpoint' ),
	);

	foreach ( $colors as $key => $label ) {
		$add( $key, 'brickpoint_design', $label, 'color', $defaults[ $key ], $sanitize_hex );
	}

	$add( 'bp_font_heading', 'brickpoint_design', __( 'Heading font family', 'brickpoint' ), 'text', $defaults['bp_font_heading'], $sanitize_text );
	$add( 'bp_font_body', 'brickpoint_design', __( 'Body font family', 'brickpoint' ), 'text', $defaults['bp_font_body'], $sanitize_text );
	$add(
		'bp_load_google_fonts',
		'brickpoint_design',
		__( 'Load Google Fonts', 'brickpoint' ),
		'checkbox',
		$defaults['bp_load_google_fonts'],
		$sanitize_checkbox,
		array( 'description' => __( 'Uncheck if your fonts are loaded elsewhere (e.g. a performance plugin or local hosting).', 'brickpoint' ) )
	);

	$add(
		'bp_radius',
		'brickpoint_design',
		__( 'Card / button radius (px)', 'brickpoint' ),
		'range',
		$defaults['bp_radius'],
		$sanitize_int,
		array(
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 32,
				'step' => 2,
			),
		)
	);

	$add(
		'bp_container',
		'brickpoint_design',
		__( 'Container width (px)', 'brickpoint' ),
		'range',
		$defaults['bp_container'],
		$sanitize_int,
		array(
			'input_attrs' => array(
				'min'  => 960,
				'max'  => 1600,
				'step' => 20,
			),
		)
	);

	$add(
		'bp_section_space',
		'brickpoint_design',
		__( 'Section vertical spacing (px)', 'brickpoint' ),
		'range',
		$defaults['bp_section_space'],
		$sanitize_int,
		array(
			'input_attrs' => array(
				'min'  => 40,
				'max'  => 180,
				'step' => 4,
			),
		)
	);

	$add(
		'bp_anim_speed',
		'brickpoint_design',
		__( 'Animation speed (ms)', 'brickpoint' ),
		'range',
		$defaults['bp_anim_speed'],
		$sanitize_int,
		array(
			'input_attrs' => array(
				'min'  => 150,
				'max'  => 1200,
				'step' => 50,
			),
		)
	);

	// ---------------------------------------------------------------- Footer.
	$wp_customize->add_section(
		'brickpoint_footer',
		array(
			'title' => __( 'Footer', 'brickpoint' ),
			'panel' => 'brickpoint_panel',
		)
	);

	$add( 'bp_footer_about', 'brickpoint_footer', __( 'Footer about text', 'brickpoint' ), 'textarea', $defaults['bp_footer_about'], $sanitize_textarea );
	$add( 'bp_footer_copyright', 'brickpoint_footer', __( 'Copyright text', 'brickpoint' ), 'text', $defaults['bp_footer_copyright'], $sanitize_text, array( 'description' => __( 'Use {year} for the current year.', 'brickpoint' ) ) );
	$add( 'bp_footer_note', 'brickpoint_footer', __( 'Footer note / companies line', 'brickpoint' ), 'text', $defaults['bp_footer_note'], $sanitize_text );
}
add_action( 'customize_register', 'brickpoint_customize_register' );

/**
 * Print the design tokens as CSS variables so both the theme and Elementor
 * widgets share one source of truth.
 *
 * @return void
 */
function brickpoint_output_css_variables() {
	$ink     = brickpoint_option( 'bp_color_ink' );
	$ink2    = brickpoint_option( 'bp_color_ink_soft' );
	$brick   = brickpoint_option( 'bp_color_brick' );
	$accent  = brickpoint_option( 'bp_color_accent' );
	$sand    = brickpoint_option( 'bp_color_sand' );
	$text    = brickpoint_option( 'bp_color_text' );
	$muted   = brickpoint_option( 'bp_color_muted' );
	$radius  = (int) brickpoint_option( 'bp_radius' );
	$width   = (int) brickpoint_option( 'bp_container' );
	$space   = (int) brickpoint_option( 'bp_section_space' );
	$anim    = (int) brickpoint_option( 'bp_anim_speed' );
	$heading = brickpoint_option( 'bp_font_heading' );
	$body    = brickpoint_option( 'bp_font_body' );

	$css = ':root{';
	$css .= '--bp-ink:' . esc_attr( $ink ) . ';';
	$css .= '--bp-ink-soft:' . esc_attr( $ink2 ) . ';';
	$css .= '--bp-brick:' . esc_attr( $brick ) . ';';
	$css .= '--bp-accent:' . esc_attr( $accent ) . ';';
	$css .= '--bp-sand:' . esc_attr( $sand ) . ';';
	$css .= '--bp-text:' . esc_attr( $text ) . ';';
	$css .= '--bp-muted:' . esc_attr( $muted ) . ';';
	$css .= '--bp-radius:' . $radius . 'px;';
	$css .= '--bp-radius-sm:' . max( 0, $radius - 6 ) . 'px;';
	$css .= '--bp-radius-lg:' . ( $radius + 8 ) . 'px;';
	$css .= '--bp-container:' . $width . 'px;';
	$css .= '--bp-section:' . $space . 'px;';
	$css .= '--bp-anim:' . $anim . 'ms;';
	$css .= '--bp-font-heading:"' . esc_attr( $heading ) . '", "Barlow Condensed", "Arial Narrow", system-ui, sans-serif;';
	$css .= '--bp-font-body:"' . esc_attr( $body ) . '", "Inter", system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;';
	$css .= '}';

	printf( "<style id=\"brickpoint-tokens\">%s</style>\n", $css ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- values escaped above.
}
add_action( 'wp_head', 'brickpoint_output_css_variables', 5 );

/**
 * Mirror the tokens into the block editor so the backend matches the front end.
 *
 * @return void
 */
function brickpoint_editor_css_variables() {
	$brick = brickpoint_option( 'bp_color_brick' );
	$ink   = brickpoint_option( 'bp_color_ink' );

	$css = sprintf(
		':root{--bp-brick:%1$s;--bp-ink:%2$s;--bp-radius:%3$dpx;}',
		esc_attr( $brick ),
		esc_attr( $ink ),
		(int) brickpoint_option( 'bp_radius' )
	);

	/*
	 * Printed through the styles API, never echoed directly: this hook runs
	 * before the admin page sends its headers, and any raw output there breaks
	 * the response headers of every admin request (the editor, REST and
	 * admin-ajax included).
	 */
	wp_register_style( 'brickpoint-editor-tokens', false, array(), BRICKPOINT_VERSION );
	wp_enqueue_style( 'brickpoint-editor-tokens' );
	wp_add_inline_style( 'brickpoint-editor-tokens', $css );
}
add_action( 'enqueue_block_editor_assets', 'brickpoint_editor_css_variables' );
