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

$resetting = false;

if (!Core::isInstalled()){
   header('location: ../install/install.php');
    exit;
}

$error = '';

if (isset($_POST['resetbtn'])){
  $username=strip_tags($_POST['username']);
  $login=new Query('users');
  $records = $login->getCache()->find('username = '.$username)->top(1)->get();
  if (count($records)==1){
    $userid = $records[0]['id'];
    $name = $records[0]['firstname'];
    $email=$records[0]['email'];
    if ($name=='') $name = $records[0]['username'];
    $userinfo = $login->getFullRecord($userid);
    $userinfo['reset'] = Security::genKey(40); 

    $url = Utils::myUrl()."admin/resetpassword.php?token=".$userinfo['reset'];
    $ret = file_put_contents(Core::$settings['rootpath'] . '/data/users/'.$userid.'.xml', Xml::arrayToXml($userinfo));
    $login->generateCacheFile();
    $mailer = new Mailer();
    $mailer->sendmail($email,__("PASSWORDRESET"), __("RESETEMAIL",array(':user'=>$name,':url'=>$url,':site'=>Core::$site['sitename']) ) );
    $error = Form::showAlert('success', __("RESETSUCCESS"));
  } 
}

if (isset($_GET['token']) && strlen($_GET['token'])==40){
  $resetting = true;
  $token = trim($_GET['token']);
  $users=new Query('users');
  $records = $users->getCache()->get();
  $reset = false;
  //print_r($records);
  foreach ($records as $record) {
    if (strcmp((string)$record['reset'],(string)$token)==0){
      $reset = true;
    }
  }
  if ($reset == false){
     $resetting = false;
     $error = Form::showAlert('warning', __("OUTDATEDLINK"));
  }
}

if (isset($_POST['changebtn'])){
    if ($_POST['password']==$_POST['password2']){
        $token = $_POST['token'];
        $users=new Query('users');
        $records = $users->getCache()->get();
        $reset = false;
        foreach ($records as $record) {
          if (strcmp((string)$record['reset'],(string)$token)==0){
            $reset = true;
            $userid = $record['id'];
          }
        }
        if ($reset==true){
          $userinfo = $users->getFullRecord($userid);
          print_r($userinfo);
        }
    } else {
         $resetting = true;
          $error = Form::showAlert('warning', __("PASSWORDNOMATCH"));
    }
} 


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>OGMA - Reset Password</title>
    <meta name="description" content="">
    <meta name="author" content="">

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
    </script>
    <?php 
      echo '<script type="text/javascript">';
      echo 'var i18n = '.json_encode (Lang::$language[Core::$site['language']]).';';
      echo '</script>';
    ?>
</head>
<body>

<?php 
if ($resetting){

?>

<div class="col-md-4 col-md-offset-4">
    <form method="post" id="login-form" class="well" role="form">
      <h1>PASSWORD RESET</h1>
      <p class="subheading"><?php echo __("CHANGEPWINFO"); ?></p>
      <div class="form-group">
        <label><?php echo __('PASSWORD'); ?> *</label>
        <input type="password" name="password" id="password" class="form-control required">     
      </div>
      <div class="form-group">
        <label><?php echo __('PASSWORDAGAIN'); ?> *</label>
        <input type="password" name="password2" id="password2" class="form-control required">     
      </div>
      <p id='validate-status'>&nbsp;</p>
      <input type="hidden" name="token" id="user-tf" value="<?php echo $token; ?>">
      <button type="submit" name="changebtn"  id="btn-login" class="btn btn-primary">
      <span class="fa fa-lock fa-sm"></span>&nbsp;<?php echo __("RESET"); ?></button>
      
      <div class="clear-fix"></div>
      
      <hr>
      <div class="btm-links"><a href="index.php" id="forgot-password"><?php echo __("BACKTOLOGIN"); ?></a></div>
    </form>
      <?php 
        echo $error;
      ?>
  </div>

<script type="text/javascript">

  $(document).ready(function() {
    $("#password2").keyup(validate);
  });


  function validate() {
    var password1 = $("#password").val();
    var password2 = $("#password2").val();

      if(password1 != password2) {
        $("#validate-status").html("<span style='color:red;'>"+i18n['PASSWORDNOMATCH']+"</span>");  
      } else {
        $("#validate-status").html("<span style='color:green;'>"+i18n['PASSWORDMATCH']+"</span>");  
      }

      if (password1.lenght <6 ){
        $("#validate-status").html("<span style='color:green;'>"+i18n['PASSWORDTOSMALL']+"</span>");
      }
      
  }
  </script>




<?php 
} else {
?>

<div class="col-md-4 col-md-offset-4">
    <form method="post" id="login-form" class="well" role="form">
      <h1>OGMA CMS</h1>
      <p class="subheading"><?php echo __("RESETPWINFO"); ?></p>
      <div class="form-group">
        <label><?php echo __('USERNAME'); ?> *</label>
        <input type="text" name="username" id="user-tf" class="form-control required">     
      </div>

      <button type="submit" name="resetbtn"  id="btn-login" class="btn btn-primary"><span class="fa fa-lock fa-sm"></span>&nbsp;<?php echo __("RESET"); ?></button>
      
      <div class="clear-fix"></div>
      
      <hr>
      <div class="btm-links"><a href="index.php" id="forgot-password"><?php echo __("BACKTOLOGIN"); ?></a></div>
    </form>
  <?php 
        echo $error;
      ?>
  </div>

<?php 
}
?>

</body>
</html>