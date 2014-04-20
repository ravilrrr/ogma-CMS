<?php defined('IN_OGMA') or die('No direct script access.');

    /**
     *  Main OGMA CMS Module.
     *
     *	OGMA - Content Management System. 
     *  Site: www.digimute.com
     *	Copyright (C) 2012 Mike Swan
     *
     *	@package uCMS
     *	@author Mike Swan / N00dles101
     *	@copyright 2012 Mike Swan
     *	@version 1.0
     *	@since 1.0
     */


class Core {
	public static $errorMsg = "";
	public static $devMode = false;	
	public static $site = array();
	public static $settings = array(); 
	public static $schema = array(
			"blog"       	=> array('slug','title','pubdate','author','tags','status','comments','metat','metad','metak','content','category','image','id'),
			"components" 	=> array('slug','content','active','desc','id'),
			"galleries"  	=> array('galleryname','id'),
			"menus"      	=> array('menuname','id'),
			"pages"      	=> array('slug','parent','title','pubdate','route','template','metat','metak','metad','robots','status','private','content','author','id'),
			"routes"     	=> array('route','slug','desc','page','id'),
			"snippets"   	=> array('slug','content','active','desc','id'),
			"users"      	=> array('username','password','email','role','firstname','lastname','id','language','perms','reset','salt'),
			"media"      	=> array('id','title','alt','caption','description','fileurl','tag','showorder'),
			"themehooks" 	=> array('id','hook','type','action'),
			"customfields" 	=> array('id','name','table','type','cache','desc','options')
		);

	public static $pages = array();
	public static $routes = array();
	public static $page = array();
	
	public static $permissions = array('pages','blog','snippets','components','menu','media','files','plugins','templates','settings','routes','customfields','tables','backups','healthcheck');

	public function __construct() {
	
		Core::init();
        Core::$site = Xml::xml2array(ROOT . '/data/website.xml');       
        if (Core::$site['debug']==true){
        	Debug::addLog("Debug Started");
        	ini_set('display_errors',1);
			ini_set('display_startup_errors',1);
			error_reporting(-1);
        } else {        	
        	ini_set('display_errors',0);
			ini_set('display_startup_errors',0);
			error_reporting(0);
        }

		Core::$settings['rootpath']      = self::getRootPath();
		Core::$settings['themespath']    = 'theme'.DS;
		Core::$settings['adminpath']     = self::getAdminPath();
		Core::$settings['pluginpath']    = self::getRootPath().'addins'.DS.'plugins'.DS;
		Core::$settings['shortcodepath'] = self::getRootPath().'addins'.DS.'shortcodes'.DS;
		Core::$settings['fieldspath']    = self::getRootPath().'addins'.DS.'fields'.DS;
		Core::$settings['backuppath']    = self::getRootPath().'backups'.DS;
		Core::$settings['datapath']      = 'data'.DS;
		Core::$settings['uploadpath']    = 'uploads'.DS;
		Core::$settings['temppath']   	 = self::getRootPath().'temp'.DS;

		Core::$pages = new Query("pages");
		Core::$routes = new Query("routes");

		// check for setlang 
		// 
		if (isset($_GET['setlang'])){
			$lang=$_GET['setlang'];
			if (in_array($lang, Lang::getInstalledLanguages())){
				Core::$site['language'] = $lang;
				if (User::isLoggedIn()){
					Session::set('lang',$lang);
					$username = Session::get('username');
					$users = new Query('users');
			        $users->getCache();
			        $allUsers = $users->find('username = '.$username)->get();
			        $user = $users->getFullRecord($allUsers[0]['id']);
			        $user['language'] = $lang;
				}
				$ret=self::saveSettings();
			}
		}

        if (User::getLanguage()==''){
        	$lang = Core::$site['language'];
        }  else {

        	$lang = User::getLanguage();
        }

 		Lang::loadLanguage(Core::$settings['rootpath'].'/addins/languages/en.lang.php');
 		if ($lang!='en'){
	 		Lang::loadLanguage(Core::$settings['rootpath'].'/addins/languages/'.$lang.'.lang.php');
	 	}
        require_once('startup.php');

         //
		// Update Settings
		//
		if (Core::getFilenameId()=='settings' && self::getAction()=="update"){
			$settings = Core::$site; 
			foreach ($settings as $item=>$val){
				if (isset($_POST['post-'.$item])) {
					Core::$site[$item]=$_POST['post-'.$item];
				}
			}
			//Lang::loadLanguage(Core::$settings['rootpath'].'/addins/languages/'.Core::$site['language'].'.lang.php');
			$ret=self::saveSettings();
			if ($ret){
				 Core::addAlert( Form::showAlert('success', __("UPDATED",array(":record"=>"",":type"=>__("SETTINGS"))) ));
				
			} else {
				Core::addAlert( Form::showAlert('error', __("UPDATEDFAIL",array(":record"=>"",":type"=>__("SETTINGS"))) ));
				
			}
			Core::$site = Xml::xml2array(ROOT . '/data/website.xml');
        
			$action='edit';	
			$_GET['action']='edit';
		}
		

        date_default_timezone_set(Core::$site['timezone']); 

        if (Core::$site['bootstrap']==true){
        	require_once('bootstrapcore.php');
        }
        
       


        // Load Language File
       
		Theme::loadOptions();
	}
	
	/**
	 * Core Initialization
	 *
	 * Autoloads all helper classes
	 * 	
	 * <code>
	 * 		Core::init();
	 * </code>
	 *
	 */ 
	private static function init(){
		Core::autoloadClasses();

	}
	

	/**
	 * Save Website Settings
	 *
	 * Save the values in Core::$site[] to /data/website.xml 
	 * 	
	 * <code>
	 * 		$ret = Core::saveSettings();
	 * </code>
	 *
	 * @return string Translated String or '** $name **'' if it does not exist 
	 */
	public  function saveSettings(){
		return file_put_contents(Core::$settings['rootpath'] . '/data/website.xml', Xml::arrayToXml(Core::$site));
	}

	/**
	 * Save Website Settings
	 *
	 */
	public static function isFile($file, $path, $type = 'xml') {
		if( is_file(self::tsl($path) . $file) && $file != "." && $file != ".." && (strstr($file, $type))  ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Autoload Classes
	 *
	 * Automatically load all Classes in the folder /admin/system/helpers
	 * 	
	 * <code>
	 * 		Core::autoloadClasses();
	 * </code>
	 *
	 */
	private static function autoloadClasses(){
        if (!defined('ROOT')) define('ROOT',self::getRootPath());
		$files = Core::getFiles(self::getAdminPath().'/system/helpers/','php');
		foreach ($files as $file){
			require_once('helpers/'.$file);
		}
		
	}
	
	/**
	 * Autoload Shortcodes
	 *
	 * Automatically load all Shortcodes in the folder /addins/shortcodes
	 * 	
	 * <code>
	 * 		Core::autoloadClasses();
	 * </code>
	 *
	 */
	private static function autoloadShortcodes(){
        if (!defined('ROOT')) define('ROOT',self::getRootPath());
		$files = Core::getFiles(self::getRootPath().'addins/shortcodes/','php');
		foreach ($files as $file){
			require_once(self::getRootPath().'addins/shortcodes/'.$file);
		}
		
	}
	
	

	public static function getFiles($path,$ext="") {
		$handle = opendir($path) or die("Unable to open $path");
		$file_arr = array();
		while ($file = readdir($handle)) {
			if ($file != '.' && $file != '..'){
				if (pathinfo($file,PATHINFO_EXTENSION)==$ext) {
					$file_arr[] =  $file;
				}
			}
		}
		closedir($handle);
		return $file_arr;
	}

	public static function mergePageInfo($data = array(), $type){
		foreach ($data as $key => $value) {
			Core::$page[$key]=$value;
		}
		Core::$page['type'] = $type;
		Core::$page['url'] = Core::curPageURL();
	}


	public static function getFolders($path) {
		$handle = opendir($path) or die("Unable to open $path");
		$file_arr = array();
		$files = glob($path . "*");
		foreach($files as $file){
		 	if(is_dir($file)){
		  		$file_arr[] = str_replace($path, '', $file);;
		 	}
		}
		return $file_arr;
	}

	public static function getRootPath() {
		$pos = strrpos(dirname(__FILE__),DIRECTORY_SEPARATOR.'system');
		$adm = substr(dirname(__FILE__), 0, $pos);
		$pos2 = strrpos($adm,DIRECTORY_SEPARATOR);
		return self::tsl(substr(__FILE__, 0, $pos2));
	}	
	
	public static function getAdminPath() {
		$pos = strrpos(dirname(__FILE__),DIRECTORY_SEPARATOR.'system');
		$adm = substr(dirname(__FILE__), 0, $pos);
		return $adm;
	}

	public static function getURL($slug, $parent, $type='full') {
					
		if ($type == 'full') {
			$full = Core::$site['siteurl'];
		} elseif($type == 'relative') {
			$s = pathinfo(htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES));
			$full = $s['dirname'] .'/';
			$full = str_replace('//', '/', $full);
		} else {
			$full = '/';
		}
		
		if ($parent != '') {
			$parent = self::tsl($parent); 
		}	
/*
		if (Core::$site == 'yes') {      
			if ($slug != 'index'){  
				$url = $full . $parent . $slug . '/';
			} else {
				$url = $full;
			}   
		} else {
			*/
			if ($slug != 'index'){ 
				$url = $full .'index.php?id='.$slug;
			} else {
				$url = $full;
			}
		//}
		return (string)$url;
	}	
	
	public static function getAlerts(){
		if (self::$errorMsg!==""){
			echo '<div class="notifications">'.self::$errorMsg.'</div>';
		}
	}

	public static function addAlert($alert){
		self::$errorMsg .= $alert;
	}
	
	public static function tsl($path) {
		if( substr($path, strlen($path) - 1) != '/' ) {
			$path .= DS;
		}
		return $path;
	}
	
	public static function getFilenameId() {
		$path = Core::myself(FALSE);
		$file = basename($path,".php");	
		return $file;	
	}
	
	public static function verifyPath($path, $realpath){
		if (substr(realpath($path),0,strlen( $realpath))=== $realpath) {
			return true;
		} else {
			return false;
		}
	}
	
	public static function myself($echo=true) {
		if ($echo) {
			echo htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES);
		} else {
			return htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES);
		}
	}
	
	public static function curPageURL() {
		 $pageURL = 'http';
		 if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		 $pageURL .= "://";
		 if ($_SERVER["SERVER_PORT"] != "80") {
		  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		 } else {
		  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		 }
		 return $pageURL;
	}
	
	public static function curPageURI() {
		 return $_SERVER["REQUEST_URI"];
	}
	
	public static function trueFalse($check){
		return ($check==1) ?  true :  false;
	}

	public static function getAction(){
		if ( isset( $_GET['action'] ) ){
			if (in_array($_GET['action'], array('view','create', 'delete','edit','deleterecord','update','createnew','updatemenu','opt','clone' ) )){
				return $_GET['action'];
			} else {
				$_GET['action'] = "view";
				return "view";
			}
		} else {
			$_GET['action'] = "view";
			return $_GET['action'];
		}
	}

	public static function getOption($name){
		if (isset($_GET[$name])){

			return ($_GET[$name]!='') ? $_GET[$name] : true;
		}
	}

	public static function getID(){
		if ( isset( $_GET['id'] ) ){
			return $_GET['id'];
		} else {
			return '';
		}
	}
	
	public static function getTable(){
		if ( isset( $_GET['tbl'] ) ){
			return $_GET['tbl'];
		} else {
			return '';
		}
	}
	

	public static function isDebug(){
		return Core::$site['debug'];
	}

	public static function subvalSort($a,$subkey, $order='asc',$natural = true) {
		if (count($a) != 0 || (!empty($a))) { 
			foreach($a as $k=>$v) {
				$b[$k] = strtolower($v[$subkey]);
			}

			if($natural){
				natsort($b);
				if($order=='desc') $b = array_reverse($b,true);	
			} 
			else {
				($order=='asc')? asort($b) : arsort($b);
			}
			
			foreach($b as $key=>$val) {
				$c[] = $a[$key];
			}

			return $c;
		}
	}
	
	public static function maintMode(){
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
		echo '<html xmlns="http://www.w3.org/1999/xhtml"><head><meta name="keywords" content="';
		echo Core::$site['metak'];
		echo '"><head><title>';
		echo Core::$site['sitename'];
		echo '</title></head>';
		echo '<body><div style="width:300px; margin:250px auto 0px; font-family:Arial, Helevtica, Sans-serif; font-weight:normal;">';
		
		$content = Utils::safe_strip_decode(Core::$site['maintmessage']);
        $content = Markdown($content);
        $content = Filters::execFilter('content',$content);
        echo $content;
		echo '</div>';
		echo '</div></body></html>';
		die;
	}

	public static function date($date, $time=false){
		if ($time){
			return date(Core::$site['dateformat'].' '.Core::$site['timeformat'], $date);
		} else {
			return date(Core::$site['dateformat'], $date);
		}
		
	}
	
	public static function time($date){
			return date(Core::$site['timeformat'], $date);
	}
	
	public static function setErrorMsg($msg){
		self::$errorMsg = $msg;
	}

	public static function getErrorMsg(){
		if (self::$errorMsg!=''){
			echo self::$errorMsg;
			self::$errorMsg = '';
		}
	}

	public static function isInstalled(){
		return file_exists(ROOT . DS . 'data' . DS. 'installed.xml');
	}	

}



if ( ! function_exists('__'))
{
	function __($name, array $opts = null){
		$string = Lang::langDisplay($name);
		return empty($opts) ? $string : strtr($string, $opts);
	} 
}