<?php
/**
 * Base_Widget class.
 *
 * @package ContactWidgets
 */

namespace WPCW;

if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

/**
 * Contact Widgets class Base Widget extends WP_Widget
 *
 * @var array
 */
abstract class Base_Widget extends \WP_Widget {

	/**
	 * Default properties for fields
	 *
	 * @var array
	 */
	protected $field_defaults = array(
		'key'            => '',
		'icon'           => '',
		'class'          => 'widefat',
		'id'             => '',
		'name'           => '',
		'label'          => '',
		'label_after'    => false,
		'description'    => '',
		'type'           => 'text',
		'sanitizer'      => 'sanitize_text_field',
		'escaper'        => 'esc_html',
		'form_callback'  => 'render_form_input',
		'default'        => '', // Used mainly for social fields to add default value.
		'value'          => '',
		'placeholder'    => '',
		'sortable'       => true,
		'atts'           => '', // Input attributes.
		'show_front_end' => true, // Are we showing this field on the front end?
		'show_empty'     => false, // Show the field even if value is empty.
		'select_options' => array(), // Only used if type=select & form_callback=render_form_select.
	);

	/**
	 * Widget base constructor
	 *
	 * @param string $id_base Contains the widget id eg( 'wpcw_social', 'wpcw_contact' ).
	 * @param string $name Name of widget element.
	 * @param array  $widget_options Widget element options.
	 */
	public function __construct( $id_base, $name, array $widget_options ) {

		parent::__construct( $id_base, $name, $widget_options );

		if ( has_action( 'wp_enqueue_scripts', array( $this, 'front_end_enqueue_scripts' ) ) ) {

			return;

		}

		// Enqueue style if widget is active (appears in a sidebar) or if in Customizer preview.
		if ( is_active_widget( false, false, $this->id_base ) || is_customize_preview() ) {

			add_action( 'wp_enqueue_scripts', array( $this, 'front_end_enqueue_scripts' ) );

		}

	}

	/**
	 * Add common resources needed for the form.
	 *
	 * @return string|void
	 */
	public function form() {

		add_action( 'admin_footer', array( $this, 'enqueue_scripts' ) );
		add_action( 'customize_controls_print_footer_scripts', array( $this, 'print_customizer_scripts' ) );

		?>
		<script>
			( function ( $ ) {

				// This let us know that we appended a new widget to reset sortables.
				$( document ).trigger( 'wpcw.change' );

			} )( jQuery );
		</script>
		<?php

	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @param  array $new_instance Values just sent to be saved.
	 * @param  array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {

		$wpcw_fields = $this->get_fields( $old_instance );

		// Force value for checkbox since they are not posted.
		foreach ( $wpcw_fields as $key => $field ) {

			if ( 'checkbox' === $field['type'] && ! isset( $new_instance[ $key ]['value'] ) ) {

				$new_instance[ $key ] = array( 'value' => 'no' ); // @codingStandardsIgnoreLine.

			}
		}

		// Starting at 1 since title order is 0.
		$order = 1;

		foreach ( $new_instance as $key => &$instance ) {

			// Skip unrecognized fields added by other plugins.
			if ( ! array_key_exists( $key, $wpcw_fields ) ) {

				continue;

			}

			$sanitizer_callback = $wpcw_fields[ $key ]['sanitizer'];

			// Title can't be an array.
			if ( 'title' === $key ) {

				$instance = $sanitizer_callback( $instance['value'] );

				continue;

			}

			$instance['value'] = $sanitizer_callback( $instance['value'] );
			$instance['order'] = $order++;

		}

		return $new_instance;

	}

	/**
	 * Initialize fields for use on front-end of forms.
	 *
	 * @param  array $instance The widget options.
	 * @param  array $wpcw_fields Optional field containing widget field data.
	 * @param  bool  $ordered Optional boolean value to order widget data.
	 *
	 * @return array
	 */
	protected function get_fields( array $instance, array $wpcw_fields = array(), $ordered = true ) {

		$order = 0;

		foreach ( $wpcw_fields as $key => &$field ) {

			$common_properties = array(
				'key'   => $key,
				'icon'  => $key,
				'order' => ! empty( $instance[ $key ]['order'] ) ? absint( $instance[ $key ]['order'] ) : $order,
				'id'    => $this->get_field_id( $key ),
				'name'  => $this->get_field_name( $key ) . '[value]',
				'value' => ! empty( $instance[ $key ]['value'] ) ? $instance[ $key ]['value'] : '',
			);

			$common_properties = wp_parse_args( $common_properties, $this->field_defaults );
			$field             = wp_parse_args( $field, $common_properties );

			$default_closure = function( $value ) {
				return $value;
			};

			foreach ( array( 'escaper', 'sanitizer' ) as $key ) {

				$field[ $key ] = ! is_callable( $field[ $key ] ) ? $default_closure : $field[ $key ];

			}

			$order++;

		}

		if ( $ordered ) {

			$wpcw_fields = $this->order_field( $wpcw_fields );

		}

		return $wpcw_fields;

	}

	/**
	 * Order array by field order.
	 *
	 * @param  array $wpcw_fields Optional field containing widget field data.
	 *
	 * @return array
	 */
	protected function order_field( array $wpcw_fields ) {

		uksort(
			$wpcw_fields,
			function( $a, $b ) use ( $wpcw_fields ) {

				// We want title first and order of non sortable fields doesn't matter.
				if ( ! $wpcw_fields[ $a ]['sortable'] && 'title' !== $a ) {

					return 1;

				}

				return ( $wpcw_fields[ $a ]['order'] < $wpcw_fields[ $b ]['order'] ) ? -1 : 1;

			}
		);

		return $wpcw_fields;

	}

	/**
	 * Check if all the fields we show on the front-end are empty.
	 *
	 * @param  array $wpcw_fields Optional field containing widget field data.
	 *
	 * @return bool
	 */
	protected function is_widget_empty( array $wpcw_fields ) {

		foreach ( $wpcw_fields as $key => $field ) {

			/**
			 * Filter to ignore the title when checking if a widget is empty
			 *
			 * @since 1.0.0
			 *
			 * @var bool
			 */
			$ignore_title = (bool) apply_filters( 'wpcw_is_widget_empty_ignore_title', false );

			if ( 'title' === $key && $ignore_title ) {

				continue;

			}

			if ( ! empty( $field['value'] ) && $field['show_front_end'] ) {

				return false;

			}
		}

		return true;

	}

	/**
	 * Print current label.
	 *
	 * @param array $field Associative array containing widget configuration.
	 */
	protected function print_label( array $field ) {

		printf(
			' <label for="%s" title="%s">%s</label>',
			esc_attr( $field['id'] ),
			esc_attr( $field['description'] ),
			esc_html( $field['label'] )
		);

	}

	/**
	 * Print label and wrapper.
	 *
	 * @param array $field Associative array containing widget configuration.
	 */
	protected function before_form_field( array $field ) {

		$classes = array( $field['type'], $field['key'] );

		if ( ! $field['sortable'] ) {

			$classes[] = 'not-sortable';

		}

		if ( $field['label_after'] ) {

			$classes[] = 'label-after';

		}

		printf(
			'<p class="%s">',
			implode( ' ', $classes ) // @codingStandardsIgnoreLine
		);

		if ( ! $field['label_after'] ) {

			$this->print_label( $field );

		}

		if ( $field['sortable'] ) {

			echo '<span>';

		}

	}

	/**
	 * Render input field for admin form.
	 *
	 * @param array $field Associative array containing widget configuration.
	 */
	protected function render_form_input( array $field ) {

		$this->before_form_field( $field );

		printf(
			'<input class="%s" id="%s" name="%s" type="%s" value="%s" placeholder="%s" autocomplete="off" %s>',
			esc_attr( $field['class'] ),
			esc_attr( $field['id'] ),
			esc_attr( $field['name'] ),
			esc_attr( $field['type'] ),
			esc_attr( $field['value'] ),
			esc_attr( $field['placeholder'] ),
			esc_attr( $field['atts'] )
		);

		if ( $field['label_after'] ) {

			$this->print_label( $field );

		}

		$this->after_form_field( $field );

	}

	/**
	 * Render select field.
	 *
	 * @param array $field Associative array containing widget configuration.
	 */
	protected function render_form_select( array $field ) {

		$this->before_form_field( $field );

		printf(
			'<select class="%s" id="%s" name="%s" autocomplete="off">',
			esc_attr( $field['class'] ),
			esc_attr( $field['id'] ),
			esc_attr( $field['name'] )
		);

		foreach ( $field['select_options'] as $value => $name ) {

			printf(
				'<option value="%s" %s>%s</option>',
				esc_attr( $value ),
				$field['value'] === $value ? 'selected' : '',
				esc_html( $name )
			);

		}

		echo '</select>';

		if ( $field['label_after'] ) {

			$this->print_label( $field );

		}

		$this->after_form_field( $field );

	}

	/**
	 * Render textarea field for admin widget form.
	 *
	 * @param array $field Associative array containing widget configuration.
	 */
	protected function render_form_textarea( array $field ) {

		$this->before_form_field( $field );

		printf(
			'<textarea class="%s" id="%s" name="%s" placeholder="%s">%s</textarea>',
			esc_attr( $field['class'] ),
			esc_attr( $field['id'] ),
			esc_attr( $field['name'] ),
			esc_attr( $field['placeholder'] ),
			esc_textarea( $field['value'] )
		);

		$this->after_form_field( $field );

	}

	/**
	 * Close wrapper of form field.
	 *
	 * @param array $field Associative array containing widget configuration.
	 */
	protected function after_form_field( array $field ) {

		if ( $field['sortable'] ) {

			echo '<span class="wpcw-widget-sortable-handle"><span class="dashicons dashicons-menu"></span></span></span>';

		}

		echo '</p>';

	}

	/**
	 * Print beginning of front-end display.
	 *
	 * @param array $args Associative array of widget markup.
	 * @param array $wpcw_fields Optional field containing widget field data.
	 */
	protected function before_widget( array $args, array &$wpcw_fields ) {

		$title = array_shift( $wpcw_fields );

		echo $args['before_widget']; // @codingStandardsIgnoreLine

		if ( ! empty( $title['value'] ) ) {

			/**
			 * Filter the widget title
			 *
			 * @since 1.0.0
			 *
			 * @var string
			 */
			$title = (string) apply_filters( 'widget_title', $title['value'] );

			echo $args['before_title'] . $title . $args['after_title']; // @codingStandardsIgnoreLine

		}

		echo '<ul>';

	}

	/**
	 * Print end of front-end display
	 *
	 * @param array $args Associative array of widget markup.
	 */
	protected function after_widget( array $args ) {

		echo '</ul>';

		if (
			! is_customize_preview()
			&& current_user_can( 'edit_theme_options' )
			&& current_user_can( 'customize' )
			&& isset( $args['id'] )
		) {

			// admin-bar.php -> wp_admin_bar_customize_menu().
			$current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

			$edit_url = add_query_arg(
				array(
					'url'       => rawurlencode( $current_url ),
					'autofocus' => array(
						'section' => 'sidebar-widgets-' . $args['id'],
						'control' => 'widget_' . preg_replace( '/-(\d)/', '[$1]', $args['widget_id'] ),
					),
				),
				wp_customize_url()
			);

			printf(
				'<a class="post-edit-link" data-widget-id="%s" href="%s">%s</a>',
				esc_attr( $args['widget_id'] ),
				esc_url( $edit_url ),
				esc_html__( 'Edit' ) // Use translations from core.
			);

		}

		echo $args['after_widget']; // @codingStandardsIgnoreLine

	}

	/**
	 * Helper to output only 'checked' and not checked='checked'.
	 * IE 9 & 10 don't support the latter.
	 *
	 * @param mixed $helper  One of the values to compare.
	 * @param mixed $current (true) The other value to compare if not just true.
	 * @param bool  $echo    Whether to echo or just return the string.
	 *
	 * @return string html attribute or empty string.
	 */
	public function checked( $helper, $current, $echo = false ) {

		$result = (string) $helper === (string) $current ? 'checked' : '';

		if ( $echo ) {

			echo $result; // @codingStandardsIgnoreLine.

		}

		return $result;

	}

	/**
	 * Print footer script and styles.
	 */
	public function enqueue_scripts() {

		$rtl    = is_rtl() ? '-rtl' : '';
		$suffix = SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_style( 'wpcw-admin', \Contact_Widgets::$assets_url . "css/admin{$rtl}{$suffix}.css", array(), Plugin::$version );

		wp_enqueue_style( 'font-awesome', \Contact_Widgets::$fa_url, array(), '4.7.0' );

		wp_enqueue_script( 'wpcw-admin', \Contact_Widgets::$assets_url . "js/admin{$suffix}.js", array( 'jquery' ), Plugin::$version, true );

		include 'social-networks.php';

		if ( $GLOBALS['is_IE'] ) {

			wp_enqueue_style( 'wpcw-admin-ie', \Contact_Widgets::$assets_url . "css/admin-ie{$rtl}{$suffix}.css", array( 'wpcw-admin' ), Plugin::$version );

		}

	}

	/**
	 * Print customizer script.
	 */
	public function print_customizer_scripts() {

		$this->enqueue_scripts();

		wp_print_styles( array( 'font-awesome', 'wpcw-admin', 'wpcw-admin-ie' ) );
		wp_print_scripts( 'wpcw-admin' );

	}

	/**
	 * Enqueue scripts and styles for front-end use.
	 *
	 * @action wp_enqueue_scripts
	 */
	public function front_end_enqueue_scripts() {

		$rtl    = is_rtl() ? '-rtl' : '';
		$suffix = SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_style( 'wpcw', \Contact_Widgets::$assets_url . "css/style{$rtl}{$suffix}.css", array(), Plugin::$version );

		wp_enqueue_style( 'font-awesome', \Contact_Widgets::$fa_url, array(), '4.7.0' );

		if ( is_customize_preview() ) {

			if ( ! wp_script_is( 'jquery', 'enqueued' ) ) {

				wp_enqueue_script( 'jquery' );

			}

			wp_enqueue_script( 'wpcw-helper', \Contact_Widgets::$assets_url . "js/customize-preview-helper{$suffix}.js", array( 'jquery' ), Plugin::$version, true );

		}

	}

}
