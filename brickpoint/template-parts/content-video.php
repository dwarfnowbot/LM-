<?php
/**
 * Video loop item (archives, search results).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

brickpoint_video_card( get_the_ID() );
