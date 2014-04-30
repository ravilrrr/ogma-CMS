<?php 

 /**
 *	ogmaCMS Theme Hooks Page
 *
 *	@package ogmaCMS
 *	@author Mike Swan / n00dles101
 *	@copyright 2013 Mike Swan / n00dles101
 *	@since 1.0.0
 *
 */

 	
	include "template/head.inc.php";
	include "template/navbar.inc.php";

$action = Core::getAction();            // get URI action
$id = Core::getID();                    // get page ID

$table = new Query('themehooks');

extract(Query::getSortOptions());
$theme = Core::$site['template'];
Theme::addThemeActions();
//
//Delete a record
//
if ($action=="deleterecord" ){
  $nonce = $_POST['security-nonce'];
  $record = $_POST['security-record'];
  $tableid = $_POST['security-table'];

  if (Security::checkNonce($nonce,'deleterecord', 'themehooks.php')){
    $ret=$table->deleteRecord($record);
    $action='view';
  } else {
    echo "something wrong...";
  }

}


//
// Update an Entry
//
if ($action=="update"){
  $record = $table->getFullRecord($id);
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
}


if ($action=="createnew"){
  $ret = $table->addRecordForm();
  $action='view';
  $_GET['action']='view';
}



if ($action=='view'){
  $table->getCache();
  $totalRecords = $table->count();
  $records = $table->order($sort,$dir)->range($page*15,15)->get();
	?>
	<div class="col-md-12">
  <?php 
  Core::getAlerts();
  ?>
	<legend><?php echo __("VIEW",array(":type"=>__("THEMEHOOKS"))); ?></legend>
	 <div class="btn-group" style="padding-bottom:15px;">
	 	<button class="btn btn-primary" onclick="location.href='themehooks.php?action=create'"><span class="glyphicon glyphicon-plus"></span> <?php echo __("THEMEADDHOOK"); ?></button>
	 </div>
  <table class="table table-bordered table-striped table-hover">
    <thead>
   
      <tr>
        <th><?php echo __("HOOK"); ?></th>
        <th><?php echo __("ACTION"); ?></th>
        <th><?php echo __("ORDER"); ?></th>
        <th style="width:100px;"><?php echo __("OPTIONS"); ?></th>
      </tr>


    </thead>
    <tbody> 
    <?php 
    if (count($records)>0){

    	$theme = Core::$site['template'];
 		foreach($records as $record){ 
      
    ?>
      <tr>
        <td><?php echo Theme::$themeInfo[$theme]['hooks'][(string)$record['hook']]; ?></td>
        <td><?php echo $record['action']; ?></td>
        <td><?php echo $record['order']; ?></td>
        <td>
    		<div class="btn-group">
    		 <button class="btn btn-default" onclick="location.href='themehooks.php?action=edit&amp;id=<?php echo $record['id']; ?>'"><?php echo __("EDIT"); ?></button>
    		  <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    		    <col-md- class="caret"></col-md->
    		  </button>
    		 <ul class="dropdown-menu">
              <li><a href="#" data-nonce="<?php echo Security::getNonce('deleterecord','themehooks.php'); ?>" data-slug="<?php echo $record['id']; ?>" data-table="routes" class="delButton"><?php echo __("DELETE"); ?></a></li>
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

    if ($action=="edit") $ogmaForm->addHeader( __("EDITHOOK")." : ".$record['id']);
    if ($action=="create") $ogmaForm->addHeader(__("CREATEHOOK"));
   
    $ogmaForm->startTabHeaders();

    $ogmaForm->createTabHeader(array('main'=>'Main'),true);
    
    Actions::executeAction('themehook-tab-header');

    $ogmaForm->endTabHeaders();

    if ($action=="edit") $ogmaForm->createForm('themehooks.php?action=update&amp;id='.$record['id']);
    if ($action=="create") $ogmaForm->createForm('themehooks.php?action=createnew');

    $ogmaForm->startTabs();
    $ogmaForm->createTabPane('main',true);
    $ogmaForm->displayField('post-hook','Hook', 'dropdown', Theme::getThemeHooks(),$record['hook']);
    $ogmaForm->displayField('post-action','Action', 'dropdown', Theme::getHookFunctions(),$record['action']);
    $ogmaForm->displayField('post-order','Order', 'spinner', '',$record['order']);
    $ogmaForm->displayField('post-type','Type', 'hidden', '',$record['type']);
    $ogmaForm->displayField('post-id','ID', 'hidden', '',$record['id']);
    
    Actions::executeAction('themehook-tab-new');

    $ogmaForm->endTabs();
    
    if ($action=="edit") $ogmaForm->formButtons(true);
    if ($action=="create") $ogmaForm->formButtons(false);

    
    $ogmaForm->endForm();

    $ogmaForm->show();

    ?>

<?php
} 

include "template/footer.inc.php";
?>