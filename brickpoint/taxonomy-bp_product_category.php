<?php
/**
 * Product category archive.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$term    = get_queried_object();
$banner  = $term instanceof WP_Term ? brickpoint_term_image_url( $term->term_id, 'bp-hero', 'banner' ) : '';
$image   = $term instanceof WP_Term ? brickpoint_term_image_url( $term->term_id, 'bp-card' ) : '';

brickpoint_page_hero(
	array(
		'title'    => $term instanceof WP_Term ? $term->name : __( 'Products', 'brickpoint' ),
		'text'     => $term instanceof WP_Term ? brickpoint_excerpt( 34, $term->description ) : '',
		'bg_url'   => $banner,
	)
);
?>

<div class="bp-container bp-archive bp-archive--category">

	<?php if ( $term instanceof WP_Term ) : ?>
		<div class="bp-category-intro">
			<?php if ( $image ) : ?>
				<div class="bp-category-intro__media bp-media">
					<img class="bp-media__img" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $term->name ); ?>" loading="lazy" decoding="async" />
				</div>
			<?php endif; ?>

			<div class="bp-category-intro__body">
				<?php
				$short = get_term_meta( $term->term_id, '_bp_term_short', true );

				if ( $short ) {
					echo '<p class="bp-eyebrow">' . esc_html( $short ) . '</p>';
				}

				if ( $term->description ) {
					echo '<div class="bp-content">' . wp_kses_post( wpautop( $term->description ) ) . '</div>';
				}
				?>

				<div class="bp-category-intro__actions">
					<?php
					echo brickpoint_whatsapp_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						array(
							'label'   => sprintf(
								/* translators: %s: category name. */
								__( 'Inquire about %s', 'brickpoint' ),
								$term->name
							),
							'message' => brickpoint_term_inquiry_message( $term ),
							'class'   => 'bp-btn bp-btn--whatsapp bp-btn--lg',
						)
					);

					brickpoint_phone_button(
						array(
							'label' => __( 'Call Now', 'brickpoint' ),
							'class' => 'bp-btn bp-btn--ghost bp-btn--lg',
						)
					);
					?>
				</div>
			</div>
		</div>

		<?php
		// Featured video for this category, when one is assigned.
		$video_id  = (int) get_term_meta( $term->term_id, '_bp_term_video', true );
		$video_url = (string) get_term_meta( $term->term_id, '_bp_term_video_url', true );

		if ( $video_id || $video_url ) {
			echo '<section class="bp-section bp-section--tight">';
			echo '<h2 class="bp-section__title">' . esc_html__( 'Watch', 'brickpoint' ) . '</h2>';

			brickpoint_inline_video(
				array(
					'video_id' => $video_id,
					'url'      => $video_url,
					'autoplay' => false,
					'poster_id' => $term ? (int) get_term_meta( $term->term_id, '_bp_term_image', true ) : 0,
					'label'    => $term->name,
				)
			);

			echo '</section>';
		}
		?>
	<?php endif; ?>

	<?php if ( have_posts() ) : ?>
		<div class="bp-product-grid" style="--bp-cols:3;--bp-cols-t:2;--bp-cols-m:1;">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content', 'product' );
			endwhile;
			?>
		</div>

		<?php brickpoint_pagination(); ?>
	<?php else : ?>
		<div class="bp-empty-state">
			<h2 class="bp-empty-state__title"><?php esc_html_e( 'No products in this category yet', 'brickpoint' ); ?></h2>
			<p><?php esc_html_e( 'We can still supply this material — send us your requirement and we will confirm availability.', 'brickpoint' ); ?></p>

			<?php
			if ( $term instanceof WP_Term ) {
				echo brickpoint_whatsapp_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					array(
						'label'   => __( 'Ask on WhatsApp', 'brickpoint' ),
						'message' => brickpoint_term_inquiry_message( $term ),
						'class'   => 'bp-btn bp-btn--whatsapp',
					)
				);
			}
			?>
		</div>
	<?php endif; ?>
</div>

<?php
get_footer();
