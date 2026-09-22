<?php
/**
 * Product archive (/products/).
 *
 * Elementor Pro "Archive → Products" templates override this file when set.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$per_page = 12;

brickpoint_page_hero(
	array(
		'title' => __( 'Products', 'brickpoint' ),
		'text'  => __( 'Bricks, cement, crush, sand, steel, pipes, chemicals and finishing materials. Prices and availability are confirmed on inquiry.', 'brickpoint' ),
	)
);
?>

<div class="bp-container bp-archive bp-archive--products">

	<?php
	$categories = brickpoint_get_product_categories( array( 'hide_empty' => true ) );

	if ( $categories ) :
		$current_term = is_tax( 'bp_product_category' ) ? get_queried_object() : null;
		?>
		<div class="bp-filters" role="group" aria-label="<?php esc_attr_e( 'Filter products by category', 'brickpoint' ); ?>">
			<a class="bp-chip<?php echo $current_term ? '' : ' is-active'; ?>" href="<?php echo esc_url( brickpoint_page_or_archive_url( 'products', 'bp_product' ) ); ?>">
				<?php esc_html_e( 'All products', 'brickpoint' ); ?>
			</a>

			<?php foreach ( $categories as $term ) : ?>
				<a class="bp-chip<?php echo ( $current_term instanceof WP_Term && $current_term->term_id === $term->term_id ) ? ' is-active' : ''; ?>" href="<?php echo esc_url( get_term_link( $term ) ); ?>">
					<?php echo esc_html( $term->name ); ?>
				</a>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php if ( have_posts() ) : ?>
		<div class="bp-product-grid" style="--bp-cols:3;--bp-cols-t:2;--bp-cols-m:1;">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content', 'product' );
			endwhile;
			?>
		</div>

		<?php brickpoint_pagination(); ?>
	<?php else : ?>
		<div class="bp-empty-state">
			<h2 class="bp-empty-state__title"><?php esc_html_e( 'No products published yet', 'brickpoint' ); ?></h2>
			<p><?php esc_html_e( 'Add your first product in WordPress → Products. You can also create a set of starter drafts from BrickPoint → Setup & Content.', 'brickpoint' ); ?></p>

			<?php
			echo brickpoint_whatsapp_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				array(
					'label' => __( 'Ask about availability', 'brickpoint' ),
					'class' => 'bp-btn bp-btn--whatsapp',
				)
			);
			?>
		</div>
	<?php endif; ?>

	<?php
	// Helpful cross-link to the materials overview page.
	$materials_url = brickpoint_page_url( 'construction-materials' );

	if ( $materials_url ) :
		?>
		<p class="bp-section__footer">
			<a class="bp-link-arrow" href="<?php echo esc_url( $materials_url ); ?>">
				<?php esc_html_e( 'See the full construction-materials range', 'brickpoint' ); ?>
				<?php echo brickpoint_icon( 'arrow-right', array( 'size' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</a>
		</p>
	<?php endif; ?>
</div>

<?php
unset( $per_page );

get_footer();
