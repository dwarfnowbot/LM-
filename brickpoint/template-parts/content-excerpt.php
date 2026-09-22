<?php
/**
 * Simple excerpt list item (used by compact loops and widgets).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<li class="bp-list-item">
	<a href="<?php the_permalink(); ?>">
		<span class="bp-list-item__title"><?php the_title(); ?></span>
		<span class="bp-list-item__meta"><?php echo esc_html( get_the_date() ); ?></span>
	</a>
</li>
