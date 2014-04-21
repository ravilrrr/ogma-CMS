<?php

/**
 *  ogmaCMS Stylesheet Module
 *
 *  @package ogmaCMS
 *  @author Mike Swan / n00dles101
 *  @copyright 2013 Mike Swan / n00dles101
 *  @since 1.0.0
 *
 */

class Stylesheet {
    public static $stylesheets = array();
    
    public function __construct() {
        
    }
    
    
    public static function add($file, $load = 'front', $priority = 10, $pages = array()) {
        Stylesheet::$stylesheets[] = array(
            'src' => (string) $file,
            'where' => $load,
            'priority' => $priority,
            'pages' => $pages
        );
    }
    
    public static function show($where) {
        $id = $stylesheets = Core::subvalSort(Stylesheet::$stylesheets, 'priority');
        foreach ($stylesheets as $style) {
            if ($style['where'] == $where) {
                if (count($style['pages']) == 0 or in_array(Core::getFilenameId(), $style['pages'])) {
                    echo "<link href='" . $style['src'] . "'  rel='stylesheet' />\n";
                }
            }
        }
        
        
    }
    
    
}