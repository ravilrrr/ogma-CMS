<?php

/**
 *  OGMA CMS Sitemap Module
 *
 *  @package ogmaCMS
 *  @author Mike Swan / n00dles101
 *  @copyright 2013 Mike Swan / n00dles101
 *  @since 1.0.0
 *
 */

class Sitemap {
    
    public $sitemapxml;
    
    public function __construct() {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset></urlset>');
        $xml->addAttribute('xsi:schemaLocation', 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd', 'http://www.w3.org/2001/XMLSchema-instance');
        $xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        
        $this->sitemapxml = $xml;
    }
    
    public function addPages() {
        $xml = $this->sitemapxml;
        
        $pages   = new Query('pages');
        $records = $pages->getCache()->find("id != 0, status = Published")->get();
        foreach ($records as $record) {
            $url_item = $xml->addChild('url');
            $url_item->addChild('loc', Url::returnUrl($record['slug']));
            $url_item->addChild('lastmod', date('c', $record['pubdate']));
            $url_item->addChild('changefreq', 'weekly');
            $url_item->addChild('priority', '1.0');
        }
        $this->sitemapxml = $xml;
        Actions::executeAction('sitemap-add');
    }
    
    public function addEntry($loc, $date) {
        $xml      = $this->sitemapxml;
        $url_item = $xml->addChild('url');
        $url_item->addChild('loc', $loc);
        $url_item->addChild('lastmod', date('c', $date));
        $url_item->addChild('changefreq', 'weekly');
        $url_item->addChild('priority', '1.0');
        $this->sitemapxml = $xml;
    }
    
    public function saveSitemap() {
        $file = Core::$settings['rootpath'] . 'sitemap.xml';
        $xml  = $this->sitemapxml;
        return @$xml->asXML($file);
    }
    
    public function getSitemap() {
        echo "<pre>";
        var_dump($this->sitemapxml);
        echo "</pre>";
    }
}