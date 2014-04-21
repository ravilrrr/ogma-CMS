<?php

/**
 *	ogmaCMS Utils Module
 *
 *	@package ogmaCMS
 *	@author Mike Swan / n00dles101
 *	@copyright 2013 Mike Swan / n00dles101
 *	@since 1.0.0
 *
 */

class Utils {
    /**
     * Manipulate values befor inserting into XML files 
     */
    public static function manipulateValues($value, $type) {
        switch ($type) {
            case "password":
                return hash('sha1', $value);
                break;
            case "textarea":
            case "editor":
                return $value;
                break;
            case "date":
            case "datetimepicker":
            case "datepicker":
            case "timepicker":
                return strtotime(str_replace('/', '-', $value));
                break;
            case "slug":
                return Url::urlSlug($value);
                break;
            case "":
                return ($value == 'true') ? 1 : 0;
                break;
            default:
                return $value;
                break;
        }
    }
    
    /**
     * Returns the ID of a record
     */
    public static function findRecordID($table, $row, $search) {
        
        $pages = new Query($table);
        $pages->getCache();
        $records = $pages->records;
        $realid  = '';
        
        foreach ($records as $record) {
            if ($record[$row] == $search) {
                $realid = $record['id'];
                break;
            }
        }
        return $realid;
        
    }
    
    /**
     * Returns Safe Strip Decoded Text
     */
    public static function safe_strip_decode($text) {
        if (get_magic_quotes_gpc() == 0) {
            $text = htmlspecialchars_decode($text, ENT_QUOTES);
        } else {
            $text = stripslashes(htmlspecialchars_decode($text, ENT_QUOTES));
        }
        return $text;
    }
    
    /**
     * Returns encoded text
     */
    public static function safe_slash_html($text) {
        if (get_magic_quotes_gpc() == 0) {
            $text = addslashes(htmlspecialchars($text, ENT_QUOTES, 'UTF-8'));
        } else {
            $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        }
        $text = str_replace(chr(12), '', $text);
        $text = str_replace(chr(3), ' ', $text);
        return $text;
    }
    
    /**
     * Returns a random password of a given lenght
     */
    public static function resetPassword($length = 6) {
        $password = "";
        $possible = "123456789bcdfghjkmnpqrstvwxyz";
        
        for ($i = 0; $i < $length; $i++) {
            $char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);
            $password .= $char;
        }
        
        return $password;
    }
    
    /**
     * Returns a snippet of given lenght of text
     */
    public static function resizeValue($value, $length) {
        return (strlen($value) > $length) ? substr($value, 0, $length) . '...' : $value;
    }
    
    public static function myUrl() {
        
        $url = (!empty($_SERVER['HTTPS'])) ? "https://" . $_SERVER['SERVER_NAME'] : "http://" . $_SERVER['SERVER_NAME'];
        
        return $url . '/';
        
    }
    
    public static function addTrailingSlash($url) {
        if (substr($url, -1) !== '/') {
            return $url . '/';
        } else {
            return $url;
        }
    }
    
    public static function isRemoveable($dir) {
        $folder = opendir($dir);
        while ($file = readdir($folder))
            if ($file != '.' && $file != '..' && (!is_writable($dir . "/" . $file) || (is_dir($dir . "/" . $file) && !Utils::isRemoveable($dir . "/" . $file)))) {
                echo $dir . "/" . $file;
                return false;
            }
        return true;
    }
    
    
} // End of Utils