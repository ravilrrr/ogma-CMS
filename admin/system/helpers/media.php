<?php

/**
 *  OGMA CMS Media Module
 *
 *  @package ogmaCMS
 *  @author Mike Swan / n00dles101
 *  @copyright 2013 Mike Swan / n00dles101
 *  @since 1.0.0
 *
 */

class Media {
    
    public $mediaGallery = array();
    public $tags = array();
    
    public function __construct() {
        // nothing          
    }
    
    public function getMedia($id) {
        if ($id != '') {
            $gallery            = new Query('media');
            $this->mediaGallery = $gallery->getCache()->find('tag = ' . $id)->order('showorder')->get();
            return $this->mediaGallery;
        } else {
            return array();
        }
    }
    
    public function showMedia($item) {
        $codes = array(
            '/\$id/',
            '/\$title/',
            '/\$fileurl/',
            '/\$tag/',
            '/\$showorder/',
            '/\$description/',
            '/\$caption/',
            '/\$alt/'
        );
        
        foreach ($this->mediaGallery as $slide) {
            $replace_code = array(
                $slide['id'],
                $slide['title'],
                $slide['fileurl'],
                $slide['tag'],
                $slide['showorder'],
                $slide['description'],
                $slide['caption'],
                $slide['alt']
            );
            $mediaItem    = preg_replace($codes, $replace_code, $item);
            echo $mediaItem;
        }
    }
    
    
    public function randomize() {
        $this->mediaGallery = Arr::shuffleAssoc($this->mediaGallery);
        //print_r($this->mediaGallery);
    }
    
    public function top($num) {
        $this->mediaGallery = array_slice($this->mediaGallery, 0, $num, true);
    }
    
    public static function getTags() {
        $gallery = new Query('media');
        $tags    = $gallery->getCache()->get();
        $out     = array();
        foreach ($tags as $item) {
            $out[$item['tag']] = $item['tag'];
        }
        return $out;
    }
    
    
}