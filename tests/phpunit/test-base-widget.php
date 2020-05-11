<?php
/**
 * TestBaseWidget class.
 *
 * @package ContactWidgets
 */

namespace WPCW;

/**
 * Contact Widgets TestBaseWidget class.
 */
final class TestBaseWidget extends TestCase {
	/**
	 * TestBaseWidget class setUp function.
	 */
	public function setUp() {

		parent::setUp();

		$args = array(
			'wpcw_test',
			'TEST',
			array(),
		);

		$this->plugin = $this->getMockForAbstractClass( __NAMESPACE__ . '\Base_Widget', $args );

	}

	/**
	 * Function test_form validates actions.
	 */
	public function test_form() {

		ob_start();

		$this->plugin->form( array() );

		ob_end_clean();

		$this->do_action_validation( 'admin_footer', array( $this->plugin, 'enqueue_scripts' ) );
		$this->do_action_validation( 'customize_controls_print_footer_scripts', array( $this->plugin, 'print_customizer_scripts' ) );

	}

	/**
	 * Function enqueue_scripts tests for correct scripts and styles.
	 */
	public function enqueue_scripts() {

		$GLOBALS['is_IE'] = false; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- Used only in unit tests.

		$wp_styles  = wp_styles();
		$wp_scripts = wp_scripts();

		$this->plugin->enqueue_scripts();

		$this->assertContains( 'font-awesome', $wp_styles->queue );
		$this->assertContains( 'wpcw-admin', $wp_styles->queue );
		$this->assertContains( 'wpcw-admin', $wp_scripts->queue );

		$this->assertNotContains( 'wpcw-admin-ie', $wp_scripts->queue );

		$GLOBALS['is_IE'] = true; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- Used only in unit tests.

		$this->plugin->enqueue_scripts();

		$this->assertContains( 'wpcw-admin-ie', $wp_scripts->queue );

	}

}
