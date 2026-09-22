<?php
/**
 * BrickPoint admin: dashboard, one-click setup, required pages and theme
 * settings. The owner should never need to touch PHP for content changes.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Required pages with honest starter copy (no invented facts or statistics).
 *
 * @return array<string,array<string,mixed>>
 */
function brickpoint_page_seed_data() {
	$defaults = brickpoint_default_options();

	$pages = array(
		'home' => array(
			'title'   => __( 'Home', 'brickpoint' ),
			'front'   => true,
			'content' => '',
			'note'    => __( 'Leave this page empty to use the BrickPoint homepage sections, or design it with Elementor using the BrickPoint widgets.', 'brickpoint' ),
		),
		'about-us' => array(
			'title'   => __( 'About Us', 'brickpoint' ),
			'content' => "<!-- wp:paragraph -->\n<p>" . __( 'BrickPoint is a construction-materials supplier delivering premium bricks and building materials for contractors, builders, developers, architects and individual customers. Our production companies are Masha Allah Bricks Company, Fine Bricks Company and SS7 Bricks.', 'brickpoint' ) . "</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:heading -->\n<h2>" . __( 'Our commitment', 'brickpoint' ) . "</h2>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>" . __( 'We focus on consistent quality, reliable availability and clear communication from inquiry to delivery. Add your own verified details, certifications or milestones in WordPress - never publish claims you cannot confirm.', 'brickpoint' ) . "</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:shortcode -->[bp_stats]\n<!-- /wp:shortcode -->\n\n<!-- wp:heading -->\n<h2>" . __( 'Management', 'brickpoint' ) . "</h2>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p><strong>" . esc_html( $defaults['bp_ceo_role'] ) . ":</strong> " . esc_html( $defaults['bp_ceo_name'] ) . "<br /><strong>" . esc_html( $defaults['bp_sales_role'] ) . ":</strong> " . esc_html( $defaults['bp_sales_name'] ) . "</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:heading -->\n<h2>" . __( 'Our locations', 'brickpoint' ) . "</h2>\n<!-- /wp:heading -->\n\n<!-- wp:shortcode -->[bp_locations columns=\"2\" video=\"no\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:heading -->\n<h2>" . __( 'Talk to our team', 'brickpoint' ) . "</h2>\n<!-- /wp:heading -->\n\n<!-- wp:shortcode -->[bp_whatsapp label=\"" . __( 'WhatsApp Us', 'brickpoint' ) . "\"]\n<!-- /wp:shortcode -->",
		),
		'products' => array(
			'title'   => __( 'Products', 'brickpoint' ),
			'content' => "<!-- wp:paragraph -->\n<p>" . __( 'Bricks, cement, crush, sand, steel, pipes, chemicals and finishing materials - supplied for home construction, commercial developments and large-scale projects. Prices and availability are confirmed on inquiry.', 'brickpoint' ) . "</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:shortcode -->[bp_products limit=\"12\" columns=\"3\" load_more=\"yes\"]\n<!-- /wp:shortcode -->",
		),
		'product-categories' => array(
			'title'   => __( 'Product Categories', 'brickpoint' ),
			'content' => "<!-- wp:paragraph -->\n<p>" . __( 'Browse BrickPoint materials by category. Every category page shows the products we currently supply and a WhatsApp inquiry button.', 'brickpoint' ) . "</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:shortcode -->[bp_product_categories limit=\"15\" columns=\"4\"]\n<!-- /wp:shortcode -->",
		),
		'ss7-bricks' => array(
			'title'   => __( 'SS7 Bricks', 'brickpoint' ),
			'content' => "<!-- wp:paragraph -->\n<p>" . __( 'SS7 Bricks are produced, stacked and loaded with a focus on uniform size, clean edges and consistent strength. Ask us for current sizes, rates and availability for your project.', 'brickpoint' ) . "</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:heading -->\n<h2>" . __( 'The Strength Behind Every Structure', 'brickpoint' ) . "</h2>\n<!-- /wp:heading -->\n\n<!-- wp:shortcode -->[bp_products category=\"ss7-bricks\" limit=\"6\" columns=\"3\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:heading -->\n<h2>" . __( 'See it in action', 'brickpoint' ) . "</h2>\n<!-- /wp:heading -->\n\n<!-- wp:shortcode -->[bp_videos category=\"ss7-bricks\" limit=\"3\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:heading -->\n<h2>" . __( 'Request a quotation', 'brickpoint' ) . "</h2>\n<!-- /wp:heading -->\n\n<!-- wp:shortcode -->[bp_whatsapp label=\"" . __( 'Ask about SS7 Bricks', 'brickpoint' ) . "\" message=\"" . __( 'Assalam-o-Alaikum BrickPoint, I would like a quotation for SS7 Bricks.', 'brickpoint' ) . "\"]\n<!-- /wp:shortcode -->",
		),
		'construction-materials' => array(
			'title'   => __( 'Construction Materials', 'brickpoint' ),
			'content' => "<!-- wp:paragraph -->\n<p>" . __( 'A single source for the materials a project needs: cement, crush, sand, steel, electrical and plumbing pipes, construction chemicals, insulation, cables, paints, lights and switches. Browse everything by category below, or see all products at once.', 'brickpoint' ) . "</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:shortcode -->[bp_product_categories limit=\"15\" columns=\"4\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:heading -->\n<h2>" . __( 'All materials', 'brickpoint' ) . \"</h2>\n<!-- /wp:heading -->\n\n<!-- wp:shortcode -->[bp_products limit=\"9\" columns=\"3\"]\n<!-- /wp:shortcode -->",
		),
		'for-contractors' => array(
			'title'   => __( 'For Contractors', 'brickpoint' ),
			'content' => "<!-- wp:paragraph -->\n<p>" . __( 'Bulk material supply with dependable availability, project-based quotations and delivery coordination that fits your site schedule.', 'brickpoint' ) . "</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:list -->\n<ul><li>" . __( 'Bulk bricks and construction material supply', 'brickpoint' ) . "</li><li>" . __( 'Project-based quotations', 'brickpoint' ) . "</li><li>" . __( 'Delivery coordination with your site team', 'brickpoint' ) . "</li><li>" . __( 'Multiple product categories on one inquiry', 'brickpoint' ) . "</li></ul>\n<!-- /wp:list -->\n\n<!-- wp:shortcode -->[bp_contact_form title=\"" . __( 'Contractor inquiry', 'brickpoint' ) . "\" context=\"" . __( 'For Contractors page', 'brickpoint' ) . "\"]\n<!-- /wp:shortcode -->",
		),
		'for-builders' => array(
			'title'   => __( 'For Builders', 'brickpoint' ),
			'content' => "<!-- wp:paragraph -->\n<p>" . __( 'Material sourcing support for builders who need consistent quality across multiple stages of a build - from grey structure to finishing.', 'brickpoint' ) . "</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:list -->\n<ul><li>" . __( 'Construction material sourcing', 'brickpoint' ) . "</li><li>" . __( 'Consistent quality across consignments', 'brickpoint' ) . "</li><li>" . __( 'Bulk requirements handled on request', 'brickpoint' ) . "</li><li>" . __( 'Project planning support for material scheduling', 'brickpoint' ) . "</li></ul>\n<!-- /wp:list -->\n\n<!-- wp:shortcode -->[bp_contact_form title=\"" . __( 'Builder inquiry', 'brickpoint' ) . "\" context=\"" . __( 'For Builders page', 'brickpoint' ) . "\"]\n<!-- /wp:shortcode -->",
		),
		'for-construction-companies' => array(
			'title'   => __( 'For Construction Companies', 'brickpoint' ),
			'content' => "<!-- wp:paragraph -->\n<p>" . __( 'Coordinate materials across multiple sites with one point of contact. Share your schedule and we will work through the material list with you.', 'brickpoint' ) . "</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:list -->\n<ul><li>" . __( 'Large-scale supply coordination', 'brickpoint' ) . "</li><li>" . __( 'Material documentation on request', 'brickpoint' ) . "</li><li>" . __( 'Dedicated sales contact (Sales Manager: Qasim Iqbal)', 'brickpoint' ) . "</li><li>" . __( 'Corporate inquiry workflow', 'brickpoint' ) . "</li></ul>\n<!-- /wp:list -->\n\n<!-- wp:shortcode -->[bp_contact_form title=\"" . __( 'Corporate inquiry', 'brickpoint' ) . "\" context=\"" . __( 'For Construction Companies page', 'brickpoint' ) . "\"]\n<!-- /wp:shortcode -->",
		),
		'projects' => array(
			'title'   => __( 'Projects', 'brickpoint' ),
			'content' => "<!-- wp:paragraph -->\n<p>" . __( 'Material supply for homes, commercial sites and infrastructure work. Project entries are added in WordPress → Projects and shown here automatically.', 'brickpoint' ) . "</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>" . __( 'Every project visual is marked “Illustrative construction reference” unless you confirm the project details yourself.', 'brickpoint' ) . "</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:shortcode -->[bp_projects limit=\"12\" columns=\"3\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:shortcode -->[bp_whatsapp label=\"" . __( 'Discuss your project', 'brickpoint' ) . "\" message=\"" . __( 'Assalam-o-Alaikum BrickPoint, I would like to discuss material supply for a project.', 'brickpoint' ) . "\"]\n<!-- /wp:shortcode -->",
		),
		'videos' => array(
			'title'   => __( 'Videos', 'brickpoint' ),
			'content' => "<!-- wp:paragraph -->\n<p>" . __( 'Walkthroughs of bricks, stock and loading. Videos are added in WordPress → Videos — upload an MP4 or paste a YouTube / Vimeo link.', 'brickpoint' ) . "</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:shortcode -->[bp_videos limit=\"12\" columns=\"3\" filters=\"yes\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:shortcode -->[bp_whatsapp label=\"" . __( 'Ask about a material', 'brickpoint' ) . "\" message=\"" . __( 'Assalam-o-Alaikum BrickPoint, I have a question about a material shown in your videos.', 'brickpoint' ) . "\"]\n<!-- /wp:shortcode -->",
		),
		'locations' => array(
			'title'   => __( 'Locations', 'brickpoint' ),
			'content' => "<!-- wp:paragraph -->\n<p>" . __( 'Our production sites and sales office, each with its own Google Maps link. Call or WhatsApp before visiting to confirm loading times.', 'brickpoint' ) . "</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:shortcode -->[bp_locations columns=\"2\" video=\"no\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:heading -->\n<h2>" . __( 'Visit or call', 'brickpoint' ) . "</h2>\n<!-- /wp:heading -->\n\n<!-- wp:shortcode -->[bp_contact_form title=\"" . __( 'Contact our office', 'brickpoint' ) . "\" context=\"" . __( 'Locations page', 'brickpoint' ) . "\"]\n<!-- /wp:shortcode -->",
		),
		'contact' => array(
			'title'   => __( 'Contact', 'brickpoint' ),
			'content' => "<!-- wp:paragraph -->\n<p>" . __( 'Send us your material list, quantity and delivery location - our team will respond with availability and a quotation. For the fastest response, message us on WhatsApp.', 'brickpoint' ) . "</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:shortcode -->[bp_contact_form title=\"" . __( 'Request a quotation', 'brickpoint' ) . "\" context=\"" . __( 'Contact page', 'brickpoint' ) . "\"]\n<!-- /wp:shortcode -->\n\n<!-- wp:heading -->\n<h2>" . __( 'Our locations', 'brickpoint' ) . "</h2>\n<!-- /wp:heading -->\n\n<!-- wp:shortcode -->[bp_locations columns=\"2\" video=\"no\"]\n<!-- /wp:shortcode -->",
		),
		'privacy-policy' => array(
			'title'   => __( 'Privacy Policy', 'brickpoint' ),
			'content' => "<!-- wp:paragraph -->\n<p>" . __( 'This website collects the information you voluntarily submit through the contact and quotation form (name, phone, email, company, required material, quantity, delivery location and message). That information is used only to respond to your inquiry and to coordinate supply.', 'brickpoint' ) . "</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>" . __( 'Messages sent through WhatsApp links are handled by WhatsApp under its own privacy terms. This site does not sell or share your details with third parties, and does not process online payments.', 'brickpoint' ) . "</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>" . __( 'To request correction or deletion of your inquiry data, contact us using the details on the Contact page. Replace this text with guidance reviewed for your business.', 'brickpoint' ) . "</p>\n<!-- /wp:paragraph -->",
		),
		'terms-and-conditions' => array(
			'title'   => __( 'Terms and Conditions', 'brickpoint' ),
			'content' => "<!-- wp:paragraph -->\n<p>" . __( 'Prices, availability, quantities and delivery schedules shared through this website are indicative and confirmed in writing or over WhatsApp at the time of order. Product images are representative of the material category.', 'brickpoint' ) . "</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>" . __( 'No online payment is processed on this website. Project visuals marked “illustrative construction reference” are for context only and are not a claim of supply to any named project, developer or housing society unless explicitly stated.', 'brickpoint' ) . "</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>" . __( 'Replace this placeholder text with terms reviewed for your business.', 'brickpoint' ) . "</p>\n<!-- /wp:paragraph -->",
		),
	);

	/**
	 * Filter the pages created on activation.
	 *
	 * @param array $pages Page definitions.
	 */
	return apply_filters( 'brickpoint_seed_pages', $pages );
}

/**
 * Create the required pages, front page and posts page.
 *
 * @return array<string,int> Slug => page ID.
 */
function brickpoint_create_required_pages() {
	$created = array();

	foreach ( brickpoint_page_seed_data() as $slug => $page ) {
		$existing = get_page_by_path( $slug );

		if ( $existing instanceof WP_Post ) {
			/*
			 * WordPress creates an empty "Privacy Policy" page as a draft, and a
			 * leftover draft can also block a required page (the slug is taken
			 * but the page is not visible, so /privacy-policy/ returns 404).
			 * An empty draft of a required page is published; a draft with real
			 * content is left exactly as the owner left it.
			 */
			if ( 'draft' === $existing->post_status ) {
				$body      = (string) $existing->post_content;
				$is_empty  = ( '' === trim( $body ) );
				// WordPress ships an untouched sample privacy policy full of
				// "Suggested text:" blocks. It is recognised by this marker.
				$is_sample = ( false !== strpos( $body, 'privacy-policy-tutorial' ) );

				if ( $is_empty || $is_sample ) {
					$update = array(
						'ID'          => $existing->ID,
						'post_status' => 'publish',
					);

					if ( ! empty( $page['content'] ) ) {
						// Replace the empty/placeholder text with the theme's
						// starter copy (editable like any other page).
						$update['post_content'] = $page['content'];
					}

					wp_update_post( $update );
				}
			}

			$created[ $slug ] = $existing->ID;
			continue;
		}

		$page_id = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => $page['title'],
				'post_name'    => $slug,
				'post_content' => isset( $page['content'] ) ? $page['content'] : '',
			)
		);

		if ( is_wp_error( $page_id ) || ! $page_id ) {
			continue;
		}

		$created[ $slug ] = $page_id;
	}

	// Front page + blog page (only set if the site is still using the default view).
	$home_id = isset( $created['home'] ) ? $created['home'] : 0;
	$blog_id = isset( $created['blog'] ) ? $created['blog'] : 0;

	if ( ! $blog_id ) {
		$blog = get_page_by_path( 'blog' );

		if ( ! $blog instanceof WP_Post ) {
			$blog_id = wp_insert_post(
				array(
					'post_type'   => 'page',
					'post_status' => 'publish',
					'post_title'  => __( 'Blog', 'brickpoint' ),
					'post_name'   => 'blog',
				)
			);
		} else {
			$blog_id = $blog->ID;
		}
	}

	if ( $home_id ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home_id );
	}

	if ( $blog_id && ! is_wp_error( $blog_id ) ) {
		update_option( 'page_for_posts', (int) $blog_id );
	}

	// Privacy policy page hookup.
	if ( isset( $created['privacy-policy'] ) ) {
		if ( ! get_option( 'wp_page_for_privacy_policy' ) ) {
			update_option( 'wp_page_for_privacy_policy', (int) $created['privacy-policy'] );
		}
	}

	return $created;
}

/**
 * Create the BrickPoint locations (real companies, real Maps links).
 *
 * @return int Number of locations created.
 */
function brickpoint_seed_locations() {
	$count = 0;

	foreach ( brickpoint_default_locations() as $location ) {
		if ( brickpoint_post_id_by_title( $location['title'], 'bp_location' ) ) {
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_type'    => 'bp_location',
				'post_status'  => 'publish',
				'post_title'   => $location['title'],
				'post_excerpt' => $location['excerpt'],
				'post_content' => '',
				'menu_order'   => (int) $location['order'],
			)
		);

		if ( is_wp_error( $post_id ) || ! $post_id ) {
			continue;
		}

		update_post_meta( $post_id, '_bp_location_company', $location['company'] );
		update_post_meta( $post_id, '_bp_location_address', $location['address'] );
		update_post_meta( $post_id, '_bp_location_map', $location['map'] );
		update_post_meta( $post_id, '_bp_location_order', (int) $location['order'] );

		if ( ! empty( $location['coords'] ) ) {
			update_post_meta( $post_id, '_bp_location_coords', $location['coords'] );
		}

		$count++;
	}

	return $count;
}

/**
 * Create and assign the five navigation menus.
 *
 * Only empty menu locations are filled, so a menu the owner has already
 * assigned is never replaced. Items that point to pages which do not exist
 * yet are skipped.
 *
 * @return int Number of menus created.
 */
function brickpoint_seed_menus() {
	$menus = array(
		'primary'         => array(
			'name'  => __( 'BrickPoint Primary', 'brickpoint' ),
			'items' => array(
				__( 'Home', 'brickpoint' )                   => home_url( '/' ),
				__( 'Products', 'brickpoint' )               => brickpoint_page_url( 'products' ),
				__( 'SS7 Bricks', 'brickpoint' )             => brickpoint_page_url( 'ss7-bricks' ),
				__( 'Construction Materials', 'brickpoint' ) => brickpoint_page_url( 'construction-materials' ),
				__( 'Videos', 'brickpoint' )                 => brickpoint_page_or_archive_url( 'videos', 'bp_video' ),
				__( 'Projects', 'brickpoint' )               => brickpoint_page_or_archive_url( 'projects', 'bp_project' ),
				__( 'Locations', 'brickpoint' )              => brickpoint_page_or_archive_url( 'locations', 'bp_location' ),
				__( 'About Us', 'brickpoint' )               => brickpoint_page_url( 'about-us' ),
				__( 'Contact', 'brickpoint' )                => brickpoint_page_url( 'contact' ),
			),
		),
		'topbar'          => array(
			'name'  => __( 'BrickPoint Top Bar', 'brickpoint' ),
			'items' => array(
				__( 'For Contractors', 'brickpoint' ) => brickpoint_page_url( 'for-contractors' ),
				__( 'For Builders', 'brickpoint' )    => brickpoint_page_url( 'for-builders' ),
				__( 'Blog', 'brickpoint' )            => brickpoint_page_url( 'blog' ),
			),
		),
		'footer_products' => array(
			'name'  => __( 'BrickPoint Footer – Construction Materials', 'brickpoint' ),
			'items' => array(
				__( 'Products', 'brickpoint' )               => brickpoint_page_url( 'products' ),
				__( 'SS7 Bricks', 'brickpoint' )             => brickpoint_page_url( 'ss7-bricks' ),
				__( 'Construction Materials', 'brickpoint' ) => brickpoint_page_url( 'construction-materials' ),
				__( 'Videos', 'brickpoint' )                 => brickpoint_page_or_archive_url( 'videos', 'bp_video' ),
			),
		),
		'footer_company'  => array(
			'name'  => __( 'BrickPoint Footer - Company', 'brickpoint' ),
			'items' => array(
				__( 'About Us', 'brickpoint' )  => brickpoint_page_url( 'about-us' ),
				__( 'Projects', 'brickpoint' )  => brickpoint_page_or_archive_url( 'projects', 'bp_project' ),
				__( 'Locations', 'brickpoint' ) => brickpoint_page_or_archive_url( 'locations', 'bp_location' ),
				__( 'Blog', 'brickpoint' )      => brickpoint_page_url( 'blog' ),
				__( 'Contact', 'brickpoint' )   => brickpoint_page_url( 'contact' ),
			),
		),
		'footer_support'  => array(
			'name'  => __( 'BrickPoint Footer - Support', 'brickpoint' ),
			'items' => array(
				__( 'For Contractors', 'brickpoint' )            => brickpoint_page_url( 'for-contractors' ),
				__( 'For Builders', 'brickpoint' )               => brickpoint_page_url( 'for-builders' ),
				__( 'For Construction Companies', 'brickpoint' ) => brickpoint_page_url( 'for-construction-companies' ),
				__( 'Privacy Policy', 'brickpoint' )             => brickpoint_page_url( 'privacy-policy' ),
				__( 'Terms and Conditions', 'brickpoint' )       => brickpoint_page_url( 'terms-and-conditions' ),
			),
		),
	);

	$assigned = (array) get_theme_mod( 'nav_menu_locations', array() );
	$created  = 0;

	foreach ( $menus as $location => $data ) {
		if ( ! empty( $assigned[ $location ] ) && wp_get_nav_menu_object( (int) $assigned[ $location ] ) ) {
			continue; // The owner already assigned a menu here.
		}

		$menu    = wp_get_nav_menu_object( $data['name'] );
		$menu_id = $menu ? (int) $menu->term_id : wp_create_nav_menu( $data['name'] );

		if ( is_wp_error( $menu_id ) ) {
			continue;
		}

		$menu_id = (int) $menu_id;

		if ( ! $menu ) {
			$created++;
		}

		$existing = array();

		foreach ( (array) wp_get_nav_menu_items( $menu_id ) as $item ) {
			$existing[] = untrailingslashit( (string) $item->url );
		}

		foreach ( $data['items'] as $label => $url ) {
			if ( ! $url || in_array( untrailingslashit( (string) $url ), $existing, true ) ) {
				continue;
			}

			wp_update_nav_menu_item(
				$menu_id,
				0,
				array(
					'menu-item-title'  => $label,
					'menu-item-url'    => $url,
					'menu-item-status' => 'publish',
				)
			);
		}

		$assigned[ $location ] = $menu_id;
	}

	set_theme_mod( 'nav_menu_locations', $assigned );

	return $created;
}

/**
 * Optional starter content: a few clearly-labelled example products and videos
 * the owner can edit or delete. Never auto-published without a click.
 *
 * @return array<string,int>
 */
function brickpoint_seed_starter_content() {
	$products = array(
		array(
			'title'   => __( 'SS7 Bricks', 'brickpoint' ),
			'cat'     => 'ss7-bricks',
			'excerpt' => __( 'Premium machine-moulded bricks with uniform size and clean edges, produced at our own bhattas.', 'brickpoint' ),
			'specs'   => array(
				array( 'label' => __( 'Type', 'brickpoint' ), 'value' => __( 'Clay brick', 'brickpoint' ) ),
				array( 'label' => __( 'Usage', 'brickpoint' ), 'value' => __( 'Walls, grey structure, boundary walls', 'brickpoint' ) ),
			),
			'featured' => 1,
		),
		array(
			'title'   => __( 'Clay Bricks (Standard)', 'brickpoint' ),
			'cat'     => 'bricks',
			'excerpt' => __( 'Standard clay bricks for general construction work, supplied in bulk with loading support.', 'brickpoint' ),
			'specs'   => array(),
			'featured' => 0,
		),
		array(
			'title'   => __( 'Cement', 'brickpoint' ),
			'cat'     => 'cement',
			'excerpt' => __( 'Branded cement bags supplied for grey structure and finishing work. Confirm current brand and rate on inquiry.', 'brickpoint' ),
			'specs'   => array(),
			'featured' => 0,
		),
		array(
			'title'   => __( 'Bajri / Crush', 'brickpoint' ),
			'cat'     => 'bajri-crush',
			'excerpt' => __( 'Crushed aggregate for concrete and base layers, delivered by truck to your site.', 'brickpoint' ),
			'specs'   => array(),
			'featured' => 0,
		),
		array(
			'title'   => __( 'Sand / Rait', 'brickpoint' ),
			'cat'     => 'sand-rait',
			'excerpt' => __( 'Sand for plastering, masonry and concrete mixes, available in bulk loads.', 'brickpoint' ),
			'specs'   => array(),
			'featured' => 0,
		),
		array(
			'title'   => __( 'Steel (Rebar)', 'brickpoint' ),
			'cat'     => 'steel',
			'excerpt' => __( 'Deformed steel bars supplied against your requirement list. Sizes and rates are confirmed on inquiry.', 'brickpoint' ),
			'specs'   => array(),
			'featured' => 0,
		),
	);

	$created = array(
		'products' => 0,
		'videos'   => 0,
	);

	foreach ( $products as $product ) {
		if ( brickpoint_post_id_by_title( $product['title'], 'bp_product' ) ) {
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_type'    => 'bp_product',
				'post_status'  => 'draft',
				'post_title'   => $product['title'],
				'post_excerpt' => $product['excerpt'],
				'post_content' => $product['excerpt'],
			)
		);

		if ( is_wp_error( $post_id ) || ! $post_id ) {
			continue;
		}

		wp_set_object_terms( $post_id, $product['cat'], 'bp_product_category' );

		update_post_meta( $post_id, '_bp_availability', 'on-request' );
		update_post_meta( $post_id, '_bp_unit', __( 'Confirm on inquiry', 'brickpoint' ) );

		if ( $product['specs'] ) {
			update_post_meta( $post_id, '_bp_specs', $product['specs'] );
		}

		if ( $product['featured'] ) {
			update_post_meta( $post_id, '_bp_featured', '1' );
		}

		$created['products']++;
	}

	$videos = array(
		array(
			'title' => __( 'SS7 Bricks – product overview (add your video)', 'brickpoint' ),
			'cat'   => 'ss7-bricks',
		),
		array(
			'title' => __( 'Brick manufacturing process (add your video)', 'brickpoint' ),
			'cat'   => 'brick-manufacturing',
		),
		array(
			'title' => __( 'Inside our bhatta (add your video)', 'brickpoint' ),
			'cat'   => 'our-bhattas',
		),
	);

	foreach ( $videos as $video ) {
		if ( brickpoint_post_id_by_title( $video['title'], 'bp_video' ) ) {
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_type'   => 'bp_video',
				'post_status' => 'draft',
				'post_title'  => $video['title'],
			)
		);

		if ( is_wp_error( $post_id ) || ! $post_id ) {
			continue;
		}

		wp_set_object_terms( $post_id, $video['cat'], 'bp_video_category' );
		update_post_meta( $post_id, '_bp_video_source', 'youtube' );

		$created['videos']++;
	}

	return $created;
}

/**
 * Register the BrickPoint admin menu.
 *
 * @return void
 */
function brickpoint_admin_menu() {
	add_menu_page(
		__( 'BrickPoint', 'brickpoint' ),
		__( 'BrickPoint', 'brickpoint' ),
		'edit_theme_options',
		'brickpoint',
		'brickpoint_admin_dashboard',
		'dashicons-screenoptions',
		59
	);

	add_submenu_page(
		'brickpoint',
		__( 'Dashboard', 'brickpoint' ),
		__( 'Dashboard', 'brickpoint' ),
		'edit_theme_options',
		'brickpoint',
		'brickpoint_admin_dashboard'
	);

	add_submenu_page(
		'brickpoint',
		__( 'Setup & Content', 'brickpoint' ),
		__( 'Setup & Content', 'brickpoint' ),
		'edit_theme_options',
		'brickpoint-setup',
		'brickpoint_admin_setup'
	);

	add_submenu_page(
		'brickpoint',
		__( 'Theme Settings', 'brickpoint' ),
		__( 'Theme Settings', 'brickpoint' ),
		'edit_theme_options',
		'brickpoint-settings',
		'brickpoint_admin_settings'
	);

	add_submenu_page(
		'brickpoint',
		__( 'Help & Docs', 'brickpoint' ),
		__( 'Help & Docs', 'brickpoint' ),
		'edit_theme_options',
		'brickpoint-docs',
		'brickpoint_admin_docs'
	);

	add_submenu_page(
		'brickpoint',
		__( 'Elementor Templates', 'brickpoint' ),
		__( 'Elementor Templates', 'brickpoint' ),
		'edit_theme_options',
		'brickpoint-templates',
		'brickpoint_admin_templates'
	);
}
add_action( 'admin_menu', 'brickpoint_admin_menu' );

/**
 * Admin styles.
 *
 * @param string $hook Hook.
 * @return void
 */
function brickpoint_admin_styles( $hook ) {
	$is_bp = ( false !== strpos( (string) $hook, 'brickpoint' ) );

	if ( ! $is_bp ) {
		return;
	}

	wp_enqueue_style( 'brickpoint-admin-css', BRICKPOINT_URI . 'assets/css/admin.css', array(), BRICKPOINT_VERSION );
}
add_action( 'admin_enqueue_scripts', 'brickpoint_admin_styles' );

/**
 * Dashboard screen.
 *
 * @return void
 */
function brickpoint_admin_dashboard() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to view this page.', 'brickpoint' ) );
	}

	$counts = array(
		'bp_product'  => (int) wp_count_posts( 'bp_product' )->publish,
		'bp_video'    => (int) wp_count_posts( 'bp_video' )->publish,
		'bp_project'  => (int) wp_count_posts( 'bp_project' )->publish,
		'bp_location' => (int) wp_count_posts( 'bp_location' )->publish,
	);

	$inquiry_counts = wp_count_posts( 'bp_inquiry' );
	$new_inquiries  = isset( $inquiry_counts->private ) ? (int) $inquiry_counts->private : 0;

	?>
	<div class="wrap bp-admin">
		<h1><?php esc_html_e( 'BrickPoint Dashboard', 'brickpoint' ); ?></h1>
		<p class="bp-admin__lead"><?php esc_html_e( 'Products, videos, projects, locations and inquiries - all editable without touching code.', 'brickpoint' ); ?></p>

		<div class="bp-admin__grid">
			<a class="bp-admin__card" href="<?php echo esc_url( admin_url( 'edit.php?post_type=bp_product' ) ); ?>">
				<span class="bp-admin__card-value"><?php echo esc_html( number_format_i18n( $counts['bp_product'] ) ); ?></span>
				<span class="bp-admin__card-label"><?php esc_html_e( 'Published products', 'brickpoint' ); ?></span>
				<span class="bp-admin__card-action"><?php esc_html_e( 'Manage products →', 'brickpoint' ); ?></span>
			</a>

			<a class="bp-admin__card" href="<?php echo esc_url( admin_url( 'edit.php?post_type=bp_video' ) ); ?>">
				<span class="bp-admin__card-value"><?php echo esc_html( number_format_i18n( $counts['bp_video'] ) ); ?></span>
				<span class="bp-admin__card-label"><?php esc_html_e( 'Published videos', 'brickpoint' ); ?></span>
				<span class="bp-admin__card-action"><?php esc_html_e( 'Manage videos →', 'brickpoint' ); ?></span>
			</a>

			<a class="bp-admin__card" href="<?php echo esc_url( admin_url( 'edit.php?post_type=bp_project' ) ); ?>">
				<span class="bp-admin__card-value"><?php echo esc_html( number_format_i18n( $counts['bp_project'] ) ); ?></span>
				<span class="bp-admin__card-label"><?php esc_html_e( 'Projects', 'brickpoint' ); ?></span>
				<span class="bp-admin__card-action"><?php esc_html_e( 'Manage projects →', 'brickpoint' ); ?></span>
			</a>

			<a class="bp-admin__card" href="<?php echo esc_url( admin_url( 'edit.php?post_type=bp_location' ) ); ?>">
				<span class="bp-admin__card-value"><?php echo esc_html( number_format_i18n( $counts['bp_location'] ) ); ?></span>
				<span class="bp-admin__card-label"><?php esc_html_e( 'Locations', 'brickpoint' ); ?></span>
				<span class="bp-admin__card-action"><?php esc_html_e( 'Manage locations →', 'brickpoint' ); ?></span>
			</a>

			<a class="bp-admin__card" href="<?php echo esc_url( admin_url( 'edit.php?post_type=bp_inquiry' ) ); ?>">
				<span class="bp-admin__card-value"><?php echo esc_html( number_format_i18n( $new_inquiries ) ); ?></span>
				<span class="bp-admin__card-label"><?php esc_html_e( 'Inquiries received', 'brickpoint' ); ?></span>
				<span class="bp-admin__card-action"><?php esc_html_e( 'Read inquiries →', 'brickpoint' ); ?></span>
			</a>

			<a class="bp-admin__card" href="<?php echo esc_url( admin_url( 'edit.php' ) ); ?>">
				<span class="bp-admin__card-value"><?php echo esc_html( number_format_i18n( (int) wp_count_posts( 'post' )->publish ) ); ?></span>
				<span class="bp-admin__card-label"><?php esc_html_e( 'Blog posts', 'brickpoint' ); ?></span>
				<span class="bp-admin__card-action"><?php esc_html_e( 'Manage blog →', 'brickpoint' ); ?></span>
			</a>

			<a class="bp-admin__card" href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>">
				<span class="bp-admin__card-value"><?php echo brickpoint_icon( 'menu', array( 'size' => 30 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<span class="bp-admin__card-label"><?php esc_html_e( 'Menus', 'brickpoint' ); ?></span>
				<span class="bp-admin__card-action"><?php esc_html_e( 'Edit navigation →', 'brickpoint' ); ?></span>
			</a>
		</div>

		<h2><?php esc_html_e( 'Add new content (add, edit and delete anything)', 'brickpoint' ); ?></h2>
		<div class="bp-admin__grid bp-admin__grid--links">
			<a class="bp-admin__card bp-admin__card--small" href="<?php echo esc_url( admin_url( 'post-new.php?post_type=bp_product' ) ); ?>">
				<span class="bp-admin__card-label"><?php esc_html_e( '+ Add product', 'brickpoint' ); ?></span>
				<span class="bp-admin__card-action"><?php esc_html_e( 'Text, price, photos, specs, WhatsApp message', 'brickpoint' ); ?></span>
			</a>
			<a class="bp-admin__card bp-admin__card--small" href="<?php echo esc_url( admin_url( 'post-new.php?post_type=bp_video' ) ); ?>">
				<span class="bp-admin__card-label"><?php esc_html_e( '+ Add video', 'brickpoint' ); ?></span>
				<span class="bp-admin__card-action"><?php esc_html_e( 'Upload MP4 or paste a YouTube / Vimeo link', 'brickpoint' ); ?></span>
			</a>
			<a class="bp-admin__card bp-admin__card--small" href="<?php echo esc_url( admin_url( 'post-new.php?post_type=bp_project' ) ); ?>">
				<span class="bp-admin__card-label"><?php esc_html_e( '+ Add project', 'brickpoint' ); ?></span>
				<span class="bp-admin__card-action"><?php esc_html_e( 'Photos, scope, location, supply notes', 'brickpoint' ); ?></span>
			</a>
			<a class="bp-admin__card bp-admin__card--small" href="<?php echo esc_url( admin_url( 'post-new.php?post_type=bp_location' ) ); ?>">
				<span class="bp-admin__card-label"><?php esc_html_e( '+ Add location', 'brickpoint' ); ?></span>
				<span class="bp-admin__card-action"><?php esc_html_e( 'Address, Google Maps link, timings', 'brickpoint' ); ?></span>
			</a>
			<a class="bp-admin__card bp-admin__card--small" href="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>">
				<span class="bp-admin__card-label"><?php esc_html_e( '+ Add blog post', 'brickpoint' ); ?></span>
				<span class="bp-admin__card-action"><?php esc_html_e( 'Article with featured image and category', 'brickpoint' ); ?></span>
			</a>
			<a class="bp-admin__card bp-admin__card--small" href="<?php echo esc_url( admin_url( 'term.php?taxonomy=bp_product_category&post_type=bp_product' ) ); ?>">
				<span class="bp-admin__card-label"><?php esc_html_e( '+ Product categories', 'brickpoint' ); ?></span>
				<span class="bp-admin__card-action"><?php esc_html_e( 'Name, image, icon, WhatsApp text', 'brickpoint' ); ?></span>
			</a>
		</div>

		<h2><?php esc_html_e( 'Quick links', 'brickpoint' ); ?></h2>
		<ul class="bp-admin__links">
			<li><a href="<?php echo esc_url( admin_url( 'admin.php?page=brickpoint-setup' ) ); ?>"><?php esc_html_e( 'Run first-time setup (pages, categories, locations)', 'brickpoint' ); ?></a></li>
			<li><a href="<?php echo esc_url( admin_url( 'admin.php?page=brickpoint-settings' ) ); ?>"><?php esc_html_e( 'Theme settings (phone, WhatsApp, social links, colors)', 'brickpoint' ); ?></a></li>
			<li><a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[panel]=brickpoint_panel' ) ); ?>"><?php esc_html_e( 'Open the Customizer (hero video, design tokens)', 'brickpoint' ); ?></a></li>
			<li><a href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=bp_product_category&post_type=bp_product' ) ); ?>"><?php esc_html_e( 'Product categories (images, icons, WhatsApp text)', 'brickpoint' ); ?></a></li>
			<li><a href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=bp_video_category&post_type=bp_video' ) ); ?>"><?php esc_html_e( 'Video categories', 'brickpoint' ); ?></a></li>
			<li><a href="<?php echo esc_url( admin_url( 'admin.php?page=brickpoint-docs' ) ); ?>"><?php esc_html_e( 'Help, shortcodes and Elementor guide', 'brickpoint' ); ?></a></li>
			<li><a href="<?php echo esc_url( admin_url( 'admin.php?page=brickpoint-setup' ) ); ?>"><?php esc_html_e( 'Demo content: import again or remove it', 'brickpoint' ); ?></a></li>
			<li><a href="<?php echo esc_url( admin_url( 'admin.php?page=brickpoint-templates' ) ); ?>"><?php esc_html_e( 'Elementor templates: what is editable where', 'brickpoint' ); ?></a></li>
		</ul>

		<?php if ( function_exists( 'brickpoint_demo_counts' ) ) : ?>
			<?php $bp_demo = brickpoint_demo_counts(); ?>
			<?php if ( array_sum( $bp_demo ) ) : ?>
				<h2><?php esc_html_e( 'Demo content is installed', 'brickpoint' ); ?></h2>
				<div class="bp-admin__notice">
					<p>
						<?php
						printf(
							/* translators: 1: products, 2: videos, 3: projects, 4: blog posts, 5: media files. */
							esc_html__( 'Products: %1$d · Videos: %2$d · Projects: %3$d · Blog posts: %4$d · Photos and clips: %5$d. Every demo item carries a “Demo” badge in the list screens below, so you always know what to replace. Demo photos and clips are generic material pictures — swap them for your own site photography.', 'brickpoint' ),
							(int) $bp_demo['products'],
							(int) $bp_demo['videos'],
							(int) $bp_demo['projects'],
							(int) $bp_demo['posts'],
							(int) $bp_demo['media']
						);
						?>
					</p>
					<p><?php esc_html_e( 'When your own content is ready, open Setup & Content → step 7 and press “Remove demo content”. Your pages, menus and settings stay.', 'brickpoint' ); ?></p>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<h2><?php esc_html_e( 'Content rules reminder', 'brickpoint' ); ?></h2>
		<div class="bp-admin__notice">
			<p><?php esc_html_e( 'BrickPoint publishes only verified information. Do not add certifications, awards, client counts or supply claims for named housing societies or developers unless you can confirm them. Mark concept visuals as “Illustrative construction reference”.', 'brickpoint' ); ?></p>
		</div>
	</div>
	<?php
}

/**
 * Setup screen with one-click actions.
 *
 * @return void
 */
function brickpoint_admin_setup() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to view this page.', 'brickpoint' ) );
	}

	$action = isset( $_POST['bp_setup_action'] ) ? sanitize_key( wp_unslash( $_POST['bp_setup_action'] ) ) : '';
	$notice = '';

	if ( $action ) {
		check_admin_referer( 'brickpoint_setup', 'brickpoint_setup_nonce' );

		switch ( $action ) {
			case 'pages':
				$created = brickpoint_create_required_pages();
				$notice  = sprintf(
					/* translators: %d: number of pages. */
					esc_html__( 'Required pages checked/created: %d.', 'brickpoint' ),
					count( $created )
				);
				break;

			case 'terms':
				$created = brickpoint_seed_taxonomy_terms( true );
				$notice  = esc_html__( 'Product, video and project categories created (existing ones left untouched).', 'brickpoint' );
				unset( $created );
				break;

			case 'locations':
				$count  = brickpoint_seed_locations();
				$notice = sprintf(
					/* translators: %d: number of locations. */
					esc_html__( 'Locations created: %d.', 'brickpoint' ),
					$count
				);
				break;

			case 'menus':
				$count  = brickpoint_seed_menus();
				$notice = sprintf(
					/* translators: %d: number of menus. */
					esc_html__( 'Navigation menus created/assigned: %d (Primary, Top Bar and three footer columns). Edit them under Appearance → Menus.', 'brickpoint' ),
					$count
				);
				break;

			case 'starter':
				$created = brickpoint_seed_starter_content();
				$notice  = sprintf(
					/* translators: 1: product count, 2: video count. */
					esc_html__( 'Starter drafts created: %1$d products, %2$d videos. They are saved as drafts - edit and publish what you want.', 'brickpoint' ),
					(int) $created['products'],
					(int) $created['videos']
				);
				break;

			case 'permalinks':
				flush_rewrite_rules();
				$notice = esc_html__( 'Permalinks flushed. Product, video and location archives should now resolve correctly.', 'brickpoint' );
				break;

			case 'demo_import':
				$report = brickpoint_import_demo_content( array( 'time_budget' => 60, 'force' => true ) );
				$notice = sprintf(
					/* translators: 1: products, 2: videos, 3: projects, 4: posts, 5: page designs, 6: media files. */
					esc_html__( 'Demo content ready — products: %1$d, videos: %2$d, projects: %3$d, blog posts: %4$d, page designs built with Elementor: %5$d, media files: %6$d.', 'brickpoint' ),
					(int) $report['products'],
					(int) $report['videos'],
					(int) $report['projects'],
					(int) $report['posts'],
					(int) $report['layouts'],
					(int) $report['media']
				);

				if ( ! empty( $report['partial'] ) ) {
					$notice .= ' ' . esc_html__( 'The import reached the time limit and stopped early — press the button again to finish the remaining items.', 'brickpoint' );
				}
				break;

			case 'demo_remove':
				$removed = brickpoint_remove_demo_content();
				$notice  = sprintf(
					/* translators: 1: items, 2: media files, 3: page designs, 4: kept pages. */
					esc_html__( 'Demo content removed — items deleted: %1$d, media files deleted: %2$d, page designs cleared: %3$d. Pages you edited yourself were kept: %4$d.', 'brickpoint' ),
					(int) $removed['posts'],
					(int) $removed['media'],
					(int) $removed['layouts'],
					(int) $removed['kept']
				);
				break;
		}
	}

	?>
	<div class="wrap bp-admin">
		<h1><?php esc_html_e( 'Setup & Content', 'brickpoint' ); ?></h1>

		<?php if ( $notice ) : ?>
			<div class="notice notice-success is-dismissible"><p><?php echo esc_html( $notice ); ?></p></div>
		<?php endif; ?>

		<p class="bp-admin__lead"><?php esc_html_e( 'Run these once after activating the theme, then manage everything from the normal WordPress screens.', 'brickpoint' ); ?></p>

		<form method="post" class="bp-admin__actions">
			<?php wp_nonce_field( 'brickpoint_setup', 'brickpoint_setup_nonce' ); ?>

			<div class="bp-admin__action">
				<h3><?php esc_html_e( '1. Create the required pages', 'brickpoint' ); ?></h3>
				<p><?php esc_html_e( 'Home, About, Products, SS7 Bricks, Construction Materials (all categories in one place), For Contractors / Builders / Construction Companies, Blog, Contact, Privacy Policy and Terms & Conditions - with useful starter content and shortcodes.', 'brickpoint' ); ?></p>
				<button class="button button-primary" name="bp_setup_action" value="pages"><?php esc_html_e( 'Create pages', 'brickpoint' ); ?></button>
			</div>

			<div class="bp-admin__action">
				<h3><?php esc_html_e( '2. Create product, video & project categories', 'brickpoint' ); ?></h3>
				<p><?php esc_html_e( 'Bricks, SS7 Bricks, Cement, Bajri/Crush, Sand/Rait, Steel, pipes, chemicals, insulation, cables, paints, lights, switches, other materials plus the video categories.', 'brickpoint' ); ?></p>
				<button class="button button-primary" name="bp_setup_action" value="terms"><?php esc_html_e( 'Create categories', 'brickpoint' ); ?></button>
			</div>

			<div class="bp-admin__action">
				<h3><?php esc_html_e( '3. Create the BrickPoint locations', 'brickpoint' ); ?></h3>
				<p><?php esc_html_e( 'Masha Allah Bricks Company (Ram Thaman), Fine Bricks Company (Raja Jang), Masha Allah Bricks Company (Sattoki) and the BrickPoint office - each with the real Google Maps link you provided.', 'brickpoint' ); ?></p>
				<button class="button button-primary" name="bp_setup_action" value="locations"><?php esc_html_e( 'Create locations', 'brickpoint' ); ?></button>
			</div>

			<div class="bp-admin__action">
				<h3><?php esc_html_e( '4. Create the navigation menus', 'brickpoint' ); ?></h3>
				<p><?php esc_html_e( 'Builds the Primary, Top Bar and three footer menus with your pages already linked, then assigns them to the theme menu locations. Menus you have already assigned are left alone.', 'brickpoint' ); ?></p>
				<button class="button button-primary" name="bp_setup_action" value="menus"><?php esc_html_e( 'Create menus', 'brickpoint' ); ?></button>
			</div>

			<div class="bp-admin__action">
				<h3><?php esc_html_e( '5. Optional: starter drafts', 'brickpoint' ); ?></h3>
				<p><?php esc_html_e( 'Creates draft products and videos (SS7 Bricks, bricks, cement, crush, sand, steel) that you can edit and publish. Nothing goes live automatically.', 'brickpoint' ); ?></p>
				<button class="button" name="bp_setup_action" value="starter"><?php esc_html_e( 'Create starter drafts', 'brickpoint' ); ?></button>
			</div>

			<div class="bp-admin__action">
				<h3><?php esc_html_e( '6. Troubleshooting', 'brickpoint' ); ?></h3>
				<p><?php esc_html_e( 'If a product, video or location page shows a 404, flush permalinks.', 'brickpoint' ); ?></p>
				<button class="button" name="bp_setup_action" value="permalinks"><?php esc_html_e( 'Flush permalinks', 'brickpoint' ); ?></button>
			</div>

			<?php if ( function_exists( 'brickpoint_demo_counts' ) ) : ?>
				<?php $bp_demo = brickpoint_demo_counts(); ?>
				<?php $bp_demo_total = array_sum( $bp_demo ); ?>

				<div class="bp-admin__action bp-admin__action--wide">
					<h3><?php esc_html_e( '7. Demo content (products, videos, projects, blog, photos)', 'brickpoint' ); ?></h3>

					<p>
						<?php
						printf(
							/* translators: 1: products, 2: videos, 3: projects, 4: blog posts, 5: media files. */
							esc_html__( 'Currently on the site: %1$d demo products, %2$d demo videos, %3$d demo projects, %4$d demo blog posts, %5$d demo photos/clips. Everything is marked with a “Demo” badge in the WordPress lists — edit, replace or delete it at any time.', 'brickpoint' ),
							(int) $bp_demo['products'],
							(int) $bp_demo['videos'],
							(int) $bp_demo['projects'],
							(int) $bp_demo['posts'],
							(int) $bp_demo['media']
						);
						?>
					</p>

					<p><?php esc_html_e( 'Importing again is safe: only what is missing gets created, and a page you have already designed in Elementor is never overwritten.', 'brickpoint' ); ?></p>

					<button class="button button-primary" name="bp_setup_action" value="demo_import">
						<?php
						echo esc_html(
							$bp_demo_total
								? __( 'Re-check / finish demo import', 'brickpoint' )
								: __( 'Import demo content', 'brickpoint' )
						);
						?>
					</button>

					<?php if ( $bp_demo_total ) : ?>
						<button class="button" name="bp_setup_action" value="demo_remove" onclick="return confirm('<?php echo esc_js( __( 'Remove all demo items, demo photos and the demo page designs? Your own content and the pages themselves stay.', 'brickpoint' ) ); ?>');"><?php esc_html_e( 'Remove demo content', 'brickpoint' ); ?></button>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</form>
	</div>
	<?php
}

/**
 * Theme settings screen - writes to theme mods used by the Customizer,
 * so both screens stay in sync.
 *
 * @return void
 */
function brickpoint_admin_settings() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to view this page.', 'brickpoint' ) );
	}

	$fields = array(
		'brand'   => array(
			'title'  => __( 'Brand & contact', 'brickpoint' ),
			'fields' => array(
				'bp_brand_name' => array(
					'label'    => __( 'Brand name', 'brickpoint' ),
					'sanitize' => 'sanitize_text_field',
				),
				'bp_phone'      => array(
					'label'    => __( 'Phone (displayed)', 'brickpoint' ),
					'sanitize' => 'sanitize_text_field',
				),
				'bp_whatsapp'   => array(
					'label'    => __( 'WhatsApp number (digits only, e.g. 923152850818)', 'brickpoint' ),
					'sanitize' => 'sanitize_text_field',
				),
				'bp_email'      => array(
					'label'    => __( 'Email for inquiries', 'brickpoint' ),
					'sanitize' => 'sanitize_email',
					'type'     => 'email',
				),
				'bp_address'    => array(
					'label'    => __( 'Office address', 'brickpoint' ),
					'sanitize' => 'sanitize_textarea_field',
					'type'     => 'textarea',
				),
				'bp_hours'      => array(
					'label'    => __( 'Opening hours', 'brickpoint' ),
					'sanitize' => 'sanitize_text_field',
				),
			),
		),
		'social'  => array(
			'title'  => __( 'Social links', 'brickpoint' ),
			'fields' => array(),
		),
		'footer'  => array(
			'title'  => __( 'Footer', 'brickpoint' ),
			'fields' => array(
				'bp_footer_about'     => array(
					'label'    => __( 'Footer about text', 'brickpoint' ),
					'sanitize' => 'sanitize_textarea_field',
					'type'     => 'textarea',
				),
				'bp_footer_copyright' => array(
					'label'    => __( 'Copyright text ({year} = current year)', 'brickpoint' ),
					'sanitize' => 'sanitize_text_field',
				),
				'bp_footer_note'      => array(
					'label'    => __( 'Companies line', 'brickpoint' ),
					'sanitize' => 'sanitize_text_field',
				),
			),
		),
	);

	foreach ( brickpoint_social_networks() as $slug => $network ) {
		$fields['social']['fields'][ $network['key'] ] = array(
			'label'    => $network['label'],
			'sanitize' => 'esc_url_raw',
			'type'     => 'url',
		);
	}

	if ( isset( $_POST['bp_settings_nonce'] ) ) {
		check_admin_referer( 'brickpoint_settings', 'bp_settings_nonce' );

		foreach ( $fields as $group ) {
			foreach ( $group['fields'] as $key => $field ) {
				if ( ! isset( $_POST[ $key ] ) ) {
					continue;
				}

				$raw   = wp_unslash( $_POST[ $key ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitised on the next line.
				$value = call_user_func( $field['sanitize'], $raw );

				set_theme_mod( $key, $value );
			}
		}

		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Settings saved.', 'brickpoint' ) . '</p></div>';
	}

	?>
	<div class="wrap bp-admin">
		<h1><?php esc_html_e( 'Theme Settings', 'brickpoint' ); ?></h1>
		<p class="bp-admin__lead">
			<?php esc_html_e( 'These settings power every WhatsApp link, phone button and footer block across the site. Logo and favicon live in Appearance → Customize → Site Identity.', 'brickpoint' ); ?>
		</p>

		<form method="post">
			<?php wp_nonce_field( 'brickpoint_settings', 'bp_settings_nonce' ); ?>

			<?php foreach ( $fields as $group ) : ?>
				<h2><?php echo esc_html( $group['title'] ); ?></h2>
				<table class="form-table" role="presentation">
					<tbody>
					<?php foreach ( $group['fields'] as $key => $field ) : ?>
						<tr>
							<th scope="row"><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ); ?></label></th>
							<td>
								<?php if ( 'textarea' === ( isset( $field['type'] ) ? $field['type'] : '' ) ) : ?>
									<textarea id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" rows="3" class="large-text"><?php echo esc_textarea( (string) brickpoint_option( $key ) ); ?></textarea>
								<?php else : ?>
									<input type="<?php echo esc_attr( isset( $field['type'] ) ? $field['type'] : 'text' ); ?>" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( (string) brickpoint_option( $key ) ); ?>" class="regular-text" />
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			<?php endforeach; ?>

			<p>
				<button type="submit" class="button button-primary"><?php esc_html_e( 'Save settings', 'brickpoint' ); ?></button>
				<a class="button" href="<?php echo esc_url( admin_url( 'customize.php?autofocus[panel]=brickpoint_panel' ) ); ?>"><?php esc_html_e( 'Open full Customizer (hero video, colors, fonts)', 'brickpoint' ); ?></a>
			</p>
		</form>
	</div>
	<?php
}

/**
 * Help & docs screen.
 *
 * @return void
 */
function brickpoint_admin_docs() {
	?>
	<div class="wrap bp-admin bp-admin--docs">
		<h1><?php esc_html_e( 'Help & Docs', 'brickpoint' ); ?></h1>

		<div class="bp-admin__doc">
			<h2><?php esc_html_e( '1. First five minutes', 'brickpoint' ); ?></h2>
			<ol>
				<li><?php esc_html_e( 'Appearance → Customize → Site Identity: upload the BrickPoint logo and site icon (favicon).', 'brickpoint' ); ?></li>
				<li><?php esc_html_e( 'BrickPoint → Theme Settings: confirm phone, WhatsApp number, email, address and social links.', 'brickpoint' ); ?></li>
				<li><?php esc_html_e( 'BrickPoint → Setup & Content: run steps 1–3 (pages, categories, locations).', 'brickpoint' ); ?></li>
				<li><?php esc_html_e( 'Appearance → Menus: assign the Primary Menu (and the three footer menus if you want custom footer columns).', 'brickpoint' ); ?></li>
				<li><?php esc_html_e( 'Settings → Permalinks: click Save once so /products/, /videos/ and /locations/ resolve.', 'brickpoint' ); ?></li>
			</ol>

			<h2><?php esc_html_e( '2. Adding products', 'brickpoint' ); ?></h2>
			<ol>
				<li><?php esc_html_e( 'Products → Add Product. Title = product name.', 'brickpoint' ); ?></li>
				<li><?php esc_html_e( 'Featured image = main product image. Product Details box = price, price label, unit, availability, badge, SKU.', 'brickpoint' ); ?></li>
				<li><?php esc_html_e( 'Excerpt = short description (used on cards). Main editor = full description.', 'brickpoint' ); ?></li>
				<li><?php esc_html_e( 'Gallery, Video & Documents box = extra images, product video, brochure PDF.', 'brickpoint' ); ?></li>
				<li><?php esc_html_e( 'Specifications & Features box = size, colour, type, strength, usage, delivery area. Only add what you can confirm.', 'brickpoint' ); ?></li>
				<li><?php esc_html_e( 'WhatsApp box = optional custom message + related products.', 'brickpoint' ); ?></li>
			</ol>

			<h2><?php esc_html_e( '3. Adding videos', 'brickpoint' ); ?></h2>
			<ol>
				<li><?php esc_html_e( 'Videos → Add Video. Set the thumbnail (featured image) - it is the poster used in grids.', 'brickpoint' ); ?></li>
				<li><?php esc_html_e( 'Video Source box: choose YouTube, Vimeo, self-hosted MP4 or external URL, then paste/upload.', 'brickpoint' ); ?></li>
				<li><?php esc_html_e( 'Add a category (SS7 Bricks, Manufacturing, Our Bhattas, Quality…), duration and mark featured if needed.', 'brickpoint' ); ?></li>
				<li><?php esc_html_e( 'Related Content box links the video to products, projects or locations so it appears on those pages automatically.', 'brickpoint' ); ?></li>
			</ol>

			<h2><?php esc_html_e( '4. Shortcodes (any page, widget or Elementor text editor)', 'brickpoint' ); ?></h2>
			<table class="widefat striped">
				<tbody>
				<tr><td><code>[bp_products category="ss7-bricks" limit="6" columns="3" load_more="yes"]</code></td></tr>
				<tr><td><code>[bp_product_categories limit="15" columns="4"]</code></td></tr>
				<tr><td><code>[bp_videos category="our-bhattas" limit="6" filters="yes"]</code></td></tr>
				<tr><td><code>[bp_video id="123"]</code> <?php esc_html_e( 'or', 'brickpoint' ); ?> <code>[bp_video url="https://youtu.be/…"]</code></td></tr>
				<tr><td><code>[bp_projects limit="6" columns="3"]</code></td></tr>
				<tr><td><code>[bp_locations columns="2" video="no"]</code></td></tr>
				<tr><td><code>[bp_contact_form title="Request a quotation"]</code></td></tr>
				<tr><td><code>[bp_whatsapp label="WhatsApp Us" message="Custom message"]</code></td></tr>
				<tr><td><code>[bp_stats]</code></td></tr>
				</tbody>
			</table>

			<h2><?php esc_html_e( '5. Elementor', 'brickpoint' ); ?></h2>
			<p><?php esc_html_e( 'The theme adds a “BrickPoint” widget category with: Hero, Product Grid, Product Categories, Product Price, Product Gallery, Product Specs, WhatsApp Button, Video Grid, Video Card, Projects Grid, Location Cards, Social Links, Stats and Contact Form.', 'brickpoint' ); ?></p>
			<ul>
				<li><?php esc_html_e( 'Build the homepage/pages with these widgets - every control is editable and responsive.', 'brickpoint' ); ?></li>
				<li><?php esc_html_e( 'Header, Footer, Single Product, Product Archive, Single Video, Video Archive, Projects, Single Post, Blog Archive and 404 templates need Elementor Pro (Theme Builder) to be edited visually. Without Pro, the theme’s own templates are used and they look complete out of the box.', 'brickpoint' ); ?></li>
				<li><?php esc_html_e( 'If Elementor Pro displays a “BrickPoint” template in its library list, choose the “BrickPoint Header (Default)” / “BrickPoint Footer (Default)” entries to start from the theme design.', 'brickpoint' ); ?></li>
			</ul>

			<h2><?php esc_html_e( '6. WhatsApp behaviour', 'brickpoint' ); ?></h2>
			<p><?php esc_html_e( 'Every product button opens wa.me with a prefilled message containing the product name, category, price, unit and a closing line. Edit the wording in Customize → BrickPoint Theme → WhatsApp Inquiry. The number itself comes from Theme Settings.', 'brickpoint' ); ?></p>

			<h2><?php esc_html_e( '7. Content honesty rules', 'brickpoint' ); ?></h2>
			<ul>
				<li><?php esc_html_e( 'Never publish certifications, awards, years of experience, client counts or supply claims for named societies/developers unless verified.', 'brickpoint' ); ?></li>
				<li><?php esc_html_e( 'Mark concept imagery with “Illustrative construction reference” (Projects → edit → checkbox).', 'brickpoint' ); ?></li>
				<li><?php esc_html_e( 'Price, unit and availability are free text - they are never invented by the theme.', 'brickpoint' ); ?></li>
			</ul>
		</div>
	</div>
	<?php
}

/**
 * Elementor template guide screen.
 *
 * @return void
 */
function brickpoint_admin_templates() {
	$has_pro = defined( 'ELEMENTOR_PRO_VERSION' );
	?>
	<div class="wrap bp-admin">
		<h1><?php esc_html_e( 'Elementor Templates', 'brickpoint' ); ?></h1>

		<div class="notice notice-info">
			<p>
				<?php
				echo esc_html(
					$has_pro
						? __( 'Elementor Pro detected - you can build Header, Footer, Single, Archive and 404 templates in Theme Builder.', 'brickpoint' )
						: __( 'Elementor Pro is required to edit Header, Footer, Single Product, Product Archive, Single Video, Video Archive, Blog Archive and 404 templates visually. Without it the theme uses its own complete templates, which already match the design.', 'brickpoint' )
				);
				?>
			</p>
		</div>

		<h2><?php esc_html_e( 'Where each template lives', 'brickpoint' ); ?></h2>
		<table class="widefat striped">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Area', 'brickpoint' ); ?></th>
					<th><?php esc_html_e( 'Theme file', 'brickpoint' ); ?></th>
					<th><?php esc_html_e( 'Elementor Theme Builder condition', 'brickpoint' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr><td><?php esc_html_e( 'Header', 'brickpoint' ); ?></td><td><code>header.php</code> / <code>template-parts/header.php</code></td><td><?php esc_html_e( 'Header → Entire Site', 'brickpoint' ); ?></td></tr>
				<tr><td><?php esc_html_e( 'Footer', 'brickpoint' ); ?></td><td><code>footer.php</code></td><td><?php esc_html_e( 'Footer → Entire Site', 'brickpoint' ); ?></td></tr>
				<tr><td><?php esc_html_e( 'Product archive', 'brickpoint' ); ?></td><td><code>archive-bp_product.php</code></td><td><?php esc_html_e( 'Archive → Products', 'brickpoint' ); ?></td></tr>
				<tr><td><?php esc_html_e( 'Single product', 'brickpoint' ); ?></td><td><code>single-bp_product.php</code></td><td><?php esc_html_e( 'Single → Products', 'brickpoint' ); ?></td></tr>
				<tr><td><?php esc_html_e( 'Product category', 'brickpoint' ); ?></td><td><code>taxonomy-bp_product_category.php</code></td><td><?php esc_html_e( 'Archive → Product Categories', 'brickpoint' ); ?></td></tr>
				<tr><td><?php esc_html_e( 'Video archive', 'brickpoint' ); ?></td><td><code>archive-bp_video.php</code></td><td><?php esc_html_e( 'Archive → Videos', 'brickpoint' ); ?></td></tr>
				<tr><td><?php esc_html_e( 'Single video', 'brickpoint' ); ?></td><td><code>single-bp_video.php</code></td><td><?php esc_html_e( 'Single → Videos', 'brickpoint' ); ?></td></tr>
				<tr><td><?php esc_html_e( 'Videos page (/videos/)', 'brickpoint' ); ?></td><td><code>page-templates/page-videos.php</code></td><td><?php esc_html_e( 'Use the page template if you prefer a page over the archive', 'brickpoint' ); ?></td></tr>
				<tr><td><?php esc_html_e( 'Projects', 'brickpoint' ); ?></td><td><code>archive-bp_project.php</code>, <code>single-bp_project.php</code></td><td><?php esc_html_e( 'Archive / Single → Projects', 'brickpoint' ); ?></td></tr>
				<tr><td><?php esc_html_e( 'Locations (/locations/)', 'brickpoint' ); ?></td><td><code>archive-bp_location.php</code>, <code>single-bp_location.php</code></td><td><?php esc_html_e( 'Archive / Single → Locations', 'brickpoint' ); ?></td></tr>
				<tr><td><?php esc_html_e( 'Blog archive & single post', 'brickpoint' ); ?></td><td><code>home.php</code>, <code>single.php</code>, <code>archive.php</code></td><td><?php esc_html_e( 'Archive → Posts / Single → Posts', 'brickpoint' ); ?></td></tr>
				<tr><td><?php esc_html_e( '404', 'brickpoint' ); ?></td><td><code>404.php</code></td><td><?php esc_html_e( 'Single → 404 Page', 'brickpoint' ); ?></td></tr>
			</tbody>
		</table>

		<h2><?php esc_html_e( 'Creating an Elementor template', 'brickpoint' ); ?></h2>
		<ol>
			<li><?php esc_html_e( 'Templates → Theme Builder → Add New → choose the part type (Header, Footer, Single, Archive, 404).', 'brickpoint' ); ?></li>
			<li><?php esc_html_e( 'Build with the BrickPoint widgets, then set the display condition.', 'brickpoint' ); ?></li>
			<li><?php esc_html_e( 'The theme automatically steps aside: when an Elementor template matches, its output is used instead of the theme file.', 'brickpoint' ); ?></li>
		</ol>

		<h2><?php esc_html_e( 'Dynamic content', 'brickpoint' ); ?></h2>
		<p><?php esc_html_e( 'BrickPoint registers products, videos, projects and locations with REST support, so Elementor Pro Dynamic Tags can pull titles, images, excerpts and custom fields (price, unit, availability) into any widget.', 'brickpoint' ); ?></p>
	</div>
	<?php
}

/**
 * Activation notice with the most useful next step.
 *
 * @return void
 */
function brickpoint_activation_notice() {
	if ( ! get_transient( 'brickpoint_activation_notice' ) || ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	delete_transient( 'brickpoint_activation_notice' );

	$setup_url = admin_url( 'admin.php?page=brickpoint-setup' );

	printf(
		'<div class="notice notice-success is-dismissible"><p><strong>%1$s</strong> %2$s <a href="%3$s" class="button button-primary" style="margin-left:8px">%4$s</a></p></div>',
		esc_html__( 'BrickPoint is ready.', 'brickpoint' ),
		esc_html__( 'Pages, product categories and locations are created automatically. Review the setup screen to finish the last few steps.', 'brickpoint' ),
		esc_url( $setup_url ),
		esc_html__( 'Open Setup & Content', 'brickpoint' )
	);
}
add_action( 'admin_notices', 'brickpoint_activation_notice' );

/**
 * Keep the environment tidy: warn (do not enforce) if WooCommerce is active,
 * since BrickPoint deliberately does not use it.
 *
 * @return void
 */
function brickpoint_woocommerce_notice() {
	if ( ! class_exists( 'WooCommerce' ) || ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

	if ( ! $screen || false === strpos( (string) $screen->id, 'brickpoint' ) ) {
		return;
	}

	echo '<div class="notice notice-warning"><p>' . esc_html__( 'WooCommerce is active on this site. BrickPoint does not use WooCommerce - products, prices and inquiries are handled by the theme’s own Products system and WhatsApp/contact form. You can deactivate WooCommerce unless another part of the site needs it.', 'brickpoint' ) . '</p></div>';
}
add_action( 'admin_notices', 'brickpoint_woocommerce_notice' );

/**
 * Add a "BrickPoint settings" row action on the theme list.
 *
 * @param array  $actions Actions.
 * @param object $theme   Theme object.
 * @return array
 */
function brickpoint_theme_action_links( $actions, $theme ) {
	if ( 'brickpoint' !== $theme->get_template() ) {
		return $actions;
	}

	$actions['brickpoint_settings'] = sprintf(
		'<a href="%1$s">%2$s</a>',
		esc_url( admin_url( 'admin.php?page=brickpoint-setup' ) ),
		esc_html__( 'Theme setup', 'brickpoint' )
	);

	return $actions;
}
add_filter( 'theme_action_links', 'brickpoint_theme_action_links', 10, 2 );
