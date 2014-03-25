<?php 

 /**
 *  ogmaCMS Components Admin Page
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

$table = new Query('components');

extract(Query::getSortOptions());


//
//Delete a Component
//
if ($action=="deleterecord" ){
  $nonce = $_POST['security-nonce'];
  $record = $_POST['security-record'];
  $tbl = $_POST['security-table'];

  if (Security::checkNonce($nonce,'deleterecord', 'components.php')){
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
  if ($_POST['post-slug']=="") $_POST['post-slug']=Security::cleanUrl($_POST['post-title']);
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

$alert = Core::$errorMsg;



if ($action=='view'){
  $table->getCache();
	?>
	<div class="col-md-12">
	<?php 
  Core::getAlerts();
  ?>
	<legend><?php echo __("VIEWCOMPONENT"); ?></legend>
	 <div class="btn-group" style="padding-bottom:15px;">
	 	<button class="btn btn-primary" onclick="location.href='components.php?action=create'"><span class="glyphicon glyphicon-plus"></span> <?php echo __("CREATENEW",array(":type"=>"Component")); ?></button>
	 </div>
  <?php 

      $totalRecords = $table->count();
      $records = $table->order($sort,$dir)->range($page*15,15)->get();

      $table->htmlTable(
          // array of headings
          array(
              __("ID")=>"id",
              __("SLUG")=>"slug",
              __("DESCRIPTION")=>"desc",              
              __("ACTIVE")=>"active"
            ),
          // array of options, in this case entries for dropdown
           array(
            'widths'=>'5|20|50|15'
            ), true
        );

      Query::doPagination($page,$totalRecords);

      ?>
	
	</div>
<?php
}  


// EDIT FORM
// =========


if ($action=='edit' || $action=="create"){
$record = $table->getFullRecord($id);

	?>
	<div class="col-md-12">
		 <?php 
    Core::getAlerts();
    
    $ogmaForm = new Form();
    if ($action=="edit") $ogmaForm->addHeader(__("EDITCOMPONENT")." : ".$record['slug']);
    if ($action=="create") $ogmaForm->addHeader(__("CREATECOMPONENT"));
    $ogmaForm->startTabHeaders();

    $ogmaForm->createTabHeader(array('main'=>__("MAIN")),true);
    $ogmaForm->createExtrasTab('components', $table->tableFields);

    Actions::executeAction('components-tab-header');

    $ogmaForm->endTabHeaders();

    if ($action=="edit") $ogmaForm->createForm('components.php?action=update&amp;id='.$record['id']);
    if ($action=="create") $ogmaForm->createForm('components.php?action=createnew');

    $ogmaForm->startTabs();
    $ogmaForm->createTabPane('main',true);
    $ogmaForm->displayField('post-slug',__("NAME"), 'slug', array("class"=>"required"),$record['slug']);
    $ogmaForm->displayField('post-desc',__("DESCRIPTION"), 'text', '',$record['desc']);
    $ogmaForm->displayField('post-active',__("ACTIVE"), 'yesno', '',$record['active']);
    $ogmaForm->displayField('post-content',__("CONTENT"),  'editor', array("rows"=>15,"codeedit"=>'true'),$record['content']);
    
    $ogmaForm->createExtrasPane($record);

    Actions::executeAction('components-tab-new');

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