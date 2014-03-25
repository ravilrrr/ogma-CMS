<?php
// ----------------------------------------------------------------
// Field Type: Dropdown
// ----------------------------------------------------------------

class Dropdown
{
	var $value;

	function Dropdown($name, $label, $type, $options,$value='', $help='')
	{
		$action = (isset($_GET['action'])) ? $_GET['action'] : "create";
		$view = isset($options['view']) ? $options['view'] : false;	
		
		$view=true;
		switch($action)
		{

			// Edit output.
			// -------------------------------------------------
			case "edit":
				$this->value .= '<div class="form-group">';
		        $this->value .= '    <label class="col-sm-2" for="'.$name.'">'.$label.'</label>';
		        $this->value .= '    <div class="col-sm-6">';
		        $this->value .= '      <select  class="form-control" id="'.$name.'" name="'.$name.'">';
		        if (!Arr::isAssoc($options)){
			        foreach($options as $item){
			        	$this->value .= '        <option value="'.($item).'"';
			        	if (($value)==($item)) $this->value .= " selected ";
				        $this->value .='>'.$item.'</option>';	
			        }
				} else {

					foreach($options as $item=>$value2){
			        	$this->value .= '        <option value="'.($item).'"';
			        	if (($value)==($item)) $this->value .= " selected ";
				        $this->value .='>'.$value2.'</option>';	
			        }
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
		        $this->value .= '    <div class="col-sm-6">';
		        $this->value .= '      <select  class="form-control" id="'.$name.'" name="'.$name.'">';

		        if (!Arr::isAssoc($options)){
			        foreach($options as $item){
			        	$this->value .= '        <option value="'.($item).'"';
			        	if (($value)==($item)) $this->value .= " selected ";
				        $this->value .='>'.$item.'</option>';	
			        }
				} else {

					foreach($options as $item=>$value2){
			        	$this->value .= '        <option value="'.($item).'"';
			        	if (($value)==($item)) $this->value .= " selected ";
				        $this->value .='>'.$value2.'</option>';	
			        }
		        }

		        $this->value .= '      </select>';
		        if ($help!='') $this->value .= '<p class="help-block">'.$help.'</p>';
		        $this->value .= '    </div>';
		        $this->value .= '  </div>';			
				break;

			// not found so output typical value.
			// -------------------------------------------------
			default:
				if ($view){
					//$name=str_replace($options['field']. '-', '', $name);
					$this->value .= '<div class="form-group">';
					$this->value .= '      <select  id="'.$name.'" name="'.$name.'"  data-field="'.$options['field'].'" data-id="'.$options['id'].'" data-table="'.$options['table'].'"  class="form-control dropdownajax" >';
			        if (!Arr::isAssoc($options[$options['field']])){
				        foreach($options[$options['field']] as $item){
				        	$this->value .= '        <option value="'.($item).'"';
				        	if (($value)==($item)) $this->value .= " selected ";
					        $this->value .='>'.$item.'</option>';	
				        }
					} else {
						foreach($options[$options['field']] as $item=>$value2){
				        	$this->value .= '        <option value="'.($item).'"';
				        	if (($value)==($item)) $this->value .= " selected ";
					        $this->value .='>'.$value2.'</option>';	
				        }
			        }

			        $this->value .= '      </select>';
			        $this->value .= '  </div>';	
					break;
				} else {
					$this->value = $value;
				}
		}
	}
} // End
?>