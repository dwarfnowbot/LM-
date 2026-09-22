<?php
/**
 * Elementor: BrickPoint Hero.
 *
 * Full-width premium hero with headline, CTAs, SS7 brick visual and an
 * autoplay-muted-loop video block (poster first, click/scroll aware).
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
 * Hero widget.
 */
class Hero extends Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'bp_hero';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Hero (with video)', 'brickpoint' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-slider-video';
	}

	/**
	 * Controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		// ------------------------------------------------------------ Content.
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Content', 'brickpoint' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'eyebrow',
			array(
				'label'   => __( 'Eyebrow', 'brickpoint' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Bricks • Cement • Crush • Sand • Steel', 'brickpoint' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => __( 'Headline', 'brickpoint' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 3,
				'default' => __( "Building Strength.\nDelivering Quality.\nShaping Tomorrow.", 'brickpoint' ),
			)
		);

		$this->add_control(
			'text',
			array(
				'label'   => __( 'Supporting text', 'brickpoint' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 3,
				'default' => __( 'Premium bricks and reliable construction materials for homes, commercial developments, and large-scale building projects.', 'brickpoint' ),
			)
		);

		$this->add_control(
			'btn1_label',
			array(
				'label'   => __( 'Primary button', 'brickpoint' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Explore Products', 'brickpoint' ),
			)
		);

		$this->add_control(
			'btn1_link',
			array(
				'label'       => __( 'Primary button link', 'brickpoint' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'Leave empty for the Products page', 'brickpoint' ),
			)
		);

		$this->add_control(
			'btn2_label',
			array(
				'label'   => __( 'Secondary button', 'brickpoint' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Request a Quote', 'brickpoint' ),
			)
		);

		$this->add_control(
			'btn2_link',
			array(
				'label'       => __( 'Secondary button link', 'brickpoint' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'Leave empty for the Contact page', 'brickpoint' ),
			)
		);

		$this->add_control(
			'btn3_whatsapp',
			array(
				'label'        => __( 'Show WhatsApp button', 'brickpoint' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'wa_label',
			array(
				'label'     => __( 'WhatsApp button label', 'brickpoint' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'WhatsApp Us', 'brickpoint' ),
				'condition' => array( 'btn3_whatsapp' => 'yes' ),
			)
		);

		$this->end_controls_section();

		// --------------------------------------------------------------- Video.
		$this->start_controls_section(
			'video_section',
			array(
				'label' => __( 'Hero Video', 'brickpoint' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'video_file',
			array(
				'label'       => __( 'Self-hosted video (MP4)', 'brickpoint' ),
				'type'        => Controls_Manager::MEDIA,
				'media_types' => array( 'video' ),
				'description' => __( 'Short, compressed clips work best (10–20s, under ~4 MB).', 'brickpoint' ),
			)
		);

		$this->add_control(
			'video_url',
			array(
				'label'       => __( 'or video URL (YouTube / Vimeo / .mp4)', 'brickpoint' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => 'https://',
			)
		);

		$this->add_control(
			'poster',
			array(
				'label'       => __( 'Poster image', 'brickpoint' ),
				'type'        => Controls_Manager::MEDIA,
				'description' => __( 'Shown before the video loads. Always set one for performance.', 'brickpoint' ),
			)
		);

		$this->add_control(
			'badge',
			array(
				'label'     => __( 'Video badge label', 'brickpoint' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'SS7 Bricks', 'brickpoint' ),
			)
		);

		$this->add_responsive_control(
			'video_position',
			array(
				'label'     => __( 'Video position', 'brickpoint' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'corner',
				'options'  => array(
					'corner'   => __( 'Top right corner card', 'brickpoint' ),
					'inline'   => __( 'Inline below the text', 'brickpoint' ),
					'background' => __( 'Full hero background', 'brickpoint' ),
				),
			)
		);

		$this->add_responsive_control(
			'video_width',
			array(
				'label'      => __( 'Video width', 'brickpoint' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 160,
						'max' => 900,
					),
					'%'  => array(
						'min' => 20,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => '%',
					'size' => 100,
				),
				'selectors'  => array(
					'{{WRAPPER}} .bp-hero__media' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'video_height',
			array(
				'label'      => __( 'Video min height', 'brickpoint' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array(
					'px' => array(
						'min' => 120,
						'max' => 700,
					),
					'vh' => array(
						'min' => 20,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .bp-hero__media .bp-hero__video-el' => 'min-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'video_radius',
			array(
				'label'      => __( 'Video border radius (px)', 'brickpoint' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'default'    => array(
					'size' => 18,
				),
				'selectors'  => array(
					'{{WRAPPER}} .bp-hero__media' => '--bp-video-radius: {{SIZE}}px;',
				),
			)
		);

		$this->add_control(
			'video_opacity',
			array(
				'label'     => __( 'Video overlay opacity (%)', 'brickpoint' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range'     => array(
					'%' => array(
						'min' => 0,
						'max' => 95,
					),
				),
				'default'   => array(
					'size' => 25,
				),
				'selectors' => array(
					'{{WRAPPER}} .bp-hero__video-overlay' => 'background: rgba(14,15,17, calc({{SIZE}} / 100)); opacity:1;',
				),
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'        => __( 'Autoplay (muted, looped)', 'brickpoint' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => __( 'Automatically disabled for visitors who prefer reduced motion.', 'brickpoint' ),
			)
		);

		$this->add_control(
			'show_toggle',
			array(
				'label'        => __( 'Show play/pause control', 'brickpoint' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();

		// -------------------------------------------------------- SS7 visual.
		$this->start_controls_section(
			'ss7_section',
			array(
				'label' => __( 'SS7 Brick Animation', 'brickpoint' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'ss7_enable',
			array(
				'label'        => __( 'Enable SS7 brick animation', 'brickpoint' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => __( 'A single brick drifts from the background into the foreground. Uses the SS7 brick image and keeps the aspect ratio - it never stretches.', 'brickpoint' ),
			)
		);

		$this->add_control(
			'ss7_image',
			array(
				'label'     => __( 'SS7 brick image (cutout, transparent PNG works best)', 'brickpoint' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => array( 'ss7_enable' => 'yes' ),
			)
		);

		$this->add_control(
			'ss7_depth',
			array(
				'label'     => __( 'Animation depth', 'brickpoint' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'medium',
				'options'   => array(
					'subtle' => __( 'Subtle', 'brickpoint' ),
					'medium' => __( 'Medium', 'brickpoint' ),
					'strong' => __( 'Strong (more movement)', 'brickpoint' ),
				),
				'condition' => array( 'ss7_enable' => 'yes' ),
			)
		);

		$this->end_controls_section();

		// ------------------------------------------------------------ Style.
		$this->start_controls_section(
			'style_section',
			array(
				'label' => __( 'Style', 'brickpoint' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'bg_color',
			array(
				'label'     => __( 'Hero background', 'brickpoint' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0e0f11',
				'selectors' => array(
					'{{WRAPPER}} .bp-hero' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'bg_image',
			array(
				'label'     => __( 'Background image (optional)', 'brickpoint' ),
				'type'      => Controls_Manager::MEDIA,
				'selectors' => array(
					'{{WRAPPER}} .bp-hero' => 'background-image: url({{URL}}); background-size: cover; background-position: center;',
				),
			)
		);

		$this->add_control(
			'bg_overlay',
			array(
				'label'     => __( 'Background overlay', 'brickpoint' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(14,15,17,0.72)',
				'selectors' => array(
					'{{WRAPPER}} .bp-hero__inner' => 'background: {{VALUE}};',
				),
			)
		);

		$this->register_text_style( 'title', __( 'Headline', 'brickpoint' ), '{{WRAPPER}} .bp-hero__title', array( 'default' => '#ffffff' ) );
		$this->register_text_style( 'text', __( 'Supporting text', 'brickpoint' ), '{{WRAPPER}} .bp-hero__text', array( 'default' => '#d7d2c9' ) );
		$this->register_text_style( 'eyebrow', __( 'Eyebrow', 'brickpoint' ), '{{WRAPPER}} .bp-eyebrow', array( 'default' => '#e2571e' ) );

		$this->add_responsive_control(
			'min_height',
			array(
				'label'      => __( 'Minimum hero height', 'brickpoint' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array(
					'px' => array(
						'min' => 320,
						'max' => 1200,
					),
					'vh' => array(
						'min' => 30,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'vh',
					'size' => 82,
				),
				'selectors'  => array(
					'{{WRAPPER}} .bp-hero' => 'min-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'padding',
			array(
				'label'      => __( 'Padding', 'brickpoint' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .bp-hero__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		$products_page = brickpoint_page_url( 'products' );
		$contact_page  = brickpoint_page_url( 'contact' );

		$btn1_url = ! empty( $s['btn1_link']['url'] ) ? $s['btn1_link']['url'] : ( $products_page ? $products_page : home_url( '/' ) );
		$btn2_url = ! empty( $s['btn2_link']['url'] ) ? $s['btn2_link']['url'] : ( $contact_page ? $contact_page : home_url( '/' ) );

		$file_url   = ! empty( $s['video_file']['url'] ) ? $s['video_file']['url'] : '';
		$video_url  = ! empty( $s['video_url'] ) ? $s['video_url'] : '';
		$poster_url = ! empty( $s['poster']['url'] ) ? $s['poster']['url'] : '';

		$position = isset( $s['video_position'] ) && $s['video_position'] ? $s['video_position'] : 'corner';

		printf(
			'<section class="bp-hero bp-hero--video-%1$s" data-bp-hero data-ss7="%2$s">',
			esc_attr( $position ),
			'yes' === $s['ss7_enable'] ? esc_attr( $s['ss7_depth'] ) : 'off'
		);

		echo '<div class="bp-hero__bg">';

		if ( 'background' === $position && ( $file_url || $video_url ) ) {
			brickpoint_hero_video(
				array(
					'file_id'   => 0,
					'url'       => $file_url ? $file_url : $video_url,
					'poster_id' => 0,
					'overlay'   => 55,
					'radius'    => 0,
					'class'     => 'bp-hero__media bp-hero__media--bg',
					'badge'     => '',
				)
			);

			if ( $poster_url ) {
				printf( '<img class="bp-hero__bg-poster" src="%s" alt="" aria-hidden="true" />', esc_url( $poster_url ) );
			}
		}

		echo '</div>';

		echo '<div class="bp-hero__inner bp-container">';

		// Text column.
		echo '<div class="bp-hero__content">';

		if ( $s['eyebrow'] ) {
			echo '<p class="bp-eyebrow bp-reveal">' . esc_html( $s['eyebrow'] ) . '</p>';
		}

		if ( $s['title'] ) {
			$lines = preg_split( '/\r\n|\r|\n/', trim( $s['title'] ) );

			echo '<h1 class="bp-hero__title">';

			foreach ( $lines as $index => $line ) {
				printf(
					'<span class="bp-hero__line bp-reveal" style="animation-delay:%1$dms">%2$s</span>',
					(int) ( $index * 120 ),
					esc_html( trim( $line ) )
				);
			}

			echo '</h1>';
		}

		if ( $s['text'] ) {
			echo '<p class="bp-hero__text bp-reveal">' . esc_html( $s['text'] ) . '</p>';
		}

		echo '<div class="bp-hero__actions bp-reveal">';

		if ( $s['btn1_label'] ) {
			printf(
				'<a class="bp-btn bp-btn--primary bp-btn--lg" href="%1$s">%2$s<span class="bp-btn__label">%3$s</span></a>',
				esc_url( $btn1_url ),
				brickpoint_icon( 'grid', array( 'size' => 18 ) ),
				esc_html( $s['btn1_label'] )
			);
		}

		if ( $s['btn2_label'] ) {
			printf(
				'<a class="bp-btn bp-btn--ghost bp-btn--lg" href="%1$s">%2$s<span class="bp-btn__label">%3$s</span></a>',
				esc_url( $btn2_url ),
				brickpoint_icon( 'quote', array( 'size' => 18 ) ),
				esc_html( $s['btn2_label'] )
			);
		}

		if ( 'yes' === $s['btn3_whatsapp'] ) {
			echo brickpoint_whatsapp_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				array(
					'label' => $s['wa_label'] ? $s['wa_label'] : __( 'WhatsApp Us', 'brickpoint' ),
					'class' => 'bp-btn bp-btn--whatsapp bp-btn--lg',
				)
			);
		}

		echo '</div>';

		// SS7 brick animation.
		if ( 'yes' === $s['ss7_enable'] ) {
			echo '<div class="bp-hero__ss7" aria-hidden="true">';

			if ( ! empty( $s['ss7_image']['url'] ) ) {
				printf(
					'<img class="bp-ss7-brick" src="%1$s" alt="" loading="lazy" decoding="async" />',
					esc_url( $s['ss7_image']['url'] )
				);
			} else {
				echo '<span class="bp-ss7-brick bp-ss7-brick--css">' . brickpoint_icon( 'brick', array( 'size' => 120 ) ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			echo '<span class="bp-hero__ss7-shadow" aria-hidden="true"></span>';
			echo '</div>';
		}

		echo '</div>';

		// Video column (corner or inline).
		if ( 'background' !== $position && ( $file_url || $video_url ) ) {
			echo '<div class="bp-hero__media bp-media bp-media--video">';

			brickpoint_hero_video(
				array(
					'url'     => $file_url ? $file_url : $video_url,
					'overlay' => 0,
					'radius'  => isset( $s['video_radius']['size'] ) ? (int) $s['video_radius']['size'] : 18,
					'badge'   => $s['badge'],
					'class'   => 'bp-hero__media-inner',
				)
			);

			echo '</div>';
		}

		echo '</div></section>';
	}
}
