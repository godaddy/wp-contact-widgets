import contactBlockIcons from './block-icons';
import BlockOrderControl from './block-order-control';

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

function renderFields( setAttributes, fields, displayLabels, values ) {

  console.log( values );

  var fieldMarkup = fields.map( function( field ) {

    switch( field.label ) {

      case 'Title':

        return <li key={ field.label }>
          <strong>{ field.label }</strong>
          <TextControl
            name={ field.label.toLowerCase() }
            placeholder={ field.label }
            value={ values.title }
            onChange={ title => setAttributes( { title } ) }
          />
        </li>;

      case 'Email':

        return <li key={ field.label }>
          <strong>{ field.label }</strong>
          <TextControl
            name={ field.label.toLowerCase() }
            placeholder={ field.label }
            value={ values.email }
            onChange={ email => setAttributes( { email } ) }
          />
        </li>;

      case 'Phone':

        return <li key={ field.label }>
          <strong>{ field.label }</strong>
          <TextControl
            name={ field.label.toLowerCase() }
            placeholder={ field.label }
            value={ values.phone }
            onChange={ phone => setAttributes( { phone } ) }
          />
        </li>;

      case 'Fax':

        return <li key={ field.label }>
          <strong>{ field.label }</strong>
          <TextControl
            name={ field.label.toLowerCase() }
            placeholder={ field.label }
            value={ values.fax }
            onChange={ fax => setAttributes( { fax } ) }
          />
        </li>;

      case 'Address':

        return <li key={ field.label }>
          <strong>{ field.label }</strong>
          <RichText
            name={ field.label.toLowerCase() }
            placeholder={ field.label }
            value={ values.address }
            onChange={ address => setAttributes( { address } ) }
          />
        </li>;

    }

  } );

  return <ul className="fields">{ fieldMarkup }</ul>;

}

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
    fields: {
      type: 'array',
      selector: '.fields',
      default: [
        {
          label: __( 'Title', 'contact-widgets' ),
        },
        {
          label: __( 'Email', 'contact-widgets' ),
        },
        {
          label: __( 'Phone', 'contact-widgets' ),
        },
        {
          label: __( 'Fax', 'contact-widgets' ),
        },
        {
          label: __( 'Address', 'contact-widgets' ),
        },
      ]
    },
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

    const { attributes: { textAlignment, blockAlignment, fields, title, email, phone, fax, address, displayLabels, displayMapOfAddress }, isSelected, className, setAttributes } = props;
    const toggleDisplayLabels = () => setAttributes( { displayLabels: ! displayLabels } );
    const toggleDisplayMapOfAddress = () => setAttributes( { displayMapOfAddress: ! displayMapOfAddress } );
    const showTitle = ( typeof title !== 'undefined' && title.length > 0 ) ? true : false;
    const showEmail = ( typeof email !== 'undefined' && email.length > 0 ) ? true : false;
    const showPhone = ( typeof phone !== 'undefined' && phone.length > 0 ) ? true : false;
    const showFax = ( typeof fax !== 'undefined' && fax.length > 0 ) ? true : false;
    const showAddress = ( typeof address !== 'undefined' && address.length > 0 ) ? true : false;
    var mapAddress = showAddress ? encodeURIComponent( address.join( ' ' ).replace( ' [object Object]', '' ).trim() ) : '';

    // console.log( fields );

    return [

      // Inspector Controls
      <InspectorControls key="contact-block-inspector-controls">
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
          <PanelRow>
            <BlockOrderControl { ...{ setAttributes, ...props } } />
          </PanelRow>
        </PanelBody>
      </InspectorControls>,

      // Custom Toolbar
      <BlockControls key="contact-block-controls">
        <AlignmentToolbar
          value={ textAlignment }
          onChange={ ( textAlignment ) => props.setAttributes( { textAlignment } ) }
        />
        <Toolbar>
          <Tooltip text={ __( 'Display Labels', 'contact-widgets' )  }>
            <Button onClick={ toggleDisplayLabels }>
              {contactBlockIcons.label}
            </Button>
          </Tooltip>
        </Toolbar>
        <Toolbar>
          <Tooltip text={ __( 'Display Map of Address', 'contact-widgets' )  }>
            <Button onClick={ toggleDisplayMapOfAddress }>
              {contactBlockIcons.map}
            </Button>
          </Tooltip>
        </Toolbar>
      </BlockControls>,

      // Admin Block Markup
      <div
        className={ className }
        key={ className }
      >
        <ul
          className="fields"
          key="contact-widgets-content"
        >
          { renderFields( props.setAttributes, fields, displayLabels, { 'title': title, 'email': email, 'phone': phone, 'fax': fax, 'address': address } ) }
        </ul>
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
