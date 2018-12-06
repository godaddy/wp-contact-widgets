<?php

namespace WPCW;

if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

class Contact_Block {

	public function __construct() {

		add_action( 'enqueue_block_editor_assets', array( $this, 'contact_block_scripts' ) );

		add_action( 'enqueue_block_assets', array( $this, 'contact_block_scripts' ) );

	}


	/**
	 * Enqueue admin block styles.
	 *
	 * @action enqueue_block_editor_assets
	 */
	public function contact_block_scripts() {

		$suffix = SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_style( 'contact-widgets-contact-block', plugins_url( "css/contact-block{$suffix}.css", __FILE__ ), array( 'wp-edit-blocks' ), Plugin::$version );

	}
}

new Contact_Block();
