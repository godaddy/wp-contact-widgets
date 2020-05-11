<?php
// phpcs:ignoreFile - Dev only testing file. Ignores warnings related to variable prefix.
/**
 * Apply filters for browserstack credentials since we don't want to version it.
 *
 * @package ContactWidgets
 */

self::$config['modules'] = array(
	'config' => array(
		'WebDriver'    => array(
			// 'url'     => apply_filters( 'webdriver_url', trailingslashit( home_url() ) ),
			'browser' => apply_filters( 'webdriver_browser', 'chrome' ),
		),
		'BrowserStack' => array(
			'url'        => apply_filters( 'browserstack_url', trailingslashit( home_url() ) ),
			'username'   => apply_filters( 'browserstack_username', '' ),
			'access_key' => apply_filters( 'browserstack_accesskey', '' ),
		),
	),
);

// Activate twenty_sixteen for our test purpose.
$wpcw_current_theme = get_stylesheet();

switch_theme( 'twentysixteen' );

$wpcw_contact_widgets = get_option( 'widget_wpcw_contact' );
$wpcw_social_widgets  = get_option( 'widget_wpcw_social' );

// Let's delete any present widget.
delete_option( 'widget_wpcw_contact' );
delete_option( 'widget_wpcw_social' );

add_action(
	'shutdown',
	function() use ( $wpcw_current_theme, $wpcw_contact_widgets, $wpcw_social_widgets ) {

		switch_theme( $wpcw_current_theme );

		update_option( 'widget_wpcw_contact', $wpcw_contact_widgets );
		update_option( 'widget_wpcw_social', $wpcw_social_widgets );

	}
);
