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

console.log( wpcw_social );

export default class AdminControlIconURLS extends Component {

  constructor() {
    super( ...arguments );
  }

  render() {
    const { attributes: { icons, iconURLS }, setAttributes  } = this.props;

    return Object.keys( wpcw_social.icons ).map( function( key ) {

	    var iconClass  = ( ! ( "icon" in wpcw_social.icons[key] ) ) ? key : wpcw_social.icons[key].icon,
	        iconLabel  = wpcw_social.icons[key].label,
	        iconURL    = wpcw_social.icons[key].default,
	        iconSelect = wpcw_social.icons[key].select;

      if ( $.inArray(iconClass, icons) < 0 ) {
        return;
      }

	    return (
        <p className={ iconClass }>
          <label for="social-networks">
            <span className={ wpcw_social.iconPrefix + " fa-" + iconClass }></span>
            <span className="text">{ ucfirst( iconClass )  }</span>
          </label>
          <span>
            <UrlInput
              className="url"
              value={ wpcw_social.icons[key].default }
              onChange={ url => setAttributes( { url } ) }
            />
            <span className="wpcw-widget-sortable-handle">
              <span className="dashicons dashicons-menu"></span>
            </span>
          </span>
        </p>
	    );
	  } );
	}
}
