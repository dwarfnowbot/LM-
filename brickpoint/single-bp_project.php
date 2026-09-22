<?php
/**
 * Single project page.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$project_id   = get_the_ID();
	$location     = brickpoint_meta( $project_id, '_bp_project_location' );
	$status       = brickpoint_meta( $project_id, '_bp_project_status' );
	$scope        = brickpoint_meta( $project_id, '_bp_project_scope' );
	$year         = brickpoint_meta( $project_id, '_bp_project_year' );
	$illustrative = (bool) brickpoint_meta( $project_id, '_bp_illustrative' );
	$disclaimer   = brickpoint_meta( $project_id, '_bp_project_disclaimer' );
	$video_id     = (int) brickpoint_meta( $project_id, '_bp_project_video', 0 );
	$video_url    = brickpoint_meta( $project_id, '_bp_project_video_url' );

	$status_labels = array(
		'planning'  => __( 'Planning / concept', 'brickpoint' ),
		'ongoing'   => __( 'Ongoing', 'brickpoint' ),
		'completed' => __( 'Completed', 'brickpoint' ),
		'reference' => __( 'Reference visual', 'brickpoint' ),
	);

	brickpoint_page_hero(
		array(
			'title'    => get_the_title(),
			'text'     => $location ? $location : '',
			'image_id' => has_post_thumbnail() ? get_post_thumbnail_id() : 0,
			'compact'  => ! has_post_thumbnail(),
		)
	);
	?>

	<article id="project-<?php the_ID(); ?>" <?php post_class( 'bp-single-project bp-container' ); ?>>
		<?php if ( $illustrative ) : ?>
			<p class="bp-notice bp-notice--info">
				<?php echo brickpoint_icon( 'shield', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php esc_html_e( 'Illustrative construction reference — this visual shows construction context and is not a claim of supply to a named developer or society.', 'brickpoint' ); ?>
			</p>
		<?php endif; ?>

		<?php if ( $disclaimer ) : ?>
			<p class="bp-disclaimer"><?php echo esc_html( $disclaimer ); ?></p>
		<?php endif; ?>

		<dl class="bp-project-facts">
			<?php if ( $location ) : ?>
				<div><dt><?php esc_html_e( 'Location', 'brickpoint' ); ?></dt><dd><?php echo esc_html( $location ); ?></dd></div>
			<?php endif; ?>

			<?php if ( $status && isset( $status_labels[ $status ] ) ) : ?>
				<div><dt><?php esc_html_e( 'Status', 'brickpoint' ); ?></dt><dd><?php echo esc_html( $status_labels[ $status ] ); ?></dd></div>
			<?php endif; ?>

			<?php if ( $scope ) : ?>
				<div><dt><?php esc_html_e( 'Scope', 'brickpoint' ); ?></dt><dd><?php echo esc_html( $scope ); ?></dd></div>
			<?php endif; ?>

			<?php if ( $year ) : ?>
				<div><dt><?php esc_html_e( 'Year', 'brickpoint' ); ?></dt><dd><?php echo esc_html( $year ); ?></dd></div>
			<?php endif; ?>
		</dl>

		<div class="bp-content"><?php the_content(); ?></div>

		<?php
		$gallery = brickpoint_sanitize_id_list( brickpoint_meta( $project_id, '_bp_project_gallery' ) );

		if ( $gallery ) :
			?>
			<div class="bp-project-gallery">
				<?php
				foreach ( array_filter( array_map( 'absint', explode( ',', $gallery ) ) ) as $image_id ) {
					echo wp_get_attachment_image(
						$image_id,
						'bp-card',
						false,
						array(
							'class'   => 'bp-project-gallery__img',
							'loading' => 'lazy',
						)
					);
				}
				?>
			</div>
		<?php endif; ?>

		<?php if ( $video_id || $video_url ) : ?>
			<section class="bp-section bp-section--tight">
				<h2 class="bp-section__title"><?php esc_html_e( 'Project video', 'brickpoint' ); ?></h2>
				<?php
				brickpoint_inline_video(
					array(
						'video_id'  => $video_id,
						'url'       => $video_url,
						'autoplay'  => false,
						'poster_id' => has_post_thumbnail() ? (int) get_post_thumbnail_id() : 0,
						'label'     => get_the_title(),
					)
				);
				?>
			</section>
		<?php endif; ?>

		<?php
		brickpoint_linked_products( $project_id, '_bp_project_products', array( 'title' => __( 'Materials used', 'brickpoint' ) ) );

		echo brickpoint_whatsapp_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			array(
				'label'   => __( 'Discuss a similar project on WhatsApp', 'brickpoint' ),
				'message' => brickpoint_general_inquiry_message( get_the_title() ),
				'class'   => 'bp-btn bp-btn--whatsapp bp-btn--lg',
			)
		);
		?>
	</article>

	<?php
endwhile;

get_footer();
