<?php
// ----------------------------------------------------------------
// Field Type: Pages
// ----------------------------------------------------------------

class Pages
{
	var $value;

	public  function __construct($name, $label, $type, $options,$value,$help) {
	 	$pages = Core::$pages; 
	 	$currentpage = isset($options['currentpage']) ? ',slug !='.$options['currentpage'] : '';
	 	$pages->getCache();
	 	$this->pageList = $pages->find('slug !=404 '.$currentpage.', parent = ')->get(); 	
	 	$currentpage = isset($options['currentpage']) ? $options['currentpage'] : '1';
	 	self::doPages($name, $label, $type, $options,$value,$help);
	}
	 

	
	function doPages($name, $label, $type, $options,$value='',$help='')
	{
		$action = (isset($_GET['action'])) ? $_GET['action'] : "create";
		
		switch($action)
		{
			

			// Edit output.
			// -------------------------------------------------
			case "edit":
				$this->value .= '<div class="form-group">';
		        $this->value .= '    <label class="col-sm-2" for="'.$name.'">'.$label.'</label>';
		        $this->value .= '    <div class="col-sm-4">';
		        $this->value .= '      <select  class="form-control" id="'.$name.'" name="'.$name.'">';
		        $this->value .= ' 		<option value="">None</option>';
		        foreach($this->pageList as $item){
		        	$this->value .= '        <option value="'.$item['slug'].'"';
		        	if (strtolower($value)==$item['slug']) $this->value .= " selected ";
		        	$this->value .='>'.$item['slug'].'</option>';	
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
		        $this->value .= ' 		<option value="">None</option>';
		        foreach($this->pageList as $item){
		        	$this->value .= '        <option value="'.$item['slug'].'"';
		        	if (strtolower($value)==$item['slug']) $this->value .= " selected ";
		        	$this->value .='>'.$item['slug'].'</option>';	
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