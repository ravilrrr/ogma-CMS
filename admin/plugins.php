<?php 

 /**
 *  ogmaCMS Plugins Admin Page
 *
 *  @package ogmaCMS
 *  @author Mike Swan / n00dles101
 *  @copyright 2013 Mike Swan / n00dles101
 *  @since 1.0.0
 *
 */

include "template/head.inc.php";
include "template/navbar.inc.php";

?>
<div class="col-md-12">


    <legend>Plugins</legend>
    
    <table class="table table-bordered table-striped table-hover ">
    <thead>
   
      <tr> 
        <th style="width:46%;"><?php echo __("DESCRIPTION"); ?></th>
        <th style="width:10%;"><?php echo __("VERSION"); ?></th>
        <th style="width:7%;"><?php echo __("STATUS"); ?></th>        
        <th style="width:7%;"><?php echo __("OPTIONS"); ?></th>
      </tr>
    </thead>
    <tbody> 
    <?php 
    foreach (Plugins::$registeredPlugins as $plugin) {


    ?>
     <tr>
        <td>
        <?php 
        echo $plugin['desc'];  
        if (file_exists(Core::$settings['pluginpath'].DS.$plugin['name'].DS.'readme.md')){
        echo '<button type="button" style="float:right;" data-readme="'.$plugin['name'].'" class="btn btn-success btn-xs markdown-readme"><span class="glyphicon glyphicon-info-sign"></span></button>';
        }
        ?>
        </td> 
        <td><?php echo $plugin['version']; ?></td>
               
        <td>
            <?php 
            if (array_key_exists($plugin['name'], (Plugins::$installedPlugins) ) && Plugins::$installedPlugins[$plugin['name']]['status']==1) {
                echo "activated";
            } else {
                echo "deactivated";
            }
            ?>

        </td>
        
        <td>
            <?php 
                
            if (array_key_exists($plugin['name'], (Plugins::$installedPlugins) ) && Plugins::$installedPlugins[$plugin['name']]['status']==1) {
                echo '<a href="plugins.php?status=deactivate&plugin='.$plugin['name'].'"><col-md- class="label label-success">De-Activate</col-md-></a>';
            } else {
                echo '<a href="plugins.php?status=activate&plugin='.$plugin['name'].'"><col-md- class="label label-info">Activate</col-md-></a>';
            }
            ?>

        </td>
    </tr>

    <?php
    }
    ?>
</tbody>
</table>
</div>

<?php 
    include "template/footer.inc.php"; 
?>