<?php 

 /**
 *  ogmaCMS Blog Module
 *
 *  @package ogmaCMS
 *  @author Mike Swan / n00dles101
 *  @copyright 2013 Mike Swan / n00dles101
 *  @since 1.0.0
 *
 */

class Blog {

    public $blogs = array();
    public $entries = array();
    public $tags = array();
    public $pubdates = array();
    public $pagesize = 5;


    public function __construct() {
        $this->blogs = new Query('blog');
        $this->tags = $this->blogs->getCache()->find('status = Published')->unique('tags')->get();
        $this->entries = $this->blogs->reload()->find('status = Published')->order('pubdate','desc')->get();
        $this->getDates();
    }

    public function getDates(){
        foreach ($this->entries as $record) {
            $this->pubdates[] = $record['pubdate'];
        }
    }

    public function getArchives($title, $totals = false){
        $lmonths=array("January","February","March","April","May","June","July","August","September","October","November","December");
        echo '<div class="sidebar-module">';
        echo '<h4>'.$title.'</h4>';
        $dates=array();
        foreach ($this->pubdates as $record) {
            $d_var=explode('/',date('d/F/Y',(int)$record ));    
            $m_var=explode('/',date('d/n/Y',(int)$record ));
            $year=  $d_var[2];
            $month= $m_var[1];      
            @$dates[$year][$month]+= 1;
        }
        echo '<ol class="list-unstyled">';
        foreach ($dates as $year=>$months){
            foreach($months as $mon=>$num){
                $value = ($totals==true) ? " (".$num.")" : "";
                echo "<li><a href='/blog/archive/{$year}/{$mon}' title='Show {$year} / {$mon} Articles'>{$lmonths[$mon-1]} {$year}</a> ".$value."</li>";
            }
            
        }
        echo '</ol>';
        echo '</div>';
    }

    public function getCategories($title, $totals = false){
        $temptags = array();
        foreach ($this->tags as $tag) {
            $db = explode(',', $tag);    
            while(list($key, $value) = each($db)){
                if ($value!=''){
                   @$temptags[(string)trim($value)] += 1;
                }
            }
        }
        echo '<div class="sidebar-module">';
        echo '<h4>'.$title.'</h4>';
        echo '<ol class="list-unstyled">';
        foreach ($temptags as $tag=>$value) {
                $value = ($totals==true) ? " (".$value.")" : "";
                echo "<li><a href='/blog/tag/{$tag}' title='Show Tag ".ucfirst($tag)." Articles'>".ucfirst($tag)."</a>".$value."</li>";
        }
        echo '</ol>';
        echo '</div>';
    }

    public function getTags($title){   
        $temptags = array();
        foreach ($this->tags as $tag) {
            $db = explode(',', $tag);    
            while(list($key, $value) = each($db)){
                if ($value!=''){
                   @$temptags[(string)trim($value)] += 1;
                }
            }
        }
        echo '<div class="sidebar-module">';
        echo '<h4>'.$title.'</h4>';
        foreach ($temptags as $tag=>$value) {
            echo '<span class="label label-default">'.$tag.'</span>';
        }
        echo '</div>';

    }


    public function displayBlogPost($id, $full = true){
        $record = $this->blogs->getFullRecord($id);
        Actions::executeAction('bootstrap-blog-before-post'); 
        echo '<div class="blog-post">';
        echo '<h2 class="blog-post-title">'.$record['title'].'</h2>';
        echo '<p class="blog-post-meta">'.Core::date($record['pubdate'], true).' by <a href="#">'.$record['author'].'</a></p>'; 
        $content = Utils::safe_strip_decode($record['content']);
        $content = Markdown($content);
        if (!$full){
            $pos = strpos($content,"[more]");
            if ($pos>0) {
                $content = substr($content,0,$pos);
                $content .= '<a href="/blog/'.$record['slug'].'" class="btn btn-default btn-sm" role="button">Read More..</a>';
            }
        } else {
            $content = str_replace('[more]', '', $content);
        }
        $content = Filters::execFilter('content',$content);   
        echo $content;
        if ($record['comments']==true){
            echo '<div class="comments" >';
            Actions::executeAction('bootstrap-blog-comments'); 
            echo '</div>';
        }
        echo '</div>'; 
        Actions::executeAction('bootstrap-blog-after-post');
    }



    public function displayBlogPosts($full = false){
        $records = $this->entries;
        if (count($records)>0){
            foreach ($records as $record) {
                $fullrecord = $this->blogs->getFullRecord($record['id']);
                echo '<div class="blog-post">';
                echo '<h2 class="blog-post-title"><a href="/blog/'.$record['slug'].'">'.$record['title'].'</a></h2>';
                echo '<p class="blog-post-meta">'.Core::date($record['pubdate'], true).' by <a href="#">'.$record['author'].'</a>, Posted in '.$record['tags']; 
                if (Plugins::enabled('comments')) echo ' <a href="'.Core::$site['siteurl'].'blog/'.$record['slug'].'#disqus_thread">comments</a>';
                echo '</p>';
                $content = Utils::safe_strip_decode($fullrecord['content']);
                $content = Markdown($content);
                if (!$full){
                    $pos = strpos($content,"[more]");
                    if ($pos>0) {
                        $content = substr($content,0,$pos);
                        $content .= '<a href="/blog/'.$record['slug'].'" class="btn btn-default btn-sm" role="button">Read More..</a>';
                    }
                } else {
                    $content = str_replace('[more]', '', $content);
                }
                $content = Filters::execFilter('content',$content);
                echo $content;           
                echo '</div>';
            }
        } else {
            Page::get404();
        }
    }


    // set the default paging 
    public function setPageSize($size){
        $this->pagesize = $size;
    }



}