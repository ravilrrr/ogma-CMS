<?php 

function addCode($atts, $content = null) {
 extract(Shortcodes::shortcodeAtts(array(
    "mode" => null
  ), $atts));
  $mode   = isset($mode) ? $mode : 'html'; 
  return '<pre><code data-language="'.$mode.'">'.str_replace('<br/>', '', $content).'</code></pre>'; 
}
Shortcodes::addShortcode('code','addCode', '[code mode=""]content[/code]');
Shortcodes::addShortcodeDesc('code','Code Block', '[code mode=""]content[/code]');

?>