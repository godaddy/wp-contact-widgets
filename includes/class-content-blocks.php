<?php

namespace WPCW;

if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

class Content_Blocks {

	public function __construct() {

		include_once __DIR__ . '/blocks/contact/contact-block.php';
		include_once __DIR__ . '/blocks/social/social-block.php';

		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_scripts' ) );

	}

	/**
	 * Enqueue content block scripts.
	 *
	 * @action enqueue_block_editor_assets
	 *
	 * @since 1.6.0
	 */
	public function enqueue_block_scripts() {

		$suffix = SCRIPT_DEBUG ? '' : '.min';

		include 'social-networks.php';

		wp_enqueue_script( 'jquery-ui-sortable' );

		wp_enqueue_script( 'contact-widgets-blocks', plugins_url( "../assets/js/contact-widget-blocks{$suffix}.js", __FILE__ ), array( 'wp-i18n', 'wp-element', 'wp-blocks', 'wp-components', 'jquery-ui-sortable' ), Plugin::$version, true );

		wp_localize_script(
			'contact-widgets-blocks',
			'wpcw_social',
			array(
				'icons'      => (array) apply_filters( 'wpcw_widget_social_custom_fields', $fields, new \stdClass() ),
				'iconPrefix' => \Contact_Widgets::$fontawesome_5 ? 'fab' : 'fa',
			)
		);

	}

}
