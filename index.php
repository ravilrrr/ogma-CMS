<?php
// Main engine defines    
define('DS', DIRECTORY_SEPARATOR);
define('IN_OGMA', true);

// enable error temporarily in case there are startup errors 
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require_once( 'config.php');
require_once( 'admin' .DS . 'system' . DS . 'core.php');

// Load Core file
// 
$core = new Core();

if (!Core::isInstalled()){
   header('location: install/install.php');
   exit;
}

$plugins = new Plugins('frontend');

$TEMPLATE = Core::$site['template'];

# get page id (url slug) that is being passed via .htaccess mod_rewrite
if (isset($_GET['id']) && $_GET['id']!=''){ 
	$id = $_GET['id'];
} else {
	$id = "index";
}

$id = Url::getPageID();

if ($id=='404') header("HTTP/1.0 404 Not Found");

Actions::executeAction('index-get-id');

$page = new Page($id);

Core::mergePageInfo($page->pageFields,'page');

$template_file=$page->pageFields['template']; 


# include the file functions.php if it exists within the theme
if ( file_exists(Core::$settings['themespath'] .$TEMPLATE."/functions.php") ) {
	include(Core::$settings['themespath'] .$TEMPLATE."/functions.php");	
}

# call pretemplate Hook
Actions::executeAction('index-pretemplate');

if (Core::$site['maintenance'] == true ) {
	Core::maintMode();
}

Theme::addThemeActions();

if ( (!file_exists(Core::$settings['themespath'] .$TEMPLATE."/".$template_file)) || ($template_file == '') ) { $template_file = "template.php"; }
require_once(Core::$settings['themespath'] .$TEMPLATE."/".$template_file);

# call posttemplate Hook
 Actions::executeAction('index-posttemplate');