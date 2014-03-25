<?php 

// Tesimonial plugin for OGMA CMS

Plugins::registerPlugin( 
				'testimonials',
        'Testimonials',
        'Testimonials Plugin for OGMA CMS',
        '0.0.1',
        'Mike Swan',
        'http://www.digimute.com/'
        );

class Testimonials{
	
	public function __construct() {
 
    }

    public static function init(){
        // check if the table exists 
        if (!Query::tableExists('testimonials')){
            // create it
            Query::createTable('testimonials', 
                array(
                        'who'=>'text', 
                        'what'=>'textarea', 
                        'when'=>'datetimepicker',
                        'company'=>"text",
                        'email'=>"textlong",
                        'active' =>"yesno"
                    ), 
                array(
                        'cache'=>'id|who|what|when|company|email|active'
                    )
                );

        }
        if (Query::tableExists('testimonials')){
            Actions::addAction('admin-add-sidebar','Menu::addSidebarMenu',1,array("Testimonials",'','testimonials','fa fa-fw fa-edit'));
            Actions::addAction('admin-add-to-dashboard','Menu::addDashboardItem',1,array("Testimonials",'','testimonials','fa fa-fw fa-edit'));
        }
        $language = Core::$site['language'];
        Lang::mergeLanguage(Core::$settings['pluginpath'].'testimonials'.DS.'lang'.DS.$language.'.lang.php');
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

        $table = new Query('testimonials');

        extract(Query::getSortOptions());

        if ($action=="deleterecord" ){
          $nonce = $_POST['security-nonce'];
          $record = $_POST['security-record'];
          $tableid = $_POST['security-table'];
          if (Security::checkNonce($nonce,'deleterecord', 'testimonials')){
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
            <legend><?php echo __("VIEW",array(":type"=>__("TESTIMONIALS_TESTIMONIALS"))); ?></legend>
             <div class="btn-group" style="padding-bottom:15px;">
                <button class="btn btn-primary" onclick="location.href='load.php?tbl=testimonials&action=create'"><span class="glyphicon glyphicon-plus"></span> Create New Testimonial</button>
             </div>

              <?php 
              $totalRecords = $table->count();
              $records = $table->order($sort,$dir)->range($page*15,15)->get();

              $table->htmlTableHeader(
                  // array of headings
                  array(
                      __("TESTIMONIALS_WHO")=>"who",
                      __("TESTIMONIALS_COMPANY")=>"company",
                      __("ACTIVE")=>"active"
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
                      'widths'=>'5|50|20|15'
                      ), true); 

                }
              }
              $table->htmlTableFooter();
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

            if ($action=="edit") $ogmaForm->addHeader( __("TESTIMONIALS_EDIT")." : ".$record['id']);
            if ($action=="create") $ogmaForm->addHeader(__("TESTIMONIALS_CREATE"));
           
            $ogmaForm->startTabHeaders();

            $ogmaForm->createTabHeader(array('main'=>'Main'),true);
            
            Actions::executeAction('testimonials-tab-header');

            $ogmaForm->endTabHeaders();

            if ($action=="edit") $ogmaForm->createForm('load.php?tbl=testimonials&action=update&amp;id='.$record['id']);
            if ($action=="create") $ogmaForm->createForm('load.php?tbl=testimonials&action=createnew');

            $ogmaForm->startTabs();
            $ogmaForm->createTabPane('main',true);
            $ogmaForm->displayField('post-active', __("ACTIVE") ,  $table->tableFields['active'], '',$record['active']);
            $ogmaForm->displayField('post-who', __("TESTIMONIALS_WHO") ,  $table->tableFields['who'], '',$record['who']);
            $ogmaForm->displayField('post-company',__("TESTIMONIALS_COMPANY"),  $table->tableFields['company'], '',$record['company']);
            $ogmaForm->displayField('post-email',__("TESTIMONIALS_EMAIL"),  $table->tableFields['email'], '',$record['email']);
            $ogmaForm->displayField('post-what',__("TESTIMONIALS_WHAT"), $table->tableFields['what'], array("rows"=>5),$record['what']);
            $ogmaForm->displayField('post-when',__("TESTIMONIALS_WHEN"),   $table->tableFields['when'], '',$record['when']);  
            $ogmaForm->displayField('post-id','ID', 'hidden', '',$record['id']);
            
            Actions::executeAction('testimonials-tab-new');

            $ogmaForm->endTabs();
            
            $ogmaForm->formButtons(true);
            $ogmaForm->endForm();

            $ogmaForm->show();

            ?>
            </div>
        <?php
        } 
    }

    public static function showTestimonials($atts, $content = null){
         extract(Shortcodes::shortcode_atts(array(
            "who" => null,
            "when" => null    
          ), $atts));

        return '<div class=" quotebubble-wide-bttm">
              <div class="qb-top">&nbsp;</div>
              <div class="qb-mid client">
                <blockquote>'.$content.'</blockquote>
                <h3>'.$who.'</h3>
              </div>
              <div class="qb-bttm">&nbsp;</div>
            </div>';

          //return '<p>'.$content.'</p><h4 class="title">'.$who.'</h4>';
         
    }

    public static function getTestimonials($num = 9999,$random = false){
        $table = new Query("testimonials");
        if ($random){
          $testimonials = $table->getCache()->find("active = 1")->top($num)->get();
        } else {
           $testimonials = $table->getCache()->find("active = 1")->randomize()->top($num)->get();
        }
        return $testimonials;

    }

}

?>
