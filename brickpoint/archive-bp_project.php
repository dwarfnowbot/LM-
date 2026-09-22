<?php
/**
 * Projects archive (/projects/).
 *
 * Honesty: visuals marked "illustrative" carry the label automatically.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

brickpoint_page_hero(
	array(
		'title' => __( 'Projects & Construction References', 'brickpoint' ),
		'text'  => __( 'Materials from BrickPoint are used across residential, commercial and infrastructure work. Visuals labelled “illustrative construction reference” are for context only.', 'brickpoint' ),
	)
);

$terms = get_terms(
	array(
		'taxonomy'   => 'bp_project_category',
		'hide_empty' => true,
	)
);
?>

<div class="bp-container bp-archive bp-archive--projects">
	<?php if ( $terms && ! is_wp_error( $terms ) ) : ?>
		<div class="bp-filters" role="group" aria-label="<?php esc_attr_e( 'Filter projects', 'brickpoint' ); ?>">
			<a class="bp-chip is-active" href="<?php echo esc_url( get_post_type_archive_link( 'bp_project' ) ); ?>"><?php esc_html_e( 'All projects', 'brickpoint' ); ?></a>

			<?php foreach ( $terms as $term ) : ?>
				<a class="bp-chip" href="<?php echo esc_url( get_term_link( $term ) ); ?>"><?php echo esc_html( $term->name ); ?></a>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

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
			<h2 class="bp-empty-state__title"><?php esc_html_e( 'No projects published yet', 'brickpoint' ); ?></h2>
			<p><?php esc_html_e( 'Add project references in WordPress → Projects. Only publish a project when the details can be verified — otherwise enable the “illustrative” checkbox so visitors are not misled.', 'brickpoint' ); ?></p>
		</div>
	<?php endif; ?>
</div>

<?php
get_footer();
