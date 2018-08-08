const { Component } = wp.element;

const {
  UrlInput,
} = wp.editor;

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
    const { attributes: { icons, iconURLS }, setAttributes, getIconData  } = this.props;
    const updateIconURLS = () => {
      var newURLS = {};
      $( '.holder input[type="text"]' ).each( function() {
        newURLS[ $( this ).closest( 'span' ).data( 'icon' ) ] = $( this ).val();
      } );
      setAttributes( { iconURLS: newURLS } );
    };

    return icons.map( function( icon ) {

      var iconData = getIconData( icon );

      if ( ! iconData ) {

        return;

      }

      var iconLabel  = iconData['label'],
          iconURL    = ( icon in iconURLS ) ? iconURLS[ icon ] : iconData['default'],
          iconSelect = iconData['select'];

      return (
        <p className={ icon }>
          <label for="social-networks">
            <span className={ wpcw_social.iconPrefix + " fa-" + icon }></span>
            <span className="text">{ iconLabel }</span>
          </label>
          <span className="holder" data-icon={ icon }>
            <UrlInput
              value={ iconURL }
              onChange={ updateIconURLS }
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
