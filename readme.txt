=== Contact Widgets ===
Contributors:      godaddy, jonathanbardo, fjarrett, eherman24
Tags:              widget, contact, social, social icons, social media, facebook, twitter, instagram, linkedin, pinterest
Requires at least: 4.4
Tested up to:      5.7
Stable tag:        1.6.2
License:           GPL-2.0
License URI:       https://www.gnu.org/licenses/gpl-2.0.html

Beautifully display social media and contact information on your website with these simple widgets.

== Description ==

**Note: This plugin requires PHP 5.4 or higher**

Beautifully display social media and contact information on your website with simple, easy-to-use widgets.

[youtube https://youtu.be/Y3NsrWHd_us]

**Contact Information**

Display your contact information including email address, phone number, fax and physical address (including a map).

**Social Media Profiles**

Display your social media profiles in an attractive, intuitive way.

**30 Languages Supported**

English - [Dansk](https://github.com/godaddy/wp-contact-widgets/blob/master/languages/contact-widgets-da_DK.po) - [Deutsch](https://github.com/godaddy/wp-contact-widgets/blob/master/languages/contact-widgets-de_DE.po) - [Ελληνικά](https://github.com/godaddy/wp-contact-widgets/blob/master/languages/contact-widgets-el.po) - [Español](https://github.com/godaddy/wp-contact-widgets/blob/master/languages/contact-widgets-es_ES.po) - [Español de México](https://github.com/godaddy/wp-contact-widgets/blob/master/languages/contact-widgets-es_MX.po) - [Suomi](https://github.com/godaddy/wp-contact-widgets/blob/master/languages/contact-widgets-fi.po) - [Français](https://github.com/godaddy/wp-contact-widgets/blob/master/languages/contact-widgets-fr_FR.po) - [हिन्दी](https://github.com/godaddy/wp-contact-widgets/blob/master/languages/contact-widgets-hi_IN.po) - [Bahasa Indonesia](https://github.com/godaddy/wp-contact-widgets/blob/master/languages/contact-widgets-id_ID.po) - [Italiano](https://github.com/godaddy/wp-contact-widgets/blob/master/languages/contact-widgets-it_IT.po) - [日本語](https://github.com/godaddy/wp-contact-widgets/blob/master/languages/contact-widgets-ja.po) - [한국어](https://github.com/godaddy/wp-contact-widgets/blob/master/languages/contact-widgets-ko_KR.po) - [मराठी](https://github.com/godaddy/wp-contact-widgets/blob/master/languages/contact-widgets-mr.po) - [Bahasa Melayu](https://github.com/godaddy/wp-contact-widgets/blob/master/languages/contact-widgets-ms_MY.po) - [Norsk bokmål](https://github.com/godaddy/wp-contact-widgets/blob/master/languages/contact-widgets-nb_NO.po) - [Nederlands](https://github.com/godaddy/wp-contact-widgets/blob/master/languages/contact-widgets-nl_NL.po) - [Polski](https://github.com/godaddy/wp-contact-widgets/blob/master/languages/contact-widgets-pl_PL.po) - [Português do Brasil](https://github.com/godaddy/wp-contact-widgets/blob/master/languages/contact-widgets-pt_BR.po) - [Português](https://github.com/godaddy/wp-contact-widgets/blob/master/languages/contact-widgets-pt_PT.po) - [Русский](https://github.com/godaddy/wp-contact-widgets/blob/master/languages/contact-widgets-ru_RU.po) - [Svenska](https://github.com/godaddy/wp-contact-widgets/blob/master/languages/contact-widgets-sv_SE.po) - [ไทย](https://github.com/godaddy/wp-contact-widgets/blob/master/languages/contact-widgets-th.po) - [Tagalog](https://github.com/godaddy/wp-contact-widgets/blob/master/languages/contact-widgets-tl.po) - [Türkçe](https://github.com/godaddy/wp-contact-widgets/blob/master/languages/contact-widgets-tr_TR.po) - [Українська](https://github.com/godaddy/wp-contact-widgets/blob/master/languages/contact-widgets-uk.po) - [Tiếng Việt](https://github.com/godaddy/wp-contact-widgets/blob/master/languages/contact-widgets-vi.po) - [简体中文](https://github.com/godaddy/wp-contact-widgets/blob/master/languages/contact-widgets-zh_CN.po) - [香港中文版](https://github.com/godaddy/wp-contact-widgets/blob/master/languages/contact-widgets-zh_HK.po) - [繁體中文](https://github.com/godaddy/wp-contact-widgets/blob/master/languages/contact-widgets-zh_TW.po)

**Support**

If you run into a problem, post your question in the [plugin support forum](https://wordpress.org/support/plugin/contact-widgets) and we would be happy to help. Remember, the more information you can provide up-front, the easier it is for us to verify the problem and the faster we can help!

* Screenshot(s) - [How-to guide](https://www.take-a-screenshot.org/)
* Name and version of your theme - [Video tutorial](https://youtu.be/xUVRgorHSyA)
* List of all active plugins on your site - [Video tutorial](https://youtu.be/g3TDziHl7zQ?t=1m51s)
* Steps taken or details we should know to reproduce and verify the problem

**Contributing**

Development of this plugin is done [on GitHub](https://github.com/godaddy/wp-contact-widgets). If you believe you have found a bug, or have a killer feature idea, please [open a new issue](https://github.com/godaddy/wp-contact-widgets/issues) there. Pull requests on existing issues are also welcome!

== Screenshots ==

1. Contact widget
2. Social widget
3. Twenty Sixteen theme showing both widgets
4. Social widget block settings and icons
5. Contact widget block settings
6. Contact widget block rendered

== Frequently Asked Questions ==

= How do I add additional fields to the Contact Information widget? =

Adding additional fields to the Contact Information widget is as simple as adding a WordPress filter.

Here is an example:

<pre lang="php">
add_filter( 'wpcw_widget_contact_custom_fields', function ( $wpcw_fields, $instance ) {

  $wpcw_fields['cellphone'] = [
    'order'       => 2,
    'label'       => __( 'Cellphone:', 'YOURTEXTDOMAIN' ),
    'type'        => 'text',
    'description' => __( 'A cellphone number that website visitors can call if they have questions.', 'YOURTEXTDOMAIN' ),
  ];

  return $wpcw_fields;

}, 10, 2 );
</pre>

= How do I add additional fields to the Social Media Profiles widget? =

The Social Media Profiles widget requires a different set of options but follows the same principle as above.

Here is an example:

<pre lang="php">
add_filter( 'wpcw_widget_social_custom_fields', function ( $wpcw_fields, $instance ) {

  $wpcw_fields['scribd'] = [
    'icon'      => 'scribd', // See font-awesome icon slug
    'label'     => __( 'Scribd', 'YOURTEXTDOMAIN' ),
    'default'   => 'https://www.scribd.com/username',
    'select'    => 'username',
    'sanitizer' => 'esc_url_raw',
    'escaper'   => 'esc_url',
    'social'    => true,
    'target'    => '_blank',
  ];

  return $wpcw_fields;

}, 10, 2 );
</pre>

If using Font Awesome v5, 'solid' & 'regular' icons require a 'prefix' value when defining the custom icon. If excluded, the default prefix added to icons is 'fab', for the Font Awesome brand icons. If you are adding an icon that is not a brand icon, you will need to add a prefix. For example, if you wanted to add a [graduation cap icon](https://fontawesome.com/icons/graduation-cap?style=solid) you would need to add `'prefix' => 'fas'` to the attributes array.

Here is an example of adding a 'fas' (Solid) icon to the social profiles.

<pre lang="php">
add_filter( 'wpcw_widget_social_custom_fields', function ( $wpcw_fields, $instance ) {

  $wpcw_fields['lattes'] = [
    'icon'      => 'graduation-cap', // See font-awesome icon slug
    'prefix'    => 'fas', // See font-awesome icon prefix
    'label'     => __( 'Service Name', 'YOURTEXTDOMAIN' ),
    'default'   => 'https://example.com/username',
    'select'    => 'username',
    'sanitizer' => 'esc_url_raw',
    'escaper'   => 'esc_url',
    'social'    => true,
    'target'    => '_blank',
  ];

  return $wpcw_fields;

}, 10, 2 );
</pre>

= Where are the Font Awesome files served from? =

Out of the box the Font Awesome files are bundled in Contact Widgets and served locally. However, we have included a filter to allow Font Awesome files to be loaded from MaxCDN.

To force the plugin to load the files from MaxCDN you can return a `true` value in the filter `wpcw_social_icons_use_cdn`.

<pre>
add_filter( 'wpcw_social_icons_use_cdn', '__return_true' );
</pre>

== Changelog ==

= 1.7.0 - February 20th, 2018 =
* Fix: WordPress 5.3 compatibility fixes. (Replaced `$` in favor of `jQuery`)
* Tweak: Removed all references of PHP array shorthand syntax. (Replaced `[]` in favor of `array()`)
