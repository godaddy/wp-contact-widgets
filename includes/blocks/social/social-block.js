import icons from './icons';

/**
 * Internal block libraries
 */
const { __ } = wp.i18n;

const {
    registerBlockType,
    RichText,
    AlignmentToolbar,
    BlockControls,
    BlockAlignmentToolbar,
    InspectorControls,
} = wp.blocks;

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
 * Render the social media icons inspector controls
 */
function renderIcons() {
  if ( ! Object.keys( wpcw_social.icons ).length ) {
    return <h2>{ __( 'No Icons Found.', 'contact-widgets' ) }</h2>;
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
export default registerBlockType(
  'contact-widgets/social-block',
  {
    title: __( 'Social Profiles', 'contact-widgets' ),
    description: __( 'Display contact details on your site.', 'contact-widgets' ),
    category: 'common',
    icon: 'email-alt',
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
    },
    edit: props => {
      const { attributes: { title, icons }, isSelected, className, setAttributes } = props;
      const showTitle = ( typeof title !== 'undefined' && title.length > 0 ) ? true : false;
      return [
        // Inspector Controls
        isSelected && (
          <InspectorControls>
            <PanelBody
              title={ __( 'Social Icons', 'contact-widgets' ) }
            >
              <div className="icons">
                { renderIcons() }
              </div>
            </PanelBody>
          </InspectorControls>
        ),
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
            ) : ( <h2>Hey</h2> ) }
          </div>
        </div>
      ];
    },
    save: props => {
      const { attributes: { title } } = props;
      const showTitle = ( typeof title !== 'undefined' && title.length > 0 ) ? true : false;
      return (
        <div>
          { showTitle && (
            <h2 class="social-title">
              { title }<br />
            </h2>
          ) }
          <ul class="social-icons">
            <li>test</li>
          </ul>
        </div>
      );
    },
  },
);
