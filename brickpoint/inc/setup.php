<?php
/**
 * Theme setup, supports, menus, image sizes and one-time activation work.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register theme supports, menus and image sizes.
 *
 * @return void
 */
function brickpoint_setup() {
	load_theme_textdomain( 'brickpoint', BRICKPOINT_DIR . 'languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor.css' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 96,
			'width'       => 320,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// Image sizes used by cards, heroes and galleries.
	add_image_size( 'bp-card', 720, 540, true );
	add_image_size( 'bp-card-wide', 900, 506, true );
	add_image_size( 'bp-square', 720, 720, true );
	add_image_size( 'bp-hero', 1920, 1080, true );
	add_image_size( 'bp-poster', 1280, 720, true );

	register_nav_menus(
		array(
			'primary'          => __( 'Primary Menu', 'brickpoint' ),
			'footer_products'  => __( 'Footer – Construction Materials', 'brickpoint' ),
			'footer_company'   => __( 'Footer – Company', 'brickpoint' ),
			'footer_support'   => __( 'Footer – Support & Legal', 'brickpoint' ),
			'topbar'           => __( 'Top Bar Links', 'brickpoint' ),
		)
	);

	if ( ! isset( $GLOBALS['content_width'] ) ) {
		$GLOBALS['content_width'] = 1240;
	}
}
add_action( 'after_setup_theme', 'brickpoint_setup' );

/**
 * Body classes for fine-grained styling and Elementor context.
 *
 * @param array $classes Existing classes.
 * @return array
 */
function brickpoint_body_classes( $classes ) {
	$classes[] = 'bp-body';

	if ( is_front_page() ) {
		$classes[] = 'bp-is-front';
	}

	if ( brickpoint_is_bp_content() ) {
		$classes[] = 'bp-is-content';
	}

	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'bp-no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'brickpoint_body_classes' );

/**
 * Register the themes single widget area (optional sidebar for the blog).
 *
 * @return void
 */
function brickpoint_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Blog Sidebar', 'brickpoint' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Optional sidebar shown next to the blog. Leave empty to keep the blog full width.', 'brickpoint' ),
			'before_widget' => '<section id="%1$s" class="bp-widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="bp-widget__title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'brickpoint_widgets_init' );

/**
 * One-time activation routine: pages, categories, permalinks.
 *
 * @return void
 */
function brickpoint_after_switch_theme() {
	// Activation must never be able to break the site: if the host times out or
	// a helper is unavailable, log it and let the site carry on. The same steps
	// can always be re-run from BrickPoint -> Setup & Content.
	try {
		brickpoint_run_activation_setup();
	} catch ( \Throwable $e ) {
		error_log( '[BrickPoint] activation setup stopped: ' . $e->getMessage() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	}
}
add_action( 'after_switch_theme', 'brickpoint_after_switch_theme' );

/**
 * Send the untouched WordPress starter post and page to the trash.
 *
 * Both are only removed when they still hold the exact default text, so a post
 * the owner has written is never affected.
 *
 * @return void
 */
function brickpoint_trash_default_sample_content() {
	$targets = array(
		1 => 'Welcome to WordPress',
		2 => 'This is an example page',
	);

	foreach ( $targets as $id => $marker ) {
		$post = get_post( $id );

		if ( ! $post || 'trash' === $post->post_status ) {
			continue;
		}

		if ( false === strpos( (string) $post->post_content, $marker ) ) {
			continue;
		}

		if ( get_post_meta( $id, '_bp_demo', true ) ) {
			continue;
		}

		wp_trash_post( $id );
	}
}

/**
 * The actual activation work (pages, categories, locations, menus, permalinks).
 *
 * @return void
 */
function brickpoint_run_activation_setup() {
	// Make sure our content types exist before touching terms.
	if ( function_exists( 'brickpoint_register_taxonomies' ) ) {
		brickpoint_register_taxonomies();
	}

	if ( function_exists( 'brickpoint_register_post_types' ) ) {
		brickpoint_register_post_types();
	}

	// Categories for products, videos and projects (idempotent).
	brickpoint_seed_taxonomy_terms( true );

	// Pages: Home, About Us, Products, Product Categories, SS7 Bricks,
	// Construction Materials, Projects, For Contractors/Builders/Companies,
	// Blog, Contact, Privacy Policy, Terms and Conditions.
	brickpoint_create_required_pages();

	// The four real BrickPoint locations with their Google Maps links.
	// Existing locations are never overwritten.
	if ( function_exists( 'brickpoint_seed_locations' ) ) {
		brickpoint_seed_locations();
	}

	// Navigation menus (primary, top bar and the three footer columns).
	if ( function_exists( 'brickpoint_seed_menus' ) ) {
		brickpoint_seed_menus();
	}

	// Park the untouched "Hello world!" post and "Sample Page" in the trash so
	// the site starts on the BrickPoint demo content instead of WordPress
	// defaults. Owner-written content is never touched.
	brickpoint_trash_default_sample_content();

	// Pretty permalinks for the new archives.
	if ( ! get_option( 'permalink_structure' ) ) {
		update_option( 'permalink_structure', '/%postname%/' );
	}

	// Demo content: products, videos, projects, blog posts, photos, clips and
	// the Elementor page designs. Skipped when the owner has removed it before,
	// or when BRICKPOINT_SKIP_DEMO_CONTENT is set in wp-config.php.
	if ( function_exists( 'brickpoint_demo_auto_allowed' ) && brickpoint_demo_auto_allowed() ) {
		try {
			brickpoint_import_demo_content( array( 'time_budget' => 40 ) );
		} catch ( \Throwable $e ) {
			error_log( '[BrickPoint] demo import stopped: ' . $e->getMessage() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		}
	}

	update_option( 'brickpoint_version', BRICKPOINT_VERSION );
	update_option( 'brickpoint_flush_needed', 1 );
	set_transient( 'brickpoint_activation_notice', 1, 300 );

	/*
	 * v1.0.5 upgrade: "Product Categories" is merged into "Construction
	 * Materials". This must run on activation/upgrade (not just at demo import
	 * time) so a site that was already live keeps a single, correct menu and
	 * front page. See brickpoint_upgrade_to_v105().
	 */
	if ( function_exists( 'brickpoint_upgrade_to_v105' ) ) {
		brickpoint_upgrade_to_v105();
	}

	/*
	 * Re-register the content types before flushing.
	 *
	 * The pages created above (Videos, Projects, Locations ...) decide whether
	 * the matching archive stays enabled (`brickpoint_prevent_archive_slug_collision`).
	 * The post types were registered earlier in this request, when those pages
	 * did not exist yet, so their cached `has_archive` value would still produce
	 * the old archive rules - and /projects/ would keep serving the archive
	 * instead of the page until something else flushed the rules.
	 */
	if ( function_exists( 'brickpoint_register_post_types' ) ) {
		brickpoint_register_post_types();
	}

	if ( function_exists( 'brickpoint_register_taxonomies' ) ) {
		brickpoint_register_taxonomies();
	}

	flush_rewrite_rules();
}

/**
 * One-time v1.0.5 upgrade: merge "Product Categories" into "Construction
 * Materials".
 *
 * The two pages used to show the same category grid. From v1.0.5 the
 * "Construction Materials" page is the single home for browsing by category,
 * so the support/legal links (and the seeded menus) stop pointing at that page
 * and the "Product Categories" page is unpublished. The page itself is kept
 * (not deleted) so any content the owner added is never lost; it can be
 * republished or deleted from Pages → All Pages.
 *
 * @return void
 */
function brickpoint_upgrade_to_v105() {
	if ( get_option( 'brickpoint_v105_done' ) ) {
		return;
	}

	// Rewrite site-wide links. brickpoint_page_url() is the shared helper used
	// for patents (menus), the footer, shortcode links and the breadcrumbs.
	$archived = get_page_by_path( 'product-categories' );
	$replacement = brickpoint_page_url( 'construction-materials' );

	/**
	 * Filter the URL that legacy "Product Categories" links are rewritten to.
	 *
	 * @param string $replacement Destination URL (Construction Materials page).
	 */
	$replacement = apply_filters( 'brickpoint_legacy_categories_url', $replacement );

	if ( $archived instanceof WP_Post && $replacement ) {
		$old_url = untrailingslashit( get_permalink( $archived ) );
		$new_url = untrailingslashit( (string) $replacement );
		$rewritten = 0;

		$menu_items = get_posts(
			array(
				'post_type'      => 'nav_menu_item',
				'post_status'    => 'any',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'no_found_rows'  => true,
			)
		);

		// Every URL already in a menu so we never create a duplicate link to
		// the Construction Materials page (v1.0.4 seeded both items).
		$menu_urls = array();
		foreach ( $menu_items as $item_id ) {
			$url = untrailingslashit( (string) get_post_meta( $item_id, '_menu_item_url', true ) );

			if ( '' !== $url ) {
				$menu_urls[ $item_id ] = $url;
			}
		}

		foreach ( $menu_items as $item_id ) {
			$url = isset( $menu_urls[ $item_id ] ) ? $menu_urls[ $item_id ] : '';

			if ( $url !== $old_url ) {
				continue;
			}

			if ( in_array( $new_url, $menu_urls, true ) ) {
				/*
				 * The destination page is already linked in the menus
				 * (the default v1.0.4 state), so the legacy item is a
				 * duplicate: remove the old link instead of adding a second.
				 */
				wp_delete_post( $item_id, true );
			} else {
				update_post_meta( $item_id, '_menu_item_url', $new_url );
				update_post_meta( $item_id, '_menu_item_object', '' );
				update_post_meta( $item_id, '_menu_item_object_id', '0' );
				update_post_meta( $item_id, '_menu_item_type', 'custom' );
			}

			$rewritten++;
		}

		// Park the page under "About Us" so /product-categories/ stops rendering.
		$about = get_page_by_path( 'about-us' );
		wp_update_post(
			array(
				'ID'           => $archived->ID,
				'post_status'  => 'draft',
				'post_name'    => 'product-categories-legacy',
				'post_parent'  => $about instanceof WP_Post ? $about->ID : 0,
			)
		);
	}

	// Make the real destination permanent: the "Construction Materials" page
	// is the single browse-by-category page. On a standard install the setting
	// was already "page", so this only changes sites that had switched it.
	$front = (int) get_option( 'page_on_front' );

	if ( $front ) {
		$target = get_page_by_path( 'product-categories' );
		if ( $target instanceof WP_Post && (int) $target->ID === $front ) {
			$new_front = get_page_by_path( 'construction-materials' );
			if ( $new_front instanceof WP_Post ) {
				update_option( 'page_on_front', $new_front->ID );
			}
		}
	}

	update_option( 'brickpoint_v105_done', 1 );

	// The "Product Categories" page is no longer in the navigation.
	flush_rewrite_rules();
}

/**
 * Full-size JPEG/WebP quality used by the theme's own sizes.
 *
 * @param int    $quality Quality 0-100.
 * @param string $mime    Mime type.
 * @return int
 */
function brickpoint_image_quality( $quality, $mime ) {
	if ( in_array( $mime, array( 'image/jpeg', 'image/webp' ), true ) ) {
		return 82;
	}

	return $quality;
}
add_filter( 'jpeg_quality', 'brickpoint_image_quality', 10, 2 );

/**
 * Allow inline SVG in `wp_kses` contexts used by our own output.
 *
 * @param array $tags Allowed tags.
 * @return array
 */
function brickpoint_kses_allowed_svg( $tags ) {
	$svg_attrs = array(
		'class'            => true,
		'width'            => true,
		'height'           => true,
		'viewbox'          => true,
		'fill'             => true,
		'stroke'           => true,
		'stroke-width'     => true,
		'stroke-linecap'   => true,
		'stroke-linejoin'  => true,
		'aria-hidden'      => true,
		'focusable'        => true,
		'xmlns'            => true,
		'role'             => true,
		'preserveaspectratio' => true,
	);

	$tags['svg']      = $svg_attrs;
	$tags['path']     = array(
		'd'    => true,
		'fill' => true,
		'stroke' => true,
	);
	$tags['circle']   = array(
		'cx' => true,
		'cy' => true,
		'r'  => true,
		'fill' => true,
	);
	$tags['rect']     = array(
		'x'      => true,
		'y'      => true,
		'width'  => true,
		'height' => true,
		'rx'     => true,
		'ry'     => true,
		'fill'   => true,
	);
	$tags['g']        = array( 'fill' => true );
	$tags['line']     = array(
		'x1' => true,
		'y1' => true,
		'x2' => true,
		'y2' => true,
		'stroke' => true,
	);
	$tags['polyline'] = array( 'points' => true );

	return $tags;
}
add_filter( 'wp_kses_allowed_html', 'brickpoint_kses_allowed_svg' );
