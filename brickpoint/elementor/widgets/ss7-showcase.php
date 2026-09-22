<?php
/**
 * Elementor: SS7 Bricks Showcase.
 *
 * The dedicated "The Strength Behind Every Structure" section with the
 * foreground brick animation, editable specifications and CTAs.
 *
 * @package BrickPoint
 */

namespace BrickPoint\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/base.php';

use Elementor\Controls_Manager;
use Elementor\Repeater;

/**
 * SS7 showcase widget.
 */
class Ss7_Showcase extends Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'bp_ss7_showcase';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'SS7 Bricks Showcase', 'brickpoint' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-image-before-after';
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
				'default' => __( 'SS7 Bricks', 'brickpoint' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => __( 'Heading', 'brickpoint' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'The Strength Behind Every Structure', 'brickpoint' ),
			)
		);

		$this->add_control(
			'text',
			array(
				'label'   => __( 'Description', 'brickpoint' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 4,
				'default' => __( 'SS7 Bricks are produced, stacked and loaded with a focus on uniform size, clean edges and consistent strength for walls, grey structure and boundary work.', 'brickpoint' ),
			)
		);

		$this->add_control(
			'image',
			array(
				'label' => __( 'SS7 brick image (cutout)', 'brickpoint' ),
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$this->add_control(
			'gallery',
			array(
				'label' => __( 'Small gallery', 'brickpoint' ),
				'type'  => Controls_Manager::GALLERY,
			)
		);

		$this->add_control(
			'video_file',
			array(
				'label'       => __( 'Product video (MP4)', 'brickpoint' ),
				'type'        => Controls_Manager::MEDIA,
				'media_types' => array( 'video' ),
			)
		);

		$this->add_control(
			'video_url',
			array(
				'label' => __( 'or video URL', 'brickpoint' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'btn1_label',
			array(
				'label'   => __( 'Primary button', 'brickpoint' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'View SS7 Bricks', 'brickpoint' ),
			)
		);

		$this->add_control(
			'btn1_link',
			array(
				'label' => __( 'Primary button link', 'brickpoint' ),
				'type'  => Controls_Manager::URL,
			)
		);

		$this->add_control(
			'quote_label',
			array(
				'label'   => __( 'Quotation button label', 'brickpoint' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Request Quotation', 'brickpoint' ),
			)
		);

		$this->add_control(
			'whatsapp_label',
			array(
				'label'   => __( 'WhatsApp button label', 'brickpoint' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Ask about SS7 on WhatsApp', 'brickpoint' ),
			)
		);

		$this->end_controls_section();

		// ------------------------------------------------- Specifications.
		$this->start_controls_section(
			'specs_section',
			array(
				'label' => __( 'Specifications', 'brickpoint' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'label',
			array(
				'label' => __( 'Label', 'brickpoint' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$repeater->add_control(
			'value',
			array(
				'label' => __( 'Value', 'brickpoint' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'specs',
			array(
				'label'       => __( 'Specification rows', 'brickpoint' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ label }}}',
				'description' => __( 'Size, Colour, Type, Strength, Usage, Availability, Delivery area. Only enter what you can confirm - empty rows are ignored.', 'brickpoint' ),
				'default'     => array(
					array(
						'label' => __( 'Type', 'brickpoint' ),
						'value' => __( 'Clay brick', 'brickpoint' ),
					),
					array(
						'label' => __( 'Usage', 'brickpoint' ),
						'value' => __( 'Walls, grey structure, boundary walls', 'brickpoint' ),
					),
				),
			)
		);

		$this->add_control(
			'highlights',
			array(
				'label'       => __( 'Quality highlights (one per line)', 'brickpoint' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 4,
				'default'     => __( "Uniform size and clean edges\nConsistent strength across consignments\nLoading support at the bhatta", 'brickpoint' ),
			)
		);

		$this->end_controls_section();

		// ------------------------------------------------------------ Layout.
		$this->start_controls_section(
			'layout_section',
			array(
				'label' => __( 'Layout & Animation', 'brickpoint' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'media_side',
			array(
				'label'   => __( 'Image side', 'brickpoint' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => array(
					'right' => __( 'Right', 'brickpoint' ),
					'left'  => __( 'Left', 'brickpoint' ),
				),
			)
		);

		$this->add_control(
			'animation',
			array(
				'label'   => __( 'Brick animation', 'brickpoint' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'depth',
				'options' => array(
					'depth' => __( 'Depth (moves from back to front)', 'brickpoint' ),
					'float' => __( 'Subtle float', 'brickpoint' ),
					'none'  => __( 'None', 'brickpoint' ),
				),
				'description' => __( 'Pure CSS transforms - the brick keeps its aspect ratio and never distorts. Disabled for reduced-motion visitors.', 'brickpoint' ),
			)
		);

		$this->add_control(
			'section_bg',
			array(
				'label'     => __( 'Section background', 'brickpoint' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0e0f11',
				'selectors' => array(
					'{{WRAPPER}} .bp-ss7' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => __( 'Text color', 'brickpoint' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .bp-ss7__title, {{WRAPPER}} .bp-ss7__text, {{WRAPPER}} .bp-ss7__specs th, {{WRAPPER}} .bp-ss7__specs td, {{WRAPPER}} .bp-ss7__list li' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->register_text_style( 'ss7_title', __( 'Heading', 'brickpoint' ), '{{WRAPPER}} .bp-ss7__title' );
		$this->register_text_style( 'ss7_text', __( 'Description', 'brickpoint' ), '{{WRAPPER}} .bp-ss7__text' );
	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	protected function render() {
		$s = $this->get_settings_for_display();

		$ss7_page = brickpoint_page_url( 'ss7-bricks' );

		printf(
			'<section class="bp-ss7 bp-ss7--media-%1$s bp-ss7--anim-%2$s bp-section">',
			esc_attr( $s['media_side'] ),
			esc_attr( $s['animation'] )
		);

		echo '<div class="bp-container bp-ss7__inner">';

		// Text column.
		echo '<div class="bp-ss7__content">';

		if ( $s['eyebrow'] ) {
			echo '<p class="bp-eyebrow">' . esc_html( $s['eyebrow'] ) . '</p>';
		}

		if ( $s['title'] ) {
			echo '<h2 class="bp-ss7__title">' . esc_html( $s['title'] ) . '</h2>';
		}

		if ( $s['text'] ) {
			echo '<p class="bp-ss7__text">' . esc_html( $s['text'] ) . '</p>';
		}

		$specs = array_filter(
			(array) $s['specs'],
			function ( $row ) {
				return ! empty( $row['label'] ) || ! empty( $row['value'] );
			}
		);

		if ( $specs ) {
			echo '<table class="bp-ss7__specs"><tbody>';

			foreach ( $specs as $row ) {
				printf(
					'<tr><th scope="row">%1$s</th><td>%2$s</td></tr>',
					esc_html( isset( $row['label'] ) ? $row['label'] : '' ),
					esc_html( isset( $row['value'] ) ? $row['value'] : '' )
				);
			}

			echo '</tbody></table>';
		}

		$lines = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $s['highlights'] ) ) );

		if ( $lines ) {
			echo '<ul class="bp-ss7__list">';

			foreach ( $lines as $line ) {
				echo '<li>' . brickpoint_icon( 'check', array( 'size' => 18 ) ) . '<span>' . esc_html( $line ) . '</span></li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			echo '</ul>';
		}

		echo '<div class="bp-ss7__actions">';

		if ( $s['btn1_label'] ) {
			$link = ! empty( $s['btn1_link']['url'] ) ? $s['btn1_link']['url'] : $ss7_page;

			printf(
				'<a class="bp-btn bp-btn--primary bp-btn--lg" href="%1$s"><span class="bp-btn__label">%2$s</span>%3$s</a>',
				esc_url( $link ),
				esc_html( $s['btn1_label'] ),
				brickpoint_icon( 'arrow-right', array( 'size' => 18 ) )
			);
		}

		if ( $s['quote_label'] ) {
			printf(
				'<a class="bp-btn bp-btn--ghost bp-btn--lg" href="%1$s"><span class="bp-btn__label">%2$s</span></a>',
				esc_url( brickpoint_page_url( 'contact' ) ? brickpoint_page_url( 'contact' ) : home_url( '/' ) ),
				esc_html( $s['quote_label'] )
			);
		}

		echo brickpoint_whatsapp_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			array(
				'label'   => $s['whatsapp_label'] ? $s['whatsapp_label'] : __( 'Ask on WhatsApp', 'brickpoint' ),
				'message' => brickpoint_general_inquiry_message( __( 'SS7 Bricks', 'brickpoint' ) ),
				'class'   => 'bp-btn bp-btn--whatsapp bp-btn--lg',
			)
		);

		echo '</div></div>';

		// Media column.
		echo '<div class="bp-ss7__media">';

		if ( ! empty( $s['image']['url'] ) ) {
			printf(
				'<figure class="bp-ss7__figure"><img class="bp-ss7-brick" src="%1$s" alt="%2$s" loading="lazy" decoding="async" width="900" height="600" /></figure>',
				esc_url( $s['image']['url'] ),
				esc_attr__( 'SS7 brick', 'brickpoint' )
			);
		} else {
			echo '<div class="bp-ss7__figure bp-ss7__figure--placeholder">' . brickpoint_placeholder( '4x3' ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		$gallery = (array) $s['gallery'];

		if ( $gallery ) {
			echo '<div class="bp-ss7__gallery">';

			foreach ( array_slice( $gallery, 0, 4 ) as $image ) {
				$id = isset( $image['id'] ) ? (int) $image['id'] : 0;

				if ( $id ) {
					echo wp_get_attachment_image( $id, 'bp-square', false, array( 'loading' => 'lazy' ) );
				}
			}

			echo '</div>';
		}

		$video_url = ! empty( $s['video_file']['url'] ) ? $s['video_file']['url'] : $s['video_url'];

		if ( $video_url ) {
			brickpoint_inline_video(
				array(
					'url'       => $video_url,
					'poster_id' => ! empty( $s['image']['id'] ) ? (int) $s['image']['id'] : 0,
					'autoplay'  => false,
					'class'     => 'bp-video--ss7',
					'label'     => __( 'SS7 Bricks', 'brickpoint' ),
				)
			);
		}

		echo '</div></div></section>';
	}
}
