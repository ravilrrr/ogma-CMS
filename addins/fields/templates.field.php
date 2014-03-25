<?php
// ----------------------------------------------------------------
// Field Type: Templates
// ----------------------------------------------------------------

class Templates
{
	var $value;
	public $templateFiles = array();
	
	public  function __construct($name, $label, $type, $options,$value='',$help) {

			
			$themes_path   = Core::$settings['rootpath'] . 'theme/'. Core::$site['template'];
			
			$themes_handle = opendir($themes_path) or die("Unable to open ". Core::$settings['themespath']);		
			while ($file = readdir($themes_handle))	{		
				if( Core::isFile($file, $themes_path, 'php') ) {		
					if ($file != 'functions.php' && substr(strtolower($file),-8) !='.inc.php' && substr($file,0,1)!=='.') {		
				      $this->templateFiles[] = $file;		
				    }		
				}		
			}	
	 	self::doTemplates($name, $label, $type, $options,$value,$help);
	}
	 


	function doTemplates($name, $label, $type, $options,$value,$help='')
	{
		$action = (isset($_GET['action'])) ? $_GET['action'] : "create";
		
		if ($value=='') $value="template.php";
		switch($action)
		{


			// Edit output.
			// -------------------------------------------------
			case "edit":
				$this->value .= '<div class="form-group">';
		        $this->value .= '    <label class="col-sm-2" for="'.$name.'">'.$label.'</label>';
		        $this->value .= '    <div class="col-sm-4">';
		        $this->value .= '      <select  class="form-control" id="'.$name.'" name="'.$name.'">';
		        foreach($this->templateFiles as $item){
		        	$this->value .= '        <option value="'.$item.'"';
		        	if (strtolower($value)==$item) $this->value .= " selected ";
		        	$this->value .='>'.$item.'</option>';	
		        }
		        $this->value .= '      </select>';
		        if ($help!='') $this->value .= '<p class="help-block">'.$help.'</p>';
				$this->value .= '    </div>';
		        $this->value .= '  </div>';		
				break;

			// New output.
			// -------------------------------------------------
			case "create":
				$this->value .= '<div class="form-group">';
		        $this->value .= '    <label class="col-sm-2" for="'.$name.'">'.$label.'</label>';
		        $this->value .= '    <div class="col-sm-4">';
		        $this->value .= '      <select  class="form-control" id="'.$name.'" name="'.$name.'">';
		        foreach($this->templateFiles as $item){
		        	$this->value .= '        <option value="'.$item.'"';
		        	if (strtolower($value)==$item) $this->value .= " selected ";
		        	$this->value .='>'.$item.'</option>';	
		        }
		        $this->value .= '      </select>';
		        if ($help!='') $this->value .= '<p class="help-block">'.$help.'</p>';
				$this->value .= '    </div>';
		        $this->value .= '  </div>';				
				break;

			// not found so output typical value.
			// -------------------------------------------------
			default:
				$this->value = $value;
		}
	}
} // End
?>