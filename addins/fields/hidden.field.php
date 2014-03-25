<?php
// ----------------------------------------------------------------
// Field Type: Hidden
// ----------------------------------------------------------------

class Hidden
{
	var $value;

	function Hidden($name, $label, $type, $options,$value='')
	{
		$action = (isset($_GET['action'])) ? $_GET['action'] : "create";
		switch($action)
		{
			// Edit output.
			// -------------------------------------------------
			case "edit":
				$this->value .= '	<input type="text" id="'.$name.'" name="'.$name.'" placeholder="'.$label.'"  class="input-xxlarge" style="display:none;" value="'.$value.'" >';			
				break;

			// New output.
			// -------------------------------------------------
			case "create":
				$this->value .= '	<input type="text" id="'.$name.'" name="'.$name.'" placeholder="'.$label.'"  class="input-xxlarge"  style="display:none;" value="'.$value.'" >';		
				break;

			// not found so output typical value.
			// -------------------------------------------------
			default:
				$this->value = $value;
		}
	}
} // End 
?>