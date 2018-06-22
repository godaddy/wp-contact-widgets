import icons from './icons';

/**
 * Internal block libraries
 */
const { __ } = wp.i18n;

const {
    registerBlockType
} = wp.blocks;

const {
  BlockControls,
  InspectorControls,
  AlignmentToolbar,
  RichText
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
 * Register block
 */
export default registerBlockType( 'contact-widgets/contact-block', {
  title: __( 'Contact Details', 'contact-widgets' ),
  description: __( 'Display contact details on your site.', 'contact-widgets' ),
  category: 'widgets',
  icon: 'email-alt',
  keywords: [
    __( 'Email', 'contact-widgets' ),
    __( 'Phone', 'contact-widgets' ),
    __( 'Map', 'contact-widgets' ),
  ],
  attributes: {
    title: {
      type: 'string',
      source: 'text',
      selector: '.contact-title',
    },
    email: {
      type: 'string',
      source: 'text',
      selector: '.contact-email',
    },
    phone: {
      type: 'string',
      source: 'text',
      selector: '.contact-phone',
    },
    fax: {
      type: 'string',
      source: 'text',
      selector: '.contact-fax',
    },
    address: {
      type: 'array',
      source: 'children',
      selector: '.contact-address',
    },
    displayLabels: {
      type: 'boolean',
      default: true,
    },
    displayMapOfAddress: {
      type: 'boolean',
      default: true,
    },
  },
  getEditWrapperProps( attributes ) {
    const { blockAlignment } = attributes;
    if ( 'left' === blockAlignment || 'right' === blockAlignment || 'full' === blockAlignment ) {
      return { 'data-align': blockAlignment };
    }
  },
  edit: props => {

    const { attributes: { textAlignment, blockAlignment, title, email, phone, fax, address, displayLabels, displayMapOfAddress }, isSelected, className, setAttributes } = props;
    const toggleDisplayLabels = () => setAttributes( { displayLabels: ! displayLabels } );
    const toggleDisplayMapOfAddress = () => setAttributes( { displayMapOfAddress: ! displayMapOfAddress } );
    const showTitle = ( typeof title !== 'undefined' && title.length > 0 ) ? true : false;
    const showEmail = ( typeof email !== 'undefined' && email.length > 0 ) ? true : false;
    const showPhone = ( typeof phone !== 'undefined' && phone.length > 0 ) ? true : false;
    const showFax = ( typeof fax !== 'undefined' && fax.length > 0 ) ? true : false;
    const showAddress = ( typeof address !== 'undefined' && address.length > 0 ) ? true : false;
    var mapAddress = showAddress ? encodeURIComponent( address.join( ' ' ).replace( ' [object Object]', '' ).trim() ) : '';

    return [

      // Inspector Controls
      <InspectorControls>
        <PanelBody
          title={ __( 'Contact Details Controls', 'contact-widgets' ) }
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
            <label htmlFor="display-map-of-address-toggle">
              { __( 'Display Map of Address', 'contact-widgets' ) }
            </label>
            <FormToggle
              id="display-map-of-address-toggle"
              label={ __( 'Display Map of Address', 'contact-widgets' ) }
              checked={ displayMapOfAddress }
              onChange={ toggleDisplayMapOfAddress }
            />
          </PanelRow>
        </PanelBody>
      </InspectorControls>,

      // Custom Toolbar
      <BlockControls>
        <AlignmentToolbar
          value={ textAlignment }
          onChange={ ( textAlignment ) => props.setAttributes( { textAlignment } ) }
        />
        <Toolbar>
          <Tooltip text={ __( 'Display Labels', 'contact-widgets' )  }>
            <Button onClick={ toggleDisplayLabels }>
              {icons.label}
            </Button>
          </Tooltip>
        </Toolbar>
        <Toolbar>
          <Tooltip text={ __( 'Display Map of Address', 'contact-widgets' )  }>
            <Button onClick={ toggleDisplayMapOfAddress }>
              {icons.map}
            </Button>
          </Tooltip>
        </Toolbar>
      </BlockControls>,

      // Admin Block Markup
      <div className={ className }>
        <div className="contact-widgets-content">
          { isSelected ? (
            <TextControl
              tagName="h2"
              placeholder={ __( 'Contact Details Title', 'contact-widgets' ) }
              value={ title }
              onChange={ title => setAttributes( { title } ) }
            />
          ) : ( showTitle && ( <h2>{ title }</h2> ) ) }
          { displayLabels && (
            <strong>{ __( 'Email', 'contact-widgets' ) }<br /></strong>
          ) }
          { isSelected ? (
            <TextControl
              placeholder={ __( 'Email Address', 'contact-widgets' ) }
              value={ email }
              onChange={ email => setAttributes( { email } ) }
            />
          ) : ( showEmail && ( <div>{ email }</div> ) ) }
          { displayLabels && (
            <strong>{ __( 'Phone', 'contact-widgets' ) }<br /></strong>
          ) }
          { isSelected ? (
            <TextControl
              placeholder={ __( 'Phone Number', 'contact-widgets' ) }
              value={ phone }
              onChange={ phone => setAttributes( { phone } ) }
            />
          ) : ( showPhone && ( <div>{ phone }</div> ) ) }
          { displayLabels && (
            <strong>{ __( 'Fax', 'contact-widgets' ) }<br /></strong>
          ) }
          { isSelected ? (
            <TextControl
              placeholder={ __( 'Fax Number', 'contact-widgets' ) }
              value={ fax }
              onChange={ fax => setAttributes( { fax } ) }
            />
          ) : ( showFax && ( <div>{ fax }</div> ) ) }
          { displayLabels && (
            <strong>{ __( 'Address', 'contact-widgets' ) }<br /></strong>
          ) }
          { isSelected ? (
            <RichText
              placeholder={ __( 'Address', 'contact-widgets' ) }
              onChange={ address => setAttributes( { address } ) }
              value={ address }
            />
          ) : ( showAddress && ( <div>{ address }</div> ) ) }
          { ! isSelected && showAddress && displayMapOfAddress && (
             <iframe
               src={ "https://www.google.com/maps?q=" + mapAddress + "&output=embed&hl=%s&z=14" }
             />
          ) }
        </div>
      </div>
    ];
  },

  save: props => {

    const { attributes: { textAlignment, displayLabels, displayMapOfAddress, title, email, phone, fax, address } } = props;
    const labelClass = displayLabels ? 'has-label' : 'no-label';
    const mapClass = displayMapOfAddress ? 'has-map' : labelClass;
    const showTitle = ( typeof title !== 'undefined' && title.length > 0 ) ? true : false;
    const showEmail = ( typeof email !== 'undefined' && email.length > 0 ) ? true : false;
    const showPhone = ( typeof phone !== 'undefined' && phone.length > 0 ) ? true : false;
    const showFax = ( typeof fax !== 'undefined' && fax.length > 0 ) ? true : false;
    const showAddress = ( typeof address !== 'undefined' && address.length > 0 ) ? true : false;
    const mapAddress = showAddress ? encodeURIComponent( address.join( ' ' ).replace( ' [object Object]', '' ).trim() ) : '';

    return (
      <div>
        { showTitle && (
          <h2 class="contact-title">
            { title }<br />
          </h2>
        ) }
        <ul>
          { showEmail && (
            <li className={ labelClass }>
              { displayLabels && (
                <strong>{ __( 'Email', 'contact-widgets' ) }<br /></strong>
              ) }
              <div className="contact-email" >
                { email }
              </div>
            </li>
          ) }
          { showPhone && (
            <li className={ labelClass }>
              { displayLabels && (
                <strong>{ __( 'Phone', 'contact-widgets' ) }<br /></strong>
              ) }
              <div className="contact-phone">
                { phone }
              </div>
            </li>
          ) }
          { showFax && (
            <li className={ labelClass }>
              { displayLabels && (
                <strong>{ __( 'Fax', 'contact-widgets' ) }<br /></strong>
              ) }
              <div className="contact-fax">
                { fax }
              </div>
            </li>
          ) }
          { showAddress && (
            <li className={ labelClass }>
              { displayLabels && (
                <strong>{ __( 'Address', 'contact-widgets' ) }<br /></strong>
              ) }
              <div className="contact-address">
                { address }
              </div>
            </li>
          ) }
          { displayMapOfAddress && (
            <li className="has-map">
              <iframe
                src={ "https://www.google.com/maps?q=" + mapAddress + "&output=embed&hl=%s&z=14" }
              />
            </li>
          ) }
        </ul>
      </div>
    );
  },
} );
