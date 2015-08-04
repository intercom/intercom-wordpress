<?php
class SettingsPage
{
  private $settings = array();

  public function __construct($settings)
  {
    $this->settings = $settings;
  }

  public function html()
  {
    $app_id = $this->getSettings()['app_id'];
    $secret = $this->getSettings()['secret'];

    return <<<END
<h1>Intercom Settings</h1>

<div id="intercom-settings-container" class="postbox-container">
  <form method="post" action="$action">
    <table class="form-table">
      <tbody>
        <tr>
          <td><b>App ID</b></td>
          <td><input name="app_id" type="text" value="$app_id" placeholder="App ID"></td>
        </tr>
        <tr>
          <td><b>Secret</b></td>
          <td><input name="secret" type="text" value="$secret" placeholder="Secret"></td>
        </tr>
      </tbody>
    </table>
    <input type="submit" value="Save">
  </form>
</div>
END;
  }

  private function getSettings()
  {
    return $this->settings;
  }
}
