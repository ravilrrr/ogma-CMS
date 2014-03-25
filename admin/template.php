<?php 

 /**
 *  ogmaCMS Templates Admin Page
 *
 *  @package ogmaCMS
 *  @author Mike Swan / n00dles101
 *  @copyright 2013 Mike Swan / n00dles101
 *  @since 1.0.0
 *
 */


include "template/head.inc.php";

if (isset($_REQUEST['settheme'])){
	if (Theme::themeExists($_REQUEST['settheme'])){
		Core::$site['template']=$_REQUEST['settheme'];
		$ret = $siteSettings->saveSettings();
		Debug::addUpdateLog(User::getUsername().__("CHANGEDTEMPLATE").$_REQUEST['settheme'],User::getUsername());
		//header("Location: template.php");
		//exit;
	};
}



include "template/navbar.inc.php";

 ?>
		<div class="col-md-12">
			<legend><?php echo __("CURRENTTHEME"); ?></legend>
		</div>


			<div class="col-md-6"> 
				<?php Theme::getImage(); ?>
			</div>
			<div class="col-md-6"> 
				<?php Theme::getThemeInfo(); ?>

				<?php if (Theme::hasOptions()){
					echo '<div><a href="theme.php?action=view" class="btn btn-primary" >'.__('EDITTHEMEOPTIONS').'</a></div>';

				}
				?>
			</div>


			<div class="col-md-10 morethemes">
				<legend><?php echo __("MORETEMPLATES"); ?></legend>
			</div>
				
		<div class="col-md-10">
		<?php Theme::showAllThemes(); ?>
		</div>
			

	</div>

			
<?php 
	include "template/footer.inc.php"; 
?>