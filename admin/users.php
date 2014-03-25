<?php 

 /**
 *  ogmaCMS Users Admin Page
 *
 *  @package ogmaCMS
 *  @author Mike Swan / n00dles101
 *  @copyright 2013 Mike Swan / n00dles101
 *  @since 1.0.0
 *
 */

include "template/head.inc.php";
include "template/navbar.inc.php";

 
$action = Core::getAction();            // get URI action
$id = Core::getID();                    // get page ID

$table = new Query('users');

extract(Query::getSortOptions());

$alert = '';


if ($action=="deleterecord" ){
  if ($id!='0'){
    if (User::isAdmin()){
      $nonce = $_POST['security-nonce'];
      $record = $_POST['security-record'];
      $tableid = $_POST['security-table'];
      if (Security::checkNonce($nonce,'deleterecord', 'users.php')){
        $ret=$table->deleteRecord($record);
        $action='view';
      } else {
        Core::addAlert( Form::showAlert('error', __('SECURITYALERT')) ); 
      }
    } else {
        Core::addAlert( Form::showAlert('error', __('NOPERMISSION')) ); 
        $action='view';
    }
  } else {
    $alert = Core::addAlert('error', __("NOPERMISSION"));
  }
}


// Update a Blog Entry
if ($action=="update"){
	$record = $table->getFullRecord($id);
	$fieldTypes= $table->tableFields;
  if (($_POST['post-password']=="" || $_POST['post-password2']=="") ||  ($_POST['post-password2']!=$_POST['post-password'])){
    unset($_POST['post-password']);
  } 
  if (isset($_POST['post-password'])){
    $_POST['post-salt'] = Security::genKey(20);
    $_POST['post-password']=$_POST['post-salt'].$_POST['post-password'];
  } 

	foreach($fieldTypes as  $name => $val){
		if (!isset($_POST['post-'.$name]) && $val=="checkbox") $_POST['post-'.$name]='false';
		if(isset($_POST['post-'.$name])){
			$record[$name]=Utils::manipulateValues($_POST['post-'.$name],$val);
		}
	}

  if (User::getUserName()==$_POST['post-username']){
      Session::set('username', $_POST['post-username']);
      Session::set('role',$_POST['post-role']);
      Session::set('lang',$_POST['post-language']);
      Session::set('email',$_POST['post-email']);
  }
  //Lang::loadLanguage(Core::$settings['rootpath'].'/addins/languages/'.$_POST['post-language'].'.lang.php');

	$ret=$table->saveRecord($record,$id);
	 if (isset($_POST['submitclose'])){ 
      $action='view';
    } else {
      $action='edit';
      $_GET['action']='edit';
    }
}

if ($action=="createnew"){
  $_POST['post-password']=$_POST['post-salt'].$_POST['post-password'];
  $ret = $table->addRecordForm();
  $action='view';
  $_GET['action']='view';
}

$alert = Core::$errorMsg;

if ($action=='view'){
	$table->getCache();
  $totalRecords = $table->count();
  $records = $table->order($sort,$dir)->range($page*PAGINGSIZE,PAGINGSIZE)->get();


  ?>
	<div class="col-md-12">
  <?php 
  Core::getAlerts();
  ?>
	<legend><?php echo __("VIEWUSER"); ?></legend>
	<?php 
      if (User::isAdmin()){
    ?> 
    <div class="btn-group" style="padding-bottom:15px;">
	 	<button class="btn btn-primary" onclick="location.href='users.php?action=create'"><span class="glyphicon glyphicon-plus"></span> <?php echo __("CREATENEW",array(":type"=>"User")); ?></button>
	</div>
    <?php 
    }
    ?>
  <table class="table table-bordered table-striped table-hover">
    <thead>
   
      <tr>
        <th><?php echo __("USERNAME"); ?></th>
        <th><?php echo __("NAME"); ?></th>
        <th><?php echo __("ROLE"); ?></th>
        <th style="width:100px;"><?php echo __("OPTIONS"); ?></th>
      </tr>


    </thead>
    <tbody> 
    <?php 
 		foreach($records as $record){ 
    if (User::isAdmin() or $record['username']==User::getUsername()){
    ?>
      <tr>
        <td><?php echo $record['username']; ?></td>
        <td><?php echo $record['firstname']." ".$record['lastname']; ?></td>
        <td><?php echo $record['role']; ?></td>
        
        <td>
		<div class="btn-group">
		 <button class="btn btn-default" onclick="location.href='users.php?action=edit&amp;id=<?php echo $record['id']; ?>'"><?php echo __("EDIT"); ?></button>
		  <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
		    <col-md- class="caret"></col-md->
		  </button>
		 <?php if (User::isAdmin()){ ?><ul class="dropdown-menu">
		 
          <li><a href="#" data-nonce="<?php echo Security::getNonce('deleterecord','users.php'); ?>" data-slug="<?php echo $record['id']; ?>" data-href="delme.php" data-table="users" class="delButton" ><?php echo __("DELETE"); ?></a></li>
        
        </ul>
        <?php } ?>
		</div>
        </td>
      </tr>
		<?php 

      }
		}
		
    ?>

    </tbody>
  </table>
	<?php
     Query::doPagination($page,$totalRecords);
  ?>
	</div>
<?php
} 



if ($action=='edit' || $action=="create"){
    $record = $table->getFullRecord($id);

	?>
	<div class="col-md-12">
	<?php 
    Core::getAlerts();

    if (User::isAdmin() or User::getUsername()==$record['username']){
    $ogmaForm = new Form();

    if ($action=="edit") $ogmaForm->addHeader(__("EDITUSER")." : ".$record['username']);
    if ($action=="create") $ogmaForm->addHeader(__("CREATEUSER"));

    $ogmaForm->startTabHeaders();

    $ogmaForm->createTabHeader(array('main'=>__("MAIN")),true);
    if (User::isAdmin() && $record['role']=="author"){
      $ogmaForm->createTabHeader(array('perms'=>__("PERMISSIONS")));
    }
    $ogmaForm->createExtrasTab('users', $table->tableFields);

    Actions::executeAction('users-tab-header');

    $ogmaForm->endTabHeaders();

    if ($action=="edit") $ogmaForm->createForm('users.php?action=update&amp;id='.$record['id']);
    if ($action=="create") $ogmaForm->createForm('users.php?action=createnew');

    $ogmaForm->startTabs();
    $ogmaForm->createTabPane('main',true);
    $ogmaForm->displayField('post-username',__("USERNAME"), 'text', array('class'=>' required'),$record['username']);
    $ogmaForm->displayField('post-salt',__("SALT"),  'disabled', '',$record['salt'], "This will be auto-generated");
    $ogmaForm->displayField('post-firstname',__("FIRSTNAME"),  'text', '',$record['firstname']);
    $ogmaForm->displayField('post-lastname',__("LASTNAME"),  'text', '',$record['lastname']);
    $ogmaForm->displayField('post-email',__("EMAIL"),  'text', array('class'=>' required'),$record['email']);
    $ogmaForm->displayField('post-password',__("PASSWORD"),  'password', '','');
    $ogmaForm->displayField('post-password2',__("PASSWORDAGAIN"),  'password', '','');
    $ogmaForm->displayField('post-reset', '',  'hidden', '',$record['reset']);
    $ogmaForm->displayField('post-perms', '',  'hidden', '',$record['perms']);
    $ogmaForm->output("<p id='validate-status'>&nbsp;</p>");
    if (User::isAdmin()){
      $ogmaForm->displayField('post-role',__("ROLE"),   'dropdown', array('admin'=>'Admin', 'author'=>'Author'),$record['role']);
    } else {
      $ogmaForm->displayField('post-role',__("ROLE"),  'dropdown', array( 'author'=>'Author'),$record['role']);      
    }
    $ogmaForm->displayField('post-language',__("LANGUAGE"), 'dropdown', Lang::getInstalledLanguages(),$record['language']);

    if (User::isAdmin() && $record['role']=="author"){
      $allowedPerms=$record['perms'];
      $ogmaForm->createTabPane('perms',false);
      $ogmaForm->displayField('post-perms',__("PERMISSIONS"), 'textlong', array('tags'=>true),$allowedPerms);
      $currentPerms = explode(',',$allowedPerms);
      $perms = Core::$permissions;
      foreach ($perms as $perm){
        if (in_array($perm, $currentPerms)){
          $checked = " checked='checked'";
        } else {
          $checked = "";
        }
        $ogmaForm->output('<div class="control-group"><label class="control-label" for="post-'.$perm.'">'.__(strtoupper($perm)).'</label><div class="controls"> <div id="normal-toggle-button"><input class="usertoggle" type="checkbox" value="'.$perm.'" name="userperms" '.$checked.'></div> </div></div>');
      }
    }

    $ogmaForm->createExtrasPane($record);

    Actions::executeAction('users-tab-new');

    $ogmaForm->endTabs();
    
    if ($action=="edit") $ogmaForm->formButtons(true);
    if ($action=="create") $ogmaForm->formButtons(false);

    $ogmaForm->endForm();

    $ogmaForm->show();
    } else {
      echo Form::showAlert('error', __("NOPERMISSION"));;
    }
    ?>
	</div>

<?php
} 

?>
<?php 
  if ($action=='create' || $action=='edit'){
?>
<script type="text/javascript">
  $(document).ready(function() {
    $("#post-password2").keyup(validate);
  });

  function validate() {
    var password1 = $("#post-password").val();
    var password2 = $("#post-password2").val();
      if(password1 != password2) {
          $("#validate-status").html("<span style='color:red;'>"+i18n['PASSWORDNOMATCH']+"</span>");  
      } else {
           $("#validate-status").html("<span style='color:green;'>"+i18n['PASSWORDMATCH']+"</span>");  
      }
      
  }
  </script>
<?php 
}
include "template/footer.inc.php";
?>
