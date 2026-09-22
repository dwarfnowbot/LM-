<?php
/**
 * Project loop item (archives, search results).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

brickpoint_project_card( get_the_ID() );
