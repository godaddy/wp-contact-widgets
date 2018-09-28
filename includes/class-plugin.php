<?php

namespace WPCW;

if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

class Plugin {

	/**
	 * Plugin version
	 *
	 * @var string
	 */
	public static $version = '1.0.1';

	/**
	 * Class constructor
	 */
	public static function init() {

		add_action( 'widgets_init', [ get_called_class(), 'register_widgets' ] );

		new Content_Blocks();

	}

	/**
	 * Register our custom widget using the api
	 */
	public static function register_widgets() {

		register_widget( __NAMESPACE__ . '\Contact' );
		register_widget( __NAMESPACE__ . '\Social' );

	}

}
