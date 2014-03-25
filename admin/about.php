<?php 

 /**
 *	ogmaCMS About Admin Page
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
		<div class="col-md-12">
			
		<legend><?php echo __("ABOUT"); ?></legend>

		<?php 
		$ogmaForm = new Form();

	    $ogmaForm->startTabHeaders();

	    $ogmaForm->createTabHeader(array('about'=>__("ABOUT")),true);
	    $ogmaForm->createTabHeader(array('health'=>__("HEALTHCHECK")));
	    $ogmaForm->createTabHeader(array('credits'=>__("CREDITS")));
	    $ogmaForm->createTabHeader(array('license'=>__("LICENSE")));

	    $ogmaForm->endTabHeaders();

	    $ogmaForm->startTabs();

	    $ogmaForm->createTabPane('health',false);

	    $output = '<h3>'.__("HEALTHCHECK").'</h3>';
		$output .= '<table class="table table-bordered table-striped table-hover">';
	    $output .= '<thead>';
	    $output .= '  <tr>';
	    $output .= '  	<th>'.__("MODULE").'</th>';
	    $output .= '    <th>'.__("STATUS").'</th>';
	    $output .= '    <th>'.__("RESULT").'</th>';
	    $output .= '  </tr>';
	    $output .= '</thead>';
	    $output .= '<tbody> ';

		if (function_exists('apache_get_version')){
			$version = apache_get_version();
		} else {
			$version = "Non-Apache";
		}
		//echo "$versi7on\n";
		//echo 'Current PHP version: ' . phpversion();
		$php_modules = get_loaded_extensions();

		$requredModules=array(
			'curl'=>'cURL Module',
			'gd'=>'GD Library',
			'zip'=>'ZipArchive Module',
			'SimpleXML'=>'SimpleXML Module',
			);

			foreach ($requredModules as $module=>$name) {
				if  (in_array($module, $php_modules)) {
					$output .= '<tr><td >'.$name.'</td><td>'.__("INSTALLED").'</td><td><col-md- class="label label-info">'.__("OK").'</col-md-></td></tr>';
				} 
				else {
					$output .= '<tr><td >'.$name.'</td><td><col-md- class="WARNmsg">'.__("NOTINSTALLED").'</col-md-></td><td><col-md- class="label label-warning">'.__("WARNING").'</col-md-></td></tr>';
				}	
			}
			
			
		$output .= ' </tbody>';
		$output .= '</table>';

		$ogmaForm->output($output);

		$ogmaForm->createTabPane('about',true);
		$output = '<h3>'.__("ABOUT").'</h3>';
		$ogmaForm->output($output);

		$ogmaForm->createTabPane('credits',false);
		$output = '<h3>'.__("CREDITS").'</h3>';
		$output .= 'OGMA CMS was made possible with the help of these great scripts: <br/><br/>';
		$output .= 'GetSimple CMS - http://get-simple.info <br/>';
		$output .= 'Bootstrap Datepicker by Stefan Petre - http://www.eyecon.ro/bootstrap-datepicker <br/>';
		$output .= 'Bootstrap Toggle by Min HUr - The New York Times Company <br/>';
		$output .= 'Markdown Extra by Michel Fortin - http://michelf.ca/projects/php-markdown/ <br/>';
		$output .= 'Markdown by John Gruber - http://daringfireball.net/projects/markdown/  <br/>';
		$output .= 'PHP Error by Joseph Lenton - http://phperror.net/   <br/>';
		$output .= 'Bootstrap by Twitter - http://getbootstrap.com <br/>';
		$output .= 'Font Awesome by Dave Gandy - http://fontawesome.io <br/>';
		$output .= 'Bigmodal by Andrew Rowls - https://github.com/eternicode/bootstrap-bigmodal <br/>';
		$output .= 'jQuery by The jQuery Foundation - http://jqeury.com <br/>';
		$output .= 'Bootstrap Tags by Francois Deschenes - https://github.com/fdeschenes/bootstrap-tag <br/>';
		$output .= 'jConfirm by Hidayat Sagita - http://www.webstuffshare.com <br/>';
		$output .= 'jQuery MixItUp by Patrick Kunka - http://www.mixitup.io <br/>';
		$output .= 'jQuery Nestable by David Bushell - http://dbushell.com/ <br/>';
		$output .= 'jQuery SpinEdit';
		$output .= 'jQuery Validation by Trevor Davis - http://trevordavis.net <br/>';
		$output .= 'Rangy Inputs by Tim Down - http://code.google.com/p/rangy/ <br/>';
		$output .= 'jQuery-typing by Maciej Konieczny - http://narf.pl/jquery-typing/';

		$ogmaForm->output($output);

		$ogmaForm->createTabPane('license',false);
		$output = '<h3>'.__("LICENSE").'</h3>';
		$ogmaForm->output($output);

		if (file_exists('..'.DS.'LICENSE.txt')){
			$license = file_get_contents('..'.DS.'LICENSE.txt');
		} else {
			$license = 'Unable to find License file...';
		}

		$ogmaForm->displayField('post-license','', 'textarea', '30', $license);
    
		$ogmaForm->endTabs();

	    $ogmaForm->endForm();

    	$ogmaForm->show();
		?>

		</div>

<?php 
	include "template/footer.inc.php"; 
?>