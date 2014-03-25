<?php 

 /**
 *  ogmaCMS Main Admin / Login Page
 *
 *  @package ogmaCMS
 *  @author Mike Swan / n00dles101
 *  @copyright 2013 Mike Swan / n00dles101
 *  @since 1.0.0
 *
 */


session_start();
define('DS', DIRECTORY_SEPARATOR);
define('IN_OGMA', true);
// Load Core file

require_once( '..' . DS . 'config.php');
require_once(  'system' . DS . 'core.php');
$core = new Core();
//$plugins = new Plugins();


if (!Core::isInstalled()){
   header('location: ../install/install.php');
    exit;
}

$alert = '';
if (isset($_GET['auth']) && $_GET['auth']=='logout'){
    Debug::addUpdateLog(User::getUsername()." logged out.",User::getUsername(), date('U'));
    Session::stopSession();
    Session::startSession();
}

if (isset($_POST['loginbtn'])){
    $username=strip_tags($_POST['username']);
    $password=strip_tags($_POST['password']);
    $login=new User();
    $login->login($username, $password);
    if (Session::get('authenticated') == false){
        Core::addAlert('<div class="alert alert-warning"><strong>Warning!</strong> '.__("LOGINFAIL").'</div>');
    }
}

if (Session::get('authenticated') == true){
          header("location: dashboard.php");       
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>OGMA Login</title>
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv="X-FRAME-OPTIONS" content="DENY">
    <link href="../3rdparty/bootstrap3/css/bootstrap.min.css" rel="stylesheet">
    <link href="../3rdparty/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="template/css/ogmalogin.css" rel="stylesheet">
    <script type="text/javascript" src="../3rdparty/jquery/jquery.min.js" ></script>
    <script type="text/javascript" src="../3rdparty/bootstrap3/js/bootstrap.js" ></script>
    <script type="text/javascript" src="template/js/jquery.validation.js" ></script>

<script>
jQuery(document).ready(function () {
    $('#login-form').simpleValidate({
          errorElement: 'em',
          errorText: '<?php echo __("REQUIREDFIELD"); ?>',
          emailErrorText: '<?php echo __("INVALIDEMAIL"); ?>',
          numericErrorText: '<?php echo __("INVALIDNUMERIC"); ?>',
          urlErrorText: '<?php echo __("INVALIDURL"); ?>'       
          });
  })

jQuery(document).ready(function () {
  $('#language').on('change',function(){
    window.location = "index.php?setlang="+$( "#language option:selected" ).val();
  })
})
</script>


</head>
<body>

<div class="col-md-4 col-md-offset-4">
    <form method="post" id="login-form" class="well" role="form">
      <h1>OGMA CMS</h1>
      <p class="subheading"><?php echo __("SIGNIN"); ?></p>
      <div class="form-group">
        <label><?php echo __('USERNAME'); ?> </label>
        <input type="text" name="username" id="user-tf" class="form-control required">
      </div>

      <div class="form-group">
        <label ><?php echo __('PASSWORD'); ?></label>
        <input type="password" name="password" id="pass-tf" class="form-control required" autocomplete="off">
      </div>

      <div class="form-group">
      <label ><?php echo __('LANGUAGE'); ?></label>

        <?php 

        $curLang =  Lang::getCurrentLanguage(); 
        $installedLang = Lang::getInstalledLanguages();

        ?>
        <select type="select" name="language" id="language" class="form-control">
          <?php foreach ($installedLang as $language) {
            echo "<option value='$language' ";
            if (Lang::$langnames[$language]==$curLang) echo "selected "; 
            echo ">".Lang::$langnames[$language]."</option>";
          }
          ?>
        </select>
      </div>
      <?php
        Core::getAlerts();
      ?>

      <button type="submit" name="loginbtn"  id="btn-login" class="btn btn-primary"><span class="fa fa-lock fa-sm"></span>&nbsp;<?php echo __("LOGIN"); ?></button>
      
      <div class="clear-fix"></div>
      <hr>
      <div class="btm-links"><a href="resetpassword.php" id="forgot-password"><?php echo __("FORGOTPWD"); ?></a></div>
    </form>
  </div>

  

</body>
</html>