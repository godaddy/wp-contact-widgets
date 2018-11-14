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

function renderFields( setAttributes, isSelected, fields, displayLabels, displayMapOfAddress, values ) {

  var address = values.address;
  const showAddress = ( typeof address !== 'undefined' && address.length > 0 ) ? true : false;
  const mapAddress = showAddress ? encodeURIComponent( address.join( ' ' ).replace( ' [object Object]', '' ).trim() ) : '';

  var fieldMarkup = fields.map( function( field ) {

    switch( field.label ) {

      case 'Title':

        return <li key={ field.label }>
          { isSelected && (
            <div>
              <strong>{ field.label }</strong>
              <TextControl
                name={ field.label.toLowerCase() }
                placeholder={ field.label }
                value={ values.title }
                onChange={ title => setAttributes( { title } ) }
              />
            </div>
          ) }
          { ! isSelected && (
            <h2>{ values.title }</h2>
          ) }
        </li>;

      case 'Email':

        return <li key={ field.label }>
          { isSelected ? (
            <div>
              <strong>{ field.label }</strong>
              <TextControl
                name={ field.label.toLowerCase() }
                placeholder={ field.label }
                value={ values.email }
                onChange={ email => setAttributes( { email } ) }
              />
            </div>
          ) :
          (
            <div>
              { displayLabels && values.email && (
                <strong>
                  { field.label }
                </strong>
              ) }
              <div>{ values.email }</div>
            </div>
          )
        }
        </li>;

      case 'Phone':

        return <li key={ field.label }>
          { isSelected ? (
            <div>
              <strong>{ field.label }</strong>
              <TextControl
                name={ field.label.toLowerCase() }
                placeholder={ field.label }
                value={ values.phone }
                onChange={ phone => setAttributes( { phone } ) }
              />
            </div>
          ) : (
            <div>
              { displayLabels && values.phone && (
                <strong>
                  { field.label }
                </strong>
              ) }
              <div>{ values.phone }</div>
            </div>
          ) }
        </li>;

      case 'Fax':

        return <li key={ field.label }>
          { isSelected ? (
            <div>
              <strong>{ field.label }</strong>
              <TextControl
                name={ field.label.toLowerCase() }
                placeholder={ field.label }
                value={ values.fax }
                onChange={ fax => setAttributes( { fax } ) }
              />
            </div>
          ) : (
            <div>
              { displayLabels && values.fax && (
                <strong>
                  { field.label }
                </strong>
              ) }
              <div>{ values.fax }</div>
            </div>
          ) }
        </li>;

      case 'Address':

        return <li key={ field.label }>
          { isSelected ? (
            <div>
              <strong>{ field.label }</strong>
              <RichText
                name={ field.label.toLowerCase() }
                placeholder={ field.label }
                value={ values.address }
                onChange={ address => setAttributes( { address } ) }
              />
            </div>
          ) : (
            <div>
              { displayLabels && values.address && (
                <strong>
                  { field.label }
                </strong>
              ) }
              <div>{ values.address }</div>
              { displayMapOfAddress && showAddress && (
                <div className="has-map">
                  <iframe
                    src={ "https://www.google.com/maps?q=" + mapAddress + "&output=embed&hl=%s&z=14" }
                  />
                </div>
              ) }
            </div>
          ) }
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
          { renderFields( props.setAttributes, isSelected, fields, displayLabels, displayMapOfAddress, { 'title': title, 'email': email, 'phone': phone, 'fax': fax, 'address': address } ) }
        </ul>
      </div>
    ];
  },

  save: props => {

    const { attributes: { textAlignment, blockAlignment, fields, title, email, phone, fax, address, displayLabels, displayMapOfAddress } } = props;
    const labelClass = displayLabels ? 'has-label' : 'no-label';
    const mapClass = displayMapOfAddress ? 'has-map' : labelClass;

    return (
      <div>
        <ul>
          { renderFields( props.setAttributes, false, fields, displayLabels, displayMapOfAddress, { 'title': title, 'email': email, 'phone': phone, 'fax': fax, 'address': address } ) }
        </ul>
      </div>
    );
  },
} );
