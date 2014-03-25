<?php
// ----------------------------------------------------------------
// Field Type: Spinner
// ----------------------------------------------------------------

class Spinner
{
	var $value;

	function Spinner($name, $label, $type, $options,$value='',$help='')
	{
		$action = (isset($_GET['action'])) ? $_GET['action'] : "create";
		
		switch($action)
		{


			// Edit output.
			// -------------------------------------------------
			case "edit":
				$this->value .= '<div class="form-group">';
				$this->value .= '<label class="col-sm-2" for="'.$name.'">'.$label.'</label>';
				$this->value .= '	<div class="col-sm-6"><div>';
				$this->value .= '	<input type="text" id="'.$name.'" name="'.$name.'" placeholder="'.$label.'"  class="spinedit" value="'.$value.'">';
				if ($help!='') $this->value .= '<p class="help-block">'.$help.'</p>';
				$this->value .= '	</div></div>';
				$this->value .= '</div>';				
				break;

			// New output.
			// -------------------------------------------------
			case "create":
				$this->value .= '<div class="form-group">';
				$this->value .= '<label class="col-sm-2" for="'.$name.'">'.$label.'</label>';
				$this->value .= '	<div class="col-sm-6"><div>';
				$this->value .= '	<input type="text" id="'.$name.'" name="'.$name.'" placeholder="'.$label.'"  class="spinedit" value="'.$value.'">';
				if ($help!='') $this->value .= '<p class="help-block">'.$help.'</p>';
				$this->value .= '	</div></div>';
				$this->value .= '</div>';				
				break;

			// not found so output typical value.
			// -------------------------------------------------
			default:
				$this->value .= '	<input type="text" id="'.$name.'" name="'.$name.'" data-field="'.$options['field'].'" data-id="'.$options['id'].'" data-table="'.$options['table'].'" placeholder="'.$label.'"  class="spinedit spinajax input-xlarge" value="'.$value.'">';
				break;
		}
	}
} // End
?>