<?php 

 /**
 *	ogmaCMS Settings Admin Page
 *
 *	@package ogmaCMS
 *	@author Mike Swan / n00dles101
 *	@copyright 2013 Mike Swan / n00dles101
 *	@since 1.0.0
 *
 */


include "template/head.inc.php";
include "template/navbar.inc.php";


if (isset($_GET['action']) && $_GET['action']=="view") $_GET['action']="edit";

$action = Core::getAction();            // get URI action

$id = Core::getID();                    // get page ID

$theme = Core::$site['template'];
$settings = Theme::$themeInfo[$theme]['options'];
$alert = '';
// Update a Blog Entry
if ($action=="update"){
	
	$ret= Theme::saveSettings();	
	
	if ($ret){
		$alert = '<div class="alert alert-success"><a class="close">x</a><p>'.__("UPDATED",array(":record"=>$id,":type"=>"Theme Settings")).'</p></div>';
	} else {
		$alert = '<div class="alert alert-error"><a class="close">x</a><p>'.__("UPDATEDFAIL",array(":record"=>$id,":type"=>"Theme Settings")).'</p></div>';
	}
	$action='edit';
	$_GET['action'] = "edit";
}



?>
<div class="col-md-12">
	<?php 
	  if ($alert!=''){
	    echo $alert;
	  }
	  ?>
	  <legend>Edit Theme Settings</legend>

	<?php 
	if ($action=='edit'){
		if (method_exists($theme, 'admin')){
			$theme::admin(); 
		}
	}
	?>
</div>
<?php 
	include "template/footer.inc.php"; 
?>