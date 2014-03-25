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

//$adminOnly = true;

include "template/head.inc.php";
include "template/navbar.inc.php";

if ($_GET['action']=="view") $_GET['action']="edit";

$action = Core::getAction();            // get URI action

$id = Core::getID();                    // get page ID

$settings = Core::$site; 

$alert = '';

if ($action=='edit'){
?>
		<div class="col-md-12">
		<?php 
			Core::getAlerts();
		?>
  		
		<legend><?php echo __("EDITSETTINGS"); ?></legend>
		<p>
		    <button class="btn btn-info btn-sm has-spinner" id="clearcache" type="button"  data-loading-text="<?php echo __('WORKING'); ?>" ><?php echo __("CLEARCACHE"); ?></button>
		    <button class="btn btn-info btn-sm has-spinner" id="sitemap" type="button"  data-loading-text="<?php echo __('WORKING'); ?>" ><i class="fa fa-fw fa-sitemap"></i> <?php echo __("CREATESITEMAP"); ?></button>
		</p>
		 <?php 

		    $ogmaForm = new Form();

		    $ogmaForm->startTabHeaders();

		    $ogmaForm->createTabHeader(array('main'=>__("SITESETTINGS")),true);
		    $ogmaForm->createTabHeader(array('system'=>__("SYSTEMSETTINGS"))); 
			$ogmaForm->createTabHeader(array('maint'=>__("MAINTENANCE"))); 
		   			   	

		    Actions::executeAction('settings-tab-header');

		    $ogmaForm->endTabHeaders();
		    
		    $ogmaForm->createForm('settings.php?action=update');

		    $ogmaForm->startTabs();

		    $ogmaForm->createTabPane('main',true);
		    $ogmaForm->displayField('post-sitename',__("SITENAME"), 'textlong', '',$settings['sitename']);
		    $ogmaForm->displayField('post-siteurl',__("SITEURL"),  'text', '',$settings['siteurl']);
		    $ogmaForm->displayField('post-template',__("TEMPLATE"),  'hidden', '',$settings['template']);
			$ogmaForm->displayField('post-metadesc',__("METADESC"),  'textarea', array("rows"=>5),$settings['metadesc']);
			$ogmaForm->displayField('post-metak',__("METAKEYWORDS"),  'textarea', array("rows"=>3),$settings['metak']);
			$ogmaForm->displayField('post-email',__("SYSTEMEMAIL"),  'textlong', '',$settings['email']);
			
			
			$ogmaForm->createTabPane('system',false);
			$ogmaForm->displayField('post-bootstrap',__("BOOTSTRAP"),  'yesno', '',$settings['bootstrap']);
			$ogmaForm->displayField('post-language',__("LANG"),  'dropdown', Lang::getInstalledLanguages(),$settings['language']);
			$ogmaForm->displayField('post-timezone',__("TIMEZONE"),  'dropdown', Lang::$timezones,$settings['timezone']);
			$ogmaForm->displayField('post-dateformat',__("DATEFORMAT"),  'dropdown', Lang::$dateformats ,$settings['dateformat']);	
			$ogmaForm->displayField('post-timeformat',__("TIMEFORMAT"),  'textlong', '',$settings['timeformat']);
			
			$ogmaForm->displayField('post-debug',__("DEBUG"),  'yesno', '',$settings['debug']);
			$ogmaForm->displayField('post-cdn',__("CDN"),  'yesno', '',$settings['cdn']);
			$ogmaForm->displayField('post-minify',__("MINIFY"),  'yesno', '',$settings['minify']);

			$ogmaForm->createTabPane('maint',false);
			$ogmaForm->displayField('post-maintenance',__("MAINTENANCE"),  'yesno', '',$settings['maintenance']);
			$ogmaForm->displayField('post-maintmessage',__('MAINTMESSAGE'),  'textarea', '',$settings['maintmessage']);

		    Actions::executeAction('settings-tab-new');

		    $ogmaForm->endTabs();
		    
		    $ogmaForm->formButtons();
		    $ogmaForm->endForm();

		    $ogmaForm->show();

		    ?>
	</div>
<?php 
}
	include "template/footer.inc.php"; 
?>