<?php
/*
Plugin Name: Intercom
Plugin URI: https://wordpress.org/plugins/intercom
Description: Official <a href="https://www.intercom.io">Intercom</a> support for WordPress.
Author: Intercom
Author URI: https://www.intercom.io
Version: 2.6.1
 */

class IdentityVerificationCalculator
{
  private $raw_data = array();
  private $secret_key = "";

  public function __construct($data, $secret_key)
  {
    $this->raw_data = $data;
    $this->secret_key = $secret_key;
  }

  public function identityVerificationComponent()
  {
    $secret_key = $this->getSecretKey();
    if (empty($secret_key))
    {
      return $this->emptyIdentityVerificationHashComponent();
    }
    if (array_key_exists("user_id", $this->getRawData()))
    {
      return $this->identityVerificationHashComponent("user_id");
    }
    if (array_key_exists("email", $this->getRawData()))
    {
      return $this->identityVerificationHashComponent("email");
    }
    return $this->emptyIdentityVerificationHashComponent();
  }

  private function emptyIdentityVerificationHashComponent()
  {
    return array();
  }

  private function identityVerificationHashComponent($key)
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

class IntercomSettingsPage
{
  private $settings = array();
  private $styles = array();

  public function __construct($settings)
  {
    $this->settings = $settings;
    $this->styles = $this->setStyles($settings);
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
    $styles = $this->getStyles();
    $app_id = WordPressEscaper::escAttr($settings['app_id']);
    $secret = WordPressEscaper::escAttr($settings['secret']);
    $auth_url = $this->getAuthUrl();
    $dismissable_message = '';
    if (isset($_GET['appId'])) {
      // Copying app_id from setup guide
      $app_id = WordPressEscaper::escAttr($_GET['appId']);
      $dismissable_message = $this->dismissibleMessage("We've copied your new Intercom app id below. click to save changes and then close this window to finish signing up for Intercom.");
    }
    if (isset($_GET['saved'])) {
      $dismissable_message = $this->dismissibleMessage("Your app id has been successfully saved. You can now close this window to finish signing up for Intercom.");
    }
    if (isset($_GET['authenticated'])) {
      $dismissable_message = $this->dismissibleMessage('You successfully authenticated with Intercom');
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
            <div id="oauth_content" style="$styles[app_id_link_style]">
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

            <div class="t__h1 c__red" style="$styles[app_id_copy_title]">Intercom setup</div>
            <div class="t__h1 c__red" style="$styles[app_id_saved_title]">Intercom app ID saved</div>
            <div id="app_id_and_secret_content" style="$styles[app_id_row_style]">
              <div class="t__h1 c__red" style="$styles[app_id_copy_hidden]">Intercom has been installed</div>

              <div class="cta__desc">
                <div style="$styles[app_id_copy_hidden]">
                  Intercom is now set up and ready to go. You can now chat with your existing and potential new customers, send them targeted messages, and get feedback.
                  <br/>
                  <br/>
                  <a class="c__blue" href="https://app.intercom.io/a/apps/$app_id" target="_blank">Click here to access your Intercom Team Inbox.</a>
                  <br/>
                  <br/>
                  Need help? <a class="c__blue" href="https://docs.intercom.io/for-converting-visitors-to-users" target="_blank">Visit our documentation</a> for best practices, tips, and much more.
                  <br/>
                  <br/>
                </div>

                <div>
                  <div style="font-size:0.87em;$styles[app_id_copy_hidden]">
                  Learn more about our products : <a class="c__blue" href="https://www.intercom.com/customer-engagement" target="_blank">Messages</a>, <a class="c__blue" href="https://www.intercom.com/customer-support-software/knowledge-base" target="_blank">Articles</a> and <a class="c__blue" href="https://www.intercom.com/customer-support-software/help-desk" target="_blank">Inbox</a>.
                  </div>
                  <form method="post" action="" name="update_settings">
                    <table class="form-table" align="center" style="margin-top: 16px; width: inherit;">
                      <tbody>
                        <tr>
                          <th scope="row" style="text-align: center; vertical-align: middle;"><label for="intercom_app_id">App ID</label></th>
                          <td>
                            <input id="intercom_app_id" $styles[app_id_state] name="app_id" type="text" value="$app_id" class="$styles[app_id_class]">
                            <button type="submit" class="btn btn__primary cta__submit" style="$styles[button_submit_style]">Save</button>
                          </td>
                        </tr>
                      </tbody>
                    </table>

END;
  }

  public function htmlClosed()
  {
    $settings = $this->getSettings();
    $styles = $this->getStyles();
    $auth_url = $this->getAuthUrl();
    $secret = WordPressEscaper::escAttr($settings['secret']);
    $app_id = WordPressEscaper::escAttr($settings['app_id']);
    $auth_url_identity_verification = "";
    if (empty($secret) && !empty($app_id)) {
      $auth_url_identity_verification = $auth_url.'&enable_identity_verification=1';
    }
    return <<<END
                  </form>
                  <div style="$styles[app_id_copy_hidden]">
                    <div style="$styles[app_secret_link_style]">
                      <a class="c__blue" href="$auth_url_identity_verification">Authenticate with your Intercom application to enable Identity Verification</a>
                    </div>
                    <p style="font-size:0.86em">Identity Verification ensures that conversations between you and your users are kept private.<br/>
                    <br/>
                      <a class="c__blue" href="https://docs.intercom.com/configure-intercom-for-your-product-or-site/staying-secure/enable-identity-verification-on-your-web-product" target="_blank">Learn more about Identity Verification</a>
                    </p>
                    <br/>
                    <div style="font-size:0.8em">If the Intercom application associated with your Wordpress is incorrect, please <a class="c__blue" href="$auth_url">click here</a> to reconnect with Intercom, to choose a new application.</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
    <script src="https://code.jquery.com/jquery-2.2.0.min.js"></script>
END;
  }

  public function html()
  {
    return $this->htmlUnclosed() . $this->htmlClosed();
  }

  public function setStyles($settings) {
    $styles = array();
    $app_id = WordPressEscaper::escAttr($settings['app_id']);
    $secret = WordPressEscaper::escAttr($settings['secret']);
    $identity_verification = WordPressEscaper::escAttr($settings['identity_verification']);

    // Use Case : Identity Verification enabled : checkbox checked and disabled
    if($identity_verification) {
      $styles['identity_verification_state'] = 'checked disabled';
    } else {
      $styles['identity_verification_state'] = '';
    }

    // Use Case : app_id here but Identity Verification disabled
    if (empty($secret) && !empty($app_id)) {
      $styles['app_secret_row_style'] = 'display: none;';
      $styles['app_secret_link_style'] = '';
    } else {
      $styles['app_secret_row_style'] = '';
      $styles['app_secret_link_style'] = 'display: none;';
    }

    // Copying appId from Intercom Setup Guide for validation
    if (isset($_GET['appId'])) {
        $app_id = WordPressEscaper::escAttr($_GET['appId']);
        $styles['app_id_state'] = 'readonly';
        $styles['app_id_class'] = "cta__email";
        $styles['button_submit_style'] = '';
        $styles['app_id_copy_hidden'] = 'display: none;';
        $styles['app_id_copy_title'] = '';
        $styles['identity_verification_state'] = 'disabled'; # Prevent from sending POST data about identity_verification when using app_id form
    } else {
      $styles['app_id_class'] = "";
      $styles['button_submit_style'] = 'display: none;';
      $styles['app_id_copy_title'] = 'display: none;';
      $styles['app_id_state'] = 'disabled'; # Prevent from sending POST data about app_id when using identity_verification form
      $styles['app_id_copy_hidden'] = '';
    }

    //Use Case App_id successfully copied
    if (isset($_GET['saved'])) {
      $styles['app_id_copy_hidden'] = 'display: none;';
      $styles['app_id_saved_title'] = '';
    } else {
      $styles['app_id_saved_title'] = 'display: none;';
    }

    // Display 'connect with intercom' button if no app_id provided (copied from setup guide or from Oauth)
    if (empty($app_id)) {
      $styles['app_id_row_style'] = 'display: none;';
      $styles['app_id_link_style'] = '';
    } else {
      $styles['app_id_row_style'] = '';
      $styles['app_id_link_style'] = 'display: none;';
    }
    return $styles;
  }

  private function getSettings()
  {
    return $this->settings;
  }

  private function getStyles()
  {
    return $this->styles;
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

class IntercomSnippet
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
  document.onreadystatechange = function () {
    if (document.readyState == "complete") {
      var logout_link = document.querySelectorAll('a[href*="wp-login.php?action=logout"]');
      if (logout_link) {
        for(var i=0; i < logout_link.length; i++) {
          logout_link[i].addEventListener( "click", function() {
            Intercom('shutdown');
          });
        }
      }
    }
  };
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

class IntercomSnippetSettings
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
    return json_encode(apply_filters("intercom_settings", $this->getRawData()));
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
    $identityVerificationCalculator = new IdentityVerificationCalculator($settings, $this->secret);
    $result = array_merge($settings, $identityVerificationCalculator->identityVerificationComponent());
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

if (getenv('INTERCOM_PLUGIN_TEST') == '1' && !function_exists('apply_filters')) {
  function apply_filters($_, $value) {
    return $value;
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
  $snippet_settings = new IntercomSnippetSettings(
    array("app_id" => WordPressEscaper::escJS($options['app_id'])),
    WordPressEscaper::escJS($options['secret']),
    wp_get_current_user()
  );
  $snippet = new IntercomSnippet($snippet_settings);
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
  $settings_page = new IntercomSettingsPage(array("app_id" => $options['app_id'], "secret" => $options['secret']));
  echo $settings_page->htmlUnclosed();
  wp_nonce_field('intercom-update');
  echo $settings_page->htmlClosed();
}

function intercom_settings() {
  register_setting('intercom', 'intercom');
  if (isset($_GET['state']) && wp_verify_nonce($_GET[ 'state'], 'intercom-oauth') && current_user_can('manage_options') && isset($_GET['app_id']) && isset($_GET['secret']) ) {
    $validator = new Validator($_GET, function($x) { return wp_kses(trim($x), array()); });
    update_option("intercom", array("app_id" => $validator->validAppId(), "secret" => $validator->validSecret()));
    $redirect_to = 'options-general.php?page=intercom&authenticated=1';
    wp_safe_redirect(admin_url($redirect_to));
  }
  if (current_user_can('manage_options') && isset($_POST['app_id']) && isset($_POST[ '_wpnonce']) && wp_verify_nonce($_POST[ '_wpnonce'],'intercom-update')) {
      $options = array();
      $options["app_id"] = WordPressEscaper::escAttr($_POST['app_id']);
      update_option("intercom", $options);
      wp_safe_redirect(admin_url('options-general.php?page=intercom&saved=1'));
  }
}

if (getenv('INTERCOM_PLUGIN_TEST') != '1') {
  add_action('wp_footer', 'add_intercom_snippet');
  add_action('admin_menu', 'add_intercom_settings_page');
  add_action('network_admin_menu', 'add_intercom_settings_page');
  add_action('admin_init', 'intercom_settings');
}
