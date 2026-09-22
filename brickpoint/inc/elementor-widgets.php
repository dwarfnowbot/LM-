<?php
/**
 * Elementor widget loader (thin wrapper).
 *
 * The widgets themselves live in elementor/widgets/*.php and are registered by
 * inc/elementor.php. This file exists so the theme structure stays predictable
 * and so a child theme can unhook the loader if it needs to.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Path helper for widget files.
 *
 * @param string $file File name.
 * @return string
 */
function brickpoint_widget_path( $file ) {
	return BRICKPOINT_DIR . 'elementor/widgets/' . $file;
}

/**
 * Which widgets ship with the theme (used by the docs screen and health check).
 *
 * @return array<string,string> File => widget name.
 */
function brickpoint_widget_manifest() {
	return array(
		'hero.php'              => 'bp_hero',
		'product-grid.php'      => 'bp_product_grid',
		'product-categories.php' => 'bp_product_categories',
		'product-price.php'     => 'bp_product_price',
		'product-gallery.php'   => 'bp_product_gallery',
		'product-specs.php'     => 'bp_product_specs',
		'whatsapp-button.php'   => 'bp_whatsapp_button',
		'video-grid.php'        => 'bp_video_grid',
		'video-card.php'        => 'bp_video_card',
		'project-grid.php'      => 'bp_project_grid',
		'location-cards.php'    => 'bp_location_cards',
		'social-links.php'      => 'bp_social_links',
		'stats.php'             => 'bp_stats',
		'contact-form.php'      => 'bp_contact_form',
		'ss7-showcase.php'      => 'bp_ss7_showcase',
		'section-heading.php'   => 'bp_section_heading',
		'breadcrumbs.php'       => 'bp_breadcrumbs',
		'team.php'              => 'bp_team',
	);
}

/**
 * Warn in the admin (Elementor screen only) if a widget file is missing.
 *
 * @return void
 */
function brickpoint_widget_health_check() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

	if ( ! $screen || false === strpos( (string) $screen->id, 'elementor' ) ) {
		return;
	}

	$missing = array();

	foreach ( array_keys( brickpoint_widget_manifest() ) as $file ) {
		if ( ! is_file( brickpoint_widget_path( $file ) ) ) {
			$missing[] = $file;
		}
	}

	if ( ! $missing ) {
		return;
	}

	printf(
		'<div class="notice notice-error"><p>%1$s <code>%2$s</code></p></div>',
		esc_html__( 'BrickPoint: these Elementor widget files are missing from the theme:', 'brickpoint' ),
		esc_html( implode( ', ', $missing ) )
	);
}
add_action( 'admin_notices', 'brickpoint_widget_health_check' );

/**
 * When Elementor renders a BrickPoint template, the theme should not wrap the
 * content in its own page container twice.
 *
 * @param bool $is_built_with_elementor Whether Elementor built the document.
 * @return bool
 */
function brickpoint_elementor_document_flag( $is_built_with_elementor ) {
	return $is_built_with_elementor;
}
add_filter( 'brickpoint_is_built_with_elementor', 'brickpoint_elementor_document_flag' );
