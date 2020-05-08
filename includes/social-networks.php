<?php
/**
 * Social Network fields processor.
 *
 * @package ContactWidgets
 */

if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

$wpcw_username = esc_attr_x( 'username', 'Must be lowercase and use url-safe characters', 'contact-widgets' );
$wpcw_channel  = esc_attr_x( 'channel', 'Must be lowercase and use url-safe characters', 'contact-widgets' );
$wpcw_company  = esc_attr_x( 'company', 'Must be lowercase and use url-safe characters', 'contact-widgets' );
$wpcw_board    = esc_attr_x( 'board', 'Must be lowercase and use url-safe characters', 'contact-widgets' );

$wpcw_fields = array(
	'facebook'    => array(
		'icon'    => 'facebook',
		'label'   => __( 'Facebook', 'contact-widgets' ),
		'default' => "https://www.facebook.com/{$wpcw_username}",
		'select'  => $wpcw_username,
	),
	'twitter'     => array(
		'label'   => __( 'Twitter', 'contact-widgets' ),
		'default' => "https://twitter.com/{$wpcw_username}",
		'select'  => $wpcw_username,
	),
	'googleplus'  => array(
		'icon'    => 'google-plus',
		'label'   => __( 'Google+', 'contact-widgets' ),
		'default' => "https://google.com/+{$wpcw_username}",
		'select'  => $wpcw_username,
	),
	'linkedin'    => array(
		'icon'    => 'linkedin',
		'label'   => __( 'LinkedIn', 'contact-widgets' ),
		'default' => "https://www.linkedin.com/in/{$wpcw_username}",
		'select'  => $wpcw_username,
	),
	'rss'         => array(
		'label'   => __( 'RSS feed', 'contact-widgets' ),
		'default' => get_feed_link(),
	),
	'pinterest'   => array(
		'label'   => __( 'Pinterest', 'contact-widgets' ),
		'default' => "https://www.pinterest.com/{$wpcw_username}",
		'select'  => $wpcw_username,
	),
	'youtube'     => array(
		'label'      => __( 'YouTube', 'contact-widgets' ),
		'default'    => "https://www.youtube.com/user/{$wpcw_username}",
		'select'     => $wpcw_username,
		'deprecated' => true,
	),
	'vimeo'       => array(
		'label'   => __( 'Vimeo', 'contact-widgets' ),
		'default' => "https://vimeo.com/{$wpcw_username}",
		'select'  => $wpcw_username,
	),
	'flickr'      => array(
		'label'   => __( 'Flickr', 'contact-widgets' ),
		'default' => "https://www.flickr.com/photos/{$wpcw_username}",
		'select'  => $wpcw_username,
	),
	'500px'       => array(
		'label'   => __( '500px', 'contact-widgets' ),
		'default' => "https://www.500px.com/{$wpcw_username}",
		'select'  => $wpcw_username,
	),
	'foursquare'  => array(
		'label'   => __( 'Foursquare', 'contact-widgets' ),
		'default' => "https://foursquare.com/{$wpcw_username}",
		'select'  => $wpcw_username,
	),
	'github'      => array(
		'label'   => __( 'GitHub', 'contact-widgets' ),
		'default' => "https://github.com/{$wpcw_username}",
		'select'  => $wpcw_username,
	),
	'slack'       => array(
		'label'   => __( 'Slack', 'contact-widgets' ),
		'default' => "https://{$wpcw_channel}.slack.com/",
		'select'  => $wpcw_channel,
	),
	'skype'       => array(
		'label'     => __( 'Skype', 'contact-widgets' ),
		'default'   => "skype:{$wpcw_username}?chat",
		'sanitizer' => 'esc_attr',
		'escaper'   => 'esc_attr',
		'select'    => $wpcw_username,
	),
	'soundcloud'  => array(
		'label'   => __( 'SoundCloud', 'contact-widgets' ),
		'default' => "https://soundcloud.com/{$wpcw_username}",
		'select'  => $wpcw_username,
	),
	'tripadvisor' => array(
		'label'   => __( 'TripAdvisor', 'contact-widgets' ),
		'default' => 'https://www.tripadvisor.com/',
	),
	'wordpress'   => array( // @codingStandardsIgnoreLine
		'label'   => __( 'WordPress', 'contact-widgets' ),
		'default' => "https://profiles.wordpress.org/{$wpcw_username}",
		'select'  => $wpcw_username,
	),
	'yelp'        => array(
		'label'   => __( 'Yelp', 'contact-widgets' ),
		'default' => "http://www.yelp.com/biz/{$wpcw_company}",
		'select'  => $wpcw_company,
	),
	'amazon'      => array(
		'label'   => __( 'Amazon', 'contact-widgets' ),
		'default' => 'https://www.amazon.com/',
	),
	'instagram'   => array(
		'label'   => __( 'Instagram', 'contact-widgets' ),
		'default' => "https://www.instagram.com/{$wpcw_username}",
		'select'  => $wpcw_username,
	),
	'vine'        => array(
		'label'   => __( 'Vine', 'contact-widgets' ),
		'default' => "https://vine.co/{$wpcw_username}",
		'select'  => $wpcw_username,
	),
	'reddit'      => array(
		'label'   => __( 'reddit', 'contact-widgets' ),
		'default' => "https://www.reddit.com/user/{$wpcw_username}",
		'select'  => $wpcw_username,
	),
	'xing'        => array(
		'label'   => __( 'XING', 'contact-widgets' ),
		'default' => 'https://www.xing.com/',
	),
	'tumblr'      => array(
		'label'   => __( 'Tumblr', 'contact-widgets' ),
		'default' => "https://{$wpcw_username}.tumblr.com/",
		'select'  => $wpcw_username,
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
		'default' => "https://medium.com/@{$wpcw_username}",
		'select'  => $wpcw_username,
	),
	'dribbble'    => array(
		'label'   => __( 'Dribbble', 'contact-widgets' ),
		'default' => "https://dribbble.com/{$wpcw_username}",
		'select'  => $wpcw_username,
	),
	'twitch'      => array(
		'label'   => __( 'Twitch', 'contact-widgets' ),
		'default' => "https://www.twitch.tv/{$wpcw_username}",
		'select'  => $wpcw_username,
	),
	'vk'          => array(
		'label'   => __( 'VK', 'contact-widgets' ),
		'default' => 'https://vk.com/',
	),
	'trello'      => array(
		'label'   => __( 'Trello', 'contact-widgets' ),
		'default' => "https://trello.com/b/{$wpcw_board}",
		'select'  => $wpcw_board,
	),
	'unsplash'    => array(
		'icon'    => 'camera',
		'label'   => __( 'Unsplash', 'contact-widgets' ),
		'default' => "https://unsplash.com/@{$wpcw_username}",
		'select'  => $wpcw_username,
	),
);

if ( \Contact_Widgets::$fontawesome_5 ) {

	$wpcw_fields['rss']['prefix']      = 'fas';
	$wpcw_fields['unsplash']['prefix'] = 'fas';

}
