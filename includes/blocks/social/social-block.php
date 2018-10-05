<?php

namespace WPCW;

if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

class Social_Block {

	public function __construct() {

		add_action( 'enqueue_block_editor_assets', array( $this, 'social_block_scripts' ) );

		add_action( 'enqueue_block_assets', array( $this, 'social_block_styles' ) );

	}


	/**
	 * Enqueue admin block styles.
	 *
	 * @action enqueue_block_editor_assets
	 */
	public function social_block_scripts() {

		wp_enqueue_style( 'contact-widgets-social-block-frontend', plugins_url( 'social-block.css', __FILE__ ), array( 'wp-edit-blocks' ), Plugin::$version );

		$rtl    = is_rtl() ? '-rtl' : '';
		$suffix = SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_style( 'font-awesome', \Contact_Widgets::$fa_url, [], '4.7.0' );

	}

	/**
	 * Enqueue frontend styles.
	 *
	 * @action enqueue_block_assets
	 */
	public function social_block_styles() {

		wp_enqueue_style( 'font-awesome', \Contact_Widgets::$fa_url, [], '4.7.0' );

		wp_enqueue_style( 'contact-widgets-social-block-frontend', plugins_url( 'social-block.css', __FILE__ ), array( 'wp-edit-blocks', 'font-awesome' ), Plugin::$version );

	}

}

new Social_Block();
