<?php
// ----------------------------------------------------------------
// Field Type: Yesno
// ----------------------------------------------------------------

class Yesno
{
	var $value;

	function Yesno($name, $label, $type, $options,$value='',$help='')
	{
		$action = (isset($_GET['action'])) ? $_GET['action'] : "create";
		
		switch($action)
		{

			// Edit output.
			// -------------------------------------------------
			case "edit":
				$this->value .= '<div class="form-group">';
		        $this->value .= '    <label class="col-sm-2" for="'.$name.'">'.$label.'</label>';
		        $this->value .= '    <div class="col-sm-2">';
		        $this->value .= '      <select  class="form-control" id="'.$name.'" name="'.$name.'">';
		        $this->value .= '        <option value="1" ';
		        if ($value=="1") $this->value .= " selected ";
		        $this->value .= '>'.__("YES").'</option>';
		        $this->value .= '        <option value="0" ';
		        if ($value=="0") $this->value .= " selected ";
		        $this->value .= '>'.__("NO").'</option>';
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
		        $this->value .= '    <div class="col-sm-2">';
		        $this->value .= '      <select  class="form-control" id="'.$name.'" name="'.$name.'">';
		        $this->value .= '        <option value="1" ';
		        if ($options=="1") $this->value .= " selected ";
		        $this->value .= '>'.__("YES").'</option>';
		        $this->value .= '        <option value="0" ';
		        if ($options=="0") $this->value .= " selected ";
		        $this->value .= '>'.__("NO").'</option>';
		        $this->value .= '      </select>';
		        if ($help!='') $this->value .= '<p class="help-block">'.$help.'</p>';
				$this->value .= '    </div>';
		        $this->value .= '  </div>';		
				break;

			// not found so output typical value.
			// -------------------------------------------------
			default:
				$this->value .= '<div class="form-group">';
		        $this->value .= '    <div>';
		        $this->value .= '      <select  class="form-control yesnoajax" id="'.$name.'" name="'.$name.'" data-field="'.$options['field'].'" data-id="'.$options['id'].'" data-table="'.$options['table'].'" >';
		        $this->value .= '        <option value="1" ';
		        if ($value=="1") $this->value .= " selected ";
		        $this->value .= '>'.__("YES").'</option>';
		        $this->value .= '        <option value="0" ';
		        if ($value=="0") $this->value .= " selected ";
		        $this->value .= '>'.__("NO").'</option>';
		        $this->value .= '      </select>';
		        $this->value .= '    </div>';
		        $this->value .= '  </div>';	
		}
	}
} // End 
?>