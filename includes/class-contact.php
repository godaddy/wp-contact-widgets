<?php

namespace WPCW;

if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

final class Contact extends Base_Widget {

	/**
	 * Widget constructor
	 */
	public function __construct() {

		$widget_options = [
			'classname'   => 'wpcw-widget-contact',
			'description' => __( 'Display custom contact information.', 'contact-widgets' ),
		];

		parent::__construct(
			'wpcw_contact',
			__( 'Contact Details', 'contact-widgets' ),
			$widget_options
		);

	}

	/**
	 * Widget form fields
	 *
	 * @param array $instance The widget options
	 *
	 * @return string|void
	 */
	public function form( $instance ) {

		parent::form( $instance );

		$fields = $this->get_fields( $instance );

		echo '<div class="wpcw-widget wpcw-widget-contact">';

		echo '<div class="title">';

		// Title field
		$this->render_form_input( array_shift( $fields ) );

		echo '</div>';

		echo '<div class="form">';

		foreach ( $fields as $key => $field ) {

			$method = $field['form_callback'];

			if ( is_callable( [ $this, $method ] ) ) {

				$this->$method( $field );

			}

		}

		// Workaround customizer refresh @props @westonruter
		echo '<input class="customizer_update" type="hidden" value="">';

		echo '</div>'; // End form

		echo '</div>'; // End wpcw-widget-contact

	}

	/**
	 * Front-end display
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {

		$fields = $this->get_fields( $instance );

		if ( $this->is_widget_empty( $fields ) ) {

			return;

		}

		$suffix = SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_style( 'wpcw', \Contact_Widgets::$assets_url . "css/style{$suffix}.css", [], Plugin::$version );

		$this->before_widget( $args, $fields );

		$display_labels = ( 'yes' === $instance['labels']['value'] );

		foreach ( $fields as $field ) {

			if ( empty( $field['value'] ) || ! $field['show_front_end'] ) {

				continue;

			}

			$escape_callback = $field['escaper'];
			$label           = str_replace( ':', '', $field['label'] );

			printf(
				'<li class="%s">%s<div>%s</div></li>',
				$display_labels ? 'has-label' : 'no-label',
				$display_labels ? sprintf( '<strong>%s</strong><br>', esc_html( $label ) ) : '',
				$escape_callback( $field['value'] )
			);

		}

		if ( 'yes' === $instance['map']['value'] && ! empty( $fields['address']['value'] ) ) {

			printf(
				'<li class="has-map"><iframe src="//www.google.com/maps?q=%s&output=embed&hl=%s" scrolling="no" frameborder="0"></iframe></li>',
				urlencode( trim( strip_tags( $fields['address']['value'] ) ) ),
				urlencode( $this->get_google_maps_locale() )
			);

		}

		$this->after_widget( $args, $fields );

	}

	/**
	 * Initialize fields for use on front-end of forms
	 *
	 * @param array $instance
	 * @param array $fields
	 * @param bool  $ordered
	 *
	 * @return array
	 */
	protected function get_fields( array $instance, array $fields = [], $ordered = true ) {

		$fields = [
			'title'   => [
				'label'       => __( 'Title:', 'contact-widgets' ),
				'description' => __( 'The title of widget. Leave empty for no title.', 'contact-widgets' ),
				'value'       => ! empty( $instance['title'] ) ? $instance['title'] : '',
				'sortable'    => false,
			],
			'email'   => [
				'label'       => __( 'Email:', 'contact-widgets' ),
				'type'        => 'email',
				'sanitizer'   => 'sanitize_email',
				'escaper'     => function( $value ) {
					// Work around until https://core.trac.wordpress.org/ticket/32787
					return sprintf( '<a href="mailto:%1$s">%1$s</a>', antispambot( $value ) );
				},
				'description' => __( 'An email address where website vistors can contact you.', 'contact-widgets' ),
			],
			'phone'   => [
				'label'       => __( 'Phone:', 'contact-widgets' ),
				'type'        => 'text',
				'description' => __( 'A phone number that website vistors can call if they have questions.', 'contact-widgets' ),
			],
			'fax'   => [
				'label'       => __( 'Fax:', 'contact-widgets' ),
				'type'        => 'text',
				'description' => __( 'A fax number that website vistors can use to send important documents.', 'contact-widgets' ),
			],
			'address' => [
				'label'         => __( 'Address:', 'contact-widgets' ),
				'type'          => 'textarea',
				'sanitizer'     => function( $value ) { return current_user_can( 'unfiltered_html' ) ? $value : wp_kses_post( stripslashes( $value ) ); },
				'escaper'       => function( $value ) { return nl2br( apply_filters( 'widget_text', $value ) ); },
				'form_callback' => 'render_form_textarea',
				'description'   => __( 'A physical address where website vistors can go to visit you in person.', 'contact-widgets' ),
			],
			'labels'  => [
				'label'          => __( 'Display labels?', 'contact-widgets' ),
				'class'          => '',
				'label_after'    => true,
				'type'           => 'checkbox',
				'sortable'       => false,
				'value'          => 'yes',
				'atts'           => $this->checked( 'yes', isset( $instance['labels']['value'] ) ? $instance['labels']['value'] : 'yes' ),
				'show_front_end' => false,
			],
			'map'  => [
				'label'          => __( 'Display map of address?', 'contact-widgets' ),
				'class'          => '',
				'label_after'    => true,
				'type'           => 'checkbox',
				'sortable'       => false,
				'value'          => 'yes',
				'atts'           => $this->checked( 'yes', isset( $instance['map']['value'] ) ? $instance['map']['value'] : 'yes' ),
				'show_front_end' => false,
			],
		];

		$fields = apply_filters( 'wpcw_widget_contact_custom_fields', $fields, $instance );
		$fields = parent::get_fields( $instance, $fields );

		/**
		 * Filter the contact fields
		 *
		 * @since 1.0.0
		 *
		 * @var array
		 */
		return (array) apply_filters( 'wpcw_widget_contact_fields', $fields, $instance );

	}

	/**
	 * Get the current locale in Google Maps API format
	 *
	 * @link https://developers.google.com/maps/faq#languagesupport
	 *
	 * @return string
	 */
	protected function get_google_maps_locale() {

		$locale = get_locale();

		switch ( $locale ) {

			case 'en_AU' :
			case 'en_GB' :
			case 'pt_BR' :
			case 'pt_PT' :
			case 'zh_TW' :

				$locale = str_replace( '_', '-', $locale );

				break;

			case 'zh_CH' :

				$locale = 'zh-CN';

				break;

			default :

				$locale = substr( $locale, 0, 2 );

		}

		return $locale;

	}

}
