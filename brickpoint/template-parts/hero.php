<?php
/**
 * Homepage hero with the SS7 brick animation and the hero video.
 *
 * All values come from the Customizer (Appearance → Customize → BrickPoint
 * Theme → Homepage Hero), so nothing is hardcoded in the template.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$title    = brickpoint_option( 'bp_hero_title' );
$eyebrow  = brickpoint_option( 'bp_hero_eyebrow' );
$text     = brickpoint_option( 'bp_hero_text' );
$overlay  = (int) brickpoint_option( 'bp_hero_overlay' );
$radius   = (int) brickpoint_option( 'bp_hero_video_radius' );
$badge    = brickpoint_option( 'bp_hero_badge' );

$cta1_label = brickpoint_option( 'bp_hero_cta1_label' );
$cta1_link  = brickpoint_option( 'bp_hero_cta1_link' );
$cta2_label = brickpoint_option( 'bp_hero_cta2_label' );
$cta2_link  = brickpoint_option( 'bp_hero_cta2_link' );

$products_url = brickpoint_page_url( 'products' );
$contact_url  = brickpoint_page_url( 'contact' );
$cta1_link    = $cta1_link ? $cta1_link : ( $products_url ? $products_url : home_url( '/' ) );
$cta2_link    = $cta2_link ? $cta2_link : ( $contact_url ? $contact_url : home_url( '/' ) );

$video_id  = (int) brickpoint_option( 'bp_hero_video' );
$video_url = brickpoint_option( 'bp_hero_video_youtube' );
$poster_id = (int) brickpoint_option( 'bp_hero_poster' );

$ss7_term = get_term_by( 'slug', 'ss7-bricks', 'bp_product_category' );
$ss7_img  = $ss7_term ? brickpoint_term_image_url( $ss7_term->term_id, 'bp-card' ) : '';

$lines = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $title ) ) );
?>
<section class="bp-hero" data-bp-hero data-hero-video="<?php echo ( $video_id || $video_url ) ? '1' : '0'; ?>">
	<div class="bp-hero__inner bp-container">

		<div class="bp-hero__content">
			<?php if ( $eyebrow ) : ?>
				<p class="bp-eyebrow bp-reveal"><?php echo esc_html( $eyebrow ); ?></p>
			<?php endif; ?>

			<?php if ( $lines ) : ?>
				<h1 class="bp-hero__title">
					<?php
					foreach ( $lines as $index => $line ) {
						printf(
							'<span class="bp-hero__line bp-reveal" style="animation-delay:%1$dms">%2$s</span>',
							(int) ( $index * 120 ),
							esc_html( $line )
						);
					}
					?>
				</h1>
			<?php endif; ?>

			<?php if ( $text ) : ?>
				<p class="bp-hero__text bp-reveal"><?php echo esc_html( $text ); ?></p>
			<?php endif; ?>

			<div class="bp-hero__actions bp-reveal">
				<?php if ( $cta1_label ) : ?>
					<a class="bp-btn bp-btn--primary bp-btn--lg" href="<?php echo esc_url( $cta1_link ); ?>">
						<?php echo brickpoint_icon( 'grid', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<span class="bp-btn__label"><?php echo esc_html( $cta1_label ); ?></span>
					</a>
				<?php endif; ?>

				<?php if ( $cta2_label ) : ?>
					<a class="bp-btn bp-btn--ghost bp-btn--lg" href="<?php echo esc_url( $cta2_link ); ?>">
						<?php echo brickpoint_icon( 'quote', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<span class="bp-btn__label"><?php echo esc_html( $cta2_label ); ?></span>
					</a>
				<?php endif; ?>

				<?php
				echo brickpoint_whatsapp_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					array(
						'label' => __( 'WhatsApp Us', 'brickpoint' ),
						'class' => 'bp-btn bp-btn--whatsapp bp-btn--lg',
					)
				);
				?>
			</div>

		</div>

		<?php if ( $video_id || $video_url ) : ?>
			<div class="bp-hero__media bp-media bp-media--video">
				<?php
				brickpoint_hero_video(
					array(
						'file_id'   => $video_id,
						'url'       => $video_url,
						'poster_id' => $poster_id,
						'overlay'   => $overlay,
						'radius'    => $radius,
						'badge'     => $badge,
						'class'     => 'bp-hero__media-inner',
					)
				);
				?>
			</div>
		<?php endif; ?>

		<?php // The brick is a grid item of its own: it has its own space and can never cover the text or the buttons. ?>
		<div class="bp-hero__ss7" aria-hidden="true">
			<?php if ( $ss7_img ) : ?>
				<img class="bp-ss7-brick" src="<?php echo esc_url( $ss7_img ); ?>" alt="" loading="eager" decoding="async" />
			<?php else : ?>
				<span class="bp-ss7-brick bp-ss7-brick--css"><?php echo brickpoint_icon( 'brick', array( 'size' => 120 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<?php endif; ?>
			<span class="bp-hero__ss7-shadow"></span>
		</div>

	</div>
</section>
