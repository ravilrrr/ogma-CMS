<?php 
ini_set('display_errors',1); 
error_reporting(E_ALL);
 /**
 *	ogmaCMS Files Admin Page
 *
 *	@package ogmaCMS
 *	@author Mike Swan / n00dles101
 *	@copyright 2013 Mike Swan / n00dles101
 *	@since 1.0.0
 *
 */


    $alert="";
	include "template/head.inc.php";
	include "template/navbar.inc.php";
    $fm = new Fm();     


    $timestamp = date('U');


    if (isset($_POST['new-folder']) && $_POST['new-folder']==Security::checkNonce($_POST['new-folder'],'addfolder', 'files.php')){
        $fm->create();
    }

    if (isset($_POST['add-media']) && $_POST['add-media']==Security::checkNonce($_POST['add-media'],'addmedia', 'files.php')){
        $records = New Query('media');            
        $ret = $records->addRecordForm();
          if ($ret){
            $alert = Form::showAlert('success', __("CREATED",array(":record"=>"Media",":type"=>"Media")));
          } else {
            $alert = Form::showAlert('error', __("CREATEDFAIL",array(":record"=>"Media",":type"=>"Media")));

          }
    }
    ?>

		<div class="col-md-8">
            <?php 
            if ($alert!=''){
                echo $alert;
            }
            ?>
			<legend>Media Folders</legend>
			 <!-- message box -->
            <div id="msgbox">
                <?php 
                    $errormsg = $fm->errors();
                    if ($errormsg != ''){
                        echo $errormsg;
                    }

                ?>
            </div>
 <!-- tool box -->
            <div class="well-small" id="tools">
                <a class="btn btn-primary"  data-type="folder" id="new-folder-button"><i class="fa fa-fw fa-folder-open-o"></i> New folder</a>
            </div>

            <!-- breadcrumb -->
            <?php $fm->showBreadcrumbs(); 

            $path =  isset($_GET['path']) ? $_GET['path'] : null;
            ?>
            
            <div id="filemanager" data-path='<?php echo $path; ?>' >
                
            <table class="table table-hover table-condensed fm">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Size</th>
                        <th>Date</th>
                        <th>Perms</th>
                        <th>Media</th>
                        <th>Options</th>
                    </tr> 
                </thead>  
                    <tbody>
                    <?php $fm->index(); ?>
                    </tbody>
            </table>
            </div>
            

            <!-- progress bar -->
            <div id="progress" class="progress progress-striped active hide">
                <div class="bar"></div>
            </div>

           
		</div>
            <div class="col-md-4">
                <legend>Upload Files</legend>
                 <!-- <a class="btn btn-primary" id="file_upload"  name="file_upload"><i class="fa fa-fw fa-upload"></i> Select Files</a> -->
                <div id="dropzone">
                <form action="upload.php" class="dropzone dz-clickable" id="file_upload" method="post" >
                    <div class="dz-message"><span><?php echo __("DROPFILES"); ?></span></div>
                    <input type="hidden" name="realpath" value="<?php echo $fm->getRelPath(); ?>" />
                    <input type="hidden" name="timestamp" value="<?php echo $timestamp; ?>" />
                    <input type="hidden" name="token" value="<?php echo md5("unique_salt".$timestamp); ?>" />
                </form>
                </div>
            </div>

		</div>


<!-- media modal -->
            <div id="addmedia" class="modal  fade"   tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h3>Add File to Media</h3>
                    <h4 id="fileurl"></h4>
                </div>
               
                    <div class="modal-body">
                       
                    <?php 

                    $ogmaForm = new Form();
    
                    $ogmaForm->createForm('#','submitmedia');

                    $ogmaForm->displayField('add-media',__("IMAGE"),  'hidden', '',Security::getNonce('addmedia','files.php'));
                    $ogmaForm->displayField('post-fileurl',__("IMAGE"),  'hidden', '','');
                    $ogmaForm->displayField('post-showorder',__("ORDER"),  'spinner', '','');
                    $ogmaForm->displayField('post-tag',__("TAG"),  'textlong', '','');
                    $ogmaForm->displayField('post-title',__("TITLE"), 'textlong', '','');
                    $ogmaForm->displayField('post-alt',__("ALT"),  'textlong', '','');
                    $ogmaForm->displayField('post-caption',__("CAPTION"),  'textlong', '','');
                    $ogmaForm->displayField('post-description',__("DESCRIPTION"),  'textarea', array('rows'=>'3'),'');

                    $ogmaForm->output(' </div><div class="modal-footer">');
                    $ogmaForm->formButtons(false);
                    $ogmaForm->output('</div>');
                    
                    $ogmaForm->endForm();

                    $ogmaForm->show();

                    ?>

                   
                  
                    </div>
                    </div>
            </div>


 <!-- new modal -->
           

        <?php 
   
        $timestamp = date('U');
        ?>

        </div>
         <div id="newfolder" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="newfolder" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h3><?php echo __("CREATEFOLDER"); ?></h3>
                </div>
                <?php $path =  isset($_GET['path']) ? "?path=".$_GET['path'] : null; ?>
                <form id="createnewfolder" action="files.php<?php echo $path ?>" method="post" >
                    <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo __("FOLDERNAME"); ?></label>
   
                        <input type="hidden" id="new-folder" name="new-folder" value="<?php echo Security::getNonce('addfolder','files.php'); ?>" />
                        <input type="text" class="form-control" id="target" name="target" />
                    </div>
                    </div>
                    <div class="modal-footer">
                        <a class="submit btn btn-primary" id="createfolder" data-dismiss="modal"><?php echo __("CREATE"); ?></a>
                        <a class="btn" data-dismiss="modal"><?php echo __("CANCEL"); ?></a>
                    </div>
                </form>
                </div>
                </div>

            </div>

            <!-- remove modal -->
            <div id="remove" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="removefile" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h3>Rename</h3>
                </div>
                <div class="modal-body">
                    <input type="text" class="input-xlarge" id="remove-target" disabled /><br />
                    <small>Only empty directories can be removed.</small>
                </div>
                <div class="modal-footer">
                    <a class="submit btn btn-danger" data-dismiss="modal"><?php echo __("REMOVE"); ?></a>
                    <a class="btn" data-dismiss="modal"><?php echo __("CANCEL"); ?></a>
                </div>
                </div>
                </div>
            </div>

            <!-- upload modal -->
            <div id="upload" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h3>Upload</h3>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="fun" id="upload-fun" />
                        <input type="hidden" name="path" id="upload-path" />
                        <input type="file" name="files[]" multiple /><br />
                        <small>You can select multiple files.</small>
                    </form>
                </div>
                <div class="modal-footer">
                    <a class="submit btn btn-primary" data-dismiss="modal"><?php echo __("UPLOAD"); ?></a>
                    <a class="btn" data-dismiss="modal"><?php echo __("CANCEL"); ?></a>
                </div>
                </div>
                </div>
            </div>




<?php
	include "template/footer.inc.php"; 
?>