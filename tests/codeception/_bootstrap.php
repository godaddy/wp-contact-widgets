<?php

define( 'WP_USE_THEMES', false );

if ( getenv( 'TRAVIS' ) ) {

	require( '/tmp/wordpress/wp-load.php' );

} else {

	require( dirname( dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) ) . '/wp-load.php' );

}

require self::$config['paths']['tests'] . '/acceptance/_bootstrap.php';
require self::$config['paths']['tests'] . '/functional/_bootstrap.php';
