<?php
/**
 * Taxonomies plus their term meta (image, icon, banner, video, WhatsApp text).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register product, video and project taxonomies.
 *
 * @return void
 */
function brickpoint_register_taxonomies() {
	register_taxonomy(
		'bp_product_category',
		array( 'bp_product' ),
		array(
			'labels'            => array(
				'name'              => _x( 'Product Categories', 'taxonomy general name', 'brickpoint' ),
				'singular_name'     => _x( 'Product Category', 'taxonomy singular name', 'brickpoint' ),
				'menu_name'         => __( 'Product Categories', 'brickpoint' ),
				'all_items'         => __( 'All Product Categories', 'brickpoint' ),
				'edit_item'         => __( 'Edit Product Category', 'brickpoint' ),
				'view_item'         => __( 'View Product Category', 'brickpoint' ),
				'update_item'       => __( 'Update Product Category', 'brickpoint' ),
				'add_new_item'      => __( 'Add New Product Category', 'brickpoint' ),
				'new_item_name'     => __( 'New Product Category Name', 'brickpoint' ),
				'search_items'      => __( 'Search Product Categories', 'brickpoint' ),
				'parent_item'       => __( 'Parent Category', 'brickpoint' ),
				'parent_item_colon' => __( 'Parent Category:', 'brickpoint' ),
			),
			'description'       => __( 'Groups products (Bricks, SS7 Bricks, Cement, Crush, Sand, Steel, Pipes, Paints…). Each category supports an image, icon, banner and a featured video.', 'brickpoint' ),
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_in_rest'      => true,
			'rest_base'         => 'bp-product-categories',
			'rewrite'           => array(
				'slug'       => 'product-category',
				'with_front' => false,
			),
		)
	);

	register_taxonomy(
		'bp_video_category',
		array( 'bp_video' ),
		array(
			'labels'            => array(
				'name'          => _x( 'Video Categories', 'taxonomy general name', 'brickpoint' ),
				'singular_name' => _x( 'Video Category', 'taxonomy singular name', 'brickpoint' ),
				'menu_name'     => __( 'Video Categories', 'brickpoint' ),
				'all_items'     => __( 'All Video Categories', 'brickpoint' ),
				'edit_item'     => __( 'Edit Video Category', 'brickpoint' ),
				'update_item'   => __( 'Update Video Category', 'brickpoint' ),
				'add_new_item'  => __( 'Add New Video Category', 'brickpoint' ),
				'new_item_name' => __( 'New Video Category Name', 'brickpoint' ),
				'search_items'  => __( 'Search Video Categories', 'brickpoint' ),
			),
			'description'       => __( 'Groups videos (SS7 Bricks, Brick Manufacturing, Our Bhattas, Quality, Projects, Construction Materials, Company…).', 'brickpoint' ),
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_in_rest'      => true,
			'rest_base'         => 'bp-video-categories',
			'rewrite'           => array(
				'slug'       => 'video-category',
				'with_front' => false,
			),
		)
	);

	register_taxonomy(
		'bp_project_category',
		array( 'bp_project' ),
		array(
			'labels'            => array(
				'name'          => _x( 'Project Categories', 'taxonomy general name', 'brickpoint' ),
				'singular_name' => _x( 'Project Category', 'taxonomy singular name', 'brickpoint' ),
				'menu_name'     => __( 'Project Categories', 'brickpoint' ),
				'all_items'     => __( 'All Project Categories', 'brickpoint' ),
				'edit_item'     => __( 'Edit Project Category', 'brickpoint' ),
				'update_item'   => __( 'Update Project Category', 'brickpoint' ),
				'add_new_item'  => __( 'Add New Project Category', 'brickpoint' ),
				'new_item_name' => __( 'New Project Category Name', 'brickpoint' ),
				'search_items'  => __( 'Search Project Categories', 'brickpoint' ),
			),
			'description'       => __( 'Groups projects (Residential, Commercial, Industrial, Infrastructure, Housing Societies…).', 'brickpoint' ),
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_in_rest'      => true,
			'rest_base'         => 'bp-project-categories',
			'rewrite'           => array(
				'slug'       => 'project-category',
				'with_front' => false,
			),
		)
	);
}
add_action( 'init', 'brickpoint_register_taxonomies', 4 );

/**
 * Term meta keys and their sanitisation callbacks.
 *
 * @return array<string,string>
 */
function brickpoint_term_meta_keys() {
	return array(
		'_bp_term_image'        => 'absint',
		'_bp_term_banner'       => 'absint',
		'_bp_term_icon'         => 'sanitize_text_field',
		'_bp_term_short'        => 'sanitize_text_field',
		'_bp_term_video'        => 'absint',
		'_bp_term_video_url'    => 'esc_url_raw',
		'_bp_term_whatsapp'     => 'sanitize_textarea_field',
		'_bp_term_order'        => 'absint',
		'_bp_term_show_home'    => 'absint',
	);
}

/**
 * Register term meta so it is available in REST and via get_term_meta safely.
 *
 * @return void
 */
function brickpoint_register_term_meta() {
	$taxonomies = array( 'bp_product_category', 'bp_video_category', 'bp_project_category' );

	foreach ( $taxonomies as $taxonomy ) {
		foreach ( brickpoint_term_meta_keys() as $key => $sanitize ) {
			register_term_meta(
				$taxonomy,
				$key,
				array(
					'type'              => 'integer' === $sanitize || 'absint' === $sanitize ? 'integer' : 'string',
					'single'            => true,
					'show_in_rest'      => true,
					'sanitize_callback' => $sanitize,
					'auth_callback'     => function () {
						return current_user_can( 'manage_categories' );
					},
				)
			);
		}
	}
}
add_action( 'init', 'brickpoint_register_term_meta', 6 );

/**
 * Term fields: add form.
 *
 * @param string $taxonomy Taxonomy slug.
 * @return void
 */
function brickpoint_term_add_fields( $taxonomy ) {
	wp_nonce_field( 'brickpoint_term_meta', 'brickpoint_term_meta_nonce' );

	$fields = brickpoint_get_term_field_definitions();
	brickpoint_render_term_fields( $fields, null, $taxonomy, false );
}
add_action( 'bp_product_category_add_form_fields', 'brickpoint_term_add_fields' );
add_action( 'bp_video_category_add_form_fields', 'brickpoint_term_add_fields' );
add_action( 'bp_project_category_add_form_fields', 'brickpoint_term_add_fields' );

/**
 * Term fields: edit form.
 *
 * @param WP_Term $term Term object.
 * @return void
 */
function brickpoint_term_edit_fields( $term ) {
	wp_nonce_field( 'brickpoint_term_meta', 'brickpoint_term_meta_nonce' );

	$fields = brickpoint_get_term_field_definitions();
	brickpoint_render_term_fields( $fields, $term, $term->taxonomy, true );
}
add_action( 'bp_product_category_edit_form_fields', 'brickpoint_term_edit_fields' );
add_action( 'bp_video_category_edit_form_fields', 'brickpoint_term_edit_fields' );
add_action( 'bp_project_category_edit_form_fields', 'brickpoint_term_edit_fields' );

/**
 * Field definitions for taxonomy terms.
 *
 * @return array<string,array<string,mixed>>
 */
function brickpoint_get_term_field_definitions() {
	return array(
		'_bp_term_icon'      => array(
			'label' => __( 'Category icon key', 'brickpoint' ),
			'type'  => 'text',
			'help'  => __( 'Optional. Available: brick, layers, truck, factory, shield, grid, star, pin, check.', 'brickpoint' ),
		),
		'_bp_term_short'     => array(
			'label' => __( 'Short description', 'brickpoint' ),
			'type'  => 'text',
			'help'  => __( 'One line shown under the category name on cards.', 'brickpoint' ),
		),
		'_bp_term_image'     => array(
			'label' => __( 'Category image', 'brickpoint' ),
			'type'  => 'image',
			'help'  => __( 'Square or 4:3 image used on category cards.', 'brickpoint' ),
		),
		'_bp_term_banner'    => array(
			'label' => __( 'Category banner', 'brickpoint' ),
			'type'  => 'image',
			'help'  => __( 'Wide banner used at the top of the category archive.', 'brickpoint' ),
		),
		'_bp_term_video'     => array(
			'label' => __( 'Featured video (from Videos)', 'brickpoint' ),
			'type'  => 'post',
			'pt'    => 'bp_video',
			'help'  => __( 'Select a video from the Videos post type.', 'brickpoint' ),
		),
		'_bp_term_video_url' => array(
			'label' => __( 'or featured video URL', 'brickpoint' ),
			'type'  => 'url',
			'help'  => __( 'YouTube / Vimeo / MP4 URL used when no video is selected above.', 'brickpoint' ),
		),
		'_bp_term_whatsapp'  => array(
			'label' => __( 'WhatsApp inquiry message', 'brickpoint' ),
			'type'  => 'textarea',
			'help'  => __( 'Message prefilled when someone taps this category’s WhatsApp button.', 'brickpoint' ),
		),
		'_bp_term_order'     => array(
			'label' => __( 'Display order', 'brickpoint' ),
			'type'  => 'number',
			'help'  => __( 'Lower numbers appear first.', 'brickpoint' ),
		),
		'_bp_term_show_home' => array(
			'label' => __( 'Show on homepage category section', 'brickpoint' ),
			'type'  => 'checkbox',
		),
	);
}

/**
 * Render term fields (shared by add/edit screens).
 *
 * @param array        $fields   Field definitions.
 * @param WP_Term|null $term     Term or null.
 * @param string       $taxonomy Taxonomy.
 * @param bool         $is_edit  Whether we are on the edit screen.
 * @return void
 */
function brickpoint_render_term_fields( $fields, $term, $taxonomy, $is_edit ) {
	foreach ( $fields as $key => $field ) {
		$value = $term ? get_term_meta( $term->term_id, $key, true ) : '';
		$id    = $taxonomy . $key;

		echo $is_edit ? '<tr class="form-field"><th scope="row">' : '<div class="form-field">';

		if ( $is_edit ) {
			printf( '<label for="%s">%s</label></th><td>', esc_attr( $id ), esc_html( $field['label'] ) );
		} else {
			printf( '<label for="%s">%s</label>', esc_attr( $id ), esc_html( $field['label'] ) );
		}

		switch ( $field['type'] ) {
			case 'image':
				$url = $value ? wp_get_attachment_image_url( (int) $value, 'medium' ) : '';
				printf(
					'<span class="bp-term-image-field"><input type="hidden" id="%1$s" name="%1$s" value="%2$s" class="bp-term-image-id" />
					<img src="%3$s" class="bp-term-image-preview" alt="" style="max-width:220px;display:%4$s;border-radius:8px;margin:6px 0" />
					<button type="button" class="button bp-term-image-select">%5$s</button>
					<button type="button" class="button bp-term-image-clear" style="display:%6$s">%7$s</button></span>',
					esc_attr( $id ),
					esc_attr( (string) $value ),
					esc_url( $url ? $url : '' ),
					$url ? 'block' : 'none',
					esc_html__( 'Select image', 'brickpoint' ),
					$value ? 'inline-block' : 'none',
					esc_html__( 'Remove', 'brickpoint' )
				);
				break;

			case 'post':
				$posts = get_posts(
					array(
						'post_type'      => isset( $field['pt'] ) ? $field['pt'] : 'page',
						'posts_per_page' => 200,
						'orderby'        => 'title',
						'order'          => 'ASC',
						'post_status'    => array( 'publish', 'draft' ),
					)
				);

				echo '<select id="' . esc_attr( $id ) . '" name="' . esc_attr( $id ) . '">';
				echo '<option value="">' . esc_html__( '— None —', 'brickpoint' ) . '</option>';

				foreach ( $posts as $item ) {
					printf(
						'<option value="%1$d" %2$s>%3$s</option>',
						(int) $item->ID,
						selected( (int) $value, $item->ID, false ),
						esc_html( $item->post_title )
					);
				}

				echo '</select>';
				break;

			case 'textarea':
				printf(
					'<textarea id="%1$s" name="%1$s" rows="3" class="large-text">%2$s</textarea>',
					esc_attr( $id ),
					esc_textarea( (string) $value )
				);
				break;

			case 'checkbox':
				printf(
					'<input type="checkbox" id="%1$s" name="%1$s" value="1" %2$s />',
					esc_attr( $id ),
					checked( (bool) $value, true, false )
				);
				break;

			case 'number':
				printf(
					'<input type="number" id="%1$s" name="%1$s" value="%2$s" class="small-text" step="1" />',
					esc_attr( $id ),
					esc_attr( (string) $value )
				);
				break;

			case 'url':
				printf(
					'<input type="url" id="%1$s" name="%1$s" value="%2$s" class="regular-text" />',
					esc_attr( $id ),
					esc_attr( (string) $value )
				);
				break;

			default:
				printf(
					'<input type="text" id="%1$s" name="%1$s" value="%2$s" class="regular-text" />',
					esc_attr( $id ),
					esc_attr( (string) $value )
				);
				break;
		}

		if ( ! empty( $field['help'] ) ) {
			echo '<p class="description">' . esc_html( $field['help'] ) . '</p>';
		}

		echo $is_edit ? '</td></tr>' : '</div>';
	}
}

/**
 * Save term meta with nonce + capability checks.
 *
 * @param int $term_id Term ID.
 * @return void
 */
function brickpoint_save_term_meta( $term_id ) {
	if ( ! isset( $_POST['brickpoint_term_meta_nonce'] ) ) {
		return;
	}

	$nonce = sanitize_text_field( wp_unslash( $_POST['brickpoint_term_meta_nonce'] ) );

	if ( ! wp_verify_nonce( $nonce, 'brickpoint_term_meta' ) || ! current_user_can( 'manage_categories' ) ) {
		return;
	}

	foreach ( brickpoint_term_meta_keys() as $key => $sanitize ) {
		$field_name = '';

		// Field names are prefixed with the taxonomy, so accept both known taxonomies.
		foreach ( array( 'bp_product_category', 'bp_video_category', 'bp_project_category' ) as $taxonomy ) {
			if ( isset( $_POST[ $taxonomy . $key ] ) ) {
				$field_name = $taxonomy . $key;
				break;
			}
		}

		if ( '_bp_term_show_home' === $key ) {
			$value = isset( $_POST['bp_product_category' . $key] ) || isset( $_POST['bp_video_category' . $key] ) || isset( $_POST['bp_project_category' . $key] ) ? 1 : 0;
			update_term_meta( $term_id, $key, $value );
			continue;
		}

		if ( ! $field_name ) {
			continue;
		}

		$raw   = wp_unslash( $_POST[ $field_name ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitised below via the registered callback.
		$value = is_callable( $sanitize ) ? call_user_func( $sanitize, $raw ) : sanitize_text_field( (string) $raw );

		if ( '' === $value || '0' === $value ) {
			delete_term_meta( $term_id, $key );
		} else {
			update_term_meta( $term_id, $key, $value );
		}
	}
}
add_action( 'created_term', 'brickpoint_save_term_meta' );
add_action( 'edited_term', 'brickpoint_save_term_meta' );

/**
 * Media picker for term images and meta boxes.
 *
 * @param string $hook Admin hook.
 * @return void
 */
function brickpoint_admin_media_scripts( $hook ) {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

	if ( ! $screen ) {
		return;
	}

	$is_term_screen = in_array( $hook, array( 'edit-tags.php', 'term.php' ), true );

	if ( ! $is_term_screen && ! in_array( $screen->post_type, array( 'bp_product', 'bp_video', 'bp_project', 'bp_location' ), true ) ) {
		return;
	}

	wp_enqueue_media();

	wp_enqueue_script(
		'brickpoint-admin',
		BRICKPOINT_URI . 'assets/js/admin.js',
		array( 'jquery' ),
		BRICKPOINT_VERSION,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'brickpoint_admin_media_scripts' );

/**
 * Required product categories (created on activation, editable afterwards).
 *
 * @return array<string,string> Slug => name.
 */
function brickpoint_default_product_categories() {
	return array(
		'bricks'                    => __( 'Bricks', 'brickpoint' ),
		'ss7-bricks'                => __( 'SS7 Bricks', 'brickpoint' ),
		'cement'                    => __( 'Cement', 'brickpoint' ),
		'bajri-crush'               => __( 'Bajri / Crush', 'brickpoint' ),
		'sand-rait'                 => __( 'Sand / Rait', 'brickpoint' ),
		'steel'                     => __( 'Steel', 'brickpoint' ),
		'electric-conduit-pipes'    => __( 'Electric Conduit Pipes', 'brickpoint' ),
		'plumbing-pipes-fittings'   => __( 'Plumbing Pipes and Fittings', 'brickpoint' ),
		'construction-chemicals'    => __( 'Construction Chemicals', 'brickpoint' ),
		'insulation-membrane'       => __( 'Insulation and Membrane', 'brickpoint' ),
		'cables-wires'              => __( 'Cables and Wires', 'brickpoint' ),
		'paints'                    => __( 'Paints', 'brickpoint' ),
		'lights'                    => __( 'Lights', 'brickpoint' ),
		'switches-sockets'          => __( 'Switches and Sockets', 'brickpoint' ),
		'other-construction-materials' => __( 'Other Construction Materials', 'brickpoint' ),
	);
}

/**
 * Required video categories.
 *
 * @return array<string,string>
 */
function brickpoint_default_video_categories() {
	return array(
		'ss7-bricks'             => __( 'SS7 Bricks', 'brickpoint' ),
		'brick-manufacturing'    => __( 'Brick Manufacturing', 'brickpoint' ),
		'our-bhattas'            => __( 'Our Bhattas', 'brickpoint' ),
		'brick-quality'          => __( 'Brick Quality', 'brickpoint' ),
		'construction-projects'  => __( 'Construction Projects', 'brickpoint' ),
		'construction-materials' => __( 'Construction Materials', 'brickpoint' ),
		'cement'                 => __( 'Cement', 'brickpoint' ),
		'bajri-crush'            => __( 'Bajri / Crush', 'brickpoint' ),
		'sand-rait'              => __( 'Sand / Rait', 'brickpoint' ),
		'steel'                  => __( 'Steel', 'brickpoint' ),
		'company-brand'          => __( 'Company / Brand', 'brickpoint' ),
		'promotional-videos'     => __( 'Promotional Videos', 'brickpoint' ),
		'product-videos'         => __( 'Product Videos', 'brickpoint' ),
		'behind-the-scenes'      => __( 'Behind the Scenes', 'brickpoint' ),
		'other-videos'           => __( 'Other Videos', 'brickpoint' ),
	);
}

/**
 * Sensible starting project categories.
 *
 * @return array<string,string>
 */
function brickpoint_default_project_categories() {
	return array(
		'residential'     => __( 'Residential', 'brickpoint' ),
		'commercial'      => __( 'Commercial', 'brickpoint' ),
		'industrial'      => __( 'Industrial', 'brickpoint' ),
		'infrastructure'  => __( 'Infrastructure', 'brickpoint' ),
		'housing-societies' => __( 'Housing Societies', 'brickpoint' ),
		'grey-structure'  => __( 'Grey Structure', 'brickpoint' ),
		'renovation'      => __( 'Renovation & Extension', 'brickpoint' ),
	);
}

/**
 * Seed the taxonomy terms. Safe to run repeatedly.
 *
 * @param bool $include_projects Whether to seed project categories too.
 * @return array<string,int> Counts of terms created.
 */
function brickpoint_seed_taxonomy_terms( $include_projects = true ) {
	$sets  = array(
		'bp_product_category' => brickpoint_default_product_categories(),
		'bp_video_category'   => brickpoint_default_video_categories(),
	);

	if ( $include_projects ) {
		$sets['bp_project_category'] = brickpoint_default_project_categories();
	}

	$created = array();

	foreach ( $sets as $taxonomy => $terms ) {
		if ( ! taxonomy_exists( $taxonomy ) ) {
			continue;
		}

		$created[ $taxonomy ] = 0;
		$order                = 0;

		foreach ( $terms as $slug => $name ) {
			$order++;

			$existing = get_term_by( 'slug', $slug, $taxonomy );

			if ( $existing instanceof WP_Term ) {
				if ( ! get_term_meta( $existing->term_id, '_bp_term_order', true ) ) {
					update_term_meta( $existing->term_id, '_bp_term_order', $order );
				}

				continue;
			}

			$result = wp_insert_term( $name, $taxonomy, array( 'slug' => $slug ) );

			if ( is_wp_error( $result ) ) {
				continue;
			}

			update_term_meta( $result['term_id'], '_bp_term_order', $order );

			if ( 'bp_product_category' === $taxonomy ) {
				update_term_meta( $result['term_id'], '_bp_term_show_home', 1 );
			}

			if ( 'bp_product_category' === $taxonomy ) {
				$icons = array(
					'ss7-bricks' => 'brick',
					'bricks'     => 'brick',
					'cement'     => 'layers',
					'bajri-crush' => 'truck',
					'sand-rait'  => 'truck',
					'steel'      => 'factory',
					'paints'     => 'shield',
				);
				if ( isset( $icons[ $slug ] ) ) {
					update_term_meta( $result['term_id'], '_bp_term_icon', $icons[ $slug ] );
				}
			}

			$created[ $taxonomy ]++;
		}
	}

	return $created;
}

/**
 * Product category terms, ordered, optionally limited to "show on homepage".
 *
 * @param array $args Query args: hide_empty, home_only, number, parent.
 * @return WP_Term[]
 */
function brickpoint_get_product_categories( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'hide_empty' => false,
			'home_only'  => false,
			'number'     => 0,
			'parent'     => 0,
		)
	);

	$terms = get_terms(
		array(
			'taxonomy'   => 'bp_product_category',
			'hide_empty' => (bool) $args['hide_empty'],
			'number'     => (int) $args['number'],
			'parent'     => '' === $args['parent'] ? '' : (int) $args['parent'],
			'orderby'    => 'meta_value_num',
			'meta_key'   => '_bp_term_order', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'order'      => 'ASC',
		)
	);

	if ( is_wp_error( $terms ) || ! $terms ) {
		return array();
	}

	// Terms without an explicit order still need a home; sort by term order then name.
	usort(
		$terms,
		function ( $a, $b ) {
			$oa = (int) get_term_meta( $a->term_id, '_bp_term_order', true );
			$ob = (int) get_term_meta( $b->term_id, '_bp_term_order', true );

			if ( $oa === $ob ) {
				return strcasecmp( $a->name, $b->name );
			}

			return $oa <=> $ob;
		}
	);

	if ( $args['home_only'] ) {
		$terms = array_values(
			array_filter(
				$terms,
				function ( $term ) {
					return (bool) get_term_meta( $term->term_id, '_bp_term_show_home', true );
				}
			)
		);
	}

	return $terms;
}

/**
 * Category image (or banner) URL with graceful fallback.
 *
 * @param int    $term_id Term ID.
 * @param string $size    Image size.
 * @param string $which   image|banner.
 * @return string
 */
function brickpoint_term_image_url( $term_id, $size = 'bp-card', $which = 'image' ) {
	$key      = 'banner' === $which ? '_bp_term_banner' : '_bp_term_image';
	$id       = (int) get_term_meta( $term_id, $key, true );
	static $fallback = array();

	if ( $id ) {
		$url = wp_get_attachment_image_url( $id, $size );

		if ( $url ) {
			return $url;
		}
	}

	/*
	 * No image chosen for the term yet (the default for categories the owner
	 * has not decorated). Instead of an empty grey tile, borrow the picture of
	 * the newest item in the term - so every category card, badge and menu
	 * shows a real photo until a term image is set.
	 */
	if ( 'banner' === $which ) {
		return '';
	}

	$term = get_term( $term_id );

	if ( ! $term instanceof WP_Term ) {
		return '';
	}

	$map = array(
		'bp_product_category' => 'bp_product',
		'bp_video_category'   => 'bp_video',
		'bp_project_category' => 'bp_project',
	);

	$post_type = isset( $map[ $term->taxonomy ] ) ? $map[ $term->taxonomy ] : '';

	if ( ! $post_type ) {
		return '';
	}

	if ( ! isset( $fallback[ $term_id ] ) ) {
		$items = get_posts(
			array(
				'post_type'        => $post_type,
				'post_status'      => 'publish',
				'posts_per_page'   => 1,
				'fields'           => 'ids',
				'no_found_rows'    => true,
				'suppress_filters' => false,
				'tax_query'        => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					array(
						'taxonomy' => $term->taxonomy,
						'field'    => 'term_id',
						'terms'    => $term_id,
					),
				),
				'meta_query'       => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					array(
						'key'     => '_thumbnail_id',
						'compare' => 'EXISTS',
					),
				),
			)
		);

		$fallback[ $term_id ] = $items ? (int) get_post_thumbnail_id( $items[0] ) : 0;
	}

	$thumb_id = $fallback[ $term_id ];

	if ( $thumb_id ) {
		$url = wp_get_attachment_image_url( $thumb_id, $size );

		if ( $url ) {
			return $url;
		}
	}

	return '';
}
