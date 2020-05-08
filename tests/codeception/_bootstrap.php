<?php

define( 'WP_USE_THEMES', false );

if ( getenv( 'TRAVIS' ) ) {

	require '/tmp/wordpress/wp-load.php';

} else {

	require '../../../wp-load.php';

}

require self::$config['paths']['tests'] . '/acceptance/_bootstrap.php';
require self::$config['paths']['tests'] . '/functional/_bootstrap.php';
