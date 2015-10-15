=== Plugin Name ===
Contributors: bobintercom
License: Apache 2.0
Tags: intercom, customer, chat
Requires at least: 4.2.0
Tested up to: 4.2.4

Official Intercom support for WordPress

== Description ==

With the Intercom plugin for Wordpress, you can chat to both logged-in and anonymous users (using [Acquire](https://www.intercom.io/live-chat)).

Installing this plugin provides a new Intercom settings page, which allows you to configure your app id and secure mode secret. Once filled out, the Intercom widget will automatically appear.

If `current_user` is present, their email will be used as an identifier in the widget. Otherwise, it falls back to anonymous chat using [Acquire](https://www.intercom.io/live-chat).

[View more details](https://github.com/intercom/intercom-wordpress).

== Installation ==

1. Upload `bootstrap.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Enter your app details in the `Intercom` settings page

== Changelog ==

= 1.0.1 =
* First version
