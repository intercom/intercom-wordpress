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
    $user = new User(new FakeWordpressUser(), $settings);
    $built_settings = $user->buildSettings();
    $this->assertEquals("foo@bar.com", $built_settings["email"]);
  }
  public function testNoUser()
  {
    $settings = array();
    $user = new User(NULL, $settings);
    $built_settings = $user->buildSettings();
    $this->assertEquals(false, array_key_exists('email', $built_settings));
  }
  public function testNoUserEmail()
  {
    $settings = array();
    $user = new User(new FakeWordpressUserNoEmail(), $settings);
    $built_settings = $user->buildSettings();
    $this->assertEquals(false, array_key_exists('email', $built_settings));
  }
}
