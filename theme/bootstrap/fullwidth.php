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
        <div class="col-md-12">
          <?php $page->getContent(); ?>
        </div>
      </div>

<?php include('footer.inc.php'); ?>
