<?php
class Validator
{
  private $inputs = array();
  private $validation;

  public function __construct($inputs, $validation)
  {
    $this->input = $inputs;
    $this->validation = $validation;
  }

  public function validAppId()
  {
    return $this->validate($this->input["app_id"]);
  }

  public function validSecret()
  {
    return $this->validate($this->input["secret"]);
  }

  private function validate($x)
  {
    return call_user_func($this->validation, $x);
  }
}
