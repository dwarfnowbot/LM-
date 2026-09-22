<?php
/**
 * Elementor: BrickPoint Team.
 *
 * Customisable team section for the About Us page: name, role / designation,
 * photo and a short intro per member. Every field is editable in Elementor;
 * the layout is controlled by the shared "Columns" control and follows the
 * theme's card styles, so it adapts to desktop, laptop, tablet and mobile.
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
use Elementor\Utils;

/**
 * Team grid widget.
 */
class Team extends Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'bp_team';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Team', 'brickpoint' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-person';
	}

	/**
	 * Controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->register_heading_controls( 'head', __( 'Section Heading', 'brickpoint' ) );

		$this->start_controls_section(
			'members_section',
			array(
				'label' => __( 'Team Members', 'brickpoint' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'photo',
			array(
				'label'   => __( 'Photo', 'brickpoint' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);

		$repeater->add_control(
			'name',
			array(
				'label'       => __( 'Name', 'brickpoint' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'role',
			array(
				'label'       => __( 'Role / designation', 'brickpoint' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'bio',
			array(
				'label' => __( 'Short introduction', 'brickpoint' ),
				'type'  => Controls_Manager::TEXTAREA,
				'rows'  => 3,
			)
		);

		$this->add_control(
			'members',
			array(
				'label'       => __( 'Members', 'brickpoint' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'name' => __( 'Syed Iftikhar Haider', 'brickpoint' ),
						'role' => __( 'Chief Executive Officer', 'brickpoint' ),
						'bio'  => __( 'Leads supply planning and the production companies behind BrickPoint.', 'brickpoint' ),
					),
					array(
						'name' => __( 'Qasim Iqbal', 'brickpoint' ),
						'role' => __( 'Sales Manager', 'brickpoint' ),
						'bio'  => __( 'Your first point of contact for quotations, availability and delivery coordination.', 'brickpoint' ),
					),
				),
				'title_field' => '{{{ name }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'layout_section',
			array(
				'label' => __( 'Layout', 'brickpoint' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->register_column_controls( 'columns' );

		$this->add_control(
			'photo_ratio',
			array(
				'label'     => __( 'Photo shape', 'brickpoint' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '1-1',
				'options'   => array(
					'1-1'   => __( 'Square', 'brickpoint' ),
					'4-3'   => '4:3',
					'16-9'  => '16:9',
					'circle' => __( 'Circle', 'brickpoint' ),
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

		$this->render_heading( $s, 'head' );

		$members = ! empty( $s['members'] ) && is_array( $s['members'] ) ? $s['members'] : array();

		if ( ! $members ) {
			printf(
				'<p class="bp-empty">%s</p>',
				esc_html__( 'Add team members in the widget settings (name, role, photo, introduction).', 'brickpoint' )
			);

			return;
		}

		$ratio_class = 'bp-team--ratio-' . ( in_array( $s['photo_ratio'], array( '1-1', '4-3', '16-9', 'circle' ), true ) ? $s['photo_ratio'] : '1-1' );

		printf(
			'<div class="bp-grid-cols bp-team-grid %1$s" style="%2$s">',
			esc_attr( $ratio_class ),
			esc_attr( $this->grid_vars( $s ) )
		);

		foreach ( $members as $index => $member ) {
			$name = isset( $member['name'] ) ? trim( (string) $member['name'] ) : '';
			$role = isset( $member['role'] ) ? trim( (string) $member['role'] ) : '';
			$bio  = isset( $member['bio'] ) ? trim( (string) $member['bio'] ) : '';
			$photo_id = isset( $member['photo']['id'] ) ? (int) $member['photo']['id'] : 0;
			$photo_url = isset( $member['photo']['url'] ) ? (string) $member['photo']['url'] : '';

			echo '<article class="bp-team-card bp-reveal">';

			echo '<div class="bp-team-card__media bp-media">';

			if ( $photo_id ) {
				echo wp_get_attachment_image(
					$photo_id,
					'bp-square',
					false,
					array(
						'class'   => 'bp-media__img',
						'loading' => 'lazy',
						'alt'     => $name,
					)
				);
			} elseif ( $photo_url ) {
				printf(
					'<img class="bp-media__img" src="%1$s" alt="%2$s" loading="lazy" />',
					esc_url( $photo_url ),
					esc_attr( $name )
				);
			} else {
				echo brickpoint_placeholder( '1x1' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			echo '</div>';

			echo '<div class="bp-team-card__body">';

			if ( $name ) {
				printf( '<h3 class="bp-team-card__name">%s</h3>', esc_html( $name ) );
			}

			if ( $role ) {
				printf( '<p class="bp-eyebrow bp-team-card__role">%s</p>', esc_html( $role ) );
			}

			if ( $bio ) {
				printf( '<p class="bp-team-card__bio">%s</p>', esc_html( $bio ) );
			}

			echo '</div>';

			echo '</article>';
		}

		echo '</div>';
	}
}
