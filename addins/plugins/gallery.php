<?php 

// Gallery plugin for OGMA CMS

Plugins::registerPlugin( 
				'gallery',
				'Gallery',
				'Gallery Plugin for OGMA CMS',
				'0.0.1',
				'Mike Swan',
				'http://www.digimute.com/'
				);


class Gallery{

	public function __construct() {
 
	}

	public static function init(){
		// check if the table exists 
		if (!Query::tableExists('gallery')){
			// create it
			Query::createTable('gallery', 
					array(
									'name'=>'text', 
									'src'=>'text',
									'thumbsrc'=>'text',
									'active'=>'yesno'
							), 
					array(
									'cache'=>'id|name|src|thumbsrc|active'
							)
					);

		}
		if (Query::tableExists('gallery')){
				Actions::addAction('admin-add-sidebar','Menu::addSidebarMenu',1,array("Galleries",'','gallery','fa fa-fw fa-picture-o'));
				Actions::addAction('admin-add-to-dashboard','Menu::addDashboardItem',1,array("Galleries",'','gallery','fa fa-fw fa-picture-o'));
		}
		$language = Core::$site['language'];
		Lang::mergeLanguage(Core::$settings['pluginpath'].'gallery'.DS.'lang'.DS.$language.'.lang.php');
		Scripts::add("/addins/plugins/gallery/js/gallery.js","backend",10);
		Stylesheet::add("/addins/plugins/gallery/css/style.css","backend",10);
	}

	public static function initShortcodes(){
		// initialize shortcodes
	}

	public static function initFrontend(){
		// Frontend stuff
	}

	public static function admin(){
		$action = Core::getAction();            // get URI action
		$id = Core::getID();                    // get page ID

		$table = new Query('gallery');
		$table->getCache();
		extract(Query::getSortOptions());

		if ($action=="deleterecord" ){
			$nonce = $_POST['security-nonce'];
			$record = $_POST['security-record'];
			$tableid = $_POST['security-table'];
			if (Security::checkNonce($nonce,'deleterecord', 'gallery')){
				$ret=$table->deleteRecord($record);
				$action='view';
			} else {
				echo "something wrong...";
			}

		}

		if ($action=="createnew"){
			$ret = $table->addRecordForm();
			$action='view';
			$_GET['action']='view';
			Gallery::initializeGallery($_POST['post-name'],$_POST['post-src']);
			
		}


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

	 	if ($action=='view'){
				//$blogentry = $tableRecords->query('select * from routes order by route ASC');

			$totalRecords = $table->count();
			$records = $table->order($sort,$dir)->range($page*15,15)->get();
				?>
				<div class="col-md-12">
			<?php 
			Core::getAlerts();
			?>
				<legend><?php echo __("VIEW",array(":type"=>__("GALLERY_GALLERY"))); ?></legend>
				 <div class="btn-group" style="padding-bottom:15px;">
						<button class="btn btn-primary" onclick="location.href='load.php?tbl=gallery&action=create'"><span class="glyphicon glyphicon-plus"></span> <?php echo __("GALLERY_CREATE"); ?></button>
				 </div>
				 <table class="table table-bordered table-striped table-hover">
				    <thead>
				   
				      <tr>
				        <th><?php echo __("GALLERY_NAME"); ?></th>
				        <th><?php echo __("GALLERY_SRC"); ?></th>
				    
				        <th style="width:100px;"><?php echo __("OPTIONS"); ?></th>
				      </tr>


				    </thead>
					<?php 
					$totalRecords = $table->count();
					$records = $table->order($sort,$dir)->range($page*15,15)->get();

					if (count($records)>0){
						foreach ($records  as $record) {
					?>

					 <tr>
			        <td><?php echo $record['name']; ?></td>
			        <td><?php echo $record['src']; ?></td>
			       
			        <td>
					<div class="btn-group">
					 <button class="btn btn-default" onclick="location.href='load.php?tbl=gallery&action=edit&amp;id=<?php echo $record['id']; ?>'"><?php echo __("EDIT"); ?></button>
					  <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
					    <col-md- class="caret"></col-md->
					  </button>
					 <?php if (User::isAdmin()){ ?><ul class="dropdown-menu">
					 
			          <li><a href="#" data-nonce="<?php echo Security::getNonce('deleterecord','gallery'); ?>" data-slug="<?php echo $record['id']; ?>" data-href="delme.php" data-table="users" class="delButton" ><?php echo __("DELETE"); ?></a></li>
			        
			        </ul>
			        <?php } ?>
					</div>
			        </td>

					<?php

						}
					}

				?>
				
		    </tbody>
		  </table>
		</div>
		<?php
		}

		if ($action=='edit' || $action=="create"){
			$record = $table->getFullRecord($id);
				?>
				<div class="col-md-12">

				 <?php 

				$ogmaForm = new Form();

				if ($action=="edit") $ogmaForm->addHeader( __("TESTIMONIALS_EDIT")." : ".$record['id']);
				if ($action=="create") $ogmaForm->addHeader(__("TESTIMONIALS_CREATE"));
			 
				$ogmaForm->startTabHeaders();

				$ogmaForm->createTabHeader(array('main'=>'Main'),true);
				if (Gallery::hasImages($record['name']) && $action=="edit"){
					$ogmaForm->createTabHeader(array('images'=>__("GALLERY_IMAGES")),false);
					
				}
				Actions::executeAction('gallery-tab-header');

				$ogmaForm->endTabHeaders();

				if ($action=="edit") $ogmaForm->createForm('load.php?tbl=gallery&action=update&amp;id='.$record['id']);
				if ($action=="create") $ogmaForm->createForm('load.php?tbl=gallery&action=createnew');

				$ogmaForm->startTabs();
				$ogmaForm->createTabPane('main',true);
				$ogmaForm->displayField('post-name', __("GALLERY_NAME") , 'text', '',$record['name']);
				$ogmaForm->displayField('post-src', __("GALLERY_SRC") , 'text' , '',$record['src']);
				$ogmaForm->displayField('post-thumbsrc',__("GALLERY_THUMBSRC"), 'text', '',$record['thumbsrc']);
				$ogmaForm->displayField('post-active',__("ACTIVE"),  'yesno', '',$record['active']);
				$ogmaForm->displayField('post-id','ID', 'hidden', '',$record['id']);
				
				if (Gallery::hasImages($record['name']) && $action=="edit"){
					$ogmaForm->createTabPane('images',false);
					$ogmaForm->addHeader(__("GALLERY_IMAGES"));
					$ogmaForm->output(Gallery::showImagesAdmin($record['name'], $record['src']));
				}

				Actions::executeAction('gallery-tab-new');

				$ogmaForm->endTabs();
				
				$ogmaForm->formButtons(true);
				$ogmaForm->endForm();

				$ogmaForm->show();
				
				?>
				</div>   
				<div id="galleryEditImageModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="galleryEditImageModal" aria-hidden="true">
				  <div class="modal-dialog">
				  <div class="modal-content">
				  <div class="modal-header">
				    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
				    <h3 id="myModalLabel"><?php echo __("GALLERY_EDIT"); ?></h3>
				  </div>
				  <div class="modal-body">
				  <div class="form-group">
				    <label for="imageTitle"><?php echo __("GALLERY_IMAGE_TITLE"); ?></label>
				    <input name="imageTitle" id="imageTitle" type="text" value="" placeholder="<?php echo __("GALLERY_IMAGE_TITLE"); ?>" class="form-control" ?>
				  </div>
				   <div class="form-group">
				    <label for="imageAlt"><?php echo __("GALLERY_IMAGE_ALT"); ?></label>
				    <input name="imageAlt" id="imageAlt" type="text" value=""  placeholder="<?php echo __("GALLERY_IMAGE_ALT"); ?>" class="form-control" ?>
				  </div>
				   	<input  name="imageID" id="imageID" type="hidden" value=""  class="form-control" ?>
				  </div>
				  <div class="modal-footer">
				    <!--<form action="javascript:void();" method="post" > -->		   
				    <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo __("CLOSE"); ?></button>
				    <button class="btn btn-success" id="updateImageDetails" name="updateImageDetails" data-url="" ><?php echo __("SAVE"); ?></button>
				  <!-- </form> -->
				  </div>
				  </div>
				  </div>
			<?php

			} 
		}

		public static function hasImages($name){
			return file_exists(Core::$settings['rootpath'].'addins/plugins/gallery/data/'.$name.".gallery");
		}


		public static function showImagesAdmin($name,$folder){
	        $gallery = array();
	        $file=Core::$settings['rootpath'].'addins/plugins/gallery/data/'.$name.".gallery";
	          if (file_exists($file)){
	          // load the xml file and setup the array. 
	                $thisfile = file_get_contents($file);
	            } else {
	                $thisfile = '<?xml version="1.0" encoding="utf-8"?><root></root>';
	          }

	            $data = simplexml_load_string($thisfile);
	            $components = @$data->item;
	            if (count($components) != 0) {
	            	$i = 0;
	                foreach ($components as $component) {
	                $name=(string)$component->name;
	                   $gallery[(string)$component->order] = array(
	                        'id' => $i,
	                        'src' => (string)$component->src, 
	                        'thumbsrc' => (string)$component->thumbsrc,
	                        'title' => (string)$component->title,
	                        'alt' => (string)$component->alt,
	                        'active' => (string)$component->active,
	                        'order' => (string)$component->order
	                        );
	                  $i++;
	                }
	            }

	            $gal = '<div class="row"><ul class="sortable grid">';
				
	            foreach ($gallery as $image) {
				    //$gal .= '<li class="span3">';
				    $gal .= '<li class="col-xs-6 col-md-3 gal-edit" data-active="'.$image['active'].'" ><a href="'.$image['src'].'" class="thumbnail" data-toggle="lightbox"><img class="galimage" id="image'.$image['id'].'" src="'.$image['src'].'" data-title="'.$image['title'].'" data-alt="'.$image['alt'].'" >';
				    $gal .= '<div class="caption" style="display:none;"><p>Thumbnail label</p></div>';
				    $gal .= '</a></li>';
	            }
	            $gal .= '<ul></div>';

	            return $gal;
		}

		public static function initializeGallery($name,$folder){
			$sdir=Core::getRootPath().$folder;
			  
			$pattern="(\.jpg$)|(\.png$)|(\.jpeg$)|(\.gif$)"; //valid image extensions
			$i = 0;
			foreach(scandir($sdir) as $file){
				if($file != '.' && $file != '..'){
					$gallery[$i] = array(
						'src' => $folder.'/'.$file,
						'thumbsrc' => $file, 
						'title' => '',
						'alt' => '',
						'active' => true,
						'order' => $i 
					);  
					$i++;
				}
				
			}
			$xml=Xml::arrayToXml( $gallery );
			$ret =  file_put_contents(Core::$settings['rootpath'].'addins/plugins/gallery/data/'.$name.".gallery", $xml);
		}

}  


?>
