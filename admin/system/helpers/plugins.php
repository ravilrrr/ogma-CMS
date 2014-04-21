<?php

/**
 *  ogmaCMS Plugins Module
 *
 *  @package ogmaCMS
 *  @author Mike Swan / n00dles101
 *  @copyright 2013 Mike Swan / n00dles101
 *  @since 1.0.0
 *
 */

class Plugins {
    
    public static $installedPlugins = array();
    public static $registeredPlugins = array();
    public static $system = 'backend';
    
    public function __construct($system = 'backend') {
        Plugins::$system = $system;
        
        Plugins::readPluginsXml();
        Plugins::getPluginFiles();
        
        Plugins::checkUpdate();
        
        Plugins::initializePlugins();
    }
    
    
    public function readPluginsXml() {
        
        $file = Core::$settings['rootpath'] . '/data/plugins.xml';
        if (file_exists($file)) {
            $thisfile = file_get_contents($file);
        } else {
            $thisfile = '<?xml version="1.0" encoding="utf-8"?><root></root>';
        }
        $data       = simplexml_load_string($thisfile);
        $components = @$data->item;
        if (count($components) != 0) {
            foreach ($components as $component) {
                $name                                                 = (string) $component->name;
                Plugins::$installedPlugins[(string) $component->name] = array(
                    'status' => (string) $component->status,
                    'name' => (string) $component->name
                );
            }
        }
    }
    
    private static function savePluginsFile() {
        $file       = Core::$settings['rootpath'] . '/data/plugins.xml';
        $tmpPlugins = array();
        $count      = 0;
        foreach (Plugins::$installedPlugins as $plugin) {
            $tmpPlugins[$count] = array(
                'status' => $plugin['status'],
                'name' => $plugin['name']
            );
            $count++;
        }
        
        $xml = Xml::arrayToXml($tmpPlugins);
        $ret = file_put_contents($file, $xml);
    }
    
    private static function checkUpdate() {
        // first check that plugins have not been removed. 
        $saveFile = false;
        foreach (Plugins::$installedPlugins as $plugin) {
            if (!array_key_exists($plugin['name'], Plugins::$registeredPlugins)) {
                unset(Plugins::$installedPlugins[$plugin['name']]);
                $saveFile = true;
            }
        }
        if ($saveFile)
            Plugins::savePluginsFile();
        
        if (Core::getFilenameId() == "plugins") {
            $status = isset($_GET['status']) ? $_GET['status'] : '';
            $plugin = isset($_GET['plugin']) ? $_GET['plugin'] : '';
            if ($status == "activate") {
                Plugins::$installedPlugins[$plugin] = array(
                    'status' => '1',
                    'name' => $plugin
                );
                Plugins::savePluginsFile();
            }
            if ($status == "deactivate") {
                unset(Plugins::$installedPlugins[$plugin]);
                Plugins::savePluginsFile();
            }
            
        }
    }
    
    private static function initializePlugins() {
        $language = Core::$site['language'];
        foreach (Plugins::$installedPlugins as $plugin) {
            $currentPlugin = $plugin['name'];
            switch (Plugins::$system) {
                case 'backend':
                    if (method_exists($currentPlugin, 'init') && (array_key_exists($currentPlugin, Plugins::$installedPlugins) && Plugins::$installedPlugins[$currentPlugin]['status'] == 1)) {
                        call_user_func_array(array(
                            $currentPlugin,
                            'init'
                        ), array());
                    }
                    break;
                case 'frontend':
                    if (method_exists($currentPlugin, 'initFrontend') && (array_key_exists($currentPlugin, Plugins::$installedPlugins) && Plugins::$installedPlugins[$currentPlugin]['status'] == 1)) {
                        call_user_func_array(array(
                            $currentPlugin,
                            'initFrontend'
                        ), array());
                    }
                    break;
                default:
                    # code...
                    break;
            }
            
            if (method_exists($currentPlugin, 'initShortcodes') && (array_key_exists($currentPlugin, Plugins::$installedPlugins) && Plugins::$installedPlugins[$currentPlugin]['status'] == 1)) {
                call_user_func_array(array(
                    $currentPlugin,
                    'initShortcodes'
                ), array());
            }
        }
    }
    
    private static function getPluginFiles() {
        $pluginPath = Core::$settings['pluginpath'];
        $files      = Core::getFiles($pluginPath, 'php');
        foreach ($files as $file) {
            $ext = substr($file, strrpos($file, '.') + 1);
            if ($ext == 'php')
                require_once($pluginPath . $file);
        }
        
    }
    
    public static function registerPlugin($name, $title, $desc, $version, $author, $url) {
        Plugins::$registeredPlugins[$name]['name']    = $name;
        Plugins::$registeredPlugins[$name]['title']   = $title;
        Plugins::$registeredPlugins[$name]['desc']    = $desc;
        Plugins::$registeredPlugins[$name]['version'] = $version;
        Plugins::$registeredPlugins[$name]['author']  = $author;
        Plugins::$registeredPlugins[$name]['url']     = $url;
    }
    
    
    public static function listPlugins() {
        Debug::pa(Plugins::$installedPlugins);
    }
    
    public static function returnPlugins() {
        return (Plugins::$registeredPlugins);
    }
    
}