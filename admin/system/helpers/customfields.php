<?php

/** 
 *  OGMA CMS Customfields Module
 *
 *  @package ogmaCMS
 *  @author Mike Swan / n00dles101
 *  @copyright 2013 Mike Swan / n00dles101
 *  @since 1.0.0
 *
 */

class Customfields {
    
    public static $fields = array();
    
    public function __construct() {
        self::loadFields();
    }
    
    public static function loadFields() {
        $file = ROOT . 'data/customfields.xml';
        if (file_exists($file)) {
            // load the xml file and setup the array. 
            $thisfile = file_get_contents($file);
        } else {
            $thisfile = '<?xml version="1.0" encoding="utf-8"?><root></root>';
        }
        
        $data       = simplexml_load_string($thisfile);
        $components = @$data->item;
        if (count($components) != 0) {
            foreach ($components as $component) {
                $name                                            = (string) $component->name;
                Customfields::$fields[(string) $component->name] = array(
                    'name' => (string) $component->name,
                    'type' => (string) $component->type,
                    'table' => (string) $component->table,
                    'desc' => (string) $component->desc
                );
            }
        }
    }
    
    public static function getFields() {
        return Customfields::$fields;
    }
    
}