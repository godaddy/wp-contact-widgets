const { Component } = wp.element;

export const AdminControlIcons = props => {

  const { icons = [], toggleSelectedIcons = () => {} } = props;

  if ( ! Object.keys( wpcw_social.icons ).length ) {
    return <h2>{ __( 'No Icons Found.', 'contact-widgets' ) }</h2>;
  }

  return Object.keys( wpcw_social.icons ).map( function( key ) {

    var iconClass  = ( ! ( "icon" in wpcw_social.icons[key] ) ) ? key : wpcw_social.icons[key].icon,
        iconLabel  = wpcw_social.icons[key].label,
        iconURL    = wpcw_social.icons[key].default,
        iconSelect = wpcw_social.icons[key].select;

    return (
      <a href="#" onClick={toggleSelectedIcons} class="inactive" title={ iconLabel } data-key={ iconClass } data-value={ iconURL } data-select={ iconSelect } data-label={ iconLabel }>
        <i class={ wpcw_social.iconPrefix + " fa-" + iconClass }></i>
      </a>
    );

  } );

}
