<?php
/**
 * Site header.
 *
 * When Elementor Pro (Theme Builder) provides a header template it is used
 * instead; otherwise this file renders the BrickPoint header.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="bp-skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'brickpoint' ); ?></a>

<?php
// Elementor Pro header, if one is assigned.
if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'header' ) ) {
	// Elementor header rendered - still keep the mobile action bar from wp_footer.
	do_action( 'brickpoint_after_header' );
} else {
	$phone_display = brickpoint_phone_display();
	$email         = brickpoint_option( 'bp_email' );
	$socials       = brickpoint_get_social_links();
	$products_url  = brickpoint_page_url( 'products' );
	?>
	<header id="masthead" class="bp-header" data-bp-header data-sticky="1">
		<div class="bp-topbar">
			<div class="bp-container bp-topbar__inner">
				<ul class="bp-topbar__list">
					<?php if ( $phone_display ) : ?>
						<li class="bp-topbar__item">
							<a href="tel:<?php echo esc_attr( brickpoint_phone_raw() ); ?>">
								<?php echo brickpoint_icon( 'phone', array( 'size' => 15 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<span><?php echo esc_html( $phone_display ); ?></span>
							</a>
						</li>
					<?php endif; ?>

					<?php if ( $email ) : ?>
						<li class="bp-topbar__item bp-topbar__item--email">
							<a href="mailto:<?php echo esc_attr( $email ); ?>">
								<?php echo brickpoint_icon( 'mail', array( 'size' => 15 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<span><?php echo esc_html( $email ); ?></span>
							</a>
						</li>
					<?php endif; ?>

					<?php
					$hours = brickpoint_option( 'bp_hours' );

					if ( $hours ) :
						?>
						<li class="bp-topbar__item bp-topbar__item--hours">
							<?php echo brickpoint_icon( 'clock', array( 'size' => 15 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<span><?php echo esc_html( $hours ); ?></span>
						</li>
					<?php endif; ?>
				</ul>

				<?php if ( $socials ) : ?>
					<ul class="bp-topbar__social">
						<?php foreach ( $socials as $slug => $link ) : ?>
							<li>
								<a href="<?php echo esc_url( $link['url'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $link['label'] ); ?>">
									<?php echo brickpoint_icon( $slug, array( 'size' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		</div>

		<div class="bp-header__bar">
			<div class="bp-container bp-header__inner">
				<div class="bp-header__brand">
					<?php echo brickpoint_logo(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>
				</div>

				<nav class="bp-nav" id="bp-primary-nav" aria-label="<?php esc_attr_e( 'Primary menu', 'brickpoint' ); ?>">
					<?php
					if ( has_nav_menu( 'primary' ) ) {
						wp_nav_menu(
							array(
								'theme_location' => 'primary',
								'container'      => false,
								'menu_class'     => 'bp-menu',
								'depth'          => 3,
								'fallback_cb'    => false,
							)
						);
					} else {
						brickpoint_primary_menu_fallback();
					}
					?>
				</nav>

				<div class="bp-header__actions">
					<button type="button" class="bp-header__search-toggle" aria-expanded="false" aria-controls="bp-header-search" aria-label="<?php esc_attr_e( 'Search', 'brickpoint' ); ?>">
						<?php echo brickpoint_icon( 'grid', array( 'size' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</button>

					<?php
					echo brickpoint_whatsapp_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper.
						array(
							'label' => __( 'WhatsApp Us', 'brickpoint' ),
							'class' => 'bp-btn bp-btn--whatsapp bp-header__cta',
						)
					);
					?>

					<button type="button" class="bp-burger" aria-expanded="false" aria-controls="bp-primary-nav" aria-label="<?php esc_attr_e( 'Menu', 'brickpoint' ); ?>">
						<span class="bp-burger__line" aria-hidden="true"></span>
						<span class="bp-burger__line" aria-hidden="true"></span>
						<span class="bp-burger__line" aria-hidden="true"></span>
					</button>
				</div>
			</div>

			<div class="bp-header__search" id="bp-header-search" hidden>
				<div class="bp-container">
					<form class="bp-header__search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" data-bp-search-form>
						<label class="screen-reader-text" for="bp-header-search-input"><?php esc_html_e( 'Search products, videos and pages', 'brickpoint' ); ?></label>
						<input type="search" id="bp-header-search-input" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search products, videos, pages…', 'brickpoint' ); ?>" data-bp-search-input autocomplete="off" />
						<button type="submit" class="bp-btn bp-btn--primary"><?php esc_html_e( 'Search', 'brickpoint' ); ?></button>
						<div class="bp-search-results" data-bp-search-results hidden></div>
					</form>
				</div>
			</div>
		</div>
	</header>
	<?php
}

do_action( 'brickpoint_after_header' );
?>
<main id="content" class="bp-main" tabindex="-1">
