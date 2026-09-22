<?php
/**
 * Template helpers: breadcrumbs, pagination, schema, nav fallbacks, badges.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Breadcrumbs with BreadcrumbList schema (no plugin needed).
 *
 * @return void
 */
function brickpoint_breadcrumbs() {
	$items = brickpoint_breadcrumb_items();

	if ( count( $items ) < 2 ) {
		return;
	}

	$schema = array(
		'@context'        => 'https://schema.org',
		'@type'           => 'BreadcrumbList',
		'itemListElement' => array(),
	);

	echo '<nav class="bp-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'brickpoint' ) . '"><ol class="bp-breadcrumbs__list">';

	$position = 1;

	foreach ( $items as $item ) {
		$is_last = ( $position === count( $items ) );

		echo '<li class="bp-breadcrumbs__item">';

		if ( $is_last || empty( $item['url'] ) ) {
			echo '<span aria-current="page">' . esc_html( $item['label'] ) . '</span>';
		} else {
			printf( '<a href="%1$s">%2$s</a>', esc_url( $item['url'] ), esc_html( $item['label'] ) );
		}

		echo '</li>';

		$schema['itemListElement'][] = array(
			'@type'    => 'ListItem',
			'position' => $position,
			'name'     => $item['label'],
			'item'     => $item['url'] ? $item['url'] : null,
		);

		$position++;
	}

	echo '</ol></nav>';

	echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
}

/**
 * Breadcrumb trail data.
 *
 * @return array<int,array<string,string>>
 */
function brickpoint_breadcrumb_items() {
	$items = array(
		array(
			'label' => __( 'Home', 'brickpoint' ),
			'url'   => home_url( '/' ),
		),
	);

	if ( brickpoint_is_shop_archive() ) {
		$products_page = get_page_by_path( 'products' );

		if ( $products_page instanceof WP_Post && ! is_page( $products_page->ID ) ) {
			$items[] = array(
				'label' => get_the_title( $products_page->ID ),
				'url'   => get_permalink( $products_page->ID ),
			);
		}

		if ( is_tax( 'bp_product_category' ) ) {
			$term = get_queried_object();

			if ( $term instanceof WP_Term ) {
				$ancestors = array_reverse( get_ancestors( $term->term_id, 'bp_product_category', 'taxonomy' ) );

				foreach ( $ancestors as $ancestor_id ) {
					$ancestor = get_term( $ancestor_id, 'bp_product_category' );

					if ( $ancestor instanceof WP_Term ) {
						$items[] = array(
							'label' => $ancestor->name,
							'url'   => get_term_link( $ancestor ),
						);
					}
				}

				$items[] = array(
					'label' => $term->name,
					'url'   => '',
				);
			}
		} elseif ( is_singular( 'bp_product' ) ) {
			$id    = get_the_ID();
			$terms = get_the_terms( $id, 'bp_product_category' );

			if ( $terms && ! is_wp_error( $terms ) ) {
				$items[] = array(
					'label' => $terms[0]->name,
					'url'   => get_term_link( $terms[0] ),
				);
			}

			$items[] = array(
				'label' => get_the_title( $id ),
				'url'   => '',
			);
		} else {
			$items[] = array(
				'label' => post_type_archive_title( '', false ) ? post_type_archive_title( '', false ) : __( 'Products', 'brickpoint' ),
				'url'   => '',
			);
		}

		return $items;
	}

	if ( is_singular( 'bp_video' ) ) {
		$items[] = array(
			'label' => __( 'Videos', 'brickpoint' ),
			'url'   => get_post_type_archive_link( 'bp_video' ),
		);

		$terms = get_the_terms( get_the_ID(), 'bp_video_category' );

		if ( $terms && ! is_wp_error( $terms ) ) {
			$items[] = array(
				'label' => $terms[0]->name,
				'url'   => get_term_link( $terms[0] ),
			);
		}

		$items[] = array(
			'label' => get_the_title(),
			'url'   => '',
		);

		return $items;
	}

	if ( is_singular( 'bp_project' ) ) {
		$items[] = array(
			'label' => __( 'Projects', 'brickpoint' ),
			'url'   => get_post_type_archive_link( 'bp_project' ),
		);
		$items[] = array(
			'label' => get_the_title(),
			'url'   => '',
		);

		return $items;
	}

	if ( is_singular( 'bp_location' ) ) {
		$items[] = array(
			'label' => __( 'Locations', 'brickpoint' ),
			'url'   => get_post_type_archive_link( 'bp_location' ),
		);
		$items[] = array(
			'label' => get_the_title(),
			'url'   => '',
		);

		return $items;
	}

	if ( is_post_type_archive( 'bp_video' ) || is_tax( 'bp_video_category' ) ) {
		$items[] = array(
			'label' => __( 'Videos', 'brickpoint' ),
			'url'   => get_post_type_archive_link( 'bp_video' ),
		);

		if ( is_tax( 'bp_video_category' ) ) {
			$term = get_queried_object();

			if ( $term instanceof WP_Term ) {
				$items[] = array(
					'label' => $term->name,
					'url'   => '',
				);
			}
		}

		return $items;
	}

	if ( is_post_type_archive( 'bp_project' ) || is_tax( 'bp_project_category' ) ) {
		$items[] = array(
			'label' => __( 'Projects', 'brickpoint' ),
			'url'   => get_post_type_archive_link( 'bp_project' ),
		);

		if ( is_tax( 'bp_project_category' ) ) {
			$term = get_queried_object();

			if ( $term instanceof WP_Term ) {
				$items[] = array(
					'label' => $term->name,
					'url'   => '',
				);
			}
		}

		return $items;
	}

	if ( is_singular( 'post' ) ) {
		$categories = get_the_category();

		if ( $categories ) {
			$items[] = array(
				'label' => $categories[0]->name,
				'url'   => get_category_link( $categories[0]->term_id ),
			);
		}

		$items[] = array(
			'label' => get_the_title(),
			'url'   => '',
		);

		return $items;
	}

	if ( is_singular() ) {
		$post = get_post();

		if ( $post instanceof WP_Post && $post->post_parent ) {
			$ancestors = array_reverse( get_post_ancestors( $post->ID ) );

			foreach ( $ancestors as $ancestor_id ) {
				$items[] = array(
					'label' => get_the_title( $ancestor_id ),
					'url'   => get_permalink( $ancestor_id ),
				);
			}
		}

		$items[] = array(
			'label' => get_the_title(),
			'url'   => '',
		);

		return $items;
	}

	if ( is_search() ) {
		$items[] = array(
			'label' => sprintf(
				/* translators: %s: search query. */
				__( 'Search: %s', 'brickpoint' ),
				get_search_query()
			),
			'url' => '',
		);

		return $items;
	}

	if ( is_archive() ) {
		$items[] = array(
			'label' => wp_strip_all_tags( get_the_archive_title() ),
			'url'   => '',
		);
	}

	return $items;
}

/**
 * Is the current request part of the product system?
 *
 * @return bool
 */
function brickpoint_is_shop_archive() {
	return is_post_type_archive( 'bp_product' ) || is_tax( 'bp_product_category' ) || is_singular( 'bp_product' );
}

/**
 * Numeric pagination that matches the theme styling.
 *
 * @param WP_Query|null $query Optional query.
 * @return void
 */
function brickpoint_pagination( $query = null ) {
	$query = $query ? $query : $GLOBALS['wp_query'];

	$total = (int) $query->max_num_pages;

	if ( $total < 2 ) {
		return;
	}

	$current = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );

	$links = paginate_links(
		array(
			'total'     => $total,
			'current'   => $current,
			'type'      => 'array',
			'mid_size'  => 1,
			'end_size'  => 1,
			'prev_text' => brickpoint_icon( 'arrow-left', array( 'size' => 18 ) ) . '<span class="screen-reader-text">' . esc_html__( 'Previous', 'brickpoint' ) . '</span>',
			'next_text' => brickpoint_icon( 'arrow-right', array( 'size' => 18 ) ) . '<span class="screen-reader-text">' . esc_html__( 'Next', 'brickpoint' ) . '</span>',
		)
	);

	if ( ! $links ) {
		return;
	}

	echo '<nav class="bp-pagination" aria-label="' . esc_attr__( 'Pagination', 'brickpoint' ) . '"><ul class="bp-pagination__list">';

	foreach ( $links as $link ) {
		echo '<li class="bp-pagination__item">' . wp_kses_post( $link ) . '</li>';
	}

	echo '</ul></nav>';
}

/**
 * Fallback main navigation before the owner creates a menu.
 *
 * @return void
 */
function brickpoint_primary_menu_fallback() {
	$links = array(
		__( 'Home', 'brickpoint' )             => home_url( '/' ),
		__( 'Products', 'brickpoint' )         => brickpoint_page_url( 'products' ),
		__( 'SS7 Bricks', 'brickpoint' )       => brickpoint_page_url( 'ss7-bricks' ),
		__( 'Materials', 'brickpoint' )        => brickpoint_page_url( 'construction-materials' ),
		__( 'Videos', 'brickpoint' )           => get_post_type_archive_link( 'bp_video' ),
		__( 'Projects', 'brickpoint' )         => get_post_type_archive_link( 'bp_project' ),
		__( 'Locations', 'brickpoint' )        => get_post_type_archive_link( 'bp_location' ),
		__( 'About', 'brickpoint' )            => brickpoint_page_url( 'about-us' ),
		__( 'Contact', 'brickpoint' )          => brickpoint_page_url( 'contact' ),
	);

	echo '<ul class="bp-menu bp-menu--fallback">';

	foreach ( $links as $label => $url ) {
		if ( ! $url ) {
			continue;
		}

		printf(
			'<li class="menu-item"><a href="%1$s">%2$s</a></li>',
			esc_url( $url ),
			esc_html( $label )
		);
	}

	echo '</ul>';
}

/**
 * Product availability badge data.
 *
 * @param int $post_id Product ID.
 * @return array<string,string>
 */
function brickpoint_availability_badge( $post_id ) {
	$status = brickpoint_meta( $post_id, '_bp_availability' );
	$labels = brickpoint_availability_options();

	if ( ! $status || ! isset( $labels[ $status ] ) ) {
		return array(
			'status' => '',
			'label'  => '',
		);
	}

	$classes = array(
		'in-stock'     => 'is-in-stock',
		'limited'      => 'is-limited',
		'made-to-order' => 'is-order',
		'on-request'   => 'is-request',
		'out-of-stock' => 'is-out',
	);

	return array(
		'status' => isset( $classes[ $status ] ) ? $classes[ $status ] : '',
		'label'  => $labels[ $status ],
	);
}

/**
 * Price line: label + price + unit.
 *
 * @param int   $post_id     Product ID.
 * @param array $args         hide_label, hide_unit.
 * @return string
 */
function brickpoint_price_html( $post_id, $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'hide_label' => false,
			'hide_unit'  => false,
		)
	);

	$price = brickpoint_format_price( brickpoint_meta( $post_id, '_bp_price' ) );
	$label = brickpoint_meta( $post_id, '_bp_price_label' );
	$unit  = brickpoint_meta( $post_id, '_bp_unit' );

	if ( ! $price && ! $label ) {
		return '<span class="bp-price bp-price--empty">' . esc_html__( 'Price on request', 'brickpoint' ) . '</span>';
	}

	$html = '<span class="bp-price">';

	if ( $label && ! $args['hide_label'] ) {
		$html .= '<span class="bp-price__label">' . esc_html( $label ) . '</span>';
	}

	if ( $price ) {
		$html .= '<span class="bp-price__value">' . esc_html( $price ) . '</span>';
	}

	if ( $unit && ! $args['hide_unit'] ) {
		$html .= '<span class="bp-price__unit">' . esc_html( $unit ) . '</span>';
	}

	$html .= '</span>';

	return $html;
}

/**
 * Organization schema, printed once in the footer.
 *
 * Only accurate, admin-provided data is output.
 *
 * @return void
 */
function brickpoint_organization_schema() {
	if ( is_404() ) {
		return;
	}

	$name   = brickpoint_option( 'bp_brand_name' );
	$phone  = brickpoint_phone_raw();
	$email  = brickpoint_option( 'bp_email' );
	$social = wp_list_pluck( brickpoint_get_social_links(), 'url' );

	$data = array(
		'@context' => 'https://schema.org',
		'@type'    => 'Organization',
		'name'     => $name,
		'url'      => home_url( '/' ),
	);

	$logo_id = get_theme_mod( 'custom_logo' );

	if ( $logo_id ) {
		$logo = wp_get_attachment_image_url( $logo_id, 'full' );

		if ( $logo ) {
			$data['logo'] = $logo;
		}
	}

	if ( $phone ) {
		$data['telephone'] = $phone;
	}

	if ( $email ) {
		$data['email'] = $email;
	}

	if ( $social ) {
		$data['sameAs'] = array_values( $social );
	}

	$address = brickpoint_option( 'bp_address' );

	if ( $address ) {
		$data['address'] = array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => wp_strip_all_tags( $address ),
			'addressLocality' => 'Lahore',
			'addressRegion'   => 'Punjab',
			'addressCountry'  => 'PK',
		);
	}

	echo '<script type="application/ld+json">' . wp_json_encode( $data ) . '</script>';
}
add_action( 'wp_footer', 'brickpoint_organization_schema', 5 );

/**
 * Product structured data - only real values that the admin entered.
 *
 * @return void
 */
function brickpoint_product_schema() {
	if ( ! is_singular( 'bp_product' ) ) {
		return;
	}

	$id    = get_the_ID();
	$price = brickpoint_meta( $id, '_bp_price' );
	$sku   = brickpoint_meta( $id, '_bp_sku' );

	$data = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'Product',
		'name'        => get_the_title( $id ),
		'description' => brickpoint_excerpt( 40 ),
		'url'         => get_permalink( $id ),
	);

	if ( has_post_thumbnail( $id ) ) {
		$data['image'] = wp_get_attachment_image_url( get_post_thumbnail_id( $id ), 'full' );
	}

	if ( $sku ) {
		$data['sku'] = $sku;
	}

	$brand = brickpoint_option( 'bp_brand_name' );

	if ( $brand ) {
		$data['brand'] = array(
			'@type' => 'Brand',
			'name'  => $brand,
		);
	}

	$terms = get_the_terms( $id, 'bp_product_category' );

	if ( $terms && ! is_wp_error( $terms ) ) {
		$data['category'] = $terms[0]->name;
	}

	// Offers are only declared when a real, numeric price exists.
	if ( $price && is_numeric( str_replace( array( ',', ' ' ), '', $price ) ) ) {
		$availability = 'https://schema.org/InStock';
		$status       = brickpoint_meta( $id, '_bp_availability' );

		if ( 'out-of-stock' === $status ) {
			$availability = 'https://schema.org/OutOfStock';
		} elseif ( in_array( $status, array( 'made-to-order', 'on-request' ), true ) ) {
			$availability = 'https://schema.org/PreOrder';
		}

		$data['offers'] = array(
			'@type'         => 'Offer',
			'price'         => (string) str_replace( array( ',', ' ' ), '', $price ),
			'priceCurrency' => apply_filters( 'brickpoint_price_currency', 'PKR' ),
			'availability'  => $availability,
			'url'           => get_permalink( $id ),
		);
	}

	// Intentionally no aggregateRating / review - BrickPoint does not publish fake reviews.

	echo '<script type="application/ld+json">' . wp_json_encode( $data ) . '</script>';
}
add_action( 'wp_footer', 'brickpoint_product_schema', 6 );

/**
 * VideoObject schema for single videos.
 *
 * @return void
 */
function brickpoint_video_schema() {
	if ( ! is_singular( 'bp_video' ) || ! function_exists( 'brickpoint_get_video_data' ) ) {
		return;
	}

	$video = brickpoint_get_video_data( get_the_ID() );

	$data = array(
		'@context'     => 'https://schema.org',
		'@type'        => 'VideoObject',
		'name'         => get_the_title( get_the_ID() ),
		'description'  => brickpoint_excerpt( 40 ),
		'uploadDate'   => get_the_date( 'c' ),
	);

	if ( ! empty( $video['thumbnail'] ) ) {
		$data['thumbnailUrl'] = $video['thumbnail'];
	}

	if ( ! empty( $video['embed_url'] ) ) {
		$data['embedUrl'] = $video['embed_url'];
	}

	if ( ! empty( $video['duration_schema'] ) ) {
		$data['duration'] = $video['duration_schema'];
	}

	echo '<script type="application/ld+json">' . wp_json_encode( $data ) . '</script>';
}
add_action( 'wp_footer', 'brickpoint_video_schema', 6 );

/**
 * Open Graph + Twitter card tags (only if no SEO plugin is active).
 *
 * @return void
 */
function brickpoint_open_graph() {
	if ( defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) || defined( 'AIOSEO_VERSION' ) ) {
		return;
	}

	$title = wp_get_document_title();
	$url   = home_url( add_query_arg( array() ) );
	$desc  = '';

	if ( is_singular() ) {
		$url  = get_permalink();
		$desc = brickpoint_excerpt( 30 );
	} elseif ( is_archive() ) {
		$desc = wp_strip_all_tags( get_the_archive_description() );
	}

	$image = '';

	if ( is_singular() && has_post_thumbnail() ) {
		$image = wp_get_attachment_image_url( get_post_thumbnail_id(), 'bp-hero' );
	}

	if ( ! $image ) {
		$logo_id = get_theme_mod( 'custom_logo' );

		if ( $logo_id ) {
			$image = wp_get_attachment_image_url( $logo_id, 'full' );
		}
	}

	printf( '<meta property="og:site_name" content="%s" />' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
	printf( '<meta property="og:type" content="%s" />' . "\n", is_singular( 'post' ) ? 'article' : 'website' );
	printf( '<meta property="og:title" content="%s" />' . "\n", esc_attr( $title ) );
	printf( '<meta property="og:url" content="%s" />' . "\n", esc_url( $url ) );

	if ( $desc ) {
		printf( '<meta property="og:description" content="%s" />' . "\n", esc_attr( $desc ) );
	}

	if ( $image ) {
		printf( '<meta property="og:image" content="%s" />' . "\n", esc_url( $image ) );
	}

	printf( '<meta name="twitter:card" content="%s" />' . "\n", $image ? 'summary_large_image' : 'summary' );
	printf( '<meta name="twitter:title" content="%s" />' . "\n", esc_attr( $title ) );

	if ( $desc ) {
		printf( '<meta name="twitter:description" content="%s" />' . "\n", esc_attr( $desc ) );
	}
}
add_action( 'wp_head', 'brickpoint_open_graph', 6 );

/**
 * Section heading helper used by templates and widgets.
 *
 * @param array $args eyebrow, title, text, align, tag, class.
 * @return void
 */
function brickpoint_section_heading( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'eyebrow' => '',
			'title'   => '',
			'text'    => '',
			'align'   => 'left',
			'tag'     => 'h2',
			'class'   => '',
		)
	);

	if ( ! $args['title'] && ! $args['eyebrow'] ) {
		return;
	}

	$allowed_tags = array( 'h1', 'h2', 'h3', 'h4', 'div', 'p' );
	$tag          = in_array( $args['tag'], $allowed_tags, true ) ? $args['tag'] : 'h2';

	printf(
		'<header class="bp-section__head bp-section__head--%1$s %2$s">',
		esc_attr( $args['align'] ),
		esc_attr( $args['class'] )
	);

	if ( $args['eyebrow'] ) {
		echo '<p class="bp-eyebrow">' . esc_html( $args['eyebrow'] ) . '</p>';
	}

	if ( $args['title'] ) {
		printf( '<%1$s class="bp-section__title">%2$s</%1$s>', esc_attr( $tag ), esc_html( $args['title'] ) );
	}

	if ( $args['text'] ) {
		echo '<p class="bp-section__text">' . esc_html( $args['text'] ) . '</p>';
	}

	echo '</header>';
}

/**
 * Renders a page hero (used by archives and page templates).
 *
 * @param array $args title, text, image_id, breadcrumbs, compact.
 * @return void
 */
function brickpoint_page_hero( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'title'       => '',
			'text'        => '',
			'image_id'    => 0,
			'bg_url'      => '',
			'breadcrumbs' => true,
			'compact'     => false,
			'btn'         => array(),
		)
	);

	$image_url = $args['bg_url'] ? $args['bg_url'] : ( $args['image_id'] ? wp_get_attachment_image_url( (int) $args['image_id'], 'bp-hero' ) : '' );

	$style = $image_url ? ' style="background-image:url(' . esc_url( $image_url ) . ')"' : '';

	printf(
		'<section class="bp-page-hero%1$s"%2$s>',
		$args['compact'] ? ' bp-page-hero--compact' : '',
		$style // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
	);

	echo '<div class="bp-container bp-page-hero__inner">';

	if ( $args['breadcrumbs'] ) {
		brickpoint_breadcrumbs();
	}

	if ( $args['title'] ) {
		echo '<h1 class="bp-page-hero__title">' . esc_html( $args['title'] ) . '</h1>';
	}

	if ( $args['text'] ) {
		echo '<p class="bp-page-hero__text">' . esc_html( $args['text'] ) . '</p>';
	}

	if ( ! empty( $args['btn']['label'] ) ) {
		printf(
			'<a class="bp-btn bp-btn--primary" href="%1$s">%2$s</a>',
			esc_url( isset( $args['btn']['url'] ) ? $args['btn']['url'] : '#' ),
			esc_html( $args['btn']['label'] )
		);
	}

	echo '</div></section>';
}

/**
 * Reusable icon-box/feature list markup.
 *
 * @param array $items Each: icon, title, text.
 * @return void
 */
function brickpoint_feature_grid( $items ) {
	if ( ! $items ) {
		return;
	}

	echo '<div class="bp-features">';

	foreach ( $items as $item ) {
		echo '<article class="bp-feature bp-reveal">';
		echo '<span class="bp-feature__icon">' . brickpoint_icon( isset( $item['icon'] ) ? $item['icon'] : 'check', array( 'size' => 24 ) ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( ! empty( $item['title'] ) ) {
			echo '<h3 class="bp-feature__title">' . esc_html( $item['title'] ) . '</h3>';
		}

		if ( ! empty( $item['text'] ) ) {
			echo '<p class="bp-feature__text">' . esc_html( $item['text'] ) . '</p>';
		}

		echo '</article>';
	}

	echo '</div>';
}

/**
 * Comment template tweaks: cleaner markup, no default avatars clutter.
 *
 * @param string $template Default template path.
 * @return string
 */
function brickpoint_comment_template( $template ) {
	$custom = BRICKPOINT_DIR . 'template-parts/comments.php';

	if ( is_file( $custom ) ) {
		return $custom;
	}

	return $template;
}
add_filter( 'comments_template', 'brickpoint_comment_template' );
