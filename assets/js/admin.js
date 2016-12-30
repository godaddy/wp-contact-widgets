( function ( $ ) {

	$.fn.selectString = function ( string ) {

		var el    = $( this )[ 0 ],
		    start = el.value.indexOf( string ),
		    end   = start + string.length;

		if ( ! el || start < 0 ) {

			return;

		} else if ( el.setSelectionRange ) {

			// Webkit
			el.focus();
			el.setSelectionRange( start, end );

		} else if ( el.createTextRange ) {

			var range = el.createTextRange();

			// IE
			range.collapse( true );
			range.moveEnd( 'character', end );
			range.moveStart( 'character', start );
			range.select();

		} else if ( el.selectionStart ) {

			el.selectionStart = start;
			el.selectionEnd   = end;

		}

	};

	function start_sortable() {

		var $contact_form = $( '.wpcw-widget .form' ).not( '.wpcw-widget-hours .form' );

		$contact_form.sortable( {
			items : '> *:not(.not-sortable)',
			handle: '.wpcw-widget-sortable-handle',
			containment: 'parent',
			placeholder: 'sortable-placeholder',
			axis: 'y',
			tolerance: 'pointer',
			forcePlaceholderSize: true,
			cursorAt: { top: 40 },
			stop: function ( e, ui ) {
				// Trigger change for customizer
				$contact_form.find( '.customizer_update' ).val( ui.item.index() ).trigger( 'change' );
			}
		} );

	}

	var socialField = {

		     $btn: null,
		  $widget: null,
		$template: null,

		init: function ( e ) {

			e.preventDefault();

			var self = socialField;

			self.$btn    = $( this );
			self.$widget = self.$btn.parents( '.wpcw-widget' );

			// Make sure we don't trigger the animation again on double-click
			if ( self.$widget.find( '.' + self.$btn.data('key') ).is( ':animated' ) ) {

				return false;

			}

			if ( self.$btn.hasClass( 'inactive' ) ) {

				self.$template = self.$widget.find( '.default-fields' );
				self.$template = $( $.trim( self.$template.clone().html() ) );

				self.add();

				return;

			}

			self.remove();

		},

		add: function () {

			this.$btn.removeClass( 'inactive' );

			var data = this.$btn.data();

			this.$template
				.addClass( data.key )
				.find( 'label' )
				.prop( 'for', data.id );

			this.$template
				.find( 'input' )
				.prop( 'id', data.id )
				.prop( 'name', data.name )
				.prop( 'value', data.value );

			this.$template
				.find( 'label span.fa' )
				.prop( 'class', this.$btn.find( 'i' ).attr( 'class' ) );

			this.$template
				.find( 'label span.text' )
				.text( data.label );

			this.$template
				.hide()
				.prependTo( this.$widget.find( '.form' ) )
				.stop( true, true )
				.animate( {
					height: 'toggle',
					opacity: 'toggle'
				}, 250 );

			this.$template.find( 'input' ).selectString( data.select );

			this.update_customizer();

		},

		remove: function () {

			this.$btn.addClass( 'inactive' );

			this.$widget
				.find( '.form .' + this.$btn.data( 'key' ) )
				.stop( true, true )
				.animate( {
					height: 'toggle',
					opacity: 'toggle'
				}, 250, function () {
					$( this ).remove();
				} );

			this.update_customizer();

		},

		update_customizer: function () {

			var count = this.$widget.find( 'div > div' ).length;

			this.$widget.find( '.customizer_update' ).val( count ).trigger( 'change' );

		}

	};

	var dayRow = {

		toggle: function ( e ) {

			e.preventDefault();

			var $day       = $( this ).closest( '.day-row' ),
			    $container = $day.find( '.day-row-container' );

			if ( $container.is( ':animated' ) ) {

				return false;

			}

			var $icon  = $day.find( '.toggle-icon' ),
			    active = $day.hasClass( 'active' );

			if ( ! active ) {

				$icon
					.toggleClass( 'dashicons-arrow-down' )
					.toggleClass( 'dashicons-arrow-up' );

			}

			$container.slideToggle( 'fast', function () {

				if ( active ) {

					$icon
						.toggleClass( 'dashicons-arrow-down' )
						.toggleClass( 'dashicons-arrow-up' );

				}

				$day.toggleClass( 'active' );

			} );

		},

		maybeBlockSelect: function ( e ) {

			var $day = $( this ).closest( '.day-row' );

			if ( $day.hasClass( 'status-closed' ) ) {

				e.preventDefault();

			}

		},

		addBlock: function ( e ) {

			e.preventDefault();

			if ( $( this ).hasClass( 'disabled' ) ) {

				return false;

			}

			var $block  = $( this ).closest( '.time-block' ),
			    $clone  = dayRow.cloneTimeBlock( $block, $block.parent() );

			$clone.find( 'a.button' ).attr( 'data-action', 'remove' ).text( '-' );

			$( this ).hide();

		},

		removeBlock: function ( e ) {

			e.preventDefault();

			if ( $( this ).hasClass( 'disabled' ) ) {

				return false;

			}

			var $block = $( this ).closest( '.time-block' );

			$block.prev( '.time-block' ).find( 'a.button' ).show();

			$block.remove();

		},

		applyToAll: function ( e ) {

			e.preventDefault();

			var $day       = $( this ).closest( '.day-row' ),
			    $widget    = $day.closest( '.wpcw-widget' ),
			    $animating = $widget.find( '.day-row' ).filter( function() { return $( this ).is( ':animated' ); } );

			if ( $animating.length > 0 ) {

				return false;

			}

			if ( $day.hasClass( 'status-closed' ) ) {

				$widget.find( '.status-closed-checkbox input:not(:checked)' ).not( this ).trigger( 'click' );

			} else {

				$widget.find( '.status-closed-checkbox input:checked' ).not( this ).trigger( 'click' );

			}

			var $blocks = $day.find( '.time-block' );

			$widget.find( '.day-row' ).not( $day ).each( function() {

				var $target = $( this ).find( '.time-blocks' );

				$target.empty();

				$blocks.each( function() {

					dayRow.cloneTimeBlock( $( this ), $target, true, true );

				} );

			} );

		},

		cloneTimeBlock: function ( $block, $target, deep, flashDay ) {

			var $day         = $block.closest( '.day-row' ),
			    day          = parseInt( $day.attr( 'data-day' ), 10 ),
			    target_day   = parseInt( $target.closest( '.day-row' ).attr( 'data-day' ), 10 ),
			    block        = parseInt( $block.attr( 'data-time-block' ), 10 ),
			    target_block = $target.is( ':empty' ) ? 0 : parseInt( $target.find( '.time-block' ).last().attr( 'data-time-block' ), 10 ) + 1,
			    deep         = deep || false, // jshint ignore:line
			    flashDay     = flashDay || false, // jshint ignore:line
			    $clone       = $block.clone( deep ),
			    html         = $clone.html(),
			    name_search  = '\\[schedule\\]\\[' + day + '\\]\\[blocks\\]\\[' + block + '\\]',
			    name_replace = '[schedule][' + target_day + '][blocks][' + target_block + ']',
			    id_search    = 'schedule-' + day + '-blocks-' + block,
			    id_replace   = 'schedule-' + target_day + '-blocks-' + target_block;

			html = html.replace( new RegExp( name_search, 'g' ), name_replace ),
			html = html.replace( new RegExp( id_search, 'g' ), id_replace ),

			$clone = $clone.html( html );

			if ( deep ) {

				$clone.find( 'select' ).each( function() {

					var classes = $( this )
						.attr( 'class' )
						.split( ' ' )
						.map( function ( v ) {
							return v.trim();
						} )
						.join( '.' );

					$( this ).val( $block.find( 'select.' + classes ).val() );

				} );

			}

			$target.append( $clone );

			$clone.attr( 'data-time-block', target_block );

			if ( flashDay ) {

				$clone.closest( '.day-row' ).fadeTo( 50, 0.1, function() {

					$( this ).fadeTo( 500, 1.0 );

				} );

			}

			return $clone;

		},

		toggleClosed: function () {

			var $day   = $( this ).closest( '.day-row' ),
			    closed = $day.hasClass( 'status-closed' );

			$day.toggleClass( 'status-closed' ).toggleClass( 'status-open' );

			$day.find( 'a.button' ).toggleClass( 'disabled', ! closed );

			$day.find( 'select' ).toggleClass( 'disabled', ! closed );

		},

		changeTime: function( e ) {

			var $target        = $( e.currentTarget ),
			    time           = $target.val();

			dayRow.updateTimeSelect( $target, time );

		},

		updateTimeSelect: function( $target, time ) {

			if ( ! $target ) {

				$( document ).find( '.wpcw-widget-hours .time-block-open' ).each( function() {

					dayRow.updateTimeSelect( $( this ), $( this ).val() );

				} );

				return;

			}

			var $parent = $target.closest( '.time-block' ),
			    $close_select = $parent.find( '.time-block-close' );

			$close_select.children().show();
			$close_select.find( 'option[value="' + time + '"]' ).prevAll().andSelf().hide();

			if ( time === $target.find( 'option:last-child' ).val() ) {

				$close_select.find( 'option:first-child' ).show().prop( 'selected', true );

				return;

			}

			if ( time >= $close_select.val() ) {

				$close_select.find( 'option[value="' + time + '"]' ).next().prop( 'selected', true );

			}

		}

	};

	$( document ).ready( function ( $ ) {

		// Social
		$( document ).on( 'click', '.wpcw-widget-social .icons a', socialField.init );

		// Hours of Operation
		$( document ).on( 'click', '.wpcw-widget-hours .day-row-top', dayRow.toggle );
		$( document ).on( 'mousedown', '.wpcw-widget-hours .time-block select', dayRow.maybeBlockSelect );
		$( document ).on( 'click', '.wpcw-widget-hours .time-block a.button[data-action="add"]', dayRow.addBlock );
		$( document ).on( 'click', '.wpcw-widget-hours .time-block a.button[data-action="remove"]', dayRow.removeBlock );
		$( document ).on( 'click', '.wpcw-widget-hours .apply-to-all', dayRow.applyToAll );
		$( document ).on( 'change', '.wpcw-widget-hours .status-closed-checkbox input', dayRow.toggleClosed );
		$( document ).on( 'change', '.wpcw-widget-hours .time-block-open', dayRow.changeTime );

		$( document ).find( '.wpcw-widget-hours .time-block-open' ).each( function() {

			dayRow.updateTimeSelect( $( this ), $( this ).val() );

		} );

		// Sortable
		$( document ).on( 'wpcw.change', start_sortable );
		$( document ).on( 'click.widgets-toggle', start_sortable );
		$( document ).on( 'widget-updated', function() {

			start_sortable();

			dayRow.updateTimeSelect();

		} );

	} );

} )( jQuery );
