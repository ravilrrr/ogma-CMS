<?php 

 /**
 *  ogmaCMS Snippets Admin Page
 *
 *  @package ogmaCMS
 *  @author Mike Swan / n00dles101
 *  @copyright 2013 Mike Swan / n00dles101
 *  @since 1.0.0
 *
 */

//$adminOnly = true;

include "template/head.inc.php";
include "template/navbar.inc.php";


$action = Core::getAction();            // get URI action
$id = Core::getID();                    // get page ID


$table = new Query('customfields');

extract(Query::getSortOptions());

$allTables = Query::getTables(); 
$tables = Arr::removeValues($allTables,'customfields','menus','routes');

$alert = '';


if ($action=="deleterecord"){
  $nonce = $_POST['security-nonce'];
  $record = $_POST['security-record'];
  $tablename = $_POST['security-table'];

  if (Security::checkNonce($nonce,'deleterecord', 'customfields.php')){
    $records = $table->getFullRecord($record);
    $tablename = $records['table']; 
    $name = $records['name']; 
    $type = $records['type']; 
    $updateTable = New Query($tablename);
    $updateTable->removeFromCache($name);
    $updateTable->deleteField($name);
    $updateTable->deleteFieldFromRecords($tablename,$name);    
    $updateTable->saveSchema();

    $ret=$table->deleteRecord($record);
    if ($ret>0){
      $alert = Form::showAlert('success', __('DELETESUCCESS') ); 
      $action='view';
    } else {
      $alert = Form::showAlert('error', __('UNABLETODELETE') ); 
      $action='view';
    }
  }

}


// Update Entry
if ($action=="update"){
	$blogentry = $table->getFullRecord($id);
	$fieldTypes= $table->tableFields;
  foreach($fieldTypes as  $name => $val){
		if (!isset($_POST['post-'.$name]) && $val=="checkbox") $_POST['post-'.$name]='false';
		if(isset($_POST['post-'.$name])){
			$record[$name]=Utils::manipulateValues($_POST['post-'.$name],$val);
		}
	}
	$ret=$table->saveRecord($record,$id);
  if (isset($_POST['submitclose'])){ 
    $action='view';
  } else {
    $action='edit';
    $_GET['action']='edit';
  }
    $action='view';
}

if ($action=="createnew"){
 $ret = $table->addRecordForm();
    if ($ret){
        $tablename = $_POST['post-table']; 
        $name = $_POST['post-name']; 
        $type = $_POST['post-type']; 
        $updateTable = New Query($tablename);
        $updateTable->addToCache($name);
        $updateTable->addField($name, $type);    
        $updateTable->addFieldToRecords($tablename,$name,$type);
        $updateTable->saveSchema();
        $alert = Form::showAlert('success', __("CREATED",array(":record"=>$id,":type"=>"Customfield")));
    } else {
        $alert = Form::showAlert('error', __("CREATEDFAIL",array(":record"=>$id,":type"=>"Customfield")));
    }
    $action='view';
}



if ($action=='view'){
  $table->getCache();
	?>
	<div class="col-md-12">
  <?php 
  Core::getAlerts();
  ?>
	<legend><?php echo __("VIEW",array(":type"=>__("CUSTOMFIELDS"))); ?></legend>
	 <div class="btn-group" style="padding-bottom:15px;">
	 	<button class="btn btn-primary" onclick="location.href='customfields.php?action=create'"><span class="glyphicon glyphicon-plus"></span> <?php echo __("CREATENEW",array(":type"=>"Customfield")); ?></button>
	 </div>

    <?php 

    $totalRecords = $table->count();
    $records = $table->order($sort,$dir)->range($page*15,15)->get();

    ?>
    <table class="table table-bordered table-striped table-hover">
    <thead>
   
      <tr>
        <th><?php echo __("NAME"); ?></th>
        <th><?php echo __("DESCRIPTION"); ?></th>
        <th><?php echo __("TYPE"); ?></th>
        <th><?php echo __("TABLE"); ?></th>
        <th style="width:100px;"><?php echo __("OPTIONS"); ?></th>
      </tr>


    </thead>
    <tbody> 
    <?php 
    if (count($records)>0){
    foreach($records as $record){ 
      
    ?>
      <tr>
        <td><?php echo $record['name']; ?></td>
        <td><?php echo $record['desc']; ?></td>
        <td><?php echo $record['type']; ?></td>
        <td><?php echo $record['table']; ?></td>
        <td>

            <div class="btn-group">
            <button class="btn btn-default" onclick="location.href='customfields.php?action=edit&amp;id=<?php echo $record['id']; ?>'"><?php echo __("EDIT"); ?></button>
            <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
              </button>
             <ul class="dropdown-menu">
              <li><a href="#" data-nonce="<?php echo Security::getNonce('deleterecord',Core::getFilenameId().'.php'); ?>" data-slug="<?php echo $record['id']; ?>"  data-table="customfields" class="delButton"><?php echo __("DELETE"); ?></a></li>
            </ul>
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

    $ogmaForm = new Form();

    if ($action=="edit") $ogmaForm->addHeader(__("EDITCUSTOMFIELD")." : ".$record['name']);
    if ($action=="create") $ogmaForm->addHeader(__("ADDCUSTOMFIELD"));

    $ogmaForm->startTabHeaders();

    $ogmaForm->createTabHeader(array('main'=>'Main'),true);
    
    Actions::executeAction('customfields-tab-header');

    $ogmaForm->endTabHeaders();

    if ($action=="edit") $ogmaForm->createForm('customfields.php?action=update&amp;id='.$record['id']);
    if ($action=="create") $ogmaForm->createForm('customfields.php?action=createnew');

    $ogmaForm->startTabs();
    $ogmaForm->createTabPane('main',true);

    if ($action=="edit"){
        $ogmaForm->displayField('post-name',__("NAME"),  'disabled' , '',$record['name']);
        $ogmaForm->displayField('post-table',__("TABLE"),   'disabled',  $tables, $record['table']);
        $ogmaForm->displayField('post-type',__("TYPE"), 'disabled' , '',$record['type']);
        $ogmaForm->displayField('post-cacheit',__("ADDTOCACHE"), 'disabled' , '',$record['cacheit']);
           
    }
    if ($action=="create"){
        $ogmaForm->displayField('post-name',__("NAME"),  'textlong', '',$record['name']);
        $ogmaForm->displayField('post-table',__("TABLE"),   'dropdown' ,  $tables, $record['table']);
        $ogmaForm->displayField('post-type',__("TYPE"), 'dropdown', Form::getFields(),$record['type']);
        $ogmaForm->displayField('post-cacheit',__("ADDTOCACHE"), 'yesno' , '',$record['cacheit']);
    }
    $ogmaForm->displayField('post-desc',__("DESCRIPTION"), 'textlong', '',$record['desc']);
    $ogmaForm->displayField('post-options',__("OPTIONS"),  'textarea', array('rows'=>'3'),$record['options']);
    
    $ogmaForm->displayField('post-id','ID', 'hidden', '',$record['id']);
    
    Actions::executeAction('customfields-tab-new');

    $ogmaForm->endTabs();
    
    if ($action=="edit") $ogmaForm->formButtons(true);
    if ($action=="create") $ogmaForm->formButtons(false);
    
    $ogmaForm->endForm();

    $ogmaForm->show();

    ?>
	</div>
<?php
} 

include "template/footer.inc.php";
?>