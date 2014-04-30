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

include "template/head.inc.php";
include "template/navbar.inc.php";

 
$action = Core::getAction();            // get URI action
$id = Core::getID();                    // get page ID

$table = new Query('snippets');

extract(Query::getSortOptions());


if ($action=="deleterecord" ){
  $nonce = $_POST['security-nonce'];
  $record = $_POST['security-record'];
  $tbl = $_POST['security-table'];

  if (Security::checkNonce($nonce,'deleterecord', 'snippets.php')){
    $ret=$table->deleteRecord($record);
    $action='view';
  } else {
    echo "something wrong...";
  }

}


// Update an Entry
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

$alert = Core::$errorMsg;

if ($action=='view'){
	$table->getCache();
	?>
	<div class="col-md-12">
  <?php 
  Core::getAlerts();
  ?>
	<legend><?php echo __("VIEWSNIPPET"); ?></legend>
	 <div class="btn-group" style="padding-bottom:15px;">
	 	<button class="btn btn-primary" onclick="location.href='snippets.php?action=create'"><span class="glyphicon glyphicon-plus"></span> <?php echo __("CREATENEW",array(":type"=>"Snippet")); ?></button>
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



if ($action=='edit' || $action=="create"){
$record = $table->getFullRecord($id);

	?>
	<div class="col-md-12">
    <?php 
    Core::getAlerts();

    $ogmaForm = new Form();

    if ($action=="edit") $ogmaForm->addHeader( __("EDITSNIPPET")." : ".$record['slug']);
    if ($action=="create") $ogmaForm->addHeader(__("CREATESNIPPET"));
    
    $ogmaForm->startTabHeaders();

    $ogmaForm->createTabHeader(array('main'=>__("MAIN")),true);
    $ogmaForm->createExtrasTab('snippets', $table->tableFields);

    Actions::executeAction('snippets-tab-header');

    $ogmaForm->endTabHeaders();
    
    if ($action=="edit") $ogmaForm->createForm('snippets.php?action=update&amp;id='.$record['id'],"snippets");
    if ($action=="create") $ogmaForm->createForm('snippets.php?action=createnew',"snippets");
    
    $ogmaForm->startTabs();
    $ogmaForm->createTabPane('main',true);
    $ogmaForm->displayField('post-slug',__("NAME"), 'slug', array('class'=>' required'),$record['slug']);
    $ogmaForm->displayField('post-desc',__("DESCRIPTION"), 'textlong', '',$record['desc']);
    $ogmaForm->displayField('post-active',__("ACTIVE"), 'yesno', '',$record['active']);
    $ogmaForm->displayField('post-content',__("CONTENT"), 'editor', '15',$record['content']);
    
    $ogmaForm->createExtrasPane($record);

    Actions::executeAction('snippets-tab-new');

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