# Intercom / WordPress

[![Build Status](https://travis-ci.org/intercom/intercom-wordpress.svg?branch=master)](https://travis-ci.org/intercom/intercom-wordpress)

# Compatibility

Requires PHP 7.2 or higher.

# Local Testing

Running tests requires [phpunit](https://phpunit.de/).

```php
INTERCOM_PLUGIN_TEST=1 phpunit
```

# Usage

Installing this plugin provides a new Intercom settings page.
Authenticate with Intercom to retrieve your app_id and Identity Verification secret.
<img src="https://raw.githubusercontent.com/intercom/intercom-wordpress/master/screenshots/settings_not_auth.png"/>

Once authenticated, the Intercom widget will automatically appear on your site.

<img src="https://raw.githubusercontent.com/intercom/intercom-wordpress/master/screenshots/settings_auth.png"/>

NB: This plugin injects a Javascript snippet on your website frontend containing dynamic user data. Some caching solutions will cache entire pages and should not be used with this plugin. Doing so may cause conversations to be delivered to the wrong user.

# Pass custom data attributes to the Intercom Messenger

Using the [add_filter](https://developer.wordpress.org/reference/functions/add_filter) method in your WordPress theme or custom plugin you can pass [custom data attributes](https://www.intercom.com/help/en/articles/179-create-and-track-custom-data-attributes-cdas) to the Intercom Messenger (see example below):

```php
add_filter( 'intercom_settings', function( $settings ) {
  $settings['customer_type'] = $customer_type;
  return $settings;
} );
```


# Users

If a `$current_user` is present, we use their email and ID as an identifier in the widget.
We recommend enabling [Identity Verification](https://docs.intercom.com/configure-intercom-for-your-product-or-site/staying-secure/enable-identity-verification-on-your-web-product) in the settings page.

# Contributing

* Check out the latest master to make sure the feature hasn't been implemented or the bug hasn't been fixed yet.
* Check out the issue tracker to make sure someone already hasn't requested it and/or contributed it.
* Fork the project.
* Start a feature/bugfix branch.
* Commit and push until you are happy with your contribution.
* Make sure to add tests for it. This is important so we don't break it in a future version unintentionally.
