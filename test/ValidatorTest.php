<?php
class ValidatorTest extends PHPUnit_Framework_TestCase
{
  public function testValidator()
  {
    // Emulate the wordpress wp_kses function
    $wp_kses = function($x) {
      return str_replace("<script>", "", $x);
    };
    $validator = new Validator(array("app_id" => "foo<script>"), $wp_kses);
    $this->assertEquals("foo", $validator->validAppId());
  }
}
