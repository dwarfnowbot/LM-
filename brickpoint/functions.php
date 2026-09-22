<?php
/**
 * BrickPoint theme bootstrap.
 *
 * Loads every module that makes up the theme. Nothing in here should output
 * markup - that belongs in the templates and template-parts.
 *
 * Safety model
 * ------------
 * A theme file damaged in transit (interrupted upload/FTP copy) would normally
 * produce a PHP parse error on every request and take the whole site - admin
 * included - offline. So every module is loaded inside a try/catch, and if
 * anything fails to load the theme:
 *
 *   1. keeps WordPress and wp-admin usable,
 *   2. shows the broken file names in the admin,
 *   3. serves a dependency-free fallback page on the front end instead of a
 *      fatal error,
 *   4. logs the details for the hosting error log.
 *
 * The theme can also be forced into this safe state without touching its files
 * by adding `define( 'BRICKPOINT_SAFE_MODE', true );` to wp-config.php.
 *
 * @package BrickPoint
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // No direct access.
}

define( 'BRICKPOINT_VERSION', '1.0.5' );
define( 'BRICKPOINT_DIR', trailingslashit( get_template_directory() ) );
define( 'BRICKPOINT_URI', trailingslashit( get_template_directory_uri() ) );

/**
 * Minimum supported PHP version.
 */
define( 'BRICKPOINT_MIN_PHP', '7.4' );

/**
 * Collects everything that stopped a module from loading.
 *
 * @var array<int,string>
 */
$GLOBALS['brickpoint_load_errors'] = array();

/**
 * Show the load problems in wp-admin (and log them).
 *
 * @return void
 */
function brickpoint_load_error_notice() {
	$errors = isset( $GLOBALS['brickpoint_load_errors'] ) ? (array) $GLOBALS['brickpoint_load_errors'] : array();

	if ( ! $errors ) {
		return;
	}

	echo '<div class="notice notice-error"><p><strong>' . esc_html__( 'BrickPoint theme: some theme files could not be loaded.', 'brickpoint' ) . '</strong></p>';
	echo '<ul style="list-style:disc;margin-left:20px">';

	foreach ( $errors as $error ) {
		echo '<li><code>' . esc_html( $error ) . '</code></li>';
	}

	echo '</ul><p>' . esc_html__( 'Your site is still online and this page is safe to use. To fix the theme: delete (or rename) the wp-content/themes/brickpoint folder and install the theme again from a fresh download - the uploaded copy is incomplete or damaged. Nothing else on your site is affected.', 'brickpoint' ) . '</p></div>';
}

/**
 * Serve a dependency-free page on the front end while the theme is incomplete.
 *
 * @param string $template Template path WordPress picked.
 * @return string
 */
function brickpoint_load_error_template( $template ) {
	if ( is_admin() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
		return $template;
	}

	$fallback = BRICKPOINT_DIR . 'fallback.php';

	return file_exists( $fallback ) ? $fallback : $template;
}

// Always listen: admin_notices only fires inside wp-admin anyway.
add_action( 'admin_notices', 'brickpoint_load_error_notice' );

$brickpoint_modules = array(
	'inc/helpers.php',            // Shared helper API (options, icons, images).
	'inc/setup.php',              // Theme supports, menus, image sizes, activation.
	'inc/enqueue.php',            // Styles & scripts.
	'inc/template-functions.php', // Body classes, breadcrumbs, schema, pagination.
	'inc/post-types.php',         // Products, Videos, Projects, Locations, Inquiries.
	'inc/taxonomies.php',         // Product / Video / Project categories.
	'inc/meta-fields.php',        // Admin meta boxes + registered post meta.
	'inc/product-functions.php',  // Product queries, cards, grids, specs.
	'inc/video-functions.php',    // Video source resolution, embeds, lightbox.
	'inc/project-functions.php',  // Project + location helpers.
	'inc/contact.php',            // Contact / quotation form (no WooCommerce).
	'inc/shortcodes.php',         // Shortcodes for any editor.
	'inc/whatsapp.php',           // WhatsApp inquiry / quotation system.
	'inc/customizer.php',         // Theme settings (brand, contact, hero, design).
	'inc/ajax.php',               // Admin-ajax endpoints (load more, form, filters).
	'inc/admin.php',              // BrickPoint admin dashboard + content setup.
	'inc/demo-content.php',       // Demo content: products, videos, projects, blog, photos, page designs.
	'inc/elementor.php',          // Elementor theme locations & integration.
	'inc/elementor-widgets.php',  // Custom Elementor widgets.
);

$brickpoint_skip_modules = '';

// 1. Manual safe mode (wp-config.php rescue switch).
if ( defined( 'BRICKPOINT_SAFE_MODE' ) && BRICKPOINT_SAFE_MODE ) {
	$brickpoint_skip_modules = 'Safe mode is enabled (BRICKPOINT_SAFE_MODE in wp-config.php).';
} elseif ( version_compare( PHP_VERSION, BRICKPOINT_MIN_PHP, '<' ) ) {
	// 2. Never fatal on an unsupported PHP version - the site stays online.
	$brickpoint_skip_modules = sprintf(
		/* translators: 1: detected PHP version, 2: required PHP version. */
		__( 'PHP %1$s is too old for this theme (PHP %2$s or newer is required). Ask your host to switch the PHP version.', 'brickpoint' ),
		PHP_VERSION,
		BRICKPOINT_MIN_PHP
	);
}

if ( $brickpoint_skip_modules ) {
	$GLOBALS['brickpoint_load_errors'][] = $brickpoint_skip_modules;
	add_filter( 'template_include', 'brickpoint_load_error_template' );
} else {
	foreach ( $brickpoint_modules as $brickpoint_module ) {
		$brickpoint_path = BRICKPOINT_DIR . $brickpoint_module;

		if ( ! file_exists( $brickpoint_path ) ) {
			$GLOBALS['brickpoint_load_errors'][] = $brickpoint_module . ' - ' . __( 'file is missing', 'brickpoint' );
			continue;
		}

		try {
			require_once $brickpoint_path;
		} catch ( \Throwable $brickpoint_error ) {
			// ParseError (damaged file), Error (missing function/class) or any
			// other Throwable: report it and carry on with the rest.
			$GLOBALS['brickpoint_load_errors'][] = $brickpoint_module . ' - ' . $brickpoint_error->getMessage();
		}
	}
}

// If anything failed, protect the front end and tell the administrator.
if ( ! empty( $GLOBALS['brickpoint_load_errors'] ) ) {
	add_filter( 'template_include', 'brickpoint_load_error_template' );

	foreach ( $GLOBALS['brickpoint_load_errors'] as $brickpoint_problem ) {
		error_log( '[BrickPoint] ' . $brickpoint_problem ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	}
}

unset( $brickpoint_modules, $brickpoint_module, $brickpoint_path, $brickpoint_skip_modules, $brickpoint_problem );
