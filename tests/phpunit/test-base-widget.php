<?php

namespace WPCW;

final class TestBaseWidget extends TestCase {

	function setUp() {

		parent::setUp();

		$args = array(
			'wpcw_test',
			'TEST',
			array(),
		);

		$this->plugin = $this->getMockForAbstractClass( __NAMESPACE__ . '\Base_Widget', $args );

	}

	function test_form() {

		ob_start();

		$this->plugin->form( array() );

		ob_end_clean();

		$this->do_action_validation( 'admin_footer', array( $this->plugin, 'enqueue_scripts' ) );
		$this->do_action_validation( 'customize_controls_print_footer_scripts', array( $this->plugin, 'print_customizer_scripts' ) );

	}

	function enqueue_scripts() {

		$GLOBALS['is_IE'] = false;

		$wp_styles  = wp_styles();
		$wp_scripts = wp_scripts();

		$this->plugin->enqueue_scripts();

		$this->assertContains( 'font-awesome', $wp_styles->queue );
		$this->assertContains( 'wpcw-admin', $wp_styles->queue );
		$this->assertContains( 'wpcw-admin', $wp_scripts->queue );

		$this->assertNotContains( 'wpcw-admin-ie', $wp_scripts->queue );

		$GLOBALS['is_IE'] = true;

		$this->plugin->enqueue_scripts();

		$this->assertContains( 'wpcw-admin-ie', $wp_scripts->queue );

	}

}
