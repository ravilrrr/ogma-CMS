<?php
// ----------------------------------------------------------------
// Field Type: Blog
// ----------------------------------------------------------------

class Blogs
{
	var $value;

	public  function __construct($name, $label, $type, $options,$value) {
	 	$pages = new Query('blogs'); 
	 	$this->pageList = $pages->getCache(); 	
	 	self::doPages($name, $label, $type, $options,$value);
	}
	 

	
	function doPages($name, $label, $type, $options, $value='', $help='')
	{
		$action = (isset($_GET['action'])) ? $_GET['action'] : "create";
		
		switch($action)
		{

			// Edit output.
			// -------------------------------------------------
			case "edit":
				$this->value .= '<div class="form-group">';
		        $this->value .= '    <label class="col-sm-2" for="'.$name.'">'.$label.'</label>';
		        $this->value .= '    <div class="col-sm-5">';
		        $this->value .= '      <select  id="'.$name.'" class="form-control" name="'.$name.'">';
		        $this->value .= ' 		<option value="">None</option>';
		        foreach($this->pageList as $item){
		        	$this->value .= '        <option value="'.$item['slug'].'"';
		        	if (strtolower($value)==$item['slug']) $this->value .= " selected ";
		        	$this->value .='>'.$item['slug'].'</option>';	
		        }
		        $this->value .= '      </select>';
		        $help = isset($options['help']) ? $options['help'] : '';
		        $this->value .= '    </div>';
		        $this->value .= '  </div>';		
				break;

			// New output.
			// -------------------------------------------------
			case "create":
				$this->value .= '<div class="form-group">';
		        $this->value .= '    <label class="col-sm-2" for="'.$name.'">'.$label.'</label>';
		        $this->value .= '    <div class="col-sm-5">';
		        $this->value .= '      <select  id="'.$name.'" class="form-control" name="'.$name.'">';
		        foreach($this->pageList as $item){
		        	$this->value .= '        <option value="'.$item['slug'].'"';
		        	if (strtolower($value)==$item['slug']) $this->value .= " selected ";
		        	$this->value .='>'.$item['slug'].'</option>';	
		        }
		        $this->value .= '      </select>';
		        $help = isset($options['help']) ? $options['help'] : '';
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