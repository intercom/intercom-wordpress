<?php
class SnippetSettings
{
  private $raw_data = array();
  private $secret = NULL;
  private $wordpress_user = NULL;

  public function __construct($raw_data, $secret = NULL, $wordpress_user = NULL)
  {
    $this->raw_data = $this->validateRawData($raw_data);
    $this->secret = $secret;
    $this->wordpress_user = $wordpress_user;
  }

  public function json()
  {
    return json_encode($this->getRawData());
  }

  public function appId()
  {
    return $this->getRawData()["app_id"];
  }

  private function getRawData()
  {
    $settings = (new User($this->wordpress_user, $this->raw_data))->buildSettings();
    $secureModeCalculator = new SecureModeCalculator($settings, $this->secret);
    return array_merge($settings, $secureModeCalculator->secureModeComponent());
  }

  private function validateRawData($raw_data)
  {
    if (!array_key_exists("app_id", $raw_data)) {
      throw new Exception("app_id is required");
    }
    return $raw_data;
  }
}
