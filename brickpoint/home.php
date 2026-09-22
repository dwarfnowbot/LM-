<?php
/**
 * Blog index (posts page).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$blog_page_id = (int) get_option( 'page_for_posts' );
$title        = $blog_page_id ? get_the_title( $blog_page_id ) : __( 'Blog', 'brickpoint' );
$intro        = $blog_page_id ? get_post_field( 'post_content', $blog_page_id ) : '';

brickpoint_page_hero(
	array(
		'title' => $title,
		'text'  => $intro ? esc_html( brickpoint_excerpt( 30, $intro ) ) : esc_html__( 'Guides on bricks, cement, sand, crush, steel and construction-material planning.', 'brickpoint' ),
	)
);
?>

<div class="bp-container bp-blog">
	<div class="bp-blog__layout<?php echo is_active_sidebar( 'sidebar-1' ) ? '' : ' bp-blog__layout--full'; ?>">

		<div class="bp-blog__main">
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
					<h2 class="bp-empty-state__title"><?php esc_html_e( 'No posts yet', 'brickpoint' ); ?></h2>
					<p><?php esc_html_e( 'Publish your first article from WordPress → Posts. Ideas: brick selection, cement grades, sand and crush quality, material planning.', 'brickpoint' ); ?></p>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
			<aside class="bp-blog__sidebar" aria-label="<?php esc_attr_e( 'Blog sidebar', 'brickpoint' ); ?>">
				<?php dynamic_sidebar( 'sidebar-1' ); ?>
			</aside>
		<?php endif; ?>
	</div>
</div>

<?php
get_footer();
