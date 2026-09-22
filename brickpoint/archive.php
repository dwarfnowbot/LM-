<?php
/**
 * Generic archive (categories, tags, dates, authors).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

brickpoint_page_hero(
	array(
		'title' => wp_strip_all_tags( get_the_archive_title() ),
		'text'  => wp_strip_all_tags( get_the_archive_description() ),
	)
);
?>

<div class="bp-container bp-archive">
	<?php if ( have_posts() ) : ?>
		<div class="bp-post-grid">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content', 'card' );
			endwhile;
			?>
		</div>

		<?php brickpoint_pagination(); ?>
	<?php else : ?>
		<div class="bp-empty-state">
			<h2 class="bp-empty-state__title"><?php esc_html_e( 'Nothing here yet', 'brickpoint' ); ?></h2>
			<p><?php esc_html_e( 'There is no content in this archive yet.', 'brickpoint' ); ?></p>
			<?php get_search_form(); ?>
		</div>
	<?php endif; ?>
</div>

<?php
get_footer();
