<?php 

 /**
 *	ogmaCMS Installer Page
 *
 *	@package ogmaCMS
 *	@author Mike Swan / n00dles101
 *	@copyright 2013 Mike Swan / n00dles101
 *	@since 1.0.0
 *
 */


class Install {

	// minimum version of PHP required. 
	const MIN_PHP_VERSION = "5.2.4";

	protected $Errors = 0; 

	public function init() {
		// check if installer has already been run ? 
		if (!Core::isInstalled()){

			if(isset($_POST['step'])) switch($_POST['step']) {

				case 1: $this->checkRequirements(); 
					break;

				case 2: $this->siteInfo(); 
					break;

				case 3: $this->userInfo(); 
					break;

				case 4: $this->userInfoSave(); 
					break;

				default: 
					$this->welcome();

			} else {
				$this->welcome();
			}

		} else {
			$this->alreadyInstalled();
		}
	}

	protected function alreadyInstalled(){
		$this->h1("OGMA ". __("INSTALLER")); 
		$this->p(__("ALREADYINSTALLED"));
		$this->linkButton(__('SHOWWEBSITE'), Utils::myUrl()); 
	}

	protected function welcome() {
		$this->h1("OGMA ". __("INSTALLER")); 
		$this->p(__("THANKSFORCHOOSING"));
		echo '<label>'. __('SELECTLANGUAGE').'</label>';


      $curLang =  Lang::getCurrentLanguage(); 
      $installedLang = Lang::getInstalledLanguages();

     
      echo '<select type="select" name="language" id="language" class="form-control ">';
        foreach ($installedLang as $language) {
          echo "<option value='$language' ";
          if (Lang::$langnames[$language]==$curLang) echo "selected "; 
          echo ">".Lang::$langnames[$language]."</option>";
        }
      
      	echo ' </select>';
		$this->btn(__("GETSTARTED"), 1); 
	}


	protected function checkRequirements(){
		$this->h1(__("INSTALLSTEP")." 1"); 
		$this->h2("Requirements"); 

		if(version_compare(PHP_VERSION, self::MIN_PHP_VERSION) >= 0) {
			$this->success(__("PHPVERSION",array(":version"=>PHP_VERSION)));
		} else {
			$this->error(__("PHPVERSION",array(":version"=>self::MIN_PHP_VERSION, ":yourversion"=>PHP_VERSION)) );
		}


		$php_modules = get_loaded_extensions();

		$requredModules=array(
		'zip'=>'ZipArchive Module',
		'SimpleXML'=>'SimpleXML Module',
		);
		foreach ($requredModules as $module=>$name) {
			if  (in_array($module, $php_modules)) {
				$this->success(__("MODULEINSTALLED",array(":module"=>$module)));
			} 
			else {
				$this->error(__("MODULEMISSING",array(":module"=>$module)));
			}		
		}

		$nicetohaveModules=array(
		'curl'=>'cURL Module',
		'gd'=>'GD Library'
		);
		foreach ($nicetohaveModules as $module=>$name) {
			if  (in_array($module, $php_modules)) {
				$this->success(__("MODULEINSTALLED",array(":module"=>$module)));
			} 
			else {
				$this->warning(__("MODULEMISSING",array(":module"=>$module)));
			}		
		}

		if(is_writable("../data/website.xml")) $this->success(__("WEBSITEWRITABLE")); 
			else $this->error(__("WEBSITENOTWRITABLE")); 
		
		if(is_writable("../sitemap.xml")) $this->success(__("SITEMAPWRITABLE")); 
			else $this->error(__("SITEMAPNOTWRITABLE")); 

		if(Utils::isRemoveable("../data/")) $this->success(__("DATAWRITABLE")); 
			else $this->error(__("DATANOTWRITABLE")); 
		$this->hiddenInput('post-language',$_POST['language']);
		if($this->Errors) {
			$this->p(__("ERRORSFOUND"));
			$this->btn(__("CHECKAGAIN"), 1, "glyphicon glyphicon-refresh icon-white"); 
			$this->btn(__("IGNORE"), 2); 
		} else {
			$this->btn(__("NEXTBUTTON"), 2); 
		}

		

	}

	protected function siteInfo(){
		$siteSettings = Xml::xml2array(ROOT . '/data/website.xml');
		$this->h1(__("INSTALLSTEP")." 2"); 
		$this->h2(__("SITEINFO")); 
		$this->input("post-sitename",__("SITENAME"),$siteSettings['sitename'],false,true);
		$this->input("post-siteurl",__("SITEURL"),Utils::myUrl(),false,true);
		$this->dropdown('post-timezone',__("TIMEZONE"),  Lang::$timezones, $siteSettings['timezone']);
		$this->dropdown('post-dateformat',__("DATEFORMAT"),  Lang::$dateformats , $siteSettings['dateformat']);
		$this->input("post-salt",__("SALT"),Security::genKey(32),true,true);
		$this->hiddenInput('post-language',$_POST['post-language']);
		$this->btn(__("NEXTBUTTON"), 3); 
	}
	
	protected function userInfo(){
		$siteSettings = Xml::xml2array(ROOT . '/data/website.xml');
		$_POST['post-siteurl'] = Utils::addTrailingSlash($_POST['post-siteurl']);
		foreach ($siteSettings as $item=>$val){
			if (isset($_POST['post-'.$item])) {
				$siteSettings[$item]=$_POST['post-'.$item];
			}
		}
		$ret = file_put_contents(Core::$settings['rootpath'] . '/data/website.xml', Xml::arrayToXml($siteSettings));
		if ($ret){
			$this->success(__("WEBSETTINGSSAVED"));
		} else {
			$this->error(__("UNABLETOSAVE"));
		}
		$this->h1(__("INSTALLSTEP")." 3"); 
		$this->h2(__("ADMINUSERINFO")); 
		$this->input("post-username",__("USERNAME"),"admin",false,true);
		$this->input("post-firstname",__("FIRSTNAME"),"",false,false);
		$this->input("post-lastname",__("LASTNAME"),"",false,false);
		$this->input("post-password",__("PASSWORD"),"",false,true,'password');
		$this->input("post-email",__("EMAIL"),"",false,true,"text","email");
		$this->input("post-role",__("ROLE"),"admin",true);
		$this->hiddenInput('post-perms','');
		$this->hiddenInput('post-reset','');
		$this->hiddenInput('post-id','0');
		$this->hiddenInput('post-salt',Security::genKey(20));
		$this->hiddenInput('post-language', $_POST['post-language'] );
		
		$this->btn(__("NEXTBUTTON"), 4); 
	}

	protected function userInfoSave(){
		$tempXml = array();

		$_POST['post-password']=$_POST['post-salt'].$_POST['post-password'];
		$_POST['post-role'] = 'admin';
		$user = new Query("users");
		$user->getCache();
		$ret = $user->addRecordForm();
		if ($ret){
			$this->success(__("USERINFOSAVED"));
		} else {
			$this->error(__("USERINFOERROR"));
		}

		$this->h1(__("CONGRATS"));
		$this->p(__("SYSTEMINSTALLED"));
		$this->linkButton(__("SHOWWEBSITE"), Utils::myUrl(),"glyphicon glyphicon-home icon-white"); 
		$this->linkButton(__("LOGINTOADMIN"), Utils::myUrl().'admin', "glyphicon glyphicon-log-in icon-white"); 

		$xml=Xml::arrayToXml($tempXml);
        $ret =  file_put_contents(ROOT . DS . 'data' . DS. 'installed.xml', $xml);

        $siteSettings = Xml::xml2array(ROOT . '/data/website.xml');
		$siteSettings['email']=$_POST['post-email'];
		$ret = file_put_contents(Core::$settings['rootpath'] . '/data/website.xml', Xml::arrayToXml($siteSettings));


	}


	protected function dropdown($name, $label, $options,  $value){
		echo  '<div class="form-group">';
        echo  '    <label for="'.$name.'">'.$label.'</label>';
        echo  '      <select  id="'.$name.'" name="'.$name.'" class="form-control" >';
        if (!Arr::isAssoc($options)){
	        foreach($options as $item){
	        	echo  '        <option value="'.($item).'"';
	        	if (($value)==($item)) echo  " selected ";
		        echo '>'.$item.'</option>';	
	        }
		} else {

			foreach($options as $item=>$value2){
	        	echo  '        <option value="'.($item).'"';
	        	if (($value)==($item)) echo  " selected ";
		        echo '>'.$value2.'</option>';	
	        }
        }

        echo  '      </select>';
        echo  '  </div>';
	}

	protected function input($name, $label, $value, $disabled = false, $required = false, $type="text", $class=""){
		echo '<div class="form-group">';
		if ($required){
			$fLabel = $label . " (*)"; 
		} else {
			$fLabel=$label;
		}
		echo  '<label  for="'.$name.'">'.$fLabel.'</label>';
		echo '	<input '; 
		if ($disabled) echo " readonly ";
		echo ' type="'.$type.'" id="'.$name.'" name="'.$name.'" placeholder="'.$label.'"  class="form-control'; 
		if ($required) echo " required ";
		if ($class!="") echo $class; 
		echo '" value="'.$value.'">';
		echo '</div>';
	}

	protected function hiddenInput($name, $value){
		echo '<input type="hidden" id="'.$name.'" name="'.$name.'" class="form-control" value="'.$value.'">';
	}



	/**
	 * Check if the given function $name exists and report OK or fail with $label
	 *
	 */
	protected function checkFunction($name, $label) {
		if(function_exists($name)) $this->ok("OK: $label"); 
			else $this->err("Fail: $label"); 
	}

	/**
	 * Output a button 
	 *
	 */
	protected function linkButton($label, $value, $icon = "glyphicon glyphicon-ok icon-white" ) {
		echo "<p><a href='$value' class='btn  btn-primary '><span class='$icon'></span>&nbsp;$label</a></p>";
	}


	/**
	 * Output a button 
	 *
	 */
	protected function btn($label, $value, $icon = "glyphicon glyphicon-ok icon-white" ) {
		echo "<p><button type='submit' name='step' id='step' class='btn btn-primary text-center step' value='$value'><span class='$icon'></span>&nbsp;$label</button></p>";
	}

	/**
	 * Report Warning
	 *
	 */
	protected function warning($str){
		echo "<div class='alert alert-warning'><span class='glyphicon glyphicon-warning-sign'></span><strong>".__("WARNING")."</strong> $str </div>";
		return false;
	}

	/**
	 * Report Success
	 *
	 */
	protected function success($str){
		echo "<div class='alert alert-success'><span class='glyphicon glyphicon-ok-sign'></span> $str </div>";
		return false;
	}

	/**
	 * Report Error
	 *
	 */
	protected function error($str){
		$this->Errors++;
		echo "<div class='alert alert-danger'><span class='glyphicon glyphicon-remove-sign'></span><strong>".__("ERROR")."</strong> $str </div>";
		return false;
	}


	/**
	 * Output a H1 Headline
	 *
	 */
	protected function h1($label) {
		echo "\n<h1>$label</h1>";
	}
	/**
	 * Output a H2 headline
	 *
	 */
	protected function h2($label) {
		echo "\n<h2>$label</h2>";
	}

	/**
	 * Output a paragraph 
	 *
	 */
	protected function p($text, $class = '') {
		if($class) echo "\n<p class='$class'>$text</p>";
			else echo "\n<p>$text</p>";
	}
}



?>