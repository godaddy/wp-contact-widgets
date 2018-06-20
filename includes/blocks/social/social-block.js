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
    },
    edit: props => {
      const { attributes: { textAlignment, blockAlignment, title, email, phone, fax, address, displayLabels, displayMapOfAddress }, isSelected, className, setAttributes } = props;
      const showTitle = ( typeof title !== 'undefined' && title.length > 0 ) ? true : false;
      return [
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
        </div>
      );
    },
  },
);
