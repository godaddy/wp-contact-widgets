<?php
/**
 * Plugin Name: Contact Widgets
 * Description: Beautifully display social media and contact information on your website with these simple widgets.
 * Version: 1.5.2
 * Author: GoDaddy
 * Author URI: https://godaddy.com
 * Text Domain: contact-widgets
 * Domain Path: /languages
 * License: GPL-2.0
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * Copyright © 2016 GoDaddy Operating Company, LLC. All Rights Reserved.
 */

if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

if ( ! class_exists( 'Contact_Widgets' ) ) {

	final class Contact_Widgets {

		/**
		 * Minimum PHP version
		 *
		 * @var string
		 */
		private $php_min_version = '5.4';

		/**
		 * Plugin assets URL
		 *
		 * @var string
		 */
		public static $assets_url;

		/**
		 * Font Awesome 5
		 *
		 * @var boolean
		 */
		public static $fontawesome_5;

		/**
		 * Font Awesome CSS locations
		 *
		 * @var string
		 */
		public static $fa_url;

		/**
		 * Class constructor
		 *
		 * @param string $cur_php_version
		 */
		public function __construct( $cur_php_version = PHP_VERSION ) {

			static::$assets_url = plugin_dir_url( __FILE__ ) . 'assets/';

			/**
			 * Should Font Awesome 5 be loaded.
			 *
			 * @var boolean
			 */
			static::$fontawesome_5 = (boolean) apply_filters( 'wpcw_social_icons_fontawesome_5', false );

			static::$fa_url = $this->font_awesome_url();

			$composer_autoloader = __DIR__ . '/vendor/autoload.php';

			if ( defined( 'WP_CLI' ) && WP_CLI && file_exists( $composer_autoloader ) ) {

				// This is for enabling codeception
				require_once $composer_autoloader;

			}

			add_action( 'plugins_loaded', array( $this, 'i18n' ) );

			if ( version_compare( $cur_php_version, $this->php_min_version, '<' ) ) {

				add_action( 'shutdown', array( $this, 'notice' ) );

				return;

			}

			require_once __DIR__ . '/includes/autoload.php';

		}

		/**
		 * Setup the Front Awesome assets URL
		 *
		 * @return string Returns URL where Font Awesome should load from.
		 *
		 * @since 1.5.0
		 */
		public function font_awesome_url() {

			$suffix = SCRIPT_DEBUG ? '' : '.min';

			/**
			 * Font Awesome CDN URL.
			 *
			 * @var string
			 */
			$fontawesome_cdn_url = (string) esc_url( apply_filters( 'wpcw_social_icons_cdn_url', ( self::$fontawesome_5 ? 'https://use.fontawesome.com/releases/v5.0.13/css/all.css' : "//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome{$suffix}.css" ) ) );

			if ( self::$fontawesome_5 ) {

				// Font Awesome 5 CDN URL
				return $fontawesome_cdn_url;

			}

			/**
			 * Should Font Awesome be loaded from the CDN.
			 *
			 * @var boolean
			 */
			$use_cdn = (boolean) apply_filters( 'wpcw_social_icons_use_cdn', false );

			return ! $use_cdn ? static::$assets_url . "css/font-awesome{$suffix}.css" : $fontawesome_cdn_url;

		}

		/**
		 * Load languages
		 *
		 * @action plugins_loaded
		 */
		public function i18n() {

			load_plugin_textdomain( 'contact-widgets', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

		}

		/**
		 * Display min PHP version notice
		 *
		 * @action shutdown
		 */
		public function notice() {

			printf(
				'<div class="error"><p>%s</p></div>',
				sprintf(
					/* translators: Minumum PHP version supported. */
					esc_html__( 'Contact widgets requires PHP version %s or higher. Please deactivate the plugin and contact your system administrator.', 'contact-widgets' ),
					esc_html( $this->php_min_version )
				)
			);

		}

	}

	new Contact_Widgets();

}
