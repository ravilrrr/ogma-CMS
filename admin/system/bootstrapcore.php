<?php

class Bootstrapcore extends Theme {
    
    
    public function __construct() {
        
    }
    
    
    public static function bootstrap_notice($atts, $content = null) {
        extract(Shortcodes::shortcodeAtts(array(
            'type' => 'unknown'
        ), $atts));
        $content = preg_replace('/<br class="nc".\/>/', '', $content);
        $result  = '<div class="alert alert-' . $type . ' alert-dismissable">';
        $result .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
        $result .= Shortcodes::doShortcode($content);
        $result .= '</div>';
        return $result;
    }
    
    public static function bootstrap_buttons($atts, $content = null) {
        extract(Shortcodes::shortcodeAtts(array(
            'size' => 'default',
            'type' => 'default',
            'value' => 'button',
            "icon" => '',
            'href' => "#"
        ), $atts));
        
        $content = preg_replace('/<br class="nc".\/>/', '', $content);
        $result  = '<a class="btn btn-' . $size . ' btn-' . $type . '" href="' . $href . '">';
        if ($icon != '') {
            $result .= '<span class="glyphicon glyphicon-' . $icon . '"></span> ';
        }
        $result .= $value;
        $result .= '</a>';
        return $result;
    }
    
    public static function bootstrap_collapse($atts, $content = null) {
        extract(Shortcodes::shortcodeAtts(array(
            'id' => ''
        ), $atts));
        $content = preg_replace('/<br class="nc".\/>/', '', $content);
        $result  = '<div class="panel-group" id="' . $id . '">';
        $result .= Shortcodes::doShortcode($content);
        $result .= '</div>';
        return $result;
    }
    
    
    
    public static function bootstrap_citem($atts, $content = null) {
        extract(Shortcodes::shortcodeAtts(array(
            'id' => '',
            'title' => 'Collapse title',
            'parent' => ''
        ), $atts));
        $content = preg_replace('/<br class="nc".\/>/', '', $content);
        $result  = ' <div class="panel panel-default">';
        $result .= ' <div class="panel-heading">';
        $result .= '    <h4 class="panel-title">';
        $result .= '<a class="accordion-toggle" data-toggle="collapse" data-parent="#' . $parent . '" href="#' . $id . '">';
        $result .= $title;
        $result .= '</a>';
        $result .= '</h4>';
        $result .= '</div>';
        $result .= '<div id="' . $id . '" class="panel-collapse collapse">';
        $result .= '<div class="panel-body">';
        $result .= Shortcodes::doShortcode($content);
        $result .= '</div>';
        $result .= '</div>';
        $result .= '</div>';
        return $result;
    }
    
    public static function bootstrap_row($atts, $content = null) {
        extract(Shortcodes::shortcodeAtts(array(
            'class' => 'row'
        ), $atts));
        $content = preg_replace('/<br class="nc".\/>/', '', $content);
        $result  = '<div class="' . $class . '">';
        $result .= Shortcodes::doShortcode($content);
        $result .= '</div>';
        return $result;
    }
    
    
    public static function bootstrap_span($atts, $content = null) {
        extract(Shortcodes::shortcodeAtts(array(
            'class' => 'col-xs-1'
        ), $atts));
        
        $result = '<div class="' . $class . '">';
        $result .= Shortcodes::doShortcode($content);
        $result .= '</div>';
        return $result;
    }
    
    public static function bootstrap_icons($atts, $content = null) {
        extract(Shortcodes::shortcodeAtts(array(
            'name' => 'default'
        ), $atts));
        
        $content = preg_replace('/<br class="nc".\/>/', '', $content);
        $result  = '<span class="glyphicon glyphicon-' . $name . '"></span>';
        return $result;
    }
    
    public static function bootstrap_tooltip($atts, $content = null) {
        extract(Shortcodes::shortcodeAtts(array(
            'placement' => 'top',
            'trigger' => 'hover',
            'href' => "#"
        ), $atts));
        
        $placement = (in_array($placement, array(
            'top',
            'right',
            'bottom',
            'left'
        ))) ? $placement : "top";
        $content   = preg_replace('/<br class="nc".\/>/', '', $content);
        $title     = explode("\n", wordwrap($content, 20, "\n"));
        $result    = '<a href="#" data-toggle="tooltip" title="' . $title[0] . '" data-placement="' . $placement . '" data-trigger="' . $trigger . '">' . $content . '</a>';
        return $result;
    }
    
    public static function bootstrap_panel($atts, $content = null) {
        extract(Shortcodes::shortcodeAtts(array(
            'type' => 'default',
            'title' => '',
            'footer' => ''
        ), $atts));
        
        $content = preg_replace('/<br class="nc".\/>/', '', $content);
        $result  = '<div class="panel panel-' . $type . '">';
        if ($title != '')
            $result .= '<div class="panel-heading">' . $title . '</div>';
        $result .= '<div class="panel-body">';
        $result .= Shortcodes::doShortcode($content);
        $result .= '</div>';
        if ($footer != '')
            $result .= '<div class="panel-footer">' . $footer . '</div>';
        $result .= '</div>';
        return $result;
    }
    
    public static function bootstrap_badge($atts, $content = null) {
        $content = preg_replace('/<br class="nc".\/>/', '', $content);
        $result  = '<span class="badge">' . Shortcodes::doShortcode($content) . '</span>';
        return $result;
    }
    
    public static function bootstrap_lead($atts, $content = null) {
        $content = preg_replace('/<br class="nc".\/>/', '', $content);
        $result  = '<p class="lead">' . Shortcodes::doShortcode($content) . '</p>';
        return $result;
    }
    
    public static function bootstrap_label($atts, $content = null) {
        extract(Shortcodes::shortcodeAtts(array(
            'type' => 'default'
        ), $atts));
        
        $content = preg_replace('/<br class="nc".\/>/', '', $content);
        $result  = '<span class="label label-' . $type . '">' . Shortcodes::doShortcode($content) . '</span>';
        return $result;
    }
    
    public static function bootstrap_jumbotron($atts, $content = null) {
        extract(Shortcodes::shortcodeAtts(array(
            'fullwidth' => 'true'
        ), $atts));
        $content = preg_replace('/<br class="nc".\/>/', '', $content);
        $result  = '<div class="jumbotron">';
        if ($fullwidth == 'true') {
            $result .= '<div>';
        }
        $result .= Shortcodes::doShortcode($content);
        if ($fullwidth == 'true') {
            $result .= '</div>';
        }
        $result .= '</div>';
        return $result;
    }
    
    public static function ogmaSiteTitle() {
        return Core::$site['sitename'];
    }
    public static function ogmaTitle() {
        global $page;
        return $page->getTitle(false);
    }
    public static function ogmaAuthor() {
        global $page;
        return $page->getAuthor(false);
    }
    public static function addiCode($atts, $content = null) {
        extract(Shortcodes::shortcodeAtts(array(
            "mode" => null
        ), $atts));
        return '<code>' . $content . '</code>';
    }
    
    public static function addCode($atts, $content = null) {
        extract(Shortcodes::shortcodeAtts(array(
            "mode" => null
        ), $atts));
        $content = preg_replace('/<br class="nc".\/>/', '', $content);
        $mode    = isset($mode) ? $mode : 'html';
        return '<pre><code data-language="' . $mode . '">' . str_replace('<br/>', '', $content) . '</code></pre>';
    }
    
    public static function bootstrapGallery($atts, $content = null) {
        extract(Shortcodes::shortcodeAtts(array(
            "media" => '',
            "captions" => 'false',
            "num" => 3
        ), $atts));
        $value    = '';
        $sizes    = array(
            "2" => 6,
            "3" => 4,
            "4" => 3,
            "6" => 2
        );
        $size     = in_array($num, $sizes) ? $sizes[$num] : 4;
        $num      = in_array($num, $sizes) ? $num : 3;
        $mediagal = new Media();
        $gallery  = $mediagal->getMedia($media);
        if (count($gallery) > 0) {
            $i     = 0;
            $value = '';
            foreach ($gallery as $item) {
                if ($i % $num == 0) {
                    $value .= '<div class="row">';
                    $end = true;
                }
                $value .= '  <div class="col-xs-6 col-md-' . $size . '">';
                $value .= '    <a href="#" class="thumbnail">';
                $value .= '      <img src="' . $item['fileurl'] . '" alt="' . $item['alt'] . '">';
                
                if ($captions == 'true') {
                    $value .= '<div class="caption">';
                    $value .= '    <h3>' . $item['title'] . '</h3>';
                    $value .= '    <p>' . $item['caption'] . '</p>';
                    $value .= '  </div>';
                }
                
                $value .= '    </a>';
                $value .= '  </div>';
                $i++;
                if ($i % $num == 0 && $end = true) {
                    $value .= '</div>';
                    $end = false;
                }
                
            }
            if ($end) {
                $value .= '</div>';
            }
        }
        return $value;
    }
    
    public static function bootstrapCarousel($atts, $content = null) {
        extract(Shortcodes::shortcodeAtts(array(
            "media" => '',
            "controls" => 'true',
            "titles" => 'true'
        ), $atts));
        $mediagal = new Media();
        $gallery  = $mediagal->getMedia($media);
        $value    = '   <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">';
        if (count($gallery) > 0) {
            $value .= '   <!-- Indicators -->';
            $value .= '   <ol class="carousel-indicators">';
            $i = 0;
            foreach ($gallery as $item) {
                $class = $i == 0 ? 'active' : '';
                $value .= '     <li data-target="#carousel-example-generic" data-slide-to="' . $i . '" class="' . $class . '"></li>';
                $i++;
            }
            $value .= '   </ol>';
            $value .= '   <!-- Wrapper for slides -->';
            $value .= '   <div class="carousel-inner">';
            $i = ' active';
            foreach ($gallery as $item) {
                $value .= '     <div class="item ' . $i . '">';
                $value .= '       <img src="' . $item['fileurl'] . '" alt="' . $item['alt'] . '">';
                if ($titles == 'true') {
                    $value .= '       <div class="carousel-caption">';
                    $value .= '         ' . $item['caption'];
                    $value .= '       </div>';
                }
                $value .= '     </div>';
                $i = '';
            }
            //$value .= '       ...';
            $value .= '   </div>';
            if ($controls == 'true') {
                $value .= '   <!-- Controls -->';
                $value .= '   <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">';
                $value .= '     <span class="glyphicon glyphicon-chevron-left"></span>';
                $value .= '   </a>';
                $value .= '   <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">';
                $value .= '     <span class="glyphicon glyphicon-chevron-right"></span>';
                $value .= '   </a>';
            }
            $value .= ' </div>';
            return $value;
        }
        
    }
    
    
}



Shortcodes::addShortcode('title', 'Bootstrapcore::ogmaTitle');
Shortcodes::addShortcodeDesc('title', 'Page Title', '[title]');

Shortcodes::addShortcode('author', 'Bootstrapcore::ogmaAuthor');
Shortcodes::addShortcodeDesc('author', 'Page Author', '[author]');

Shortcodes::addShortcode('sitetitle', 'Bootstrapcore::ogmaSiteTitle');
Shortcodes::addShortcodeDesc('sitetitle', 'Site Title', '[sitetitle]');

Shortcodes::addShortcode('icode', 'Bootstrapcore::addiCode', '[icode mode=""]content[/icode]');
Shortcodes::addShortcodeDesc('icode', 'Inline Code', '[icode mode=""]content[/icode]');

Shortcodes::addShortcode('code', 'Bootstrapcore::addCode', '[code mode=""]content[/code]');
Shortcodes::addShortcodeDesc('code', 'Code Block', '[code mode=""]content[/code]');


Shortcodes::addShortcode('jumbotron', 'Bootstrapcore::bootstrap_jumbotron');
Shortcodes::addShortcodeDesc('jumbotron', 'Bootstrap Jumbotron', '[jumbotron]content[/jumbotron]');

Shortcodes::addShortcode('label', 'Bootstrapcore::bootstrap_label');
Shortcodes::addShortcodeDesc('label', 'Bootstrap Label', '[label]content[/label]');

Shortcodes::addShortcode('badge', 'Bootstrapcore::bootstrap_badge');
Shortcodes::addShortcodeDesc('badge', 'Bootstrap Badge', '[badge]content[/badge]');

Shortcodes::addShortcode('lead', 'Bootstrapcore::bootstrap_lead');
Shortcodes::addShortcodeDesc('lead', 'Bootstrap Lead', '[lead]content[/lead]');

Shortcodes::addShortcode('panel', 'Bootstrapcore::bootstrap_panel');
Shortcodes::addShortcodeDesc('panel', 'Bootstrap Panel', '[panel type="" title="" footer=""]content[/panel]');

Shortcodes::addShortcode('tooltip', 'Bootstrapcore::bootstrap_tooltip');
Shortcodes::addShortcodeDesc('tooltip', 'Bootstrap Tooltip', '[tooltip placement="" trigger="" href=""]content[/tooltip]');

Shortcodes::addShortcode('icon', 'Bootstrapcore::bootstrap_icons');
Shortcodes::addShortcodeDesc('icon', 'Bootstrap Icon', '[icon name=""]content[/icon]');

Shortcodes::addShortcode('row', 'Bootstrapcore::bootstraps_row');
Shortcodes::addShortcodeDesc('row', 'Bootstrap Row', '[rown]content[/row]');

Shortcodes::addShortcode('col', 'Bootstrapcore::bootstrap_span');
Shortcodes::addShortcodeDesc('col', 'Bootstrap Column', '[col class=""]content[/col]');

Shortcodes::addShortcode('collapse', 'Bootstrapcore::bootstrap_collapse');
Shortcodes::addShortcodeDesc('collapse', 'Bootstrap Collapseable', '[collapse id=""]content[/collapse]');

Shortcodes::addShortcode('citem', 'Bootstrapcore::bootstrap_citem');
Shortcodes::addShortcodeDesc('citem', 'Bootstrap Collapseable Item', '[citem id="" title="" parent=""]content[/citem]');

Shortcodes::addShortcode('button', 'Bootstrapcore::bootstrap_buttons');
Shortcodes::addShortcodeDesc('button', 'Bootstrap Button', '[button size="" type="" value="" href="" icon=""]content[/button]');

Shortcodes::addShortcode('notification', 'Bootstrapcore::bootstrap_notice');
Shortcodes::addShortcodeDesc('notification', 'Bootstrap Notification', '[notification type=""]content[/notification]');

Shortcodes::addShortcode('carousel', 'Bootstrapcore::bootstrapCarousel');
Shortcodes::addShortcodeDesc('carousel', 'Bootstrap Carousel', '[carousel media="" titles="true" control="true" /]');

Shortcodes::addShortcode('gallery', 'Bootstrapcore::bootstrapGallery');
Shortcodes::addShortcodeDesc('gallery', 'Bootstrap Gallery', '[gallery media="" captions="" num="3" /]');

// this is for future use but allows you to add buttons to the editor bar
Ogmaeditor::addButton('lead', '', 'fa:fa fa-fw fa-text-height', '[lead]', '[/lead]', '');

?>