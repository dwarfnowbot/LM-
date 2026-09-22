<?php
/**
 * Single blog post.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	brickpoint_page_hero(
		array(
			'title' => get_the_title(),
			'image_id' => has_post_thumbnail() ? get_post_thumbnail_id() : 0,
			'compact' => ! has_post_thumbnail(),
		)
	);
	?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( 'bp-single bp-container' ); ?>>
		<div class="bp-single__layout">
			<div class="bp-single__main">
				<div class="bp-post-meta">
					<span class="bp-post-meta__date">
						<?php echo brickpoint_icon( 'calendar', array( 'size' => 15 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php echo esc_html( get_the_date() ); ?>
					</span>
					<span class="bp-post-meta__author"><?php echo esc_html( get_the_author() ); ?></span>

					<?php
					$categories = get_the_category();

					if ( $categories ) {
						echo '<span class="bp-post-meta__cats">';

						foreach ( $categories as $category ) {
							printf(
								'<a href="%1$s">%2$s</a>',
								esc_url( get_category_link( $category->term_id ) ),
								esc_html( $category->name )
							);
						}

						echo '</span>';
					}
					?>
				</div>

				<div class="bp-content">
					<?php the_content(); ?>
				</div>

				<?php
				$tags = get_the_tag_list( '<div class="bp-tags">', '', '</div>' );

				if ( $tags ) {
					echo wp_kses_post( $tags );
				}

				brickpoint_share_bar( get_the_ID(), __( 'Share this article', 'brickpoint' ) );

				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
				?>
			</div>

			<aside class="bp-single__aside">
				<div class="bp-card bp-card--products">
					<h2 class="bp-card__title"><?php esc_html_e( 'Our materials', 'brickpoint' ); ?></h2>
					<p><?php esc_html_e( 'Bricks, cement, crush, sand, steel and finishing materials — supplied for projects of every size.', 'brickpoint' ); ?></p>

					<?php
					$products_url = brickpoint_page_url( 'products' );

					if ( $products_url ) :
						?>
						<a class="bp-btn bp-btn--primary bp-btn--block" href="<?php echo esc_url( $products_url ); ?>">
							<span class="bp-btn__label"><?php esc_html_e( 'Browse products', 'brickpoint' ); ?></span>
							<?php echo brickpoint_icon( 'arrow-right', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</a>
					<?php endif; ?>

					<?php
					echo brickpoint_whatsapp_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						array(
							'label'   => __( 'Ask a question', 'brickpoint' ),
							'message' => brickpoint_general_inquiry_message( get_the_title() ),
							'class'   => 'bp-btn bp-btn--whatsapp bp-btn--block',
						)
					);
					?>
				</div>

				<?php
				$related = brickpoint_query_related_by_term( get_the_ID(), 'category', 'post', 3 );

				if ( $related->have_posts() ) :
					?>
					<div class="bp-card">
						<h2 class="bp-card__title"><?php esc_html_e( 'Related reading', 'brickpoint' ); ?></h2>
						<ul class="bp-link-list">
							<?php
							while ( $related->have_posts() ) :
								$related->the_post();
								printf(
									'<li><a href="%1$s">%2$s</a></li>',
									esc_url( get_permalink() ),
									esc_html( get_the_title() )
								);
							endwhile;
							wp_reset_postdata();
							?>
						</ul>
					</div>
				<?php endif; ?>
			</aside>
		</div>

		<?php
		the_post_navigation(
			array(
				'prev_text' => '<span class="bp-nav-label">' . esc_html__( 'Previous', 'brickpoint' ) . '</span> %title',
				'next_text' => '<span class="bp-nav-label">' . esc_html__( 'Next', 'brickpoint' ) . '</span> %title',
			)
		);
		?>
	</article>

	<?php
endwhile;

get_footer();
