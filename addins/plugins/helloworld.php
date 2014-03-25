<?php 

// default Hello World plugin for OGMA CMS

Plugins::registerPlugin( 
				'helloworld',
                'Hello World',
                'Hello world plugin for OGMA',
                '0.9.1',
                'Mike Swan',
                'http://www.digimute.com/'
                );

class Helloworld{
	
	public function __construct() {

    }

    public static function init(){
        Actions::addAction('admin-add-sidebar','Menu::addSidebarMenu',1,array("Helloworld",'','helloworld','glyphicon glyphicon-asterisk'));
        Actions::addAction('admin-add-to-dashboard','Menu::addDashboardItem',1,array("Helloworld",'','helloworld','fa fa-fw fa-smile-o'));
        Actions::addAction('admin-add-widget','Helloworld::widget',1,array());
       
           
    }

    public static function widget(){
        echo '<div class="panel panel-primary">';
        echo '<div class="panel-heading">'.__("SYSTEMINFO").'</div>';
        echo '  <div class="panel-body">';
        echo '    OGMA ver '.VERSION;
        echo '  </div>';
        echo '</div>';
    }

    public static function admin(){
        echo "Hello Word Admin Page";

    }


}

?>
