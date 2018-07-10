import socialIcons from './icons';
import AdminControlIcons from './icon-control';
import AdminControlIconURLS from './icon-url-control';

/**
 * Internal block libraries
 */
const { __ } = wp.i18n;

const {
  registerBlockType,
  RichText,
  AlignmentToolbar,
  BlockAlignmentToolbar
} = wp.blocks;

const {
  BlockControls,
  InspectorControls
} = wp.editor;

const {
  Toolbar,
  Button,
  Tooltip,
  PanelBody,
  PanelRow,
  FormToggle,
  TextControl
} = wp.components;

function renderFrontEndIcons( icons ) {
  var iconMarkup = Object.keys( wpcw_social.icons ).map( function( key ) {
    var iconClass  = ( ! ( "icon" in wpcw_social.icons[key] ) ) ? key : wpcw_social.icons[key].icon;
    if ( ( $.inArray(iconClass, icons) < 0 ) ) {
      return;
    }
    var iconLabel  = wpcw_social.icons[key].label,
    iconURL    = wpcw_social.icons[key].default,
    iconSelect = wpcw_social.icons[key].select,
    activeIconClass = ( $.inArray(iconClass, icons) >= 0 ) ? 'active' : 'inactive';
    return <li className="no-label"><a href="#" className={ activeIconClass } title={ iconLabel } dataKey={ iconClass } dataValue={ iconURL } dataSelect={ iconSelect } dataLabel={ iconLabel }>
      <i className={ wpcw_social.iconPrefix + " fa-" + iconClass }></i>
    </a></li>;
  } );
  if ( ! iconMarkup.filter( function(n){ return n != undefined } ).length ) {
    return;
  }
  return <ul className="social-icons">{ iconMarkup }</ul>;
}

/**
 * Register block
 */
export default registerBlockType( 'contact-widgets/social-block', {
  title: __( 'Social Profiles', 'contact-widgets' ),
  description: __( 'Display contact details on your site.', 'contact-widgets' ),
  category: 'widgets',
  icon: socialIcons.social,
  keywords: [
    __( 'Social', 'contact-widgets' ),
    __( 'Icons', 'contact-widgets' ),
    __( 'Media', 'contact-widgets' ),
  ],

  attributes: {
    title: {
      type: 'string',
      source: 'text',
      selector: '.social-title',
    },
    icons: {
      type: 'array',
      selector: '.social-icons',
      default: [],
    },
    icons: {
      type: 'array',
      selector: '.social-icon-urls',
      default: [],
    },
    displayLabels: {
      type: 'boolean',
      default: true,
    },
  },

  edit: props => {

    const { attributes: { title, icons, iconURLS, displayLabels }, isSelected, className, setAttributes } = props;
    const toggleDisplayLabels = () => setAttributes( { displayLabels: ! displayLabels } );
    const showTitle = ( typeof title !== 'undefined' && title.length > 0 ) ? true : false;

    return [

      // Inspector Controls
      <InspectorControls>
        <PanelBody
          title={ __( 'Social Icons', 'contact-widgets' ) }
        >
          <PanelRow>
            <label htmlFor="display-labels-toggle" >
              { __( 'Display Labels', 'contact-widgets' ) }
            </label>
            <FormToggle
              id="display-labels-toggle"
              label={ __( 'Display Labels', 'contact-widgets' ) }
              checked={ displayLabels }
              onChange={ toggleDisplayLabels }
            />
          </PanelRow>
          <PanelRow>
            <label htmlFor="social-networks" >
              { __( 'Social Networks', 'contact-widgets' ) }
            </label>
            <div className="icons">
              <AdminControlIcons { ...{ setAttributes, ...props } } />
            </div>
          </PanelRow>
          <div className="social-icon-urls">
            <AdminControlIconURLS { ...{ setAttributes, ...props } } />
            <div className="default-fields">
              <p className="">
                <label for="">
                  <span className="fa fa-"></span>
                  <span className="text"></span>
                </label>
                <span>
                  <input className="widefat" id="" name="" type="text" value="" placeholder="" autocomplete="off" />
                  <span className="wpcw-widget-sortable-handle">
                    <span className="dashicons dashicons-menu"></span>
                  </span>
                </span>
              </p>
            </div>
          </div>
        </PanelBody>
      </InspectorControls>,

      // Custom Toolbar
      <BlockControls>
        <Toolbar>
          <Tooltip text={ __( 'Display Labels', 'contact-widgets' )  }>
            <Button onClick={toggleDisplayLabels}>
              {socialIcons.label}
            </Button>
          </Tooltip>
        </Toolbar>
      </BlockControls>,

      // Admin Block Markup
      <div className={ className }>
        <div className="contact-widgets-social-icons">
          { isSelected ? (
            <TextControl
              tagName="h2"
              placeholder={ __( 'Social Icons Title', 'contact-widgets' ) }
              value={ title }
              onChange={ title => setAttributes( { title } ) }
              className="social-title"
            />
          ) : ( showTitle && ( <h2>{ title }</h2> ) ) }
          { renderFrontEndIcons( icons ) }
        </div>
      </div>
    ];
  },

  save: props => {
    const { attributes: { title, icons, displayLabels }, className } = props;
    const showTitle = ( typeof title !== 'undefined' && title.length > 0 ) ? true : false;

    return (
      <div className={ className }>
        { showTitle && (
          <h2 className="social-title">
            { title }<br />
          </h2>
        ) }
        { renderFrontEndIcons( icons ) }
      </div>
    );
  },
} );
