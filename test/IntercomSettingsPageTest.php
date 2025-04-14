<?php
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class IntercomSettingsPageTest extends TestCase
{
  private $settings;
  private $intercomSettingsPage;
  private $originalGet;

  protected function setUp(): void
  {
    parent::setUp();
    
    // Store original $_GET
    $this->originalGet = $_GET;
    
    // Default settings
    $this->settings = [
      'app_id' => 'test_app_id',
      'secret' => 'test_secret',
      'identity_verification' => false
    ];
    
    // Create the IntercomSettingsPage instance
    $this->intercomSettingsPage = new IntercomSettingsPage($this->settings);
  }

  protected function tearDown(): void
  {
    // Restore original $_GET
    $_GET = $this->originalGet;
    
    parent::tearDown();
  }

  /**
   * Test the constructor properly initializes the class
   */
  public function testConstructor()
  {
    $settings = [
      'app_id' => 'test_app_id',
      'secret' => 'test_secret',
      'identity_verification' => true
    ];
    
    $intercomSettingsPage = new IntercomSettingsPage($settings);
    
    // Use reflection to access private properties
    $reflection = new ReflectionClass($intercomSettingsPage);
    $settingsProperty = $reflection->getProperty('settings');
    $settingsProperty->setAccessible(true);
    $stylesProperty = $reflection->getProperty('styles');
    $stylesProperty->setAccessible(true);
    
    $this->assertEquals($settings, $settingsProperty->getValue($intercomSettingsPage));
    $this->assertNotEmpty($stylesProperty->getValue($intercomSettingsPage));
  }

  /**
   * Test the dismissibleMessage method
   */
  public function testDismissibleMessage()
  {
    $message = "Test message";
    $result = $this->intercomSettingsPage->dismissibleMessage($message);
    
    $this->assertStringContainsString('<div id="message" class="updated notice is-dismissible">', $result);
    $this->assertStringContainsString('<p>' . $message . '</p>', $result);
    $this->assertStringContainsString('<button type="button" class="notice-dismiss">', $result);
  }

  /**
   * Test the getAuthUrl method
   */
  public function testGetAuthUrl()
  {
    // Mock WordPress functions
    if (!function_exists('get_site_url')) {
      function get_site_url() {
        return 'https://example.com';
      }
    }
    
    if (!function_exists('wp_create_nonce')) {
      function wp_create_nonce($action) {
        return 'test_nonce';
      }
    }
    
    $authUrl = $this->intercomSettingsPage->getAuthUrl();
    
    $this->assertStringContainsString('https://wordpress_auth.intercom.io/confirm?state=', $authUrl);
    $this->assertStringContainsString('https://example.com', $authUrl);
    $this->assertStringContainsString('test_nonce', $authUrl);
  }

  /**
   * Test the htmlUnclosed method with different GET parameters
   */
  public function testHtmlUnclosed()
  {
    // Test with appId parameter
    $_GET['appId'] = 'new_app_id';
    $html = $this->intercomSettingsPage->htmlUnclosed();
    $this->assertStringContainsString('new_app_id', $html);
    $this->assertStringContainsString("We've copied your new Intercom app id below", $html);
    
    // Test with saved parameter
    $_GET = [];
    $_GET['saved'] = true;
    $html = $this->intercomSettingsPage->htmlUnclosed();
    $this->assertStringContainsString("Your app id has been successfully saved", $html);
    
    // Test with authenticated parameter
    $_GET = [];
    $_GET['authenticated'] = true;
    $html = $this->intercomSettingsPage->htmlUnclosed();
    $this->assertStringContainsString("You successfully authenticated with Intercom", $html);
  }

  /**
   * Test the htmlClosed method
   */
  public function testHtmlClosed()
  {
    $html = $this->intercomSettingsPage->htmlClosed();
    
    $this->assertStringContainsString('</form>', $html);
    $this->assertStringContainsString('Identity Verification', $html);
    $this->assertStringContainsString('https://docs.intercom.com', $html);
  }

  /**
   * Test the html method
   */
  public function testHtml()
  {
    $html = $this->intercomSettingsPage->html();
    
    $this->assertStringContainsString('<div class="wrap">', $html);
    $this->assertStringContainsString('</div>', $html);
    $this->assertStringContainsString('https://code.jquery.com/jquery-2.2.0.min.js', $html);
  }

  /**
   * Test the setStyles method with different settings
   */
  public function testSetStyles()
  {
    // Test with identity verification enabled
    $settings = [
      'app_id' => 'test_app_id',
      'secret' => 'test_secret',
      'identity_verification' => true
    ];
    
    $intercomSettingsPage = new IntercomSettingsPage($settings);
    $reflection = new ReflectionClass($intercomSettingsPage);
    $stylesProperty = $reflection->getProperty('styles');
    $stylesProperty->setAccessible(true);
    
    $styles = $stylesProperty->getValue($intercomSettingsPage);
    $this->assertEquals('checked disabled', $styles['identity_verification_state']);
    
    // Test with app_id but no secret
    $settings = [
      'app_id' => 'test_app_id',
      'secret' => '',
      'identity_verification' => false
    ];
    
    $intercomSettingsPage = new IntercomSettingsPage($settings);
    $styles = $stylesProperty->getValue($intercomSettingsPage);
    $this->assertEquals('display: none;', $styles['app_secret_row_style']);
    $this->assertEquals('', $styles['app_secret_link_style']);
    
    // Test with appId in GET
    $_GET['appId'] = 'new_app_id';
    $intercomSettingsPage = new IntercomSettingsPage($settings);
    $styles = $stylesProperty->getValue($intercomSettingsPage);
    $this->assertEquals('readonly', $styles['app_id_state']);
    $this->assertEquals('cta__email', $styles['app_id_class']);
    $this->assertEquals('', $styles['button_submit_style']);
    $this->assertEquals('display: none;', $styles['app_id_copy_hidden']);
    
    // Test with saved in GET
    $_GET = [];
    $_GET['saved'] = true;
    $intercomSettingsPage = new IntercomSettingsPage($settings);
    $styles = $stylesProperty->getValue($intercomSettingsPage);
    $this->assertEquals('display: none;', $styles['app_id_copy_hidden']);
    $this->assertEquals('', $styles['app_id_saved_title']);
    
    // Test with empty app_id
    $settings = [
      'app_id' => '',
      'secret' => 'test_secret',
      'identity_verification' => false
    ];
    
    $intercomSettingsPage = new IntercomSettingsPage($settings);
    $styles = $stylesProperty->getValue($intercomSettingsPage);
    $this->assertEquals('display: none;', $styles['app_id_row_style']);
    $this->assertEquals('', $styles['app_id_link_style']);
  }

  /**
   * Test the getSettings method
   */
  public function testGetSettings()
  {
    $reflection = new ReflectionClass($this->intercomSettingsPage);
    $method = $reflection->getMethod('getSettings');
    $method->setAccessible(true);
    
    $settings = $method->invoke($this->intercomSettingsPage);
    
    $this->assertEquals($this->settings, $settings);
  }

  /**
   * Test the getStyles method
   */
  public function testGetStyles()
  {
    $reflection = new ReflectionClass($this->intercomSettingsPage);
    $method = $reflection->getMethod('getStyles');
    $method->setAccessible(true);
    
    $styles = $method->invoke($this->intercomSettingsPage);
    
    $this->assertNotEmpty($styles);
    $this->assertIsArray($styles);
  }
}
