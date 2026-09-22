<?php
/**
 * Contact / quotation form. Works without WooCommerce or any form plugin.
 *
 * Security: nonce, honeypot, simple rate limiting, sanitisation on input and
 * escaping on output. Submissions are stored as private `bp_inquiry` posts
 * (with an email notification when an address is configured).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Form field definitions - filterable so the owner can add context fields.
 *
 * @return array<string,array<string,mixed>>
 */
function brickpoint_contact_fields() {
	$fields = array(
		'name'     => array(
			'label'    => __( 'Your name', 'brickpoint' ),
			'type'     => 'text',
			'required' => true,
			'width'    => 'half',
		),
		'phone'    => array(
			'label'    => __( 'Phone / WhatsApp', 'brickpoint' ),
			'type'     => 'tel',
			'required' => true,
			'width'    => 'half',
		),
		'email'    => array(
			'label'    => __( 'Email (optional)', 'brickpoint' ),
			'type'     => 'email',
			'required' => false,
			'width'    => 'half',
		),
		'company'  => array(
			'label'    => __( 'Company (optional)', 'brickpoint' ),
			'type'     => 'text',
			'required' => false,
			'width'    => 'half',
		),
		'material' => array(
			'label'    => __( 'Required material', 'brickpoint' ),
			'type'     => 'text',
			'required' => true,
			'width'    => 'half',
			'placeholder' => __( 'e.g. SS7 bricks, cement, crush', 'brickpoint' ),
		),
		'quantity' => array(
			'label'    => __( 'Quantity', 'brickpoint' ),
			'type'     => 'text',
			'required' => false,
			'width'    => 'half',
			'placeholder' => __( 'e.g. 50,000 bricks / 200 bags', 'brickpoint' ),
		),
		'city'     => array(
			'label'    => __( 'Delivery location', 'brickpoint' ),
			'type'     => 'text',
			'required' => false,
			'width'    => 'half',
		),
		'message'  => array(
			'label'    => __( 'Message', 'brickpoint' ),
			'type'     => 'textarea',
			'required' => false,
			'width'    => 'full',
		),
	);

	/**
	 * Filter contact form fields.
	 *
	 * @param array $fields Fields.
	 */
	return apply_filters( 'brickpoint_contact_fields', $fields );
}

/**
 * Render the contact form.
 *
 * @param array $args heading, subtext, button, class, show_consent.
 * @return void
 */
function brickpoint_contact_form( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'heading'  => '',
			'subtext'  => '',
			'button'   => __( 'Send Inquiry', 'brickpoint' ),
			'class'    => '',
			'context'  => '',
			'hidden'   => array(),
		)
	);

	$fields        = brickpoint_contact_fields();
	$form_id       = 'bp-contact-form-' . wp_rand( 1000, 9999 );
	$current_url   = home_url( add_query_arg( array() ) );

	echo '<form class="bp-form bp-contact-form ' . esc_attr( $args['class'] ) . '" id="' . esc_attr( $form_id ) . '" method="post" action="' . esc_url( $current_url ) . '" data-bp-form novalidate>';

	wp_nonce_field( 'brickpoint_contact', 'brickpoint_contact_nonce' );

	echo '<input type="hidden" name="action" value="brickpoint_contact" />';
	echo '<input type="hidden" name="bp_context" value="' . esc_attr( $args['context'] ) . '" />';

	foreach ( (array) $args['hidden'] as $key => $value ) {
		printf( '<input type="hidden" name="%1$s" value="%2$s" />', esc_attr( $key ), esc_attr( $value ) );
	}

	// Honeypot.
	echo '<p class="bp-hp" aria-hidden="true"><label for="' . esc_attr( $form_id ) . '-website">' . esc_html__( 'Website', 'brickpoint' ) . '</label><input type="text" id="' . esc_attr( $form_id ) . '-website" name="bp_website" tabindex="-1" autocomplete="off" /></p>';

	if ( $args['heading'] ) {
		echo '<h3 class="bp-form__title">' . esc_html( $args['heading'] ) . '</h3>';
	}

	if ( $args['subtext'] ) {
		echo '<p class="bp-form__text">' . esc_html( $args['subtext'] ) . '</p>';
	}

	echo '<div class="bp-form__grid">';

	foreach ( $fields as $key => $field ) {
		$field_id = $form_id . '-' . $key;
		$required = ! empty( $field['required'] );

		printf(
			'<div class="bp-form__row bp-form__row--%1$s"><label for="%2$s">%3$s%4$s</label>',
			esc_attr( isset( $field['width'] ) ? $field['width'] : 'full' ),
			esc_attr( $field_id ),
			esc_html( $field['label'] ),
			$required ? ' <span class="bp-form__req" aria-hidden="true">*</span>' : ''
		);

		$common = sprintf(
			'id="%1$s" name="%2$s" %3$s placeholder="%4$s"',
			esc_attr( $field_id ),
			esc_attr( $key ),
			$required ? 'required aria-required="true"' : '',
			esc_attr( isset( $field['placeholder'] ) ? $field['placeholder'] : '' )
		);

		if ( 'textarea' === $field['type'] ) {
			printf( '<textarea %s rows="4"></textarea>', $common ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- attributes escaped above.
		} else {
			printf( '<input type="%1$s" %2$s />', esc_attr( $field['type'] ), $common ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- attributes escaped above.
		}

		echo '</div>';
	}

	echo '</div>';

	echo '<div class="bp-form__actions">';
	printf(
		'<button type="submit" class="bp-btn bp-btn--primary"><span class="bp-btn__label">%1$s</span>%2$s</button>',
		esc_html( $args['button'] ),
		brickpoint_icon( 'arrow-right', array( 'size' => 18 ) )
	);

	echo brickpoint_whatsapp_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside helper.
		array(
			'label'   => __( 'Or message us on WhatsApp', 'brickpoint' ),
			'message' => brickpoint_general_inquiry_message( $args['context'] ),
			'class'   => 'bp-btn bp-btn--whatsapp',
		)
	);

	echo '</div>';

	echo '<p class="bp-form__privacy">' . esc_html__( 'Your details are only used to respond to this inquiry.', 'brickpoint' ) . '</p>';
	echo '<p class="bp-form__status" role="status" aria-live="polite" data-bp-form-status></p>';
	echo '</form>';
}

/**
 * Handle a submitted inquiry (used by both the AJAX endpoint and no-JS POST).
 *
 * @param array $request        Raw request data ($_POST).
 * @param bool  $nonce_verified True when the caller (inc/ajax.php) has already
 *                              verified the AJAX nonce for this request.
 * @return array{success:bool,message:string}
 */
function brickpoint_process_inquiry( $request, $nonce_verified = false ) {
	$response = array(
		'success' => false,
		'message' => __( 'Something went wrong. Please try again or message us on WhatsApp.', 'brickpoint' ),
	);

	$nonce = isset( $request['brickpoint_contact_nonce'] ) ? sanitize_text_field( wp_unslash( $request['brickpoint_contact_nonce'] ) ) : '';

	if ( ! $nonce_verified && ! wp_verify_nonce( $nonce, 'brickpoint_contact' ) ) {
		$response['message'] = __( 'Your session expired. Please reload the page and try again.', 'brickpoint' );

		return $response;
	}

	// Honeypot: silently accept but do nothing.
	if ( ! empty( $request['bp_website'] ) ) {
		return array(
			'success' => true,
			'message' => __( 'Thank you - your inquiry has been sent.', 'brickpoint' ),
		);
	}

	// Very light rate limit: one submission per 20 seconds per IP.
	$ip_hash = 'bp_inq_' . md5( isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown' );

	if ( get_transient( $ip_hash ) ) {
		$response['message'] = __( 'You just sent a message. Please wait a moment before sending another.', 'brickpoint' );

		return $response;
	}

	$data = array();

	foreach ( array_keys( brickpoint_contact_fields() ) as $key ) {
		$data[ $key ] = isset( $request[ $key ] ) ? sanitize_textarea_field( wp_unslash( $request[ $key ] ) ) : '';
	}

	if ( '' === $data['name'] || '' === $data['phone'] || '' === $data['material'] ) {
		$response['message'] = __( 'Please fill in your name, phone number and required material.', 'brickpoint' );

		return $response;
	}

	if ( $data['email'] && ! is_email( $data['email'] ) ) {
		$response['message'] = __( 'Please check the email address you entered.', 'brickpoint' );

		return $response;
	}

	$context = isset( $request['bp_context'] ) ? sanitize_text_field( wp_unslash( $request['bp_context'] ) ) : '';

	$title = sprintf(
		/* translators: 1: customer name, 2: required material. */
		__( '%1$s – %2$s', 'brickpoint' ),
		$data['name'],
		$data['material']
	);

	$body = array();
	$body[] = 'Name: ' . $data['name'];
	$body[] = 'Phone: ' . $data['phone'];

	if ( $data['email'] ) {
		$body[] = 'Email: ' . $data['email'];
	}

	if ( $data['company'] ) {
		$body[] = 'Company: ' . $data['company'];
	}

	$body[] = 'Material: ' . $data['material'];

	if ( $data['quantity'] ) {
		$body[] = 'Quantity: ' . $data['quantity'];
	}

	if ( $data['city'] ) {
		$body[] = 'Delivery location: ' . $data['city'];
	}

	if ( $data['message'] ) {
		$body[] = '';
		$body[] = 'Message:';
		$body[] = $data['message'];
	}

	if ( $context ) {
		$body[] = '';
		$body[] = 'Page: ' . $context;
	}

	$inquiry_id = wp_insert_post(
		array(
			'post_type'    => 'bp_inquiry',
			'post_status'  => 'private',
			'post_title'   => wp_strip_all_tags( $title ),
			'post_content' => implode( "\n", $body ),
		),
		true
	);

	if ( is_wp_error( $inquiry_id ) ) {
		return $response;
	}

	// Store structured fields for easier reading in the admin.
	$map = array(
		'_bp_inquiry_name'     => $data['name'],
		'_bp_inquiry_phone'    => $data['phone'],
		'_bp_inquiry_email'    => $data['email'],
		'_bp_inquiry_company'  => $data['company'],
		'_bp_inquiry_material' => $data['material'],
		'_bp_inquiry_quantity' => $data['quantity'],
		'_bp_inquiry_city'     => $data['city'],
		'_bp_inquiry_source'   => $context ? $context : __( 'Website form', 'brickpoint' ),
		'_bp_inquiry_status'   => 'new',
	);

	foreach ( $map as $key => $value ) {
		update_post_meta( $inquiry_id, $key, $value );
	}

	set_transient( $ip_hash, 1, 20 );

	// Notify the owner if an email address is configured.
	$to = brickpoint_option( 'bp_email' );

	if ( $to && is_email( $to ) ) {
		wp_mail(
			$to,
			sprintf(
				/* translators: %s: inquiry title. */
				__( '[BrickPoint] New inquiry: %s', 'brickpoint' ),
				$title
			),
			implode( "\n", $body ) . "\n\n" . admin_url( 'edit.php?post_type=bp_inquiry' )
		);
	}

	/**
	 * Fires after a successful inquiry submission.
	 *
	 * @param int   $inquiry_id Inquiry post ID.
	 * @param array $data       Sanitised data.
	 */
	do_action( 'brickpoint_inquiry_created', $inquiry_id, $data );

	return array(
		'success' => true,
		'message' => __( 'Thank you - your inquiry has been received. Our team will contact you shortly. For an immediate response, message us on WhatsApp.', 'brickpoint' ),
	);
}

/**
 * No-JS fallback: handle the plain POST submit on the current page.
 *
 * @return void
 */
function brickpoint_handle_contact_post() {
	if ( ! isset( $_POST['action'] ) || 'brickpoint_contact' !== $_POST['action'] ) {
		return;
	}

	if ( is_admin() ) {
		return;
	}

	$result = brickpoint_process_inquiry( $_POST ); // phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified inside brickpoint_process_inquiry().

	// Redirect-with-flag so refreshes do not resubmit (POST/redirect/GET).
	$redirect = wp_get_referer() ? wp_get_referer() : home_url( '/' );
	$redirect = add_query_arg( 'bp_sent', $result['success'] ? '1' : '0', $redirect );

	wp_safe_redirect( $redirect );
	exit;
}
add_action( 'template_redirect', 'brickpoint_handle_contact_post' );

/**
 * Success/error notice after a no-JS submit.
 *
 * @return void
 */
function brickpoint_contact_notice() {
	if ( ! isset( $_GET['bp_sent'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- display only.
		return;
	}

	$ok = '1' === sanitize_text_field( wp_unslash( $_GET['bp_sent'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	printf(
		'<div class="bp-notice bp-notice--%1$s">%2$s</div>',
		$ok ? 'success' : 'error',
		esc_html(
			$ok
				? __( 'Thank you - your inquiry has been received.', 'brickpoint' )
				: __( 'We could not send that. Please check the required fields and try again.', 'brickpoint' )
		)
	);
}

/**
 * Admin columns for inquiries.
 *
 * @param array $columns Columns.
 * @return array
 */
function brickpoint_inquiry_columns( $columns ) {
	$new = array();

	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;

		if ( 'title' === $key ) {
			$new['bp_phone']    = __( 'Phone', 'brickpoint' );
			$new['bp_material'] = __( 'Material', 'brickpoint' );
			$new['bp_quantity'] = __( 'Quantity', 'brickpoint' );
			$new['bp_city']     = __( 'Location', 'brickpoint' );
		}
	}

	unset( $new['date'] );

	return $new;
}
add_filter( 'manage_bp_inquiry_posts_columns', 'brickpoint_inquiry_columns' );

/**
 * Inquiry column content.
 *
 * @param string $column  Column.
 * @param int    $post_id Post ID.
 * @return void
 */
function brickpoint_inquiry_column_content( $column, $post_id ) {
	$map = array(
		'bp_phone'    => '_bp_inquiry_phone',
		'bp_material' => '_bp_inquiry_material',
		'bp_quantity' => '_bp_inquiry_quantity',
		'bp_city'     => '_bp_inquiry_city',
	);

	if ( ! isset( $map[ $column ] ) ) {
		return;
	}

	$value = brickpoint_meta( $post_id, $map[ $column ] );

	if ( 'bp_phone' === $column && $value ) {
		printf(
			'<a href="%1$s">%2$s</a><br /><a href="%3$s" target="_blank" rel="noopener">%4$s</a>',
			esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $value ) ),
			esc_html( $value ),
			esc_url( brickpoint_whatsapp_url( brickpoint_general_inquiry_message( get_the_title( $post_id ) ), $value ) ),
			esc_html__( 'WhatsApp', 'brickpoint' )
		);

		return;
	}

	echo $value ? esc_html( $value ) : '&mdash;';
}
add_action( 'manage_bp_inquiry_posts_custom_column', 'brickpoint_inquiry_column_content', 10, 2 );

/**
 * Read-only meta box showing the full inquiry.
 *
 * @return void
 */
function brickpoint_inquiry_metabox() {
	add_meta_box(
		'bp_inquiry_details',
		__( 'Inquiry details', 'brickpoint' ),
		function ( $post ) {
			$rows = array(
				__( 'Name', 'brickpoint' )     => '_bp_inquiry_name',
				__( 'Phone', 'brickpoint' )    => '_bp_inquiry_phone',
				__( 'Email', 'brickpoint' )    => '_bp_inquiry_email',
				__( 'Company', 'brickpoint' )  => '_bp_inquiry_company',
				__( 'Material', 'brickpoint' ) => '_bp_inquiry_material',
				__( 'Quantity', 'brickpoint' ) => '_bp_inquiry_quantity',
				__( 'Location', 'brickpoint' ) => '_bp_inquiry_city',
				__( 'Source page', 'brickpoint' ) => '_bp_inquiry_source',
			);

			echo '<table class="widefat striped">';

			foreach ( $rows as $label => $key ) {
				$value = brickpoint_meta( $post->ID, $key );

				printf(
					'<tr><th style="width:140px">%1$s</th><td>%2$s</td></tr>',
					esc_html( $label ),
					esc_html( $value ? $value : '—' )
				);
			}

			echo '</table>';
		},
		'bp_inquiry',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'brickpoint_inquiry_metabox' );

/**
 * Remove the "Add New" flows for inquiries - they are only created by the form.
 *
 * @return void
 */
function brickpoint_inquiry_admin_tweaks() {
	global $pagenow;

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

	if ( ! $screen || 'bp_inquiry' !== $screen->post_type ) {
		return;
	}

	echo '<style>#wpbody-content .page-title-action{display:none}</style>';

	if ( 'post-new.php' === $pagenow ) {
		wp_safe_redirect( admin_url( 'edit.php?post_type=bp_inquiry' ) );
		exit;
	}
}
add_action( 'admin_head', 'brickpoint_inquiry_admin_tweaks' );
