<?php
/**
 * Elementor: BrickPoint Single Video.
 *
 * Renders one video chosen from the Videos post type (or a raw URL) as a
 * poster-first, click-to-play block.
 *
 * @package BrickPoint
 */

namespace BrickPoint\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/base.php';

use Elementor\Controls_Manager;

/**
 * Video card widget.
 */
class Video_Card extends Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'bp_video_card';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Video (single)', 'brickpoint' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-play';
	}

	/**
	 * Controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Video', 'brickpoint' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'video_id',
			array(
				'label'   => __( 'Select a video', 'brickpoint' ),
				'type'    => Controls_Manager::SELECT,
				'options' => brickpoint_elementor_post_options( 'bp_video' ),
			)
		);

		$this->add_control(
			'url',
			array(
				'label'       => __( 'or paste a video URL', 'brickpoint' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => 'https://www.youtube.com/watch?v=…',
				'description' => __( 'YouTube, Vimeo or a direct .mp4 link. Used when no video is selected above.', 'brickpoint' ),
			)
		);

		$this->add_control(
			'poster',
			array(
				'label' => __( 'Poster image', 'brickpoint' ),
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$this->add_control(
			'perspective',
			array(
				'label'   => __( 'Layout', 'brickpoint' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'inline',
				'options' => array(
					'inline' => __( 'Inline player', 'brickpoint' ),
					'card'   => __( 'Card with details', 'brickpoint' ),
				),
			)
		);

		$this->add_control(
			'caption',
			array(
				'label'     => __( 'Caption', 'brickpoint' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array( 'perspective' => 'inline' ),
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'        => __( 'Start unmuted on click only (always true)', 'brickpoint' ),
				'type'         => Controls_Manager::HIDDEN,
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'open_lightbox',
			array(
				'label'        => __( 'Open in lightbox', 'brickpoint' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => __( 'The visitor clicks the poster; the player then loads (no render-blocking embeds).', 'brickpoint' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			array(
				'label' => __( 'Style', 'brickpoint' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'aspect',
			array(
				'label'   => __( 'Aspect ratio', 'brickpoint' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '16-9',
				'options' => array(
					'16-9' => '16:9',
					'4-3'  => '4:3',
					'1-1'  => '1:1',
					'9-16' => '9:16',
				),
				'selectors' => array(
					'{{WRAPPER}} .bp-video' => '--bp-video-aspect: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'radius',
			array(
				'label'      => __( 'Border radius (px)', 'brickpoint' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 48,
					),
				),
				'default'    => array(
					'size' => 16,
				),
				'selectors'  => array(
					'{{WRAPPER}} .bp-video__frame' => 'border-radius: {{SIZE}}px; overflow: hidden;',
				),
			)
		);

		$this->add_control(
			'play_bg',
			array(
				'label'     => __( 'Play button background', 'brickpoint' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#c1440e',
				'selectors' => array(
					'{{WRAPPER}} .bp-play' => 'background-color: {{VALUE}}; color:#fff;',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	protected function render() {
		$s = $this->get_settings_for_display();

		if ( 'card' === $s['perspective'] && ! empty( $s['video_id'] ) ) {
			brickpoint_video_card( (int) $s['video_id'] );
			return;
		}

		$url       = $s['url'];
		$video_id  = (int) $s['video_id'];
		$poster_id = ! empty( $s['poster']['id'] ) ? (int) $s['poster']['id'] : 0;

		if ( ! $url && $video_id ) {
			$data = brickpoint_get_video_data( $video_id );
			$url  = ! empty( $data['mp4'] ) ? $data['mp4'] : $data['url'];

			if ( ! $poster_id && ! empty( $data['thumbnail_id'] ) ) {
				$poster_id = (int) $data['thumbnail_id'];
			}
		}

		if ( ! $url ) {
			printf(
				'<p class="bp-empty">%s</p>',
				esc_html__( 'Select a video or paste a video URL in the widget settings.', 'brickpoint' )
			);

			return;
		}

		brickpoint_inline_video(
			array(
				'video_id' => $video_id,
				'url'      => $url,
				'poster_id' => $poster_id,
				'aspect'   => isset( $s['aspect'] ) && $s['aspect'] ? $s['aspect'] : '16-9',
				'autoplay' => false,
				'caption'  => $s['caption'],
			)
		);
	}
}
