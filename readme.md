# Intercom / WordPress

[![Build Status](https://travis-ci.org/intercom/intercom-wordpress.svg?branch=master)](https://travis-ci.org/intercom/intercom-wordpress)

# Compatibility

Requires PHP 5.6 or higher.

# Local Testing

Running tests requires [phpunit](https://phpunit.de/).

```php
INTERCOM_PLUGIN_TEST=1 phpunit
```

# Test the new version of the plugin with Intercom's Wordpress signup flow

It is mandatory that you fully test the [intercom wordpress setup guide](https://app.intercom.io/a/start/wordpress) before you release a new update of the plugin.

# Usage

Installing this plugin provides a new Intercom settings page.
Authenticate with Intercom to retrieve your app_id and Identity Verification secret.
<img src="https://raw.githubusercontent.com/intercom/intercom-wordpress/master/screenshots/settings_not_auth.png"/>

Once authenticated, the Intercom widget will automatically appear on your site.

<img src="https://raw.githubusercontent.com/intercom/intercom-wordpress/master/screenshots/settings_auth.png"/>

NB: This plugin injects a Javascript snippet on your website frontend containing dynamic user data. Some caching solutions will cache entire pages and should not be used with this plugin. Doing so may cause conversations to be delivered to the wrong user.

# Pass extra parameters to the Intercom Messenger

Using the [Wordpress Hooks API](https://codex.wordpress.org/Plugin_API) `add_filter` method in your Wordpress theme you can pass extra parameters to the Intercom Messenger (see example below):

```php
add_filter( 'intercom_settings', function( $settings ) {                                                  
  $settings['user_id'] = $user_id;                
  return $settings;                                                      
} );
```


# Users

If a `$current_user` is present, we use their email as an identifier in the widget.
We recommend enabling [Identity Verification](https://docs.intercom.com/configure-intercom-for-your-product-or-site/staying-secure/enable-identity-verification-on-your-web-product) in the settings page.

# Contributing

* Check out the latest master to make sure the feature hasn't been implemented or the bug hasn't been fixed yet.
* Check out the issue tracker to make sure someone already hasn't requested it and/or contributed it.
* Fork the project.
* Start a feature/bugfix branch.
* Commit and push until you are happy with your contribution.
* Make sure to add tests for it. This is important so we don't break it in a future version unintentionally.
