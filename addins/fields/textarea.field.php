<?php
// ----------------------------------------------------------------
// Field Type: Textarea
// ----------------------------------------------------------------

class Textarea
{
	var $value;

	function Textarea($name, $label, $type, $options,$value='',$help='')
	{

		$rows = isset($options['rows']) ? $options['rows'] : '15';
		$class = isset($options['class']) ? $options['class'] : '';
		$codeedit = isset($options['codeedit']) ? $options['codeedit'] : false;
	
		$action = (isset($_GET['action'])) ? $_GET['action'] : "create";
		switch($action)
		{

			// Edit output.
			// -------------------------------------------------
			case "edit":
				$this->value .= '<div class="form-group">';
				$this->value .= '<label class="col-sm-2" for="'.$name.'">'.$label.'</label>';
				$this->value .= '	<div class="col-sm-10">';
				$this->value .= '	<textarea class="form-control textedit '.$class.'"  id="'.$name.'" name="'.$name.'" rows="'.$rows.'">'.Utils::safe_strip_decode($value).'</textarea>';
				if ($help!='') $this->value .= '<p class="help-block">'.$help.'</p>';
				$this->value .= '	</div>';
				$this->value .= '</div>';				
				if ($codeedit=='true'){
					 $this->value .= ('<script>
	                        $(document).ready(function() {
	                            var editor = CodeMirror.fromTextArea(document.getElementById("'.$name.'"), {
	                                lineNumbers: true,
	                                matchBrackets: true,
	                                indentUnit: 4,
	                                mode:  "htmlmixed",
	                                indentWithTabs: true                   
	                            });
	                        });
	                </script>');
				}
				break;
				
			// New output.
			// -------------------------------------------------
			case "create":
				$this->value .= '<div class="form-group">';
				$this->value .= '<label class="col-sm-2" for="'.$name.'">'.$label.'</label>';
				$this->value .= '	<div class="col-sm-10">';
				$this->value .= '	<textarea class="form-control textedit '.$class.'"  id="'.$name.'" name="'.$name.'" rows="'.$rows.'">'.Utils::safe_strip_decode($value).'</textarea>';
				if ($help!='') $this->value .= '<p class="help-block">'.$help.'</p>';
				$this->value .= '	</div>';
				$this->value .= '</div>';
				if ($codeedit=='true'){
					 $this->value .= ('<script>
	                        $(document).ready(function() {
	                            var editor = CodeMirror.fromTextArea(document.getElementById("'.$name.'"), {
	                                lineNumbers: true,
	                                matchBrackets: true,
	                                indentUnit: 4,
	                                mode:  "htmlmixed",
	                                indentWithTabs: true                   
	                            });
	                        });
	                </script>');
				}
				break;				
				break;

			// not found so output typical value.
			// -------------------------------------------------
			default:
				$this->value = $value;
		}
	}
} // End
?>