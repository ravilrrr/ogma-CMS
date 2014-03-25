<?php

 /**
 *	OGMA CMS Forms Module
 *
 *	@package ogmaCMS
 *	@author Mike Swan / n00dles101
 *	@copyright 2013 Mike Swan / n00dles101
 *	@since 1.0.0
 *
 */

class Form{
    
	public $tabEnded = false;
	public $formOutput = "";
	public $extras = false;
	public $newFields = array();
	public $customFields = array(); 

    public function __construct() {
    	$this->tabEnded = false;
    	$this->loadCustomFields();
    }
	
	/**
    * Display a form Field
    *
    * @param string $name Field Name
    * @param string $label Field Label
    * @param string $type Field Type
    * @param string $options Field Options
    * @param string $value Initial Value
    */
	public  function displayField($name, $label, $type, $options,$value='',$help=''){
		if (@!include_once(Core::$settings['fieldspath'] . $type . '.field.php'))
			{
				$this->formOutput .= "Field not Found...".$type;
			}
		if (class_exists($type))
			{	
				$value = stripslashes(htmlentities ($value, ENT_QUOTES, "UTF-8"));
				$getValue = new $type($name, $label, $type, $options,$value,$help);
				$this->formOutput .= $getValue->value;
			}
		}

	public static function viewField($name, $label, $type, $options,$value='',$help=''){
		if (@!include_once(Core::$settings['fieldspath'] . $type . '.field.php'))
			{
				$this->formOutput .= "Field not Found...".$type;
			}
		if (class_exists($type))
			{	
				$value = stripslashes(htmlentities ($value, ENT_QUOTES, "UTF-8"));
				$getValue = new $type($name, $label, $type, $options,$value,$help);
				echo $getValue->value;
			}
		}

	public function loadCustomFields(){
		$language = Core::$site['language'];
		$cFields = new Query('customfields');
		$this->customFields = $cFields->get();
		foreach($this->customFields as $item){
			// load the customfield headers in the current language. 
			Lang::$language[$language]["CF_".strtoupper($item['name'])] = $item['desc'];
		}
		
	}


	public static function getFields(){
		$fields = array();
		$files =  Core::getFiles(Core::$settings['fieldspath'],'php');
		//print_r($files);
		foreach ($files as $file){
			$fields[] = strstr($file, '.field.php', true);
		}
		return $fields;
	}


	public function addHeader($title){
		$this->formOutput .= '<legend>'.$title.'</legend>';
	}

	/**
    * Create a Tab Header
    *
    * @param array $tabs Named pairs, div ID and display name
    * @param boolean $active Set true for active Tab
    */
	public  function createTabHeader($tabs = array(),$active=false){
		foreach($tabs as $tab=>$val){
			$class = ($active==true) ? " class='active' " : '';
		    $this->formOutput .=  '<li '.$class.'><a href="#'.$tab.'" data-toggle="tab">'.$val.'</a></li>';   
		} 
	} 


	/**
    * Create a Tab Pane
    *
    * @param string $name Name of Pane
    * @param boolean $active Set true for active Tab
    */
	public  function createTabPane($name,$active=false){
		if ($this->tabEnded==true){
			//echo "Tab=true";
			$this->formOutput .= '</div>';
			$this->tabEnded=false;
		} 
			//echo "Tab=false";
			$class = ($active==true) ? " active " : '';
			$this->formOutput .= '<div class="tab-pane '.$class.'" id="'.$name.'">';   
			$this->tabEnded=true;
		
	}

	/**
	 * [output description]
	 * @return [type] [description]
	 */
	public  function output($text){
		$this->formOutput .= $text;
	}

	/**
    * End Tab Panes
    *
    * @param string $name Name of Pane
    * @param boolean $active Set true for active Tab
    */
	public  function createForm($action, $table=""){
		//if ($id != "") $id=" id='".$id."' ";
		$this->formOutput .= '<form role="form" ';
		if ($table!="") {
			$this->formOutput .= ' data-table="'.$table.'" '; 
		}
		$this->formOutput .= ' class="form-horizontal well required-form" method="post" autocomplete="off" action="'.$action.'" >';
	} 


	/**
    * Echo Form Buttons
    *
    * @param bool $type 
    * @param boolean $active Set true for active Tab
    */
	public  function formButtons($type=false, $cancel = true){
		$this->formOutput .= '<div class="form-group"><div class="col-sm-offset-2 col-sm-10">';
        if ($type){
        	$this->formOutput .= '<div class="btn-group"><button type="submit" name="submitmain" class="btn btn-primary">'.__("SAVE").'</button><button type="submit" name="submitclose" class="btn btn-primary">&amp; Close</button></div> ';
        } else {
 			$this->formOutput .= '<button type="submit" name="submitmain" class="btn btn-primary">'.__("SAVE").'</button>';      	
        }
        $tbl = Core::getTable();
        if ($cancel){
	        if ($tbl!=="") $tbl='tbl='.$tbl.'&amp;';
	        $this->formOutput .= '<button type="reset" class="btn btn-default" onclick="location.href=\''.Core::getFilenameId().'.php?'.$tbl.'action=view\'"  >'.__("CANCEL").'</button>';
	    	$this->formOutput .= '</div>';
	    }
	} 


	/**
    * End Tab Panes
    *
    * @param string $name Name of Pane
    * @param boolean $active Set true for active Tab
    */
	public  function endform(){
		$this->formOutput .=  '</form>';
	} 

	/**
    * End Tab Panes
    *
    * @param string $name Name of Pane
    * @param boolean $active Set true for active Tab
    */
	public  function startTabHeaders(){
		$this->formOutput .=  '<ul class="nav nav-tabs">';
	} 

	/**
    * End Tab Panes
    *
    * @param string $name Name of Pane
    * @param boolean $active Set true for active Tab
    */
	public  function ENDTabHeaders(){
		$this->formOutput .=  '</ul>';
	} 


	/**
    * End Tab Panes
    *
    * @param string $name Name of Pane
    * @param boolean $active Set true for active Tab
    */
	public  function startTabs(){
		$this->formOutput .=  '<div class="tabbable" >';
        $this->formOutput .=  '<div class="tab-content">';
	} 
	/**
    * End Tab Panes
    *
    * @param string $name Name of Pane
    * @param boolean $active Set true for active Tab
    */
	public  function endTabs(){
		if ($this->tabEnded==true){
			$this->formOutput .=  '</div></div></div>'; 
			$this->tabEnded==false;
		}		
	} 

	public function show(){
		echo $this->formOutput;
	}

	public static function showAlert($type, $message){
		return '<div class="alert alert-'.$type.'"><a class="close" data-dismiss="alert">x</a>'.$message.'</div>';
	}

	public function createExtrasTab($table, $fields){
		$schemaFields = Core::$schema[$table];
		
		foreach ($fields as $item=>$field){
			if (!in_array($item, $schemaFields)){
				$this->newFields[$item] = $field;
				//$this->newFields[$item]['options'] = $fields[$item]['options'];
					
			}
		}
		if(count($this->newFields) > 0){
			$this->createTabHeader(array('extras'=>__("CUSTOM")),false);
			$this->extras=true;
		}
		
	}

	public static function sendmail($to,$subject,$message) {
	
		$message = email_template($message);

		if (defined('GSFROMEMAIL')){
			$fromemail = GSFROMEMAIL; 
		} else {
			if(!empty($_SERVER['SERVER_ADMIN']) && check_email_address($_SERVER['SERVER_ADMIN'])) $fromemail = $_SERVER['SERVER_ADMIN'];
			else $fromemail =  'noreply@'.$_SERVER['SERVER_NAME'];
		}
		
		global $EMAIL;
		$headers  ='"MIME-Version: 1.0' . PHP_EOL;
		$headers .= 'Content-Type: text/html; charset=UTF-8' . PHP_EOL;
		$headers .= 'From: '.$fromemail . PHP_EOL;
		$headers .= 'Reply-To: '.$fromemail . PHP_EOL;
		$headers .= 'Return-Path: '.$fromemail . PHP_EOL;
		
		if( @mail($to,'=?UTF-8?B?'.base64_encode($subject).'?=',"$message",$headers) ) {
			return true;
		} else {
			return false;
		}
	}

	public function createExtrasPane($data){
		$action = Core::getAction();
		if ($this->extras==true){
			$this->createTabPane('extras',false);
				foreach ($this->newFields as $field=>$type){
					$options = "";
					switch ($type) {
						case 'dropdown':
							foreach ($this->customFields as $item){
								if ($item['name']==$field){
									
									$options = explode(',', $item['options'] );
									
								}
							} 
							break;
						case 'editor': 
							foreach ($this->customFields as $item){
								if ($item['name']==$field){
									
									$options = $item['options'];
									
								}
							} 
							break; 
						default:
							# code...
							break;
					}
					
					if ($action=="create"){
						$this->displayField('post-'.$field,__("CF_".strtoupper($field)), $type, $options,'');
					} else {
						$this->displayField('post-'.$field,__("CF_".strtoupper($field)), $type, $options,$data[$field]);
					}
				}
			}
	}

}
?>
