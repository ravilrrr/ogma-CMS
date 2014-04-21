<?php

/**
 *  OGMA CMS Editor Module
 *
 *  @package ogmaCMS
 *  @author Mike Swan / n00dles101
 *  @copyright 2013 Mike Swan / n00dles101
 *  @since 1.0.0
 *
 */

class Ogmaeditor {
    
    public static $maintoolbar = array('h1' => array('name' => 'H1', 'text' => "H1", 'icon' => '', 'insertBefore' => '#', 'insertAfter' => '', 'modal' => ''), 'h2' => array('name' => 'H2', 'text' => "H2", 'icon' => '', 'insertBefore' => '##', 'insertAfter' => '', 'modal' => ''), 'h3' => array('name' => 'H3', 'text' => "H3", 'icon' => '', 'insertBefore' => '###', 'insertAfter' => '', 'modal' => ''), 'bold' => array('name' => 'bold', 'text' => "", 'icon' => 'fa:fa fa-fw fa-bold', 'insertBefore' => '_', 'insertAfter' => '_', 'modal' => ''), 'italic' => array('name' => 'italic', 'text' => "", 'icon' => 'fa:fa fa-fw fa-italic', 'insertBefore' => '*', 'insertAfter' => '*', 'modal' => ''), 'quote' => array('name' => 'quote', 'text' => "", 'icon' => 'fa:fa fa-fw fa-quote-right', 'insertBefore' => '> ', 'insertAfter' => '', 'modal' => ''), 'link' => array('name' => 'link', 'text' => "", 'icon' => 'fa:fa fa-fw fa-link', 'insertBefore' => "[", 'insertAfter' => ']()', 'modal' => '') );
    
    public function __construct() {
        // nothing			
    }
    
    public static function addButton($name, $text, $icon, $insertBefore, $insertAfter, $modal = '') {
        Ogmaeditor::$maintoolbar[$name] = array(
            'name' => $name,
            'text' => $text,
            'icon' => $icon,
            'insertBefore' => $insertBefore,
            'insertAfter' => $insertAfter,
            'modal' => $modal
        );
    }
    
    public static function displayToolbar($instance, $preview = false, $fullscreen = true) {
        $modal   = '';
        $toolbar = '		<div class="btn-toolbar editor-toolbar" role="toolbar">';
        $toolbar .= '		  <div class="btn-group btn-group-sm" data-editor="' . $instance . '" >';
        //<button type="button" class="btn btn-default btn-h1">H1</button><button type="button" class="btn btn-default">H2</button>
        foreach (Ogmaeditor::$maintoolbar as $item => $values) {
            if ($values['modal'] != '') {
                $modal = ' data-toggle="modal" data-target="#' . $values['modal'] . '" ';
            }
            if ($values['icon'] == "") {
                $toolbar .= '<button type="button" class="editor-btn btn btn-default" data-placement="bottom"  data-toggle="tooltip" title="' . $values['name'] . '" ' . $modal . ' data-insertbefore="' . $values['insertBefore'] . '" data-insertafter="' . $values['insertAfter'] . '" >' . $values['text'] . '</button>';
            } else {
                $toolbar .= '<button type="button" class="editor-btn btn btn-default" data-placement="bottom"  data-toggle="tooltip" title="' . $values['name'] . '"  ' . $modal . ' data-insertbefore="' . $values['insertBefore'] . '" data-insertafter="' . $values['insertAfter'] . '" >';
                $icontype = explode(':', $values['icon']);
                if ($icontype[0] == "fa") {
                    $toolbar .= '<i class="fa fa-' . $icontype[1] . '"></i>';
                } else {
                    $toolbar .= '<span class="' . $values['icon'] . '"></span>';
                }
                $toolbar .= '</button>';
            }
        }
        $toolbar .= '</div>';
        $toolbar .= '		  <div class="btn-group btn-group-sm" data-editor="' . $instance . '" >';
        $toolbar .= '&nbsp;<button type="button" class="btn btn-info showshortcodes" id="show_shortcodes" data-placement="bottom" data-toggle="tooltip" title="' . __("SHORTCODES") . '"><span class="glyphicon glyphicon-th-large"></span></button>&nbsp;';
        //if ($fullscreen) $toolbar .= '<button type="button" class="btn btn-primary" id="showfullscreen"><span class="glyphicon glyphicon-fullscreen"></span></button>'; 
        if ($preview) {
            $toolbar .= '<button type="button" class="btn btn-primary" id="hidefullscreen" data-placement="bottom" data-area="' . $instance . '" data-toggle="tooltip" title="' . __("FULLSCREEN") . '"><span class="glyphicon glyphicon-fullscreen"></span></button><button type="button" class="btn btn-success" id="previewme"  data-placement="bottom" data-toggle="tooltip" title="' . __("REFRESH") . '"><span class="glyphicon glyphicon-eye-open"></span></button>';
        } else {
            $toolbar .= '<button type="button" class="btn btn-primary" id="showfullscreen" data-placement="bottom" data-area="' . $instance . '" data-toggle="tooltip" title="' . __("FULLSCREEN") . '"><span class="glyphicon glyphicon-fullscreen"></span></button>';
        }
        
        $toolbar .= '</div>';
        $toolbar .= '		</div>';
        //$toolbar .= self::addShortcodeDropdown();
        return $toolbar;
    }
    
    public static function addShortcodeDropdown() {
        $value = "<select  class='form-control' id='shortcode_value'>";
        $value .= "<option value=''>" . __("SELECTSHORTCODE") . "</option>";
        foreach (Shortcodes::$shortcode_info as $key) {
            $value .= "<option value='" . $key['func'] . "'>" . $key['title'] . "</option>";
            
        }
        $value .= "</select>";
        return $value;
    }
    
}