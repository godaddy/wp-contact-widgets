const { Component } = wp.element;

const { __ } = wp.i18n;

export default class BlockOrderControl extends Component {

  constructor() {
    super( ...arguments );
  }

  componentDidMount() {

    var $contactFormFields = $( '.contact-fields-order' ),
        props         = this.props;

    $contactFormFields.sortable( {
      items : '> *:not(.not-sortable)',
      handle: '.wpcw-contact-field-sortable-handle',
      containment: 'parent',
      placeholder: 'sortable-placeholder',
      axis: 'y',
      tolerance: 'pointer',
      forcePlaceholderSize: true,
      cursorAt: { top: 40 },
      scroll: false,
      start: function( e, ui ) {
        ui.placeholder.height( ui.item.height() );
      },
      stop: function( e, ui ) {
        var fields = [];
        $( '.contact-fields-order' ).children().each( function() {
          console.log( $( this ) );
          fields.push( { label: $( this ).data( 'label' ) } );
        } );
        props.setAttributes( { fields: fields } );
        $contact_form.sortable( 'cancel' );
      }
    } );

  }

  render() {
    const { attributes: { fields }, setAttributes  } = this.props;

    var fieldMarkup = fields.map( function( field, i ) {

      return (
        <li key={ field.label } data-label={ field.label }>
          { field.label }
          <span className="wpcw-contact-field-sortable-handle">
            <span className="dashicons dashicons-menu"></span>
          </span>
        </li>
      );

    } );

    return <ul className="contact-fields-order">{ fieldMarkup }</ul>;

  }
}
