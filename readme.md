# Intercom / Wordpress

[![Build Status](https://travis-ci.org/intercom/intercom-wordpress.svg?branch=master)](https://travis-ci.org/intercom/intercom-wordpress)

# Beta

This plugin for Wordpress is in active development.

# Local Testing

Running tests requires [phpunit](https://phpunit.de/).

```php
TEST=1 phpunit
```

# Usage

Installing this plugin provides a new Intercom settings page, which allows you to configure your app id and secure mode secret:

<img src="https://raw.githubusercontent.com/intercom/intercom-wordpress/master/screenshots/settings.png"/>

Once filled out, the Intercom widget will automatically appear:

<img src="https://raw.githubusercontent.com/intercom/intercom-wordpress/master/screenshots/widget.png"/>

# Users

If a `$current_user` is present, we use their email as an identifier in the widget.

Otherwise the widget operates in [Acquire mode](https://www.intercom.io/live-chat) (if available). This allows you to talk with anonymous visitors on your Wordpress site.

# Contributing

* Check out the latest master to make sure the feature hasn't been implemented or the bug hasn't been fixed yet.
* Check out the issue tracker to make sure someone already hasn't requested it and/or contributed it.
* Fork the project.
* Start a feature/bugfix branch.
* Commit and push until you are happy with your contribution.
* Make sure to add tests for it. This is important so we don't break it in a future version unintentionally.
