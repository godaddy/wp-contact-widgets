<?php

namespace WPCW;

final class TestHours extends TestCase {

	function setUp() {

		global $_wp_sidebars_widgets;

		parent::setUp();

		$this->plugin = new Hours();

		if ( ! isset( $_wp_sidebars_widgets['sidebar-1']['wpcw_hours'] ) ) {

			$_wp_sidebars_widgets['sidebar-1'][] = 'wpcw_hours-1';

		}

	}

	function test_construct() {

		$this->assertEquals( $this->plugin->widget_options['classname'], 'wpcw-widgets wpcw-widget-hours' );

		$this->assertEquals( $this->plugin->id_base, 'wpcw_hours' );

	}

	function test_form() {

		$this->expectOutputRegex( '/class="wpcw-widget wpcw-widget-hours"/' );
		$this->expectOutputRegex( '/class="customizer_update"/' );

		$this->plugin->form( [] );

	}

	function test_widget() {

		$instance = array(
			'title' => 'Hours of Operation',
			'days'  => array(
				'monday' => array(
					'open'   => '9:00 am',
					'closed' => '5:00 pm',
				),
				'tuesday' => array(
					'open'   => '9:00 am',
					'closed' => '5:00 pm',
				),
				'wednesday' => array(
					'open'   => '9:00 am',
					'closed' => '5:00 pm',
				),
				'thursday' => array(
					'open'   => '12:00 am',
					'closed' => '3:00 pm',
				),
				'friday'   => array(
					'open'   => '9:00 am',
					'closed' => '5:00 pm',
				),
				'saturday' => array(
					'not_open' => 1,
				),
				'sunday'   => array(
					'custom_text_checkbox' => 1,
					'not_open'             => 1,
					'custom_text'          => 'We are closed.',
				),
			),
			'additional_content' => array(
				'value' => 'Note: We are closed all day Sunday.',
			),
		);

		$args = [
			'before_widget' => '<div class="widget wpcw-widget-hours"><div class="widget-content">',
			'after_widget' => '</div><div class="clear"></div></div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		];

		$this->expectOutputRegex( '/<div class="widget wpcw-widget-hours"><div class="widget-content">/' );
		$this->expectOutputRegex( '/<h3 class="widget-title">Hours of Operation/' );

		// Monday (Standard Time)
		$this->expectOutputRegex( '/<li itemprop="openingHours" datetime="Mo 09:00-17:00"><strong>Monday<\/strong> <div class="hours open">9:00 am - 5:00 pm<\/div><\/li>/' );

		// Thursday (Different Time)
		$this->expectOutputRegex( '/<li itemprop="openingHours" datetime="Th 00:00-15:00"><strong>Thursday<\/strong> <div class="hours open">12:00 am - 3:00 pm<\/div><\/li>/' );

		// Saturday (Not Open Checked)
		$this->expectOutputRegex( '/<li><strong>Saturday<\/strong> <div class="hours closed">Closed<\/div><\/li>/' );

		// Sunday (Custom Text Checkbox & Text Set)
		$this->expectOutputRegex( '/<li><strong>Sunday<\/strong> We are closed.<\/li>/' );

		// Additional Content Field
		$this->expectOutputRegex( '/<p>Note: We are closed all day Sunday.<\/p>/' );

		$this->plugin->widget( $args, $instance );

		// Tests that script & styles are enqueued
		do_action( 'wp_enqueue_scripts' );

		$wp_styles = wp_styles();

		$this->assertContains( 'wpcw', $wp_styles->queue );

	}

}
