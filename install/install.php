<?php 

 /**
 *  ogmaCMS Installer
 *
 *  @package ogmaCMS
 *  @author Mike Swan / n00dles101
 *  @copyright 2013 Mike Swan / n00dles101
 *  @since 1.0.0
 *
 */

session_start();
session_destroy();
define('DS', DIRECTORY_SEPARATOR);
define('IN_OGMA', true);
// Load Core file
require_once( 'install.class.php');
require_once( '..' . DS .  'admin' . DS . 'system' . DS . 'core.php');
$core = new Core();
$alert = '';
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title><?php echo htmlentities(__("OGMAINSTALLER"), ENT_QUOTES, "UTF-8"); ?></title>

    <link href="/3rdparty/bootstrap3/css/bootstrap.css" rel="stylesheet">
    <link href="/admin/template/css/ogmalogin.css" rel="stylesheet">
    <script type="text/javascript" src="/3rdparty/jquery/jquery.min.js" ></script>
    <script type="text/javascript" src="/3rdparty/bootstrap3/js/bootstrap.js" ></script>
    <script type="text/javascript" src="/admin/template/js/jquery.validation.js" ></script>

    <script type="text/javascript">
    $(document).ready(function() {
      $('form.required-form').simpleValidate({
        errorElement: 'em',
        errorText: '<?php echo __("REQUIREDFIELD"); ?>',
        emailErrorText: '<?php echo __("INVALIDEMAIL"); ?>',
        })
      });
    </script>

<script>

jQuery(document).ready(function () {
  $('#language').on('change',function(){
    window.location = "install.php?setlang="+$( "#language option:selected" ).val();
  })
})
</script>


</head>
<body>



<div class="col-md-6 col-md-offset-3">
    <form method="post" id="login-form" class="well required-form" role="form" >
    <?php 
      error_reporting(E_ALL | E_STRICT); 
      $installer = new Install();
      $installer->init();
    ?>
    </form>
  </div>



</body>
</html>