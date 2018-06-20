<?php

namespace WPCW;

final class TestSocial extends TestCase {

	function setUp() {

		global $_wp_sidebars_widgets;

		parent::setUp();

		$this->plugin = new Social();

		if ( ! isset( $_wp_sidebars_widgets['sidebar-1']['wpcw_social'] ) ) {

			$_wp_sidebars_widgets['sidebar-1'][] = 'wpcw_social-1';

		}

	}

	function test_construct() {

		$this->assertEquals( $this->plugin->widget_options['classname'], 'wpcw-widgets wpcw-widget-social' );

		$this->assertEquals( $this->plugin->id_base, 'wpcw_social' );

	}

	function test_form() {

		$this->expectOutputRegex( '/class="wpcw-widget wpcw-widget-social"/' );
		$this->expectOutputRegex( '/class="customizer_update"/' );
		$this->expectOutputRegex( '/class="default-fields"/' );

		$this->plugin->form( [] );

	}

	function test_widget() {

		$instance = [
			'title'  => 'test',
			'labels' => [
				'value' => 'yes',
			],
		];

		$args = [
			'before_widget' => '<div class="widget wpcw-widget-social"><div class="widget-content">',
			'after_widget' => '</div><div class="clear"></div></div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		];

		$this->expectOutputRegex( '/<div class="widget wpcw-widget-social"><div class="widget-content">/' );
		$this->expectOutputRegex( '/<h3 class="widget-title">/' );

		$this->plugin->widget( $args, $instance );

		// Tests that script & styles are enqueued enqueued
		do_action( 'wp_enqueue_scripts' );

		$wp_styles  = wp_styles();
		$wp_scripts = wp_scripts();

		// Make sure the CSS files are enqueued
		$this->assertContains( 'wpcw', $wp_styles->queue );
		$this->assertContains( 'font-awesome', $wp_styles->queue );

	}

}
