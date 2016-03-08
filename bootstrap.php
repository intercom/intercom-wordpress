<?php
/*
Plugin Name: Intercom
Plugin URI: https://wordpress.org/plugins/intercom
Description: Official <a href="https://www.intercom.io">Intercom</a> support for WordPress.
Author: Bob Long
Author URI: https://www.intercom.io
Version: 2.4.1
 */

class SecureModeCalculator
{
  private $raw_data = array();
  private $secret_key = "";

  public function __construct($data, $secret_key, $secure_mode)
  {
    $this->raw_data = $data;
    $this->secret_key = $secret_key;
    $this->secure_mode = $secure_mode;
  }

  public function secureModeComponent()
  {
    $secret_key = $this->getSecretKey();
    $secure_mode = $this->getSecureMode();
    if (empty($secret_key) || !$secure_mode)
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

  private function getSecureMode()
  {
    return $this->secure_mode;
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

  public function getAuthUrl() {
    return "https://wordpress_auth.intercom.io/confirm?state=".get_site_url()."::".wp_create_nonce('intercom-oauth');
  }

  public function htmlUnclosed()
  {
    $settings = $this->getSettings();
    $app_id = WordPressEscaper::escAttr($settings['app_id']);
    $secret = WordPressEscaper::escAttr($settings['secret']);
    $secure_mode = WordPressEscaper::escAttr($settings['secure_mode']);
    $auth_url = $this->getAuthUrl();

    if($secure_mode) {
      $secure_mode_state = "checked disabled";
    }
    if (empty($app_id) || empty($secret)) {
      $app_id_row_style = 'display: none;';
      $app_id_link_style = '';
    } else {
      $app_id_row_style = '';
      $app_id_link_style = 'display: none;';
    }
    $dismissable_message = '';
    if ($_GET['saved']) {
      $dismissable_message = $this->dismissibleMessage('Successfully authenticated');
    }
    if ($_GET['enable_secure_mode']) {
      $dismissable_message = $this->dismissibleMessage('Secure Mode successfully enabled');
    }
    $onboarding_markup = $this->getOnboardingLinkIfNoAppId();

    return <<<END

    <link rel="stylesheet" property='stylesheet' href="https://marketing.intercomassets.com/assets/redesign-ead0ee66f7c89e2930e04ac1b7e423494c29e8e681382f41d0b6b8a98b4591e1.css">
    <style>
      #wpcontent {
        background-color: #ffffff;
      }
    </style>

    <div class="wrap">
      $dismissable_message

      <section id="main_content" style="padding-top: 70px;">
        <div class="container">
          <div class="cta">

            <div class="sp__2--lg sp__2--xlg"></div>

            <div id="oauth_content"  style="$app_id_link_style">
              <div class="t__h1 c__red">Get started with Intercom</div>

              <div class="cta__desc">
                Chat with visitors to your website in real-time, capture them as leads, and convert them to customers. Install Intercom on your WordPress site in a couple of clicks.
              </div>

              <div id="get_intercom_btn_container" style="position:relative;margin-top:30px;">
                <a href="$auth_url">
                  <img src="https://static.intercomassets.com/assets/oauth/primary-7edb2ebce84c088063f4b86049747c3a.png" srcset="https://static.intercomassets.com/assets/oauth/primary-7edb2ebce84c088063f4b86049747c3a.png 1x, https://static.intercomassets.com/assets/oauth/primary@2x-0d69ca2141dfdfa0535634610be80994.png 2x, https://static.intercomassets.com/assets/oauth/primary@3x-788ed3c44d63a6aec3927285e920f542.png 3x"/>
                </a>
              </div>
              $onboarding_markup
            </div>

            <div id="app_id_and_secret_content" style="$app_id_row_style">
              <div class="t__h1 c__red">Intercom has been installed</div>

              <div class="cta__desc">
                Intercom is now set up and ready to go. You can now chat with your existing and potential new customers, send them targeted messages, and get feedback.
                <br/>
                <br/>
                <a href="https://app.intercom.io/a/apps/$app_id" target="_blank">Click here to access your Intercom Team Inbox.</a>
                <br/>
                <br/>
                Need help? <a href="https://docs.intercom.io/for-converting-visitors-to-users" target="_blank">Visit our documentation</a> for best practices, tips, and much more.
                <br/>
                <br/>

                <div>
                  <div style="font-size:0.87em">
                  Learn more about our products : <a href="https://www.intercom.io/live-chat"target="_blank">Acquire</a>, <a href="https://www.intercom.io/customer-engagement" target="_blank">Engage</a>, <a href="https://www.intercom.io/customer-feedback"  target="_blank">Learn</a> and <a href="https://www.intercom.io/customer-support"  target="_blank">Support</a>.
                  </div>
                  <form method="post" action="" name="enable_secure_mode">
                    <table class="form-table" align="center" style="margin-top: 16px; width: inherit;">
                      <tbody>
                        <tr>
                          <th scope="row" style="text-align: center; vertical-align: middle;"><label for="intercom_app_id">App ID</label></th>
                          <td><input id="intercom_app_id" disabled name="intercom[app_id]" type="text" value="$app_id" placeholder="App ID"></td>
                        </tr>
                        <tr id="intercom_secure_mode">
                          <th scope="row" style="text-align: center; vertical-align: middle;"><label for="intercom_secure">Secure Mode</label></th>
                          <td><input id="intercom-secure-mode" name="enable_secure_mode" type="checkbox" $secure_mode_state></td>
                        </tr>
                      </tbody>
                    </table>

END;
  }

  public function htmlClosed()
  {
    $auth_url = $this->getAuthUrl();
    return <<<END
                  </form>
                  <p style="font-size:0.86em">Secure mode allows you to make sure that conversations between you and your users are kept private.<br/>
                    Once you enabled secure mode you cannot disable it.<br/>
                    <a href="https://docs.intercom.io/configuring-intercom/enable-secure-mode" target="_blank">Learn more about Secure Mode</a>
                  </p>
                  <br/>
                  <div style="font-size:0.8em">If the intercom application assiocated with your store is incorrect, please <a href="$auth_url">click here</a> to reconnect with Intercom, to choose a new application.</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
    <script src="https://code.jquery.com/jquery-2.2.0.min.js"></script>
    <script type="text/javascript">
      $('#intercom-secure-mode').unbind('click').click(function() {
        $('#intercom-secure-mode').prop('checked', false);
        if(confirm('Are you sure you want to enable secure mode for Intercom ?'))  {
          $('#intercom-secure-mode').prop('value', true);
          $('#intercom-secure-mode').prop('checked', true);
          $('form[name="enable_secure_mode"]').submit();
        }
      });
    </script>
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
    return $this->shutdown_on_logout() . $this->source();
  }


    private function shutdown_on_logout()
    {
      return <<<HTML
<script data-cfasync="false">
  var logout_link = document.querySelectorAll('a[href*="wp-login.php?action=logout"]');
  if (logout_link) {
    for(var i=0; i < logout_link.length; i++) {
      logout_link[i].addEventListener( "click", function() {
        Intercom('shutdown');
      });
    }
  }
</script>

HTML;
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

  public function __construct($raw_data, $secret = NULL, $secure_mode = false, $wordpress_user = NULL, $constants = array('ICL_LANGUAGE_CODE' => 'language_override'))
  {
    $this->raw_data = $this->validateRawData($raw_data);
    $this->secret = $secret;
    $this->secure_mode = $secure_mode;
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
    $user = new IntercomUser($this->wordpress_user, $this->raw_data);
    $settings = $user->buildSettings();
    $secureModeCalculator = new SecureModeCalculator($settings, $this->secret, $this->secure_mode);
    $result = array_merge($settings, $secureModeCalculator->secureModeComponent());
    $result = $this->mergeConstants($result);
    return $result;
  }

  private function mergeConstants($settings) {
    foreach($this->constants as $key => $value) {
      if (defined($key)) {
        $const_val = WordPressEscaper::escJS(constant($key));
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

class WordPressEscaper
{
  public static function escAttr($value)
  {
    if (function_exists('esc_attr')) {
      return esc_attr($value);
    } else {
      if (getenv('INTERCOM_PLUGIN_TEST') == '1') {
        return $value;
      }
    }
  }

  public static function escJS($value)
  {
    if (function_exists('esc_js')) {
      return esc_js($value);
    } else {
      if (getenv('INTERCOM_PLUGIN_TEST') == '1') {
        return $value;
      }
    }
  }
}

class IntercomUser
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
      $this->settings["email"] = WordPressEscaper::escJS($this->wordpress_user->user_email);
    }
    if (!empty($this->wordpress_user->display_name))
    {
      $this->settings["name"] = WordPressEscaper::escJS($this->wordpress_user->display_name);
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

if (getenv('INTERCOM_PLUGIN_TEST') != '1') {
  if (!defined('ABSPATH')) exit;
}

function add_intercom_snippet()
{
  $options = get_option('intercom');
  $snippet_settings = new SnippetSettings(
    array("app_id" => WordPressEscaper::escJS($options['app_id'])),
    WordPressEscaper::escJS($options['secret']),
    WordPressEscaper::escJS($options['secure_mode']),
    wp_get_current_user()
  );
  $snippet = new Snippet($snippet_settings);
  echo $snippet->html();
}

function add_intercom_settings_page()
{
  add_options_page(
    'Intercom Settings',
    'Intercom',
    'manage_options',
    'intercom',
    'render_intercom_options_page'
  );
}

function render_intercom_options_page()
{
  if (!current_user_can('manage_options'))
  {
    wp_die('You do not have sufficient permissions to access Intercom settings');
  }
  $options = get_option('intercom');
  $settings_page = new SettingsPage(array("app_id" => $options['app_id'], "secret" => $options['secret'], "secure_mode" => $options['secure_mode']));
  echo $settings_page->htmlUnclosed();
  wp_nonce_field('intercom-update');
  echo $settings_page->htmlClosed();
}

function intercom_settings() {
  register_setting('intercom', 'intercom');
  if (isset($_GET['state']) && wp_verify_nonce($_GET[ 'state'], 'intercom-oauth') && current_user_can('manage_options') && isset($_GET['app_id']) && isset($_GET['secret']) ) {
    $validator = new Validator($_GET, function($x) { return wp_kses(trim($x), array()); });
    update_option("intercom", array("app_id" => $validator->validAppId(), "secret" => $validator->validSecret(), "secure_mode" => false));
    wp_safe_redirect(admin_url('options-general.php?page=intercom&saved=1'));
  }
  if ( current_user_can('manage_options') &&  wp_verify_nonce($_POST[ '_wpnonce'],'intercom-update') && isset($_POST['enable_secure_mode'])) {
    $options = get_option('intercom');
    $options["secure_mode"] = true;
    update_option("intercom", $options);
  }
}
// Enable Secure Mode for customers who already copy/pasted their secret_key before the Oauth2 release.
function patch_oauth() {
  $options = get_option('intercom');
  if ($options["secret"] && !isset($options["secure_mode"])) {
    $options["secure_mode"] = true;
    update_option("intercom", $options);
  }
}

if (getenv('INTERCOM_PLUGIN_TEST') != '1') {
  add_action('wp_footer', 'add_intercom_snippet');
  add_action('admin_menu', 'add_intercom_settings_page');
  add_action('network_admin_menu', 'add_intercom_settings_page');
  add_action('admin_init', 'patch_oauth');
  add_action('admin_init', 'intercom_settings');
}
