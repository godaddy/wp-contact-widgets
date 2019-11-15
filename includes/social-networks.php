<?php

if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

$username = esc_attr_x( 'username', 'Must be lowercase and use url-safe characters', 'contact-widgets' );
$channel  = esc_attr_x( 'channel', 'Must be lowercase and use url-safe characters', 'contact-widgets' );
$company  = esc_attr_x( 'company', 'Must be lowercase and use url-safe characters', 'contact-widgets' );
$board    = esc_attr_x( 'board', 'Must be lowercase and use url-safe characters', 'contact-widgets' );

$fields = array(
	'facebook'    => array(
		'icon'    => 'facebook',
		'label'   => __( 'Facebook', 'contact-widgets' ),
		'default' => "https://www.facebook.com/{$username}",
		'select'  => $username,
	),
	'twitter'     => array(
		'label'   => __( 'Twitter', 'contact-widgets' ),
		'default' => "https://twitter.com/{$username}",
		'select'  => $username,
	),
	'googleplus'  => array(
		'icon'    => 'google-plus',
		'label'   => __( 'Google+', 'contact-widgets' ),
		'default' => "https://google.com/+{$username}",
		'select'  => $username,
	),
	'linkedin'    => array(
		'icon'    => 'linkedin',
		'label'   => __( 'LinkedIn', 'contact-widgets' ),
		'default' => "https://www.linkedin.com/in/{$username}",
		'select'  => $username,
	),
	'rss'         => array(
		'label'   => __( 'RSS feed', 'contact-widgets' ),
		'default' => get_feed_link(),
	),
	'pinterest'   => array(
		'label'   => __( 'Pinterest', 'contact-widgets' ),
		'default' => "https://www.pinterest.com/{$username}",
		'select'  => $username,
	),
	'youtube'     => array(
		'label'      => __( 'YouTube', 'contact-widgets' ),
		'default'    => "https://www.youtube.com/user/{$username}",
		'select'     => $username,
		'deprecated' => true,
	),
	'vimeo'       => array(
		'label'   => __( 'Vimeo', 'contact-widgets' ),
		'default' => "https://vimeo.com/{$username}",
		'select'  => $username,
	),
	'flickr'      => array(
		'label'   => __( 'Flickr', 'contact-widgets' ),
		'default' => "https://www.flickr.com/photos/{$username}",
		'select'  => $username,
	),
	'500px'       => array(
		'label'   => __( '500px', 'contact-widgets' ),
		'default' => "https://www.500px.com/{$username}",
		'select'  => $username,
	),
	'foursquare'  => array(
		'label'   => __( 'Foursquare', 'contact-widgets' ),
		'default' => "https://foursquare.com/{$username}",
		'select'  => $username,
	),
	'github'      => array(
		'label'   => __( 'GitHub', 'contact-widgets' ),
		'default' => "https://github.com/{$username}",
		'select'  => $username,
	),
	'slack'       => array(
		'label'   => __( 'Slack', 'contact-widgets' ),
		'default' => "https://{$channel}.slack.com/",
		'select'  => $channel,
	),
	'skype'       => array(
		'label'     => __( 'Skype', 'contact-widgets' ),
		'default'   => "skype:{$username}?chat",
		'sanitizer' => 'esc_attr',
		'escaper'   => 'esc_attr',
		'select'    => $username,
	),
	'soundcloud'  => array(
		'label'   => __( 'SoundCloud', 'contact-widgets' ),
		'default' => "https://soundcloud.com/{$username}",
		'select'  => $username,
	),
	'tripadvisor' => array(
		'label'   => __( 'TripAdvisor', 'contact-widgets' ),
		'default' => 'https://www.tripadvisor.com/',
	),
	'wordpress'   => array( // @codingStandardsIgnoreLine
		'label'   => __( 'WordPress', 'contact-widgets' ),
		'default' => "https://profiles.wordpress.org/{$username}",
		'select'  => $username,
	),
	'yelp'        => array(
		'label'   => __( 'Yelp', 'contact-widgets' ),
		'default' => "http://www.yelp.com/biz/{$company}",
		'select'  => $company,
	),
	'amazon'      => array(
		'label'   => __( 'Amazon', 'contact-widgets' ),
		'default' => 'https://www.amazon.com/',
	),
	'instagram'   => array(
		'label'   => __( 'Instagram', 'contact-widgets' ),
		'default' => "https://www.instagram.com/{$username}",
		'select'  => $username,
	),
	'vine'        => array(
		'label'   => __( 'Vine', 'contact-widgets' ),
		'default' => "https://vine.co/{$username}",
		'select'  => $username,
	),
	'reddit'      => array(
		'label'   => __( 'reddit', 'contact-widgets' ),
		'default' => "https://www.reddit.com/user/{$username}",
		'select'  => $username,
	),
	'xing'        => array(
		'label'   => __( 'XING', 'contact-widgets' ),
		'default' => 'https://www.xing.com/',
	),
	'tumblr'      => array(
		'label'   => __( 'Tumblr', 'contact-widgets' ),
		'default' => "https://{$username}.tumblr.com/",
		'select'  => $username,
	),
	'whatsapp'    => array(
		'label'   => __( 'WhatsApp', 'contact-widgets' ),
		'default' => 'https://www.whatsapp.com/',
	),
	'wechat'      => array(
		'icon'    => 'weixin',
		'label'   => __( 'WeChat', 'contact-widgets' ),
		'default' => 'http://www.wechat.com/',
	),
	'medium'      => array(
		'label'   => __( 'Medium', 'contact-widgets' ),
		'default' => "https://medium.com/@{$username}",
		'select'  => $username,
	),
	'dribbble'    => array(
		'label'   => __( 'Dribbble', 'contact-widgets' ),
		'default' => "https://dribbble.com/{$username}",
		'select'  => $username,
	),
	'twitch'      => array(
		'label'   => __( 'Twitch', 'contact-widgets' ),
		'default' => "https://www.twitch.tv/{$username}",
		'select'  => $username,
	),
	'vk'          => array(
		'label'   => __( 'VK', 'contact-widgets' ),
		'default' => 'https://vk.com/',
	),
	'trello'      => array(
		'label'   => __( 'Trello', 'contact-widgets' ),
		'default' => "https://trello.com/b/{$board}",
		'select'  => $board,
	),
	'unsplash'    => array(
		'icon'    => 'camera',
		'label'   => __( 'Unsplash', 'contact-widgets' ),
		'default' => "https://unsplash.com/@{$username}",
		'select'  => $username,
	),
);

if ( \Contact_Widgets::$fontawesome_5 ) {

	$fields['rss']['prefix']      = 'fas';
	$fields['unsplash']['prefix'] = 'fas';

}
