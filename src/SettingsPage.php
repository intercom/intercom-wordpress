<?php
class SettingsPage
{
  private $settings = array();

  public function __construct($settings)
  {
    $this->settings = $settings;
  }

  public function css()
  {
    return <<<END
  <style scoped>
    #intercom-settings-container {
      width: 600px;
      background-color: white;
      padding: 20px;
      border: 1px solid #c9d7df;
      border-radius: 3px;
    }
    input[name=intercom-submit] {
      background-image: linear-gradient(to bottom, #038bcf, #0386cd);
      border-color: #006CA6;
      color: white;
      text-shadow: 0 1px 0 rgba(0,0,0,0.2);
      width: 80px;
      border-radius: 3px;
    }
  </style>
END;
  }

  public function htmlUnclosed()
  {
    $app_id = $this->getSettings()['app_id'];
    $secret = $this->getSettings()['secret'];
    return <<<END
<h1>Intercom Settings</h1>

<div id="intercom-settings-container" class="postbox-container">
  <form method="post" action="">
    <table class="form-table">
      <tbody>
        <tr>
          <td><b>App ID</b></td>
          <td><input name="intercom[app_id]" type="text" value="$app_id" placeholder="App ID"></td>
        </tr>
        <tr>
          <td><b>Secret</b></td>
          <td><input name="intercom[secret]" type="text" value="$secret" placeholder="Secret"></td>
        </tr>
      </tbody>
    </table>
    <input name="intercom-submit" type="submit" value="Save">
END;
  }

  public function htmlClosed()
  {
    return <<<END

  </form>
</div>
END;
  }

  public function html()
  {
    return $this->htmlUnclosed() . $this->htmlClosed();
  }

  private function getSettings()
  {
    return $this->settings;
  }
}
