<?php

namespace WPCW;

if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

final class Hours extends Base_Widget {

	/**
	 * Array of days in a week.
	 *
	 * @since NEXT
	 *
	 * @var array
	 */
	public $days_of_week = [];

	/**
	 * Maximum number of time blocks to allow per day.
	 *
	 * @since NEXT
	 *
	 * @var int
	 */
	private $max_time_blocks = 2;

	/**
	 * Widget constructor
	 */
	public function __construct() {

		$widget_options = [
			'classname'                   => 'wpcw-widgets wpcw-widget-hours',
			'description'                 => __( 'Display your hours of operation.', 'contact-widgets' ),
			'customize_selective_refresh' => true,
		];

		parent::__construct(
			'wpcw_hours',
			__( 'Hours of Operation', 'contact-widgets' ),
			$widget_options
		);

		$this->days_of_week = [
			0 => __( 'Sunday', 'contact-widgets' ),
			1 => __( 'Monday', 'contact-widgets' ),
			2 => __( 'Tuesday', 'contact-widgets' ),
			3 => __( 'Wednesday', 'contact-widgets' ),
			4 => __( 'Thursday', 'contact-widgets' ),
			5 => __( 'Friday', 'contact-widgets' ),
			6 => __( 'Saturday', 'contact-widgets' ),
		];

		/**
		 * Filter the maximum number of time blocks to allow per day.
		 *
		 * @since NEXT
		 *
		 * @var int
		 */
		$this->max_time_blocks = (int) apply_filters( 'wpcw_widget_hours_max_time_blocks', $this->max_time_blocks );
		$this->max_time_blocks = ( $this->max_time_blocks > 0 ) ? $this->max_time_blocks : 1; // Must be greater than 0

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

		echo '<div class="wpcw-widget wpcw-widget-hours">';

		echo '<div class="title">';

		// Title field
		$this->render_form_input( array_shift( $fields ) );

		echo '</div>';

		echo '<div class="form">';

		foreach ( $fields as $key => $field ) {

			$method = $field['form_callback'];

			if (
				! is_callable( [ $this, $method ] )
				||
				( empty( $field['value'] ) && $field['hide_empty'] )
			) {

				continue;

			}

			if ( ! empty( $field['prepend'] ) ) {

				echo $field['prepend']; // xss ok

			}

			$this->$method( $field );

			if ( ! empty( $field['append'] ) ) {

				echo $field['append']; // xss ok

			}

		}

		// Workaround customizer refresh @props @westonruter
		echo '<input class="customizer_update" type="hidden" value="">';

		echo '</div><!-- .form -->';

		echo '</div><!-- .wpcw-widget-hours -->';

	}

	/**
	 * Override update method to unset empty time blocks.
	 *
	 * @since NEXT
	 *
	 * @param  array $new_instance
	 * @param  array $old_instance
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {

		$new_instance = parent::update( $new_instance, $old_instance );

		if ( 1 === $this->max_time_blocks ) {

			return $new_instance;

		}

		foreach ( $this->get_days_of_week() as $day => $label ) {

			foreach ( range( 1, $this->max_time_blocks ) as $block ) {

				if (
					empty( $new_instance['schedule'][ $day ]['blocks'][ $block ]['open'] )
					&&
					empty( $new_instance['schedule'][ $day ]['blocks'][ $block ]['close'] )
				) {

					unset( $new_instance['schedule'][ $day ]['blocks'][ $block ] );

				}

			}

		}

		return $new_instance;

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

		$this->before_widget( $args, $fields );

		$display_current_status = ( 'yes' === $this->get_field_value( $instance, 'display_current_status', 'yes' ) );
		$hide_closed            = ( 'yes' === $this->get_field_value( $instance, 'hide_closed', 'yes' ) );
		$display_in_groups      = ( 'yes' === $this->get_field_value( $instance, 'display_in_groups', 'yes' ) );

		if ( $display_current_status ) {

			$suffix = SCRIPT_DEBUG ? '' : '.min';

			wp_enqueue_script( 'wpcw-hours', \Contact_Widgets::$assets_url . "js/wp-hours-widget{$suffix}.js", [ 'jquery' ], Plugin::$version, true );

			wp_localize_script( 'wpcw-hours', 'wpcw_hours', [
				'schedule'      => $this->get_schedule( $instance ),
				'gmt_offset'    => get_option( 'gmt_offset' ),
				'gmt_time'      => current_time( 'H:i' ),
				'open_string'   => apply_filters( 'wpcw_hours_open_string', __( 'Open', 'contact-widgets' ) ),
				'closed_string' => apply_filters( 'wpcw_hours_closed_string',__( 'Closed', 'contact-widgets' ) ),
			] );

			/**
			 * TODO: Handle this with JavaScript so it works with full-page caching.
			 *
			 * 1. Expose this site's timezone and daily schedule as JSON.
			 * 2. Use JavaScript to compare the visitor's current time against our schedule.
			 * 3. Populate this element dynamically with JavaScript.
			 */
			echo '<li><span class="wpcw-open-sign"></span></li>';

		}

		$schedule = $this->get_schedule( $instance, $display_in_groups, $hide_closed );

		foreach ( $schedule as $day => $data ) {

			$is_closed = ( false === $data['open'] );

			if ( $is_closed && $hide_closed ) {

				continue;

			}

			echo '<li>';

			printf( '<strong class="day">%s</strong>', esc_html( $data['label'] ) );

			if ( $is_closed ) {

				printf( '<span>%s</span>', __( 'Closed', 'contact-widgets' ) );

				continue;

			}

			printf(
				'<time itemprop="openingHours" datetime="%s">%s</time>',
				$data['datetime'],
				implode(
					'<br>',
					array_map(
						function ( $close, $open ) {
							return sprintf(
								'%s &ndash; %s',
								date( (string) get_option( 'time_format' ), strtotime( $open ) ),
								date( (string) get_option( 'time_format' ), strtotime( $close ) )
							);
						},
						$data['open'],
						array_keys( $data['open'] )
					)
				)
			);

			echo '</li>';

		}

		if ( $info = $this->get_field_value( $instance, 'info' ) ) {

			printf( '<li>%s</li>', wpautop( $info ) ); // xss ok

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
	protected function get_fields( array $instance, array $fields = [], $ordered = false ) {

		$fields['title'] = [
			'label'       => __( 'Title:', 'contact-widgets' ),
			'description' => __( 'The title of this widget. Leave empty for no title.', 'contact-widgets' ),
		];

		$iteration = 1;

		foreach ( $this->get_days_of_week() as $day => $label ) {

			$is_closed = ( 'yes' === $this->get_field_value( $instance, "schedule[{$day}][closed]" ) );
			$blocks    = range( 0, $this->max_time_blocks - 1 );

			foreach ( $blocks as $block ) {

				$is_last_block = ( false === $this->get_field_value( $instance, sprintf( 'schedule[%d][blocks][%d][open]', $day, $block + 1 ), false ) );

				$fields[ "schedule[{$day}][blocks][{$block}][open]" ] = [
					'type'           => 'select',
					'sanitizer'      => function( $value ) { return date( 'H:i', strtotime( (string) $value ) ); },
					'label'          => ( $block > 0 ) ? sprintf( _x( 'Open Time (Block %d)', 'time block number', 'contact-widgets' ), $block + 1 ) : __( 'Open Time', 'contact-widgets' ),
					'hide_label'     => true,
					'form_callback'  => 'render_form_select',
					'select_options' => $this->get_times(),
					'prepend'        => sprintf( '<div class="time-block" data-time-block="%d">', $block ),
					'hide_empty'     => ( $block > 0 ),
					'default'        => ( $block > 0 ) ? '' : '00:00',
					'class'          => ( $is_closed ) ? 'widefat time-block-open disabled' : 'widefat time-block-open',
					'sortable'       => false,
					'wrapper'        => '',
				];

				if ( 0 === $block ) {

					ob_start();

					?>
					<div class="day-row <?php echo ( $is_closed ) ? 'status-closed' : 'status-open'; ?>" data-day="<?php echo absint( $day ); ?>">
						<div class="day-row-top">
							<strong><?php echo esc_html( $label ); ?></strong>
							<span class="toggle-icon dashicons dashicons-arrow-down"></span>
							<span class="status-open-label"><?php _e( 'Open', 'contact-widgets' ); ?></span>
							<span class="status-closed-label"><?php _e( 'Closed', 'contact-widgets' ); ?></span>
						</div><!-- .day-row-top -->
						<div class="day-row-container">
							<div class="time-blocks">
								<div class="time-block" data-time-block="<?php echo absint( $block ); ?>">
					<?php

					$fields[ "schedule[{$day}][blocks][{$block}][open]" ]['prepend'] = ob_get_clean();

				}

				$fields[ "schedule[{$day}][blocks][{$block}][close]" ] = [
					'type'           => 'select',
					'sanitizer'      => function( $value ) { return date( 'H:i', strtotime( (string) $value ) ); },
					'label'          => ( $block > 0 ) ? sprintf( _x( 'Close Time (Block %d)', 'time block number', 'contact-widgets' ), $block + 1 ) : __( 'Close Time', 'contact-widgets' ),
					'hide_label'     => true,
					'form_callback'  => 'render_form_select',
					'select_options' => $this->get_times(),
					'hide_empty'     => ( $block > 0 ),
					'default'        => ( $block > 0 ) ? '' : '00:00',
					'class'          => ( $is_closed ) ? 'widefat time-block-close disabled' : 'widefat time-block-close',
					'sortable'       => false,
					'wrapper'        => '',
					'append'         => sprintf(
						'<a href="#" class="button button-secondary %s" data-action="%s" %s>%s</a></div><!-- .time-block -->',
						( $is_closed ) ? 'disabled' : '',
						( $block > 0 ) ? 'remove' : 'add',
						( ! $is_last_block ) ? 'style="display: none;"' : '',
						( $block > 0 ) ? '-' : '+'
					),
				];

			}

			$apply_to_all_link = ( 1 === $iteration ) ? sprintf(
				'<a href="#" class="apply-to-all">%s</a>',
				__( 'Apply to all', 'contact-widgets' )
			) : '';

			$fields[ "schedule[{$day}][closed]" ] = [
				'type'      => 'checkbox',
				'sanitizer' => function ( $value ) { return ( 'yes' === (string) $value ) ? 'yes' : 'no'; },
				'class'     => 'status-closed-checkbox',
				'label'     => __( 'Closed', 'contact-widgets' ),
				'default'   => 'no',
				'value'     => 'yes',
				'atts'      => $this->checked( $is_closed, true ),
				'sortable'  => false,
				'wrapper'   => '',
				'prepend'   => sprintf(
					'</div><!-- .time-blocks -->%s<span class="status-closed-checkbox">',
					$apply_to_all_link
				),
				'append'    => '</span><!-- .status-closed-checkbox --></div><!-- .day-row-container --></div><!-- .day-row -->',
			];

			$iteration++;

		}

		$fields['display_current_status'] = [
			'type'        => 'checkbox',
			'sanitizer'   => function ( $value ) { return ( 'yes' === (string) $value ) ? 'yes' : 'no'; },
			'label'       => __( 'Display current status?', 'contact-widgets' ),
			'label_after' => true,
			'default'     => 'no',
			'value'       => 'yes',
			'atts'        => $this->checked( 'yes', $this->get_field_value( $instance, 'display_current_status', 'yes' ) ),
			'sortable'    => false,
		];

		$fields['hide_closed'] = [
			'type'        => 'checkbox',
			'sanitizer'   => function ( $value ) { return ( 'yes' === (string) $value ) ? 'yes' : 'no'; },
			'label'       => __( 'Hide days marked as closed?', 'contact-widgets' ),
			'label_after' => true,
			'default'     => 'no',
			'value'       => 'yes',
			'atts'        => $this->checked( 'yes', $this->get_field_value( $instance, 'hide_closed', 'yes' ) ),
			'sortable'    => false,
		];

		$fields['display_in_groups'] = [
			'type'        => 'checkbox',
			'sanitizer'   => function ( $value ) { return ( 'yes' === (string) $value ) ? 'yes' : 'no'; },
			'label'       => __( 'Group days with the same hours?', 'contact-widgets' ),
			'label_after' => true,
			'default'     => 'no',
			'value'       => 'yes',
			'atts'        => $this->checked( 'yes', $this->get_field_value( $instance, 'display_in_groups', 'yes' ) ),
			'sortable'    => false,
		];

		$fields['info'] = [
			'label'         => __( 'More Information:', 'contact-widgets' ),
			'type'          => 'textarea',
			'sanitizer'     => function( $value ) { return current_user_can( 'unfiltered_html' ) ? (string) $value : wp_kses_post( stripslashes( (string) $value ) ); },
			'escaper'       => function( $value ) { return nl2br( apply_filters( 'widget_text', (string) $value ) ); },
			'form_callback' => 'render_form_textarea',
			'description'   => __( 'Display more information about your hours of operation.', 'contact-widgets' ),
			'sortable'      => false,
			'hide_empty'    => false,
		];

		$fields = apply_filters( 'wpcw_widget_hours_custom_fields', $fields, $instance );
		$fields = parent::get_fields( $instance, $fields, $ordered );

		/**
		 * Filter the contact fields
		 *
		 * @since NEXT
		 *
		 * @return array
		 */
		return (array) apply_filters( 'wpcw_widget_hours_fields', $fields, $instance );

	}

	/**
	 * Return an array of days relative to the site's `start_of_week` setting.
	 *
	 * @since NEXT
	 *
	 * @return array
	 */
	public function get_days_of_week() {

		$start_of_week = (int) get_option( 'start_of_week', 1 );

		$slice_1 = array_slice( $this->days_of_week, $start_of_week, null, true );
		$slice_2 = array_slice( $this->days_of_week, 0, count( $this->days_of_week ) - count( $slice_1 ), true );

		return $slice_1 + $slice_2; // Preserve keys

	}

	/**
	 * Return an array of times in half-hour increments
	 *
	 * @since NEXT
	 *
	 * @return array
	 */
	protected function get_times() {

		/**
		 * Filter the time increment (in minutes)
		 *
		 * @since NEXT
		 *
		 * @return int
		 */
		$increment = (int) apply_filters( 'wpcw_hours_time_increment', HOUR_IN_SECONDS / 60 / 2 );

		$times = range( 0, DAY_IN_SECONDS / 60, $increment );

		$keys = array_map( function ( $time ) {

			return date( 'H:i', $time * 60 );

		}, $times );

		$values = array_map( function ( $time ) {

			return date( (string) get_option( 'time_format' ), $time * 60 );

		}, $times );

		return array_combine( $keys, $values );

	}

	/**
	 * Return the schedule of a widget instance.
	 *
	 * @param  array  $instance
	 * @param  bool   $group       (optional)
	 * @param  bool   $hide_closed (optional)
	 *
	 * @return array
	 */
	public function get_schedule( array $instance, $group = false, $hide_closed = false ) {

		$schedule     = [];
		$days_of_week = $this->get_days_of_week();

		foreach ( $days_of_week as $day => $label ) {

			$schedule[ $day ]['label'] = $label;

			$is_closed = ( 'yes' === $this->get_field_value( $instance, "schedule[{$day}][closed]", 'no' ) );

			if ( $is_closed ) {

				$schedule[ $day ]['open'] = false;

				if ( $hide_closed ) {

					unset( $schedule[ $day ] );

				}

				continue;

			}

			$blocks = $this->get_field_value( $instance, "schedule[{$day}][blocks]", [] );

			foreach ( $blocks as $block ) {

				$schedule[ $day ]['open'][ $block['open'] ] = $block['close'];

			}

			$schedule[ $day ]['datetime'] = $this->get_datetime( $day, $schedule[ $day ]['open'] );

		}

		if ( ! $group ) {

			return $schedule;

		}

		$groups = [];

		foreach ( $schedule as $day => $data ) {

			if ( false === $data['open'] ) {

				if ( ! $hide_closed ) {

					$groups['closed']['label'][ $day ] = $days_of_week[ $day ];
					$groups['closed']['open']          = false;

				}

				continue;

			}

			$key = md5(
				implode(
					'',
					array_map(
						function ( $close, $open ) {
							return $open . $close;
						},
						$data['open'],
						array_keys( $data['open'] )
					)
				)
			);

			$groups[ $key ]['label'][ $day ] = $days_of_week[ $day ];
			$groups[ $key ]['open']          = $data['open'];

		}

		foreach ( $groups as $key => &$group ) {

			if ( false !== $group['open'] ) {

				$group['datetime'] = $this->get_datetime( array_keys( $group['label'] ), $group['open'] );

			}

			$group['label'] = $this->get_grouped_days_label( array_keys( $group['label'] ) );

		}

		return array_values( $groups );

	}

	/**
	 * Return a label for days that are grouped together that
	 * also honors the `start_of_week` setting.
	 *
	 * @param  array $days
	 *
	 * @return string
	 */
	protected function get_grouped_days_label( array $days ) {

		$slices       = [];
		$days_of_week = $this->get_days_of_week();
		$week         = array_keys( $days_of_week );

		foreach ( array_values( array_diff( $week, $days ) ) as $day ) {

			$slice = array_slice( $week, 0, (int) array_search( $day, $week ) );
			$week  = array_values( array_diff( $week, $slice ) );

			unset( $week[0] );

			$week  = array_values( $week );
			$slice = array_combine(
				$slice,
				array_intersect_key(
					$days_of_week,
					array_flip( $slice )
				)
			);

			$slices[] = $slice;

		}

		$last = array_intersect( $week, $days );

		$slices[] = array_combine(
			$last,
			array_intersect_key(
				$days_of_week,
				array_flip( $last )
			)
		);

		$labels = [];

		foreach ( array_values( array_filter( $slices ) ) as $range ) {

			$count = count( $range );

			if ( 1 === $count || 2 === $count ) {

				$labels[] = array_shift( $range );

			}

			if ( 2 === $count ) {

				$labels[] = array_pop( $range );

			}

			if ( $count > 2 ) {

				$labels[] = sprintf(
					'%s &ndash; %s',
					array_shift( $range ),
					array_pop( $range )
				);

			}

		}

		$length = count( $labels ) - 2;

		return trim(
			sprintf(
				'%s %s',
				( $length > 0 ) ? implode( ', ', array_slice( $labels, 0, $length ) ) . ',' : null,
				implode( ' & ', array_slice( $labels, -2, 2 ) )
			)
		);

	}

	/**
	 * Return a datetime string suitable for microformats.
	 *
	 * e.g. Mo,Tu,We,Th,Fr 09:00-17:00
	 *
	 * @param  int|array $days
	 * @param  array     $time_blocks
	 *
	 * @return string
	 */
	protected function get_datetime( $days, array $time_blocks ) {

		$days = array_map(
			function ( $day ) {
				return substr( jddayofweek( fmod( $day - 1, 7 ), 2 ), 0, 2 );
			},
			(array) $days
		);

		$times = array_map(
			function ( $close, $open ) {
				return sprintf( '%s-%s', $open, $close );
			},
			$time_blocks,
			array_keys( $time_blocks )
		);

		return sprintf(
			'%s %s',
			implode( ',', $days ),
			implode( ',', $times )
		);

	}

}
