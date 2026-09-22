<?php
/**
 * Shared Elementor widget base class.
 *
 * @package BrickPoint
 */

namespace BrickPoint\Widgets;

// Guard against a double include (symlinked theme directories can make
// `require_once` treat the same file as two different files).
if ( class_exists( 'BrickPoint\\Widgets\\Base', false ) ) {
	return;
}

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Widget_Base;

/**
 * Base widget with shared branding, heading and button controls.
 */
abstract class Base extends Widget_Base {

	/**
	 * Widget category.
	 *
	 * @return string[]
	 */
	public function get_categories() {
		return array( 'brickpoint' );
	}

	/**
	 * Shared keywords.
	 *
	 * @return string[]
	 */
	public function get_keywords() {
		return array( 'brickpoint', 'construction', 'materials' );
	}

	/**
	 * Add a control, opening a Style section first when none is open.
	 *
	 * Elementor 3.16+ refuses any control that is added outside a controls
	 * section and stops the whole request with `wp_die()` ("Cannot add a control
	 * outside of a section"). The shared style helpers below are called at the
	 * end of `register_controls()`, after the last `end_controls_section()`, and
	 * `Document::save()` / the editor panel build those controls - so without
	 * this guard saving a page that contains a BrickPoint widget would fail.
	 *
	 * The section is opened on demand, so widgets keep their own sections and
	 * the style controls simply land in one shared "Style" section.
	 *
	 * @param string $id      Control id.
	 * @param array  $args    Control arguments.
	 * @param array  $options Control options (position/injection).
	 * @return void
	 */
	public function add_control( $id, array $args = array(), $options = array() ) {
		$this->maybe_open_style_section( $args );

		parent::add_control( $id, $args, $options );
	}

	/**
	 * Track whether we are inside a controls section.
	 *
	 * @var bool
	 */
	protected $bp_section_open = false;

	/**
	 * Start a controls section (flagged so the style guard below can see it).
	 *
	 * @param string $section_id Section id.
	 * @param array  $args       Section arguments.
	 * @param array  $options    Section options.
	 * @return void
	 */
	public function start_controls_section( $section_id, array $args = array(), array $options = array() ) {
		$this->bp_section_open = true;

		parent::start_controls_section( $section_id, $args, $options );
	}

	/**
	 * End a controls section.
	 *
	 * @param bool $popover Whether the section ends a popover.
	 * @return void
	 */
	public function end_controls_section( $popover = false ) {
		$this->bp_section_open = false;

		parent::end_controls_section( $popover );
	}

	/**
	 * Is a controls section currently open on this widget?
	 *
	 * Elementor keeps the section in a private property, so the flag above is
	 * the primary signal; `get_current_section()` (public since Elementor 3.5)
	 * and a reflection fallback cover widgets that build controls in another
	 * order.
	 *
	 * @return bool
	 */
	protected function is_section_open() {
		if ( $this->bp_section_open ) {
			return true;
		}

		if ( method_exists( $this, 'get_current_section' ) ) {
			return null !== $this->get_current_section();
		}

		try {
			$reflection = new \ReflectionObject( $this );

			while ( $reflection ) {
				if ( $reflection->hasProperty( 'current_section' ) ) {
					$property = $reflection->getProperty( 'current_section' );

					if ( PHP_VERSION_ID < 80100 ) {
						$property->setAccessible( true );
					}

					return null !== $property->getValue( $this );
				}

				$reflection = $reflection->getParentClass();
			}
		} catch ( \Exception $e ) {
			return false;
		}

		return false;
	}

	/**
	 * Make sure a controls section is open before a control is registered.
	 *
	 * @param array $args Control arguments.
	 * @return void
	 */
	protected function maybe_open_style_section( $args = array() ) {
		if ( $this->is_section_open() ) {
			return;
		}

		$type = isset( $args['type'] ) ? $args['type'] : '';

		if ( Controls_Manager::SECTION === $type || Controls_Manager::WP_WIDGET === $type || 'tab' === $type ) {
			return;
		}

		$this->start_controls_section(
			'brickpoint_style_section',
			array(
				'label' => __( 'Style', 'brickpoint' ),
				'tab'   => isset( $args['tab'] ) ? $args['tab'] : Controls_Manager::TAB_STYLE,
			)
		);
	}

	/**
	 * Settings with control defaults filled in.
	 *
	 * Elementor only returns the values that were stored with the element, so a
	 * widget rendered from partial data (imported template, programmatic
	 * element, freshly registered widget) would otherwise hit undefined keys.
	 * Missing controls fall back to their own default instead.
	 *
	 * @param string|null $setting_key Optional single setting.
	 * @return mixed
	 */
	public function get_settings_for_display( $setting_key = null ) {
		$settings = parent::get_settings_for_display();

		if ( ! is_array( $settings ) ) {
			$settings = array();
		}

		// Not enough data to inspect controls (e.g. very early calls).
		if ( ! method_exists( $this, 'get_controls' ) ) {
			return null === $setting_key ? $settings : ( isset( $settings[ $setting_key ] ) ? $settings[ $setting_key ] : '' );
		}

		foreach ( $this->get_controls() as $id => $control ) {
			if ( array_key_exists( $id, $settings ) ) {
				continue;
			}

			// Structure-only controls (sections, tabs, headings, raw html).
			if ( isset( $control['type'] ) && in_array( $control['type'], array( 'section', 'tab', 'tabs', 'heading', 'raw_html', 'divider', 'deprecated' ), true ) ) {
				continue;
			}

			if ( isset( $control['default'] ) ) {
				$settings[ $id ] = $control['default'];
			} elseif ( isset( $control['type'] ) && 'repeater' === $control['type'] ) {
				$settings[ $id ] = array();
			} else {
				$settings[ $id ] = '';
			}
		}

		if ( null === $setting_key ) {
			return $settings;
		}

		return isset( $settings[ $setting_key ] ) ? $settings[ $setting_key ] : '';
	}

	/**
	 * Current post ID, aware of Elementor's preview/document context.
	 *
	 * @return int
	 */
	protected function current_post_id() {
		if ( class_exists( '\Elementor\Plugin' ) ) {
			$document = \Elementor\Plugin::$instance->documents->get_current();

			if ( $document ) {
				return (int) $document->get_main_id();
			}
		}

		return (int) get_the_ID();
	}

	/**
	 * Register a standard heading block (eyebrow, title, text, alignment).
	 *
	 * @param string $prefix Control prefix.
	 * @param string $label  Section label.
	 * @return void
	 */
	protected function register_heading_controls( $prefix = 'heading', $label = '' ) {
		$label = $label ? $label : __( 'Section Heading', 'brickpoint' );

		$this->start_controls_section(
			$prefix . '_section',
			array(
				'label' => $label,
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			$prefix . '_eyebrow',
			array(
				'label'       => __( 'Eyebrow', 'brickpoint' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			)
		);

		$this->add_control(
			$prefix . '_title',
			array(
				'label'       => __( 'Title', 'brickpoint' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			)
		);

		$this->add_control(
			$prefix . '_text',
			array(
				'label' => __( 'Description', 'brickpoint' ),
				'type'  => Controls_Manager::TEXTAREA,
				'rows'  => 3,
			)
		);

		$this->add_control(
			$prefix . '_align',
			array(
				'label'   => __( 'Alignment', 'brickpoint' ),
				'type'    => Controls_Manager::CHOOSE,
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
				'default' => 'left',
			)
		);

		$this->add_control(
			$prefix . '_tag',
			array(
				'label'   => __( 'Title HTML tag', 'brickpoint' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'p'  => 'P',
				),
				'default' => 'h2',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render the heading block registered above.
	 *
	 * @param array $settings Widget settings.
	 * @param string $prefix  Control prefix.
	 * @return void
	 */
	protected function render_heading( $settings, $prefix = 'heading' ) {
		brickpoint_section_heading(
			array(
				'eyebrow' => isset( $settings[ $prefix . '_eyebrow' ] ) ? $settings[ $prefix . '_eyebrow' ] : '',
				'title'   => isset( $settings[ $prefix . '_title' ] ) ? $settings[ $prefix . '_title' ] : '',
				'text'    => isset( $settings[ $prefix . '_text' ] ) ? $settings[ $prefix . '_text' ] : '',
				'align'   => isset( $settings[ $prefix . '_align' ] ) ? $settings[ $prefix . '_align' ] : 'left',
				'tag'     => isset( $settings[ $prefix . '_tag' ] ) ? $settings[ $prefix . '_tag' ] : 'h2',
			)
		);
	}

	/**
	 * Register typography + color style controls for a selector.
	 *
	 * @param string $id       Control id.
	 * @param string $label    Label.
	 * @param string $selector CSS selector.
	 * @param array  $args     Extra: typography (bool), color (bool), default color.
	 * @return void
	 */
	protected function register_text_style( $id, $label, $selector, $args = array() ) {
		$this->maybe_open_style_section();
		$args = wp_parse_args(
			$args,
			array(
				'typography' => true,
				'color'      => true,
				'default'    => '',
				'condition'  => array(),
			)
		);

		if ( $args['typography'] && ! $this->control_exists( $id . '_typo' ) && ! $this->control_exists( 'typography_' . $id . '_typo' ) ) {
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'      => $id . '_typo',
					'label'     => $label,
					'selector'  => $selector,
					'condition' => $args['condition'],
				)
			);
		}

		if ( $args['color'] && ! $this->control_exists( $id . '_color' ) ) {
			$this->add_control(
				$id . '_color',
				array(
					'label'     => __( 'Color', 'brickpoint' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => $args['default'],
					'selectors' => array(
						$selector => 'color: {{VALUE}};',
					),
					'condition' => $args['condition'],
				)
			);
		}
	}

	/**
	 * Register card border/radius/shadow controls.
	 *
	 * @param string $id       Control id.
	 * @param string $selector CSS selector.
	 * @param array  $args     Extra args.
	 * @return void
	 */
	/**
	 * Whether a control with this ID has already been registered.
	 *
	 * Elementor logs a notice when a control is registered twice, so the shared
	 * style helpers check first and stay safe if a widget also defines the same
	 * control itself.
	 *
	 * @param string $id Control ID.
	 * @return bool
	 */
	protected function control_exists( $id ) {
		if ( empty( $this->controls ) || ! is_array( $this->controls ) ) {
			return false;
		}

		return isset( $this->controls[ $id ] );
	}

	protected function register_card_style( $id, $selector, $args = array() ) {
		$this->maybe_open_style_section();
		$args = wp_parse_args(
			$args,
			array(
				'background' => true,
				'radius'     => true,
				'shadow'     => true,
				'border'     => true,
			)
		);

		$args['background'] = $args['background'] && ! $this->control_exists( $id . '_bg' );
		$args['radius']     = $args['radius'] && ! $this->control_exists( $id . '_radius' );
		$args['border']     = $args['border'] && ! $this->control_exists( $id . '_border' );
		$args['shadow']     = $args['shadow'] && ! $this->control_exists( $id . '_shadow' );

		if ( $args['background'] ) {
			$this->add_control(
				$id . '_bg',
				array(
					'label'     => __( 'Background', 'brickpoint' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						$selector => 'background-color: {{VALUE}};',
					),
				)
			);
		}

		if ( $args['radius'] ) {
			$this->add_control(
				$id . '_radius',
				array(
					'label'      => __( 'Border radius', 'brickpoint' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 60,
						),
					),
					'selectors'  => array(
						$selector => 'border-radius: {{SIZE}}{{UNIT}}; overflow: hidden;',
					),
				)
			);
		}

		if ( $args['border'] ) {
			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => $id . '_border',
					'label'    => __( 'Border', 'brickpoint' ),
					'selector' => $selector,
				)
			);
		}

		if ( $args['shadow'] ) {
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => $id . '_shadow',
					'label'    => __( 'Box shadow', 'brickpoint' ),
					'selector' => $selector,
				)
			);
		}
	}

	/**
	 * Register responsive column controls.
	 *
	 * @param string $id Control id.
	 * @return void
	 */
	protected function register_column_controls( $id = 'columns' ) {
		if ( $this->control_exists( $id ) ) {
			return;
		}

		$this->add_responsive_control(
			$id,
			array(
				'label'          => __( 'Columns', 'brickpoint' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options'        => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'selectors'      => array(
					'{{WRAPPER}} .bp-grid-cols' => '--bp-cols: {{VALUE}};',
				),
			)
		);
	}

	/**
	 * Build the CSS variable string for a grid based on responsive settings.
	 *
	 * @param array $settings Settings.
	 * @param string $id      Control id.
	 * @return string
	 */
	protected function grid_vars( $settings, $id = 'columns' ) {
		// Responsive controls may be missing entirely (imported/partial data),
		// and an empty value must never become "0 columns".
		$desktop = isset( $settings[ $id ] ) && $settings[ $id ] ? max( 1, min( 6, (int) $settings[ $id ] ) ) : 3;
		$tablet  = isset( $settings[ $id . '_tablet' ] ) && '' !== $settings[ $id . '_tablet' ] ? max( 1, min( 6, (int) $settings[ $id . '_tablet' ] ) ) : 2;
		$mobile  = isset( $settings[ $id . '_mobile' ] ) && '' !== $settings[ $id . '_mobile' ] ? max( 1, min( 6, (int) $settings[ $id . '_mobile' ] ) ) : 1;
		$gap     = isset( $settings['gap'] ) && is_array( $settings['gap'] ) && isset( $settings['gap']['size'] ) ? (int) $settings['gap']['size'] : 28;

		return sprintf( '--bp-cols:%1$s;--bp-cols-t:%2$s;--bp-cols-m:%3$s;--bp-gap:%4$dpx;', $desktop, $tablet, $mobile, $gap );
	}

	/**
	 * Availability / category filter options.
	 *
	 * @return array
	 */
	protected function category_options() {
		$options = array( '' => __( 'All categories', 'brickpoint' ) );
		$terms   = brickpoint_get_product_categories( array( 'hide_empty' => false ) );

		foreach ( $terms as $term ) {
			$options[ $term->slug ] = $term->name;
		}

		return $options;
	}

	/**
	 * Video category options.
	 *
	 * @return array
	 */
	protected function video_category_options() {
		$options = array( '' => __( 'All video categories', 'brickpoint' ) );
		$terms   = get_terms(
			array(
				'taxonomy'   => 'bp_video_category',
				'hide_empty' => false,
			)
		);

		if ( is_wp_error( $terms ) ) {
			return $options;
		}

		foreach ( $terms as $term ) {
			$options[ $term->slug ] = $term->name;
		}

		return $options;
	}

	/**
	 * Project category options.
	 *
	 * @return array
	 */
	protected function project_category_options() {
		$options = array( '' => __( 'All project categories', 'brickpoint' ) );
		$terms   = get_terms(
			array(
				'taxonomy'   => 'bp_project_category',
				'hide_empty' => false,
			)
		);

		if ( is_wp_error( $terms ) ) {
			return $options;
		}

		foreach ( $terms as $term ) {
			$options[ $term->slug ] = $term->name;
		}

		return $options;
	}
}
