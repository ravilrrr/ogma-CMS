<?php
// ----------------------------------------------------------------
// Field Type: Datetimepicker
// ----------------------------------------------------------------

class Datetimepicker
{
	var $value;

	function Datetimepicker($name, $label, $type, $options,$value='', $help='')
	{
		$action = (isset($_GET['action'])) ? $_GET['action'] : "create";
		$class = isset($options['class']) ? $options['class'] : '';
		
		// convert the system date formats into a suitable format for datetime picker
		$dateformats = array(
			'F j, Y' 	=> 'MM/dd/yyy',
			'Y/m/d' 	=> 'yyyy/MM/dd',
			'm/d/Y' 	=> 'MM/dd/yyyy',
			'd/m/Y' 	=> 'dd/MM/yyyy',
			'Y:m:d' 	=> 'yyyy:MM:dd',
			'm:d:Y' 	=> 'MM:dd:yyyy',
			'd:m:Y' 	=> 'dd:MM:yyyy'
		);

		$dateformat = $dateformats[ Core::$site['dateformat'] ];

		switch($action)
		{

			// Overview output.
			// -------------------------------------------------
			case "edit":
				$this->value .=  '<div class="form-group">';
				$this->value .=  '<label class="col-sm-2" for="'.$name.'">'.$label.'</label>';
				$this->value .=  '	<div class="col-sm-4">';
				$this->value .=  '  <div id="datetimepicker-'.$name.'" class="input-group">';
				$this->value .=  '    <input data-format="'.$dateformat.'" class="form-control" id="'.$name.'" name="'.$name.'"  type="text" value="'.Core::date($value, true).'"></input>';
				$this->value .=  '    <span class="input-group-addon">';
				$this->value .=  '      <span class="glyphicon glyphicon-calendar" data-time-icon="glyphicon-time" data-date-icon="glyphicon-calendar">';
				$this->value .=  '      </span>';
				$this->value .=  '    </span>';
				$this->value .=  '  </div>';
				if ($help!='') $this->value .= '<p class="help-block">'.$help.'</p>';
				$this->value .=  '	</div>';
				$this->value .=  '</div>';
				$this->value .=  '<script type="text/javascript">';
				$this->value .= '  jQuery(document).ready(function () {';
				$this->value .=  '    $("#datetimepicker-'.$name.'").datetimepicker({';
				$this->value .= '      pickTime: true, format: "'.$dateformat.' hh:mm:ss"' ;
				$this->value .=  '    });';
				$this->value .=  '  });';
				$this->value .=  '</script>';
				break;

			// Entryview output.
			// -------------------------------------------------
			case "create":
				$this->value .=  '<div class="form-group">';
				$this->value .=  '<label class="col-sm-2" for="'.$name.'">'.$label.'</label>';
				$this->value .=  '	<div class="col-sm-4">';
				$this->value .=  '  <div id="datetimepicker-'.$name.'" class="input-group">';
				$this->value .=  '    <input data-format="'.$dateformat.'" class="form-control" id="'.$name.'" name="'.$name.'"  type="text" value="'.Core::date(date('U'), true).'"></input>';
				$this->value .=  '    <span class="input-group-addon">';
				$this->value .=  '      <span class="glyphicon glyphicon-calendar" data-time-icon="glyphicon-time" data-date-icon="glyphicon-calendar">';
				$this->value .=  '      </span>';
				$this->value .=  '    </span>';
				$this->value .=  '  </div>';
				if ($help!='') $this->value .= '<p class="help-block">'.$help.'</p>';
				$this->value .=  '	</div>';
				$this->value .=  '</div>';	
				$this->value .=  '<script type="text/javascript">';
				$this->value .= '  jQuery(document).ready(function () {';
				$this->value .=  '    $("#datetimepicker-'.$name.'").datetimepicker({';
				$this->value .= '      pickTime: true, format: "'.$dateformat.' hh:mm:ss"' ;
				$this->value .=  '    });';
				$this->value .=  '  });';
				$this->value .=  '</script>';
				break;

			// not found so output typical value.
			// -------------------------------------------------
			default:
	        	$this->value = Core::date($value, true);
				break;
		}			
	}
} // End
?>