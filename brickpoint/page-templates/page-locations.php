<?php
/**
 * Template Name: BrickPoint – Locations Page
 * Template Post Type: page
 *
 * Locations page with the location cards, real Google Maps links and a
 * contact strip. Use it if you prefer a page over the Locations archive.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	?>

	<?php
	brickpoint_page_hero(
		array(
			'title'    => get_the_title() ? get_the_title() : __( 'Our Locations', 'brickpoint' ),
			'text'     => __( 'Production sites and office. Google Maps links are provided for each location.', 'brickpoint' ),
			'image_id' => has_post_thumbnail() ? get_post_thumbnail_id() : 0,
			'compact'  => ! has_post_thumbnail(),
		)
	);
	?>

	<div class="bp-container bp-locations-page">
		<?php
		$content = get_post_field( 'post_content', get_the_ID() );

		if ( $content ) {
			echo '<div class="bp-content bp-page-template__intro">' . wp_kses_post( apply_filters( 'the_content', $content ) ) . '</div>';
		}

		brickpoint_render_location_grid(
			array(
				'columns'        => 2,
				'columns_tablet' => 2,
				'columns_mobile' => 1,
				'show_video'     => true,
				'empty_text'     => __( 'No locations published yet. Create the BrickPoint locations from BrickPoint → Setup & Content.', 'brickpoint' ),
			)
		);
		?>
	</div>

	<?php
endwhile;

get_footer();
