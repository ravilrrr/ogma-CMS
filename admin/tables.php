<?php 

 /**
 *    ogmaCMS Tables Admin Page
 *
 *    @package ogmaCMS
 *    @author Mike Swan / n00dles101
 *    @copyright 2013 Mike Swan / n00dles101
 *    @since 1.0.0
 *
 */
include "template/head.inc.php";
include "template/navbar.inc.php";

$action = Core::getAction();            // get URI action
$id = Core::getID();                    // get page ID


if ($action=='edit'){
$table = $_GET['id'];
$matrix = new Query($table);
$options =  $matrix->tableOptions;
$fields = $matrix->tableFields;
$fieldtype = Form::getFields();
?>
<div class="col-md-10">
    <legend>Edit Table : <?php echo $id; ?></legend>
</div>

    <div  class="col-md-12">


    <table class="table table-bordered table-condensed table-striped table-hover">
      <thead>
         <tr>
          <th><?php echo __("NAME"); ?></th>
          <th style="width:70px;"><?php echo __("TYPE"); ?></th>
        </tr>
      </thead>
      
      <?php foreach($fields as $field=>$type){ ?>
          <tr>
                  <td><?php echo $field; ?></td>

                  <td><?php echo $type; ?></td>
                 
          </tr>
      <?php } ?>
    </table>
    </div>  



<?php 
}

if ($action=='view'){
    $tables=Query::getTables(true);
    ?>
    <div class="col-md-12">
    <legend><?php echo __("TABLES"); ?></legend>
  <table class="table table-bordered table-striped table-hover">
    <thead>
   
      <tr>
        <th><?php echo __("NAME"); ?></th>
        <th style="width:100px;"><?php echo __("TYPE"); ?></th>
        <th style="width:100px;"><?php echo __("OPTIONS"); ?></th>
      </tr>


    </thead>
    <tbody> 
    <?php 
     foreach($tables as $table){ 
      
    ?>
      <tr>
        <td><?php echo $table['name']; ?></td>
        <td><?php echo $table['private']; ?></td>
        <td>
        
        <div class="btn-group">
         <button class="btn" onclick="location.href='tables.php?action=edit&amp;id=<?php echo $table['name']; ?>'"><?php echo __("EDIT"); ?></button>
        </div>
        
        </td>
      </tr>
    <?php 
    }
    ?>

    </tbody>
  </table>
    
    </div>
<?php
} 




?>



<?php 
    include "template/footer.inc.php"; 
?>