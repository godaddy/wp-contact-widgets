<?php

namespace WPCW;

final class TestPluginLoader extends TestCase {

	public function setUp() {

		parent::setUp();

		$this->plugin = new \Contact_Widgets();

	}

	/**
	 * Test that all required actions and filters are added as expected
	 */
	function test_construct() {

		$this->do_action_validation( 'plugins_loaded', array( $this->plugin, 'i18n' ) );

		$this->do_action_validation( 'plugins_loaded', array( __NAMESPACE__ . '\Plugin', 'init' ) );

	}

	function test_construct_invalid_php_version() {

		$this->plugin = new \Contact_Widgets( '5.3' );

		$this->do_action_validation( 'shutdown', array( $this->plugin, 'notice' ) );

	}

	/**
	 * Test subset of output of php notice
	 */
	function test_notice_output_wrong_php_version() {

		$this->expectOutputRegex( '/class="error"/' );

		$this->plugin->notice();

	}

}

