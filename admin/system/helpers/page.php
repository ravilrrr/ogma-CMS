<?php

/**
 *  ogmaCMS Pages Module
 *
 *  @package ogmaCMS
 *  @author Mike Swan / n00dles101
 *  @copyright 2013 Mike Swan / n00dles101
 *  @since 1.0.0
 *
 */

class Page {
    
    public $pageFields = array();
    
    public $pageId = '';
    
    public function __construct($id) {
        $this->pageFields = $this->getPage($id);
    }
    
    
    public function getPage($id) {
        
        $pages = Core::$pages;
        $pages->getCache();
        $records = $pages->records;
        $realid  = '';
        
        foreach ($records as $record) {
            if ($record['slug'] == $id) {
                $realid       = $record['id'];
                $this->pageId = $realid;
                break;
            }
        }
        
        if (file_exists(Core::$settings['rootpath'] . '/data/pages/' . $realid . '.xml')) {
            return (Xml::xml2array(Core::$settings['rootpath'] . '/data/pages/' . $realid . '.xml'));
        } else {
            return (Xml::xml2array(Core::$settings['rootpath'] . '/data/pages/0.xml'));
        }
        
    }
    /**
     * Get a page field
     *
     * Return a page field
     *  
     * <code>
     *      $title = $page->getPageField('title');
     * </code>
     *
     * @return string $field name of field to return
     */
    public function getPageField($field) {
        return $this->pageFields[$field];
    }
    
    /**
     * Get published date
     *
     * Return a pages published date
     *  
     * <code>
     *      $date = $page->getPageDate();
     * </code>
     *
     * @return string $field name of field to return
     */
    public function getPageDate() {
        return $this->pageFields['pubdate'];
    }
    
    /**
     * Get page Title
     *
     * Return a pages title
     *  
     * <code>
     *      $title = $page->getPageTitle();
     * </code>
     *
     * @return string $field name of field to return
     */
    public function getPageTitle() {
        return $this->pageFields['title'];
    }
    
    public function getChildren($id) {
        if ($id == '')
            $id = $this->getPageField('slug');
        $pages = new Query('pages');
        $pages->getCache();
        $children = $pages->find('parent = "' . $id . '"')->get();
        return $children;
    }
    
    public function getTitle($echo = true) {
        if ($echo) {
            echo $this->getPageField('title');
        } else {
            return $this->getPageField('title');
        }
    }
    
    public function getSlug($echo = true) {
        if ($echo) {
            echo $this->getPageField('slug');
        } else {
            return $this->getPageField('slug');
        }
    }
    
    public function getParent($echo = true) {
        if ($echo) {
            echo $this->getPageField('parent');
        } else {
            return $this->getPageField('parent');
        }
    }
    public function getTemplate($echo = true) {
        if ($echo) {
            echo $this->getPageField('template');
        } else {
            return $this->getPageField('template');
        }
    }
    
    public function getPubdate($echo = true, $time = true) {
        if ($echo) {
            echo $this->getPageField('pubdate');
        } else {
            return $this->getPageField('pubdate');
        }
    }
    
    
    public function getAuthor($echo = true) {
        if ($echo) {
            echo $this->getPageField('author');
        } else {
            return $this->getPageField('author');
        }
    }
    
    public function getStatus($echo = true) {
        if ($echo) {
            echo $this->getPageField('status');
        } else {
            return $this->getPageField('status');
        }
    }
    
    public function getMetaD($echo = true) {
        if ($echo) {
            echo $this->getPageField('metad');
        } else {
            return $this->getPageField('metad');
        }
    }
    
    public function getMetaT($echo = true) {
        if ($echo) {
            echo $this->getPageField('metat');
        } else {
            return $this->getPageField('metat');
        }
    }
    
    public function getMetak($echo = true) {
        if ($echo) {
            echo $this->getPageField('metak');
        } else {
            return $this->getPageField('metak');
        }
    }
    
    public function getRobots($echo = true) {
        if ($echo) {
            echo $this->getPageField('robots');
        } else {
            return $this->getPageField('robots');
        }
    }
    
    public function getContent() {
        $content = $this->pageFields['content'];
        Actions::executeAction('content-top');
        $content = Utils::safe_strip_decode($content);
        $content = Markdown($content);
        $content = Filters::execFilter('content', $content);
        echo $content;
        Actions::executeAction('content-bottom');
    }
    
    public static function pageParent($array, $parent) {
        $result = array();
        foreach ($array as $key => $value) {
            if ($value['parent'] == $parent) {
                $result[] = $array[$key];
            }
        }
        return $result;
    }
    
    
    public static function get404() {
        $page    = Xml::xml2array(Core::$settings['rootpath'] . '/data/pages/0.xml');
        $content = $page['content'];
        Actions::executeAction('content-top');
        $content = Utils::safe_strip_decode($content);
        $content = Markdown($content);
        $content = Filters::execFilter('content', $content);
        echo $content;
        Actions::executeAction('content-bottom');
    }
    
    public function get_header($full = true) {
        
        if ($this->getMetaD(false) != "") {
            $metad = $this->getMetaD(false);
        } else {
            $metad = Core::$site['metadesc'];
        }
        
        if ($this->getMetaK(false) != "") {
            $metak = $this->getMetaK(false);
        } else {
            $metak = Core::$site['metak'];
        }
        
        if ($this->getMetaT(false) != "") {
            $metat = $this->getMetaT(false);
        } else {
            $metat = $this->getTitle(false);
        }
        
        echo '<title>' . $metat . '</title>';
        echo '<meta name="description" content="' . $metad . '" />' . "\n";
        echo '<meta name="keywords" content="' . $metak . '" />' . "\n";
        echo '<META NAME="ROBOTS" CONTENT="' . $this->getRobots(false) . '">';
        if ($full) {
            //echo '<meta name="generator" content="'. $site_full_name .'" />'."\n";
            //echo '<link rel="canonical" href="'. get_page_url(true) .'" />'."\n";
        }
        Actions::executeAction('index-header');
    }
    
}