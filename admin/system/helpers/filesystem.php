<?php 

 /**
 *  OGMA CMS Actions Module
 *
 *  @package ogmaCMS
 *  @author Mike Swan / n00dles101
 *  @copyright 2013 Mike Swan / n00dles101
 *  @since 1.0.0
 *
 */

class Filesystem{


	public function __construct($table) {
	 	// nothing
	}


	public static function writeFile($file, $content){
		$ret = file_put_contents($file, $content); 
		Debug::addLog("Writing file - (".$ret.")".$file);
		return $ret; 
	}

	public static function readFile($file){
		Debug::addLog("Reading file - ".$file);
		return file_get_contents($file);
	}


	public static function addVersionFile($table, $id){
		if (!file_exists(ROOT . 'data/' . $table . '/versions')){
			mkdir (ROOT . 'data/' . $table . '/versions');
		}
		$timestamp = date('U');

		$ret = copy(ROOT . 'data/' . $table . '/'. $id .'.xml', ROOT . 'data/' . $table . '/versions/'.$id.'.'.$timestamp.'.xml'); 

	}

	public static function hasVersions($table, $id){
		$filenames = array();
		if (Core::$site['history']==true){
			if (!in_array($table, Core::$no_version_control)){
				$files = glob(ROOT . 'data/' . $table . '/versions/'.$id.'.*.xml');
				
				foreach ($files as $file) {
					$parts=explode('.', pathinfo($file, PATHINFO_FILENAME));
					$filenames[] = $parts[1];
				}
			}
		}
		return ($filenames);
	}

	public static function showVersions($table, $id, $filenames = array()){
		$output = '';
		if (count($filenames)>0){
			$output .= '<table class="table table-bordered table-striped table-hover">';
			$output .=  '<thead>';		   
		    $output .=  '<tr>';
		    $output .=  '<td style="width:80%;">'.__("DATE").'</td>';
		    $output .=  '<td>'.__("OPTIONS").'</td>';
		    
		    $output .=  '</tr>';
		    $output .=  '</thead>';
		    $output .=  '<tbody>';
		    
		    

			foreach ($filenames as $file) {
				$output .= '<tr>';
				$output .= '<td>'.Core::date($file, true).'</td>';
				$output .= '<td>';
				$output .= '<button type="button" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-refresh"></span> '.__("RESTORE").'</button> ';
				$output .= '<button type="button" class="btn btn-info btn-xs"  disabled="disabled"><span class="glyphicon glyphicon-star"></span> '.__("DIFF").'</button> ';
				$output .= '<button type="button" class="btn btn-error btn-xs"><span class="glyphicon glyphicon-trash"></span> '.__("DELETE").'</button>';


				$output .= '</td>';
				
				$output .=  '</tr>';
			}
			$output .= '</tbody>';	
			$output .= '</table>';
		}

		return $output; 
	}

}