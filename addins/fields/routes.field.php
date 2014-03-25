<?php
// ----------------------------------------------------------------
// Field Type: Routes
// ----------------------------------------------------------------

class Routes
{
	var $value;
	//var $pageList;

	public  function __construct($name, $label, $type, $options,$value) {
	 	$routes = new Query('routes'); 
	 	$routes->getCache();
	 	$this->routeList = $routes->get(); 	
	 	self::doRoutes($name, $label, $type, $options,$value,$help);
	}
	 

	
	function doRoutes($name, $label, $type, $options,$value='',$help='')
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
		        $this->value .= ' 		<option value="">/</option>';
		        foreach($this->routeList as $item){
		        	$this->value .= '        <option value="'.$item['route'].'"';
		        	if (strtolower($value)==$item['route']) $this->value .= " selected ";
		        	$this->value .='>/'.$item['route'].'</option>';	
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
		        $this->value .= ' 		<option value="">/</option>';
		        foreach($this->routeList as $item){
		        	$this->value .= '        <option value="'.$item['route'].'"';
		        	if (strtolower($value)==$item['route']) $this->value .= " selected ";
		        	$this->value .='>/'.$item['route'].'</option>';	
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