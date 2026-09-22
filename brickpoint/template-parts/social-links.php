<?php
/**
 * Social links list (used in header, footer, contact page and share blocks).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$bp_socials = brickpoint_get_social_links();

if ( ! $bp_socials ) {
	return;
}

$bp_style = isset( $args['style'] ) ? $args['style'] : 'solid';
?>
<ul class="bp-social bp-social--<?php echo esc_attr( $bp_style ); ?>">
	<?php foreach ( $bp_socials as $bp_slug => $bp_link ) : ?>
		<li>
			<a class="bp-social__link" href="<?php echo esc_url( $bp_link['url'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $bp_link['label'] ); ?>">
				<?php echo brickpoint_icon( $bp_slug, array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</a>
		</li>
	<?php endforeach; ?>
</ul>
