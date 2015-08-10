<?php
class FakeWordpressUser
{
  public $user_email = "foo@bar.com";
}
class FakeWordpressUserNoEmail
{
  public $user_email = NULL;
}

class UserTest extends PHPUnit_Framework_TestCase
{
  public function testEmail()
  {
    $settings = array();
    $built_settings = (new User(new FakeWordpressUser(), $settings))->buildSettings();
    $this->assertEquals("foo@bar.com", $built_settings["email"]);
  }
  public function testNoUser()
  {
    $settings = array();
    $built_settings = (new User(NULL, $settings))->buildSettings();
    $this->assertEquals(false, array_key_exists('email', $built_settings));
  }
  public function testNoUserEmail()
  {
    $settings = array();
    $built_settings = (new User(new FakeWordpressUserNoEmail(), $settings))->buildSettings();
    $this->assertEquals(false, array_key_exists('email', $built_settings));
  }
}
