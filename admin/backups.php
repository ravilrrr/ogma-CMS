<?php 

 /**
 *	ogmaCMS Backups Admin Page
 *
 *	@package ogmaCMS
 *	@author Mike Swan / n00dles101
 *	@copyright 2013 Mike Swan / n00dles101
 *	@since 1.0.0
 *
 */

	include "template/head.inc.php";
	include "template/navbar.inc.php";

	error_reporting(E_ALL);
	$alert='';
	// check validity of request
	if (isset($_REQUEST['s']) && $_REQUEST['s'] === "backup") {
		$archive = new Archive();
		$archive->setRootPath(Core::$settings['rootpath']);
		$archive->getFiles('data');
		$archive->getFiles('addins');
		$archive->getFiles('theme');
		$archive->getFiles('uploads');
		if ($_POST['backupname']!='' ){
			$backupname = $_POST['backupname'];
			if (substr($backupname, -4)!=".zip") $backupname .= ".zip";
		} else {
			$backupname = date('U')."_archive.zip" ;	
		}
		$name = "backups".DS.$backupname;
		
		$archive->doBackup(Core::$settings['rootpath'].$name);
	}


	$action = Core::getAction();            // get URI action
	$id = Core::getID();                    // get page ID

	if ($action=="delete" && Security::checkNonce($_POST['backup-nonce'],'delete-backup','backups.php')){
		$file = Core::getRootPath().'backups'.DS.$_POST['backup-name'];
		if (Core::verifyPath($file, Core::$settings['backuppath'])) {
			if (file_exists($file)) {
				$ret = unlink($file);
				if ($ret){
					Core::addAlert( Form::showAlert('success', __("BACKUPDELETED") ) );
				} else {
					Core::addAlert( Form::showAlert('warning', __("BACKUPDELETEFAILED") ) );
				}
			}
		}
	}


	$archives = Archive::listArchives();


	?>
	<div class="col-md-12">
		<?php 
		  Core::getAlerts();
		?>
		<legend><?php echo __("VIEWBACKUPS"); ?></legend>
	 	<div class="row">
		 	<form  class="form-inline" role="form" action="backups.php?s=backup" method="post" >
			 	<div class="input-group col-md-6">
	  		 		<input type="text" class="form-control" id="backupname" name="backupname" placeholder="<?php echo __("BACKUPFILENAME"); ?>" >
			 	  	<span class="input-group-addon">.zip</span>
				</div>
				<button class="btn btn-primary" type="submit" onclick="$(this).button('loading');" data-loading-text="<?php echo __('CREATEBACKUP'); ?>" >
			 		<span class="glyphicon glyphicon-plus"></span> <?php echo __("CREATENEW",array(":type"=>"Backup")); ?>
			 	</button>
				<input type="hidden" id="backup-nonce" name="dobackup-nonce" value="<?php echo Security::getNonce('delete-backup','backups.php'); ?>" />     
		 		
		 	</form>
	 	</div>

	  <table class="table table-bordered table-striped table-hover">
	    <thead>
	   
	      <tr>
	      	
	        <th><?php echo __("NAME"); ?></th>
	       
	      </tr>


	    </thead>
	    <tbody> 
	    <?php 
	 		foreach($archives as $archive){ 
	      
	    ?>
	      <tr>

	        <td><a href="<?php echo "/backups/".$archive; ?>" /><?php echo "backups/".$archive; ?></a>
	        
			<div style="float:right;">
			 <button class="btn btn-warning deletebackup" data-delete="<?php echo $archive; ?>"><i class="fa fa-fw fa-trash-o"></i> <?php echo __("DELETE"); ?></button>
			 <button class="btn btn-success restorebackup" data-restore="<?php echo $archive; ?>"><i class="fa fa-fw fa-undo"></i> <?php echo __("RESTORE"); ?></button>
			 
			</div>
	        </td>
	      </tr>
			<?php 
			}
			?>

	    </tbody>
	  </table>
			
		

		</div>
	</div>
	<!-- Restore Dialog -->
	<div class="modal fade" id="confirmRestore" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	        <h4 class="modal-title"><?php echo __("RESTOREBACKUP"); ?></h4>
	      </div> 
	      <form action="<?php echo Core::getFilenameId(); ?>.php?action=delete" method="post" >
	      <div class="modal-body">
	        <p><?php echo __("RESTOREAREYOUSURE"); ?></p>
	        <input type="checkbox" checked > <?php echo __("RESTOREBACKUPFIRST"); ?>
	      </div>
	      <div class="modal-footer">
	       
		        <input type="hidden" id="restore-name" name="backup-name" value="" />
		        <input type="hidden" id="restore-nonce" name="backup-nonce" value="<?php echo Security::getNonce('delete-backup','backups.php'); ?>" />
		        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __("CANCEL"); ?></button>
		        <button type="submit" class="btn btn-danger" id="confirm"><?php echo __("DELETE"); ?></button>
		   </form>
	      </div>
	    </div>
	  </div>
	</div>
	<!-- Modal Dialog -->
	<div class="modal fade" id="confirmDelete" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	        <h4 class="modal-title"><?php echo __("DELETEBACKUP"); ?></h4>
	      </div>
	      <div class="modal-body">
	        <p><?php echo __("DELETEAREYOUSURE"); ?></p>
	      </div>
	      <div class="modal-footer">
	        <form action="<?php echo Core::getFilenameId(); ?>.php?action=delete" method="post" >
		        <input type="hidden" id="backup-name" name="backup-name" value="" />
		        <input type="hidden" id="backup-nonce" name="backup-nonce" value="<?php echo Security::getNonce('delete-backup','backups.php'); ?>" />
		        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __("CANCEL"); ?></button>
		        <button type="submit" class="btn btn-danger" id="confirm"><?php echo __("DELETE"); ?></button>
		   </form>
	      </div>
	    </div>
	  </div>
	</div>


<?php 
	include "template/footer.inc.php"; 
?>