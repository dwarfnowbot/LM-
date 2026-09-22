<?php
/**
 * Locations archive (/locations/).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

brickpoint_page_hero(
	array(
		'title' => __( 'Our Locations', 'brickpoint' ),
		'text'  => __( 'Masha Allah Bricks Company, Fine Bricks Company and the BrickPoint office. Call or WhatsApp before visiting so loading and paperwork are ready.', 'brickpoint' ),
	)
);
?>

<div class="bp-container bp-archive bp-archive--locations">
	<?php if ( have_posts() ) : ?>
		<div class="bp-location-grid" style="--bp-cols:2;--bp-cols-t:2;--bp-cols-m:1;">
			<?php
			while ( have_posts() ) :
				the_post();
				brickpoint_location_card( get_the_ID(), array( 'show_video' => true ) );
			endwhile;
			?>
		</div>
	<?php else : ?>
		<div class="bp-empty-state">
			<h2 class="bp-empty-state__title"><?php esc_html_e( 'No locations published yet', 'brickpoint' ); ?></h2>
			<p><?php esc_html_e( 'Add your bhatta and office locations in WordPress → Locations, or create the default set from BrickPoint → Setup & Content.', 'brickpoint' ); ?></p>
		</div>
	<?php endif; ?>

	<section class="bp-section bp-section--tight">
		<?php
		brickpoint_section_heading(
			array(
				'title' => __( 'Not sure which location to visit?', 'brickpoint' ),
				'text'  => __( 'Send us your requirement and we will point you to the closest production site or arrange delivery directly.', 'brickpoint' ),
			)
		);

		echo brickpoint_whatsapp_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			array(
				'label' => __( 'Ask on WhatsApp', 'brickpoint' ),
				'class' => 'bp-btn bp-btn--whatsapp bp-btn--lg',
			)
		);
		?>
	</section>
</div>

<?php
get_footer();
