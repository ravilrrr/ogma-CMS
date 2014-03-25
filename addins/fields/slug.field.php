<?php
// ----------------------------------------------------------------
// Field Type: Slug
// ----------------------------------------------------------------

class Slug
{
	var $value;

	function Slug($name, $label, $type, $options,$value='',$help='')
	{
		$action = (isset($_GET['action'])) ? $_GET['action'] : "create";
		$class = isset($options['class']) ? $options['class'] : '';
			
		switch($action)
		{

			// Edit output.
			// -------------------------------------------------
			case "edit":
				$this->value .= '<div class="form-group">';
				$this->value .= '<label class="col-sm-2" for="'.$name.'">'.$label.'</label>';
				$this->value .= '	<div class="col-sm-10">';
				$this->value .= '	<input type="text" id="'.$name.'" name="'.$name.'" placeholder="'.$label.'"  class="slug form-control '.$class.'" value="'.$value.'">';
				if ($help!='') $this->value .= '<p class="help-block">'.$help.'</p>';
				$this->value .= '	</div>';
				$this->value .= '</div>';				
				break;

			// New output.
			// -------------------------------------------------
			case "create":
				$this->value .= '<div class="form-group">';
				$this->value .= '<label class="col-sm-2" for="'.$name.'">'.$label.'</label>';
				$this->value .= '	<div class="col-sm-10">';
				$this->value .= '	<input type="text" id="'.$name.'" name="'.$name.'" placeholder="'.$label.'"  class="slug form-control '.$class.'" value="'.$value.'">';
				if ($help!='') $this->value .= '<p class="help-block">'.$help.'</p>';
				$this->value .= '	</div>';
				$this->value .= '</div>';				
				break;

			// not found so output typical value.
			// -------------------------------------------------
			default:
				$this->value = $value;
		}
	}
} // End
?>