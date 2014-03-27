<?php 
session_start();
error_reporting(E_ALL);
define('DS', DIRECTORY_SEPARATOR);
define('IN_OGMA', true);
// Load Core file
require_once( '..' . DS . 'config.php');
require_once(  'system' . DS . 'core.php');
$core = new Core();



if (User::isLoggedIn()==true){
    if (isset($_REQUEST['q'])){
	    switch ($_REQUEST['q']) {
	    	// check if a table and record exists
	    	case '1':
	    		$table = isset($_REQUEST['table']) ? $_REQUEST['table'] : '';
	    		$record = isset($_REQUEST['record']) ? $_REQUEST['record'] : ''; 

	    		$ret = Query::recordExists($table, $record);
	    		if ($ret){
	    			echo "1";
	    		} else {
	    			echo "0";
	    		}
	    		break;
	    	// change a spinner value
	    	case "2":
	    		$table = isset($_REQUEST['table']) ? $_REQUEST['table'] : '';
	    		$id = isset($_REQUEST['record']) ? $_REQUEST['record'] : ''; 
	    		$field = isset($_REQUEST['field']) ? $_REQUEST['field'] : '';
	    		$value = isset($_REQUEST['value']) ? $_REQUEST['value'] : '';
	    		
	    		$tbl = new Query($table);
	    		//$tbl->getCache();
	    		$record = $tbl->getFullRecord($id);
	    		$record[$field] = $value;

	    		$ret = $tbl->saveRecord($record,$id);
	    		if ($ret) {
	    			echo "1";
	    		} else {
	    			echo "0";
	    		}
	    		break;	
	     	// change a dropdown value
	    	case "3":
	    		$table = isset($_REQUEST['table']) ? $_REQUEST['table'] : '';
	    		$id = isset($_REQUEST['record']) ? $_REQUEST['record'] : ''; 
	    		$field = isset($_REQUEST['field']) ? $_REQUEST['field'] : '';
	    		$value = isset($_REQUEST['value']) ? $_REQUEST['value'] : '';
	    		
	    		$tbl = new Query($table);
	    		$record = $tbl->getFullRecord($id);
	    		$record[$field] = $value;

	    		$ret = $tbl->saveRecord($record,$id);
	    		if ($ret) {
	    			echo "1";
	    		} else {
	    			echo "0";
	    		}
	    		break;	
	    	//check if a slug exists
	    	case "4":
	    		$table = isset($_REQUEST['table']) ? $_REQUEST['table'] : '';
	    		$slug = isset($_REQUEST['slug']) ? $_REQUEST['slug'] : '';
	    		$tbl = new Query($table);
	    		$records = $tbl->getCache()->get();
	    		if (Arr::arraySearch($slug, $records, 'slug')){
	    			echo "1";
	    		} else {
	    			echo "0";
	    		}

	    		break;
	    	
	    	// clear cache files
	    	case "5": 
				$folders = Core::getFolders(ROOT . 'data/');
				$count=0;
				foreach ($folders as $folder){
					if (file_exists(ROOT . 'data/'.$folder.'/schema.xml')){
						$filename = ROOT . 'data/'.$folder.'/'.$folder.'.cache';
						if (file_exists($filename)) unlink($filename);
					}
				}
				echo "1";
	    		break;
	    	// create a sitemap
	    	case "6":
	    		$sm = new Sitemap();
				$sm->addPages();
				echo $sm->saveSitemap();
				
	    		break;
	    	// return a json list of media tags
	    	case "7":
	    		$table = new Query('media');
	    		$tags =  $table->getCache()->unique('tag')->get();
				echo json_encode($tags);
	    		break;
	    	// return a json list of media tags
	    	case "8":
	    		$media = array();
	    		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : ''; 
	    		$table = new Query('media');
	    		$records = $table->getCache()->get();
	    		foreach ($records as $record) {
	    			$media[]=array(	'value' => $record['id'],
	    							'imageSrc' => $record['fileurl'],
	    							'text' => 'Image Name',
	    							'description' => "test description",
	    							'selected' => ($id==$record['id']) ? true : false );
	    				
	    		}
				echo json_encode($media,true);
	    		break;
	    	// media manager
	    	case "99":
	    		$table = new Query('media');
				
				$tags =  $table->getCache()->unique('tag')->get();
				$records = $table->getCache()->get();
				$output =  "<div class='row'>";
				$output .= "<div class='col-md-12'>";
				$output .= "<div class='form-group'>";
				$output .= "Filter: ";
			    $output .= "<p><button type='button' class='btn btn-primary btn-xs filter'>All</button>";
			    foreach ($tags as $tag){
			      $output .= "<button type='button' class='btn btn-primary btn-xs filter'>".$tag."</button>";
			    }
			    $output .= "</p></div></div></div>";

			    $output .=  "<div class='row'>";
				$output .=  "<div class='col-md-12 ' style='height:215px;overflow:auto;'>"; 

				foreach ($records as $record) {
					$output .=  "<div class='col-xs-3 ".$record['tag']."'><a href='#' class='thumbnail' style='float:left;'><img src='".$record['fileurl']."' alt=''></a></div>";
				}
				$output .= "</div></div>";
				echo $output;


	    		break;
	    	// markdown readme conversion
	    	case "100";
	    		$readme = Core::$settings['pluginpath'].DS.$_REQUEST['plugin'].DS.'readme.md';
	    		$content = '';
	    		if (file_exists($readme)){
	    			$content = file_get_contents($readme);
	    			$content = Markdown($content);
	    		}
	    		echo $content;
	    		break;
	    	case "101":
	    		$page = new Page($_REQUEST['page']);
	    		$content = $_REQUEST['value'];
	    		$page->pageFields['content'] = $content;
		        $content = Utils::safe_strip_decode($content);
		        $content = Markdown($content);
		        $content = Filters::execFilter('content',$content);
		        echo $content;
		        break;
	    	default:
	    		# code...
	    		echo "0";
	    		break;
	    }
	}
} else {
	echo "not logged in";
	return "false";
}
