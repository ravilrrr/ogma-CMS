<?php

/**
 *  OGMA CMS Actions Module
 *
 *  @package ogmaCMS
 *  @author Mike Swan / n00dles101
 *  @copyright 2013 Mike Swan / n00dles101
 *  @since 1.0.0
 *
 */

class Filesystem {
	
    public function __construct($table) {
        // nothing
    }
    
    
    public static function writeFile($file, $content) {
        $ret = file_put_contents($file, $content);
        Debug::addLog("Writing file - (" . $ret . ")" . $file);
        return $ret;
    }
    
    public static function readFile($file) {
        Debug::addLog("Reading file - " . $file);
        return file_get_contents($file);
    }
}