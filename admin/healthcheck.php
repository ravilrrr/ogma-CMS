<?php 

 /**
 *	ogmaCMS Healthcheck Admin Page
 *
 *	@package ogmaCMS
 *	@author Mike Swan / n00dles101
 *	@copyright 2013 Mike Swan / n00dles101
 *	@since 1.0.0
 *
 */
	include "template/head.inc.php";
	include "template/navbar.inc.php";
 ?>
		<div class="col-md-10">
			
			<legend><?php echo __("VIEWHEALTHCHECK"); ?></legend>

 			<table class="table table-bordered table-striped table-hover">
		    <thead>
		   
		      <tr>
		      	<th><?php echo __("MODULE"); ?></th>
		        <th><?php echo __("STATUS"); ?></th>
		        <th><?php echo __("RESULT"); ?></th>
		      </tr>


		    </thead>
		    <tbody> 

			<?php 
			if (function_exists('apache_get_version')){
				$version = apache_get_version();
			} else {
				$version = "Non-Apache";
			}
			echo "$version\n";
			echo 'Current PHP version: ' . phpversion();
			$php_modules = get_loaded_extensions();

			$requredModules=array(
				'curl'=>'cURL Module',
				'gd'=>'GD Library',
				'zip'=>'ZipArchive Module',
				'SimpleXML'=>'SimpleXML Module',
				);

		
					foreach ($requredModules as $module=>$name) {

						

						if  (in_array($module, $php_modules)) {
							echo '<tr><td >'.$name.'</td><td>'.__("INSTALLED").'</td><td><span class="label label-info">'.__("OK").'</span></td></tr>';
						} 
						else {
							echo '<tr><td >'.$name.'</td><td><span class="WARNmsg">'.__("NOTINSTALLED").'</span></td><td><span class="label label-warning">'.__("WARNING").'</span></td></tr>';
						}
							
					}
			?>
			
			 </tbody>
		  </table>


		</div>
	</div>
<?php 
	include "template/footer.inc.php"; 
?>