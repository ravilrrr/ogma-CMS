<?php
// ----------------------------------------------------------------
// Field Type: Textlong
// ----------------------------------------------------------------

class Image
{
	public $value = '';

	function Image($name, $label, $type, $options,$value='',$help='')
	{
		$action = (isset($_GET['action'])) ? $_GET['action'] : "create";

		switch($action)
		{

			// Edit output.
			// -------------------------------------------------
			case "edit":
				$this->value .= '<div class="form-group">';
				$this->value .= '<label class="col-sm-2" for="'.$name.'">'.$label.'</label>';
				$this->value .= '	<div class="col-sm-10">';
				$this->value .= '<div class="input-group"><input class="form-control" id="'.$name.'" name="'.$name.'" placeholder="'.$label.'" value="'.$value.'" type="text"><span class="input-group-btn"><button id="browse-"'.$name.'" class="btn icon-search" type="button"><span class="glyphicon glyphicon-picture"></span></button></span></div>';
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
				$this->value .= '<div class="input-group"><input class="form-control" id="'.$name.'" name="'.$name.'" placeholder="'.$label.'" value="'.$value.'" type="text"><span class="input-group-btn"><button id="browse-"'.$name.'" class="btn icon-search" type="button"><span class="glyphicon glyphicon-picture"></span></button></span></div>';
				if ($help!='') $this->value .= '<p class="help-block">'.$help.'</p>';
				$this->value .= '	</div>';
				$this->value .= '</div>';	
				break;

			// not found so output typical value.
			// -------------------------------------------------
			default:
				//if (file_exists(Core::$site['siteurl'].$value)){
					$this->value = "<a href='".Core::$site['siteurl'].$value."' data-toggle='lightbox'><img src='".Core::$site['siteurl'].$value."' style='width:120px;' ?></a>";
				//} else {

				/**	$this->value = "<a href='".Core::$site['siteurl']."' data-toggle='lightbox'><img src='".Core::$site['siteurl']."/admin/template/img/noimage.png' style='width:120px;' ?></a>";
				
				//}

				**/
 		}
		
	}
} // End
?>