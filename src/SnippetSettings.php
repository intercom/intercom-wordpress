<?php
class SnippetSettings
{
  private $raw_data = array();

  public function __construct($raw_data)
  {
    $this->raw_data = $this->validateRawData($raw_data);
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
    return $this->raw_data;
  }

  private function validateRawData($raw_data)
  {
    if (!array_key_exists("app_id", $raw_data)) {
      throw new Exception("app_id is required");
    }
    return $raw_data;
  }
}
