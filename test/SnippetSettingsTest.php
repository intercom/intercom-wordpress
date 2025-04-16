<?php
class FakeWordPressUserForSnippetTest
{
  public $user_email = "foo@bar.com";
}

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Firebase\JWT\JWT;

class IntercomSnippetSettingsTest extends TestCase
{
  protected function setUp(): void
  {
    parent::setUp();
    // Freeze time to a specific timestamp
    TimeProvider::setMockTime(strtotime('2024-04-08 12:00:00'));
  }

  protected function tearDown(): void
  {
    TimeProvider::resetMockTime();
    parent::tearDown();
  }

  public function testJSONRendering()
  {
    $snippet_settings = new IntercomSnippetSettings(array("app_id" => "bar"));
    $this->assertEquals("{\"app_id\":\"bar\",\"installation_type\":\"wordpress\",\"installation_version\":\"" . INTERCOM_PLUGIN_VERSION . "\"}", $snippet_settings->json());
  }
  public function testJSONRenderingWithIdentityVerification()
  {
    $snippet_settings = new IntercomSnippetSettings(array("app_id" => "bar"), "s3cre7", new FakeWordPressUserForSnippetTest());
    $jwt_data = array(
      "user_id" => "foo@bar.com",
      "email" => "foo@bar.com",
      "exp" => TimeProvider::getCurrentTime() + 3600
    );
    $jwt = JWT::encode($jwt_data, "s3cre7", 'HS256'); 
    $this->assertEquals('{"app_id":"bar","intercom_user_jwt":"'.$jwt.'","installation_type":"wordpress","installation_version":"' . INTERCOM_PLUGIN_VERSION . '"}', $snippet_settings->json());
  }
  public function testJSONRenderingWithIdentityVerificationAndNoSecret()
  {
    $snippet_settings = new IntercomSnippetSettings(array("app_id" => "bar"), NULL, new FakeWordPressUserForSnippetTest());
    $this->assertEquals("{\"app_id\":\"bar\",\"email\":\"foo@bar.com\",\"installation_type\":\"wordpress\",\"installation_version\":\"" . INTERCOM_PLUGIN_VERSION . "\"}", $snippet_settings->json());
  }
  public function testInstallationType()
  {
    $snippet_settings = new IntercomSnippetSettings(array("app_id" => "bar"));
    $this->assertEquals("{\"app_id\":\"bar\",\"installation_type\":\"wordpress\",\"installation_version\":\"" . INTERCOM_PLUGIN_VERSION . "\"}", $snippet_settings->json());
  }
  public function testIclLanguageConstant()
  {
    define('ICL_LANGUAGE_CODE', 'fr');
    $snippet_settings = new IntercomSnippetSettings(array("app_id" => "bar"));
    $this->assertEquals("{\"app_id\":\"bar\",\"language_override\":\"fr\",\"installation_type\":\"wordpress\",\"installation_version\":\"" . INTERCOM_PLUGIN_VERSION . "\"}", $snippet_settings->json());
  }

  public function testAppId()
  {
    $snippet_settings = new IntercomSnippetSettings(array("app_id" => "bar"));
    $this->assertEquals("bar", $snippet_settings->appId());
  }

  public function testValidation()
  {
    $this->expectException(\Exception::class);
    $snippet = new IntercomSnippetSettings(array("foo" => "bar"));
  }
}
