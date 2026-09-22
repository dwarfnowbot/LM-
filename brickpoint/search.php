<?php
/**
 * Search results - includes products, videos, projects, locations and posts.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

brickpoint_page_hero(
	array(
		'title' => sprintf(
			/* translators: %s: search term. */
			__( 'Search results for “%s”', 'brickpoint' ),
			get_search_query()
		),
		'text'  => sprintf(
			/* translators: %d: results count. */
			_n( '%d result found.', '%d results found.', (int) $GLOBALS['wp_query']->found_posts, 'brickpoint' ),
			(int) $GLOBALS['wp_query']->found_posts
		),
		'compact' => true,
	)
);
?>

<div class="bp-container bp-archive">
	<div class="bp-search-page__form"><?php get_search_form(); ?></div>

	<?php if ( have_posts() ) : ?>
		<div class="bp-post-grid">
			<?php
			while ( have_posts() ) :
				the_post();

				$type = get_post_type();

				if ( 'bp_product' === $type ) {
					get_template_part( 'template-parts/content', 'product' );
				} elseif ( 'bp_video' === $type ) {
					get_template_part( 'template-parts/content', 'video' );
				} elseif ( 'bp_project' === $type ) {
					get_template_part( 'template-parts/content', 'project' );
				} else {
					get_template_part( 'template-parts/content', 'card' );
				}
			endwhile;
			?>
		</div>

		<?php brickpoint_pagination(); ?>
	<?php else : ?>
		<div class="bp-empty-state">
			<h2 class="bp-empty-state__title"><?php esc_html_e( 'No results found', 'brickpoint' ); ?></h2>
			<p><?php esc_html_e( 'Try another term, or browse our product categories.', 'brickpoint' ); ?></p>

			<?php
			brickpoint_render_category_grid(
				array(
					'limit'          => 6,
					'columns'        => 3,
					'columns_tablet' => 2,
					'columns_mobile' => 2,
					'home_only'      => true,
				)
			);
			?>
		</div>
	<?php endif; ?>
</div>

<?php
get_footer();
