<?php

if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

$username = esc_attr_x( 'username', 'Must be lowercase and use url-safe characters', 'contact-widgets' );
$channel  = esc_attr_x( 'channel', 'Must be lowercase and use url-safe characters', 'contact-widgets' );
$company  = esc_attr_x( 'company', 'Must be lowercase and use url-safe characters', 'contact-widgets' );
$board    = esc_attr_x( 'board', 'Must be lowercase and use url-safe characters', 'contact-widgets' );

$fields = [
	'facebook'    => [
		'icon'    => 'facebook',
		'label'   => __( 'Facebook', 'contact-widgets' ),
		'default' => "https://www.facebook.com/{$username}",
		'select'  => $username,
	],
	'twitter'     => [
		'label'   => __( 'Twitter', 'contact-widgets' ),
		'default' => "https://twitter.com/{$username}",
		'select'  => $username,
	],
	'googleplus'  => [
		'icon'    => 'google-plus',
		'label'   => __( 'Google+', 'contact-widgets' ),
		'default' => "https://google.com/+{$username}",
		'select'  => $username,
	],
	'linkedin'    => [
		'icon'    => 'linkedin',
		'label'   => __( 'LinkedIn', 'contact-widgets' ),
		'default' => "https://www.linkedin.com/in/{$username}",
		'select'  => $username,
	],
	'rss'         => [
		'label'   => __( 'RSS feed', 'contact-widgets' ),
		'default' => get_feed_link(),
	],
	'pinterest'   => [
		'label'   => __( 'Pinterest', 'contact-widgets' ),
		'default' => "https://www.pinterest.com/{$username}",
		'select'  => $username,
	],
	'youtube'     => [
		'label'      => __( 'YouTube', 'contact-widgets' ),
		'default'    => "https://www.youtube.com/user/{$username}",
		'select'     => $username,
		'deprecated' => true,
	],
	'vimeo'       => [
		'label'   => __( 'Vimeo', 'contact-widgets' ),
		'default' => "https://vimeo.com/{$username}",
		'select'  => $username,
	],
	'flickr'      => [
		'label'   => __( 'Flickr', 'contact-widgets' ),
		'default' => "https://www.flickr.com/photos/{$username}",
		'select'  => $username,
	],
	'500px'       => [
		'label'   => __( '500px', 'contact-widgets' ),
		'default' => "https://www.500px.com/{$username}",
		'select'  => $username,
	],
	'foursquare'  => [
		'label'   => __( 'Foursquare', 'contact-widgets' ),
		'default' => "https://foursquare.com/{$username}",
		'select'  => $username,
	],
	'github'      => [
		'label'   => __( 'GitHub', 'contact-widgets' ),
		'default' => "https://github.com/{$username}",
		'select'  => $username,
	],
	'slack'       => [
		'label'   => __( 'Slack', 'contact-widgets' ),
		'default' => "https://{$channel}.slack.com/",
		'select'  => $channel,
	],
	'skype'       => [
		'label'     => __( 'Skype', 'contact-widgets' ),
		'default'   => "skype:{$username}?chat",
		'sanitizer' => 'esc_attr',
		'escaper'   => 'esc_attr',
		'select'    => $username,
	],
	'soundcloud'  => [
		'label'   => __( 'SoundCloud', 'contact-widgets' ),
		'default' => "https://soundcloud.com/{$username}",
		'select'  => $username,
	],
	'tripadvisor' => [
		'label'   => __( 'TripAdvisor', 'contact-widgets' ),
		'default' => 'https://www.tripadvisor.com/',
	],
	'wordpress'   => [
		'label'   => __( 'WordPress', 'contact-widgets' ),
		'default' => "https://profiles.wordpress.org/{$username}",
		'select'  => $username,
	],
	'yelp'        => [
		'label'   => __( 'Yelp', 'contact-widgets' ),
		'default' => "http://www.yelp.com/biz/{$company}",
		'select'  => $company,
	],
	'amazon'      => [
		'label'   => __( 'Amazon', 'contact-widgets' ),
		'default' => 'https://www.amazon.com/',
	],
	'instagram'   => [
		'label'   => __( 'Instagram', 'contact-widgets' ),
		'default' => "https://www.instagram.com/{$username}",
		'select'  => $username,
	],
	'vine'        => [
		'label'   => __( 'Vine', 'contact-widgets' ),
		'default' => "https://vine.co/{$username}",
		'select'  => $username,
	],
	'reddit'      => [
		'label'   => __( 'reddit', 'contact-widgets' ),
		'default' => "https://www.reddit.com/user/{$username}",
		'select'  => $username,
	],
	'xing'        => [
		'label'   => __( 'XING', 'contact-widgets' ),
		'default' => 'https://www.xing.com/',
	],
	'tumblr'      => [
		'label'   => __( 'Tumblr', 'contact-widgets' ),
		'default' => "https://{$username}.tumblr.com/",
		'select'  => $username,
	],
	'whatsapp'    => [
		'label'   => __( 'WhatsApp', 'contact-widgets' ),
		'default' => 'https://www.whatsapp.com/',
	],
	'wechat'      => [
		'icon'    => 'weixin',
		'label'   => __( 'WeChat', 'contact-widgets' ),
		'default' => 'http://www.wechat.com/',
	],
	'medium'      => [
		'label'   => __( 'Medium', 'contact-widgets' ),
		'default' => "https://medium.com/@{$username}",
		'select'  => $username,
	],
	'dribbble'    => [
		'label'   => __( 'Dribbble', 'contact-widgets' ),
		'default' => "https://dribbble.com/{$username}",
		'select'  => $username,
	],
	'twitch'      => [
		'label'   => __( 'Twitch', 'contact-widgets' ),
		'default' => "https://www.twitch.tv/{$username}",
		'select'  => $username,
	],
	'vk'          => [
		'label'   => __( 'VK', 'contact-widgets' ),
		'default' => 'https://vk.com/',
	],
	'trello'      => [
		'label'   => __( 'Trello', 'contact-widgets' ),
		'default' => "https://trello.com/b/{$board}",
		'select'  => $board,
	],
	'unsplash'    => [
		'icon'    => 'camera',
		'label'   => __( 'Unsplash', 'contact-widgets' ),
		'default' => "https://unsplash.com/@{$username}",
		'select'  => $username,
	],
];

if ( \Contact_Widgets::$fontawesome_5 ) {

	$fields['rss']['prefix']      = 'fas';
	$fields['unsplash']['prefix'] = 'fas';

}
