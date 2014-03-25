<?php if(!defined('IN_OGMA')){ die('you cannot load this page directly.'); }
/****************************************************
*
* @File:      template.php
* @Package:   GetSimple
* @Action:    Bootstrap3 for GetSimple CMS
*
*****************************************************/
?>
<?php include('header.inc.php'); ?>
      <div class="row">
        <div class="col-md-8">
        
          <?php $page->getContent(); ?>
        </div>
        
        <div class="col-md-4">
          <?php Actions::executeAction('bootstrap-main-sidebar'); ?>
        </div>
      </div>

<?php include('footer.inc.php'); ?>
