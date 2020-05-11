<?php
/**
 * Includes bootstrap.
 *
 * @package ContactWidgets
 */

$wpcw_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $wpcw_tests_dir ) {

	$wpcw_tests_dir = '/tmp/wordpress-tests-lib';

}

require_once $wpcw_tests_dir . '/includes/functions.php';

/**
 * Function to require main plugin file.
 */
function wpcw_manually_load_plugin() {

	require dirname( dirname( dirname( __FILE__ ) ) ) . '/contact-widgets.php';

}

tests_add_filter( 'muplugins_loaded', 'wpcw_manually_load_plugin' );

require $wpcw_tests_dir . '/includes/bootstrap.php';
require __DIR__ . '/testcase.php';
