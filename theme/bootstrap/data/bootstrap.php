<?php 

class Bootstrap extends Theme{
	
	public $menuType = '';
	public $menu = array(); 
	public $menuRight = array();

	public function __construct() {

    }

	public static function admin(){
		 	
		 	$settings = Theme::$themeSettings;

		 	$ogmaForm = new Form();

		    $ogmaForm->startTabHeaders();

		    $ogmaForm->createTabHeader(array('main'=>__("THEMEOPTIONS")),true);
		   	  	
		    Actions::executeAction('theme-tab-header');

		    $ogmaForm->endTabHeaders();
		    
		    $ogmaForm->createForm('theme.php?action=update');

		    $ogmaForm->startTabs();

		    $ogmaForm->createTabPane('main',true);
		    $ogmaForm->displayField('post-title',__("BOOTSTRAP_TITLE"), 'textlong', '',$settings['title']);
		    $ogmaForm->displayField('post-logo',__("BOOTSTRAP_LOGO"),  'textlong', '',$settings['logo']);
		    $ogmaForm->displayField('post-theme',__("BOOTSTRAP_THEME"), 'dropdown', Bootstrap::getBootstrapThemes() ,$settings['theme']);
		    $ogmaForm->displayField('post-footer',__("BOOTSTRAP_FOOTER"),  'textlong', '', $settings['footer']);
			$ogmaForm->displayField('post-debug',__("BOOTSTRAP_DEBUG"),  'yesno', '', $settings['debug']);
		    
		    Actions::executeAction('theme-tab-new');

		    $ogmaForm->endTabs();
		    
		    $ogmaForm->formButtons();
		    $ogmaForm->endForm();

		    $ogmaForm->show();
	}


	public static function getBootstrapThemes(){
		return array('Amelia','Cerulean','Cosmo','Cyborg','Darkly','Default','Flatly','Journal','Readable','Simplex','Slate','Spacelab','United','Yeti');
	}



	public function bootstrapMenu($menu,$right = '', $type = ''){
		$this->menu = Menu::getmenuData($menu);
		if ($right!='') $this->menuRight = Menu::getmenuData($right);
		$this->menuType = $type;

		echo '<div class="navbar navbar-default  navbar-fixed-top" role="navigation">';
	    echo '    <div class="container"><div class="navbar-header">';
	    echo '      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">';
	    echo '        <span class="sr-only">Toggle navigation</span>';
	    echo '        <span class="icon-bar"></span>';
	    echo '        <span class="icon-bar"></span>';
	    echo '        <span class="icon-bar"></span>';
	    echo '      </button>';
	    echo '      <a class="navbar-brand" href="/">'.Core::$site['sitename'].'</a>';
	    echo '    </div>';
	    echo '    <div class="navbar-collapse collapse">';
	    echo '      <ul class="nav navbar-nav">';
	    foreach ($this->menu as $menuItem){
	    	if ($menuItem['parent']==0){
	    		$submenu = $this->getChildren($menuItem['id']);
	    		if (count($submenu)==0){
			    	echo '        <li class=""><a href="'.Core::$site['siteurl'].$menuItem['url'].'">'.$menuItem['name'].'</a></li>';
			    } else {
			    	echo '        <li class="dropdown">';
				    echo '          <a href="'.Core::$site['siteurl'].$menuItem['url'].'" class="dropdown-toggle" data-toggle="dropdown">'.$menuItem['name'].'<b class="caret"></b></a>';
				    echo '          <ul class="dropdown-menu">';
				    
				    foreach ($submenu as $subMenuItem) {
				    	echo '        <li class=""><a href="'.Core::$site['siteurl'].$subMenuItem['url'].'">'.$subMenuItem['name'].'</a></li>';
				    }
				    
				    echo '          </ul>';
				    echo '        </li>';
			    }
		    }
		}
	    
	    
	    echo '      </ul>';

	    // do the right menu
	    if (count($this->menuRight)>0){
	    
	    echo '      <ul class="nav navbar-nav navbar-right">';
	    	foreach ($this->menuRight as $menuItem){
	    		if ($menuItem['parent']==0){
		    		echo '        <li class=""><a href="'.$menuItem['url'].'">'.$menuItem['name'].'</a></li>';
		    	}
		    }
	    echo '      </ul>';
	    }

	    echo '    </div><!--/.nav-collapse -->';
	    echo '  </div></div>';



		return $this;
	}

	public function getChildren($level){
		$menu=$this->menu;
		$submenu=array();
		foreach ($menu as $menuItem) {
			if ($menuItem['parent']==$level){
				$submenu[] = $menuItem;
			}
		}
		return $submenu;
	}


	
	
}


