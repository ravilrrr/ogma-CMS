<?php 

 /**
 *	OGMA CMS Query Module
 *
 *	@package ogmaCMS
 *	@author Mike Swan / n00dles101
 *	@copyright 2013 Mike Swan / n00dles101
 *	@since 1.0.0
 *
 */



class Query {
		
    public $query = "";

    public $table = "";
    
    public $tableOutput = "";
    
    public $tableCache = array() ;

    public $currentRecord = null;
    
    public $records = array();

    public $sort ='';

    public $sortdir = ''; 
    
    public $queryresults = array();
    
    public $tableFields = array();	
	
	public $tableOptions = array();	
		
	public function __construct($table) {
	 	$this->table = $table;
	 	$this->loadTable();
	 	//$this->getCacheFile();
	}
	
	public function loadTable(){
		$xmlArray  = (Xml::xml2array(ROOT . '/data/'.$this->table.'/schema.xml'));
		$this->tableFields = $xmlArray['fields'];
		$this->tableOptions =  $xmlArray['options'];
	}
	
	public function getTable(){
		$this->loadTable();
	}

	public function getCache(){
		$this->getCacheFile(true); 
		return $this;
	}

	public function addField($name, $type, $update = false){
		$this->tableFields[$name] = $type;
		if ($update){
			//addFieldToRecords($this->table,$name,$type);
		}
		return true;
	}

	public function saveSchema(){
		return $this->createSchema( $this->table, $this->tableFields, $this->tableOptions );
	}

	public static function tableExists($table){
		return file_exists(ROOT . 'data/' . $table . '/schema.xml');
	}

	/**
    * Create a New Table
    *
    * @param array $table Table name
    * @param boolean $active Set true for active Tab
    */
	public static function createTable( $table, $fields, $options = array() ){
 		$ret = Query::createfolder($table);
		if ($ret){
			Query::createSchema($table, $fields, $options);
		}
		Debug::addUpdateLog(User::getUsername().__("CREATEDTABLE").$table,User::getUsername());
	 }
	 
	public function deleteField($name){
		unset($this->tableFields[$name]);

	}

	public function deleteFieldFromRecords($table,$name){
		$path = ROOT . '/data/'.$table.'/';
		$dir_handle = @opendir($path) or die("Unable to open $path");
		$filenames = array();
		while ($filename = readdir($dir_handle)) {
			$ext = substr($filename, strrpos($filename, '.') + 1);
			$fname=substr($filename,0, strrpos($filename, '.'));
			          
			if ($ext=="xml" && $fname!='schema'){
				//$thisfile_DM_Matrix = Filesystem::readFile($path.$filename);
				$data = Xml::xml2array($path.$filename);
				unset($data[$name]);
				$ret =  Filesystem::writeFile(	$path.$filename , Xml::arrayToXml($data));
			}
		} 
	}

	public function addFieldToRecords($table,$name,$type){
		$path = ROOT . '/data/'.$table.'/';
		$dir_handle = @opendir($path) or die("Unable to open $path");
		$filenames = array();
		while ($filename = readdir($dir_handle)) {
			$ext = substr($filename, strrpos($filename, '.') + 1);
			$fname=substr($filename,0, strrpos($filename, '.'));
			          
			if ($ext=="xml" && $fname!='schema'){
				//$thisfile_DM_Matrix = Filesystem::readFile($path.$filename);
				$data = Xml::xml2array($path.$filename);
				$data[$name] = '';
				$ret =  Filesystem::writeFile($path.$filename , Xml::arrayToXml($data));
			}
		} 
	}
	 
	 // create a Schema folder
	 public static function createFolder($table){
	 	if (!is_dir(ROOT.'/data/'.$table)){	
			$ret = mkdir(ROOT.'/data/'.$table, 0777);
		} else {
			$ret=false;
		}
		return $ret;
	 }

	public static function createSchema( $table, $fields = array(), $options = array()){
	 	
		// Create option fields 
        $_options = '';
		if (!array_key_exists('id', $fields)){
			$fields['id']='int';	
		}
		// create an empty cache entry if it doesn't exist
        if (!array_key_exists('cache', $options)){
			$options['cache']='';	
		}

		$options['private'] = '0';

        foreach ($options as $option=>$value) $_options .= "<$option><![CDATA[$value]]></$option>";
        

		// Create table fields 
        $_fields = '<fields>';
        foreach ($fields as $field=>$type) $_fields .= "<$field><![CDATA[$type]]></$field>";
        $_fields .= '</fields>';
		
	 	return Filesystem::writeFile(ROOT . '/data/' . $table . '/schema.xml','<?xml version="1.0" encoding="UTF-8"?><root><options><id>1</id>'.$_options.'</options>'.$_fields.'</root>', LOCK_EX); 
		
	 }

	public function getCacheFile(){
		$file = ROOT . '/data/'.$this->table.'/'.$this->table.'.cache';
		if (file_exists($file)){
			Debug::addLog(__("LOG_LOADCACHE"). $this->table.'.cache','info',true);
			$thisfile = Filesystem::readFile($file);
		    $data = simplexml_load_string($thisfile);
		    $pages = $data->item;
		      foreach ($pages as $page) {
		      	if (strstr("slug", $this->tableOptions['cache'])){
		      		$key=$page->slug;
		      	} else {
		      		$key=$page->id;
		      	}
		        
		        $this->tableCache[(string)$key]=array();
		        foreach ($page->children() as $opt=>$val) {
		           $this->tableCache[(string)$key][(string)$opt]=(string)$val;
		        }    
		      }
           
		    $this->records = $this->tableCache;
            $this->queryresults = $this->tableCache;
		    $this->tableCache = array();
		} else {
			$this->generateCacheFile();
			$this->getCacheFile(true);
		}
	}

	public function generateCacheFile(){
		if ($this->tableOptions['cache']=="") return;
		$fields = explode("|", $this->tableOptions['cache']);
		if (count($fields)>0) {
			$path = ROOT . '/data/'.$this->table.'/';

			$cacheArray = array();
			$table = array();
	
			if (is_dir($path)){
				  $dir_handle = @opendir($path) or die("Unable to open $path");
				  $filenames = array();
				  $recordId=0;
			  while ($filename = readdir($dir_handle)) {
					$ext = substr($filename, strrpos($filename, '.') + 1);
					$fname=substr($filename,0, strrpos($filename, '.'));
	                              
					if ($ext=="xml" && $fname!='schema'){
						
						$tmpArray = Xml::xml2array(ROOT . '/data/'.$this->table.'/'.$filename);

						$cacheArray[$recordId]=array();
						foreach ($fields as $field) {
							$cacheArray[$recordId][$field] = $tmpArray[$field];
						}
						$recordId++;

					}

	                   
				}

				$xml=Xml::arrayToXml($cacheArray);
				$ret =  Filesystem::writeFile(ROOT . '/data/'.$this->table.'/'.$this->table.'.cache',$xml);
				Debug::addLog(__("LOG_WRITECACHE").' '.$this->table);
			}
		}

	}
	
	public function query( $rows = '*', $query = ''){
		$this->getRecords($rows, $query);
	}


	public static function getTables($full = false){
		$tables = array();
		$folders = Core::getFolders(ROOT . 'data/');
		$count=0;
		foreach ($folders as $folder){
			if (file_exists(ROOT . 'data/'.$folder.'/schema.xml')){
				$tableinfo = Xml::xml2array(ROOT . 'data/'.$folder.'/schema.xml');
				if ($full){
					$tables[$count]['name'] = $folder;
					$tables[$count]['private'] = $tableinfo['options']['private'] == true ? "System" : "User";
					//$tables[$count]['maxrecords'] = $tableinfo['options']['maxrecords'];
					
				} else {
					$tables[] = $folder;	
				}


							
				$count++;
			}
		}
		return $tables;
	}

	public function getTableFields(){
		return $this->tableFields;
	}

	public function getTableOptions(){
		return $this->tableOptions;
	}

	public function htmlTable( $fields, $options, $showoptions = true ){

			// see if table width are provided
			if (array_key_exists('widths', $options)){
				$widths=explode("|", $options['widths']);
			} else {
				$widths = array();
			}

	    	$rows = $this->queryresults;

			$saveas = 'id';
	    	$this->tableOutput .=  '<table class="table table-bordered table-striped table-hover">';
		    $this->tableOutput .=  '<thead>';		   
		    $this->tableOutput .=  '  <tr>';
		    $count=0;
		    foreach ($fields as $item => $value) {
		    	if ($value==$this->sort) {
		    		if ($this->sortdir=="asc"){
		    			$class=" sorting_desc";
		    			$sort=" data-sortdir='desc'";
		    		} else {
		    			$class=" sorting_asc";
		    			$sort=" data-sortdir='asc'";
		    		}
		    	} else {
		    		$sort=" data-sortdir='asc'";
		    		$class=" sorting";
		    	}
		    	$this->tableOutput .= '<th class="sortable '.$class.' table-'.strtolower($value).'" '.$sort.' data-id="'.strtolower($value).'" ';
		    		if (count($widths)>0) $this->tableOutput .=  " style='width:".$widths[$count]."%' ";
		    	$this->tableOutput .= '>'.$item.'</th>';
		   		$count++;
		    }
		    if ($showoptions) $this->tableOutput .=  '<th class="table-options">'.__("OPTIONS").'</th>';
		    $this->tableOutput .=  '  </tr>';
		    $this->tableOutput .=  '</thead>';
		    $this->tableOutput .=  '<tbody> ';
		   
		   	if (count($rows)>0){
			    foreach ($rows as $row) {
			     	$this->tableOutput .=  '<tr>';
				    foreach ($fields as $item) {
				         $this->tableOutput .=  '<td>';
				         $options['id']=$row['id'];
				         $this->tableOutput .=  $this->getField($item.'-'.$row['id'] ,'' ,$this->tableFields[$item], $options , $row[$item]);
				         $this->tableOutput .=  '</td>';         
				    }
				if ($showoptions){
				    $this->tableOutput .=  '<td>';
					$this->tableOutput .=  '<div class="btn-group">';
					$this->tableOutput .=  '<button class="btn btn-default" onclick="location.href=\''.Core::getFilenameId().'.php?action=edit&amp;id='.$row['id'].'\'">'.__("EDIT").'</button>';
					$this->tableOutput .=  '<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">';
					$this->tableOutput .=  '    <span class="caret"></span>';
					$this->tableOutput .=  '  </button>';
					$this->tableOutput .=  ' <ul class="dropdown-menu">';
			        $this->tableOutput .=  '  <li><a href="#" data-nonce="'.Security::getNonce('deleterecord',Core::getFilenameId().'.php').'" data-slug="'.$row['id'].'"  data-table="'.$this->table.'" class="delButton">'.__("DELETE").'</a></li>';
			        $this->tableOutput .=  '</ul>';
					$this->tableOutput .=  '</div>';
			        $this->tableOutput .=  '</td>';
			    }
				    $this->tableOutput .=  '</tr>';
				}
			}
		    

		   $this->tableOutput .=  ' </tbody>';
		   $this->tableOutput .=  '</table>';

		   echo $this->tableOutput;

	}

	public function htmlTableheader( $fields, $options, $showoptions = true ){

			// see if table width are provided
			if (array_key_exists('widths', $options)){
				$widths=explode("|", $options['widths']);
			} else {
				$widths = array();
			}

	    	$rows = $this->queryresults;
	    	$this->fields=$fields;
			$saveas = 'id';
	    	$this->tableOutput .=  '<table class="table table-bordered table-striped table-hover">';
		    $this->tableOutput .=  '<thead>';		   
		    $this->tableOutput .=  '  <tr>';
		    $count=0;
		    foreach ($fields as $item => $value) {
		    	if ($value==$this->sort) {
		    		if ($this->sortdir=="asc"){
		    			$class=" sorting_desc";
		    			$sort=" data-sortdir='desc'";
		    		} else {
		    			$class=" sorting_asc";
		    			$sort=" data-sortdir='asc'";
		    		}
		    	} else {
		    		$sort=" data-sortdir='asc'";
		    		$class=" sorting";
		    	}
		    	$this->tableOutput .= '<th class="sortable '.$class.' table-'.strtolower($value).'" '.$sort.' data-id="'.strtolower($value).'" ';
		    		if (count($widths)>0) $this->tableOutput .=  " style='width:".$widths[$count]."%' ";
		    	$this->tableOutput .= '>'.$item.'</th>';
		   		$count++;
		    }
		    if ($showoptions) $this->tableOutput .=  '<th class="table-options">'.__("OPTIONS").'</th>';
		    $this->tableOutput .=  '  </tr>';
		    $this->tableOutput .=  '</thead>';

	}

	public function htmlTableRow($row, $options, $showoptions = true ){
			$fields=$this->fields;
			$this->tableOutput .=  '<tr>';
				    foreach ($fields as $item) {
				         $this->tableOutput .=  '<td>';
				         if (isset($options['indent']) && $options['indent']==$item) $this->tableOutput .= "&nbsp;&nbsp;-&nbsp;&nbsp;";
				         $options['id']=$row['id'];
				         $this->tableOutput .=  $this->getField($item.'-'.$row['id'] ,'' ,$this->tableFields[$item], $options , $row[$item]);
				         $this->tableOutput .=  '</td>';         
				    }
				if ($showoptions){
				    $this->tableOutput .=  '<td>';
					$this->tableOutput .=  '<div class="btn-group">';
					$pagename = Core::getFilenameId();
					if ($pagename=="load"){
						$this->tableOutput .=  '<button class="btn btn-default" onclick="location.href=\'load.php?tbl='.Core::getTable().'&action=edit&amp;id='.$row['id'].'\'">'.__("EDIT").'</button>';				
					} else {
						$this->tableOutput .=  '<button class="btn btn-default" onclick="location.href=\''.Core::getFilenameId().'.php?action=edit&amp;id='.$row['id'].'\'">'.__("EDIT").'</button>';
					
					}
					$this->tableOutput .=  '<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">';
					$this->tableOutput .=  '    <span class="caret"></span>';
					$this->tableOutput .=  '  </button>';
					$this->tableOutput .=  ' <ul class="dropdown-menu">';
			        $this->tableOutput .=  '  <li><a href="#" data-nonce="'.Security::getNonce('deleterecord',Core::getFilenameId().'.php').'" data-slug="'.$row['id'].'"  data-table="'.$this->table.'" class="delButton">'.__("DELETE").'</a></li>';
			        $this->tableOutput .=  '</ul>';
					$this->tableOutput .=  '</div>';
			        $this->tableOutput .=  '</td>';
			    }
				    $this->tableOutput .=  '</tr>';
	}
	

	public function htmlTableFooter(){
		$this->tableOutput .=  ' </tbody>';
	   	$this->tableOutput .=  '</table>';
	   	echo $this->tableOutput;
	}


	public static function doPagination($page,$totalRecords){
		if ($totalRecords>PAGINGSIZE){
			echo ' <ul class="pagination">';
			if (Core::getTable()==""){
			   		$filename = Core::getFilenameId().".php?";
			   	} else {
			   		$filename = Core::getFilenameId().".php?tbl=".Core::getTable()."&";
			}
			if ($page==0){
					echo "<li class='disabled' ><span>Prev</span></li>";
				} else {
					echo "<li  ><a href='".$filename."action=view&page=".($page-1).self::buildSortUri()."'>Prev</a></li>";
				}
			    $totalpages = $totalRecords / PAGINGSIZE;
			    
			    if ($totalpages<=PAGINGSIZE){
				    for ($i=0; $i < $totalpages; $i++) { 
				    	echo ' <li'; 
				    	if ($i==$page) echo " class=' active' "; 
				    	echo '><a href='.$filename.'action=view&page='.round($i).self::buildSortUri().'>';
				    	echo round($i+1);
				    	echo '</a></li>';
				    }
				} else {

						$lastpage = ($page+(PAGINGSIZE/2)>$totalpages) ? $totalpages-1 : $page+(PAGINGSIZE/2);
						$startpage = ($page-(PAGINGSIZE/2)<0) ? 0 : $page-(PAGINGSIZE/2); 
						if ($startpage==0) $lastpage +=7;
				    	if ($page>=PAGINGSIZE-1){
				    		echo '<li><a href='.$filename.'action=view&page=0'.self::buildSortUri().'>1</a></li>';
				    		echo "<li class='disabled' ><span>...</span></li>"; 
				    	}

				    	for ($i=$startpage; $i < $lastpage ; $i++) { 
						echo '<li';
				    		if ($i==$page) echo " class=' active' "; 
					    	echo '><a href='.$filename.'action=view&page='.round($i).self::buildSortUri().'>';
					    	echo round($i+1);
					    	echo '</a></li>';
				    }
				    if ($lastpage!=$totalpages-1){
					    echo "<li class='disabled' ><span>...</span></li>";
					    echo ' <li><a href='.$filename.'action=view&page='.(round($totalpages)-1).self::buildSortUri().'>'.(round($totalpages)-1).'</a></li>';
					}
				}			   

			    if ($page==round($totalRecords/PAGINGSIZE)-1 ){
					echo "<li class='disabled' ><span>Next</span></li>";
				} else {
					echo "<li ><a href='".$filename."action=view&page=".round($page+1).self::buildSortUri()."'>Next</a></li>";
				}
				echo  '  </ul>';
			}
}


	public  function getField($name, $label, $type, $options,$value=''){
		if (@!include_once(Core::$settings['fieldspath'] . $type . '.field.php'))
			{
				$this->tableOutput .= "Field not Found...".$type;
			}
		if (class_exists($type))
			{	
				$id = $options['id'];
				$value = stripslashes(htmlentities ($value, ENT_QUOTES, "UTF-8"));
				$options['table'] = $this->table;
				$options['id'] = $id;
				$options['field'] = str_replace('-'.$id, '', $name);
				$getValue = new $type($name, $label, $type, $options,$value);
				$this->tableOutput .= $getValue->value;
			}
		}

	// getRecords 
	// retrive all records from this table
	public function getRecords($rows, $query){
		// see what rows to fetch
		$getRows = array();
		// do select all 
		if ($rows=='*'){
			foreach ($this->tableFields as $key => $value) {
				array_push($getRows, $key);
			}
		} else {
		// parse rows to retrieve	
			$getRows=explode("|",$rows);
		}

		$path = ROOT . '/data/'.$this->table.'/';
			// check path exists and fetch all xml file, except for schema
			$table =array();
			if (is_dir($path)){
				  $dir_handle = @opendir($path) or die("Unable to open $path");
				  $filenames = array();
				  $recordId=0;
				  while ($filename = readdir($dir_handle)) {
					$ext = substr($filename, strrpos($filename, '.') + 1);
					$fname=substr($filename,0, strrpos($filename, '.'));
	                              
					if ($ext=="xml" && $fname!='schema'){
						// load the xml file
						$thisfile = Filesystem::readFile($path.$filename);
						$data = simplexml_load_string($thisfile);
						
						$id=$data;
						$idNum=$id->id;

						// see if there any initial selectors 
						$selectors = explode(",",$query);
						$numSelectors = count($selectors);
						$matches=0;

						// loop through the selctors 
						foreach ($selectors as $selector) {
							
							$parts = $this->parse($selector);
							$key = trim($parts[1]);				// key
							$operator = trim($parts[2]);		// operator
							$value = trim($parts[3]);			// value

							$getRecord = false;
							
							if ($query=='') {
								$getRecord = true;
							} else {
								foreach ($data->children() as $opt=>$val) {
									if ( self::evaluate($val, $operator, $value) && $opt==$key){
										$matches++;
									//echo "<br/>Matched ".$opt."=".$val.$operator.$value."-".$matches;
										}
									
								}
								if ($matches==$numSelectors) $getRecord = true;
								}
							}
							// if this record is to be loaded, get it 
							if ( $getRecord===true ){
								foreach ($data->children() as $opt=>$val) {
									if (in_array($opt, $getRows)){
											$table[(int)$idNum][(string)$opt]=(string)$val;
									}
								}
							}

						
						//$table[(int)$idNum]['id']=(int)$idNum;
						$recordId++;		
					}
				  } 
	              	$this->records=$table;
	         		$this->queryresults=$table;
	                   
			}
		return $this;
	}


	public function get(){
		return $this->queryresults;
	}

	public function reload(){
		$this->queryresults = $this->records;
		return $this;
	}

	public function unique($row){
		$tmpArray= array();
		$records = $this->queryresults;
		$this->queryresults = array();
		foreach ($records as $record) {
			if (!in_array($record[$row], $tmpArray)){
				$tmpArray[] = $record[$row];
			}
		}

		$this->queryresults = $tmpArray;
		return $this;
	}

	public static function getSortOptions(){
		$tmpArray = array();
		$tmpArray['page']     =  isset($_GET['page']) ? $_GET['page'] : 0;
		$tmpArray['sort']     =  isset($_GET['sort']) ? $_GET['sort'] : 'id';
		$tmpArray['dir']      =  isset($_GET['dir']) ? $_GET['dir'] : 'asc';	
		return $tmpArray;
	}

	public static function buildSortUri($addpage = false){
		$tmpArray = Query::getSortOptions();
		if ($addpage) {
			return implode("&", $tmpArray);
		} else {
			return "&sort=".$tmpArray['sort']."&dir=".$tmpArray['dir'];
		}
	}

	// alias for top
	public function limit($num){
		$this->top($num);
		return $this;
	}
	// return top n records
	public function top($num){
        $this->queryresults = array_slice($this->queryresults, 0, $num, true);
        return $this;
    }

    public function order($row, $direction = 'asc'){
    	$this->sort = $row;
    	$this->sortdir = $direction;
	    $this->queryresults = Core::subvalSort($this->queryresults, $row, $direction); 
    	return $this;
    }

    // get a range of records 
    // supply start and num of records to return
    public function range($start = 0, $finish = null){
    	if (!$finish) $finish = $this->count();
    	if ($this->count()>0){
	    	$this->queryresults = array_slice($this->queryresults, $start, $finish, true);
	    }
        return $this;
    }

    // randomize the records
    public function randomize(){
    	$this->queryresults = Arr::shuffleAssoc($this->queryresults);
    	return $this;
    }

    // count number of records
	public function count(){
		return count($this->queryresults);
	}

    public function getNumRows(){
        return count($this->queryresults);
    }
    

	// return current records number
	public function getCurrentRecord(){
		return $this->currentRecord;
	}

	// return a single record
	public function getRecord($id){
		foreach ($this->records as $record) {
			if ($record['id']==$id) {
				$this->currentRecord = $id;
				return $record;
			}
		}
	}

	public function dated($type, $start = 0, $finish = 0){
		$tmpArray = array();
		//debug::pa($this->queryresults);
		$records = $this->queryresults;
		$this->queryresults = array();
		foreach ($records as $record) {
	
			switch ($type) {
				case '24hrs':
					$startTime = mktime() - 24*3600;     
					$endTime = time(); 
					break;
				case 'yesterday':
					$startTime = mktime(0, 0, 0, date('m'), date('d')-1, date('Y'));     
					$endTime = mktime(23, 59, 59, date('m'), date('d')-1, date('Y'));
					break;
				case 'thisweek':
					$startTime = mktime(0, 0, 0, date('n'), date('j'), date('Y')) - ((date('N')-1)*3600*24);     
					$endTime = time(); 
					break;
				case 'lastweek':
					$startTime = mktime(0, 0, 0, date('n'), date('j')-6, date('Y')) - ((date('N'))*3600*24);     
					$endTime = mktime(23, 59, 59, date('n'), date('j'), date('Y')) - ((date('N'))*3600*24); 
					break;
				case 'thismonth':
					$startTime = mktime(0, 0, 0, date('m'), 1, date('Y'));     
					$endTime = time();
					break;
				case 'last30days':
					$starttime = mktime () - 30 * 3600 * 24;      
					$endTime = time ();  
					break;
				case 'lastmonth':
					$startTime = mktime() - 30*3600*24;     
					$endTime = time(); 
					break;
				case 'thisyear':
					$startTime = mktime(0, 0, 0, 1, 1, date('Y'));     
					$endTime = time(); 
					break;
				case 'lastyear':
					$startTime = mktime(0, 0, 0, 1, 1, date('Y')-1);     
					$endTime = mktime(23, 59, 59, 12, 31, date('Y')-1); 
					break;
				case 'range':
					$startTime = $start;
					$endTime = $finish;
					$break;
				default:
					# code...
					break;
			}
			
			if ($record['pubdate']>=$startTime && $record['pubdate']<=$endTime){
				$tmpArray[] = $record;
				}
		}

			$this->queryresults = $tmpArray;
			return $this;
				# code...
	}

	public function getFullRecord($id){
		if (file_exists(Core::$settings['rootpath'] . '/data/'.$this->table.'/'.$id.'.xml')){
			Debug::addLog("Get full record: ".'/data/'.$this->table.'/'.$id.'.xml');
			return (Xml::xml2array(Core::$settings['rootpath'] . '/data/'.$this->table.'/'.$id.'.xml'));
		}
	}

    public  function deleteRecord($id){
        $table=$this->table;
        if (file_exists(ROOT . 'data/' . $table . '/'. $id .'.xml')){
	        $ret=unlink(ROOT . 'data/' . $table . '/'. $id .'.xml');
	        if ($ret>0){
	            Core::addAlert(Form::showAlert('success', __('DELETESUCCESS')) ); 
	            $this->generateCacheFile();
	            $this->getCache();
	        } else {
	            Core::addAlert( Form::showAlert('error', __('UNABLETODELETE')) ); 
	        }
	        Debug::addUpdateLog(User::getUsername().__("DELETEDRECORD").$id." from table ".$table, User::getUsername());
	        return $ret;
	    } else {
	    	return false;
	    }
    }

    public function addRecordForm(){
        $data = array();
        $fieldTypes= $this->tableFields;
        foreach($fieldTypes as  $name => $val){
            if (!isset($_POST['post-'.$name]) && $val=="checkbox") $_POST['post-'.$name]='false';
            if(isset($_POST['post-'.$name])){
              $data[$name]=Utils::manipulateValues($_POST['post-'.$name],$val);
            }
        }
        $file = 'id';
        $data['id'] = $this->getRecordId($this->table);
        $xml=Xml::arrayToXml($data);
        $ret =  Filesystem::writeFile(ROOT . 'data/' . $this->table . '/'. $data['id'] .'.xml', $xml);
        if ($ret){
            $this->incrementRecord($this->table);
            $this->tableOptions['id']++;
            $this->generateCacheFile();
            $this->getCache();
            Core::addAlert( Form::showAlert('success', __("CREATED",array(":record"=>$data['id'],":type"=>"Record"))) );
            return true;
        } else {
            Core::addAlert( Form::showAlert('alert', __("CREATEDFAIL",array(":record"=>$data['id'],":type"=>"Record"))) );
            return false;
        }
    }



    public function getRecordId(){
        return $this->tableOptions['id'];
    }
    
    public static function incrementRecord($table){
        $xmlArray  = (Xml::xml2array(ROOT . '/data/'.$table.'/schema.xml'));
        $xmlArray['options']['id']++;
        return Filesystem::writeFile(   ROOT . '/data/' . $table . '/schema.xml', Xml::arrayToXml($xmlArray));
    }

    public function saveRecord($data = array(),$id){
        $xml=Xml::arrayToXml($data);
        $ret =  Filesystem::writeFile(ROOT . 'data/' . $this->table . '/'. $id .'.xml', $xml);
        if ($ret>0){
            Core::addAlert( Form::showAlert('success', __("UPDATED",array(":record"=>$id,":type"=>"Record"))) );
            $this->generateCacheFile();
            $this->getCache();
            Debug::addUpdateLog(User::getUsername().__("UPDATEDRECORD").$this->table." / ".$id.".",User::getUsername());
            return true;
        } else {
            Core::addAlert( Form::showAlert('error', __("UPDATEDFAIL",array(":record"=>$id,":type"=>"Record"))) );
            return false;
        }
    }


	public static function evaluate($key, $operator, $value){
		switch ($operator) {
			case '=':
				if (strcmp((string)$key,(string)$value)==0){
					return true;
				}
				break;
			
			case 'like':
				if (stripos( $key , $value) !== false){
					return true;
				}
				break;
				
			case '!=':
				if ($key != $value){
					return true;
				}
				break;
			case '>':
				if ($key > $value){
					return true;
				}
				break;
			case '>=':
				if ($key >= $value){
					return true;
				}
				break;
			case '<':
				if ($key < $value){
					return true;
				}
				break;
			case '<=':
				if ($key <= $value){
					return true;
				}
				break;
			default:
				# code...
				break;
		}
	}

	// search all records, returns a subset of records to $this->records
	public function find($selector){
		$selectors = explode(",",$selector);
		foreach ($selectors as $selector) {
			
			$parts = $this->parse($selector);
			$key = trim($parts[1]);
			$operator = trim($parts[2]);
			$value = trim($parts[3]);

			$records = $this->queryresults;
			$this->queryresults = array();
			$keys = explode("|", $key);
			foreach ($keys as $key) {
				foreach ($records as $record) {

					if (self::evaluate($record[$key], $operator, $value)){
						$this->queryresults[] = $record;
					}
					
				}
			}
		}
		//$this->records = $this->queryresults;
		//$this->queryresults = array();
		return $this;
	}




	public function parse($str){
		// (.[\sa-zA-z0-9\._]+)(<|>|=|!=|>=|<=|<>)+(.[\sa-zA-z0-9\._]+)*
		// /^([-._a-zA-Z0-9|]+)([<>!*]?=|[<>])/

		if(preg_match('/(.[\s*?a-zA-z0-9|\._]+)(like|!=|>=|<=|<>|<|>|=)([\s\'\"*?a-zA-z0-9\.\-_]+)*/', $str, $matches)) {
			return $matches;
		} 
		return false;
	}


}

?>
