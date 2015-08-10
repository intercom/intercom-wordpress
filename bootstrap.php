<?php
/*
Plugin Name: Intercom
Plugin URI: https://wordpress.org/plugins/intercom
Description: Official <a href="https://www.intercom.io">Intercom</a> support for Intercom.
Author: Bob Long
Author URI: https://www.intercom.io
Version: 0.1.0
 */

if (!defined('ABSPATH')) exit;

define( 'WP_DEBUG', true );
define( 'WP_DEBUG_DISPLAY', false );
define( 'WP_DEBUG_LOG', true );

include "src/autoload.php";

function add_intercom_snippet()
{
  $snippet_settings = new SnippetSettings(
    array("app_id" => get_option('intercom-app-id')),
    get_option("intercom-secret"),
    wp_get_current_user()
  );
  $snippet = new Snippet($snippet_settings);
  echo $snippet->html();
}

function add_settings_page()
{
  add_options_page(
    'Intercom Settings',
    'Intercom',
    'manage_options',
    'intercom',
    'render_options_page'
  );
}

function render_options_page()
{
  $settings_page = new SettingsPage(array("app_id" => get_option('intercom-app-id'), "secret" => get_option('intercom-secret')));
  echo $settings_page->css();
  echo $settings_page->htmlUnclosed();
  wp_nonce_field('intercom-update');
  echo $settings_page->htmlClosed();
}

function settings() {
  register_setting('intercom', 'intercom');
  if (isset($_POST['_wpnonce']) and wp_verify_nonce($_POST[ '_wpnonce'], 'intercom-update')
      and isset($_POST[ 'intercom-submit' ] ) and is_admin()) {
    $validator = new Validator($_POST["intercom"], function($x) { return wp_kses(trim($x)); });
    update_option("intercom-app-id", $validator->validAppId());
    update_option("intercom-secret", $validator->validSecret());
    wp_safe_redirect(wp_get_referer());
  }
}

add_action('wp_footer', 'add_intercom_snippet');
add_action('admin_menu', 'add_settings_page');
add_action('network_admin_menu', 'add_settings_page');
add_action('admin_init', 'settings');
