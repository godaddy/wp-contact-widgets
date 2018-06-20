<?php

namespace WPCW;

if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

final class Social extends Base_Widget {

	private $icon_prefix;

	/**
	 * Widget constructor
	 */
	public function __construct() {

		$widget_options = [
			'classname'                   => 'wpcw-widgets wpcw-widget-social',
			'description'                 => __( 'Display links to your social media profiles.', 'contact-widgets' ),
			'customize_selective_refresh' => true,
		];

		parent::__construct(
			'wpcw_social',
			__( 'Social Profiles', 'contact-widgets' ),
			$widget_options
		);

		$this->icon_prefix = \Contact_Widgets::$fontawesome_5 ? 'fab' : 'fa';

	}

	/**
	 * Widget form fields
	 *
	 * @param array $instance
	 *
	 * @return string|void
	 */
	public function form( $instance ) {

		parent::form( $instance );

		$fields      = $this->get_fields( $instance );
		$title_field = array_shift( $fields );

		echo '<div class="wpcw-widget wpcw-widget-social">';

		echo '<div class="title">';

		// Title field
		$this->render_form_input( $title_field );

		echo '</div>';

		echo '<div class="icons">';

		foreach ( $fields as $key => $field ) {

			if ( ( ! empty( $field['deprecated'] ) && empty( $field['value'] ) ) || ! isset( $field['social'] ) ) {

				continue;

			}

			printf(
				'<a href="#"
					class="%s"
					title="%s"
					data-key="%s"
					data-value="%s"
					data-select="%s"
					data-name="%s"
					data-id="%s"
					data-label="%s">
					<i class="%s fa-%s"></i>
				</a>',
				empty( $field['value'] ) ? 'inactive' : '',
				esc_attr( $field['label'] ),
				esc_attr( $key ),
				esc_attr( $field['default'] ),
				esc_attr( $field['select'] ),
				esc_attr( $field['name'] ),
				esc_attr( $field['id'] ),
				esc_attr( $field['label'] ),
				esc_attr( $this->get_icon_prefix( $field ) ),
				esc_attr( $field['icon'] )
			);

		}

		echo '</div>';

		echo '<div class="form">';

		$fields = $this->order_field( $fields );

		foreach ( $fields as $key => $field ) {

			$method = $field['form_callback'];

			if (
				is_callable( [ $this, $method ] )
				&&
				( ! empty( $field['value'] ) || $field['show_empty'] )
			) {

				$this->$method( $field );

			}

		}

		// Workaround customizer refresh @props @westonruter
		echo '<input class="customizer_update" type="hidden" value="">';

		echo '</div>'; // End form

		echo '<div class="default-fields">';

		// Template form for JS use
		$this->render_form_input( $this->field_defaults + [ 'social' => true ] ); // @codingStandardsIgnoreLine

		echo '</div>'; // End default-fields

		echo '</div>'; // End wpcw-widget-social

	}

	/**
	 * Front-end display
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {

		$fields = $this->get_fields( $instance, [], true );

		if ( $this->is_widget_empty( $fields ) ) {

			return;

		}

		$this->before_widget( $args, $fields );

		$display_labels = ( 'yes' === $instance['labels']['value'] );

		foreach ( $fields as $field ) {

			if ( empty( $field['value'] ) || ! $field['show_front_end'] ) {

				continue;

			}

			$escape_callback = $field['escaper'];

			printf( // xss ok.
				'<li class="%s"><a href="%s" target="%s" title="%s"><span class="%s fa-%s fa-%s"></span>%s</a></li>',
				( $display_labels ) ? 'has-label' : 'no-label',
				$escape_callback( $field['value'] ),
				esc_attr( $field['target'] ),
				sprintf(
					/* translators: 1. Title of website (e.g. My Cat Blog), 2. Name of social network (e.g. Facebook) */
					esc_attr__( 'Visit %1$s on %2$s', 'contact-widgets' ),
					get_bloginfo( 'name' ),
					$field['label']
				),
				esc_attr( $this->get_icon_prefix( $field ) ),
				isset( $fields['icon_size']['value'] ) ? esc_attr( $fields['icon_size']['value'] ) : '2x',
				esc_attr( $field['icon'] ),
				( $display_labels ) ? esc_html( $field['label'] ) : ''
			);

		}

		$this->after_widget( $args, $fields );

	}

	/**
	 * Enqueue scripts and styles for front-end use
	 *
	 * @action wp_enqueue_scripts
	 * @codingStandardsIgnoreStart
	 */
	public function front_end_enqueue_scripts() {

		parent::front_end_enqueue_scripts();

	}
	// @codingStandardsIgnoreEnd

	/**
	 * Initialize fields for use on front-end of forms
	 *
	 * @param  array $instance
	 * @param  array $fields (optional)
	 * @param  bool  $ordered (optional)
	 *
	 * @return array
	 */
	protected function get_fields( array $instance, array $fields = [], $ordered = false ) {

		include 'social-networks.php';

		foreach ( $fields as $key => &$field ) {

			$default = [
				'sanitizer' => 'esc_url_raw',
				'escaper'   => 'esc_url',
				'select'    => '',
				'social'    => true,
				'target'    => '_blank',
			];

			$field = wp_parse_args( $field, $default );

		}

		$title = [
			'title' => [
				'label'       => __( 'Title:', 'contact-widgets' ),
				'description' => __( 'The title of widget. Leave empty for no title.', 'contact-widgets' ),
				'value'       => ! empty( $instance['title'] ) ? $instance['title'] : '',
				'sortable'    => false,
			],
		];

		// Prepend title field to the array
		$fields = $title + $fields;

		$fields['labels'] = [
			'label'          => __( 'Display labels?', 'contact-widgets' ),
			'class'          => '',
			'label_after'    => true,
			'type'           => 'checkbox',
			'sortable'       => false,
			'value'          => 'yes',
			'atts'           => $this->checked( 'yes', isset( $instance['labels']['value'] ) ? $instance['labels']['value'] : 'no' ),
			'show_front_end' => false,
		];

		$fields = apply_filters( 'wpcw_widget_social_custom_fields', $fields, $instance );
		$fields = parent::get_fields( $instance, $fields, $ordered );

		/**
		 * Filter the social fields
		 *
		 * @since 1.0.0
		 *
		 * @var array
		 */
		return (array) apply_filters( 'wpcw_widget_social_fields', $fields, $instance );

	}

	/**
	 * Print label and wrapper
	 *
	 * @param array $field
	 */
	protected function print_label( array $field ) {

		if ( ! isset( $field['social'] ) ) {

			parent::print_label( $field );
			return;

		}

		printf(
			'<label for="%s"><span class="%s fa-%s"></span> <span class="text">%s</span></label>',
			esc_attr( $field['id'] ),
			esc_attr( $this->get_icon_prefix( $field ) ),
			esc_attr( $field['icon'] ),
			esc_html( $field['label'] )
		);

	}

	/**
	 * Determine the icon prefix.
	 *
	 * @param array $field
	 */
	private function get_icon_prefix( array $field ) {

		return ( \Contact_Widgets::$fontawesome_5 && isset( $field['prefix'] ) ) ? $field['prefix'] : $this->icon_prefix;

	}

}
