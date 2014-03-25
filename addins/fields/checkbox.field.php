<?php
// ----------------------------------------------------------------
// Field Type: Checkbox
// ----------------------------------------------------------------

class Checkbox
{
	var $value;

	function Checkbox($name, $label, $type, $options,$value='', $help='')
	{
		
		switch($_GET['action'])
		{

			// Edit output.
			// -------------------------------------------------
			case "edit":
				$this->value .=   '<div class="form-group">';
		        $this->value .=   '    <label class="col-sm-2" for="'.$name.'">'.$label.'</label>';
		        $this->value .=   '    <div class="col-sm-2">';
		        //$this->value .=   '      <label >';
		        $this->value .=   '        <input type="checkbox" id="'.$name.'" name="'.$name.'" value="'.$value.'"';
		        if ($value==true) $this->value .=   " checked ";
		        $this->value .=   '>';
		       	if ($help!='') $this->value .= '<p class="help-block">'.$help.'</p>';
		        $this->value .=   '    </div>';
		        $this->value .=   '  </div>';	
				break;

			// New output.
			// -------------------------------------------------
			case "create":
				$this->value .=   '<div class="form-group">';
		        $this->value .=   '    <label class="col-sm-2" for="'.$name.'">'.$label.'</label>';
		        $this->value .=   '    <div class="col-sm-2">';
		        //$this->value .=   '      <label class="checkbox">';
		        $this->value .=   '        <input type="checkbox"  id="'.$name.'" name="'.$name.'" value="'.$value.'"';
		        if ($value==true) $this->value .=   " checked ";
		        $this->value .=   '>';
		        if ($help!='') $this->value .= '<p class="help-block">'.$help.'</p>';
		        $this->value .=   '    </div>';
		        $this->value .=   '  </div>';			
				break;

			// not found so output typical value.
			// -------------------------------------------------
			default:
				$this->value = $value;
		}
	}
} // End 
?>