<?php

function addSoundcloud($atts, $content = null) {
 extract(Shortcodes::shortcodeAtts(array(
    "url" => null,
    "width" => '400',
    "height" => '225',
    "scrolling" => 'no',
    "frameborder" => 'no',
    "iframe" => 'true'
  ), $atts));
  return '<iframe width="'.$width.'" height="'.$height.'" scrolling="'.$scrolling.'" frameborder="'.$frameborder.'" src="https://w.soundcloud.com/player/?url='.$url.'"></iframe>';
  }

Shortcodes::addShortcode('soundcloud','addSoundcloud', '[soundcloud url="" width="" height="" /]');
Shortcodes::addShortcodeDesc('soundcloud','Soundcloud Music', '[soundcloud url="" width="" height="" /]');

?>