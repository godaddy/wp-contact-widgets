<?php

namespace WPCW;

if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

class Content_Blocks {

	public function __construct() {

		include_once( __DIR__ . '/blocks/contact/contact-block.php' );
		// include_once( __DIR__ . '/blocks/social/social-block.php' );

		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_scripts' ) );

	}

	public function enqueue_block_scripts() {

		$suffix = SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script( 'contact-widgets-blocks', plugins_url( "../assets/js/contact-widget-blocks{$suffix}.js", __FILE__ ), array( 'wp-i18n', 'wp-element', 'wp-blocks', 'wp-components' ), Plugin::$version );


	}

}
