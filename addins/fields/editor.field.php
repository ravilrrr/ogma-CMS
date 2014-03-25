<?php
// ----------------------------------------------------------------
// Field Type: Editor
// ----------------------------------------------------------------

class Editor
{
	var $value;

	function Editor($name, $label, $type, $options,$value='', $help='')
	{
		$action = (isset($_GET['action'])) ? $_GET['action'] : "create";

		$rows = isset($options['rows']) ? $options['rows'] : '15';
	
		switch($action)
		{

			// Edit output.
			// -------------------------------------------------
			case "edit":
				
				$this->value .= '<div class="form-group">';
				
				$this->value .= '<label class="col-sm-2" for="'.$name.'">'.$label.'</label>';
				$this->value .= '	<div class="col-sm-10">';
				$this->value .= Ogmaeditor::displayToolbar($name);
				$this->value .= '		<textarea class="input-xxlarge  editor"  id="'.$name.'" name="'.$name.'" rows="'.$rows.'">'.Utils::safe_strip_decode($value).'</textarea>';
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
				$this->value .= Ogmaeditor::displayToolbar($name);
				$this->value .= '	<textarea class="input-xxlarge  editor"  id="'.$name.'" name="'.$name.'" rows="'.$rows.'">'.Utils::safe_strip_decode($value).'</textarea>';
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