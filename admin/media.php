<?php 

 /**
 *  ogmaCMS Media Admin Page
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

$table = new Query('media');

extract(Query::getSortOptions());

$alert = '';



if ($action=="deleterecord" ){
  $nonce = $_POST['security-nonce'];
  $record = $_POST['security-record'];

  if (Security::checkNonce($nonce,'deleterecord', 'media.php')){
    $ret=$table->deleteRecord($record);
    $action='view';
  } else {
    echo "something wrong...";
  }

}


// Update a record
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
  if (isset($_POST['submitclose'])){ 
    $action='view';
  } else {
    $action='edit';
    $_GET['action']='edit';
  }
}

$alert = Core::$errorMsg;

if ($action=='view'){
  $table->getCache();
  ?>
  <div class="col-md-12">
  <?php 
  Core::getAlerts();
  ?>
  <legend><?php echo __("VIEWMEDIA"); ?></legend>
   <div class="btn-group" style="padding-bottom:15px;">
    <button class="btn btn-primary" onclick="location.href='media.php?action=create'"><span class="glyphicon glyphicon-plus"></span> <?php echo __("CREATENEW",array(":type"=>"Media")); ?></button>
   </div>
   <?php 
    
    $tags =  $table->unique('tag')->get();
    echo "<div class='right' style='float:right;'>";
    echo "Filter: <select id='filter' >";
    echo "<option value=''>".__("ALL")."</option>";
    foreach ($tags as $tag){
      echo "<option value='$tag' ";
      if (isset($_GET['filter']) && $_GET['filter']==$tag) echo " selected ";
      echo ">".$tag."</option>";
    }
    echo "</select>";
    echo "</div>";
   ?>
    <?php 

      $table->reload();
      if (isset($_GET['filter'])){
        $table->find('tag = '.$_GET['filter']);
      }
      $totalRecords = $table->count();

      $records = $table->order($sort,$dir)->range($page*15,15)->get();

      $table->htmlTable(
        // array of headings
        array(
            __("ID")=>"id",
            __("IMAGE")=>"fileurl",
            __("TITLE")=>"title",
            __("TAG")=>"tag",
            __("ORDER")=>"showorder"
          ),
        array('widths'=>'5|25|25|25|10'), true
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

        $ogmaForm = new Form();

        $ogmaForm->startTabHeaders();

        if ($action=="edit") $ogmaForm->addHeader(__("EDITMEDIA")." : ".$record['title']);
        if ($action=="create") $ogmaForm->addHeader( __("CREATENEW",array(":type"=>"Media")) );


        $ogmaForm->createTabHeader(array('main'=>__("MAIN")),true);

        $versions = Filesystem::hasVersions('media',$id);
        if (count($versions)>0){
            if ($action=="edit") $ogmaForm->createTabHeader(array('versions'=>__("VERSIONS")));
        }

        $ogmaForm->createExtrasTab('media', $table->tableFields);

        Actions::executeAction('media-tab-header');

        $ogmaForm->endTabHeaders();
        
        if ($action=="edit") $ogmaForm->createForm('media.php?action=update&amp;id='.$record['id']);
        if ($action=="create") $ogmaForm->createForm('media.php?action=createnew');


        $ogmaForm->startTabs();

        $ogmaForm->createTabPane('main',true);

        $ogmaForm->displayField('post-fileurl',__("FILEURL"),  'image', '',$record['fileurl']);
        $ogmaForm->displayField('post-showorder',__("ORDER"),  'spinner', '',$record['showorder']);
        $ogmaForm->displayField('post-tag',__("TAG"),  'text' , '',$record['tag']);
        $ogmaForm->displayField('post-title',__("TITLE"), 'text', '',$record['title']);
        $ogmaForm->displayField('post-alt',__("ALT"),  'text', '',$record['alt']);
        $ogmaForm->displayField('post-caption',__("CAPTION"), 'textarea', array('rows'=>3),$record['caption']);
        $ogmaForm->displayField('post-description',__("DESCRIPTION"),  'textarea',  array('rows'=>3),$record['description']);
        
        $ogmaForm->createExtrasPane($record);

        if (count($versions)>0){
            if ($action=="edit") $ogmaForm->createTabPane('versions',false);
            $ogmaForm->output(Filesystem::showVersions('media', $id, $versions));
        }

        Actions::executeAction('media-tab-new');

        $ogmaForm->endTabs();
        
        if ($action=="edit") $ogmaForm->formButtons(true);
        if ($action=="create") $ogmaForm->formButtons(false);

        $ogmaForm->endForm();

        $ogmaForm->show();

    ?>

<?php

// end add new page code
}
include "template/footer.inc.php";
?>