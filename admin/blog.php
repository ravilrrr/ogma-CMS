<?php 

 /**
 *  ogmaCMS Blogs Admin Page
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


$table = new Query('blog');

extract(Query::getSortOptions());

//
//Delete a Blog
//
if ($action=="deleterecord" ){
  $nonce = $_POST['security-nonce'];
  $record = $_POST['security-record'];
  if (Security::checkNonce($nonce,'deleterecord', 'blog.php')){
    $ret=$table->deleteRecord($record);
    $action='view';
  } else {
    echo "something wrong...";
  }

}


// Update a Blog Entry
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
      <legend><?php echo __("VIEWBLOG"); ?></legend>
       <div class="btn-group" style="padding-bottom:15px;">
        <button class="btn btn-primary" onclick="location.href='blog.php?action=create'"><span class="glyphicon glyphicon-plus"></span> <?php echo __("CREATENEW",array(":type"=>"Blog Post")); ?></button>  
       </div>
      <?php 
      $totalRecords = $table->count();
      $records = $table->order($sort,$dir)->range($page*15,15)->get();

      $table->htmlTableHeader(
          // array of headings
          array(
              __("TITLE")=>"title",
              __("STATUS")=>"status",
              __("DATE")=>"pubdate"
            ),
          // array of options, in this case entries for dropdown
          array(
            'widths'=>'50|20|20',
            "status"=>array(__("PUBLISHED"),__("DRAFT"))
            ), true
        );
      if (count($records)>0){
        foreach ($records  as $record) {
         $table->htmlTableRow($record,array(
              'widths'=>'5|50|20|15',
              "status"=>array(__("PUBLISHED"),__("DRAFT"))
              ), true); 

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
$record = $table->getFullRecord($id);
	?>
	<div class="col-md-12">
    <?php 
    Core::getAlerts();
    
    $ogmaForm = new Form();

    $ogmaForm->startTabHeaders();
    
    if ($action=="edit") $ogmaForm->addHeader(__("EDITBLOG")." : ".$record['title']);
    if ($action=="create") $ogmaForm->addHeader(__("CREATEBLOG"));

    $ogmaForm->createTabHeader(array('main'=>__("MAIN")),true);
    $ogmaForm->createTabHeader(array('options'=>__("OPTIONS")));
    $ogmaForm->createTabHeader(array('meta'=>__("META")));
    $ogmaForm->createExtrasTab('blog', $table->tableFields);

    Actions::executeAction('blog-tab-header');

    $ogmaForm->endTabHeaders();
    
    if ($action=="edit") $ogmaForm->createForm('blog.php?action=update&amp;id='.$record['id']);
    if ($action=="create") $ogmaForm->createForm('blog.php?action=createnew');

    $ogmaForm->startTabs();

    $ogmaForm->createTabPane('main',true);
    $ogmaForm->displayField('post-title',__('PAGETITLE'), 'textlong', array('class'=>' required'),$record['title']);
    $ogmaForm->displayField('post-slug',__('PAGESLUG'), 'slug', '' ,$record['slug']);
    $ogmaForm->displayField('post-content',__('CONTENT'), 'editor', array('rows'=>15),$record['content']);

    $ogmaForm->createTabPane('options',false);

		$ogmaForm->displayField('post-id','ID', 'hidden', '',$record['id']);
		$ogmaForm->displayField('post-pubdate',__('PUBLISHEDDATE'),  'datetimepicker', '',$record['pubdate']);
		$ogmaForm->displayField('post-status',__('PUBLISHED'), 'dropdown', array(__("PUBLISHED"),__("DRAFT")),$record['status']);
		$ogmaForm->displayField('post-author',__('AUTHOR'), 'text', '',$record['author']);
		$ogmaForm->displayField('post-comments',__('COMMENTS'), 'yesno' , '',$record['comments']);
    $ogmaForm->displayField('post-category',__('BLOG_CATEGORIES'), 'text' , '',$record['category']);

    $ogmaForm->createTabPane('meta',false);
    $ogmaForm->displayField('post-metat',__('METATITLE'), 'textlong', '',$record['metat']);
    $ogmaForm->displayField('post-tags',__('TAGS'), 'textlong', '',$record['tags']);
    $ogmaForm->displayField('post-metad',__('METADESC'), 'textarea', array('rows'=>3),$record['metad']);
    $ogmaForm->displayField('post-metak',__('METAKEYWORDS'),  'textlong',  array('tags'=>true) ,$record['metak']);
		
    $ogmaForm->createExtrasPane($record);

    Actions::executeAction('blog-tab-new');

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