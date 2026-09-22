<?php
/**
 * Single location page.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$location_id = get_the_ID();
	$company     = brickpoint_meta( $location_id, '_bp_location_company' );
	$address     = brickpoint_meta( $location_id, '_bp_location_address' );
	$map         = brickpoint_meta( $location_id, '_bp_location_map' );
	$directions  = brickpoint_meta( $location_id, '_bp_location_directions' );
	$coords      = brickpoint_meta( $location_id, '_bp_location_coords' );
	$phone       = brickpoint_meta( $location_id, '_bp_location_phone' );
	$hours       = brickpoint_meta( $location_id, '_bp_location_hours' );
	$video_id    = (int) brickpoint_meta( $location_id, '_bp_location_video', 0 );
	$video_url   = brickpoint_meta( $location_id, '_bp_location_video_url' );

	if ( ! $directions && $map ) {
		$directions = $map;
	}

	brickpoint_page_hero(
		array(
			'title'    => get_the_title(),
			'text'     => $company,
			'image_id' => has_post_thumbnail() ? get_post_thumbnail_id() : 0,
			'compact'  => ! has_post_thumbnail(),
		)
	);
	?>

	<article id="location-<?php the_ID(); ?>" <?php post_class( 'bp-single-location bp-container' ); ?>>
		<div class="bp-single-location__layout">
			<div class="bp-single-location__main">
				<div class="bp-content"><?php the_content(); ?></div>

				<?php
				$products = array_filter( array_map( 'absint', explode( ',', (string) brickpoint_meta( $location_id, '_bp_location_products' ) ) ) );

				if ( $products ) {
					echo '<section class="bp-section bp-section--tight">';
					echo '<h2 class="bp-section__title">' . esc_html__( 'Available at this location', 'brickpoint' ) . '</h2>';
					echo '<ul class="bp-link-list">';

					foreach ( $products as $product_id ) {
						printf(
							'<li><a href="%1$s">%2$s</a></li>',
							esc_url( get_permalink( $product_id ) ),
							esc_html( get_the_title( $product_id ) )
						);
					}

					echo '</ul></section>';
				}

				if ( $video_id || $video_url ) {
					echo '<section class="bp-section bp-section--tight">';
					echo '<h2 class="bp-section__title">' . esc_html__( 'Location video', 'brickpoint' ) . '</h2>';

					brickpoint_inline_video(
						array(
							'video_id'  => $video_id,
							'url'       => $video_url,
							'autoplay'  => false,
							'poster_id' => has_post_thumbnail() ? (int) get_post_thumbnail_id() : 0,
							'label'     => get_the_title(),
						)
					);

					echo '</section>';
				}
				?>
			</div>

			<aside class="bp-single-location__aside">
				<div class="bp-card">
					<h2 class="bp-card__title"><?php esc_html_e( 'Location details', 'brickpoint' ); ?></h2>

					<ul class="bp-location-facts">
						<?php if ( $company ) : ?>
							<li><?php echo brickpoint_icon( 'factory', array( 'size' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( $company ); ?></span></li>
						<?php endif; ?>

						<?php if ( $address ) : ?>
							<li><?php echo brickpoint_icon( 'pin', array( 'size' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( $address ); ?></span></li>
						<?php endif; ?>

						<?php if ( $hours ) : ?>
							<li><?php echo brickpoint_icon( 'clock', array( 'size' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( $hours ); ?></span></li>
						<?php endif; ?>

						<?php if ( $coords ) : ?>
							<li><?php echo brickpoint_icon( 'grid', array( 'size' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( $coords ); ?></span></li>
						<?php endif; ?>
					</ul>

					<div class="bp-single-location__actions">
						<?php if ( $map ) : ?>
							<a class="bp-btn bp-btn--primary bp-btn--block" href="<?php echo esc_url( $map ); ?>" target="_blank" rel="noopener noreferrer">
								<?php echo brickpoint_icon( 'pin', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<span class="bp-btn__label"><?php esc_html_e( 'View on Google Maps', 'brickpoint' ); ?></span>
							</a>
						<?php endif; ?>

						<?php if ( $directions ) : ?>
							<a class="bp-btn bp-btn--ghost bp-btn--block" href="<?php echo esc_url( $directions ); ?>" target="_blank" rel="noopener noreferrer">
								<?php echo brickpoint_icon( 'arrow-right', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<span class="bp-btn__label"><?php esc_html_e( 'Get Directions', 'brickpoint' ); ?></span>
							</a>
						<?php endif; ?>

						<?php
						brickpoint_phone_button(
							array(
								'label'  => __( 'Call Now', 'brickpoint' ),
								'class'  => 'bp-btn bp-btn--ghost bp-btn--block',
								'number' => $phone,
							)
						);

						echo brickpoint_whatsapp_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							array(
								'label'   => __( 'WhatsApp this location', 'brickpoint' ),
								'message' => brickpoint_general_inquiry_message( get_the_title() ),
								'number'  => $phone ? $phone : '',
								'class'   => 'bp-btn bp-btn--whatsapp bp-btn--block',
							)
						);
						?>
					</div>

					<?php if ( ! $map ) : ?>
						<p class="bp-muted"><?php esc_html_e( 'Add a Google Maps link in the location editor to show map and direction buttons.', 'brickpoint' ); ?></p>
					<?php endif; ?>
				</div>
			</aside>
		</div>

		<?php
		$others = new WP_Query(
			array(
				'post_type'      => 'bp_location',
				'posts_per_page' => 2,
				'post__not_in'   => array( $location_id ),
				'no_found_rows'  => true,
			)
		);

		if ( $others->have_posts() ) :
			?>
			<section class="bp-section bp-section--tight">
				<h2 class="bp-section__title"><?php esc_html_e( 'Other locations', 'brickpoint' ); ?></h2>
				<div class="bp-location-grid" style="--bp-cols:2;--bp-cols-t:2;--bp-cols-m:1;">
					<?php
					while ( $others->have_posts() ) :
						$others->the_post();
						brickpoint_location_card( get_the_ID(), array( 'show_video' => false ) );
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</section>
		<?php endif; ?>
	</article>

	<?php
endwhile;

get_footer();
