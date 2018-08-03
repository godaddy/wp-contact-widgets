<?php

namespace WPCW;

if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

class Contact_Block {

	public function __construct() {

		add_action( 'enqueue_block_editor_assets', array( $this, 'contact_block_scripts' ) );

		add_action( 'enqueue_block_assets', array( $this, 'contact_block_styles' ) );

	}


	/**
	 * Enqueue admin block scripts and styles.
	 *
	 * @action enqueue_block_editor_assets
	 */
	public function contact_block_scripts() {

		wp_enqueue_style( 'contact-widgets-contact-block-frontend', plugins_url( 'contact-block.css', __FILE__ ), array( 'wp-blocks' ), Plugin::$version );

	}

	/**
	 * Enqueue frontend styles.
	 *
	 * @action enqueue_block_assets
	 */
	public function contact_block_styles() {

		wp_enqueue_style( 'contact-widgets-contact-block-frontend', plugins_url( 'contact-block.css', __FILE__ ), array( 'wp-blocks' ), Plugin::$version );

	}

}

new Contact_Block();
