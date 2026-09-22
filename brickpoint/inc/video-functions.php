<?php
/**
 * Video system: source resolution, poster handling, lazy players, lightbox.
 *
 * Performance rules: thumbnail-first, click-to-load, poster images, no
 * autoplay with sound, no render-blocking embeds.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Resolve everything needed to render a video.
 *
 * @param int   $video_id Video post ID.
 * @param array $args     thumbnail_size.
 * @return array<string,mixed>
 */
function brickpoint_get_video_data( $video_id = 0, $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'thumbnail_size' => 'bp-card-wide',
		)
	);

	$video_id = $video_id ? (int) $video_id : get_the_ID();

	$data = array(
		'id'             => $video_id,
		'title'          => get_the_title( $video_id ),
		'permalink'      => get_permalink( $video_id ),
		'source'         => brickpoint_meta( $video_id, '_bp_video_source', 'youtube' ),
		'url'            => brickpoint_meta( $video_id, '_bp_video_url' ),
		'file'           => (int) brickpoint_meta( $video_id, '_bp_video_file', 0 ),
		'duration'       => brickpoint_meta( $video_id, '_bp_video_duration' ),
		'duration_schema' => '',
		'aspect'         => brickpoint_meta( $video_id, '_bp_video_aspect', '16-9' ),
		'captions'       => brickpoint_meta( $video_id, '_bp_video_captions' ),
		'excerpt'        => brickpoint_excerpt( 18 ),
		'thumbnail'      => '',
		'thumbnail_id'   => 0,
		'embed_url'      => '',
		'mp4'            => '',
		'is_playable'    => false,
		'featured'       => (bool) brickpoint_meta( $video_id, '_bp_featured' ),
	);

	if ( has_post_thumbnail( $video_id ) ) {
		$data['thumbnail_id'] = (int) get_post_thumbnail_id( $video_id );
		$data['thumbnail']    = (string) wp_get_attachment_image_url( $data['thumbnail_id'], $args['thumbnail_size'] );
	}

	$url = $data['url'];

	if ( $url ) {
		$provider = brickpoint_detect_video_provider( $url );

		if ( $provider ) {
			$data['source'] = $provider;
		}
	}

	switch ( $data['source'] ) {
		case 'self':
			if ( $data['file'] ) {
				$data['mp4'] = (string) wp_get_attachment_url( $data['file'] );
			} elseif ( $url ) {
				$data['mp4'] = $url;
			}
			break;

		case 'external':
			$data['mp4'] = $url;
			break;

		case 'vimeo':
			$data['embed_url'] = brickpoint_vimeo_embed_url( $url );
			$data['mp4']       = brickpoint_vimeo_direct_file( $url );
			break;

		case 'youtube':
		default:
			$data['embed_url'] = brickpoint_youtube_embed_url( $url );
			break;
	}

	if ( $data['mp4'] ) {
		$data['is_playable'] = true;
	} elseif ( $data['embed_url'] ) {
		$data['is_playable'] = true;
	}

	// Duration -> ISO 8601 for schema (support mm:ss and hh:mm:ss).
	if ( $data['duration'] && preg_match( '/^(?:(\d+):)?(\d{1,2}):(\d{2})$/', $data['duration'], $m ) ) {
		$hours   = isset( $m[1] ) && '' !== $m[1] ? (int) $m[1] : 0;
		$minutes = (int) $m[2];
		$seconds = (int) $m[3];
		$data['duration_schema'] = 'PT' . ( $hours ? $hours . 'H' : '' ) . ( $minutes ? $minutes . 'M' : '' ) . $seconds . 'S';
	}

	/**
	 * Filter resolved video data.
	 *
	 * @param array $data     Video data.
	 * @param int   $video_id Video ID.
	 */
	return apply_filters( 'brickpoint_video_data', $data, $video_id );
}

/**
 * Detect the provider from a URL.
 *
 * @param string $url URL.
 * @return string youtube|vimeo|external|''
 */
function brickpoint_detect_video_provider( $url ) {
	if ( ! $url ) {
		return '';
	}

	$host = wp_parse_url( $url, PHP_URL_HOST );
	$host = $host ? strtolower( $host ) : '';

	if ( false !== strpos( $host, 'youtube' ) || false !== strpos( $host, 'youtu.be' ) ) {
		return 'youtube';
	}

	if ( false !== strpos( $host, 'vimeo' ) ) {
		return 'vimeo';
	}

	if ( preg_match( '/\.(mp4|webm|ogv|m4v)(\?|$)/i', $url ) ) {
		return 'external';
	}

	return '';
}

/**
 * Extract a YouTube video ID.
 *
 * @param string $url URL.
 * @return string
 */
function brickpoint_youtube_id( $url ) {
	if ( ! $url ) {
		return '';
	}

	$patterns = array(
		'/youtu\.be\/([A-Za-z0-9_\-]{6,})/i',
		'/youtube\.com\/(?:watch\?v=|embed\/|v\/|shorts\/)([A-Za-z0-9_\-]{6,})/i',
		'/youtube\.com\/watch\?.*v=([A-Za-z0-9_\-]{6,})/i',
	);

	foreach ( $patterns as $pattern ) {
		if ( preg_match( $pattern, $url, $matches ) ) {
			return $matches[1];
		}
	}

	// A raw ID may have been pasted.
	if ( preg_match( '/^[A-Za-z0-9_\-]{11}$/', $url ) ) {
		return $url;
	}

	return '';
}

/**
 * Privacy-friendly YouTube embed URL (no-cookie + lazy params).
 *
 * @param string $url URL or ID.
 * @return string
 */
function brickpoint_youtube_embed_url( $url ) {
	$id = brickpoint_youtube_id( $url );

	if ( ! $id ) {
		return '';
	}

	$args = array(
		'rel'            => 0,
		'modestbranding' => 1,
		'playsinline'    => 1,
		'enablejsapi'    => 1,
		'autoplay'       => 1,
		'mute'           => 1,
	);

	/**
	 * Filter YouTube embed parameters.
	 *
	 * @param array  $args Embed args.
	 * @param string $id   Video ID.
	 */
	$args = apply_filters( 'brickpoint_youtube_embed_args', $args, $id );

	return add_query_arg( $args, 'https://www.youtube-nocookie.com/embed/' . $id );
}

/**
 * Extract a Vimeo ID.
 *
 * @param string $url URL.
 * @return string
 */
function brickpoint_vimeo_id( $url ) {
	if ( ! $url ) {
		return '';
	}

	if ( preg_match( '/vimeo\.com\/(?:video\/)?(\d{6,})/i', $url, $matches ) ) {
		return $matches[1];
	}

	if ( preg_match( '/^\d{6,}$/', $url ) ) {
		return $url;
	}

	return '';
}

/**
 * Vimeo embed URL.
 *
 * @param string $url URL.
 * @return string
 */
function brickpoint_vimeo_embed_url( $url ) {
	$id = brickpoint_vimeo_id( $url );

	if ( ! $id ) {
		return '';
	}

	$args = array(
		'title'    => 0,
		'byline'   => 0,
		'portrait' => 0,
		'dnt'      => 1,
		'muted'    => 1,
	);

	return add_query_arg( $args, 'https://player.vimeo.com/video/' . $id );
}

/**
 * Try to get a direct MP4 for a Vimeo video (public videos only).
 *
 * Uses the public oEmbed/player config endpoint - if it fails we fall back to
 * the iframe embed, so nothing breaks.
 *
 * @param string $url Vimeo URL.
 * @return string
 */
function brickpoint_vimeo_direct_file( $url ) {
	$id = brickpoint_vimeo_id( $url );

	if ( ! $id ) {
		return '';
	}

	$cache_key = 'bp_vimeo_' . $id;
	$cached    = get_transient( $cache_key );

	if ( 'none' === $cached ) {
		return '';
	}

	if ( $cached ) {
		return $cached;
	}

	$response = wp_remote_get(
		'https://player.vimeo.com/video/' . $id . '/config',
		array(
			'timeout'    => 4,
			'user-agent' => 'BrickPoint WordPress Theme',
		)
	);

	$file = '';

	if ( ! is_wp_error( $response ) && 200 === (int) wp_remote_retrieve_response_code( $response ) ) {
		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( isset( $body['request']['files']['progressive'] ) && is_array( $body['request']['files']['progressive'] ) ) {
			foreach ( $body['request']['files']['progressive'] as $item ) {
				if ( ! empty( $item['url'] ) ) {
					$file = $item['url'];
				}
			}
		} elseif ( isset( $body['request']['files']['hls']['cdns']['default']['url'] ) ) {
			$file = $body['request']['files']['hls']['cdns']['default']['url'];
		}
	}

	set_transient( $cache_key, $file ? $file : 'none', HOUR_IN_SECONDS * 6 );

	return $file;
}

/**
 * Render a video card (used by grids, homepage sections, related content).
 *
 * @param int   $video_id Video post ID.
 * @param array $args      show_meta, show_title, show_excerpt, style, aspect.
 * @return void
 */
function brickpoint_video_card( $video_id = 0, $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'show_meta'    => true,
			'show_title'   => true,
			'show_excerpt' => true,
			'escaping'     => true,
			'style'        => 'card',
		)
	);

	$data = brickpoint_get_video_data( $video_id );

	if ( ! $data['title'] ) {
		return;
	}

	$terms    = get_the_terms( $data['id'], 'bp_video_category' );
	$category = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
	$aspect   = str_replace( '-', '/', $data['aspect'] );

	printf(
		'<article class="bp-video-card bp-video-card--%1$s bp-reveal" data-bp-video-item data-video-source="%2$s" data-aspect="%3$s">',
		esc_attr( $args['style'] ),
		esc_attr( $data['source'] ),
		esc_attr( $aspect )
	);

	// Media.
	echo '<div class="bp-video-card__media bp-media bp-media--video">';

	printf(
		'<a class="bp-video-card__thumb bp-media__inner bp-js-video-trigger" href="%1$s" data-video-id="%2$d" data-embed="%3$s" data-mp4="%4$s" data-captions="%5$s" data-title="%6$s" aria-label="%7$s">',
		esc_url( $data['permalink'] ),
		(int) $data['id'],
		esc_attr( $data['embed_url'] ),
		esc_attr( $data['mp4'] ),
		esc_attr( $data['captions'] ),
		esc_attr( $data['title'] ),
		esc_attr( sprintf( /* translators: %s: video title. */ __( 'Play video: %s', 'brickpoint' ), $data['title'] ) )
	);

	if ( $data['thumbnail_id'] ) {
		echo wp_get_attachment_image(
			$data['thumbnail_id'],
			'bp-card-wide',
			false,
			array(
				'class'   => 'bp-media__img',
				'loading' => 'lazy',
				'alt'     => $data['title'],
			)
		);
	} else {
		echo brickpoint_placeholder( '16x9' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	echo '<span class="bp-video-card__overlay" aria-hidden="true"></span>';
	echo '<span class="bp-play" aria-hidden="true">' . brickpoint_icon( 'play', array( 'size' => 26 ) ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	if ( $data['duration'] ) {
		echo '<span class="bp-video-card__duration">' . esc_html( $data['duration'] ) . '</span>';
	}

	echo '</a>';

	if ( $data['featured'] ) {
		echo '<span class="bp-badge bp-badge--featured">' . esc_html__( 'Featured', 'brickpoint' ) . '</span>';
	}

	echo '</div>';

	// Body.
	echo '<div class="bp-video-card__body">';

	if ( $args['show_meta'] && ( $category || $data['duration'] ) ) {
		echo '<div class="bp-video-card__meta">';

		if ( $category ) {
			echo '<span class="bp-video-card__cat">' . esc_html( $category ) . '</span>';
		}

		if ( $data['duration'] ) {
			echo '<span class="bp-video-card__time">' . brickpoint_icon( 'clock', array( 'size' => 14 ) ) . esc_html( $data['duration'] ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo '</div>';
	}

	if ( $args['show_title'] ) {
		printf(
			'<h3 class="bp-video-card__title"><a href="%1$s">%2$s</a></h3>',
			esc_url( $data['permalink'] ),
			esc_html( $data['title'] )
		);
	}

	if ( $args['show_excerpt'] && $data['excerpt'] ) {
		echo '<p class="bp-video-card__desc">' . esc_html( $data['excerpt'] ) . '</p>';
	}

	echo '<span class="bp-video-card__actions">';

	printf(
		'<button type="button" class="bp-btn bp-btn--ghost bp-btn--sm bp-js-video-trigger" data-video-id="%1$d" data-embed="%2$s" data-mp4="%3$s" data-captions="%4$s" data-title="%5$s">%6$s<span class="bp-btn__label">%7$s</span></button>',
		(int) $data['id'],
		esc_attr( $data['embed_url'] ),
		esc_attr( $data['mp4'] ),
		esc_attr( $data['captions'] ),
		esc_attr( $data['title'] ),
		brickpoint_icon( 'play', array( 'size' => 16 ) ),
		esc_html__( 'Watch', 'brickpoint' )
	);

	printf(
		'<a class="bp-video-card__link" href="%1$s">%2$s%3$s</a>',
		esc_url( $data['permalink'] ),
		esc_html__( 'Details', 'brickpoint' ),
		brickpoint_icon( 'arrow-right', array( 'size' => 15 ) )
	);

	echo '</span>';

	echo '</div></article>';
}

/**
 * Inline lazy video block: poster + click to load player.
 *
 * @param array $args video_id, aspect, autoplay, class, caption.
 * @return void
 */
function brickpoint_inline_video( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'video_id'  => 0,
			'url'       => '',
			'aspect'    => '16-9',
			'autoplay'  => true,
			'class'     => '',
			'caption'   => '',
			'poster_id' => 0,
			'label'     => '',
		)
	);

	$data = array();

	if ( $args['video_id'] ) {
		$data = brickpoint_get_video_data( $args['video_id'] );
	} elseif ( $args['url'] ) {
		$provider = brickpoint_detect_video_provider( $args['url'] );

		$data = array(
			'title'       => $args['label'],
			'embed_url'   => 'youtube' === $provider ? brickpoint_youtube_embed_url( $args['url'] ) : ( 'vimeo' === $provider ? brickpoint_vimeo_embed_url( $args['url'] ) : '' ),
			'mp4'         => in_array( $provider, array( 'external' ), true ) || 'self' === $provider ? $args['url'] : '',
			'source'      => $provider ? $provider : 'external',
			'captions'    => '',
			'thumbnail'   => '',
			'thumbnail_id' => (int) $args['poster_id'],
			'permalink'   => '',
		);
	}

	if ( ! $data || ( empty( $data['embed_url'] ) && empty( $data['mp4'] ) ) ) {
		return;
	}

	$aspect    = str_replace( '-', '/', $args['aspect'] );
	$poster_id = $args['poster_id'] ? (int) $args['poster_id'] : ( isset( $data['thumbnail_id'] ) ? (int) $data['thumbnail_id'] : 0 );

	printf(
		'<figure class="bp-video bp-video--inline %1$s bp-js-video-block" data-autoplay="%2$d" data-aspect="%3$s" style="--bp-video-aspect:%4$s">',
		esc_attr( $args['class'] ),
		$args['autoplay'] ? 1 : 0,
		esc_attr( $aspect ),
		esc_attr( $aspect )
	);

	echo '<div class="bp-video__frame">';

	// Poster button (click to load).
	echo '<button type="button" class="bp-video__poster bp-js-video-trigger" data-video-id="' . (int) $args['video_id'] . '" data-embed="' . esc_attr( isset( $data['embed_url'] ) ? $data['embed_url'] : '' ) . '" data-mp4="' . esc_attr( isset( $data['mp4'] ) ? $data['mp4'] : '' ) . '" data-captions="' . esc_attr( isset( $data['captions'] ) ? $data['captions'] : '' ) . '" data-title="' . esc_attr( isset( $data['title'] ) ? $data['title'] : '' ) . '" aria-label="' . esc_attr__( 'Play video', 'brickpoint' ) . '">';

	if ( $poster_id ) {
		echo wp_get_attachment_image(
			$poster_id,
			'bp-poster',
			false,
			array(
				'class'   => 'bp-video__poster-img',
				'loading' => 'lazy',
				'alt'     => isset( $data['title'] ) ? $data['title'] : '',
			)
		);
	} else {
		echo brickpoint_placeholder( '16x9' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	echo '<span class="bp-play bp-play--lg" aria-hidden="true">' . brickpoint_icon( 'play', array( 'size' => 30 ) ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo '</button>';

	echo '</div>';

	if ( $args['caption'] ) {
		echo '<figcaption class="bp-video__caption">' . esc_html( $args['caption'] ) . '</figcaption>';
	}

	echo '</figure>';
}

/**
 * Hero background/foreground video. Autoplays muted + looped, poster first,
 * and respects prefers-reduced-motion (handled in JS/CSS).
 *
 * @param array $args poster_id, file_id, url, class, overlay, radius, badge.
 * @return void
 */
function brickpoint_hero_video( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'poster_id' => 0,
			'file_id'   => 0,
			'url'       => '',
			'class'     => '',
			'overlay'   => 0,
			'radius'    => 0,
			'badge'     => '',
			'caption'   => '',
		)
	);

	$file_url = $args['file_id'] ? wp_get_attachment_url( (int) $args['file_id'] ) : '';
	$poster   = $args['poster_id'] ? wp_get_attachment_image_url( (int) $args['poster_id'], 'bp-poster' ) : '';

	if ( ! $file_url && $args['url'] ) {
		$provider = brickpoint_detect_video_provider( $args['url'] );

		if ( 'external' === $provider ) {
			$file_url = $args['url'];
		}
	}

	$provider = $file_url ? 'self' : ( $args['url'] ? brickpoint_detect_video_provider( $args['url'] ) : '' );

	if ( ! $file_url && ! $args['url'] ) {
		return;
	}

	$style = '';

	if ( $args['radius'] ) {
		$style .= '--bp-video-radius:' . (int) $args['radius'] . 'px;';
	}

	printf(
		'<div class="bp-hero__video bp-media bp-media--video %1$s" style="%2$s" data-bp-hero-video data-provider="%3$s">',
		esc_attr( $args['class'] ),
		esc_attr( $style ),
		esc_attr( $provider )
	);

	if ( $file_url ) {
		printf(
			'<video class="bp-hero__video-el bp-media__inner" autoplay muted loop playsinline preload="metadata"%1$s>',
			$poster ? ' poster="' . esc_url( $poster ) . '"' : ''
		);

		printf( '<source src="%s" type="video/mp4" />', esc_url( $file_url ) );

		echo '</video>';
	} else {
		// YouTube/Vimeo: render the poster, swap in the iframe after load.
		$embed = 'youtube' === $provider ? brickpoint_youtube_embed_url( $args['url'] ) : brickpoint_vimeo_embed_url( $args['url'] );

		printf(
			'<div class="bp-hero__video-el bp-video__poster bp-js-hero-embed" data-embed="%1$s" data-poster="%2$s"%3$s>',
			esc_attr( $embed ),
			esc_attr( $poster ),
			$poster ? ' style="background-image:url(' . esc_url( $poster ) . ')"' : ''
		);

		if ( ! $poster ) {
			echo brickpoint_placeholder( '16x9' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo '<span class="bp-play bp-play--sm" aria-hidden="true">' . brickpoint_icon( 'play', array( 'size' => 20 ) ) . '</span></div>';
	}

	if ( $args['overlay'] ) {
		printf( '<span class="bp-hero__video-overlay" style="--bp-video-overlay:%1$d%%" aria-hidden="true"></span>', (int) $args['overlay'] );
	}

	if ( $args['badge'] ) {
		echo '<span class="bp-hero__video-badge">' . esc_html( $args['badge'] ) . '</span>';
	}

	printf(
		'<button type="button" class="bp-hero__video-toggle bp-js-video-toggle" aria-label="%1$s"><span class="bp-js-icon-pause">%2$s</span><span class="bp-js-icon-play" hidden>%3$s</span></button>',
		esc_attr__( 'Pause or play background video', 'brickpoint' ),
		brickpoint_icon( 'pause', array( 'size' => 14 ) ),
		brickpoint_icon( 'play', array( 'size' => 14 ) )
	);

	echo '</div>';
}

/**
 * Videos related to a product / project / location post (by relation field).
 *
 * @param int    $post_id   Post ID.
 * @param string $meta_key  Meta key holding related video IDs.
 * @param array  $args       Optional args.
 * @return void
 */
function brickpoint_related_videos_block( $post_id, $meta_key = '', $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'title'      => __( 'Related videos', 'brickpoint' ),
			'limit'      => 3,
			'empty_text' => '',
		)
	);

	$ids = brickpoint_get_related_video_ids( $post_id, $meta_key, $args['limit'] );

	if ( ! $ids ) {
		if ( $args['empty_text'] ) {
			echo '<p class="bp-muted">' . esc_html( $args['empty_text'] ) . '</p>';
		}

		return;
	}

	echo '<section class="bp-related-videos">';
	echo '<h2 class="bp-section__title bp-section__title--sm">' . esc_html( $args['title'] ) . '</h2>';
	echo '<div class="bp-video-grid bp-video-grid--cols-3">';

	foreach ( $ids as $id ) {
		brickpoint_video_card( $id, array( 'show_excerpt' => false ) );
	}

	echo '</div></section>';
}

/**
 * Find related video IDs for a post.
 *
 * Relation priority: explicit meta relation -> products relation -> category match.
 *
 * @param int    $post_id  Post ID.
 * @param string $meta_key Optional explicit meta key.
 * @param int    $limit    Max IDs.
 * @return int[]
 */
function brickpoint_get_related_video_ids( $post_id, $meta_key = '', $limit = 3 ) {
	$post_id = (int) $post_id;
	$ids     = array();

	$keys = $meta_key ? array( $meta_key ) : array( '_bp_related_videos' );

	foreach ( $keys as $key ) {
		$value = get_post_meta( $post_id, $key, true );

		if ( $value ) {
			$ids = array_merge( $ids, array_map( 'absint', explode( ',', (string) $value ) ) );
		}
	}

	if ( ! $ids ) {
		// Products: videos that reference this product.
		$linked = get_posts(
			array(
				'post_type'      => 'bp_video',
				'posts_per_page' => $limit,
				'fields'         => 'ids',
				'post_status'    => 'publish',
				'no_found_rows'  => true,
				'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					array(
						'key'     => '_bp_related_products',
						'value'   => (string) $post_id,
						'compare' => 'LIKE',
					),
				),
			)
		);

		$ids = $linked;
	}

	if ( ! $ids && 'bp_product' === get_post_type( $post_id ) ) {
		// Fall back to videos sharing the product's category name (e.g. "SS7 Bricks").
		$terms = get_the_terms( $post_id, 'bp_product_category' );
		$slugs = ( $terms && ! is_wp_error( $terms ) ) ? wp_list_pluck( $terms, 'slug' ) : array();

		if ( $slugs ) {
			$ids = get_posts(
				array(
					'post_type'      => 'bp_video',
					'posts_per_page' => $limit,
					'fields'         => 'ids',
					'post_status'    => 'publish',
					'no_found_rows'  => true,
					'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
						array(
							'taxonomy' => 'bp_video_category',
							'field'    => 'slug',
							'terms'    => $slugs,
						),
					),
				)
			);
		}
	}

	$ids = array_values( array_unique( array_filter( array_map( 'absint', $ids ) ) ) );
	$ids = array_slice( $ids, 0, (int) $limit );

	return $ids;
}

/**
 * Video grid renderer shared by shortcode, Elementor widget and archives.
 *
 * @param array $args Grid args (see brickpoint_video_query).
 * @return void
 */
function brickpoint_render_video_grid( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'columns'      => 3,
			'columns_tablet' => 2,
			'columns_mobile' => 1,
			'show_excerpt' => true,
			'show_filter'  => false,
			'empty_text'   => __( 'No videos yet. Add your first video in WordPress → Videos.', 'brickpoint' ),
			'load_more'    => false,
		)
	);

	$query = brickpoint_video_query( $args );

	if ( ! $query->have_posts() ) {
		echo '<p class="bp-empty">' . esc_html( $args['empty_text'] ) . '</p>';
		return;
	}

	$style = sprintf(
		'--bp-cols:%1$d;--bp-cols-t:%2$d;--bp-cols-m:%3$d;',
		(int) $args['columns'],
		(int) $args['columns_tablet'],
		(int) $args['columns_mobile']
	);

	echo '<div class="bp-video-grid" style="' . esc_attr( $style ) . '" data-bp-grid>';

	while ( $query->have_posts() ) {
		$query->the_post();

		brickpoint_video_card(
			get_the_ID(),
			array(
				'show_excerpt' => (bool) $args['show_excerpt'],
			)
		);
	}

	echo '</div>';

	if ( ! empty( $args['load_more'] ) && $query->max_num_pages > 1 ) {
		printf(
			'<div class="bp-load-more" data-bp-load-more data-type="video" data-args="%1$s" data-page="1" data-max="%2$d">
				<button type="button" class="bp-btn bp-btn--primary bp-js-load-more">%3$s</button>
			</div>',
			esc_attr( wp_json_encode( brickpoint_grid_query_args( $args ) ) ),
			(int) $query->max_num_pages,
			esc_html__( 'Load More Videos', 'brickpoint' )
		);
	}

	wp_reset_postdata();
}

/**
 * Build a video query from grid args.
 *
 * @param array $args Args.
 * @return WP_Query
 */
function brickpoint_video_query( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'category'   => '',
			'categories' => array(),
			'featured'   => false,
			'per_page'   => 6,
			'orderby'    => 'date',
			'order'      => 'DESC',
			'paged'      => 0,
			'related_products'  => array(),
			'related_projects'  => array(),
			'related_locations' => array(),
		)
	);

	$query_args = array(
		'post_type'           => 'bp_video',
		'post_status'         => 'publish',
		'posts_per_page'      => (int) $args['per_page'],
		'ignore_sticky_posts' => true,
		'paged'               => $args['paged'] ? (int) $args['paged'] : max( 1, (int) get_query_var( 'paged' ) ),
	);

	$tax_query = array();
	$terms     = array();

	if ( $args['category'] ) {
		$terms[] = sanitize_title( $args['category'] );
	}

	if ( ! empty( $args['categories'] ) ) {
		$terms = array_merge( $terms, array_map( 'sanitize_title', (array) $args['categories'] ) );
	}

	// Drop empty slugs: a tax_query with an empty term list matches nothing,
	// which would blank out the grid whenever an unset control is cast to array.
	$terms = array_values( array_filter( array_unique( $terms ) ) );

	if ( $terms ) {
		$tax_query[] = array(
			'taxonomy' => 'bp_video_category',
			'field'    => 'slug',
			'terms'    => $terms,
		);
	}

	if ( $tax_query ) {
		$query_args['tax_query'] = $tax_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
	}

	if ( $args['featured'] ) {
		$query_args['meta_query']   = array(); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		$query_args['meta_query'][] = array(
			'key'     => '_bp_featured',
			'value'   => '1',
			'compare' => '=',
		);
	}

	// Relations: videos that reference a given product / project / location.
	$relations = array(
		'_bp_related_products'  => $args['related_products'],
		'_bp_related_projects'  => $args['related_projects'],
		'_bp_related_locations' => $args['related_locations'],
	);

	foreach ( $relations as $meta_key => $ids ) {
		$ids = array_filter( array_map( 'absint', (array) $ids ) );

		if ( ! $ids ) {
			continue;
		}

		$clauses = array( 'relation' => 'OR' );

		foreach ( $ids as $id ) {
			$clauses[] = array(
				'key'     => $meta_key,
				'value'   => (string) $id,
				'compare' => 'LIKE',
			);
		}

		$query_args['meta_query']   = isset( $query_args['meta_query'] ) ? $query_args['meta_query'] : array();
		$query_args['meta_query'][] = $clauses;
	}

	switch ( $args['orderby'] ) {
		case 'title':
			$query_args['orderby'] = 'title';
			$query_args['order']   = 'ASC' === strtoupper( $args['order'] ) ? 'ASC' : 'ASC';
			break;

		case 'order':
			$query_args['meta_key'] = '_bp_video_order'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			$query_args['orderby']  = 'meta_value_num';
			$query_args['order']    = 'ASC' === strtoupper( $args['order'] ) ? 'ASC' : 'DESC';
			break;

		case 'rand':
			$query_args['orderby'] = 'rand';
			break;

		default:
			$query_args['orderby'] = 'date';
			$query_args['order']   = 'ASC' === strtoupper( $args['order'] ) ? 'ASC' : 'DESC';
			break;
	}

	/**
	 * Filter the video query args.
	 *
	 * @param array $query_args WP_Query args.
	 * @param array $args       Original grid args.
	 */
	$query_args = apply_filters( 'brickpoint_video_query_args', $query_args, $args );

	return new WP_Query( $query_args );
}

/**
 * Video lightbox markup, printed once in the footer on pages with videos.
 *
 * @return void
 */
function brickpoint_video_lightbox() {
	if ( is_admin() || ! brickpoint_needs_video_assets() ) {
		return;
	}

	?>
	<div class="bp-lightbox bp-lightbox--video" id="bp-video-lightbox" hidden>
		<div class="bp-lightbox__backdrop" data-bp-lightbox-close></div>
		<div class="bp-lightbox__dialog" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Video player', 'brickpoint' ); ?>">
			<button type="button" class="bp-lightbox__close" data-bp-lightbox-close aria-label="<?php esc_attr_e( 'Close video', 'brickpoint' ); ?>">
				<?php echo brickpoint_icon( 'close', array( 'size' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</button>
			<div class="bp-lightbox__stage bp-media bp-media--video" data-bp-lightbox-stage></div>
			<p class="bp-lightbox__title" data-bp-lightbox-title></p>
		</div>
	</div>
	<?php
}
add_action( 'wp_footer', 'brickpoint_video_lightbox', 15 );
