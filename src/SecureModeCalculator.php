<?php
class SecureModeCalculator
{
  private $raw_data = array();
  private $secret_key = "";

  public function __construct($data, $secret_key)
  {
    $this->raw_data = $data;
    $this->secret_key = $secret_key;
  }

  public function secureModeComponent()
  {
    $secret_key = $this->getSecretKey();
    if (empty($secret_key))
    {
      return $this->emptySecureModeHashComponent();
    }
    if (array_key_exists("user_id", $this->getRawData()))
    {
      return $this->secureModeHashComponent("user_id");
    }
    if (array_key_exists("email", $this->getRawData()))
    {
      return $this->secureModeHashComponent("email");
    }
    return $this->emptySecureModeHashComponent();
  }

  private function emptySecureModeHashComponent()
  {
    return array();
  }

  private function secureModeHashComponent($key)
  {
    return array("user_hash" => hash_hmac("sha256", $this->getRawData()[$key], $this->getSecretKey()));
  }

  private function getSecretKey()
  {
    return $this->secret_key;
  }

  private function getRawData()
  {
    return $this->raw_data;
  }
}
