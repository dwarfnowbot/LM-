<?php
/**
 * 404 - helpful, not a dead end.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="bp-404">
	<div class="bp-container bp-404__inner">
		<p class="bp-eyebrow"><?php esc_html_e( 'Error 404', 'brickpoint' ); ?></p>
		<h1 class="bp-404__title"><?php esc_html_e( 'This page has moved or no longer exists', 'brickpoint' ); ?></h1>
		<p class="bp-404__text"><?php esc_html_e( 'The link may be outdated. Search for a product, or start from one of the sections below.', 'brickpoint' ); ?></p>

		<div class="bp-404__search"><?php get_search_form(); ?></div>

		<div class="bp-404__actions">
			<?php
			$products_url  = brickpoint_page_url( 'products' );
			$contact_url   = brickpoint_page_url( 'contact' );
			$videos_url    = get_post_type_archive_link( 'bp_video' );
			$locations_url = get_post_type_archive_link( 'bp_location' );

			$links = array(
				__( 'All Products', 'brickpoint' ) => $products_url ? $products_url : '',
				__( 'Videos', 'brickpoint' )       => $videos_url ? $videos_url : '',
				__( 'Locations', 'brickpoint' )    => $locations_url ? $locations_url : '',
				__( 'Contact', 'brickpoint' )      => $contact_url ? $contact_url : '',
			);

			foreach ( $links as $label => $url ) {
				if ( ! $url ) {
					continue;
				}

				printf(
					'<a class="bp-btn bp-btn--ghost" href="%1$s"><span class="bp-btn__label">%2$s</span></a>',
					esc_url( $url ),
					esc_html( $label )
				);
			}

			echo brickpoint_whatsapp_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				array(
					'label' => __( 'Ask on WhatsApp', 'brickpoint' ),
					'class' => 'bp-btn bp-btn--whatsapp',
				)
			);
			?>
		</div>

		<?php
		brickpoint_render_category_grid(
			array(
				'limit'          => 8,
				'columns'        => 4,
				'columns_tablet' => 3,
				'columns_mobile' => 2,
				'home_only'      => true,
			)
		);
		?>
	</div>
</div>

<?php
get_footer();
