import socialBlockIcons from './block-icons';
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

function renderIcons( icons, iconURLS, displayLabels ) {

  var iconMarkup = icons.map( function( icon ) {

    var iconData = getIconData( icon );

    if ( ! iconData ) {

      return;

    }

    var iconLabel     = displayLabels ? '\u00A0' + iconData['label'] : undefined,
        iconURL       = ( icon in iconURLS ) ? iconURLS[ icon ] : iconData['default'],
        labelClass    = displayLabels ? 'has-label' : 'no-label';

    return <li key={ icon } className={ labelClass }>
      <a href={ iconURL } title={ iconLabel }>
        <i className={ wpcw_social.iconPrefix + " fa-2x fa-" + icon }></i>
        { iconLabel }
      </a>
    </li>;

  } );

  return <ul className="social-icons">{ iconMarkup }</ul>;

}

function getIconData( iconName ) {

  if ( iconName in wpcw_social.icons ) {

    return wpcw_social.icons[ iconName ];

  }

  var iconData = false;

  Object.keys( wpcw_social.icons ).map( function( key ) {


    if ( ! "icon" in wpcw_social.icons[ key ] || iconName !== wpcw_social.icons[ key ].icon ) {

      return;

    }

    iconData = wpcw_social.icons[ key ];

  } );

  return iconData;

}

/**
 * Register block
 */
export default registerBlockType( 'contact-widgets/social-block', {
  title: __( 'Social Profiles', 'contact-widgets' ),
  description: __( 'Display contact details on your site.', 'contact-widgets' ),
  category: 'widgets',
  icon: socialBlockIcons.social,
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
    iconURLS: {
      type: 'object',
      selector: '.social-icon-urls',
      default: [],
    },
    displayLabels: {
      type: 'boolean',
      default: false,
    },
  },

  edit: props => {

    const { attributes: { title, icons, iconURLS, displayLabels }, isSelected, className, setAttributes } = props;
    const toggleDisplayLabels = () => setAttributes( { displayLabels: ! displayLabels } );
    var displayTitle = ( 'undefined' !== typeof title && title.length > 0 );

    return [

      // Inspector Controls
      <InspectorControls key="social-icons-inspector-controls">
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
          <PanelRow
            className="social-icons"
          >
            <label htmlFor="social-networks" >
              { __( 'Social Networks', 'contact-widgets' ) }
            </label>
            <div className="icons">
              <AdminControlIcons { ...{ setAttributes, ...props } } />
            </div>
          </PanelRow>
          <div className="social-icon-urls" key="social-icon-urls">
            <AdminControlIconURLS { ...{ setAttributes, getIconData, ...props } } />
            <div className="default-fields">
              <p className="">
                <label htmlFor="">
                  <span className="fa fa-"></span>
                  <span className="text"></span>
                </label>
                <span className="holder">
                  <input className="widefat" id="" name="" type="text" value="" placeholder="" autoComplete="off" />
                  <span className="wpcw-social-icons-sortable-handle">
                    <span className="dashicons dashicons-menu"></span>
                  </span>
                </span>
              </p>
            </div>
          </div>
        </PanelBody>
      </InspectorControls>,

      // Custom Toolbar
      <BlockControls key="social-icons-controls">
        <Toolbar>
          <Tooltip text={ __( 'Display Labels', 'contact-widgets' )  }>
            <Button onClick={toggleDisplayLabels}>
              {socialBlockIcons.label}
            </Button>
          </Tooltip>
        </Toolbar>
      </BlockControls>,

      // Admin Block Markup
      <div className={ className } key={ className }>
        <div className="contact-widgets-social-icons">
          { isSelected ? (
            <TextControl
              placeholder={ __( 'Social Icons Title', 'contact-widgets' ) }
              value={ title }
              onChange={ title => setAttributes( { title } ) }
              className="social-title"
            />
          ) : ( displayTitle && ( <h2>{ title }</h2> ) ) }
          { renderIcons( icons, iconURLS, displayLabels ) }
        </div>
      </div>

    ];
  },

  save: props => {
    const { attributes: { title, icons, iconURLS, displayLabels }, className } = props;
    var displayTitle = ( 'undefined' !== typeof title && title.length > 0 );

    return (
      <div className={ className }>
        { displayTitle && (
          <h2 className="social-title">
            { title }<br />
          </h2>
        ) }
        { renderIcons( icons, iconURLS, displayLabels ) }
      </div>
    );
  },
} );

$( document ).on( 'click', '.social-icons a', function( event ) {
  event.preventDefault();
} );
