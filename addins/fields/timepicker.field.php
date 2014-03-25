<?php
// ----------------------------------------------------------------
// Field Type: Timepicker
// ----------------------------------------------------------------

class Timepicker
{
	var $value;

	function Timepicker($name, $label, $type, $options,$value='',$help='')
	{
		$action = (isset($_GET['action'])) ? $_GET['action'] : "create";

		switch($action)
		{

			// Overview output.
			// -------------------------------------------------
			case "edit":
				$this->value .= '<div class="form-group">';
				$this->value .= '<label class="col-sm-2" for="'.$name.'">'.$label.'</label>';
				$this->value .= '	<div class="col-sm-2">';
				$this->value .= '  <div id="datetimepicker-'.$name.'" class="input-append">';
				$this->value .= '    <input data-format="hh:mm:ss"  id="'.$name.'" name="'.$name.'"  class="form-control" type="text" value="'.$value.'"></input>';
				$this->value .= '    <span class="add-on">';
				$this->value .= '      <i data-time-icon="icon-time" data-date-icon="icon-calendar">';
				$this->value .= '      </i>';
				$this->value .= '    </span>';
				$this->value .= '  </div>';
				if ($help!='') $this->value .= '<p class="help-block">'.$help.'</p>';
				$this->value .= '	</div>';
				$this->value .= '</div>';
				$this->value .= '<script type="text/javascript">';
				$this->value .= '  $(function() {';
				$this->value .= '    $("#datetimepicker-'.$name.'").datetimepicker({';
				$this->value .= '      pickDate: false';
				$this->value .= '    });';
				$this->value .= '  });';
				$this->value .= '</script>';
				break;

			// Entryview output.
			// -------------------------------------------------
			case "create":
				$this->value .= '<div class="form-group">';
				$this->value .= '<label class="col-sm-2" for="'.$name.'">'.$label.'</label>';
				$this->value .= '	<div class="col-sm-2">';
				$this->value .= '  <div id="datetimepicker-'.$name.'" class="input-append">';
				$this->value .= '    <input data-format="hh:mm:ss"  id="'.$name.'" name="'.$name.'"  class="form-control" type="text" value="'.$value.'"></input>';
				$this->value .= '    <span class="add-on">';
				$this->value .= '      <i data-time-icon="icon-time" data-date-icon="icon-calendar">';
				$this->value .= '      </i>';
				$this->value .= '    </span>';
				$this->value .= '  </div>';
				if ($help!='') $this->value .= '<p class="help-block">'.$help.'</p>';
				$this->value .= '	</div>';
				$this->value .= '</div>';
				$this->value .= '<script type="text/javascript">';
				$this->value .= '  $(function() {';
				$this->value .= '    $("#datetimepicker-'.$name.'").datetimepicker({';
				$this->value .= '      pickDate: false';
				$this->value .= '    });';
				$this->value .= '  });';
				$this->value .= '</script>';
				break;

			// not found so output typical value.
			// -------------------------------------------------
			default:
				$this->value = $value;
		}			
	}
} // End 
?>