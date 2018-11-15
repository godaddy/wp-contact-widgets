const { Component } = wp.element;

const {
  URLInput,
} = wp.editor;

export default class AdminControlIconURLS extends Component {

  constructor() {
    super( ...arguments );
  }

  componentDidMount() {

    var $socialIcons = $( '.social-icon-urls' ),
        props         = this.props;

    $socialIcons.sortable( {
      items : '> *:not(.not-sortable)',
      handle: '.wpcw-social-icons-sortable-handle',
      containment: 'parent',
      placeholder: 'sortable-placeholder',
      axis: 'y',
      tolerance: 'pointer',
      forcePlaceholderSize: true,
      scroll: false,
      start: function( e, ui ) {
        ui.placeholder.height( ui.item.height() );
      },
      stop: function( e, ui ) {
        var icons = [];
        $( '.social-icon-urls' ).children().not( '.default-fields' ).each( function() {
          icons.push( $( this ).attr( 'class' ) );
        } );
        props.setAttributes( { icons: icons } );
        $socialIcons.sortable( 'cancel' );
      }
    } );

  }

  render() {
    const { attributes: { icons, iconURLS }, setAttributes, getIconData  } = this.props;
    const updateIconURLS = () => {
      var newURLS = {};
      $( '.holder input[type="text"]' ).each( function() {
        newURLS[ $( this ).closest( 'span' ).data( 'icon' ) ] = $( this ).val();
      } );
      setAttributes( { iconURLS: newURLS } );
    };

    return icons.map( function( icon, i ) {

      var iconData = getIconData( icon );

      if ( ! iconData ) {

        return;

      }

      var iconLabel  = iconData['label'],
          iconURL    = ( icon in iconURLS ) ? iconURLS[ icon ] : iconData['default'],
          iconSelect = iconData['select'];

      return (
        <section key={ i } className={ icon }>
          <label htmlFor="social-networks">
            <span className={ wpcw_social.iconPrefix + " fa-" + icon }></span>
            <span className="text">{ iconLabel }</span>
          </label>
          <span className="holder" data-icon={ icon }>
            <URLInput
              value={ iconURL }
              onChange={ updateIconURLS }
            />
            <span className="wpcw-social-icons-sortable-handle">
              <span className="dashicons dashicons-menu"></span>
            </span>
          </span>
        </section>
      );

    } );

  }
}
