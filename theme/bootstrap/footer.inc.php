<?php if(!defined('IN_OGMA')){ die('you cannot load this page directly.'); }
/****************************************************
*
* @File:      footer.inc.php
* @Package:   GetSimple
* @Action:    Bootstrap3 for GetSimple CMS
*
*****************************************************/
?>
      <hr>

      <footer>
        <p><?php echo Theme::getSetting('footer'); ?> </p>
        <?php Actions::executeAction('bootstrap-main-footer'); ?>
      </footer>

    </div> <!-- /container -->
    
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster 
    <script src="<?php Template::getThemeUrl(true); ?>/js/jquery.min.js"></script>
    <script src="<?php Template::getThemeUrl(true); ?>/js/bootstrap.min.js"></script>
    -->
    <?php 
    Actions::executeAction('index-footer'); 

      if (Theme::getSetting('debug')){
        Debug::showConsole();
      }
    ?>
    <?php Actions::executeAction('bootstrap-before-body-close'); ?>
  </body>
</html>