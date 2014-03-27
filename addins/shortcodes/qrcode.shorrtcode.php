<?php 

function addQRCode($atts, $content = null) {
 extract(Shortcodes::shortcodeAtts(array(
    "size" => 150,
    "eclevel" => 'L',
    "margin" => '0',
    "url" => null
  ), $atts));
if (is_null($url)){
	$url = Core::curPageURL();
}
return '<img src="https://chart.googleapis.com/chart?chs='.$size.'x'.$size.'&cht=qr&chld='.$eclevel.'|'.$margin.'&chl='.$url.'" alt="QR code" width="'.$size.'" height="'.$size.'"/>';

}
Shortcodes::addShortcode('qrcode','addQRCode', '[qrcode url="" size="150" eclevel="L" margin="0" /]');
Shortcodes::addShortcodeDesc('qrcode','QR Code', '[qrcode url="" size="150" eclevel="L" margin="0" /]');

?>