<?php
/**
 * Template Name: BrickPoint – Videos Page
 * Template Post Type: page
 *
 * A dedicated /videos/ page with hero, category filters and the video grid.
 * Useful when you prefer a page over the Videos archive, or want an extra
 * curated video page (e.g. /ss7-videos/).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$term = isset( $_GET['video_cat'] ) ? sanitize_title( wp_unslash( $_GET['video_cat'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only filter.
	?>

	<?php
	brickpoint_page_hero(
		array(
			'title'    => get_the_title() ? get_the_title() : __( 'Inside BrickPoint', 'brickpoint' ),
			'text'     => __( 'Explore our products, production process, construction materials, projects, and company updates through video.', 'brickpoint' ),
			'image_id' => has_post_thumbnail() ? get_post_thumbnail_id() : 0,
			'compact'  => ! has_post_thumbnail(),
		)
	);
	?>

	<div class="bp-container bp-videos-page">
		<?php
		$content = get_post_field( 'post_content', get_the_ID() );

		if ( $content ) {
			echo '<div class="bp-content bp-page-template__intro">' . wp_kses_post( apply_filters( 'the_content', $content ) ) . '</div>';
		}

		brickpoint_render_video_grid(
			array(
				'categories'       => $term ? array( $term ) : array(),
				'per_page'         => 12,
				'columns'          => 3,
				'columns_tablet'   => 2,
				'columns_mobile'   => 1,
				'show_filter'      => false,
				'load_more'        => true,
				'empty_text'       => __( 'No videos published yet. Add them in WordPress → Videos.', 'brickpoint' ),
			)
		);
		?>
	</div>

	<?php
endwhile;

get_footer();
