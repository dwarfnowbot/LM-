<?php
/**
 * Single video page.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$data     = brickpoint_get_video_data( get_the_ID() );
	$terms    = get_the_terms( get_the_ID(), 'bp_video_category' );
	$category = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0] : null;
	?>

	<div class="bp-container">
		<?php brickpoint_breadcrumbs(); ?>
	</div>

	<article id="video-<?php the_ID(); ?>" <?php post_class( 'bp-single-video' ); ?>>
		<div class="bp-container">
			<header class="bp-single-video__head">
				<?php if ( $category instanceof WP_Term ) : ?>
					<a class="bp-chip is-active" href="<?php echo esc_url( get_term_link( $category ) ); ?>"><?php echo esc_html( $category->name ); ?></a>
				<?php endif; ?>

				<h1 class="bp-single-video__title"><?php the_title(); ?></h1>

				<div class="bp-single-video__meta">
					<?php if ( $data['duration'] ) : ?>
						<span><?php echo brickpoint_icon( 'clock', array( 'size' => 15 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo esc_html( $data['duration'] ); ?></span>
					<?php endif; ?>

					<span><?php echo brickpoint_icon( 'calendar', array( 'size' => 15 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo esc_html( get_the_date() ); ?></span>

					<?php if ( $data['featured'] ) : ?>
						<span class="bp-badge bp-badge--featured"><?php esc_html_e( 'Featured', 'brickpoint' ); ?></span>
					<?php endif; ?>
				</div>
			</header>

			<div class="bp-single-video__player">
				<?php
				if ( $data['is_playable'] ) {
					// Poster-first player: the file/iframe loads on click unless autoplay was chosen.
					brickpoint_inline_video(
						array(
							'video_id'  => get_the_ID(),
							'aspect'    => $data['aspect'],
							'autoplay'  => false,
							'poster_id' => $data['thumbnail_id'],
							'label'     => get_the_title(),
						)
					);
				} else {
					echo '<p class="bp-empty">' . esc_html__( 'No video source saved for this entry. Edit the video and add a YouTube/Vimeo URL or upload an MP4 file.', 'brickpoint' ) . '</p>';
				}
				?>
			</div>

			<div class="bp-single-video__layout">
				<div class="bp-single-video__content">
					<div class="bp-content"><?php the_content(); ?></div>
					<?php brickpoint_share_bar( get_the_ID(), __( 'Share this video', 'brickpoint' ) ); ?>
				</div>

				<aside class="bp-single-video__aside">
					<div class="bp-card">
						<h2 class="bp-card__title"><?php esc_html_e( 'Order materials', 'brickpoint' ); ?></h2>
						<p><?php esc_html_e( 'Tell us what your project needs and we will confirm availability and rates.', 'brickpoint' ); ?></p>

						<?php
						echo brickpoint_whatsapp_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							array(
								'label'   => __( 'Order on WhatsApp', 'brickpoint' ),
								'message' => brickpoint_general_inquiry_message( get_the_title() ),
								'class'   => 'bp-btn bp-btn--whatsapp bp-btn--block',
							)
						);
						?>
					</div>
				</aside>
			</div>

			<?php
			// Related videos from the same category.
			$related = brickpoint_query_related_by_term( get_the_ID(), 'bp_video_category', 'bp_video', 3 );

			if ( $related->have_posts() ) :
				?>
				<section class="bp-related-videos">
					<h2 class="bp-section__title"><?php esc_html_e( 'More videos', 'brickpoint' ); ?></h2>
					<div class="bp-video-grid" style="--bp-cols:3;--bp-cols-t:2;--bp-cols-m:1;">
						<?php
						while ( $related->have_posts() ) :
							$related->the_post();
							brickpoint_video_card( get_the_ID(), array( 'show_excerpt' => false ) );
						endwhile;
						wp_reset_postdata();
						?>
					</div>
				</section>
			<?php endif; ?>
		</div>
	</article>

	<?php
endwhile;

get_footer();
