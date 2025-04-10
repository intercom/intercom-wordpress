<?php

use PHPUnit\Framework\TestCase;
use Firebase\JWT\JWT;

class MessengerSecurityCalculatorTest extends TestCase
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

  public function testEmailJWT()
  {
    $data = array("app_id" => "abcdef", "email" => "test@intercom.io");
    $calculator = new MessengerSecurityCalculator($data, "s3cre7");
    $jwt_data = array(
      "user_id" => "test@intercom.io",
      "email" => "test@intercom.io",
      "exp" => TimeProvider::getCurrentTime() + 3600
    );
    $jwt = JWT::encode($jwt_data, "s3cre7", 'HS256');
    $this->assertEquals(
      array(
        "app_id" => "abcdef",
        "intercom_user_jwt" => $jwt
      ),
      $calculator->messengerSecurityComponent()
    );
  }

  public function testUserIdEmailJWT()
  {
    $data = array("app_id" => "abcdef", "user_id" => "abcdef", "email" => "test@intercom.io");
    $calculator = new MessengerSecurityCalculator($data, "s3cre7");
    $jwt_data = array(
      "user_id" => "abcdef",
      "email" => "test@intercom.io",
      "exp" => TimeProvider::getCurrentTime() + 3600
    );
    $jwt = JWT::encode($jwt_data, "s3cre7", 'HS256');
    $this->assertEquals(
      array(
        "app_id" => "abcdef",
        "intercom_user_jwt" => $jwt
      ),
      $calculator->messengerSecurityComponent()
    );
  }

  public function testUserIdEmailNameJWT()
  {
    $data = array("app_id" => "abcdef", "user_id" => "abcdef", "email" => "test@intercom.io", "name" => "John Doe");
    $calculator = new MessengerSecurityCalculator($data, "s3cre7");
    $jwt_data = array(
      "user_id" => "abcdef",
      "email" => "test@intercom.io",
      "name" => "John Doe",
      "exp" => TimeProvider::getCurrentTime() + 3600
    );
    $jwt = JWT::encode($jwt_data, "s3cre7", 'HS256');
    $this->assertEquals(
      array(
        "app_id" => "abcdef",
        "intercom_user_jwt" => $jwt
      ),
      $calculator->messengerSecurityComponent()
    );
  }

  public function testEmpty()
  {
    $data = array("app_id" => "abcdef");
    $calculator = new MessengerSecurityCalculator($data, "s3cre7");
    $this->assertEquals(
      array(
        "app_id" => "abcdef",
      ),
      $calculator->messengerSecurityComponent()
    );
  }

  public function testExtraJWTData()
  {
    putenv('INTERCOM_PLUGIN_TEST_JWT_DATA=' . json_encode(array("custom_data" => "custom_value")));

    $data = array("app_id" => "abcdef", "user_id" => "abcdef", "email" => "test@intercom.io", "name" => "John Doe");
    $calculator = new MessengerSecurityCalculator($data, "s3cre7");
    $jwt_data = array(
      "user_id" => "abcdef",
      "email" => "test@intercom.io",
      "name" => "John Doe",
      "custom_data" => "custom_value",
      "exp" => TimeProvider::getCurrentTime() + 3600
    );
    $jwt = JWT::encode($jwt_data, "s3cre7", 'HS256');
    $this->assertEquals(
      array(
        "app_id" => "abcdef",
        "intercom_user_jwt" => $jwt
      ),
      $calculator->messengerSecurityComponent()
    );
    putenv('INTERCOM_PLUGIN_TEST_JWT_DATA=');
  }
}
