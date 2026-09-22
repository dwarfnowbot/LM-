<?php
/**
 * Fallback page.
 *
 * Rendered only when the theme could not load all of its files - for example
 * after an interrupted upload left a file truncated, when the server runs an
 * unsupported PHP version, or when BRICKPOINT_SAFE_MODE is switched on.
 *
 * It must stay dependency-free: core functions and inline CSS only, so it can
 * never fail for the same reason the theme did.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$brickpoint_errors = isset( $GLOBALS['brickpoint_load_errors'] ) ? (array) $GLOBALS['brickpoint_load_errors'] : array();
$brickpoint_name   = function_exists( 'get_bloginfo' ) ? get_bloginfo( 'name' ) : 'WordPress';
$brickpoint_mailto = function_exists( 'admin_url' ) ? admin_url() : '/wp-admin/';

?><!DOCTYPE html>
<html <?php if ( function_exists( 'language_attributes' ) ) { language_attributes(); } ?>>
<head>
	<meta charset="<?php if ( function_exists( 'bloginfo' ) ) { bloginfo( 'charset' ); } ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="robots" content="noindex, nofollow" />
	<title><?php echo esc_html( $brickpoint_name ); ?> &middot; <?php esc_html_e( 'Temporarily unavailable', 'brickpoint' ); ?></title>
	<?php if ( function_exists( 'wp_head' ) ) { wp_head(); } ?>
	<style>
		body { margin:0; background:#0e0f11; color:#e9e5de; font-family:system-ui,-apple-system,"Segoe UI",Roboto,Helvetica,Arial,sans-serif; line-height:1.6; }
		.bp-fallback { max-width:640px; margin:0 auto; padding:14vh 24px 18vh; }
		.bp-fallback__mark { display:inline-flex; align-items:center; justify-content:center; width:48px; height:48px; border-radius:12px; background:#c1440e; color:#fff; font-weight:700; font-size:22px; margin-bottom:22px; }
		.bp-fallback h1 { font-size:1.9rem; line-height:1.15; margin:0 0 .5em; color:#fff; }
		.bp-fallback p { margin:0 0 1em; color:#bdb7ad; }
		.bp-fallback a { color:#e2571e; }
		.bp-fallback__box { margin-top:26px; padding:18px 20px; border:1px solid rgba(255,255,255,.14); border-radius:12px; background:rgba(255,255,255,.05); }
		.bp-fallback__box h2 { font-size:1rem; margin:0 0 .6em; color:#fff; }
		.bp-fallback__box code { display:block; font-size:.82rem; color:#ffb08a; word-break:break-word; margin-bottom:6px; }
		.bp-fallback__list { list-style:none; padding:0; margin:0; }
		.bp-fallback__list li { margin:0 0 10px; }
		.bp-fallback__list a { text-decoration:none; }
		.bp-fallback__small { font-size:.85rem; color:#8f8a82; margin-top:22px; }
	</style>
</head>
<body class="bp-fallback-body">
	<div class="bp-fallback">
		<span class="bp-fallback__mark" aria-hidden="true">B</span>

		<h1><?php esc_html_e( 'This site is temporarily unavailable', 'brickpoint' ); ?></h1>

		<p><?php esc_html_e( 'The BrickPoint theme on this website did not load completely, so the page cannot be shown right now. This is a theme installation problem - no data has been lost and WordPress itself is running normally.', 'brickpoint' ); ?></p>

		<?php if ( current_user_can( 'manage_options' ) ) : ?>
			<div class="bp-fallback__box">
				<h2><?php esc_html_e( 'Administrator: what to do', 'brickpoint' ); ?></h2>

				<?php if ( $brickpoint_errors ) : ?>
					<p><?php esc_html_e( 'Files that failed to load:', 'brickpoint' ); ?></p>
					<?php foreach ( $brickpoint_errors as $brickpoint_error ) : ?>
						<code><?php echo esc_html( $brickpoint_error ); ?></code>
					<?php endforeach; ?>
				<?php endif; ?>

				<ul class="bp-fallback__list">
					<li>1. <?php esc_html_e( 'Rename or delete the folder wp-content/themes/brickpoint (cPanel File Manager or FTP).', 'brickpoint' ); ?></li>
					<li>2. <?php printf( /* translators: %s: link to the themes screen. */ esc_html__( 'Open %s - WordPress will switch to a working default theme automatically and your site will be back online.', 'brickpoint' ), '<a href="' . esc_url( $brickpoint_mailto ) . 'themes.php">' . esc_html__( 'Appearance → Themes', 'brickpoint' ) . '</a>' ); ?></li>
					<li>3. <?php esc_html_e( 'Install BrickPoint again from a fresh download (or upload the files with File Manager and extract them there) and activate it.', 'brickpoint' ); ?></li>
				</ul>
			</div>
		<?php else : ?>
			<p><?php esc_html_e( 'Please check back shortly, or contact us on WhatsApp and we will help you right away.', 'brickpoint' ); ?></p>
		<?php endif; ?>

		<p class="bp-fallback__small">
			<a href="<?php echo esc_url( $brickpoint_mailto ); ?>"><?php esc_html_e( 'Site dashboard', 'brickpoint' ); ?></a>
		</p>
	</div>
	<?php if ( function_exists( 'wp_footer' ) ) { wp_footer(); } ?>
</body>
</html>
