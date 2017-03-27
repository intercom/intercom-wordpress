<?php
class FakeWordPressUserForSnippetTest
{
  public $user_email = "foo@bar.com";
}

class IntercomSnippetSettingsTest extends PHPUnit_Framework_TestCase
{
  public function testJSONRendering()
  {
    $snippet_settings = new IntercomSnippetSettings(array("app_id" => "bar"));
    $this->assertEquals("{\"app_id\":\"bar\"}", $snippet_settings->json());
  }
  public function testJSONRenderingWithSecureMode()
  {
    $snippet_settings = new IntercomSnippetSettings(array("app_id" => "bar"), "foo", true, new FakeWordPressUserForSnippetTest());
    $this->assertEquals("{\"app_id\":\"bar\",\"email\":\"foo@bar.com\",\"user_hash\":\"a95b0a1ab461c0721d91fbe32a5f5f2a27ac0bfa4bfbcfced168173fa80d4e14\"}", $snippet_settings->json());
  }

  public function testIclLanguageConstant()
  {
    define('ICL_LANGUAGE_CODE', 'fr');
    $snippet_settings = new IntercomSnippetSettings(array("app_id" => "bar"));
    $this->assertEquals("{\"app_id\":\"bar\",\"language_override\":\"fr\"}", $snippet_settings->json());
  }

  public function testAppId()
  {
    $snippet_settings = new IntercomSnippetSettings(array("app_id" => "bar"));
    $this->assertEquals("bar", $snippet_settings->appId());
  }

  /**
  * @expectedException Exception
  */
  public function testValidation()
  {
    $snippet = new IntercomSnippetSettings(array("foo" => "bar"));
  }
}
