<?php

 /**
 *	ogmaCMS Xml Module
 *
 *	@package ogmaCMS
 *	@author Mike Swan / n00dles101
 *	@copyright 2013 Mike Swan / n00dles101
 *	@since 1.0.0
 *
 */

class SimpleXMLExtended extends SimpleXMLElement {
  public function addCData($cdata_text) {
    $node = dom_import_simplexml($this); 
    $no   = $node->ownerDocument; 
    $node->appendChild($no->createCDATASection($cdata_text)); 
    } 
  }


class Xml {
	
	protected function __construct() {
	 	
	}
	 
	 public static function loadFile($file) {
	     if (file_exists($file) && is_file($file)) {
	            $xml = Filesystem::readFile($file);
	            $data = simplexml_load_string($xml);
	            return $data;
	     } else {
	            return false;
	     }
    }
	

    public static function saveXmlFile($data = array(),$file){
		$xml=Xml::arrayToXml($data);
		$ret =  Filesystem::writeFile(ROOT . 'data/' . $file, $xml);
		return $ret;
	}


	public static function xml2array($fname){
	  $sxi = new SimpleXmlIterator($fname, null, true);
	  return Xml::sxiToArray($sxi);
	}
	
	public static function sxiToArray($sxi){
	  $a = array();
	  for( $sxi->rewind(); $sxi->valid(); $sxi->next() ) {
	    if(!array_key_exists($sxi->key(), $a)){
	      $a[$sxi->key()] = array();
	    }
	    if($sxi->hasChildren()){
	      $a[$sxi->key()] = self::sxiToArray($sxi->current());
	    }
	    else{
	      $a[$sxi->key()] = strval($sxi->current());
	    }
	  }
	  return $a;
	}
	
	
	public static function arrayToXml($data, $rootNodeName = 'root', $xml=null)
	{
		// turn off compatibility mode as simple xml throws a wobbly if you don't.
		if (ini_get('zend.ze1_compatibility_mode') == 1)
		{
			ini_set ('zend.ze1_compatibility_mode', 0);
		}
 
		if ($xml == null)
		{
			/*
			$xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$rootNodeName />");
			*/
			$xml = new SimpleXMLExtended("<$rootNodeName/>");;
		}
 
		// loop through the data passed in.
		foreach($data as $key => $value)
		{
			// no numeric keys in our xml please!
			if (is_numeric($key))
			{
				// make string key...
				$key = "item";
			}
 
			// replace anything not alpha numeric
			$key = preg_replace('/[^a-zA-Z_0-9]/i', '', $key);
 
			// if there is another array found recrusively call this function
			if (is_array($value))
			{
				$node = $xml->addChild($key);
				// recrusive call.
				Xml::arrayToXml($value, $rootNodeName, $node);
			}
			else 
			{
				// add single node
                $value = htmlentities($value);
				
				if ($key=='id'){
					$xml->addChild($key,$value);
				} else {
					$xml->$key = NULL; // VERY IMPORTANT! We need a node where to append
					$xml->$key->addCData($value);
				}
			} 
		}
		// pass back as string. or simple xml object if you want!
		return $xml->asXML();
	}



}

