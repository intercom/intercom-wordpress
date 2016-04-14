=== Plugin Name ===
Contributors: bobintercom
License: Apache 2.0
Tags: intercom, customer, chat
Requires at least: 4.2.0
Tested up to: 4.2.4

Official Intercom support for WordPress

== Description ==

[Intercom](https://www.intercom.io/) is a fundamentally new way for internet businesses to communicate with customers, personally, at scale. It's a customer communication platform with a suite of integrated products for every team—including sales, marketing, product, and support. Our products enable targeted communication with customers on your website, inside your web and mobile apps, and by email.

Our 4 products are integrated on the [Free Intercom Platform](https://www.intercom.io/customer-intelligence):
- [Acquire](https://www.intercom.io/live-chat): Chat with visitors on your website to help them become customers.
- [Engage](https://www.intercom.io/customer-engagement): Onboard & retain customers with targeted email & in-app messages.
- [Learn](https://www.intercom.io/customer-feedback): Get feedback from the right customers, in-app or by email.
- [Support](https://www.intercom.io/customer-support): Help customers inside your web or mobile app, and by email.

[The Free Intercom Platform](https://www.intercom.io/customer-intelligence) enables allows you to track live customer data, filter and segment customers, and view rich customer profiles.

Full installation and usage instructions are available on the [Intercom website](https://docs.intercom.io/install-on-your-web-product/installing-intercom-on-a-wordpress-site).

**What this plugin does**

With the Intercom plugin for WordPress, you can chat to both logged-in and anonymous users (using [Acquire](https://www.intercom.io/live-chat)).

This plugin injects a Javascript snippet on your website frontend containing dynamic user data. Some caching solutions will cache entire pages and should not be used with this plugin. Doing so may cause conversations to be delivered to the wrong user.

If `current_user` is present, their email will be used as an identifier in the widget. Otherwise, it falls back to anonymous chat using [Acquire](https://www.intercom.io/live-chat).

NB: This plugin injects a Javascript snippet on your website frontend containing dynamic user data. Some caching solutions will cache entire pages and should not be used with this plugin.

[View more details](https://github.com/intercom/intercom-wordpress).

== Installation ==

Requires PHP 5.3 or higher.

1. Upload `bootstrap.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Enter your app details in the `Intercom` settings page
