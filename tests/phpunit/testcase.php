<?php
/**
 * TestPlugin class.
 *
 * @package ContactWidgets
 */

namespace WPCW;

/**
 * Contact Widgets TestCase class.
 * Provides helper functions to test classes.
 */
class TestCase extends \WP_UnitTestCase {
	/**
	 * Protected variable $plugin.
	 *
	 * @var object Holds the plugin instance.
	 */
	protected $plugin;

	/**
	 * Helper function to check validity of action.
	 *
	 * @param string       $action String representing an action function.
	 * @param array|string $callback String or Array representing callback function.
	 * @param string       $function_call Optional string representing the type of function call.
	 *                     eg( 'has_action', 'has_filter' ).
	 */
	protected function do_action_validation( $action, $callback, $function_call = 'has_action' ) {

		// Default WP priority.
		$priority = isset( $test[3] ) ? $test[3] : 10;

		// Default function call.
		$function_call = ( in_array( $function_call, array( 'has_action', 'has_filter' ), true ) ) ? $function_call : 'has_action';

		if ( is_array( $callback ) ) {

			$callback_name = is_string( $callback[0] ) ? $callback[0] : get_class( $callback[0] ) . ':' . $callback[1];

		} else {

			$callback_name = $callback;

		}

		// Run assertion here.
		$this->assertEquals(
			$priority,
			$function_call( $action, $callback ),
			"$action is not attached to $callback_name. It might also have the wrong priority (validated priority: $priority)"
		);

		$this->assertTrue(
			is_callable( $callback ),
			"$callback_name is not implemented."
		);

	}

	/**
	 * Helper function to check validity of filters.
	 *
	 * @param string       $action String representing an action function.
	 * @param array|string $callback String or Array representing callback function.
	 */
	protected function do_filter_validation( $action, $callback ) {

		$this->do_action_validation( $action, $callback, 'has_filter' );

	}

}
