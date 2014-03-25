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

class Template{

    public function __construct() {

    }
    
 	public static function get_site_name(){
    	echo Core::$site['sitename'];
    }

 	public static function getThemeUrl($echo = true){
    	global $TEMPLATE;
        if ($echo) {
    	   echo  Core::$site['siteurl'].'/theme/'.$TEMPLATE;
        }  else {
           return Core::$site['siteurl'].'/theme/'.$TEMPLATE;
        }  
    }   

    public static function getSiteUrl($echo = true){
        if ($echo) {
            echo  Core::$site['siteurl'];
        } else {
            return Core::$site['siteurl'];
        }  
    }

	public static function get_site_url($echo = true){
    	 if ($echo) {
            echo  Core::$site['siteurl'];
        } else {
            return Core::$site['siteurl'];
        }
    }  
     
    public static function getUploadUrl($echo = true){
        if ($echo) {
            echo  Url::returnUrl(Core::$settings['uploadpath']);
        } else {
            return Url::returnUrl(Core::$settings['uploadpath']);
        }
    }

    public static function getSiteCredits(){
        echo "Powered by Ogma CMS";
    }

}
?>
