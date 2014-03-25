<?php if(!defined('IN_OGMA')){ die('you cannot load this page directly.'); }
/****************************************************
*
* @File:      header.inc.php
*
*****************************************************/

$SelectedTheme = Theme::getSetting('theme'); 

$NavBarStyle =  'navbar-default';

Scripts::add(Template::getThemeUrl(false).'/js/jquery.min.js',"frontend",0, array(), true);
Scripts::add(Template::getThemeUrl(false).'/js/bootstrap.min.js',"frontend",0 , array(), true);

?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php Template::getThemeUrl(true); ?>/ico/favicon.png">
    
    <?php $page->get_header(); ?>
    
    <!-- Bootstrap core CSS -->
    <link href="<?php Template::getThemeUrl(true); ?>/css/bootstrap_<?php echo $SelectedTheme; ?>.min.css" rel="stylesheet" class="SelectedTheme">

    <!-- Custom styles for this template -->
    <link href="<?php Template::getThemeUrl(true); ?>/css/Bootstrap3.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="<?php Template::getThemeUrl(true); ?>/js/html5shiv.js"></script>
      <script src="<?php Template::getThemeUrl(true); ?>/js/respond.min.js"></script>
    <![endif]-->
   
</head>
  <body id="<?php echo $id; ?>">
    <div class="navbar <?php echo $NavBarStyle; ?> navbar-fixed-top" id="NavBar">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php Template::getSiteUrl(true); ?>"><?php Template::get_site_name(); ?></a>
        </div>
        <div class="collapse navbar-collapse">
          
        <?php 
          $menu = new Bootstrap(); 
          $menu->bootStrapMenu('main','footer');
        ?>
          
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class="container">
