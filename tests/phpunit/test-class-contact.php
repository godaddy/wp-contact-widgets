<?php
/**
 * TestContact class.
 *
 * @package ContactWidgets
 */

namespace WPCW;

/**
 * Contact Widgets TestContact class.
 */
final class TestContact extends TestCase {
	/**
	 * TestContact class setUp function.
	 */
	public function setUp() {

		global $_wp_sidebars_widgets;

		parent::setUp();

		$this->plugin = new Contact();

		if ( ! isset( $_wp_sidebars_widgets['sidebar-1']['wpcw_contact'] ) ) {

			array_push( $_wp_sidebars_widgets['sidebar-1'], 'wpcw_contact-1' );

		}

	}

	/**
	 * Function test_construct.
	 */
	public function test_construct() {

		$this->assertEquals( $this->plugin->widget_options['classname'], 'wpcw-widgets wpcw-widget-contact' );

		$this->assertEquals( $this->plugin->id_base, 'wpcw_contact' );

	}

	/**
	 * Function test_form.
	 */
	public function test_form() {

		$this->expectOutputRegex( '/class="wpcw-widget wpcw-widget-contact"/' );
		$this->expectOutputRegex( '/class="customizer_update"/' );

		$this->plugin->form( array() );

	}

	/**
	 * Function test_widget.
	 */
	public function test_widget() {

		$instance = array(
			'title'   => 'test',
			'labels'  => array(
				'value' => 'yes',
			),
			'map'     => array(
				'value' => 'yes',
			),
			'address' => array(
				'value' => '<br>123 Santa Monica<br>',
			),
		);
		$args     = array(
			'before_widget' => '<div class="widget wpcw-widget-contact"><div class="widget-content">',
			'after_widget'  => '</div><div class="clear"></div></div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		);

		$this->expectOutputRegex( '/<div class="widget wpcw-widget-contact"><div class="widget-content">/' );
		$this->expectOutputRegex( '/<h3 class="widget-title">/' );

		// Check that we sprint the right google url.
		$this->expectOutputRegex( '~//www\.google\.com\/maps\?q=123%20Santa%20Monica&output=embed~' );

		$this->plugin->widget( $args, $instance );

		// Tests that script & styles are enqueued enqueued.
		do_action( 'wp_enqueue_scripts' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Used only in unit tests.

		$wp_styles = wp_styles();

		$this->assertContains( 'wpcw', $wp_styles->queue );

	}

}
