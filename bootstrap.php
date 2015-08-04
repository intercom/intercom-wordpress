<?php
/*
Plugin Name: Intercom
Plugin URI: https://wordpress.org/plugins/intercom
Description: Official <a href="https://www.intercom.io">Intercom</a> support for Intercom.
Author: Bob Long
Author URI: https://www.intercom.io
Version: 0.1.0
 */

include "src/autoload.php";

function add_intercom_snippet()
{
  $snippet_settings = new SnippetSettings(array("app_id" => "ub5wloc9"));
  $snippet = new Snippet($snippet_settings);
  echo $snippet->html();
}

add_action('wp_footer', 'add_intercom_snippet');
