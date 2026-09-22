<?php
/**
 * Video card partial.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$bp_args = isset( $args ) && is_array( $args ) ? $args : array();

brickpoint_video_card( get_the_ID(), $bp_args );
