<?php
 /**
 *  ogmaCMS Shortcodes Module
 *
 *  @package ogmaCMS
 *  @author Mike Swan / n00dles101
 *  @copyright 2013 Mike Swan / n00dles101
 *  @since 1.0.0
 *
 */

class Shortcodes{
     
    public static  $shortcode_tags = array();
    public static  $shortcode_info = array(); 

    public function __construct() {
   		// nothing
    }

	/* 
	 * @uses $shortcode_tags,$shortcode_info
	 *
	 * @param string $tag Shortcode tag to be searched in post content.
	 * @param callable $func Hook to run when shortcode is found.
	 * @param string $desc , the desription of the shortcode
	 */
	public static function addShortcode($tag, $func, $desc="Default Description") {
	    if ( is_callable($func) ){
	    	Shortcodes::$shortcode_tags[(string)$tag] = $func;
	    	//self::addShortcodeDesc($tag, $tag, $desc);
			//Shortcodes::$shortcode_info[(string)$tag] = $desc;
		}
	}

	/* 
	 * @uses $shortcode_tags,$shortcode_info
	 *
	 * @param string $tag Shortcode tag 
	 * @desc Description of the shortcode
	 */
	public static function addShortcodeDesc($tag, $title = '', $func='') {
		Shortcodes::$shortcode_info[(string)$tag]['desc'] = $tag;
		Shortcodes::$shortcode_info[(string)$tag]['title'] = $title;
		Shortcodes::$shortcode_info[(string)$tag]['func'] = $func; 	
	}

	/**
	 * Search content for shortcodes and filter shortcodes through their hooks.
	 *
	 * If there are no shortcode tags defined, then the content will be returned
	 * without any filtering. 
	 * 
	 * @uses $shortcode_tags
	 * @uses getShortcodeRegex() Gets the search pattern for searching shortcodes.
	 *
	 * @param string $content Content to search for shortcodes
	 * @return string Content with shortcodes filtered out.
	 */
	public static  function doShortcode($content) {
	        $shortcode_tags = Shortcodes::$shortcode_tags;
	        if (empty($shortcode_tags) || !is_array($shortcode_tags))
	                return $content;
	        $tagnames = array_keys($shortcode_tags);
	        $tagregexp = join( '|', array_map('preg_quote', $tagnames) );
	        
			$removeTagsPattern = '/(\s*)(<p>\s*)(\[('.$tagregexp.')\b(.*?)(?:(\/))?\])(?:(.+?)\[\/\2\])?(.?)(\s*<\/p>)/';

			$content2 = preg_replace($removeTagsPattern, '$3 ', $content) ;
			
	        $pattern = Shortcodes::getShortcodeRegex();
	        return preg_replace_callback('/'.$pattern.'/s', array('Shortcodes' , 'doShortcodeTag'), htmlspecialchars_decode($content2));
	}

	/**
	 * Retrieve the shortcode regular expression for searching.
	 *
	 * The regular expression combines the shortcode tags in the regular expression
	 * in a regex class.
	 *
	 * The regular expresion contains 6 different sub matches to help with parsing.
	 *
	 * 1/6 - An extra [ or ] to allow for escaping shortcodes with double [[]]
	 * 2 - The shortcode name
	 * 3 - The shortcode argument list
	 * 4 - The self closing /
	 * 5 - The content of a shortcode when it wraps some content.
	 *
	 * @uses $shortcode_tags
	 *
	 * @return string The shortcode search regular expression
	 */
	public static function getShortcodeRegex() {
	        $shortcode_tags = Shortcodes::$shortcode_tags;

	        $tagnames = array_keys($shortcode_tags);

	        $tagregexp = join( '|', array_map('preg_quote', $tagnames) );
	        // WARNING! Do not change this regex without changing do_shortcode_tag() and strip_shortcodes()
	        return '(.?)\[('.$tagregexp.')\b(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?(.?)';
	}

	/**
	 * Regular Expression callable for do_shortcode() for calling shortcode hook.
	 * @see getShortcodeRegex for details of the match array contents.
	 *
	 * @access private
	 * @uses $shortcode_tags
	 *
	 * @param array $m Regular expression match array
	 * @return mixed False on failure.
	 */
	private static function doShortcodeTag( $m ) {
	        $shortcode_tags = Shortcodes::$shortcode_tags;
	        
	        // allow [[foo]] syntax for escaping a tag
	        if ( $m[1] == '[' && $m[6] == ']' ) {
	                return substr($m[0], 1, -1);
	        }

	        $tag = $m[2];
	        $attr = Shortcodes::shortcodeParseAtts( $m[3] );

	        if ( isset( $m[5] ) ) {
	                // enclosing tag - extra parameter
	                return $m[1] . call_user_func( $shortcode_tags[$tag], $attr, $m[5], $tag ) . $m[6];
	        } else {
	                // self-closing tag
	                return $m[1] . call_user_func( $shortcode_tags[$tag], $attr, NULL,  $tag ) . $m[6];
	        }
	}

	/**
	 * Retrieve all attributes from the shortcodes tag.
	 *
	 * The attributes list has the attribute name as the key and the value of the
	 * attribute as the value in the key/value pair. This allows for easier
	 * retrieval of the attributes, since all attributes have to be known.
	 *
	 *
	 * @param string $text
	 * @return array List of attributes and their value.
	 */
	public static function shortcodeParseAtts($text) {
	        $atts = array();
	        $pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
	        $text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);
	        if ( preg_match_all($pattern, $text, $match, PREG_SET_ORDER) ) {
	                foreach ($match as $m) {
	                        if (!empty($m[1]))
	                                $atts[strtolower($m[1])] = stripcslashes($m[2]);
	                        elseif (!empty($m[3]))
	                                $atts[strtolower($m[3])] = stripcslashes($m[4]);
	                        elseif (!empty($m[5]))
	                                $atts[strtolower($m[5])] = stripcslashes($m[6]);
	                        elseif (isset($m[7]) and strlen($m[7]))
	                                $atts[] = stripcslashes($m[7]);
	                        elseif (isset($m[8]))
	                                $atts[] = stripcslashes($m[8]);
	                }
	        } else {
	                $atts = ltrim($text);
	        }
	        return $atts;
	}

	/**
	 * Combine user attributes with known attributes and fill in defaults when needed.
	 *
	 * The pairs should be considered to be all of the attributes which are
	 * supported by the caller and given as a list. The returned attributes will
	 * only contain the attributes in the $pairs list.
	 *
	 * If the $atts list has unsupported attributes, then they will be ignored and
	 * removed from the final returned list.
	 *
	 *
	 * @param array $pairs Entire list of supported attributes and their defaults.
	 * @param array $atts User defined attributes in shortcode tag.
	 * @return array Combined and filtered attribute list.
	 */
	public static function shortcodeAtts($pairs, $atts) {
	        $atts = (array)$atts;
	        $out = array();
	        foreach($pairs as $name => $default) {
	                if ( array_key_exists($name, $atts) )
	                        $out[$name] = $atts[$name];
	                else
	                        $out[$name] = $default;
	        }
	        return $out;
	}

	/**
	 * Remove all shortcode tags from the given content.
	 *
	 * @uses $shortcode_tags
	 *
	 * @param string $content Content to remove shortcode tags.
	 * @return string Content without shortcode tags.
	 */
	public function strip_shortcodes( $content ) {
	        $shortcode_tags = Shortcodes::$shortcode_tags;

	        if (empty($shortcode_tags) || !is_array($shortcode_tags))
	                return $content;

	        $pattern = Shortcodes::getShortcodeRegex();

	        return preg_replace('/'.$pattern.'/s', '$1$6', $content);
	}
    

    
}

?>
