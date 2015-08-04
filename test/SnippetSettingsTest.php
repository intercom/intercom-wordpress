<?php
class SnippetSettingsTest extends PHPUnit_Framework_TestCase
{
  public function testJSONRendering()
  {
    $snippet = new SnippetSettings(array("app_id" => "bar"));
    $this->assertEquals("{\"app_id\":\"bar\"}", $snippet->json()); 
  }
  
  /**
  * @expectedException Exception
  */
  public function testValidation()
  {
    $snippet = new SnippetSettings(array("foo" => "bar"));
  }
}
