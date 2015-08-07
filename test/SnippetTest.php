<?php
class SnippetTest extends PHPUnit_Framework_TestCase
{
  public function testGeneratedHtml()
  {
    $settings = new SnippetSettings(array("app_id" => "foo", "name" => "Nikola Tesla"));
    $snippet = new Snippet($settings);

    $expectedHtml = <<<HTML
<script>
  window.intercomSettings = JSON.parse('{"app_id":"foo","name":"Nikola Tesla"}');
</script>
<script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/foo';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()</script>
HTML;

    $this->assertEquals($expectedHtml, $snippet->html());
  }
}
