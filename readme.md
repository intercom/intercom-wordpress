# Intercom / WordPress

[![Build Status](https://travis-ci.org/intercom/intercom-wordpress.svg?branch=master)](https://travis-ci.org/intercom/intercom-wordpress)

# Compatibility

Requires PHP 5.3 or higher.

# Local Testing

Running tests requires [phpunit](https://phpunit.de/).

```php
INTERCOM_PLUGIN_TEST=1 phpunit
```

# Test the new version of the plugin with Intercom's Wordpress signup flow

It is mandatory that you fully test the [intercom wordpress setup guide](https://app.intercom.io/a/start/wordpress) before you release a new update of the plugin.

# Usage

Installing this plugin provides a new Intercom settings page.
Authenticate with Intercom to retrieve your app_id and secure_mode_secret.
<img src="https://raw.githubusercontent.com/intercom/intercom-wordpress/master/screenshots/settings_not_auth.png"/>

Once authenticated, if you have enabled [Acquire](https://www.intercom.io/live-chat), the Intercom widget will automatically appear on your site.

<img src="https://raw.githubusercontent.com/intercom/intercom-wordpress/master/screenshots/settings_auth.png"/>

NB: This plugin injects a Javascript snippet on your website frontend containing dynamic user data. Some caching solutions will cache entire pages and should not be used with this plugin. Doing so may cause conversations to be delivered to the wrong user.

# Users

If a `$current_user` is present, we use their email as an identifier in the widget.
We recommend to enable the [secure mode](https://docs.intercom.io/configuring-intercom/enable-secure-mode) in the settings page.
Otherwise the widget operates in [Acquire mode](https://www.intercom.io/live-chat) (if available). This allows you to talk with anonymous visitors on your WordPress site.

# Contributing

* Check out the latest master to make sure the feature hasn't been implemented or the bug hasn't been fixed yet.
* Check out the issue tracker to make sure someone already hasn't requested it and/or contributed it.
* Fork the project.
* Start a feature/bugfix branch.
* Commit and push until you are happy with your contribution.
* Make sure to add tests for it. This is important so we don't break it in a future version unintentionally.
