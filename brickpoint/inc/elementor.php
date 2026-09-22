<?php
/**
 * Elementor integration: widget category, theme locations, editor support.
 *
 * Elementor (free) gives the page-building widgets. Elementor Pro adds the
 * Theme Builder, which is what makes header/footer/single/archive templates
 * editable visually. Without Pro the theme's own templates are used, and they
 * already implement the same design.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Bail helper: is Elementor loaded?
 *
 * @return bool
 */
function brickpoint_has_elementor() {
	return did_action( 'elementor/loaded' ) > 0;
}

/**
 * Register the BrickPoint widget category.
 *
 * @param \Elementor\Elements_Manager $elements_manager Elementor elements manager.
 * @return void
 */
function brickpoint_elementor_widget_category( $elements_manager ) {
	$elements_manager->add_category(
		'brickpoint',
		array(
			'title' => __( 'BrickPoint', 'brickpoint' ),
			'icon'  => 'eicon-blockquote',
		)
	);
}
add_action( 'elementor/elements/categories_registered', 'brickpoint_elementor_widget_category' );

/**
 * Register the theme's widgets.
 *
 * Uses the modern API when available and falls back to the legacy hook so the
 * theme keeps working across Elementor versions.
 *
 * @param \Elementor\Widgets_Manager $widgets_manager Widgets manager.
 * @return void
 */
function brickpoint_register_elementor_widgets( $widgets_manager ) {
	if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
		return;
	}

	$widget_files = array(
		'product-grid.php',
		'product-categories.php',
		'product-price.php',
		'product-gallery.php',
		'product-specs.php',
		'whatsapp-button.php',
		'video-grid.php',
		'video-card.php',
		'project-grid.php',
		'location-cards.php',
		'social-links.php',
		'stats.php',
		'contact-form.php',
		'hero.php',
		'ss7-showcase.php',
		'section-heading.php',
		'breadcrumbs.php',
		'team.php',
	);

	foreach ( $widget_files as $file ) {
		$path  = BRICKPOINT_DIR . 'elementor/widgets/' . $file;
		$class = 'BrickPoint\\Widgets\\' . brickpoint_widget_class_from_file( $file );

		// The class check comes first: on hosts where the theme directory is
		// reached through a symlink, `require_once` can load the same file under
		// two different paths - without this guard PHP would fail with
		// "Cannot declare class ... because the name is already in use".
		if ( class_exists( $class ) ) {
			$widgets_manager->register( new $class() );
			continue;
		}

		if ( ! is_file( $path ) ) {
			continue;
		}

		require_once $path;

		if ( class_exists( $class ) ) {
			$widgets_manager->register( new $class() );
		}
	}
}
add_action( 'elementor/widgets/register', 'brickpoint_register_elementor_widgets' );

/**
 * Legacy fallback for older Elementor versions.
 *
 * @return void
 */
function brickpoint_register_elementor_widgets_legacy() {
	if ( did_action( 'elementor/widgets/register' ) || ! class_exists( '\Elementor\Plugin' ) ) {
		return;
	}

	$manager = \Elementor\Plugin::instance()->widgets_manager;

	if ( $manager && method_exists( $manager, 'register_widget_type' ) ) {
		foreach ( array( 'product-grid', 'product-categories', 'product-price', 'whatsapp-button', 'video-grid', 'project-grid', 'location-cards', 'social-links', 'stats', 'contact-form', 'hero', 'ss7-showcase' ) as $file ) {
			$path  = BRICKPOINT_DIR . 'elementor/widgets/' . $file . '.php';
			$class = 'BrickPoint\\Widgets\\' . brickpoint_widget_class_from_file( $file . '.php' );

			if ( class_exists( $class ) ) {
				$manager->register_widget_type( new $class() );
				continue;
			}

			if ( ! is_file( $path ) ) {
				continue;
			}

			require_once $path;

			if ( class_exists( $class ) ) {
				$manager->register_widget_type( new $class() );
			}
		}
	}
}
add_action( 'elementor/widgets/widgets_registered', 'brickpoint_register_elementor_widgets_legacy' );

/**
 * Convert a widget file name into a class name fragment.
 *
 * product-grid.php -> Product_Grid
 *
 * @param string $file File name.
 * @return string
 */
function brickpoint_widget_class_from_file( $file ) {
	$name = str_replace( array( '.php', '-' ), array( '', '_' ), $file );

	return implode( '_', array_map( 'ucfirst', explode( '_', $name ) ) );
}

/**
 * Make our content types editable with Elementor.
 *
 * @return void
 */
function brickpoint_elementor_post_type_support() {
	foreach ( array( 'bp_product', 'bp_video', 'bp_project', 'bp_location', 'page', 'post' ) as $post_type ) {
		if ( ! post_type_supports( $post_type, 'elementor' ) ) {
			add_post_type_support( $post_type, 'elementor' );
		}
	}
}
add_action( 'init', 'brickpoint_elementor_post_type_support', 20 );

/**
 * Register Elementor Theme Builder locations (Elementor Pro).
 *
 * @param object $elementor_theme_manager Theme manager.
 * @return void
 */
function brickpoint_register_elementor_locations( $elementor_theme_manager ) {
	if ( ! is_object( $elementor_theme_manager ) ) {
		return;
	}

	if ( method_exists( $elementor_theme_manager, 'register_all_core_location' ) ) {
		$elementor_theme_manager->register_all_core_location();
	}

	// Extra, product/video/location specific locations.
	$extra = array(
		'bp_single_product'  => array(
			'label'     => __( 'Single Product (BrickPoint)', 'brickpoint' ),
			'post_type' => 'bp_product',
		),
		'bp_product_archive' => array(
			'label'     => __( 'Product Archive (BrickPoint)', 'brickpoint' ),
			'post_type' => 'bp_product',
		),
		'bp_single_video'    => array(
			'label'     => __( 'Single Video (BrickPoint)', 'brickpoint' ),
			'post_type' => 'bp_video',
		),
		'bp_video_archive'   => array(
			'label'     => __( 'Video Archive (BrickPoint)', 'brickpoint' ),
			'post_type' => 'bp_video',
		),
		'bp_single_project'  => array(
			'label'     => __( 'Single Project (BrickPoint)', 'brickpoint' ),
			'post_type' => 'bp_project',
		),
		'bp_project_archive' => array(
			'label'     => __( 'Project Archive (BrickPoint)', 'brickpoint' ),
			'post_type' => 'bp_project',
		),
	);

	foreach ( $extra as $location => $args ) {
		if ( method_exists( $elementor_theme_manager, 'register_location' ) ) {
			$elementor_theme_manager->register_location( $location, $args );
		}
	}
}
add_action( 'elementor/theme/register_locations', 'brickpoint_register_elementor_locations' );

/**
 * Render an Elementor theme location with a graceful fallback.
 *
 * @param string $location Location name.
 * @param callable $fallback Output used when no Elementor template matches.
 * @return void
 */
function brickpoint_do_elementor_location( $location, $fallback = null ) {
	$handled = false;

	if ( function_exists( 'elementor_theme_do_location' ) ) {
		$handled = elementor_theme_do_location( $location );
	}

	if ( ! $handled && is_callable( $fallback ) ) {
		call_user_func( $fallback );
	}
}

/**
 * Add BrickPoint design tokens to Elementor's global colors/fonts defaults
 * so new widgets start from the brand palette.
 *
 * @param \Elementor\Core\Kits\Documents\Kit $kit Elementor kit.
 * @return void
 */
function brickpoint_elementor_kit_defaults( $kit ) {
	if ( ! $kit || ! is_object( $kit ) || ! method_exists( $kit, 'get_settings' ) ) {
		return;
	}

	// Only seed defaults once, and never overwrite values the user already set.
	if ( get_option( 'brickpoint_elementor_kit_seeded' ) ) {
		return;
	}

	$settings = $kit->get_settings();

	$palette = array(
		'primary'   => brickpoint_option( 'bp_color_brick' ),
		'secondary' => brickpoint_option( 'bp_color_ink' ),
		'text'      => brickpoint_option( 'bp_color_text' ),
		'accent'    => brickpoint_option( 'bp_color_accent' ),
	);

	$changed = false;

	foreach ( $palette as $index => $color ) {
		$key = 'system_colors';

		if ( empty( $settings[ $key ] ) ) {
			continue;
		}

		foreach ( $settings[ $key ] as $i => $system_color ) {
			if ( isset( $system_color['_id'] ) && $system_color['_id'] === $index && empty( $system_color['color'] ) ) {
				$settings[ $key ][ $i ]['color'] = $color;
				$changed                         = true;
			}
		}
	}

	if ( $changed ) {
		$kit->update_settings( $settings );
	}

	update_option( 'brickpoint_elementor_kit_seeded', 1 );
}
/**
 * Seed the active Elementor kit from the BrickPoint tokens (colours, fonts).
 *
 * Hooked on `init` (priority 20): `elementor/loaded` fires before the theme is
 * loaded, and `elementor/kit/register_tabs` receives the tabs manager rather
 * than the kit itself, so neither can be used directly.
 *
 * @return void
 */
function brickpoint_seed_elementor_kit() {
	if ( ! did_action( 'elementor/loaded' ) || ! class_exists( '\Elementor\Plugin' ) ) {
		return;
	}

	$plugin = \Elementor\Plugin::$instance;

	if ( ! isset( $plugin->kits_manager ) || ! method_exists( $plugin->kits_manager, 'get_active_kit' ) ) {
		return;
	}

	brickpoint_elementor_kit_defaults( $plugin->kits_manager->get_active_kit() );
}
add_action( 'init', 'brickpoint_seed_elementor_kit', 20 );

/**
 * Editor styles inside the Elementor preview so widgets look right while editing.
 *
 * @return void
 */
function brickpoint_elementor_preview_assets() {
	wp_enqueue_style( 'brickpoint-main', BRICKPOINT_URI . 'assets/css/main.css', array(), BRICKPOINT_VERSION );
	wp_enqueue_style( 'brickpoint-animations', BRICKPOINT_URI . 'assets/css/animations.css', array(), BRICKPOINT_VERSION );
	wp_enqueue_style( 'brickpoint-responsive', BRICKPOINT_URI . 'assets/css/responsive.css', array(), BRICKPOINT_VERSION );
}
add_action( 'elementor/preview/enqueue_styles', 'brickpoint_elementor_preview_assets' );

/**
 * List of product/video/project select options for widget controls.
 *
 * @param string $post_type Post type.
 * @return array<int|string,string>
 */
function brickpoint_elementor_post_options( $post_type ) {
	$options = array( '' => __( '— Select —', 'brickpoint' ) );

	$posts = get_posts(
		array(
			'post_type'      => $post_type,
			'posts_per_page' => 100,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'post_status'    => 'publish',
		)
	);

	foreach ( $posts as $post ) {
		$options[ $post->ID ] = $post->post_title;
	}

	return $options;
}
