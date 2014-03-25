<?php 

// Twitter Feed plugin for OGMA CMS

Plugins::registerPlugin( 
				'comments',
                'Disqus Comments',
                'Disqus Comments plugin for OGMA',
                '0.0.1',
                'Mike Swan',
                'http://www.digimute.com/'
                );

class Comments {

    public function __construct() {
 
    }

    public static function init(){
        Actions::addAction('admin-add-sidebar','Menu::addSidebarMenu',1,array("Comments",'','comments&action=edit','fa fa-fw fa-comment'));
        Actions::addAction('bootstrap-blog-comments', 'Comments::showComments',1,array());
        $language = Core::$site['language'];
        Lang::mergeLanguage(Core::$settings['pluginpath'].'comments'.DS.'lang'.DS.$language.'.lang.php');
      
    }

    public static function initShortcodes(){
        // initialize shortcodes
    }

    public static function initFrontend(){
        // Frontend stuff
    }
    
    public static function admin(){
        $action = Core::getAction();            // get URI action
        $id = Core::getID();                    // get page ID
        
        if ($action=="update"){
            $settings = Xml::xml2array(ROOT . 'addins/plugins/comments/data/settings.xml');
            $savesettings = $settings;
            foreach ($settings as $item=>$val){
                if (isset($_POST['post-'.$item])) {
                    $savesettings[$item]=$_POST['post-'.$item];
                }
            }
            $ret=file_put_contents(ROOT . 'addins/plugins/comments/data/settings.xml', Xml::arrayToXml($savesettings));
            if ($ret){
                 Core::addAlert( Form::showAlert('success', __("UPDATED",array(":record"=>"",":type"=>__("SETTINGS"))) ));
            } else {
                Core::addAlert( Form::showAlert('error', __("UPDATEDFAIL",array(":record"=>"",":type"=>__("SETTINGS"))) ));
            }
            $action='edit'; 
            $_GET['action']='edit';  
        }

        $settings = Xml::xml2array(ROOT . 'addins/plugins/comments/data/settings.xml');

        if ($action=="edit"){

            Core::getAlerts();
            
            echo '<div class="col-md-12">';

            $ogmaForm = new Form();
            
            $ogmaForm->addHeader(__("COMMENTS_SETTINGS"));
           
            $ogmaForm->startTabHeaders();

            $ogmaForm->createTabHeader(array('main'=>'Main'),true);
            
            Actions::executeAction('comments-tab-header');

            $ogmaForm->endTabHeaders();

            $ogmaForm->createForm('load.php?tbl=comments&action=update');

            $ogmaForm->startTabs();
            $ogmaForm->createTabPane('main',true);
            $ogmaForm->displayField('post-shortname', __("COMMENTS_SHORTNAME") ,  'text', '',$settings['shortname']);
         
            Actions::executeAction('comments-tab-new');

            $ogmaForm->endTabs();
            
            $ogmaForm->formButtons();
            $ogmaForm->endForm();

            $ogmaForm->show();

           echo '</div>';
        }
    }

    public static function showComments(){
        $settings = Xml::xml2array(ROOT . 'addins/plugins/comments/data/settings.xml');

        $curUrl= ''; 
        $coment_code = "\n<!-- START: external_coments plugin embed code -->\n";  
        $coment_code .= '<div id="disqus_thread"></div>';
        $coment_code .= '<script type="text/javascript">';
        $coment_code .= "var disqus_shortname = '" . $settings['shortname'] . "';"; 
        $coment_code .= "var disqus_identifier = '" . Core::$page['type'].'-'.Core::$page['id'] . "';";
        $coment_code .= "var disqus_url = '" . Core::$page['url'] . "';";
        $coment_code .= "var disqus_title = '" . Core::$page['title'] . "';";
        $coment_code .= <<<INLINECODE
    (function() {
        var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
        dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
    })();
</script>
<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
<a href="http://disqus.com" class="dsq-brlink">blog comments powered by <span class="logo-disqus">Disqus</span></a>
INLINECODE;
        echo $coment_code;
    }

}

?>