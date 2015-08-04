<?php
class SnippetSettingsTest extends PHPUnit_Framework_TestCase
{
  public function testJSONRendering()
  {
    $snippet_settings = new SnippetSettings(array("app_id" => "bar"));
    $this->assertEquals("{\"app_id\":\"bar\"}", $snippet_settings->json());
  }

  public function testAppId()
  {
    $snippet_settings = new SnippetSettings(array("app_id" => "bar"));
    $this->assertEquals("bar", $snippet_settings->appId());
  }

  /**
  * @expectedException Exception
  */
  public function testValidation()
  {
    $snippet = new SnippetSettings(array("foo" => "bar"));
  }
}
