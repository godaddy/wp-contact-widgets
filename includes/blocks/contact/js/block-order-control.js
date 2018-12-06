const { Component } = wp.element;

const { __ } = wp.i18n;

export default class BlockOrderControl extends Component {

  constructor() {
    super( ...arguments );
  }

  componentDidMount() {

    var $contactFormFields = $( '.contact-fields-order' ),
        props              = this.props;

    $contactFormFields.sortable( {
      items : '> *:not(.not-sortable)',
      handle: '.wpcw-contact-field-sortable-handle',
      containment: 'parent',
      placeholder: 'sortable-placeholder',
      axis: 'y',
      forcePlaceholderSize: true,
      scroll: false,
      tolerance: 'pointer',
      start: function( e, ui ) {
        ui.placeholder.height( ui.item.height() );
      },
      stop: function( e, ui ) {
        var fields = [];
        $( '.contact-fields-order' ).children().each( function() {
          fields.push( { label: $( this ).data( 'label' ) } );
        } );
        props.setAttributes( { fields: fields } );
      }
    } );

  }

  render() {
    const { attributes: { fields }, setAttributes  } = this.props;

    var fieldMarkup = fields.map( function( field, i ) {

      return (
        <li key={ field.label } data-label={ field.label }>
          <span className="wpcw-contact-field-sortable-handle">
            <span className="dashicons dashicons-menu"></span>
          </span>
          { field.label }
        </li>
      );

    } );

    return <div className="contact-fields-reorder"><h2 className="reorder-title">{ __( 'Reorder Contact Fields', 'contact-widgets' ) }</h2><ul className="contact-fields-order">{ fieldMarkup }</ul></div>;

  }
}
