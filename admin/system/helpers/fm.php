<?php 

class Fm {

    private $base_path = '../uploads/';
    public static $errormsg = "";

    /**
     * Prints the page.
     */
    public function index() {
        $arr = self::browse();        
        self::showFolders($arr['folders']);
        self::showFiles($arr['files']);
    }

    private function showFolders($folders){
       
        $path =  isset($_GET['path']) ? $_GET['path'] : null;
        foreach($folders as $folder){ 

            $edit = '<a href=""><i class="fa fa-fw fa-pencil"></i></a>&nbsp;';
            //$move = '<a href=""><i class="icon-arrow-right"></i></a>&nbsp;';
            $remove = '<a href=""><i class="fa fa-fw fa-trash-o"></i></a>';
            $name = '<a href="files.php?path='.$path.DIRECTORY_SEPARATOR.$folder['name'].'">'.$folder['name'].'</a>'; 
            echo '<tr>';
            echo '<td><i class="icon-folder-close"></i> '.$name.'</td>';
            echo '<td>'.$folder['size'].'</td>';
            echo '<td>'.$folder['date'].'</td>';
            echo '<td>'.$folder['perm'].'</td>';
            echo '<td>&nbsp</td>';
            echo '<td style="text-align:right">'.$edit.$remove.'</td>'; 
            echo '</tr>';
        }
    }

    public function errors(){
        return self::$errormsg;
    }

    private function showFiles($folder){
        $records = new Query("media");
        $mediaFiles = $records->get();
        foreach($folder as $file){ 

            if (self::checkIfImage($file['name'])){
                $media = '<a class="new-media" data-fileurl="'.self::getRelPath().$file['name'].'" ><i class="fa fa-fw fa-picture-o"></i></a>&nbsp;';
            } else {
                $media='';
            }
            $edit = '<a ><i class="fa fa-fw fa-pencil"></i></a>&nbsp;';
            //$move = '<a href=""><i class="icon-arrow-right"></i></a>&nbsp;';
            $remove = '<a ><i class="fa fa-fw fa-trash-o"></i></a>'; 
            echo '<tr>';
            if ($media!=''){
                echo '<td><a href="'.self::getRelPath().$file['name'].'" data-toggle="lightbox" ><i class="fa fa-fw fa-file"></i></a> '.$file['name'].'</td>';
            } else {
                echo '<td><i class="fa fa-fw fa-file"></i> '.$file['name'].'</td>';    
            }
            echo '<td>'.$file['size'].'</td>';
            echo '<td>'.$file['date'].'</td>';
            echo '<td>'.$file['perm'].'</td>';
            echo '<td>';
            if ($media!=''){
                $inMedia = Arr::arraySearch(self::getRelPath().$file['name'],$mediaFiles,'fileurl');
                if ($inMedia) {
                    echo $inMedia;
                    } else {
                        echo $media;
                } 
            } else {
                echo $media;
            }
            echo '</td>';
            
            echo '<td style="text-align:right">'.$edit.$remove.'</td>'; 
            echo '</tr>';
        }
    }

    public function getPath($flag = false){
        $path =  isset($_GET['path']) ? $_GET['path'] : null;

        $basepath = Core::$settings['rootpath'].'uploads';
        $realBase = realpath($basepath);
       
        $userpath = $basepath . $path;
        $realUserPath = realpath($userpath);
       
        if (strpos($realUserPath, $realBase ) !== 0) {
            if ($flag) { return $basepath; } else { return ''; }
        } else {
            //return str_replace($basepath, '', $realUserPath);    //Good path!
            return $realUserPath;
        }
    }

    public function showBreadcrumbs(){
        $path = self::getPath();
        $bcrumbs = explode(DIRECTORY_SEPARATOR , $path);
        $breadcrumbs = array_splice($bcrumbs,array_search('uploads', $bcrumbs)+1); 
        echo '<ul class="breadcrumb" id="breadcrumb"><li><a href="files.php">uploads</a> </li>';
        $link='';
        foreach ($breadcrumbs as $crumb){
            $link .= DIRECTORY_SEPARATOR.$crumb;
            echo '<li><a href="files.php?path='.$link.'">'.$crumb.'</a> <span class="divider">/</span></li>';
        }
        echo '</ul>';

    }

    public function getRelPath(){
        $path = self::getPath();
        $bcrumbs = explode(DIRECTORY_SEPARATOR , $path);
        $breadcrumbs = array_splice($bcrumbs,array_search('uploads', $bcrumbs)+1);
        $relPath='/uploads';
        foreach ($breadcrumbs as $crumb){
            $relPath .= '/'.$crumb;
            
        }
        return $relPath.'/';
    }


    private function success($msg) {
        self::$errormsg .= Form::showAlert('success', $msg);
    }

    private function error($msg, $exit=true) {
        self::$errormsg .= Form::showAlert('error', $msg);
    }

    /**
     * Checks path for '..' and replace spaces
     */
    private function checkPath(&$path) {
        if (strpos($path, '..') === true)
            $this->error('no .. allowed in path');
        else
            $path = str_replace(' ', '-', $path);
    }

    /**
     * Takes a given path and prints the content in json format.
     */
    public function browse() {
        // check path
        $this->checkPath($_GET['path']);
        // concat
        //$path = $this->base_path.$_GET['path'];
        $path = self::getPath(true);    
        $files = array();
        $folders = array();

        foreach (scandir($path) as $f) {
            if ($f == '.' || $f == '..' || $f == '.htaccess')
                continue;

            $e = $this->get_file_info("$path/$f", array('name', 'size', 'date', 'fileperms'));

            if (is_dir("$path/$f"))
                $folders[] = array(
                    'name' => $e['name'],
                    'size' => '---',
                    'date' => date('Y-m-d H:i:s', $e['date']),
                    'perm' => $this->unix_perm_string($e['fileperms'])
                );
            else
                $files[] = array(
                    'name' => $e['name'],
                    'size' => $this->human_filesize($e['size']),
                    'date' => date('Y-m-d H:i:s', $e['date']),
                    'perm' => $this->unix_perm_string($e['fileperms'])
                );
        }

        return array('status' => 'ok', 'folders' => $folders, 'files' => $files);
    }


    /**
     * Creates a file / folder according to type.
     */
    public function create() {
        // check path
        $this->checkPath($_POST['target']);

        // concat
        $path = self::getPath(true);
        $target = $path.DS.$_POST['target'];

        // check if dir is writeable
        if (!is_writable(pathinfo($target, PATHINFO_DIRNAME))){
         $this->error('target directory not writeable');   
        }

       
        if (!is_dir($target)) {
           if (mkdir($target))
               $this->success('directory '.$target.' created');
           else
               $this->error('mkdir failed');
        } else {
             $this->error('Folder already exists');
        }
           
       
    }

    /**
     * Move file / folder from source to destination.
     */
    public function move() {
        // check paths
        $this->checkPath($_POST['source']);
        $this->checkPath($_POST['destination']);

        // concat
        $src = $this->base_path.$_POST['source'];
        $dst = $this->base_path.$_POST['destination'];

        // check if source exists
        if (!file_exists($src))
            $this->error('source file / folder does not exist');

        // check if destination exists
        if (file_exists($dst))
            $this->error('destination file / folder already exists');

        // check if destination path exists
        if (!file_exists(pathinfo($dst, PATHINFO_DIRNAME)))
            $this->error('destination path does not exist');

        // check if source is writable
        if (!is_writable($src))
            $this->error('source file / folder is not writable');

        // check if destination path is writable
        if (!is_writable(pathinfo($dst, PATHINFO_DIRNAME)))
            $this->error('destination path is not writable');

        // move source to destination
        if (@rename($src, $dst))
            $this->success('moved file / folder');
        else
            $this->error('file / folder was not moved');
    }

    /**
     * Removes target.
     */
    public function remove() {
        // check path
        $this->checkPath($_POST['target']);

        // concat
        $target = $this->base_path.$_POST['target'];

        // check if target exists
        if (!file_exists($target))
            $this->error('target does not exist');

        // check if target is writable
        if (!is_writable($target))
            $this->error('target is not writable');

        // remove target
        if (is_dir($target))
            $result = @rmdir($target);
        else
            $result = @unlink($target);

        // check result
        if ($result)
            $this->success('target has been removed');
        else
            $this->error('target has not been removed');
    }

    public static function checkIfImage($file){
        $type = pathinfo($file, PATHINFO_EXTENSION);
        $images = array('jpg','jpeg','gif','png','tif');
        if (in_array($type, $images)){
            return true;
        } else {
            return false;
        }
    }

    /**
     * Receive uploaded files and save them at target.
     */
    public function upload() {
        // check path
        $this->checkPath($_POST['path']);

        // concat
        $path = $this->base_path.$_POST['path'];

        // check if files are set
        if (!isset($_FILES['files']['name'][0]))
            $this->error('no files uploaded');

        // restructure
        $files = array();
        foreach ($_FILES['files']['name'] as $n => $v) {
            $files[$n] = array(
                'name'     => $_FILES['files']['name'][$n],
                'type'     => $_FILES['files']['type'][$n],
                'tmp_name' => $_FILES['files']['tmp_name'][$n],
                'error'    => $_FILES['files']['error'][$n],
                'size'     => $_FILES['files']['size'][$n]
            );
        }

        // check upload state
        foreach ($files as $f)
            if ($f['error'] > 0)
                $this->error($f['name'].' was not uploaded successfully');

        // replace spaces in filename
        foreach ($files as $n => $f)
            $files[$n]['name'] = str_replace(' ', '-', $f['name']);

        // check if files already exists
        foreach ($files as $f)
            if (file_exists($path.$f['name']))
                $this->error('a file named '.$f['name'].' already exists');

        // check if path is writable
        if (!is_writable($path))
            $this->error('target path is not writable');

        // move files from tmp
        foreach ($files as $f)
            if (!move_uploaded_file($f['tmp_name'], $path.$f['name']))
                $this->error('file '.$f['name'].' was not moved from tmp to destination');

        // success
        $this->success(count($files).' files have been uploaded');
    }


    /**
     * Takes a file size in bytes and process a human readable filesize.
     */
    private function human_filesize($bytes, $decimals = 2) {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
    }

    /**
     * Taken from php.net, this function takes an permission value and builds a 
     * human_readable unix style permission string.
     */
    private function unix_perm_string($perms) {
        if (($perms & 0xC000) == 0xC000) {
            // Socket
            $info = 's';
        } elseif (($perms & 0xA000) == 0xA000) {
            // Symbolic Link
            $info = 'l';
        } elseif (($perms & 0x8000) == 0x8000) {
            // Regular
            $info = '-';
        } elseif (($perms & 0x6000) == 0x6000) {
            // Block special
            $info = 'b';
        } elseif (($perms & 0x4000) == 0x4000) {
            // Directory
            $info = 'd';
        } elseif (($perms & 0x2000) == 0x2000) {
            // Character special
            $info = 'c';
        } elseif (($perms & 0x1000) == 0x1000) {
            // FIFO pipe
            $info = 'p';
        } else {
            // Unknown
            $info = 'u';
        }

        // Owner
        $info .= (($perms & 0x0100) ? 'r' : '-');
        $info .= (($perms & 0x0080) ? 'w' : '-');
        $info .= (($perms & 0x0040) ?
            (($perms & 0x0800) ? 's' : 'x' ) :
            (($perms & 0x0800) ? 'S' : '-'));

        // Group
        $info .= (($perms & 0x0020) ? 'r' : '-');
        $info .= (($perms & 0x0010) ? 'w' : '-');
        $info .= (($perms & 0x0008) ?
            (($perms & 0x0400) ? 's' : 'x' ) :
            (($perms & 0x0400) ? 'S' : '-'));

        // World
        $info .= (($perms & 0x0004) ? 'r' : '-');
        $info .= (($perms & 0x0002) ? 'w' : '-');
        $info .= (($perms & 0x0001) ?
            (($perms & 0x0200) ? 't' : 'x' ) :
            (($perms & 0x0200) ? 'T' : '-'));

        return $info;
    }

    /**
     * Taken from code igniter. This function returns information about a given 
     * file.
     */
    private function get_file_info($file, $returned_values = array('name', 'server_path', 'size', 'date')) {
        if ( ! file_exists($file))
        {
            return FALSE;
        }

        if (is_string($returned_values))
        {
            $returned_values = explode(',', $returned_values);
        }

        foreach ($returned_values as $key)
        {
            switch ($key)
            {
                case 'name':
                    $fileinfo['name'] = substr($file,strrpos($file, "/")+1);
                    //$fileinfo['name'] = $file;
                    break;
                case 'server_path':
                    $fileinfo['server_path'] = $file;
                    break;
                case 'size':
                    $fileinfo['size'] = filesize($file);
                    break;
                case 'date':
                    $fileinfo['date'] = filemtime($file);
                    break;
                case 'readable':
                    $fileinfo['readable'] = is_readable($file);
                    break;
                case 'writable':
                    // There are known problems using is_weritable on IIS.  It may not be reliable - consider fileperms()
                    $fileinfo['writable'] = is_writable($file);
                    break;
                case 'executable':
                    $fileinfo['executable'] = is_executable($file);
                    break;
                case 'fileperms':
                    $fileinfo['fileperms'] = fileperms($file);
                    break;
            }
        }

        return $fileinfo;
    }
}
$fm = new Fm();