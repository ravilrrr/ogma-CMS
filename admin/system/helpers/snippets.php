<?php 

 /**
 *  ogmaCMS Snippets Module
 *
 *  @package ogmaCMS
 *  @author Mike Swan / n00dles101
 *  @copyright 2013 Mike Swan / n00dles101
 *  @since 1.0.0
 *
 */

class Snippet {

	public function __construct() {

    }
    
    /**
    * Show a Snippet
    *
    * @param string $name Snippet Name
    */
    public static function show($name){

       $name = Utils::findRecordID('snippets','slug',$name);

        if (file_exists(Core::getRootPath() . '/data/snippets/'.$name.'.xml')){
    	   $snippet = (Xml::xml2array(Core::$settings['rootpath'] . '/data/snippets/'.$name.'.xml'));
    	   if (Core::trueFalse($snippet['active']==true)){
                $content = Utils::safe_strip_decode($snippet['content']);
                $content = Markdown($content);
                $content = Filters::execFilter('content',$content);
                echo $content;
            }
        } else {
            echo "Error: Unable to find Snippet: ".$name."<br/>";;
        }
    }

    /**
    * Show a Snippet
    *
    * @param string $name Snippet Name
    */
    public static function get($name){

       $name = Utils::findRecordID('snippets','slug',$name);

        if (file_exists(Core::getRootPath() . '/data/snippets/'.$name.'.xml')){
          $snippet = (Xml::xml2array(Core::$settings['rootpath'] . '/data/snippets/'.$name.'.xml'));
            if (Core::trueFalse($snippet['active']==true)){
                $content = Utils::safe_strip_decode($snippet['content']);
                $content = Markdown($content);
                $content = Filters::execFilter('content',$content);
                return $content;
            }
        } else {
            return "Error: Unable to find Snippet: ".$name."<br/>";;
        }
    }

    /**
     * check is snippet exists
     * 
     * @param string $name Snippet Name
     */
    public static function exists($name){
        $name = Utils::findRecordID('snippets','slug',$name);
        return file_exists(Core::getRootPath() . '/data/snippets/'.$name.'.xml') ? true : false ;
    }
	

    /**
     * check if SNippet is active
     *
     * @param string $name Snippet Namr
     */
    public static function isActive($name){
        $name = Utils::findRecordID('snippets','slug',$name);
        if (self::exists($name)){
             $component = (Xml::xml2array(Core::$settings['rootpath'] . '/data/snippets/'.$name.'.xml'));
             return Core::trueFalse($component['active']);  
        } else {
            return false;
        }
    }

}
