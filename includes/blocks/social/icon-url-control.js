const { Component } = wp.element;

const {
  UrlInput,
} = wp.editor;

function ucfirst(str) {
  str += ''
  var f = str.charAt(0)
    .toUpperCase()
  return f + str.substr(1)
}

export default class AdminControlIconURLS extends Component {

  constructor() {
    super( ...arguments );
  }

  componentDidMount() {

    var $contact_form = $( '.social-icon-urls' ),
        props         = this.props;

    $contact_form.sortable( {
      items : '> *:not(.not-sortable)',
      handle: '.wpcw-social-icons-sortable-handle',
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
        var icons = [];
        $( '.social-icon-urls' ).children().not( '.default-fields' ).each( function() {
          icons.push( $( this ).attr( 'class' ) );
        } );
        props.setAttributes( { icons: icons } );
        $contact_form.sortable( 'cancel' );
      }
    } );

  }

  render() {
    const { attributes: { icons, iconURLS }, setAttributes  } = this.props;

    return icons.map( function( icon ) {

      var iconData = ( icon in wpcw_social.icons ) ? wpcw_social.icons[ icon ] : false;

      if ( ! iconData || $.inArray( icon, icons ) < 0 ) {

        return;

      }

      var iconLabel  = iconData['label'],
          iconURL    = iconData['default'],
          iconSelect = iconData['select'];

      return (
        <p className={ icon }>
          <label for="social-networks">
            <span className={ wpcw_social.iconPrefix + " fa-" + icon }></span>
            <span className="text">{ ucfirst( icon )  }</span>
          </label>
          <span className="holder">
            <UrlInput
              className="url"
              value={ iconURL }
              onChange={ url => setAttributes( { url } ) }
            />
            <span className="wpcw-social-icons-sortable-handle">
              <span className="dashicons dashicons-menu"></span>
            </span>
          </span>
        </p>
      );

    } );

  }
}
