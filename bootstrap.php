<?php
/*
Plugin Name: Intercom
Plugin URI: https://wordpress.org/plugins/intercom
Description: Official <a href="https://www.intercom.io">Intercom</a> support for Wordpress.
Author: Bob Long
Author URI: https://www.intercom.io
Version: 2.2.1
 */

class SecureModeCalculator
{
  private $raw_data = array();
  private $secret_key = "";

  public function __construct($data, $secret_key)
  {
    $this->raw_data = $data;
    $this->secret_key = $secret_key;
  }

  public function secureModeComponent()
  {
    $secret_key = $this->getSecretKey();
    if (empty($secret_key))
    {
      return $this->emptySecureModeHashComponent();
    }
    if (array_key_exists("user_id", $this->getRawData()))
    {
      return $this->secureModeHashComponent("user_id");
    }
    if (array_key_exists("email", $this->getRawData()))
    {
      return $this->secureModeHashComponent("email");
    }
    return $this->emptySecureModeHashComponent();
  }

  private function emptySecureModeHashComponent()
  {
    return array();
  }

  private function secureModeHashComponent($key)
  {
    $raw_data = $this->getRawData();
    return array("user_hash" => hash_hmac("sha256", $raw_data[$key], $this->getSecretKey()));
  }

  private function getSecretKey()
  {
    return $this->secret_key;
  }

  private function getRawData()
  {
    return $this->raw_data;
  }
}

class SettingsPage
{
  private $settings = array();

  public function __construct($settings)
  {
    $this->settings = $settings;
  }

  public function dismissibleMessage($text)
  {
    return <<<END
  <div id="message" class="updated notice is-dismissible">
    <p>$text</p>
    <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
  </div>
END;
  }

  public function htmlUnclosed()
  {
    $settings = $this->getSettings();
    $app_id = WordpressEscaper::escAttr($settings['app_id']);
    $secret = WordpressEscaper::escAttr($settings['secret']);

    if (empty($secret)) {
      $secret_row_style = 'display: none;';
      $secret_link_style = '';
    } else {
      $secret_row_style = '';
      $secret_link_style = 'display: none;';
    }

    $dismissable_message = '';
    if ($_GET['saved']) {
      $dismissable_message = $this->dismissibleMessage('App ID saved.');
    }

    if ($_GET['appId']) {
      $app_id = WordpressEscaper::escAttr($_GET['appId']);
      $dismissable_message = $this->dismissibleMessage('We’ve copied your new Intercom app ID below. Click to save changes and then close this window to finish signing up for Intercom.');
    }

    return <<<END
<div class="wrap">
<h1>Intercom Settings</h1>
  $dismissable_message
  <form method="post" action="">
    <table class="form-table">
      <tbody>
        <tr>
          <th scope="row"><label for="intercom_app_id">App ID</label></th>
          <td><input id="intercom_app_id" name="intercom[app_id]" type="text" value="$app_id" placeholder="App ID"></td>
        </tr>
        <tr id="intercom_secret_key_row" style="$secret_row_style">
          <th scope="row"><label for="intercom_secret">Secret Key (optional)</label></th>
          <td><input id="intercom_secret" name="intercom[secret]" type="text" value="$secret" placeholder="Secret Key"></td>
        </tr>
      </tbody>
    </table>
    <p class="submit">
      <input name="intercom-submit" type="submit" value="Save Changes" class="button button-primary">
      <a id="intercom_secret_key_show_link" style="$secret_link_style margin-left: 20px" href="javascript: jQuery('#intercom_secret_key_row').show(); jQuery('#intercom_secret_key_show_link').hide(); jQuery('#intercom_secret').focus();">Add your Intercom secret key (optional)</a>
    </p>
END;
  }

  public function htmlClosed()
  {
    $onboarding_markup = $this->getOnboardingLinkIfNoAppId();
    return <<<END

  </form>$onboarding_markup
</div>
END;
  }

  public function html()
  {
    return $this->htmlUnclosed() . $this->htmlClosed();
  }

  private function getSettings()
  {
    return $this->settings;
  }

  private function getOnboardingLinkIfNoAppId()
  {
    $settings = $this->getSettings();
    $app_id = $settings['app_id'];
    if(!$app_id) {
      return '<p>Need an Intercom account? <a target="_blank" href="https://app.intercom.io/a/get_started/add_people?signupMethod=integrate&userSource=wordpress">Get started</a>.</p>';
    } else {
      return '';
    }
  }
}

class Snippet
{
  private $snippet_settings = "";

  public function __construct($snippet_settings)
  {
    $this->snippet_settings = $snippet_settings;
  }
  public function html()
  {
    return $this->source();
  }

  private function source()
  {
    $snippet_json = $this->snippet_settings->json();
    $app_id = $this->snippet_settings->appId();

    return <<<HTML
<script data-cfasync="false">
  window.intercomSettings = $snippet_json;
</script>
<script data-cfasync="false">(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/$app_id';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()</script>
HTML;
  }
}

class SnippetSettings
{
  private $raw_data = array();
  private $secret = NULL;
  private $wordpress_user = NULL;

  public function __construct($raw_data, $secret = NULL, $wordpress_user = NULL, $constants = array('ICL_LANGUAGE_CODE' => 'language_override'))
  {
    $this->raw_data = $this->validateRawData($raw_data);
    $this->secret = $secret;
    $this->wordpress_user = $wordpress_user;
    $this->constants = $constants;
  }

  public function json()
  {
    return json_encode($this->getRawData());
  }

  public function appId()
  {
    $raw_data = $this->getRawData();
    return $raw_data["app_id"];
  }

  private function getRawData()
  {
    $user = new User($this->wordpress_user, $this->raw_data);
    $settings = $user->buildSettings();
    $secureModeCalculator = new SecureModeCalculator($settings, $this->secret);
    $result = array_merge($settings, $secureModeCalculator->secureModeComponent());
    $result = $this->mergeConstants($result);
    return $result;
  }

  private function mergeConstants($settings) {
    foreach($this->constants as $key => $value) {
      if (defined($key)) {
        $const_val = WordpressEscaper::escJS(constant($key));
        $settings = array_merge($settings, array($value => $const_val));
      }
    }
    return $settings;
  }

  private function validateRawData($raw_data)
  {
    if (!array_key_exists("app_id", $raw_data)) {
      throw new Exception("app_id is required");
    }
    return $raw_data;
  }
}

class WordpressEscaper
{
  public static function escAttr($value)
  {
    if (function_exists('esc_attr')) {
      return esc_attr($value);
    } else {
      return $value;
    }
  }

  public static function escJS($value)
  {
    if (function_exists('esc_js')) {
      return esc_js($value);
    } else {
      return $value;
    }
  }
}

class User
{
  private $wordpress_user = NULL;
  private $settings = array();

  public function __construct($wordpress_user, $settings)
  {
    $this->wordpress_user = $wordpress_user;
    $this->settings = $settings;
  }

  public function buildSettings()
  {
    if (empty($this->wordpress_user))
    {
      return $this->settings;
    }
    if (!empty($this->wordpress_user->user_email))
    {
      $this->settings["email"] = WordpressEscaper::escJS($this->wordpress_user->user_email);
    }
    return $this->settings;
  }
}

class Validator
{
  private $inputs = array();
  private $validation;

  public function __construct($inputs, $validation)
  {
    $this->input = $inputs;
    $this->validation = $validation;
  }

  public function validAppId()
  {
    return $this->validate($this->input["app_id"]);
  }

  public function validSecret()
  {
    return $this->validate($this->input["secret"]);
  }

  private function validate($x)
  {
    return call_user_func($this->validation, $x);
  }
}

if (getenv('TEST') != '1') {
  if (!defined('ABSPATH')) exit;

  define( 'WP_DEBUG', true );
  define( 'WP_DEBUG_DISPLAY', false );
  define( 'WP_DEBUG_LOG', true );
}

function add_intercom_snippet()
{
  $options = get_option('intercom');
  $snippet_settings = new SnippetSettings(
    array("app_id" => WordpressEscaper::escJS($options['app_id'])),
    WordpressEscaper::escJS($options['secret']),
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
  if (!current_user_can('manage_options'))
  {
    wp_die('You do not have sufficient permissions to access Intercom settings');
  }
  $options = get_option('intercom');
  $settings_page = new SettingsPage(array("app_id" => $options['app_id'], "secret" => $options['secret']));
  echo $settings_page->htmlUnclosed();
  wp_nonce_field('intercom-update');
  echo $settings_page->htmlClosed();
}

function settings() {
  register_setting('intercom', 'intercom');
  if (isset($_POST['_wpnonce']) and wp_verify_nonce($_POST[ '_wpnonce'], 'intercom-update')
      and isset($_POST[ 'intercom-submit' ] ) and current_user_can('manage_options')) {
    $validator = new Validator($_POST["intercom"], function($x) { return wp_kses(trim($x), array()); });
    update_option("intercom", array("app_id" => $validator->validAppId(), "secret" => $validator->validSecret()));
    wp_safe_redirect(admin_url('options-general.php?page=intercom&saved=1'));
  }
}

if (getenv('TEST') != '1') {
  add_action('wp_footer', 'add_intercom_snippet');
  add_action('admin_menu', 'add_settings_page');
  add_action('network_admin_menu', 'add_settings_page');
  add_action('admin_init', 'settings');
}
