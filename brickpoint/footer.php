<?php
/**
 * Site footer.
 *
 * Elementor Pro footer templates take precedence when assigned.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Close the <main> opened in header.php.
echo '</main>';

if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'footer' ) ) {
	wp_footer();
	echo '</body>\n</html>';
	return;
}

$socials      = brickpoint_get_social_links();
$phone        = brickpoint_phone_display();
$email        = brickpoint_option( 'bp_email' );
$address      = brickpoint_option( 'bp_address' );
$hours        = brickpoint_option( 'bp_hours' );
$products_url = brickpoint_page_url( 'products' );
?>

<footer id="colophon" class="bp-footer">
	<div class="bp-container">

		<div class="bp-footer__cta bp-reveal">
			<div class="bp-footer__cta-text">
				<h2 class="bp-footer__cta-title"><?php esc_html_e( 'Ready to order bricks and construction materials?', 'brickpoint' ); ?></h2>
				<p><?php esc_html_e( 'Send your material list, quantity and delivery location - we will confirm availability and rates.', 'brickpoint' ); ?></p>
			</div>
			<div class="bp-footer__cta-actions">
				<?php
				echo brickpoint_whatsapp_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					array(
						'label' => __( 'Order on WhatsApp', 'brickpoint' ),
						'class' => 'bp-btn bp-btn--whatsapp bp-btn--lg',
					)
				);

				brickpoint_phone_button(
					array(
						'label' => __( 'Call Now', 'brickpoint' ),
						'class' => 'bp-btn bp-btn--ghost bp-btn--lg',
					)
				);
				?>
			</div>
		</div>

		<div class="bp-footer__grid">

			<div class="bp-footer__col bp-footer__col--brand">
				<?php echo brickpoint_logo( array( 'class' => 'bp-logo bp-logo--footer', 'footer' => true ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

				<p class="bp-footer__about"><?php echo esc_html( brickpoint_option( 'bp_footer_about' ) ); ?></p>

				<?php if ( $socials ) : ?>
					<ul class="bp-social bp-social--outline bp-footer__social">
						<?php foreach ( $socials as $slug => $link ) : ?>
							<li>
								<a class="bp-social__link" href="<?php echo esc_url( $link['url'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $link['label'] ); ?>">
									<?php echo brickpoint_icon( $slug, array( 'size' => 17 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>

			<div class="bp-footer__col">
				<h3 class="bp-footer__title"><?php esc_html_e( 'Construction Materials', 'brickpoint' ); ?></h3>
				<?php
				if ( has_nav_menu( 'footer_products' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'footer_products',
							'container'      => false,
							'menu_class'     => 'bp-footer__menu',
							'depth'          => 1,
							'fallback_cb'    => false,
						)
					);
				} else {
					$terms = brickpoint_get_product_categories( array( 'number' => 8, 'hide_empty' => false ) );

					if ( $terms ) {
						echo '<ul class="bp-footer__menu">';

						foreach ( $terms as $term ) {
							printf(
								'<li><a href="%1$s">%2$s</a></li>',
								esc_url( get_term_link( $term ) ),
								esc_html( $term->name )
							);
						}

						echo '</ul>';
					}
				}
				?>
			</div>

			<div class="bp-footer__col">
				<h3 class="bp-footer__title"><?php esc_html_e( 'Company', 'brickpoint' ); ?></h3>
				<?php
				if ( has_nav_menu( 'footer_company' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'footer_company',
							'container'      => false,
							'menu_class'     => 'bp-footer__menu',
							'depth'          => 1,
							'fallback_cb'    => false,
						)
					);
				} else {
					$links = array(
						__( 'About Us', 'brickpoint' )                => brickpoint_page_url( 'about-us' ),
						__( 'Projects', 'brickpoint' )                => get_post_type_archive_link( 'bp_project' ),
						__( 'Videos', 'brickpoint' )                  => get_post_type_archive_link( 'bp_video' ),
						__( 'Locations', 'brickpoint' )               => get_post_type_archive_link( 'bp_location' ),
						__( 'For Contractors', 'brickpoint' )         => brickpoint_page_url( 'for-contractors' ),
						__( 'For Builders', 'brickpoint' )            => brickpoint_page_url( 'for-builders' ),
						__( 'For Construction Companies', 'brickpoint' ) => brickpoint_page_url( 'for-construction-companies' ),
						__( 'Blog', 'brickpoint' )                    => brickpoint_page_url( 'blog' ),
					);

					echo '<ul class="bp-footer__menu">';

					foreach ( $links as $label => $url ) {
						if ( ! $url ) {
							continue;
						}

						printf( '<li><a href="%1$s">%2$s</a></li>', esc_url( $url ), esc_html( $label ) );
					}

					echo '</ul>';
				}
				?>
			</div>

			<div class="bp-footer__col bp-footer__col--contact">
				<h3 class="bp-footer__title"><?php esc_html_e( 'Contact', 'brickpoint' ); ?></h3>

				<ul class="bp-footer__contact">
					<?php if ( $phone ) : ?>
						<li>
							<?php echo brickpoint_icon( 'phone', array( 'size' => 17 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<a href="tel:<?php echo esc_attr( brickpoint_phone_raw() ); ?>"><?php echo esc_html( $phone ); ?></a>
						</li>
					<?php endif; ?>

					<?php if ( $email ) : ?>
						<li>
							<?php echo brickpoint_icon( 'mail', array( 'size' => 17 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
						</li>
					<?php endif; ?>

					<?php if ( $address ) : ?>
						<li>
							<?php echo brickpoint_icon( 'pin', array( 'size' => 17 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<span><?php echo esc_html( $address ); ?></span>
						</li>
					<?php endif; ?>

					<?php if ( $hours ) : ?>
						<li>
							<?php echo brickpoint_icon( 'clock', array( 'size' => 17 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<span><?php echo esc_html( $hours ); ?></span>
						</li>
					<?php endif; ?>
				</ul>

				<?php
				$locations = get_posts(
					array(
						'post_type'      => 'bp_location',
						'posts_per_page' => 4,
						'orderby'        => 'menu_order',
						'order'          => 'ASC',
						'meta_key'       => '_bp_location_order', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
					)
				);

				if ( $locations ) {
					echo '<h4 class="bp-footer__subtitle">' . esc_html__( 'Locations', 'brickpoint' ) . '</h4><ul class="bp-footer__menu bp-footer__menu--compact">';

					foreach ( $locations as $location ) {
						printf(
							'<li><a href="%1$s">%2$s</a></li>',
							esc_url( get_permalink( $location ) ),
							esc_html( get_the_title( $location ) )
						);
					}

					echo '</ul>';
				}
				?>
			</div>
		</div>

		<div class="bp-footer__bottom">
			<p class="bp-footer__copy">
				<?php
				$copyright = brickpoint_option( 'bp_footer_copyright' );
				echo esc_html( str_replace( '{year}', gmdate( 'Y' ), $copyright ) );
				?>
			</p>

			<?php
			if ( has_nav_menu( 'footer_support' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'footer_support',
						'container'      => false,
						'menu_class'     => 'bp-footer__legal',
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
			} else {
				echo '<ul class="bp-footer__legal">';

				$privacy = get_option( 'wp_page_for_privacy_policy' );

				if ( $privacy ) {
					printf(
						'<li><a href="%1$s">%2$s</a></li>',
						esc_url( get_permalink( $privacy ) ),
						esc_html__( 'Privacy Policy', 'brickpoint' )
					);
				}

				$terms = brickpoint_page_url( 'terms-and-conditions' );

				if ( $terms ) {
					printf(
						'<li><a href="%1$s">%2$s</a></li>',
						esc_url( $terms ),
						esc_html__( 'Terms and Conditions', 'brickpoint' )
					);
				}

				if ( $products_url ) {
					printf(
						'<li><a href="%1$s">%2$s</a></li>',
						esc_url( $products_url ),
						esc_html__( 'Products', 'brickpoint' )
					);
				}

				echo '</ul>';
			}
			?>

			<?php
			$note = brickpoint_option( 'bp_footer_note' );

			if ( $note ) :
				?>
				<p class="bp-footer__note"><?php echo esc_html( $note ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
