<?php
/**
 * Default page template.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$has_hero = has_post_thumbnail() && ! is_page_template( array( 'page-templates/page-videos.php', 'page-templates/page-locations.php' ) );
	?>

	<?php if ( $has_hero ) : ?>
		<?php
		brickpoint_page_hero(
			array(
				'title'    => get_the_title(),
				'image_id' => get_post_thumbnail_id(),
			)
		);
		?>
	<?php else : ?>
		<?php
		brickpoint_page_hero(
			array(
				'title' => get_the_title(),
				'compact' => true,
			)
		);
		?>
	<?php endif; ?>

	<?php $bp_elementor = brickpoint_is_elementor_page( get_the_ID() ); ?>

	<?php if ( $bp_elementor ) : ?>
		<?php brickpoint_contact_notice(); ?>

		<div class="bp-page bp-page--elementor">
			<?php the_content(); ?>
		</div>

		<?php
		edit_post_link(
			__( 'Edit this page', 'brickpoint' ),
			'<p class="bp-edit-link bp-container">',
			'</p>'
		);
		?>
	<?php else : ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'bp-page bp-container' ); ?>>
			<?php brickpoint_contact_notice(); ?>

			<div class="bp-content">
				<?php
				the_content();

				wp_link_pages(
					array(
						'before' => '<nav class="bp-page-links">',
						'after'  => '</nav>',
					)
				);
				?>
			</div>

			<?php
			edit_post_link(
				__( 'Edit this page', 'brickpoint' ),
				'<p class="bp-edit-link">',
				'</p>'
			);
			?>
		</article>
	<?php endif; ?>

	<?php
endwhile;

get_footer();
