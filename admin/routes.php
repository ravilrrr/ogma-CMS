<?php 

 /**
 *  ogmaCMS Routes Admin Page
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

$table = new Query('routes');

extract(Query::getSortOptions());


//
//Delete a record
//
if ($action=="deleterecord" ){
  $nonce = $_POST['security-nonce'];
  $record = $_POST['security-record'];
  $table = $_POST['security-table'];

  if (Security::checkNonce($nonce,'deleterecord', 'routes.php')){
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
	<legend><?php echo __("VIEW",array(":type"=>__("ROUTES"))); ?></legend>
	 <div class="btn-group" style="padding-bottom:15px;">
	 	<button class="btn btn-primary" onclick="location.href='routes.php?action=create'"><span class="glyphicon glyphicon-plus"></span> Create New Route</button>
	 </div>
  <table class="table table-bordered table-striped table-hover">
    <thead>
   
      <tr>
        <th><?php echo __("SLUG"); ?></th>
        <th><?php echo __("ROUTE"); ?></th>
        <th><?php echo __("DESCRIPTION"); ?></th>
        <th style="width:100px;"><?php echo __("OPTIONS"); ?></th>
      </tr>


    </thead>
    <tbody> 
    <?php 
    if (count($records)>0){
 		foreach($records as $record){ 
      
    ?>
      <tr>
        <td><?php echo $record['slug']; ?></td>
        <td><?php echo $record['route']; ?></td>
        <td><?php echo $record['desc']; ?></td>
        <td>
    		<div class="btn-group">
    		 <button class="btn btn-default" onclick="location.href='routes.php?action=edit&amp;id=<?php echo $record['id']; ?>'"><?php echo __("EDIT"); ?></button>
    		  <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    		    <col-md- class="caret"></col-md->
    		  </button>
    		 <ul class="dropdown-menu">
              <li><a href="#" data-nonce="<?php echo Security::getNonce('deleterecord','routes.php'); ?>" data-slug="<?php echo $record['id']; ?>" data-table="routes" class="delButton"><?php echo __("DELETE"); ?></a></li>
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

    if ($action=="edit") $ogmaForm->addHeader( __("EDITROUTE")." : ".$record['route']);
    if ($action=="create") $ogmaForm->addHeader(__("CREATEROUTE"));
   
    $ogmaForm->startTabHeaders();

    $ogmaForm->createTabHeader(array('main'=>'Main'),true);
    
    $versions = Filesystem::hasVersions('routes',$id);
    if (count($versions)>0){
        if ($action=="edit") $ogmaForm->createTabHeader(array('versions'=>__("VERSIONS")));
    }

    Actions::executeAction('routes-tab-header');

    $ogmaForm->endTabHeaders();

    if ($action=="edit") $ogmaForm->createForm('routes.php?action=update&amp;id='.$record['id']);
    if ($action=="create") $ogmaForm->createForm('routes.php?action=createnew');


    $ogmaForm->startTabs();
    $ogmaForm->createTabPane('main',true);
    $ogmaForm->displayField('post-slug','Slug', 'textlong', '',$record['slug']);
    $ogmaForm->displayField('post-route','Route', 'textlong', '',$record['route']);
    $ogmaForm->displayField('post-desc','Description', 'textlong', '',$record['desc']);
    $ogmaForm->displayField('post-page','Page', 'pages', '',$record['page']);
    
    $ogmaForm->displayField('post-id','ID', 'hidden', '',$record['id']);
    
    if (count($versions)>0){
        if ($action=="edit") $ogmaForm->createTabPane('versions',false);
        $ogmaForm->output(Filesystem::showVersions('routes', $id, $versions));
    }

    Actions::executeAction('routes-tab-new');

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