<?php
/**
 * Single product page.
 *
 * Elementor Pro "Single → Products" templates override this file when set.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$product_id   = get_the_ID();
	$availability = brickpoint_availability_badge( $product_id );
	$badge        = brickpoint_meta( $product_id, '_bp_badge' );
	$terms        = get_the_terms( $product_id, 'bp_product_category' );
	$category     = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0] : null;
	$video_id     = (int) brickpoint_meta( $product_id, '_bp_video', 0 );
	$video_url    = brickpoint_meta( $product_id, '_bp_video_url' );
	$short        = has_excerpt() ? get_the_excerpt() : '';
	?>

	<div class="bp-container">
		<?php brickpoint_breadcrumbs(); ?>
	</div>

	<article id="product-<?php the_ID(); ?>" <?php post_class( 'bp-single-product' ); ?>>
		<div class="bp-container bp-single-product__top">

			<div class="bp-single-product__media">
				<?php brickpoint_product_gallery( $product_id ); ?>

				<?php if ( $video_id || $video_url ) : ?>
					<div class="bp-single-product__video">
						<?php
						brickpoint_inline_video(
							array(
								'video_id'  => $video_id,
								'url'       => $video_url,
								'autoplay'  => false,
								'poster_id' => has_post_thumbnail() ? (int) get_post_thumbnail_id() : 0,
								'label'     => get_the_title(),
							)
						);
						?>
					</div>
				<?php endif; ?>
			</div>

			<div class="bp-single-product__summary">
				<?php if ( $category instanceof WP_Term ) : ?>
					<a class="bp-product-card__cat" href="<?php echo esc_url( get_term_link( $category ) ); ?>"><?php echo esc_html( $category->name ); ?></a>
				<?php endif; ?>

				<h1 class="bp-single-product__title"><?php the_title(); ?></h1>

				<?php if ( $badge ) : ?>
					<span class="bp-badge bp-badge--product"><?php echo esc_html( $badge ); ?></span>
				<?php endif; ?>

				<?php if ( $short ) : ?>
					<p class="bp-single-product__excerpt"><?php echo esc_html( $short ); ?></p>
				<?php endif; ?>

				<div class="bp-single-product__price">
					<?php echo brickpoint_price_html( $product_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

					<?php if ( $availability['label'] ) : ?>
						<span class="bp-badge bp-badge--availability <?php echo esc_attr( $availability['status'] ); ?>"><?php echo esc_html( $availability['label'] ); ?></span>
					<?php endif; ?>
				</div>

				<?php brickpoint_product_meta_list( $product_id ); ?>

				<?php brickpoint_product_actions( $product_id ); ?>

				<?php brickpoint_share_bar( $product_id, __( 'Share this product', 'brickpoint' ) ); ?>
			</div>
		</div>

		<div class="bp-container bp-single-product__body">
			<div class="bp-single-product__content">
				<section class="bp-product-description">
					<h2 class="bp-section__title"><?php esc_html_e( 'Product description', 'brickpoint' ); ?></h2>
					<div class="bp-content"><?php the_content(); ?></div>
				</section>

				<?php
				brickpoint_product_specs( $product_id );
				brickpoint_product_features( $product_id );
				?>
			</div>

			<aside class="bp-single-product__aside">
				<div class="bp-card">
					<h2 class="bp-card__title"><?php esc_html_e( 'Need a quotation?', 'brickpoint' ); ?></h2>
					<p><?php esc_html_e( 'Send your quantity and delivery location — we will confirm rate and dispatch.', 'brickpoint' ); ?></p>

					<?php
					echo brickpoint_whatsapp_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						array(
							'label'   => __( 'Request Quote on WhatsApp', 'brickpoint' ),
							'message' => brickpoint_product_inquiry_message( $product_id ),
							'class'   => 'bp-btn bp-btn--whatsapp bp-btn--block',
							'product' => $product_id,
						)
					);

					brickpoint_phone_button(
						array(
							'label' => __( 'Call Now', 'brickpoint' ),
							'class' => 'bp-btn bp-btn--ghost bp-btn--block',
						)
					);
					?>
				</div>

				<?php
				// Related product categories.
				$all_categories = brickpoint_get_product_categories( array( 'hide_empty' => false, 'number' => 8 ) );

				if ( $all_categories ) :
					?>
					<div class="bp-card">
						<h2 class="bp-card__title"><?php esc_html_e( 'Browse categories', 'brickpoint' ); ?></h2>
						<ul class="bp-link-list">
							<?php foreach ( $all_categories as $item ) : ?>
								<li>
									<a href="<?php echo esc_url( get_term_link( $item ) ); ?>">
										<?php echo esc_html( $item->name ); ?>
										<span class="bp-count"><?php echo esc_html( number_format_i18n( (int) $item->count ) ); ?></span>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>

				<?php
				// Related videos for this product.
				if ( function_exists( 'brickpoint_get_related_video_ids' ) ) {
					$video_ids = brickpoint_get_related_video_ids( $product_id, '_bp_related_videos', 2 );

					if ( $video_ids ) {
						echo '<div class="bp-card">';
						echo '<h2 class="bp-card__title">' . esc_html__( 'Videos', 'brickpoint' ) . '</h2>';

						foreach ( $video_ids as $vid ) {
							brickpoint_video_card( $vid, array( 'show_excerpt' => false ) );
						}

						echo '</div>';
					}
				}
				?>
			</aside>
		</div>

		<div class="bp-container">
			<?php brickpoint_related_products( $product_id, array( 'title' => __( 'Related products', 'brickpoint' ), 'limit' => 3 ) ); ?>
		</div>
	</article>

	<?php
endwhile;

get_footer();
