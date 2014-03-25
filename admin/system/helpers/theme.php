<?php 

 /**
 *  ogmaCMS Template Module
 *
 *  @package ogmaCMS
 *  @author Mike Swan / n00dles101
 *  @copyright 2013 Mike Swan / n00dles101
 *  @since 1.0.0
 *
 */

class Theme{
    
    public static $themeInfo = array();
    public static $themeSettings = array();

    public function __construct() {

    }

    public static function loadOptions(){
        self::getThemes();
        $theme = Core::$site['template'];
        $language = Core::$site['language'];
        $options = self::$themeInfo[$theme]['options'];
        if (file_exists(ROOT.Core::$settings['themespath'].$theme.DS.'data'.DS.$theme.'.php')){
            require_once(ROOT.Core::$settings['themespath'].$theme.DS.'data'.DS.$theme.'.php');
        }

        // check if theme options file exists, if not create it. 
        if (!file_exists(ROOT.Core::$settings['themespath'].$theme.DS.'data'.DS.'data.xml')){
            foreach ($options as $option=>$val){
                self::$themeSettings[$option] = '';
                   
            }
           // $options = self::$themeInfo[$theme]['options'];
            $xml=Xml::arrayToXml(self::$themeSettings);
            $ret =  file_put_contents(ROOT.Core::$settings['themespath'].$theme.DS.'data'.DS.'data.xml', $xml);
        } else {
            self::$themeSettings = Xml::xml2array(ROOT.Core::$settings['themespath'].$theme.DS.'data'.DS.'data.xml');
            Lang::mergeLanguage(ROOT.Core::$settings['themespath'].$theme.DS.'data'.DS.$language.'.lang.php');
        }
    }

    public static function loadSettings(){
        
    }

    public static function themeExists($theme){
       
        if (array_key_exists($theme, self::$themeInfo)) {
            return  true ;
        } else {
            return  false;
        }

    }

    public static function getImage($theme=''){
        if ($theme=='') $theme = Core::$site['template'];
        echo '<img src="'.Core::$site['siteurl'].'/'.Core::$settings['themespath'] . $theme."/data/screenshot.png".'" alt="" />';
    }

    public static function getThemeInfo($theme=''){
         if ($theme=='') $theme = Core::$site['template'];
         echo '<h3>'.self::$themeInfo[$theme]['theme']['name'].'</h3>';
         echo '<h6>Author: '.self::$themeInfo[$theme]['theme']['author'].', Version: '.self::$themeInfo[$theme]['theme']['version'].'</h6>';
         echo '<p>'.self::$themeInfo[$theme]['theme']['description'].'</h3>';
    }

    public static function showAllThemes(){
        $themes=self::$themeInfo;
       // echo '<div class="row">';
        $count=0;
       // echo Core::$site['template'];

        echo '<div class="row">';
        foreach($themes as $theme){
            if (Core::$site['template']!=$theme['theme']['name']){
               
               echo '  <div class="col-sm-4 col-md-3">';
               echo '    <div class="thumbnail" style="min-height:250px;" >';
               echo '      <img src="'.Core::$site['siteurl'].'/'.Core::$settings['themespath'] . $theme['theme']['folder']."/data/screenshot.png".'" alt="">';
               echo '      <div class="caption">';
               echo '        <h3>'.$theme['theme']['name'].'</h3>';
               echo '        <p>'.$theme['theme']['description'].'</p>';
               echo '        <a href="template.php?settheme='.$theme['theme']['folder'].'" class="btn btn-primary" role="button">'.__("ACTIVATETHEME").'</a>';
               echo '      </div>';
               echo '    </div>';
               echo '  </div>';
              
               $count++;
           // if ($count % 3 == 0) {
           //     echo '</div><div class="row">';
           // }
           }
        } 
        echo '</div>';
        //echo '</div>';
    }

    public static function getThemes(){
        $themeNames = array();
        $themes_handle = opendir(Core::getRootPath().Core::$settings['themespath']) or die("Unable to open ".THEMESPATH);
        while ($file = readdir($themes_handle)) {
            $curpath = Core::getRootPath().Core::$settings['themespath'] . $file;
            if( is_dir($curpath) && $file != "." && $file != ".." ) {
                if (file_exists($curpath.'/template.php') && file_exists($curpath . '/data/manifest.xml') ){

                     Theme::$themeInfo[$file] = Xml::xml2array($curpath . '/data/manifest.xml');
                     Theme::$themeInfo[$file]['theme']['folder'] = $file;
                }
            }
        }
        return $themeNames; 
    }

    public static function hasOptions(){
        $theme = Core::$site['template'];
        if(count(self::$themeInfo[$theme]['options']) > 1){
            return true;
        } else {
            return false;
        }
    }

    public static function getSetting($setting){
        $theme = Core::$site['template'];
        if (array_key_exists($setting, self::$themeSettings)){
            return self::$themeSettings[$setting];
        } else {
            return "Unknown key ($setting)";
        }
    }

    public static function getThemeHooks(){
        $theme = Core::$site['template'];
        return self::$themeInfo[$theme]['hooks'];
    }

    public static function hasHooks(){
        $theme = Core::$site['template'];
        if(count(self::$themeInfo[$theme]['hooks']) > 1){
            return true;
        } else {
            return false;
        }
    }

    /**
     * getHookFunction  - Return a array of snippets and components
     */
    public static function getHookFunctions(){
        $functions = array();
        $records = new Query('snippets');
        $snippets = $records->getCache()->get();
        foreach ($snippets as $snippet) {
            $functions[]="SNIPPET:".$snippet['slug'];
        }
        $records = new Query('components');
        $snippets = $records->getCache()->get();
        foreach ($snippets as $snippet) {
            $functions[]="COMPONENT:".$snippet['slug'];
        }

        return $functions;
    }

    /**
     * addThemeActions - Add actions for Theme hooks
     */
    public static function addThemeActions(){
        $records = new Query('themehooks');
        $hooks = $records->getCache()->order('order')->get();
        if (count($hooks)>0){
            foreach ($hooks as $hook) {
               if (substr($hook['action'],0,8)=="SNIPPET:"){
                   Actions::addAction($hook['hook'], 'Snippet::show',1,array(substr($hook['action'],8)));
                } 
                if (substr($hook['action'],0,10)=="COMPONENT:"){
                    Actions::addAction($hook['hook'], 'Component::show',1,array(substr($hook['action'],10)) );
                }
                   
            }
        }
        return $hooks;
    }

    public static function saveSettings(){
        $settings = Theme::$themeSettings;
        $theme = Core::$site['template'];
        foreach ($settings  as $setting=>$val) {
           if(isset($_POST['post-'.$setting])){
                Theme::$themeSettings[$setting]=$_POST['post-'.$setting];
            } 
        }
        return file_put_contents(ROOT.Core::$settings['themespath'].$theme.'/data/data.xml', Xml::arrayToXml(self::$themeSettings));
    }


}
?>
