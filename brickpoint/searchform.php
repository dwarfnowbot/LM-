<?php
/**
 * Search form.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$bp_search_id = 'bp-search-' . wp_rand( 1000, 9999 );
?>
<form role="search" method="get" class="bp-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $bp_search_id ); ?>"><?php esc_html_e( 'Search for:', 'brickpoint' ); ?></label>
	<input type="search" id="<?php echo esc_attr( $bp_search_id ); ?>" class="bp-search-form__input" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search products, materials, videos…', 'brickpoint' ); ?>" />
	<button type="submit" class="bp-btn bp-btn--primary">
		<span class="bp-btn__label"><?php esc_html_e( 'Search', 'brickpoint' ); ?></span>
	</button>
</form>
