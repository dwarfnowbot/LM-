<?php
/**
 * Generic content part (used by the fallback loop).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'bp-entry bp-reveal' ); ?>>
	<?php if ( is_singular() ) : ?>
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
	<?php else : ?>
		<h2 class="bp-entry__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h2>

		<p class="bp-entry__text"><?php echo esc_html( brickpoint_excerpt( 24 ) ); ?></p>

		<a class="bp-link-arrow" href="<?php the_permalink(); ?>">
			<?php esc_html_e( 'Read more', 'brickpoint' ); ?>
			<?php echo brickpoint_icon( 'arrow-right', array( 'size' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</a>
	<?php endif; ?>
</article>
