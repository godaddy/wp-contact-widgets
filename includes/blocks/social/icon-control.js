const { Component } = wp.element;

export default class AdminControlIcons extends Component {

  constructor() {
    super( ...arguments );
  }

  render() {
    const { attributes: { icons, getIconData }, setAttributes  } = this.props;
    const toggleSelectedIcons = ( e, iconClass ) => {

      e.preventDefault();

      var $btn     = $( e.target ).closest( 'a' ),
          newIcons = [ ...icons ];

      $btn.toggleClass( 'inactive' );

      if ( $btn.hasClass( 'inactive' ) ) {
        var iconIndex = icons.indexOf( iconClass );
        if ( iconIndex > -1 ) {
          newIcons.splice( iconIndex, 1 );
        }
      } else {
        newIcons = [ ...icons, iconClass ];
      }

      // Breaking things...
      this.props.setAttributes( { icons: newIcons } );
    };

    if ( ! Object.keys( wpcw_social.icons ).length ) {
      return <h2>{ __( 'No Icons Found.', 'contact-widgets' ) }</h2>;
    }

    return Object.keys( wpcw_social.icons ).map( function( key ) {

      var iconClass  = ( "icon" in wpcw_social.icons[ key ] ) ? wpcw_social.icons[ key ].icon : key,
          iconLabel  = wpcw_social.icons[ key ].label,
          iconURL    = wpcw_social.icons[ key ].default,
          iconSelect = wpcw_social.icons[ key ].select,
          activeIconClass = ( $.inArray( iconClass, icons ) >= 0 ) ? 'active' : 'inactive';

      return (
        <a key={ key } href="#" onClick={ ( e ) => toggleSelectedIcons( e, iconClass ) } className={ activeIconClass } title={ iconLabel } data-key={ iconClass } data-value={ iconURL } data-select={ iconSelect } data-label={ iconLabel }>
          <i className={ wpcw_social.iconPrefix + " fa-" + iconClass }></i>
        </a>
      );

    } );
  }
}
