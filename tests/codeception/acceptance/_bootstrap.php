<?php

// Apply filters for browserstack credentials since we don't want to version it
self::$config['modules'] = [
	'config' => [
		'WebDriver' => [
			'url'     => apply_filters( 'webdriver_url', home_url() ),
			'browser' => apply_filters( 'webdriver_browser', 'firefox' ),
		],
		'BrowserStack' => [
			'url'        => apply_filters( 'browserstack_url', home_url() ),
			'username'   => apply_filters( 'browserstack_username', '' ),
			'access_key' => apply_filters( 'browserstack_accesskey', '' ),
		],
	],
];

// Activate twenty_sixteen for our test purpose
$current_theme = get_stylesheet();

switch_theme( 'twentysixteen' );

$contact_widgets = get_option( 'widget_wpcw_contact' );
$social_widgets = get_option( 'widget_wpcw_social' );
$hours_widgets = get_option( 'widget_wpcw_hours' );

// Let's delete any present widget
delete_option( 'widget_wpcw_contact' );
delete_option( 'widget_wpcw_social' );
delete_option( 'widget_wpcw_hours' );

add_action( 'shutdown', function() use( $current_theme, $contact_widgets, $social_widgets, $hours_widgets ) {

	switch_theme( $current_theme );

	update_option( 'widget_wpcw_contact', $contact_widgets );
	update_option( 'widget_wpcw_social', $social_widgets );
	update_option( 'widget_wpcw_hours', $hours_widgets );

} );
