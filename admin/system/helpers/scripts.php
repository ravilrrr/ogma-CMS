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

class Scripts {
    public static $scripts = array();
    
    public function __construct() {
        
    }
    
    
    public static function add($file, $load = 'front', $priority = 10, $pages = array(), $in_footer = FALSE) {
        Scripts::$scripts[] = array(
            'src' => $file,
            'where' => $load,
            'priority' => $priority,
            'in_footer' => $in_footer,
            'pages' => $pages
        );
    }
    
    public static function show($where, $in_footer = FALSE) {
        $scripts = Core::subvalSort(Scripts::$scripts, 'priority');
        foreach ($scripts as $script) {
            if ($script['where'] == $where) {
                if ($in_footer && $script['in_footer'] == TRUE) {
                    if (count($script['pages']) == 0 or in_array(Core::getFilenameId(), $script['pages'])) {
                        echo "<script src='" . $script['src'] . "'></script>\n";
                    }
                }
                
                if (!$in_footer && $script['in_footer'] == FALSE) {
                    if (count($script['pages']) == 0 or in_array(Core::getFilenameId(), $script['pages'])) {
                        echo "<script src='" . $script['src'] . "'></script>\n";
                    }
                }
            }
        }
        
    }
    
    
}