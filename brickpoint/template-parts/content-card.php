<?php
/**
 * Post card used in blog and archive grids.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$bp_categories = get_the_category();
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'bp-post-card bp-reveal' ); ?>>
	<a class="bp-post-card__media bp-media" href="<?php the_permalink(); ?>">
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail(
				'bp-card',
				array(
					'class'   => 'bp-media__img',
					'loading' => 'lazy',
					'alt'     => get_the_title(),
				)
			);
		} else {
			echo brickpoint_placeholder( '16x9' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		?>
	</a>

	<div class="bp-post-card__body">
		<div class="bp-post-card__meta">
			<span class="bp-post-card__date"><?php echo esc_html( get_the_date() ); ?></span>

			<?php
			if ( $bp_categories ) {
				printf(
					'<a class="bp-post-card__cat" href="%1$s">%2$s</a>',
					esc_url( get_category_link( $bp_categories[0]->term_id ) ),
					esc_html( $bp_categories[0]->name )
				);
			}
			?>
		</div>

		<h2 class="bp-post-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

		<p class="bp-post-card__text"><?php echo esc_html( brickpoint_excerpt( 20 ) ); ?></p>

		<a class="bp-link-arrow" href="<?php the_permalink(); ?>">
			<?php esc_html_e( 'Read more', 'brickpoint' ); ?>
			<?php echo brickpoint_icon( 'arrow-right', array( 'size' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</a>
	</div>
</article>
