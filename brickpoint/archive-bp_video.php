<?php
/**
 * Videos archive (/videos/) - "Inside BrickPoint".
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

brickpoint_page_hero(
	array(
		'title' => __( 'Inside BrickPoint', 'brickpoint' ),
		'text'  => __( 'Explore our products, production process, construction materials, projects, and company updates through video.', 'brickpoint' ),
	)
);

$per_page = 9;
$paged    = max( 1, (int) get_query_var( 'paged' ) );
$terms    = get_terms(
	array(
		'taxonomy'   => 'bp_video_category',
		'hide_empty' => true,
	)
);
?>

<div class="bp-container bp-videos-page">

	<?php if ( $terms && ! is_wp_error( $terms ) ) : ?>
		<div class="bp-video-filters" data-bp-video-filters data-per-page="<?php echo (int) $per_page; ?>" data-orderby="date">
			<button type="button" class="bp-chip is-active" data-filter=""><?php esc_html_e( 'All Videos', 'brickpoint' ); ?></button>

			<?php foreach ( $terms as $term ) : ?>
				<button type="button" class="bp-chip" data-filter="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></button>
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
				<h2 class="bp-empty-state__title"><?php esc_html_e( 'No videos yet', 'brickpoint' ); ?></h2>
				<p><?php esc_html_e( 'Add your first video in WordPress → Videos (YouTube, Vimeo or a self-hosted MP4) and it will appear here.', 'brickpoint' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php
unset( $per_page, $paged );

get_footer();
