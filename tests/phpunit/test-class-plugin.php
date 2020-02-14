<?php

namespace WPCW;

final class TestPlugin extends TestCase {

	function setUp() {

		parent::setUp();

		$this->plugin = new Plugin();

	}

	/**
	 * Test that all required actions and filters are added as expected
	 */
	function test_init() {

		Plugin::init();

		$this->do_action_validation( 'widgets_init', [ __NAMESPACE__ . '\Plugin', 'register_widgets' ] );

	}

	/**
	 * Test for register_widget function
	 */
	function test_register_widget() {

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

