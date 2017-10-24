<?php
class IdentityVerificationCalculatorTest extends PHPUnit_Framework_TestCase
{
  public function testHashEmail()
  {
    $data = array("email" => "test@intercom.io");
    $calculator = new IdentityVerificationCalculator($data, "s3cre7", true);
    $this->assertEquals(array("user_hash" => "844240a2deab99438ade8e7477aa832e22adb0e1eb1ad7754ff8d4054fb63869"), $calculator->identityVerificationComponent());
  }

  public function testHashUserId()
  {
    $data = array("user_id" => "abcdef", "email" => "test@intercom.io");
    $calculator = new IdentityVerificationCalculator($data, "s3cre7", true);
    $this->assertEquals(array("user_hash" => "532cd9cd6bfa49528cf2503db0743bb72456bda2cb7424d2894c5b11f6cad6cf"), $calculator->identityVerificationComponent());
  }

  public function testEmpty()
  {
    $data = array();
    $calculator = new IdentityVerificationCalculator($data, "s3cre7", true);
    $this->assertEquals(array(), $calculator->identityVerificationComponent());
  }

  public function testNoIdentityVerification()
  {
    $data = array("email" => "test@intercom.io");
    $calculator = new IdentityVerificationCalculator($data, "s3cre7", false);
    $this->assertEquals(array(), $calculator->identityVerificationComponent());
  }
}
