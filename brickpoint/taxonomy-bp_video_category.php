<?php
/**
 * Video category archive.
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
		'title' => $term instanceof WP_Term ? $term->name : __( 'Videos', 'brickpoint' ),
		'text'  => $term instanceof WP_Term ? wp_strip_all_tags( $term->description ) : '',
	)
);

$terms = get_terms(
	array(
		'taxonomy'   => 'bp_video_category',
		'hide_empty' => true,
	)
);
?>

<div class="bp-container bp-videos-page">
	<?php if ( $terms && ! is_wp_error( $terms ) ) : ?>
		<div class="bp-video-filters" data-bp-video-filters data-per-page="9" data-orderby="date">
			<a class="bp-chip" href="<?php echo esc_url( get_post_type_archive_link( 'bp_video' ) ); ?>"><?php esc_html_e( 'All Videos', 'brickpoint' ); ?></a>

			<?php foreach ( $terms as $item ) : ?>
				<button type="button" class="bp-chip<?php echo ( $term instanceof WP_Term && $term->term_id === $item->term_id ) ? ' is-active' : ''; ?>" data-filter="<?php echo esc_attr( $item->slug ); ?>">
					<?php echo esc_html( $item->name ); ?>
				</button>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<div class="bp-video-grid-wrap" data-bp-video-grid-wrap>
		<?php if ( have_posts() ) : ?>
			<div class="bp-video-grid" style="--bp-cols:3;--bp-cols-t:2;--bp-cols-m:1;">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', 'video' );
				endwhile;
				?>
			</div>

			<?php brickpoint_pagination(); ?>
		<?php else : ?>
			<div class="bp-empty-state">
				<h2 class="bp-empty-state__title"><?php esc_html_e( 'No videos in this category yet', 'brickpoint' ); ?></h2>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php
get_footer();
