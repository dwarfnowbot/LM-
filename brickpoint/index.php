<?php
/**
 * Fallback template (blog index / generic archive).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="bp-container bp-archive">
	<?php
	if ( have_posts() ) :
		brickpoint_page_hero(
			array(
				'title' => is_home() && get_option( 'page_for_posts' ) ? get_the_title( (int) get_option( 'page_for_posts' ) ) : wp_strip_all_tags( get_the_archive_title() ),
				'text'  => wp_strip_all_tags( get_the_archive_description() ),
			)
		);
		?>

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
			<h1 class="bp-empty-state__title"><?php esc_html_e( 'Nothing found', 'brickpoint' ); ?></h1>
			<p><?php esc_html_e( 'Try a different search, or browse our products instead.', 'brickpoint' ); ?></p>
			<?php get_search_form(); ?>
		</div>
	<?php endif; ?>
</div>

<?php
get_footer();
