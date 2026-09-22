<?php
/**
 * Project category archive.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$term = get_queried_object();

brickpoint_page_hero(
	array(
		'title' => $term instanceof WP_Term ? $term->name : __( 'Projects', 'brickpoint' ),
		'text'  => $term instanceof WP_Term ? wp_strip_all_tags( $term->description ) : '',
	)
);
?>

<div class="bp-container bp-archive bp-archive--projects">
	<?php if ( have_posts() ) : ?>
		<div class="bp-project-grid" style="--bp-cols:3;--bp-cols-t:2;--bp-cols-m:1;">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content', 'project' );
			endwhile;
			?>
		</div>

		<?php brickpoint_pagination(); ?>
	<?php else : ?>
		<div class="bp-empty-state">
			<h2 class="bp-empty-state__title"><?php esc_html_e( 'No projects in this category yet', 'brickpoint' ); ?></h2>
		</div>
	<?php endif; ?>
</div>

<?php
get_footer();
