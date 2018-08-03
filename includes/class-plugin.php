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

		if ( ! function_exists( 'is_plugin_active' ) ) {

			require_once ABSPATH . '/wp-admin/includes/plugin.php';

		}

		$blog = get_bloginfo();

		if ( is_plugin_active( 'gutenberg/gutenberg.php' ) || version_compare( $blog->version, '5.0.0', '>=' ) ) {

			new Content_Blocks();

		}

	}

	/**
	 * Register our custom widget using the api
	 */
	public static function register_widgets() {

		register_widget( __NAMESPACE__ . '\Contact' );
		register_widget( __NAMESPACE__ . '\Social' );

	}

}
