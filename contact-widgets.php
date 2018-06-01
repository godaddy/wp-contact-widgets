<?php
/**
 * Plugin Name: Contact Widgets
 * Description: Beautifully display social media and contact information on your website with these simple widgets.
 * Version: 1.5.0
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

			/**
			 * Should Font Awesome be loaded from the CDN.
			 *
			 * @var boolean
			 */
			$use_cdn = (boolean) apply_filters( 'wpcw_widget_social_icons_use_cdn', false );

			/**
			 * Font Awesome CDN URL.
			 *
			 * @var string
			 */
			$fontawesome_cdn_url = (string) esc_url( apply_filters( 'wpcw_widget_social_icons_cdn_url', 'https://use.fontawesome.com/releases/v5.0.6/js/all.js' ) );

			$suffix = SCRIPT_DEBUG ? '' : '.min';

			return $use_cdn ? $fontawesome_cdn_url : static::$assets_url . "js/fontawesome-all{$suffix}.js";

		}

		/**
		 * Enqueue Font Awesome
		 */
		public function enqueue_font_awesome() {

			wp_enqueue_script( 'font-awesome', self::$fa_url, [], '5.0.6', true );

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
