</div>
<div id="deleteModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
  <div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
    <h3 id="myModalLabel"><?php echo __("DELETE"); ?> <span id="modalTable"></span> Record ?</h3>
  </div>
  <div class="modal-body">
    <p><?php echo __("DELETERECORD"); ?> '<span id="modalSlug"></span>'</p>
  </div>
  <div class="modal-footer">
  <?php 
      $tbl = Core::getTable();
      if ($tbl!=="") $tbl='tbl='.$tbl.'&amp;';
  ?>
    <form action="<?php echo Core::getFilenameId(); ?>.php?<?php echo $tbl; ?>action=deleterecord" method="post" >
    <input type="hidden" id="security-nonce" name="security-nonce" value="" />
    <input type="hidden" id="security-record" name="security-record" value="" />
    <input type="hidden" id="security-table" name="security-table" value="" />
    
    <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo __("CANCEL"); ?></button>
    <button class="btn btn-danger" id="modalButton" name="deleterecord" data-url="" ><?php echo __("DELETE"); ?></button>
  </form>
  </div>
  </div>
  </div>
</div>

<div id="modal-button" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
  <div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
    <h3 id="myModalLabel">Add Button</h3>
  </div>
  <div class="modal-body">
    <p>Form Goes here....</p>
  </div>
  <div class="modal-footer">
    <form action="<?php echo Core::getFilenameId(); ?>.php?action=deleterecord" method="post" >
    <input type="hidden" id="security-nonce" name="security-nonce" value="" />
    <input type="hidden" id="security-record" name="security-record" value="" />
    <input type="hidden" id="security-table" name="security-table" value="" />
    
    <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo __("CANCEL"); ?></button>
    <button class="btn btn-danger" id="modalButton" name="deleterecord" data-url="" ><?php echo __("DELETE"); ?></button>
  </form>
  </div>
  </div>
  </div>
</div>



<div id="modal-readme" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-readme" aria-hidden="true">
  <div class="modal-dialog">
  <div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
    <h3 id="modal-readme-label"></h3>
  </div>
  <div id="modal-readme-body" class="modal-body">
    
  </div>
  <div class="modal-footer">
    <form action="#" method="post" >
    <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo __("CLOSE"); ?></button>
  </form>
  </div>
  </div>
  </div>
</div>

<?php 
if ($action=="edit" or $action=="create"){
?>
<div id="fullscreenedit">
  <div id="editortoolbar">
  <?php echo Ogmaeditor::displayToolbar('editorarea', true, true); ?>
  <input id="editorslug" type="hidden" value="index" />
  </div>
  <div id="editwrapper">
    <div id="editor">
      <textarea id="editorarea">

      </textarea>
    </div>

    <div id="preview">
      
    </div>
  </div>
</div>

<div id="shortcodeModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="shortcodeModal" aria-hidden="true">
  <div class="modal-dialog">
  <div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
    <h3 id="myModalLabel"><?php echo __("INSERTSHORTCODE"); ?></h3>
  </div>
  <div class="modal-body">
  <div class="form-group">
    <label for="exampleInputEmail1"><?php echo __("SELECTSHORTCODE"); ?></label>
    <?php echo Ogmaeditor::addShortcodeDropdown(); ?>
  </div>
   <div class="form-group">
    <label for="exampleInputEmail1"><?php echo __("SHORTCODE"); ?></label>
    <textarea id="shortcodeTxt"class="form-control"> </textarea>
  </div>
   
  </div>
  <div class="modal-footer">
    <!--<form action="javascript:void();" method="post" > -->
    <input type="hidden" id="shortcode_area"  value="" />
   
    <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo __("CLOSE"); ?></button>
    <button class="btn btn-success" id="addshortcodebtn" name="addshortcodebtn" data-url="" ><?php echo __("INSERT"); ?></button>
  <!-- </form> -->
  </div>
  </div>
  </div>
</div>


<?php 
}
?>