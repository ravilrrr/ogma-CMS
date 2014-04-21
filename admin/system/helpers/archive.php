<?php 

 /**
 *	ogmaCMS Archive Module
 *
 *	@package ogmaCMS
 *	@author Mike Swan / n00dles101
 *	@copyright 2013 Mike Swan / n00dles101
 *	@since 1.0.0
 *
 */

class Archive {

	public static $files = array();
	public static $rootpath = '';


	public function __construct() {
		
    }

    public static function listArchives(){
    	$sourcePath = Core::$settings['rootpath']."backups/";
    	return Core::getFiles($sourcePath,'zip');
    }

   	public static function setRootPath($path){
   		Archive::$rootpath = $path;
   	}

    public static function getFiles($path){
    	$sourcePath = Archive::$rootpath.$path;
    	
    	$dirIter = new RecursiveDirectoryIterator($sourcePath);
		$iter = new RecursiveIteratorIterator($dirIter);

		foreach($iter as $element) {

		    $dir = str_replace($sourcePath, '', $element->getPath()) . DIRECTORY_SEPARATOR;
		    if ( strstr($dir, Core::$settings['adminpath'].DIRECTORY_SEPARATOR ) || strstr($dir, 'backups'.DIRECTORY_SEPARATOR )) {
  				#don't archive these folders
				} else if ($element->getFilename() != '..') { // FIX: if added to ignore parent directories
				  if ($element->isDir()) {
				    // $archiv->addEmptyDir($dir);
			    } elseif ($element->isFile()) {
			        $file         = $element->getPath() .
			                        DIRECTORY_SEPARATOR  . $element->getFilename();
			        $fileInArchiv = $dir . $element->getFilename();
			        // add file to archive 
			        //echo $file." : " .$fileInArchiv."<br/>";
			        //$archiv->addFile($file, $fileInArchiv);
			        Archive::$files[] = $file;
			    }
			  }
		}


    	return true;
    }

    // function courtesy of davidwalsh.name. 
    /* creates a compressed zip file */
	public static function doBackup($destination = '',$overwrite = false) {

		$files = Archive::$files;

		//if the zip file already exists and overwrite is false, return false
		if(file_exists($destination) && !$overwrite) { return false; }
		//vars
		$valid_files = array();
		//if files were passed in...
		if(is_array($files)) {
			//cycle through each file
			foreach($files as $file) {
				//make sure the file exists
			
				if(file_exists($file)) {
					$valid_files[] = $file;
				}
			}
		}
		//if we have good files...
		if(count($valid_files)) {
			//create the archive
			$zip = new ZipArchive();
			if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
				return false;
			}
			//add the files
			foreach($valid_files as $file) {
				$zip->addFile($file,str_replace(Archive::$rootpath, "", $file));
			}
			//debug
	
			//close the zip -- done!
			$zip->close();
			
			//check to make sure the file exists
			return file_exists($destination);
		}
		else
		{
			return false;
		}
	}


	public static function doRestore($file, $dest){
		$temppath = Core::$settings['temppath']."restore_".$file;

		Archive::deleteFolder(Core::$settings['rootpath'].'addins');
		Archive::deleteFolder(Core::$settings['rootpath'].'data');
		Archive::deleteFolder(Core::$settings['rootpath'].'theme');
		Archive::deleteFolder(Core::$settings['rootpath'].'uploads');

		mkdir($temppath);
		$zip = new ZipArchive;
	    $res = $zip->open(Core::$settings['backuppath'].$file);
	    if ($res === TRUE) {
	        $zip->extractTo($temppath);
	        $zip->close();
	        Archive::copyFolder($temppath, Core::$settings['rootpath'].$dest);
	        Archive::deleteFolder($temppath);
	        return TRUE;
	    } else {
	        return FALSE;
	    }
	    
	}

	public static function copyFolder($source, $dest){
	    if(is_dir($source)) {
	        $dir_handle=opendir($source);
	        while($file=readdir($dir_handle)){
	            if($file!="." && $file!=".."){
	                if(is_dir($source."/".$file)){
	                    if(!is_dir($dest."/".$file)) mkdir($dest."/".$file);
	                    Archive::copyFolder($source."/".$file, $dest."/".$file);
	                } else {
	                    copy($source."/".$file, $dest."/".$file);
	                }
	            }
	        }
	        closedir($dir_handle);
	    } else {
	        copy($source, $dest);
	    }
	}


	public static function deleteFolder($dir) {
	   if (is_dir($dir)) 
	   {
	        $objects = scandir($dir);

	        foreach ($objects as $object) 
	        {
	            if ($object != "." && $object != "..") 
	            {
	                if (filetype($dir . "/" . $object) == "dir")
	                {
	                    Archive::deleteFolder($dir . "/" . $object); 
	                }
	                else
	                {
	                    unlink($dir . "/" . $object);
	                }
	            }
	        }

	        reset($objects);
	        rmdir($dir);
	    }
	}
}