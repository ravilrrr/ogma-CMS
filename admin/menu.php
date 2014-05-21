<?php 

 /**
 *  ogmaCMS Menu Admin Page
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

$table = new Query('menus');
$table->getCache();

extract(Query::getSortOptions());

$alert = '';

if ($action=="deleterecord" ){
  $nonce = $_POST['security-nonce'];
  $record = $_POST['security-record'];
  $tableid = $_POST['security-table'];

  if (Security::checkNonce($nonce,'deleterecord', 'menu.php')){
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


if ($action=="updatemenu"){
  $menufile = Xml::xml2array(Core::$settings['rootpath'].'data/menus/'.$id.'.xml'); 
  $menuname = Core::$settings['rootpath'].'data/menus/'.$menufile['menuname'].".menu";
  $data = json_decode($_POST['menu-data'],true);
  $xml=Xml::arrayToXml( $data );
  $ret =  file_put_contents($menuname, $xml);
  $action="view";
}


// Update a Menu
// 
if ($action=="update"){
	$blogentry = $tableRecords->getRecord($id);
	$fieldTypes= $tableRecords->tableFields;
	foreach($fieldTypes as  $name => $val){
		if (!isset($_POST['post-'.$name]) && $val=="checkbox") $_POST['post-'.$name]='false';

		
		if(isset($_POST['post-'.$name])){
			//if ($val=="date") $_POST['post-'.$name]=strtotime($_POST['post-'.$name]);
			$blogentry[$name]=Utils::manipulateValues($_POST['post-'.$name],$val);
		}
	}
	$ret=$tableRecords->saveRecord($blogentry,$id);
	 if ($ret){
    $alert = Form::showAlert('success', __("UPDATED",array(":record"=>$id,":type"=>"Menu")));
  } else {
    $alert = Form::showAlert('error', __("UPDATEDFAIL",array(":record"=>$id,":type"=>"Menu")));
  }
	$action='view';
}

if ($action=="createnew"){
  $ret = $table->addRecordForm();
  $action='view';
  $_GET['action']='view';
}



if ($action=='view'){
	//$blogentry = $tableRecords->query('select * from menus');
   	?>
	<div class="col-md-12">
	<?php 
	if ($alert!=''){
		echo $alert;
	}
	?>
	<legend>View Menus</legend>
	 <div class="btn-group" style="padding-bottom:15px;">
	 	<button class="btn btn-primary" onclick="location.href='menu.php?action=create'"><span class="glyphicon glyphicon-plus"></span> <?php echo __("CREATENEW",array(":type"=>"Menu")); ?></button>
	 </div>
  <?php 

      $totalRecords = $table->count();
      $records = $table->order($sort,$dir)->range($page*15,15)->get();

      $table->htmlTable(
          // array of headings
          array(
              __("ID")=>"id",
              __("NAME")=>"menuname"
            ),
          // array of options, in this case entries for dropdown
           array(
            'widths'=>'5|85'
            ), true
        );

      ?>
	
	</div>
<?php
} 

if ($action=='edit'){
$record = $table->getFullRecord($id);
$pages=new Query('pages');
$allPages = $pages->getCache()->get();
$blogpages=new Query('blog');
$allBlogs = $blogpages->getCache()->get();
?>
<div class="col-md-12">
	<legend>Edit Menu : <?php echo $record['menuname']; ?></legend>
</div>
  
    <div class="col-md-4">    
    <form class="well well-sm">
        <fieldset>

          <div class="form-group">
            <label class="control-label" for="multiSelect">Type</label>
            <div class="controls">
              <select id="menutypeselect" class="form-control">
                <option value="1" >Page</option>
                <option value="2" >Blog</option>
                <option value="3" >URL</option>
              </select>
              
              <div id="menutype1" class="menutype" >
                <label class="control-label" for="multiSelect">Pages</label>
                <select  id="menuPage"  class="form-control">
                  <option value=''>- none - </option>
                  <?php 
                  foreach ($allPages as $page){
                    $route = isset($page['route']) && $page['route']!='' ? $page['route'].'/' : '';
                    echo '<option value="'.$route.$page['slug'].'">'.$page['title'].'</option>';
                  }
                  ?>
                </select>
              </div>

              <div id="menutype2" class="menutype hidden" >
                <label class="control-label" for="multiSelect">Blogs</label>
                <select  id="menuBlog"  class="form-control">
                  <option value=''>- none - </option>
                  <?php 
                  foreach ($allBlogs as $page){
                    echo '<option value="'.$page['slug'].'">'.$page['title'].'</option>';
                  }
                  ?>
                </select>
              </div>

              <div id="menutype3" class="menutype hidden" >
                <label class="control-label" for="multiSelect">URL</label>
                <div class="controls">
                  <input type="text" id="menuUrl"  class="form-control"/>
                </div> 
              </div>

              <label class="control-label" for="multiSelect">Label</label>
              <div class="controls">
                <input type="text" id="menulabel"  class="form-control"/>
              </div>
            </div>
          </div>
          
            <button id="addtomenu" type="button" class="btn btn-primary btn-block">Add to Menu..</button>
         
        </fieldset> 

      </form>
     
    </div>
    <div class="col-md-8">
    <form class="form-horizontal well" method="post" action='menu.php?action=updatemenu&amp;id=<?php echo $record['id']; ?>' >
    
    <div class="tabbable" style="min-height:400px;">
     

    <div class="dd" id="nestable">
               <?php 
                $menufile = Xml::xml2array(Core::$settings['rootpath'].'data/menus/'.$id.'.xml'); 
                $menu = new Menu($menufile['menuname']);
                $menu->displayMenu();
                ?>
        </div>


    </div> 
    <textarea id="nestable-output" name="menu-data"></textarea>
    <div class="form-actions">
            <button type="submit" id="savemenu" class="btn btn-primary">Save changes</button>
            <button type="reset" class="btn" onclick="location.href='menu.php?action=view'">Cancel</button>
          </div>
	</form>

  <div id="menu-edit" class="menu-item-settings" style="display:none;">
   <form class="form-horizontal">
        <div class="control-group">
          <label class="control-label" for="menutext">Menu Text</label>
          <div class="controls">
            <input type="text" value="" id="menutext" class="input-full">
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="menuattr">Title Attribute</label>
          <div class="controls">
            <input type="text" value="" id="menuattr"  class="input-full">
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="menuurl">URL</label>
          <div class="controls">
            <input type="text" value=""  id="menuurl" class="input-full">
          </div>
        </div>
        <div class="form-actions">
        <button type="button" id="menusavesettings" class="btn btn-primary">Save</button>
        <button type="button" id="cancelsavesettings" class="btn btn-warning">Cancel</button>
      </div>
        </form>
      </div>
   </form>
  </div>

</div>
<?php
} 

if ($action=='create'){

$record = $table->getFullRecord($id);
?>

<div class="col-md-12">
    
    <?php 

        $ogmaForm = new Form();
        $ogmaForm->addHeader(__("CREATEMENU"));
        $ogmaForm->startTabHeaders();

        $ogmaForm->createTabHeader(array('main'=>__("MAIN")),true);
        $versions = Filesystem::hasVersions('menu',$id);
        if (count($versions)>0){
            if ($action=="edit") $ogmaForm->createTabHeader(array('versions'=>__("VERSIONS")));
        }
        
        Actions::executeAction('menu-tab-header');

        $ogmaForm->endTabHeaders();
        
        $ogmaForm->createForm('menu.php?action=createnew');

        $ogmaForm->startTabs();

        $ogmaForm->createTabPane('main',true);

        $ogmaForm->displayField('post-menuname',__("NAME"),  'textlong', '','');

        if (count($versions)>0){
            if ($action=="edit") $ogmaForm->createTabPane('versions',false);
            $ogmaForm->output(Filesystem::showVersions('menu', $id, $versions));
        }

        Actions::executeAction('menu-tab-new');

        $ogmaForm->endTabs();
        
        if ($action=="edit") $ogmaForm->formButtons(true);
        if ($action=="create") $ogmaForm->formButtons(false);

        $ogmaForm->endForm();

        $ogmaForm->show();

        ?>
  </div>  
</div>
<?php 
// end add new page code
}
include "template/footer.inc.php";
?>
