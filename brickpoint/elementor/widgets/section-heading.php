<?php
/**
 * Elementor: BrickPoint Section Heading.
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
 * Section heading widget.
 */
class Section_Heading extends Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'bp_section_heading';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Section Heading', 'brickpoint' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-heading';
	}

	/**
	 * Controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->register_heading_controls( 'head', __( 'Heading', 'brickpoint' ) );

		$this->start_controls_section(
			'style_section',
			array(
				'label' => __( 'Style', 'brickpoint' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->register_text_style( 'eyebrow', __( 'Eyebrow', 'brickpoint' ), '{{WRAPPER}} .bp-eyebrow', array( 'default' => '#c1440e' ) );
		$this->register_text_style( 'title', __( 'Title', 'brickpoint' ), '{{WRAPPER}} .bp-section__title' );
		$this->register_text_style( 'text', __( 'Description', 'brickpoint' ), '{{WRAPPER}} .bp-section__text' );

		$this->add_control(
			'divider',
			array(
				'label'        => __( 'Show accent divider', 'brickpoint' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'divider_color',
			array(
				'label'     => __( 'Divider color', 'brickpoint' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#c1440e',
				'selectors' => array(
					'{{WRAPPER}} .bp-section__divider' => 'background-color: {{VALUE}};',
				),
				'condition' => array( 'divider' => 'yes' ),
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

		echo '<div class="bp-heading-widget">';

		$this->render_heading( $s, 'head' );

		if ( 'yes' === $s['divider'] ) {
			echo '<span class="bp-section__divider" aria-hidden="true"></span>';
		}

		echo '</div>';
	}
}
