<?php
/**
 * TestPlugin class.
 *
 * @package ContactWidgets
 */

namespace WPCW;

/**
 * Contact Widgets TestPlugin class.
 * Tests actions and filters. Tests for availability of local classes.
 */
final class TestPlugin extends TestCase {
	/**
	 * TestPlugin class setUp function.
	 */
	public function setUp() {

		parent::setUp();

		$this->plugin = new Plugin();

	}

	/**
	 * Test that all required actions and filters are added as expected.
	 */
	public function test_init() {

		Plugin::init();

		$this->do_action_validation( 'widgets_init', array( __NAMESPACE__ . '\Plugin', 'register_widgets' ) );

	}

	/**
	 * Test for register_widget function.
	 */
	public function test_register_widget() {

		global $wp_widget_factory;

		// Check contact widget presence.
		$this->assertTrue(
			class_exists( 'WPCW\Contact' ),
			'Class WPCW\Contact is not found'
		);

		$this->assertTrue( isset( $wp_widget_factory->widgets['WPCW\Contact'] ) );

		// Check social widget class presence.
		$this->assertTrue(
			class_exists( 'WPCW\Social' ),
			'Class WPCW\Social is not found'
		);

		$this->assertTrue( isset( $wp_widget_factory->widgets['WPCW\Social'] ) );

	}

}

