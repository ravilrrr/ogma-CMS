<?php
 /**
 *  ogmaCMS Menu Module
 *
 *  @package ogmaCMS
 *  @author Mike Swan / n00dles101
 *  @copyright 2013 Mike Swan / n00dles101
 *  @since 1.0.0
 *
 */

class Menu{
    
    public  $menuItems = array();
    public  $menuName = "";

    public  $options=array(
            'menuname' => '',
            'maxLevels' => 3,
            'menuID' => '',
            'menutag' => 'ul',
            'menuclass' => '', 
            'subclass' => '', 
            'itemtag' => 'li',
            'itemclass' => '',
            'iteminner' =>'<a href="$url">$name</a>',
            'currentclass' => ' current'
        );
    
    public function __construct($name, $properties=array() ){
        $this->setOptions($properties);
        $this->options['menuname']=$name;
    }

    /**
     * setOptions
     * 
     * Prepares menu options for output. 
     * @param array $properties Menu properties for output
     */
    public  function setOptions($properties=array()){
        foreach($properties as $key => $value){
           $this->options[$key] = $value;
        } 
    }

    /**
     * addSidebarMenu 
     *
     * Adds a sidebar menu item, for use with plugins 
     *
     * [code]
     *
     *
     * [/code]
     * 
     * @param string  $title Title to display, use Lang functions 
     * @param string  $link  URL of menu item
     * @param string  $url   
     * @param string  $icon  [description]
     * @param boolean $admin [description]
     */
    public static function addSidebarMenu($title, $link, $url,  $icon, $admin = false){
    	if (($admin==true && User::isAdmin()==true) or ($admin==false)){
    		echo '<li ';
    		self::checkCurrent($url);
    		echo '><a href="load.php?tbl='.$url.'"><i class="'.$icon.'"></i> '.$title.'</a></li>'; 
    	} 
    }

    public static function addDashboardItem($title, $link, $url, $icon, $admin = false){
        if (($admin==true && User::isAdmin()==true) or ($admin==false)){
            echo '<a href="load.php?tbl='.$url.'" class="shortcut btn-warning">';
            echo '    <i class="shortcut-icon '.$icon.'"></i>';
            echo '    <span class="shortcut-label">'.$title.'</span>';
            echo '</a>';
        }
    }

    /**
     * checkCurrent
     *
     * Checks if the current page ID is as the current filename and echos a class='active' 
     *
     * @param  string $page current page
     */
    public static function checkCurrent($page){
		if (Core::getFilenameId()===$page){
			echo " class='active'";
            return;
		} else if (isset( $_GET['tbl'] )) {
        if (Core::getFilenameId()==='load' && $_GET['tbl']==$page ){
    			echo " class='active'";
                return;
    		}
        } else {
            return;
        }
	}

    /**
     * displayMenu
     *
     * Displays a menu in Menu Manager admin for editing
     * 
     */
    public  function displayMenu(){
        $this->options['menutag'] = 'ol';
        $this->options['menuID'] = '';

        $this->options['menuclass'] = 'dd-list';
        $this->options['iteminner']='<div class="dd-handle">$name</div>';

        $this->setOptions(
            array(
                'maxLevels'=>'20',
                'menuID'=>'',
                'itemtag'=>'li data-id="$id"',
                'menutag'=>'ol',
                'itemclass'=>'dd-item', 
                'iteminner'=>' <div class="dd-handle dd3-handle" id="itemdata-c$id" data-name="$name" data-title="$name" data-attr="$name" data-id="$id" data-url="$url" data-order="" data-parent="$parent">
                    </div>
                    <div class="dd3-content">$name</div>
                    <span class="item-controls">
                    <span class="item-type">
                    <a href="javascript:null;" id="c$id" class="menuedit closed"><i class="fa fa-fw fa-edit"></i></a>
                    <a href="javascript:null;" id="d$id" class="menudelete askconfirm"><i class="fa fa-fw fa-trash-o"></i></a>
                    </span>
                    </span>
                    <div id="control-c$id"></div>'
                ));

        $this->getMenu(false);
    }

    public static function getMenuData($menuname){
        $menu = array();
        $file=Core::$settings['rootpath'].'data/menus/'.$menuname.'.menu';
          if (file_exists($file)){
          // load the xml file and setup the array. 
                $thisfile = file_get_contents($file);
            } else {
                $thisfile = '<?xml version="1.0" encoding="utf-8"?><root></root>';
          }

            $data = simplexml_load_string($thisfile);
            $components = @$data->item;
            if (count($components) != 0) {
                foreach ($components as $component) {
                $name=(string)$component->name;
                   $menu[(string)$component->id] = array(
                        'name' => (string)$component->name, 
                        'order' => (string)$component->order,
                        'parent' => (string)$component->parent,
                        'url' => (string)$component->url,
                        'id' => (string)$component->id
                        );
                }
            }
            return $menu;
    }

    public  function getMenu($fullUrls = true){
        $menuname = $this->options['menuname'];
        $file=Core::$settings['rootpath'].'data/menus/'.$menuname.'.menu';
          if (file_exists($file)){
          // load the xml file and setup the array. 
                $thisfile = file_get_contents($file);
            } else {
                $thisfile = '<?xml version="1.0" encoding="utf-8"?><root></root>';
          }

            $data = simplexml_load_string($thisfile);
            $components = @$data->item;
            if (count($components) != 0) {
                foreach ($components as $component) {
                $name=(string)$component->name;
                   $this->menuItems[(string)$component->id] = array(
                        'name' => (string)$component->name, 
                        'order' => (string)$component->order,
                        'parent' => (string)$component->parent,
                        'url' => (string)$component->url,
                        'id' => (string)$component->id
                        );
                }
            }
        
           // print_r(Menu::$menuItems);

            $ret = $this->showMenu('',Core::subvalSort($this->menuItems,'order'), 0,0, $fullUrls); 

            $codes=array('/\$iteminner/','/\$itemclass/','/\$itemtag/','/\$menutag/', '/\$menuclass/', '/\$subclass/');
            $replace_code=array($this->options['iteminner'],$this->options['itemclass'], $this->options['itemtag'],$this->options['menutag'], $this->options['menuclass'], $this->options['subclass']);
            $menu = preg_replace($codes, $replace_code, $ret);
            
            echo $menu;
    }

    public  function showMenu($menu, $menuarray, $parent = null, $level, $fullurls = true){
        
        $menuID = ($level==0) ? " id='".$this->options['menuID']."'" : '';
        $id = Core::getID();
        $itemTag = ( $this->options['itemtag']!='') ? $this->options['itemtag'] : '';
        $count=0;
        //if ($level<=self::$options['maxLevels']){
            if (count($menuarray)!=0){
            foreach ($menuarray as $menuItem){

                if ($id===$menuItem['url']){
                    $currentClass=$this->options['currentclass'];
                } else {
                    $currentClass='';
                }
                $itemClass = ' class="'.$this->options['itemclass'];
                $itemClass .= $currentClass;
                $itemClass .= '" ';

                if ($count==0 && $menuItem['parent']==$parent){
                    if ($parent==0){
                        $menu .= '<$menutag '.$menuID.' class="$menuclass" data-level="'.$parent.'">';
                    } else {
                        $menu .= '<$menutag class="$subclass"  data-level="'.$parent.'">';
                    }
                    $count=-1;
                }
                if ($menuItem['parent']==$parent){
                    
                       $iteminner = '<'.$this->options['itemtag'].$itemClass.'  >'.$this->options['iteminner'];

                        $codes=array('/\$url/','/\$name/','/\$id/');
                        if ($fullurls==true){
                            $newUrl = Url::returnUrl($menuItem['url']);
                        } else {
                            $newUrl = $menuItem['url'];
                        }
                        $replace_code=array($newUrl, $menuItem['name'], $menuItem['id'] );
                        $iteminner = preg_replace($codes, $replace_code, $iteminner);
                        $menu .= $iteminner;

                       if ($level<=$this->options['maxLevels'] ){
                           $menu .= $this->showMenu('', $menuarray, (int)$menuItem['id'], $level++);
                            
                        }

                   $menu .= '</$itemtag>';
                }
            }
        } else {
            $menu .= '<$menutag '.$menuID.' class="$menuclass">';
        }
        if ($count==-1){
            $menu .=  '</'.$this->options['menutag'].'>';
            
        }
        return $menu; 
    }


    public static function addMenuItem($title, $slug, $type){
        echo '<li class="dd-item" data-id="1">';
        echo '   <div class="dd-handle">'.$title;
        echo '   </div>';
        echo '</li>';
    }


}
?>