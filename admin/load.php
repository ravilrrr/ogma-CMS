<?php 
include "template/head.inc.php";
include "template/navbar.inc.php";

		$plugin = Core::getTable();
		if (method_exists($plugin, 'admin')){
			$plugin::admin();
		}
	include "template/footer.inc.php"; 
?>