=== Intercom ===
Contributors: bobintercom, jacopointercom
Tags: intercom, ai, customer, chat
Requires at least: 4.7.0
Tested up to: 6.7.2
Requires PHP: 7.2
License: Apache 2.0
Stable tag: 3.0.2

Official Intercom WordPress plugin: Engage visitors in real time, power growth with AI, and convert leads into loyal customers.

== Description ==

[Intercom](https://www.intercom.com/) is a next-generation customer communications platform that combines powerful live chat, proactive messaging, and advanced AI solutions — like our Fin AI chatbot — to help businesses instantly connect with customers.

By installing the Intercom WordPress plugin, you can seamlessly add the Messenger to your site, track both logged-in users and visitors, and engage them right away. With Intercom’s industry-leading AI at your fingertips, you’ll deliver fast, personalized support and drive growth more effectively than ever before.

== Installation ==

Installing Intercom on your WordPress site takes just a few minutes.

You can find full instructions on signing up and installing Intercom using the WordPress plugin [here](https://www.intercom.com/help/en/articles/173-install-intercom-on-your-wordpress-site).

If you’re already an Intercom customer, you can also find instructions in the in-app [setup guide](https://app.intercom.com/a/apps/_/platform/guide) or [app store](https://app.intercom.com/a/apps/_/appstore?app_package_code=wordpress&search=wordpress).

The first thing you’ll need to do is install and activate the plugin - you must be using WordPress v4.9.0 or higher and have the ability to install plugins in order to use this method.

Note: This plugin injects a Javascript snippet on your website frontend containing dynamic user data. Some caching solutions will cache entire pages and should not be used with this plugin. Doing so may cause conversations to be delivered to the wrong user.

== Screenshots ==
1. Plugin settings authenticate with Intercom settings_not_auth.png
2. Plugin settings successfully authenticated with Intercom settings_auth.png
3. Intercom widget used by customers to communicate with the business widget.png
== Changelog ==
= 3.0.2 =

https://github.com/intercom/intercom-wordpress/pull/131
* Updated version attribute to avoid it getting sent in the user data.

= 3.0.1 =

https://github.com/intercom/intercom-wordpress/pull/130
* Added version tracking to help Intercom provide better support by identifying which plugin version is in use.

https://github.com/intercom/intercom-wordpress/pull/88
* Loads Intercom messenger last to ensure JQuery is present. 

= 3.0.0 =

https://github.com/intercom/intercom-wordpress/pull/127
* Replaced user_hash with intercom_user_jwt https://www.intercom.com/help/en/articles/10589769-authenticating-users-in-the-messenger-with-json-web-tokens-jwts.
* Updated readme to follow guidelines.
* Added missing tests.

== Upgrade Notice ==
= 3.0.1 =
Updated version attribute name to avoid setting it as part of user data.

= 3.0.1 =
Help Intercom provide better support by sharing the plugin version is in use.

= 3.0.0 =
Upgrade the security of your messenger with the introduction of JWT - https://www.intercom.com/help/en/articles/10589769-authenticating-users-in-the-messenger-with-json-web-tokens-jwts
