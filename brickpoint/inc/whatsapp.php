<?php
/**
 * WhatsApp inquiry and quotation system.
 *
 * No cart, no checkout - every order path goes through a prefilled WhatsApp
 * deep link (or the on-site quotation form).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Build a wa.me link from a message.
 *
 * @param string $message Plain text message.
 * @param string $number  Optional override number (digits only).
 * @return string
 */
function brickpoint_whatsapp_url( $message = '', $number = '' ) {
	$number  = $number ? preg_replace( '/[^0-9]/', '', $number ) : brickpoint_whatsapp_number();
	$message = $message ? $message : brickpoint_option( 'bp_whatsapp_default' );

	if ( '' === $number ) {
		return '';
	}

	$url = 'https://wa.me/' . $number;

	if ( $message ) {
		// add_query_arg escapes properly; wa.me expects a plain urlencoded text param.
		$url = add_query_arg( 'text', $message, $url );
	}

	/**
	 * Filter the final WhatsApp URL.
	 *
	 * @param string $url     WhatsApp URL.
	 * @param string $message Message text.
	 * @param string $number  Number.
	 */
	return apply_filters( 'brickpoint_whatsapp_url', $url, $message, $number );
}

/**
 * Compose the standard product inquiry message.
 *
 * @param int    $product_id Product ID.
 * @param string $quantity   Optional quantity.
 * @param string $extra      Optional customer note.
 * @return string
 */
function brickpoint_product_inquiry_message( $product_id, $quantity = '', $extra = '' ) {
	$product_id = (int) $product_id;

	// A custom message per product always wins.
	$custom = get_post_meta( $product_id, '_bp_whatsapp_msg', true );

	if ( $custom ) {
		$message = $custom;
	} else {
		$name     = get_the_title( $product_id );
		$terms    = get_the_terms( $product_id, 'bp_product_category' );
		$category = ( $terms && ! is_wp_error( $terms ) ) ? implode( ', ', wp_list_pluck( $terms, 'name' ) ) : '';

		$price = brickpoint_format_price( get_post_meta( $product_id, '_bp_price', true ) );
		$label = get_post_meta( $product_id, '_bp_price_label', true );
		$unit  = get_post_meta( $product_id, '_bp_unit', true );
		$sku   = get_post_meta( $product_id, '_bp_sku', true );

		$lines   = array();
		$lines[] = brickpoint_option( 'bp_whatsapp_greeting' );
		$lines[] = '';
		$lines[] = __( 'I am interested in the following product:', 'brickpoint' );
		$lines[] = '';
		$lines[] = 'Product: ' . $name;

		if ( $category ) {
			$lines[] = 'Category: ' . $category;
		}

		if ( $price || $label ) {
			$lines[] = 'Price: ' . trim( $label . ( $label && $price ? ' ' : '' ) . $price );
		}

		if ( $unit ) {
			$lines[] = 'Unit: ' . $unit;
		}

		if ( $sku ) {
			$lines[] = 'Reference: ' . $sku;
		}

		$message = implode( "\n", $lines );
	}

	$tail = array();

	if ( $quantity ) {
		$tail[] = '';
		$tail[] = 'Required quantity: ' . $quantity;
	}

	if ( $extra ) {
		$tail[] = 'Note: ' . $extra;
	}

	$closing = brickpoint_option( 'bp_whatsapp_closing' );

	if ( $closing ) {
		$tail[] = '';
		$tail[] = $closing;
	}

	$message .= implode( "\n", $tail );

	/**
	 * Filter the composed WhatsApp product message.
	 *
	 * @param string $message    Message.
	 * @param int    $product_id Product ID.
	 * @param string $quantity   Quantity.
	 */
	return apply_filters( 'brickpoint_product_inquiry_message', $message, $product_id, $quantity );
}

/**
 * Compose a taxonomy-term inquiry message (category, video category…).
 *
 * @param WP_Term $term Term.
 * @return string
 */
function brickpoint_term_inquiry_message( $term ) {
	$custom = get_term_meta( $term->term_id, '_bp_term_whatsapp', true );

	if ( $custom ) {
		return $custom;
	}

	$taxonomy_labels = array(
		'bp_product_category' => __( 'Product category', 'brickpoint' ),
		'bp_video_category'   => __( 'Video category', 'brickpoint' ),
		'bp_project_category' => __( 'Project category', 'brickpoint' ),
	);

	$label = isset( $taxonomy_labels[ $term->taxonomy ] ) ? $taxonomy_labels[ $term->taxonomy ] : __( 'Category', 'brickpoint' );

	$lines   = array();
	$lines[] = brickpoint_option( 'bp_whatsapp_greeting' );
	$lines[] = '';
	$lines[] = sprintf(
		/* translators: 1: label, 2: term name. */
		__( 'I would like to inquire about your %1$s: %2$s', 'brickpoint' ),
		$label,
		$term->name
	);
	$lines[] = '';
	$lines[] = brickpoint_option( 'bp_whatsapp_closing' );

	return implode( "\n", $lines );
}

/**
 * Compose a generic page/general inquiry message.
 *
 * @param string $context Optional context label (page title etc.).
 * @return string
 */
function brickpoint_general_inquiry_message( $context = '' ) {
	if ( ! $context ) {
		return brickpoint_option( 'bp_whatsapp_default' );
	}

	$lines   = array();
	$lines[] = brickpoint_option( 'bp_whatsapp_greeting' );
	$lines[] = '';
	$lines[] = sprintf(
		/* translators: %s: context, e.g. page or product name. */
		__( 'I am contacting you about: %s', 'brickpoint' ),
		$context
	);
	$lines[] = '';
	$lines[] = brickpoint_option( 'bp_whatsapp_closing' );

	return implode( "\n", $lines );
}

/**
 * Render the standard WhatsApp button markup.
 *
 * @param array $args Button args.
 * @return string
 */
function brickpoint_whatsapp_button( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'label'   => __( 'Order on WhatsApp', 'brickpoint' ),
			'message' => '',
			'number'  => '',
			'class'   => 'bp-btn bp-btn--whatsapp',
			'style'   => 'default', // default | ghost | solid-outline.
			'icon'    => true,
			'target'  => '_blank',
			'product' => 0,
			'quantity_field' => false,
		)
	);

	$url = brickpoint_whatsapp_url( $args['message'], $args['number'] );

	if ( ! $url ) {
		return '';
	}

	$classes = $args['class'] . ( 'default' === $args['style'] ? '' : ' bp-btn--' . $args['style'] );

	$html = sprintf(
		'<a class="%1$s" href="%2$s" target="%3$s" rel="noopener noreferrer" data-bp-whatsapp="1"%4$s>%5$s<span class="bp-btn__label">%6$s</span></a>',
		esc_attr( $classes ),
		esc_url( $url ),
		esc_attr( $args['target'] ),
		$args['product'] ? ' data-product="' . (int) $args['product'] . '"' : '',
		$args['icon'] ? brickpoint_icon( 'whatsapp', array( 'size' => 20 ) ) : '',
		esc_html( $args['label'] )
	);

	return $html;
}

/**
 * Phone button (tel: link).
 *
 * @param array $args Args.
 * @return string
 */
function brickpoint_phone_button( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'label' => __( 'Call Now', 'brickpoint' ),
			'class' => 'bp-btn bp-btn--ghost',
			'number' => '',
			'icon'  => true,
		)
	);

	$number = $args['number'] ? $args['number'] : brickpoint_phone_raw();
	$number = preg_replace( '/[^0-9+]/', '', (string) $number );

	if ( ! $number ) {
		return '';
	}

	return sprintf(
		'<a class="%1$s" href="tel:%2$s">%3$s<span class="bp-btn__label">%4$s</span></a>',
		esc_attr( $args['class'] ),
		esc_attr( $number ),
		$args['icon'] ? brickpoint_icon( 'phone', array( 'size' => 18 ) ) : '',
		esc_html( $args['label'] )
	);
}

/**
 * Sticky mobile action bar: call + WhatsApp + quote.
 *
 * @return void
 */
function brickpoint_mobile_action_bar() {
	if ( ! apply_filters( 'brickpoint_show_mobile_bar', true ) ) {
		return;
	}

	$product_id = is_singular( 'bp_product' ) ? get_the_ID() : 0;
	$message    = $product_id ? brickpoint_product_inquiry_message( $product_id ) : brickpoint_general_inquiry_message();
	$quote_url  = brickpoint_page_url( 'contact' );

	echo '<div class="bp-mobile-bar" aria-label="' . esc_attr__( 'Quick contact', 'brickpoint' ) . '">';

	brickpoint_phone_button(
		array(
			'label' => __( 'Call', 'brickpoint' ),
			'class' => 'bp-mobile-bar__btn',
			'icon'  => true,
		)
	);

	echo brickpoint_whatsapp_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside the helper.
		array(
			'label'   => __( 'WhatsApp', 'brickpoint' ),
			'message' => $message,
			'class'   => 'bp-mobile-bar__btn bp-mobile-bar__btn--whatsapp',
		)
	);

	if ( $quote_url ) {
		printf(
			'<a class="bp-mobile-bar__btn" href="%1$s">%2$s<span>%3$s</span></a>',
			esc_url( $quote_url ),
			brickpoint_icon( 'quote', array( 'size' => 18 ) ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			esc_html__( 'Get Quote', 'brickpoint' )
		);
	}

	echo '</div>';
}
add_action( 'wp_footer', 'brickpoint_mobile_action_bar' );

/**
 * Floating WhatsApp button (desktop + mobile).
 *
 * @return void
 */
function brickpoint_floating_whatsapp() {
	if ( ! apply_filters( 'brickpoint_show_floating_whatsapp', true ) ) {
		return;
	}

	$product_id = is_singular( 'bp_product' ) ? get_the_ID() : 0;
	$message    = $product_id ? brickpoint_product_inquiry_message( $product_id ) : brickpoint_general_inquiry_message();

	printf(
		'<a class="bp-float-wa" href="%1$s" target="_blank" rel="noopener noreferrer" aria-label="%2$s">%3$s</a>',
		esc_url( brickpoint_whatsapp_url( $message ) ),
		esc_attr__( 'Chat on WhatsApp', 'brickpoint' ),
		brickpoint_icon( 'whatsapp', array( 'size' => 26 ) ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	);
}
add_action( 'wp_footer', 'brickpoint_floating_whatsapp', 20 );

/**
 * Share bar (WhatsApp, Facebook, X, copy link).
 *
 * @param int    $post_id Post ID.
 * @param string $label   Optional label.
 * @return void
 */
function brickpoint_share_bar( $post_id = 0, $label = '' ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();
	$url     = get_permalink( $post_id );
	$title   = get_the_title( $post_id );

	if ( ! $url ) {
		return;
	}

	$label = $label ? $label : __( 'Share', 'brickpoint' );

	echo '<div class="bp-share">';
	echo '<span class="bp-share__label">' . esc_html( $label ) . '</span>';

	printf(
		'<a class="bp-share__item" href="%1$s" target="_blank" rel="noopener noreferrer" aria-label="%2$s">%3$s</a>',
		esc_url( brickpoint_whatsapp_url( $title . ' - ' . $url ) ),
		esc_attr__( 'Share on WhatsApp', 'brickpoint' ),
		brickpoint_icon( 'whatsapp', array( 'size' => 18 ) ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	);

	printf(
		'<a class="bp-share__item" href="%1$s" target="_blank" rel="noopener noreferrer" aria-label="%2$s">%3$s</a>',
		esc_url( 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode( $url ) ),
		esc_attr__( 'Share on Facebook', 'brickpoint' ),
		brickpoint_icon( 'facebook', array( 'size' => 18 ) ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	);

	printf(
		'<a class="bp-share__item" href="%1$s" target="_blank" rel="noopener noreferrer" aria-label="%2$s">%3$s</a>',
		esc_url( 'https://twitter.com/intent/tweet?url=' . rawurlencode( $url ) . '&text=' . rawurlencode( $title ) ),
		esc_attr__( 'Share on X', 'brickpoint' ),
		brickpoint_icon( 'twitter', array( 'size' => 18 ) ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	);

	printf(
		'<button type="button" class="bp-share__item bp-copy-link" data-url="%1$s" aria-label="%2$s">%3$s</button>',
		esc_url( $url ),
		esc_attr__( 'Copy link', 'brickpoint' ),
		brickpoint_icon( 'share', array( 'size' => 18 ) ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	);

	echo '</div>';
}
