<?php
/**
 * Meta fields for Products, Videos, Projects and Locations.
 *
 * Everything here is admin-editable: no PHP editing needed for content changes.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Every meta key the theme registers, grouped by object type.
 *
 * @return array<string,array<string,mixed>>
 */
function brickpoint_meta_schema() {
	$repeater = array(
		'type'              => 'array',
		'single'            => true,
		'show_in_rest'      => array(
			'schema' => array(
				'type'  => 'array',
				'items' => array(
					'type'       => 'object',
					'properties' => array(
						'label' => array( 'type' => 'string' ),
						'value' => array( 'type' => 'string' ),
					),
				),
			),
		),
		'sanitize_callback' => 'brickpoint_sanitize_repeater',
	);

	$text = array(
		'type'         => 'string',
		'single'       => true,
		'show_in_rest' => true,
	);

	$id = array(
		'type'         => 'integer',
		'single'       => true,
		'show_in_rest' => true,
	);

	$ids = array(
		'type'         => 'string',
		'single'       => true,
		'show_in_rest' => true,
	);

	$list = array(
		'type'         => 'string',
		'single'       => true,
		'show_in_rest' => true,
	);

	return array(
		'bp_product'  => array(
			'_bp_price'         => $text,
			'_bp_price_label'   => $text,
			'_bp_unit'          => $text,
			'_bp_min_order'     => $text,
			'_bp_availability'  => $text,
			'_bp_badge'         => $text,
			'_bp_sku'           => $text,
			'_bp_delivery_area' => $text,
			'_bp_featured'      => $text,
			'_bp_gallery'       => $ids,
			'_bp_specs'         => $repeater,
			'_bp_features'      => $list,
			'_bp_video'         => $id,
			'_bp_video_url'     => $text,
			'_bp_brochure'      => $id,
			'_bp_brochure_url'  => $text,
			'_bp_whatsapp_msg'  => $text,
			'_bp_related'       => $ids,
		),
		'bp_video'    => array(
			'_bp_video_source'     => $text,
			'_bp_video_file'       => $id,
			'_bp_video_url'        => $text,
			'_bp_video_aspect'     => $text,
			'_bp_video_duration'   => $text,
			'_bp_video_captions'   => $text,
			'_bp_video_order'      => $text,
			'_bp_featured'         => $text,
			'_bp_related_products' => $ids,
			'_bp_related_projects' => $ids,
			'_bp_related_locations' => $ids,
		),
		'bp_project'  => array(
			'_bp_project_location'  => $text,
			'_bp_project_status'    => $text,
			'_bp_project_scope'     => $text,
			'_bp_project_year'      => $text,
			'_bp_project_gallery'   => $ids,
			'_bp_project_video'     => $id,
			'_bp_project_video_url' => $text,
			'_bp_project_products'  => $ids,
			'_bp_project_disclaimer' => $text,
			'_bp_illustrative'      => $text,
			'_bp_featured'          => $text,
		),
		'bp_location' => array(
			'_bp_location_company'    => $text,
			'_bp_location_address'    => $text,
			'_bp_location_map'        => $text,
			'_bp_location_directions' => $text,
			'_bp_location_phone'      => $text,
			'_bp_location_hours'      => $text,
			'_bp_location_coords'     => $text,
			'_bp_location_video'      => $id,
			'_bp_location_video_url'  => $text,
			'_bp_location_products'   => $ids,
			'_bp_location_order'      => $text,
			'_bp_featured'            => $text,
		),
		'bp_inquiry'  => array(
			'_bp_inquiry_name'     => $text,
			'_bp_inquiry_phone'    => $text,
			'_bp_inquiry_email'    => $text,
			'_bp_inquiry_company'  => $text,
			'_bp_inquiry_material' => $text,
			'_bp_inquiry_quantity' => $text,
			'_bp_inquiry_city'     => $text,
			'_bp_inquiry_source'   => $text,
			'_bp_inquiry_status'   => $text,
		),
	);
}

/**
 * Register all post meta so it is sanitised, typed and REST aware.
 *
 * @return void
 */
function brickpoint_register_meta() {
	foreach ( brickpoint_meta_schema() as $post_type => $fields ) {
		foreach ( $fields as $key => $args ) {
			$defaults = array(
				'type'         => 'string',
				'single'       => true,
				'show_in_rest' => true,
				'auth_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			);

			if ( ! isset( $args['sanitize_callback'] ) ) {
				// Comma separated attachment/relation IDs.
				$id_lists = array(
					'_bp_gallery',
					'_bp_related',
					'_bp_related_products',
					'_bp_related_projects',
					'_bp_related_locations',
					'_bp_project_gallery',
					'_bp_project_products',
					'_bp_location_products',
				);

				// Multi-line fields where each line is kept (features are one
				// bullet per line, addresses and WhatsApp messages keep breaks).
				// These must NOT run through the ID sanitiser, which would
				// reduce any non-numeric text to an empty string.
				$multiline = array(
					'_bp_features',
					'_bp_location_address',
					'_bp_whatsapp_msg',
				);

				if ( in_array( $key, $id_lists, true ) ) {
					$args['sanitize_callback'] = 'brickpoint_sanitize_id_list';
				} elseif ( in_array( $key, $multiline, true ) ) {
					$args['sanitize_callback'] = 'sanitize_textarea_field';
				} else {
					$args['sanitize_callback'] = 'sanitize_text_field';
				}
			}

			register_post_meta( $post_type, $key, wp_parse_args( $args, $defaults ) );
		}
	}
}
add_action( 'init', 'brickpoint_register_meta', 7 );

/**
 * Sanitise a comma separated list of IDs into a normalised CSV string.
 *
 * @param mixed $value Raw value.
 * @return string
 */
function brickpoint_sanitize_id_list( $value ) {
	$ids = is_array( $value ) ? $value : explode( ',', (string) $value );
	$ids = array_filter( array_map( 'absint', $ids ) );

	return implode( ',', array_unique( $ids ) );
}

/**
 * Sanitise repeater rows (label/value pairs).
 *
 * @param mixed $value Raw value.
 * @return array<int,array<string,string>>
 */
function brickpoint_sanitize_repeater( $value ) {
	$rows = array();

	if ( ! is_array( $value ) ) {
		return $rows;
	}

	foreach ( $value as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}

		$label = isset( $row['label'] ) ? sanitize_text_field( $row['label'] ) : '';
		$val   = isset( $row['value'] ) ? sanitize_text_field( $row['value'] ) : '';

		if ( '' === $label && '' === $val ) {
			continue;
		}

		$rows[] = array(
			'label' => $label,
			'value' => $val,
		);
	}

	return $rows;
}

/**
 * Register the admin meta boxes.
 *
 * @return void
 */
function brickpoint_add_meta_boxes() {
	add_meta_box(
		'bp_product_details',
		__( 'Product Details', 'brickpoint' ),
		'brickpoint_render_product_box',
		'bp_product',
		'normal',
		'high'
	);

	add_meta_box(
		'bp_product_media',
		__( 'Gallery, Video & Documents', 'brickpoint' ),
		'brickpoint_render_product_media_box',
		'bp_product',
		'normal',
		'high'
	);

	add_meta_box(
		'bp_product_specs',
		__( 'Specifications & Features', 'brickpoint' ),
		'brickpoint_render_product_specs_box',
		'bp_product',
		'normal',
		'default'
	);

	add_meta_box(
		'bp_product_cta',
		__( 'WhatsApp Inquiry & Related Products', 'brickpoint' ),
		'brickpoint_render_product_cta_box',
		'bp_product',
		'side',
		'default'
	);

	add_meta_box(
		'bp_video_details',
		__( 'Video Source', 'brickpoint' ),
		'brickpoint_render_video_box',
		'bp_video',
		'normal',
		'high'
	);

	add_meta_box(
		'bp_video_relations',
		__( 'Related Content', 'brickpoint' ),
		'brickpoint_render_video_relations_box',
		'bp_video',
		'side',
		'default'
	);

	add_meta_box(
		'bp_project_details',
		__( 'Project Details', 'brickpoint' ),
		'brickpoint_render_project_box',
		'bp_project',
		'normal',
		'high'
	);

	add_meta_box(
		'bp_location_details',
		__( 'Location Details', 'brickpoint' ),
		'brickpoint_render_location_box',
		'bp_location',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'brickpoint_add_meta_boxes' );

/**
 * Shared field renderer.
 *
 * @param string $key   Meta key (without the _bp_ registration prefix confusion - pass the stored key).
 * @param array  $field Field args.
 * @param int    $post_id Post ID.
 * @return void
 */
function brickpoint_field( $key, $field, $post_id ) {
	$type    = isset( $field['type'] ) ? $field['type'] : 'text';
	$value   = get_post_meta( $post_id, $key, true );
	$id      = 'field' . $key . '_' . $post_id;
	$name    = $key;
	$help    = isset( $field['help'] ) ? $field['help'] : '';
	$options = isset( $field['options'] ) ? $field['options'] : array();
	$rows    = isset( $field['rows'] ) ? (int) $field['rows'] : 3;

	if ( 'checkbox' === $type ) {
		printf(
			'<p class="bp-field bp-field--checkbox"><label for="%1$s"><input type="checkbox" id="%1$s" name="%2$s" value="1" %3$s /> %4$s</label></p>',
			esc_attr( $id ),
			esc_attr( $name ),
			checked( (bool) $value, true, false ),
			esc_html( isset( $field['label'] ) ? $field['label'] : '' )
		);

		if ( $help ) {
			echo '<p class="description">' . esc_html( $help ) . '</p>';
		}

		return;
	}

	echo '<p class="bp-field bp-field--' . esc_attr( $type ) . '">';

	if ( ! empty( $field['label'] ) ) {
		printf( '<label for="%1$s"><strong>%2$s</strong></label><br />', esc_attr( $id ), esc_html( $field['label'] ) );
	}

	switch ( $type ) {
		case 'textarea':
			printf(
				'<textarea id="%1$s" name="%2$s" rows="%3$d" class="large-text" placeholder="%4$s">%5$s</textarea>',
				esc_attr( $id ),
				esc_attr( $name ),
				$rows,
				esc_attr( isset( $field['placeholder'] ) ? $field['placeholder'] : '' ),
				esc_textarea( (string) $value )
			);
			break;

		case 'select':
			printf( '<select id="%1$s" name="%2$s" class="regular-text">', esc_attr( $id ), esc_attr( $name ) );
			foreach ( $options as $opt_value => $opt_label ) {
				printf(
					'<option value="%1$s" %2$s>%3$s</option>',
					esc_attr( $opt_value ),
					selected( (string) $value, (string) $opt_value, false ),
					esc_html( $opt_label )
				);
			}
			echo '</select>';
			break;

		case 'image':
			$url = $value ? wp_get_attachment_image_url( (int) $value, 'medium' ) : '';
			printf(
				'<span class="bp-image-field" data-title="%6$s" data-button="%7$s">
					<input type="hidden" id="%1$s" name="%2$s" value="%3$s" class="bp-image-id" />
					<img src="%4$s" class="bp-image-preview" alt="" style="max-width:260px;border-radius:8px;display:%5$s;margin-bottom:6px" />
					<button type="button" class="button bp-image-select">%6$s</button>
					<button type="button" class="button bp-image-clear" style="display:%8$s">%9$s</button>
				</span>',
				esc_attr( $id ),
				esc_attr( $name ),
				esc_attr( (string) $value ),
				esc_url( $url ? $url : '' ),
				$url ? 'block' : 'none',
				esc_html__( 'Select file', 'brickpoint' ),
				esc_html__( 'Choose file', 'brickpoint' ),
				$value ? 'inline-block' : 'none',
				esc_html__( 'Remove', 'brickpoint' )
			);
			break;

		case 'gallery':
			$ids = brickpoint_sanitize_id_list( $value );
			printf(
				'<span class="bp-gallery-field">
					<input type="hidden" id="%1$s" name="%2$s" value="%3$s" class="bp-gallery-ids" />
					<span class="bp-gallery-preview" style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:8px">',
				esc_attr( $id ),
				esc_attr( $name ),
				esc_attr( $ids )
			);

			foreach ( array_filter( array_map( 'absint', explode( ',', (string) $ids ) ) ) as $image_id ) {
				$thumb = wp_get_attachment_image_url( $image_id, 'thumbnail' );

				if ( $thumb ) {
					printf(
						'<span class="bp-gallery-item" data-id="%1$d"><img src="%2$s" alt="" style="width:80px;height:80px;object-fit:cover;border-radius:6px;display:block" /><button type="button" class="button-link bp-gallery-remove" style="color:#b32d2e">%3$s</button></span>',
						(int) $image_id,
						esc_url( $thumb ),
						esc_html__( 'Remove', 'brickpoint' )
					);
				}
			}

			printf(
					'</span>
					<button type="button" class="button bp-gallery-select">%1$s</button>
					<button type="button" class="button-link bp-gallery-clear">%2$s</button>
				</span>',
				esc_html__( 'Add images', 'brickpoint' ),
				esc_html__( 'Clear gallery', 'brickpoint' )
			);
			break;

		case 'post':
			$post_type = isset( $field['post_type'] ) ? $field['post_type'] : 'page';
			$items     = get_posts(
				array(
					'post_type'      => $post_type,
					'posts_per_page' => 200,
					'orderby'        => 'title',
					'order'          => 'ASC',
					'post_status'    => array( 'publish', 'draft' ),
				)
			);

			printf( '<select id="%1$s" name="%2$s" class="regular-text">', esc_attr( $id ), esc_attr( $name ) );
			printf( '<option value="">%s</option>', esc_html__( '— None —', 'brickpoint' ) );

			foreach ( $items as $item ) {
				printf(
					'<option value="%1$d" %2$s>%3$s</option>',
					(int) $item->ID,
					selected( (int) $value, $item->ID, false ),
					esc_html( $item->post_title )
				);
			}

			echo '</select>';
			break;

		case 'posts':
			$post_type = isset( $field['post_type'] ) ? $field['post_type'] : 'bp_product';
			$items     = get_posts(
				array(
					'post_type'      => $post_type,
					'posts_per_page' => 200,
					'orderby'        => 'title',
					'order'          => 'ASC',
					'post_status'    => array( 'publish', 'draft' ),
				)
			);
			$selected  = array_filter( array_map( 'absint', explode( ',', (string) brickpoint_sanitize_id_list( $value ) ) ) );

			printf(
				'<select id="%1$s" name="%2$s[]" class="regular-text" multiple size="%3$d">',
				esc_attr( $id ),
				esc_attr( $name ),
				min( 8, max( 4, count( $items ) ) )
			);

			foreach ( $items as $item ) {
				printf(
					'<option value="%1$d" %2$s>%3$s</option>',
					(int) $item->ID,
					in_array( (int) $item->ID, $selected, true ) ? 'selected="selected"' : '',
					esc_html( $item->post_title )
				);
			}

			echo '</select>';
			break;

		case 'number':
			printf(
				'<input type="number" id="%1$s" name="%2$s" value="%3$s" class="small-text" step="%4$s" />',
				esc_attr( $id ),
				esc_attr( $name ),
				esc_attr( (string) $value ),
				esc_attr( isset( $field['step'] ) ? $field['step'] : '1' )
			);
			break;

		default:
			printf(
				'<input type="text" id="%1$s" name="%2$s" value="%3$s" class="regular-text" placeholder="%4$s" />',
				esc_attr( $id ),
				esc_attr( $name ),
				esc_attr( (string) $value ),
				esc_attr( isset( $field['placeholder'] ) ? $field['placeholder'] : '' )
			);
			break;
	}

	if ( $help ) {
		echo '<br /><span class="description">' . esc_html( $help ) . '</span>';
	}

	echo '</p>';
}

/**
 * Nonce + capability guard for our meta boxes.
 *
 * @param int $post_id Post ID.
 * @return bool
 */
function brickpoint_can_save_meta( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return false;
	}

	if ( wp_is_post_revision( $post_id ) ) {
		return false;
	}

	if ( ! isset( $_POST['brickpoint_meta_nonce'] ) ) {
		return false;
	}

	$nonce = sanitize_text_field( wp_unslash( $_POST['brickpoint_meta_nonce'] ) );

	if ( ! wp_verify_nonce( $nonce, 'brickpoint_save_meta' ) ) {
		return false;
	}

	return current_user_can( 'edit_post', $post_id );
}

/**
 * Product details box.
 *
 * @param WP_Post $post Post.
 * @return void
 */
function brickpoint_render_product_box( $post ) {
	wp_nonce_field( 'brickpoint_save_meta', 'brickpoint_meta_nonce' );

	echo '<div class="bp-meta-grid">';

	brickpoint_field(
		'_bp_price',
		array(
			'label'       => __( 'Product price', 'brickpoint' ),
			'placeholder' => __( 'e.g. 45000 or "Ask for price"', 'brickpoint' ),
			'help'        => __( 'Free text. Leave empty to hide the price and show only the price label.', 'brickpoint' ),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_price_label',
		array(
			'label'       => __( 'Price label', 'brickpoint' ),
			'placeholder' => __( 'e.g. Starting from / Per 1000 pcs / Negotiable', 'brickpoint' ),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_unit',
		array(
			'label'       => __( 'Unit', 'brickpoint' ),
			'placeholder' => __( 'e.g. per 1000 bricks, per bag, per ton, per sq.ft', 'brickpoint' ),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_min_order',
		array(
			'label'       => __( 'Minimum order / quantity note', 'brickpoint' ),
			'placeholder' => __( 'e.g. Minimum 20,000 bricks per delivery', 'brickpoint' ),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_availability',
		array(
			'label'   => __( 'Availability status', 'brickpoint' ),
			'type'    => 'select',
			'options' => brickpoint_availability_options(),
			'help'    => __( 'Shown as a badge on cards and on the product page.', 'brickpoint' ),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_badge',
		array(
			'label'       => __( 'Product badge', 'brickpoint' ),
			'placeholder' => __( 'e.g. Best Seller, New Arrival, Premium', 'brickpoint' ),
			'help'        => __( 'Optional short label shown on the product image. Leave empty for none.', 'brickpoint' ),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_sku',
		array(
			'label'       => __( 'SKU / internal reference', 'brickpoint' ),
			'placeholder' => __( 'e.g. BP-BRK-SS7', 'brickpoint' ),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_delivery_area',
		array(
			'label'       => __( 'Delivery area (optional)', 'brickpoint' ),
			'placeholder' => __( 'e.g. Lahore & nearby districts - confirm on inquiry', 'brickpoint' ),
			'help'        => __( 'Only state what you can deliver. Leave empty to omit.', 'brickpoint' ),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_featured',
		array(
			'type'  => 'checkbox',
			'label' => __( 'Mark as featured product', 'brickpoint' ),
			'help'  => __( 'Featured products can be shown on the homepage and in "featured only" grids.', 'brickpoint' ),
		),
		$post->ID
	);

	echo '</div>';
}

/**
 * Product gallery / video / brochure box.
 *
 * @param WP_Post $post Post.
 * @return void
 */
function brickpoint_render_product_media_box( $post ) {
	echo '<div class="bp-meta-grid">';

	brickpoint_field(
		'_bp_gallery',
		array(
			'type' => 'gallery',
			'help' => __( 'Additional product images. The featured image is always shown first.', 'brickpoint' ),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_video',
		array(
			'type'      => 'post',
			'post_type' => 'bp_video',
			'label'     => __( 'Product video (from Videos)', 'brickpoint' ),
			'help'      => __( 'Pick an existing video. It renders under the gallery.', 'brickpoint' ),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_video_url',
		array(
			'label'       => __( 'or paste a video URL', 'brickpoint' ),
			'placeholder' => __( 'YouTube / Vimeo / .mp4 URL', 'brickpoint' ),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_brochure',
		array(
			'type'  => 'image',
			'label' => __( 'Brochure / PDF (media library)', 'brickpoint' ),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_brochure_url',
		array(
			'label'       => __( 'or brochure URL', 'brickpoint' ),
			'placeholder' => 'https://',
		),
		$post->ID
	);

	echo '</div>';
}

/**
 * Product specification repeater + features list.
 *
 * @param WP_Post $post Post.
 * @return void
 */
function brickpoint_render_product_specs_box( $post ) {
	$specs = get_post_meta( $post->ID, '_bp_specs', true );
	$specs = is_array( $specs ) ? $specs : array();

	if ( ! $specs ) {
		$specs = array(
			array(
				'label' => '',
				'value' => '',
			),
		);
	}

	echo '<p><strong>' . esc_html__( 'Product specifications', 'brickpoint' ) . '</strong><br />';
	echo '<span class="description">' . esc_html__( 'Useful for SS7 Bricks: Size, Color, Type, Strength, Usage, Availability, Delivery area. Only add specifications you can confirm.', 'brickpoint' ) . '</span></p>';

	echo '<div class="bp-repeater" data-next="' . esc_attr( count( $specs ) ) . '">';
	echo '<table class="widefat striped bp-repeater__table"><thead><tr><th>' . esc_html__( 'Label', 'brickpoint' ) . '</th><th>' . esc_html__( 'Value', 'brickpoint' ) . '</th><th style="width:36px"></th></tr></thead><tbody>';

	foreach ( $specs as $index => $row ) {
		brickpoint_repeater_row( $index, $row );
	}

	echo '</tbody></table>';
	echo '<p><button type="button" class="button bp-repeater-add">' . esc_html__( '+ Add specification', 'brickpoint' ) . '</button></p>';
	echo '<template class="bp-repeater-template">';
	brickpoint_repeater_row( '__INDEX__', array( 'label' => '', 'value' => '' ) );
	echo '</template>';
	echo '</div>';

	brickpoint_field(
		'_bp_features',
		array(
			'type'        => 'textarea',
			'label'       => __( 'Product features / highlights', 'brickpoint' ),
			'rows'        => 5,
			'placeholder' => __( "One feature per line, e.g.\nHand-moulded finish\nUniform size and edges\nConsistent strength", 'brickpoint' ),
			'help'        => __( 'Each line becomes a bullet point on the product page.', 'brickpoint' ),
		),
		$post->ID
	);

	echo '<p class="description">' . esc_html__( 'Use the main editor above for the full product description and the Excerpt field for the short description.', 'brickpoint' ) . '</p>';
}

/**
 * One repeater row.
 *
 * @param int|string $index Row index.
 * @param array      $row   Row data.
 * @return void
 */
function brickpoint_repeater_row( $index, $row ) {
	printf(
		'<tr class="bp-repeater__row">
			<td><input type="text" name="_bp_specs[%1$s][label]" value="%2$s" class="widefat" placeholder="%4$s" /></td>
			<td><input type="text" name="_bp_specs[%1$s][value]" value="%3$s" class="widefat" placeholder="%5$s" /></td>
			<td><button type="button" class="button-link bp-repeater-remove" aria-label="%6$s">&times;</button></td>
		</tr>',
		esc_attr( (string) $index ),
		esc_attr( isset( $row['label'] ) ? $row['label'] : '' ),
		esc_attr( isset( $row['value'] ) ? $row['value'] : '' ),
		esc_attr__( 'Size', 'brickpoint' ),
		esc_attr__( '9 x 4.5 x 3 inch', 'brickpoint' ),
		esc_attr__( 'Remove row', 'brickpoint' )
	);
}

/**
 * Product WhatsApp / related products box.
 *
 * @param WP_Post $post Post.
 * @return void
 */
function brickpoint_render_product_cta_box( $post ) {
	brickpoint_field(
		'_bp_whatsapp_msg',
		array(
			'type'        => 'textarea',
			'label'       => __( 'Custom WhatsApp inquiry message', 'brickpoint' ),
			'rows'        => 4,
			'placeholder' => __( 'Leave empty to auto-generate: product, category, price and unit are filled automatically.', 'brickpoint' ),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_related',
		array(
			'type'      => 'posts',
			'post_type' => 'bp_product',
			'label'     => __( 'Related products', 'brickpoint' ),
			'help'      => __( 'Ctrl/Cmd-click to select multiple. Leave empty to auto-pick from the same category.', 'brickpoint' ),
		),
		$post->ID
	);

	echo '<p class="description">' . esc_html__( 'The button always uses your WhatsApp number from Appearance → Customize → BrickPoint → Contact.', 'brickpoint' ) . '</p>';
}

/**
 * Video source box.
 *
 * @param WP_Post $post Post.
 * @return void
 */
function brickpoint_render_video_box( $post ) {
	echo '<div class="bp-meta-grid">';

	brickpoint_field(
		'_bp_video_source',
		array(
			'label'   => __( 'Video source type', 'brickpoint' ),
			'type'    => 'select',
			'options' => array(
				'youtube'  => __( 'YouTube URL', 'brickpoint' ),
				'vimeo'    => __( 'Vimeo URL', 'brickpoint' ),
				'self'     => __( 'Self-hosted MP4 (media library)', 'brickpoint' ),
				'external' => __( 'External video URL (mp4/webm)', 'brickpoint' ),
			),
			'help'    => __( 'Self-hosted files are best for short clips. Use YouTube/Vimeo for long videos to keep the site fast.', 'brickpoint' ),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_video_url',
		array(
			'label'       => __( 'Video URL', 'brickpoint' ),
			'placeholder' => 'https://www.youtube.com/watch?v=…  |  https://vimeo.com/…',
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_video_file',
		array(
			'type'  => 'image',
			'label' => __( 'Self-hosted video file (MP4/WebM)', 'brickpoint' ),
			'help'  => __( 'Upload or select the file from the media library.', 'brickpoint' ),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_video_duration',
		array(
			'label'       => __( 'Duration', 'brickpoint' ),
			'placeholder' => '1:45',
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_video_aspect',
		array(
			'label'   => __( 'Aspect ratio', 'brickpoint' ),
			'type'    => 'select',
			'options' => array(
				'16-9' => __( '16:9 (widescreen)', 'brickpoint' ),
				'4-3'  => __( '4:3', 'brickpoint' ),
				'1-1'  => __( '1:1 (square - Reels/TikTok)', 'brickpoint' ),
				'9-16' => __( '9:16 (vertical - Reels/Shorts)', 'brickpoint' ),
			),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_video_captions',
		array(
			'label'       => __( 'Captions / subtitles (.vtt)', 'brickpoint' ),
			'placeholder' => 'https://…/captions.vtt',
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_video_order',
		array(
			'label' => __( 'Display order', 'brickpoint' ),
			'type'  => 'number',
			'help'  => __( 'Lower numbers appear first in custom-ordered grids.', 'brickpoint' ),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_featured',
		array(
			'type'  => 'checkbox',
			'label' => __( 'Featured video', 'brickpoint' ),
		),
		$post->ID
	);

	echo '</div>';
}

/**
 * Video relations box.
 *
 * @param WP_Post $post Post.
 * @return void
 */
function brickpoint_render_video_relations_box( $post ) {
	brickpoint_field( '_bp_related_products', array( 'type' => 'posts', 'post_type' => 'bp_product', 'label' => __( 'Related products', 'brickpoint' ) ), $post->ID );
	brickpoint_field( '_bp_related_projects', array( 'type' => 'posts', 'post_type' => 'bp_project', 'label' => __( 'Related projects', 'brickpoint' ) ), $post->ID );
	brickpoint_field( '_bp_related_locations', array( 'type' => 'posts', 'post_type' => 'bp_location', 'label' => __( 'Related locations', 'brickpoint' ) ), $post->ID );
}

/**
 * Project details box.
 *
 * @param WP_Post $post Post.
 * @return void
 */
function brickpoint_render_project_box( $post ) {
	wp_nonce_field( 'brickpoint_save_meta', 'brickpoint_meta_nonce' );

	echo '<div class="bp-meta-grid">';

	brickpoint_field(
		'_bp_project_location',
		array(
			'label'       => __( 'Location / area', 'brickpoint' ),
			'placeholder' => __( 'e.g. DHA Lahore (only if verified)', 'brickpoint' ),
			'help'        => __( 'Leave empty rather than guessing. Never claim a society or developer you did not supply.', 'brickpoint' ),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_project_status',
		array(
			'label'   => __( 'Completion status', 'brickpoint' ),
			'type'    => 'select',
			'options' => array(
				''             => __( '— Not set —', 'brickpoint' ),
				'planning'     => __( 'Planning / concept', 'brickpoint' ),
				'ongoing'      => __( 'Ongoing', 'brickpoint' ),
				'completed'    => __( 'Completed', 'brickpoint' ),
				'reference'    => __( 'Reference visual', 'brickpoint' ),
			),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_project_scope',
		array(
			'label'       => __( 'Scope / materials supplied', 'brickpoint' ),
			'placeholder' => __( 'e.g. Bricks, cement and crush for grey structure', 'brickpoint' ),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_project_year',
		array(
			'label'       => __( 'Year (optional)', 'brickpoint' ),
			'placeholder' => '2026',
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_project_gallery',
		array(
			'type'  => 'gallery',
			'label' => __( 'Project gallery', 'brickpoint' ),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_project_video',
		array(
			'type'      => 'post',
			'post_type' => 'bp_video',
			'label'     => __( 'Project video', 'brickpoint' ),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_project_video_url',
		array(
			'label' => __( 'or video URL', 'brickpoint' ),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_project_products',
		array(
			'type'      => 'posts',
			'post_type' => 'bp_product',
			'label'     => __( 'Related products', 'brickpoint' ),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_illustrative',
		array(
			'type'  => 'checkbox',
			'label' => __( 'This is an illustrative / inspiration visual', 'brickpoint' ),
			'help'  => __( 'When enabled the card and page show the label “Illustrative construction reference”.', 'brickpoint' ),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_project_disclaimer',
		array(
			'label'       => __( 'Extra disclaimer line (optional)', 'brickpoint' ),
			'placeholder' => __( 'e.g. Concept visual - not a completed BrickPoint supply project', 'brickpoint' ),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_featured',
		array(
			'type'  => 'checkbox',
			'label' => __( 'Featured project', 'brickpoint' ),
		),
		$post->ID
	);

	echo '</div>';
}

/**
 * Location details box.
 *
 * @param WP_Post $post Post.
 * @return void
 */
function brickpoint_render_location_box( $post ) {
	wp_nonce_field( 'brickpoint_save_meta', 'brickpoint_meta_nonce' );

	echo '<div class="bp-meta-grid">';

	brickpoint_field(
		'_bp_location_company',
		array(
			'label'       => __( 'Company', 'brickpoint' ),
			'placeholder' => __( 'Masha Allah Bricks Company', 'brickpoint' ),
			'help'        => __( 'Masha Allah Bricks Company • Fine Bricks Company • SS7 Bricks', 'brickpoint' ),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_location_address',
		array(
			'type'        => 'textarea',
			'label'       => __( 'Address', 'brickpoint' ),
			'placeholder' => __( 'Village / area, tehsil, district', 'brickpoint' ),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_location_map',
		array(
			'label'       => __( 'Google Maps link', 'brickpoint' ),
			'placeholder' => 'https://maps.app.goo.gl/…',
			'help'        => __( 'Paste the real Google Maps share link. The map button only appears when this is filled.', 'brickpoint' ),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_location_directions',
		array(
			'label'       => __( 'Get Directions link (optional)', 'brickpoint' ),
			'placeholder' => 'https://www.google.com/maps/dir/?api=1&destination=…',
			'help'        => __( 'Leave empty and the Maps link is used for directions too.', 'brickpoint' ),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_location_coords',
		array(
			'label'       => __( 'Coordinates (optional)', 'brickpoint' ),
			'placeholder' => '31.2328, 74.3169424',
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_location_phone',
		array(
			'label'       => __( 'Phone number (optional)', 'brickpoint' ),
			'placeholder' => '03152850818',
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_location_hours',
		array(
			'label'       => __( 'Opening hours (optional)', 'brickpoint' ),
			'placeholder' => __( 'Mon–Sat, 9:00 am – 7:00 pm', 'brickpoint' ),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_location_video',
		array(
			'type'      => 'post',
			'post_type' => 'bp_video',
			'label'     => __( 'Location video', 'brickpoint' ),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_location_video_url',
		array(
			'label' => __( 'or video URL', 'brickpoint' ),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_location_products',
		array(
			'type'      => 'posts',
			'post_type' => 'bp_product',
			'label'     => __( 'Products available here', 'brickpoint' ),
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_location_order',
		array(
			'label' => __( 'Display order', 'brickpoint' ),
			'type'  => 'number',
		),
		$post->ID
	);

	brickpoint_field(
		'_bp_featured',
		array(
			'type'  => 'checkbox',
			'label' => __( 'Featured location', 'brickpoint' ),
		),
		$post->ID
	);

	echo '</div>';
}

/**
 * Availability options, filterable.
 *
 * @return array<string,string>
 */
function brickpoint_availability_options() {
	return apply_filters(
		'brickpoint_availability_options',
		array(
			''            => __( '— Not set —', 'brickpoint' ),
			'in-stock'    => __( 'In Stock', 'brickpoint' ),
			'limited'     => __( 'Limited Stock', 'brickpoint' ),
			'made-to-order' => __( 'Made to Order', 'brickpoint' ),
			'on-request'  => __( 'Available on Request', 'brickpoint' ),
			'out-of-stock' => __( 'Out of Stock', 'brickpoint' ),
		)
	);
}

/**
 * Save meta boxes.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 * @return void
 */
function brickpoint_save_meta( $post_id, $post = null ) {
	if ( ! brickpoint_can_save_meta( $post_id ) ) {
		return;
	}

	$schema = brickpoint_meta_schema();

	if ( ! isset( $schema[ $post->post_type ] ) ) {
		return;
	}

	$registered = $schema[ $post->post_type ];

	foreach ( $registered as $key => $args ) {
		$is_multi   = in_array( $key, array( '_bp_related_products', '_bp_related_projects', '_bp_related_locations', '_bp_project_products', '_bp_location_products' ), true );
		$is_repeater = ( '_bp_specs' === $key );
		$is_list    = in_array( $key, array( '_bp_gallery', '_bp_features', '_bp_related', '_bp_project_gallery' ), true );
		$is_checkbox = in_array( $key, array( '_bp_featured', '_bp_illustrative' ), true );

		if ( $is_checkbox ) {
			update_post_meta( $post_id, $key, isset( $_POST[ $key ] ) ? '1' : '' );
			continue;
		}

		if ( $is_multi ) {
			if ( ! isset( $_POST[ $key ] ) ) {
				delete_post_meta( $post_id, $key );
				continue;
			}

			$values = array_map( 'absint', (array) wp_unslash( $_POST[ $key ] ) );
			update_post_meta( $post_id, $key, brickpoint_sanitize_id_list( array_filter( $values ) ) );
			continue;
		}

		if ( ! isset( $_POST[ $key ] ) ) {
			continue;
		}

		$raw = wp_unslash( $_POST[ $key ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitised through the registered callback below.

		if ( $is_repeater ) {
			$value = brickpoint_sanitize_repeater( $raw );
			update_post_meta( $post_id, $key, $value );
			continue;
		}

		if ( $is_list ) {
			update_post_meta( $post_id, $key, brickpoint_sanitize_id_list( $raw ) );
			continue;
		}

		$sanitize = isset( $args['sanitize_callback'] ) ? $args['sanitize_callback'] : 'sanitize_text_field';
		$value    = call_user_func( $sanitize, $raw );

		if ( '' === $value || '0' === $value ) {
			delete_post_meta( $post_id, $key );
		} else {
			update_post_meta( $post_id, $key, $value );
		}
	}
}
add_action( 'save_post', 'brickpoint_save_meta', 10, 2 );

/**
 * The _bp_brochure and _bp_video_file fields reuse the image picker markup,
 * so they arrive as attachment IDs. Keep them integers.
 *
 * @param mixed  $value     Value.
 * @param int    $object_id Object ID.
 * @param string $meta_key  Meta key.
 * @return mixed
 */
function brickpoint_sanitize_media_meta( $value, $object_id, $meta_key ) {
	unset( $object_id );

	if ( in_array( $meta_key, array( '_bp_brochure', '_bp_video_file', '_bp_video', '_bp_project_video', '_bp_location_video' ), true ) ) {
		return $value ? absint( $value ) : '';
	}

	if ( in_array( $meta_key, array( '_bp_video_url', '_bp_brochure_url', '_bp_project_video_url', '_bp_location_video_url', '_bp_location_map', '_bp_location_directions', '_bp_video_captions' ), true ) ) {
		return $value ? esc_url_raw( $value ) : '';
	}

	if ( in_array( $meta_key, array( '_bp_whatsapp_msg' ), true ) ) {
		return sanitize_textarea_field( $value );
	}

	return $value;
}
add_filter( 'sanitize_post_meta', 'brickpoint_sanitize_media_meta', 10, 3 );

/**
 * Explain where to edit the description fields, right on the product screen.
 *
 * @param int $post_id Post ID.
 * @return void
 */
function brickpoint_product_intro_notice( $post_id ) {
	$post = get_post( $post_id );

	if ( ! $post instanceof WP_Post || 'bp_product' !== $post->post_type ) {
		return;
	}

	echo '<div class="bp-admin-hint">' . wp_kses_post(
		sprintf(
			/* translators: %s: manage categories link. */
			__( 'Use the main editor for the <strong>full description</strong>, the Excerpt box for the <strong>short description</strong>, and the Featured Image box for the <strong>main product image</strong>. Settings for specifications, video and WhatsApp are below. Need a bulk starting point? Open <a href="%s">BrickPoint → Setup</a>.', 'brickpoint' ),
			esc_url( admin_url( 'admin.php?page=brickpoint' ) )
		)
	) . '</div>';
}
add_action( 'edit_form_after_title', 'brickpoint_product_intro_notice' );
