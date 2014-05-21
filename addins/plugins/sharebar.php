<?php 

// Twitter Feed plugin for OGMA CMS

Plugins::registerPlugin( 
		'sharebar',
        'Share Bar',
        'Sharebar plugin for OGMA',
        '0.0.1',
        'Mike Swan',
        'http://www.digimute.com/'
        );

class Sharebar {

    public function __construct() {
 
    }

    public static function init(){
        Actions::addAction('menu-add-plugin','Menu::addSidebarMenu',1,array("Sharebar Config",'','sharebar&action=edit','fa fa-fw fa-share'));
        Actions::addAction('admin-add-to-dashboard','Menu::addDashboardItem',1,array("Sharebar Config",'','sharebar&action=edit','fa fa-fw fa-share'));
        
        $language = Core::$site['language'];
        Lang::mergePluginLanguage('sharebar');
        Stylesheet::add('/addins/plugins/sharebar/css/sharebar.css','backend');
    }

    public static function initFrontend(){
        // Frontend stuff
       Stylesheet::add("/3rdparty/font-awesome/css/font-awesome.min.css","frontend",1);
       Stylesheet::add('/addins/plugins/sharebar/css/sharebar.css','frontend');
    }
    
    public static function initShortcodes(){
        // initialize shortcodes 
        Shortcodes::addShortcode('sharebar','Sharebar::showSharebar', '[sharebar /]');
    }

    public static function showSharebar($atts){
        extract(Shortcodes::shortcodeAtts(array(  
                "size"      => 'sm'
            ), $atts));
            
        $size = " btn-".$size;
        $url = Core::$page['url'];
        $title = Core::$page['title'];
        
        $settings = Plugins::getSettings(ROOT . 'addins/plugins/sharebar/data/settings.xml');
        echo '<aside id="sharebar">Share ';
        if ($settings['facebook']){
            echo '<a class="btn btn-default '.$size.'" href="http://www.facebook.com/sharer.php?u='.$url.'&t='.$title.'"><i class="fa fa-facebook fa-lg fb"></i></a>';
        }
        if ($settings['twitter']){
            echo '<a class="btn btn-default'.$size.'" href="http://twitter.com/share?url='.$url.'&text='.$title.'"><i class="fa fa-twitter fa-lg tw"></i></a>';
        }
        if ($settings['google']){
            echo '<a class="btn btn-default'.$size.'" href="https://plus.google.com/share?url='.$url.'"><i class="fa fa-google-plus fa-lg google"></i></a>';
        }
        if ($settings['linkedin']){
            echo '<a class="btn btn-default'.$size.'" href="http://www.linkedin.com/shareArticle?url='.$url.'"><i class="fa fa-linkedin fa-lg linkin"></i></a>';
        }
        if ($settings['vk']){
            echo '<a class="btn btn-default'.$size.'" href="http://vk.com/share.php?url='.$url.'&title='.$title.'&description='.$title.'"><i class="fa fa-vk fa-lg vk"></i></a>';
        }
        if ($settings['digg']){
            echo '<a class="btn btn-default'.$size.'" href="http://digg.com/submit?url='.$url.'&title='.$title.'"><i class="fa fa-digg fa-lg digg"></i></a>';
        }
        if ($settings['tumblr']){
            echo '<a class="btn btn-default'.$size.'" href="http://www.tumblr.com/share/link?url='.$url.'&name='.$title.'&description='.$title.'"><i class="fa fa-tumblr fa-lg tumblr"></i></a>';
        }
        if ($settings['stumble']){
            echo '<a class="btn btn-default'.$size.'" href="http://www.stumbleupon.com/submit?url='.$url.'&title='.$title.'"><i class="fa fa-stumbleupon fa-lg stumble"></i></a>';
        }
        echo '</aside>';
    }

    public static function admin(){
        

        $action = Core::getAction();            // get URI action
        $id = Core::getID();                    // get page ID

        $settings = Plugins::getSettings(ROOT . 'addins/plugins/sharebar/data/settings.xml');

        if ($action=="update"){
            $savesettings = $settings;
            foreach ($settings as $item=>$val){
                if (isset($_POST['post-'.$item])) {
                    $savesettings[$item]=$_POST['post-'.$item];
                }
            }

            $ret = Plugins::saveSettings(ROOT . 'addins/plugins/sharebar/data/settings.xml', Xml::arrayToXml($savesettings));
            
            if ($ret){
                 Core::addAlert( Form::showAlert('success', __("UPDATED",array(":record"=>"",":type"=>__("SETTINGS"))) ));
            } else {
                Core::addAlert( Form::showAlert('error', __("UPDATEDFAIL",array(":record"=>"",":type"=>__("SETTINGS"))) ));
            }
            $action='edit'; 
            $_GET['action']='edit';  
            $settings = Plugins::getSettings(ROOT . 'addins/plugins/sharebar/data/settings.xml');
        }
       
        if ($action=="edit"){

            Core::getAlerts();
            
            echo '<div class="col-md-12">';

            $ogmaForm = new Form();
            
            $ogmaForm->addHeader(__("SHAREBAR_SETTINGS"));
           
            $ogmaForm->startTabHeaders();

            $ogmaForm->createTabHeader(array('main'=>'Main'),true);
            
            Actions::executeAction('sahrebar-tab-header');

            $ogmaForm->endTabHeaders();

            $ogmaForm->createForm('load.php?tbl=sharebar&action=update');

            $ogmaForm->startTabs();
            $ogmaForm->createTabPane('main',true);
            $ogmaForm->displayField('post-twitter', "Twitter" ,  'yesno', '',$settings['twitter']);
            $ogmaForm->displayField('post-facebook', "Facebook" ,  'yesno', '',$settings['facebook']);
            $ogmaForm->displayField('post-google', "Google+",  'yesno', '',$settings['google']);
            $ogmaForm->displayField('post-linkedin', "Linked In",  'yesno', '',$settings['linkedin']);
            $ogmaForm->displayField('post-stumble', "StumbledUpon", 'yesno', '',$settings['stumble']);
            $ogmaForm->displayField('post-vk', "VK",  'yesno', '',$settings['vk']);  
            $ogmaForm->displayField('post-digg', "Digg", 'yesno', '',$settings['digg']);
            $ogmaForm->displayField('post-tumblr', "Tumblr",  'yesno', '',$settings['tumblr']);  
         

            Actions::executeAction('sharebar-tab-new');

            $ogmaForm->endTabs();
            
            $ogmaForm->formButtons(false, false);
            $ogmaForm->endForm();

            $ogmaForm->show();

           echo '</div>';
        }
    }


  

}

?>