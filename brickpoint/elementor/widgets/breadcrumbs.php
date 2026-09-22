<?php
/**
 * Elementor: BrickPoint Breadcrumbs.
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
 * Breadcrumbs widget.
 */
class Breadcrumbs extends Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'bp_breadcrumbs';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Breadcrumbs', 'brickpoint' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-navigation-horizontal';
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
			'schema',
			array(
				'label'        => __( 'Output BreadcrumbList structured data', 'brickpoint' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'align',
			array(
				'label'   => __( 'Alignment', 'brickpoint' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'left',
				'options' => array(
					'left'   => array(
						'title' => __( 'Left', 'brickpoint' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'brickpoint' ),
						'icon'  => 'eicon-text-align-center',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .bp-breadcrumbs' => 'text-align: {{VALUE}};',
				),
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

		$this->register_text_style( 'text', __( 'Breadcrumb text', 'brickpoint' ), '{{WRAPPER}} .bp-breadcrumbs__item' );
		$this->register_text_style( 'link', __( 'Link color', 'brickpoint' ), '{{WRAPPER}} .bp-breadcrumbs a' );

		$this->end_controls_section();
	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	protected function render() {
		$s = $this->get_settings_for_display();

		if ( 'yes' === $s['schema'] ) {
			brickpoint_breadcrumbs();
			return;
		}

		$items = brickpoint_breadcrumb_items();

		if ( count( $items ) < 2 ) {
			return;
		}

		echo '<nav class="bp-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'brickpoint' ) . '"><ol class="bp-breadcrumbs__list">';

		foreach ( $items as $index => $item ) {
			$is_last = ( $index === count( $items ) - 1 );

			echo '<li class="bp-breadcrumbs__item">';

			if ( $is_last || empty( $item['url'] ) ) {
				echo '<span aria-current="page">' . esc_html( $item['label'] ) . '</span>';
			} else {
				printf( '<a href="%1$s">%2$s</a>', esc_url( $item['url'] ), esc_html( $item['label'] ) );
			}

			echo '</li>';
		}

		echo '</ol></nav>';
	}
}
