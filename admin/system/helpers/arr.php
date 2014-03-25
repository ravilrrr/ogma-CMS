<?php 

 /**
 *  OGMA CMS Arr Module
 *
 *  @package ogmaCMS
 *  @author Mike Swan / n00dles101
 *  @copyright 2013 Mike Swan / n00dles101
 *  @since 1.0.0
 *
 */

class Arr{
   
    public function __construct() {
    	// nothing
	}

	/**
	* isAssoc
	*
	* <code>
	* 		$arr = arrya("a"=>"apple", "b"=>"bus");
	* 		$ret = Arr::isAssoc($arr); //returns true
	* </code>
	*
	* @param array $arr Array to check
	* @return boolean True is array is associative
	*/
	public static function isAssoc($arr){
	    return array_keys($arr) !== range(0, count($arr) - 1);
	}
	
	/**
	 * [removeValues description]
	 * @return [type] [description]
	 */
	public static function removeValues(){
	  $args = func_get_args();
	  return array_diff($args[0],array_slice($args,1));
	}

	/**
	 * [removeKeys description]
	 * @return [type] [description]
	 */
	public static function removeKeys(){
	  $args  = func_get_args();
	  return array_diff_key($args[0],array_flip(array_slice($args,1)));
	}

	public static function unique($array,$key){
		$tmp = array();
		$unique = array();
		foreach ($array as $item) {
		    if (!in_array($item[$key], $tmp)) {
		        $unique[] = $item[$key];
		        $tmp[] = $item[$key];
		    }
		}

		return  $unique;
	}

	public static function shuffleAssoc($array) {
        $keys = array_keys($array);
        $new = array();
        
        shuffle($keys);

        foreach($keys as $key) {
            $new[$key] = $array[$key];
        }

        return $new;
    }

    public static function arrayUnique( $array ){ 
        $rReturn = array (); 
        while ( list( $key, $val ) = each ( $array ) ){ 
            if ( !in_array( $val, $rReturn ) ) 
            array_push( $rReturn, $val ); 
        } 
        return $rReturn; 
    } 


   	public static function arraySearch($needle, $haystack, $row){     
	    foreach ($haystack as $k => $v){
	        if ($v[$row] === $needle){
	          return $k;
	          break;
	        } 
	  	}
	}
}