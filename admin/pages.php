<?php 

 /**
 *  ogmaCMS Pages Admin 
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

$table = Core::$pages;

extract(Query::getSortOptions());


//
//Delete a Page
//
if ($action=="deleterecord" ){
  $nonce = $_POST['security-nonce'];
  $record = $_POST['security-record'];
  $tableid = $_POST['security-table'];
  if (Security::checkNonce($nonce,'deleterecord', 'pages.php')){
    $ret=$table->deleteRecord($record);
    $action='view';
  } else {
    echo "something wrong...";
  }

}


// Update a Page Entry
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
  if ($_POST['post-slug']=="") $_POST['post-slug']=Security::cleanUrl($_POST['post-title']);
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
  <legend><?php echo __("VIEW",array(":type"=>__("PAGES"))); ?></legend>

   <div style="padding-bottom:15px;">
    <p>
    <button class="btn btn-primary" onclick="location.href='pages.php?action=create'" type="button"><span class="glyphicon glyphicon-plus"></span> <?php echo __("CREATENEW",array(":type"=>__("PAGE"))); ?></button>
    <button class="btn btn-success" onclick="location.href='pages.php?action=edit&amp;id=0'" type="button"><span class="glyphicon glyphicon-edit"></span> <?php echo __("EDIT404"); ?></button>
  </p>
   </div>

  <?php 

      // get all pages except for 404 page
      $table->find("id != 0, parent =  ");

      $totalRecords = $table->count();
      $records = $table->order($sort,$dir)->range($page*PAGINGSIZE,PAGINGSIZE)->get();

      $table->htmlTableHeader(
          // array of headings
          array(
              __("TITLE")=>"title",
              __("STATUS")=>"status",
              __("DATE")=>"pubdate"
            ),
          // array of options, in this case entries for dropdown
          array(
            'widths'=>'50|20|15',
            "status"=>array('Published'=>__("PUBLISHED"),'Draft'=>__("DRAFT"))
            ), true
        );

      $children = $table->reload();
      foreach ($records  as $record) {
       $table->htmlTableRow($record,array(
            'widths'=>'5|50|20|15',
            "status"=>array('Published'=>__("PUBLISHED"),'Draft'=>__("DRAFT"))
            ), true,
            array('Clone'=>'pages.php?action=edit&clone',
                  'Add Child'=>'pages.php?action=create&child='.$record['slug'])
      ); 

       //see if there are any child pages
       
       $children->reload();

       $children->find('slug != 404, parent = '.$record['slug']);
       $childrecords = $children->get();
       if ($children->count()>0){
        foreach ($childrecords  as $childrecord) {
         $table->htmlTableRow($childrecord,array(
              'widths'=>'6|50|20|15',
              "status"=>array('Published'=>__("PUBLISHED"),'Draft'=>__("DRAFT")),
              "indent"=>'title'
              ), true); 
         }
       }
      }

      $table->htmlTableFooter();
      Query::doPagination($page,$totalRecords);

      ?>
  
  </div>
<?php
} 

// EDIT FORM
// =========

if ($action=='edit' || $action=="create"){
  $clone = Core::getOption('clone');
  $child = Core::getOption('child');

  if ($action=="create" && $child){
      $id=null;
  }
  
  $record = $table->getFullRecord($id);
  
  if ($action=="edit" && $clone){
      $action="create";
      $record['id']='';
      $record['slug']='';
  } 

  if ($action=="create" && $child){
      $parent=$child;
  }

  if (isset($record['parent'])){
    $parent = $record['parent'];
  } 
  ?>
  <div class="col-md-12">
  <?php 
    Core::getAlerts();

    $ogmaForm = new Form();

    if ($action=="edit" && !$clone) $ogmaForm->addHeader(__("EDITPAGE")." : ".$record['title']);
    if ($action=="create") $ogmaForm->addHeader(__("CREATEPAGE"));
    if ($action=="edit" && $clone) $ogmaForm->addHeader(__("CLONEPAGE"));
    
    $ogmaForm->startTabHeaders();

    $ogmaForm->createTabHeader(array('main'=>__("MAIN")),true);
    $ogmaForm->createTabHeader(array('options'=>__("OPTIONS")));
    $ogmaForm->createTabHeader(array('meta'=>__("META")));

    $ogmaForm->createExtrasTab('pages', $table->tableFields);
    
    Actions::executeAction('pages-tab-header');

    $ogmaForm->endTabHeaders();
    
    if ($action=="edit") $ogmaForm->createForm('pages.php?action=update&amp;id='.$record['id']);
    if ($action=="create") $ogmaForm->createForm('pages.php?action=createnew');


    $ogmaForm->startTabs();

    $ogmaForm->createTabPane('main',true);
    $ogmaForm->displayField('post-title',__("PAGETITLE"), 'textlong', array('class'=>' required'),$record['title']);
    $ogmaForm->displayField('post-slug',__("PAGESLUG"), 'slug', '',$record['slug']);
    $ogmaForm->displayField('post-content',__("CONTENT"), 'editor', array('rows'=>'15') ,$record['content']);


    $ogmaForm->createTabPane('options',false);
    $ogmaForm->displayField('post-id','ID', 'hidden', '',$record['id']);
   
    $ogmaForm->displayField('post-parent',__("PARENT"), 'pages', array('currentpage'=>$record['slug']),$parent);
    $ogmaForm->displayField('post-template',__("TEMPLATE"),  'templates', '',$record['template']);
    $ogmaForm->displayField('post-private',__("PRIVATE"),  'yesno', 'no' ,$record['private']);
    $ogmaForm->displayField('post-pubdate',__("PUBLISHEDDATE"),  'datetimepicker', '',$record['pubdate']);
    $ogmaForm->displayField('post-status',__("PUBLISHED"), 'dropdown', array('Published'=>__("PUBLISHED"),'Draft'=>__("DRAFT")),$record['status']);
    $ogmaForm->displayField('post-author',__("AUTHOR"),  'textlong', '',$record['author']);


    $ogmaForm->createTabPane('meta',false);
    $ogmaForm->displayField('post-metat',__('METATITLE'),  'textlong', '',$record['metat']);
    $ogmaForm->displayField('post-metad',__("METADESC"),  'textarea', array('rows'=>'3'),$record['metad']);
    $ogmaForm->displayField('post-metak',__("METAKEYWORDS"), 'textlong', array('tags'=>true), $record['metak']);
    $ogmaForm->displayField('post-robots',__("SEARCHENGINEROBOTS"),  $table->tableFields['robots'], array('index, follow','noindex, nofollow'),$record['robots']);

    $ogmaForm->createExtrasPane($record);

    Actions::executeAction('pages-tab-new');

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