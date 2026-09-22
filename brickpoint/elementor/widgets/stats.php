<?php
/**
 * Elementor: BrickPoint Stats / Feature Strip.
 *
 * Deliberately qualitative by default - the theme never invents numbers.
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
 * Stats / features widget.
 */
class Stats extends Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'bp_stats';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Stats / Trust Strip', 'brickpoint' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-counter';
	}

	/**
	 * Controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->register_heading_controls( 'head', __( 'Section Heading', 'brickpoint' ) );

		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Items', 'brickpoint' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'icon',
			array(
				'label'   => __( 'Icon', 'brickpoint' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'check',
				'options' => array(
					'check'   => __( 'Check', 'brickpoint' ),
					'brick'   => __( 'Brick', 'brickpoint' ),
					'layers'  => __( 'Layers', 'brickpoint' ),
					'truck'   => __( 'Delivery truck', 'brickpoint' ),
					'factory' => __( 'Factory', 'brickpoint' ),
					'shield'  => __( 'Shield / quality', 'brickpoint' ),
					'star'    => __( 'Star', 'brickpoint' ),
					'pin'     => __( 'Location pin', 'brickpoint' ),
					'clock'   => __( 'Clock', 'brickpoint' ),
					'grid'    => __( 'Grid', 'brickpoint' ),
				),
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'       => __( 'Title', 'brickpoint' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Quality-focused supply', 'brickpoint' ),
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'text',
			array(
				'label' => __( 'Text', 'brickpoint' ),
				'type'  => Controls_Manager::TEXTAREA,
				'rows'  => 2,
			)
		);

		$repeater->add_control(
			'value',
			array(
				'label'       => __( 'Number / value (optional)', 'brickpoint' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Only add a number you can verify. Otherwise leave empty and the icon is shown instead.', 'brickpoint' ),
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => __( 'Trust items', 'brickpoint' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array(
						'icon'  => 'shield',
						'title' => __( 'Quality-focused supply', 'brickpoint' ),
						'text'  => __( 'Materials sourced from our own production and trusted suppliers.', 'brickpoint' ),
					),
					array(
						'icon'  => 'truck',
						'title' => __( 'Reliable delivery', 'brickpoint' ),
						'text'  => __( 'Loading and dispatch coordinated with your site schedule.', 'brickpoint' ),
					),
					array(
						'icon'  => 'factory',
						'title' => __( 'Multiple production locations', 'brickpoint' ),
						'text'  => __( 'Masha Allah Bricks Company, Fine Bricks Company and SS7 Bricks.', 'brickpoint' ),
					),
					array(
						'icon'  => 'layers',
						'title' => __( 'Construction material solutions', 'brickpoint' ),
						'text'  => __( 'From bricks and cement to steel, crush, sand and finishing items.', 'brickpoint' ),
					),
				),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => __( 'Columns', 'brickpoint' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '4',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options'        => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				),
				'selectors'      => array(
					'{{WRAPPER}} .bp-features' => '--bp-cols: {{VALUE}};',
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

		$this->add_control(
			'icon_color',
			array(
				'label'     => __( 'Icon color', 'brickpoint' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .bp-feature__icon' => 'color: {{VALUE}};',
				),
			)
		);

		$this->register_card_style( 'card', '{{WRAPPER}} .bp-feature' );
		$this->register_text_style( 'title', __( 'Title', 'brickpoint' ), '{{WRAPPER}} .bp-feature__title' );
		$this->register_text_style( 'text', __( 'Text', 'brickpoint' ), '{{WRAPPER}} .bp-feature__text' );

		$this->end_controls_section();
	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	protected function render() {
		$s = $this->get_settings_for_display();

		$this->render_heading( $s, 'head' );

		$items = array();

		foreach ( (array) $s['items'] as $item ) {
			$items[] = array(
				'icon'  => isset( $item['icon'] ) ? $item['icon'] : 'check',
				'title' => isset( $item['title'] ) ? $item['title'] : '',
				'text'  => isset( $item['text'] ) ? $item['text'] : '',
				'value' => isset( $item['value'] ) ? $item['value'] : '',
			);
		}

		brickpoint_feature_grid( $items );
	}
}
