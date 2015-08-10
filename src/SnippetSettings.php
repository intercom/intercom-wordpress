<?php
class SnippetSettings
{
  private $raw_data = array();
  private $wordpress_user = NULL;

  public function __construct($raw_data, $wordpress_user = NULL)
  {
    $this->raw_data = $this->validateRawData($raw_data);
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
    return (new User($this->wordpress_user, $this->raw_data))->buildSettings();
  }

  private function validateRawData($raw_data)
  {
    if (!array_key_exists("app_id", $raw_data)) {
      throw new Exception("app_id is required");
    }
    return $raw_data;
  }
}
