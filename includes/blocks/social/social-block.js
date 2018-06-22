import socialIcons from './icons';
import { AdminControlIcons } from './icon-control';

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

/**
 * Render the front end social media icons
 */
function renderFrontEndIcons() {
  if ( ! Object.keys( wpcw_social.icons ).length ) {
    return;
  }
  return Object.keys( wpcw_social.icons ).map( function( key ) {
    var iconClass  = ( ! ( "icon" in wpcw_social.icons[key] ) ) ? key : wpcw_social.icons[key].icon,
    iconLabel  = wpcw_social.icons[key].label,
    iconURL    = wpcw_social.icons[key].default,
    iconSelect = wpcw_social.icons[key].select;
    return <a href="#" class="inactive" title={ iconLabel } data-key={ iconClass } data-value={ iconURL } data-select={ iconSelect } data-label={ iconLabel }>
      <i class={ wpcw_social.iconPrefix + " fa-" + iconClass }></i>
    </a>;
  } );
}

/**
 * Register block
 */
export default registerBlockType( 'contact-widgets/social-block', {
  title: __( 'Social Profiles', 'contact-widgets' ),
  description: __( 'Display contact details on your site.', 'contact-widgets' ),
  category: 'common',
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
      source: 'child',
      selector: '.social-icons',
    },
    displayLabels: {
      type: 'boolean',
      default: true,
    },
  },

  edit: props => {

    const { attributes: { title, icons, displayLabels }, isSelected, className, setAttributes } = props;
    const toggleDisplayLabels = () => setAttributes( { displayLabels: ! displayLabels } );
    const toggleSelectedIcons = (e) => {
      $(e.target).closest('a').toggleClass('inactive');
      setAttributes( { icons: true } );
    };
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
              <AdminControlIcons
                toggleSelectedIcons={toggleSelectedIcons}
              />
            </div>
          </PanelRow>
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
            />
          ) : ( showTitle && ( <h2>{ title }</h2> ) ) }
          { isSelected ? (
            ( <i className="fa fa-user-circle-o" ariaHidden="true"></i> )
          ) : ( renderFrontEndIcons() ) }
        </div>
      </div>
    ];
  },

  save: props => {
    const { attributes: { title } } = props;
    const showTitle = ( typeof title !== 'undefined' && title.length > 0 ) ? true : false;
    const icons = ( typeof icons !== 'undefined' && icons.length > 0 ) ? true : false;
    return (
      <div>
        { showTitle && (
          <h2 class="social-title">
            { title }<br />
          </h2>
        ) }
        { icons && (
          <ul class="social-icons">
            <li>{ renderFrontEndIcons() }</li>
          </ul>
        ) }
      </div>
    );
  },
} );
