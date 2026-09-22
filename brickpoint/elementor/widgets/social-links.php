<?php
/**
 * Elementor: BrickPoint Social Links.
 *
 * Pulls Facebook / Instagram / X / TikTok / YouTube from the theme settings so
 * links are never hardcoded in a template.
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
 * Social links widget.
 */
class Social_Links extends Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'bp_social_links';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Social Links', 'brickpoint' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-social-icons';
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
				'label' => __( 'Content', 'brickpoint' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => __( 'Heading (optional)', 'brickpoint' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$this->add_control(
			'networks',
			array(
				'label'    => __( 'Networks to show', 'brickpoint' ),
				'type'     => Controls_Manager::SELECT2,
				'multiple' => true,
				'default'  => array( 'facebook', 'instagram', 'twitter', 'tiktok' ),
				'options'  => array(
					'facebook'  => __( 'Facebook', 'brickpoint' ),
					'instagram' => __( 'Instagram', 'brickpoint' ),
					'twitter'   => __( 'X (Twitter)', 'brickpoint' ),
					'tiktok'    => __( 'TikTok', 'brickpoint' ),
					'youtube'   => __( 'YouTube', 'brickpoint' ),
				),
			)
		);

		$this->add_control(
			'style',
			array(
				'label'   => __( 'Style', 'brickpoint' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'solid',
				'options' => array(
					'solid'  => __( 'Solid circles', 'brickpoint' ),
					'outline' => __( 'Outlined circles', 'brickpoint' ),
					'plain'  => __( 'Plain icons', 'brickpoint' ),
				),
			)
		);

		$this->add_control(
			'note',
			array(
				'label'       => __( 'Where links come from', 'brickpoint' ),
				'type'        => Controls_Manager::RAW_HTML,
				'raw'         => __( 'URLs are managed in BrickPoint → Theme Settings → Social links (or in the Customizer). Empty entries are hidden automatically.', 'brickpoint' ),
				'content_classes' => 'elementor-descriptor',
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
			'size',
			array(
				'label'      => __( 'Icon size (px)', 'brickpoint' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 24,
						'max' => 80,
					),
				),
				'default'    => array(
					'size' => 42,
				),
				'selectors'  => array(
					'{{WRAPPER}} .bp-social__link' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
					'{{WRAPPER}} .bp-social__link svg' => 'width: calc({{SIZE}}px * 0.45); height: calc({{SIZE}}px * 0.45);',
				),
			)
		);

		$this->add_control(
			'bg',
			array(
				'label'     => __( 'Background', 'brickpoint' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#16181c',
				'selectors' => array(
					'{{WRAPPER}} .bp-social--solid .bp-social__link' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'bg_hover',
			array(
				'label'     => __( 'Hover background', 'brickpoint' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#c1440e',
				'selectors' => array(
					'{{WRAPPER}} .bp-social__link:hover' => 'background-color: {{VALUE}}; border-color: {{VALUE}}; color:#fff;',
				),
			)
		);

		$this->add_control(
			'color',
			array(
				'label'     => __( 'Icon color', 'brickpoint' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .bp-social__link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'spacing',
			array(
				'label'      => __( 'Gap (px)', 'brickpoint' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 40,
					),
				),
				'default'    => array(
					'size' => 12,
				),
				'selectors'  => array(
					'{{WRAPPER}} .bp-social' => 'gap: {{SIZE}}px;',
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

		$all    = brickpoint_get_social_links();
		$wanted = (array) $s['networks'];

		if ( $wanted ) {
			$all = array_intersect_key( $all, array_flip( $wanted ) );
		}

		if ( ! $all ) {
			if ( current_user_can( 'edit_theme_options' ) ) {
				printf(
					'<p class="bp-empty">%s</p>',
					esc_html__( 'No social links saved yet. Add them in BrickPoint → Theme Settings → Social links.', 'brickpoint' )
				);
			}

			return;
		}

		if ( $s['title'] ) {
			echo '<p class="bp-social__title">' . esc_html( $s['title'] ) . '</p>';
		}

		printf( '<ul class="bp-social bp-social--%s">', esc_attr( $s['style'] ) );

		foreach ( $all as $slug => $link ) {
			printf(
				'<li><a class="bp-social__link bp-social__link--%1$s" href="%2$s" target="_blank" rel="noopener noreferrer" aria-label="%3$s">%4$s</a></li>',
				esc_attr( $slug ),
				esc_url( $link['url'] ),
				esc_attr( $link['label'] ),
				brickpoint_icon( $slug, array( 'size' => 18 ) ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}

		echo '</ul>';
	}
}
