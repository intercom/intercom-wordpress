<?php
class FakeWordPressUser
{
  public $user_email = "foo@bar.com";
}
class FakeWordPressUserNoEmail
{
  public $user_email = NULL;
}

class UserTest extends PHPUnit_Framework_TestCase
{
  public function testEmail()
  {
    $settings = array();
    $user = new IntercomUser(new FakeWordPressUser(), $settings);
    $built_settings = $user->buildSettings();
    $this->assertEquals("foo@bar.com", $built_settings["email"]);
  }
  public function testNoUser()
  {
    $settings = array();
    $user = new IntercomUser(NULL, $settings);
    $built_settings = $user->buildSettings();
    $this->assertEquals(false, array_key_exists('email', $built_settings));
  }
  public function testNoUserEmail()
  {
    $settings = array();
    $user = new IntercomUser(new FakeWordPressUserNoEmail(), $settings);
    $built_settings = $user->buildSettings();
    $this->assertEquals(false, array_key_exists('email', $built_settings));
  }
}
