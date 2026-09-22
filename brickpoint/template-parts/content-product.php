<?php
/**
 * Product loop item (archives, search results).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

brickpoint_product_card( get_the_ID() );
