<?php 
session_start();
define('DS', DIRECTORY_SEPARATOR);
define('IN_OGMA', true);

// Load Core file
require_once( '..' . DS . 'config.php');
require_once('system' . DS . 'core.php');
	
define('ROOT', Core::getRootPath());
  
$siteSettings = new Core();

$plugins = new Plugins();
$action = '';

Session::set('url', Core::curPageURL());

if (User::isLoggedIn()==false){
    header('location: index.php');
    exit;
}

if (isset($adminOnly) && !User::isAdmin()) {
    header ("location: error.php");
    exit;
} 	

if (!User::hasPerms(Core::getFilenameId()) && Core::getFilenameId()!="error") {
    header ("location: error.php");
    exit;
} 


?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="dns-prefetch" href="<?php echo Core::$site['siteurl']; ?>">
    <?php 
    if (Core::$site['cdn']){
      echo '<link rel="dns-prefetch" href="//ajax.googleapis.com">';
      echo '<link rel="dns-prefetch" href="//netdna.bootstrapcdn.com">';
    }
    ?>

    <title><?php echo Core::$site['sitename']; ?> - Admin</title>
   	
    <?php Actions::executeAction('admin-header'); ?> 
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <script type="text/javascript">
    $(document).ready(function() {
      $('form.required-form').simpleValidate({
        errorElement: 'em',
        errorText: '<?php echo __("REQUIREDFIELD"); ?>',
        emailErrorText: '<?php echo __("INVALIDEMAIL"); ?>',
        numericErrorText: '<?php echo __("INVALIDNUMERIC"); ?>',
        urlErrorText: '<?php echo __("INVALIDURL"); ?>'       
        })

      $('.askconfirm').jConfirmAction({
        question: '<?php echo __("DELETE"); ?>',
        yesAnswer: '<?php echo __("YES"); ?>',
        cancelAnswer: '<?php echo __("NO"); ?>'
      });
    });




    </script>
    <?php 
      echo '<script type="text/javascript">';
      echo 'var i18n = '.json_encode (Lang::$language).';';
      echo '</script>';
    ?>
  </head>
 <body>
