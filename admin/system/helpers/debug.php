<?php 

 /**
 *  OGMA CMS Debug Module
 *
 *  @package ogmaCMS
 *  @author Mike Swan / n00dles101
 *  @copyright 2013 Mike Swan / n00dles101
 *  @since 1.0.0
 *
 */

class Debug{

	public static $log = array();
	public static $timers = array();

	public function __construct() {

    }
    
    public static function pa($array){
    	echo "<pre>";
    	print_r($array);
    	echo "</pre>";
    }
    public static function addLog($txt, $type = "info", $caller = false){
    	if ($caller) {
    		$bt = debug_backtrace();
  			$caller = array_shift($bt);
  			$caller = array_shift($bt);
  			$caller = array_shift($bt);
  			$txt = basename($caller['file']).':'.$caller['line'].'->'.$txt;
  		}
    	Debug::$log[] = array('time'=>microtime(true), 
								'text'=> $txt, 
								'type'=>$type );
    }

    public static function active(){
    	return Core::$site['debug'];
    }

    public static function showConsole(){
    	echo '<div class="row"><div class="container">';
		echo '<div id="debugconsole"><h4>'.__('DEBUG_CONSOLE').'</h4>';
		echo '<pre>';
		$count = count(Debug::$log);
		$time = 0;
		foreach (Debug::$log as $log) {

			$date = $log['time'];
			$error = $log['text'];
			$type = $log['type'];
			if ($time==0) {
				$time=$log['time'];
			} 
			$executionTime = $log['time'] - $time;
			echo "<div class='alert'>";
			echo "<span class='timestamp' > ".number_format($executionTime, 4, '.', '')."ms </span>";
			echo "<span class='alert alert-".$type."'>".$type."</span> ";
			
			print($error.'<br/>');
			echo "</div>";
		}
		echo '</pre>';	
		echo '</div></div>';
    }

    public static function timerRead($name) {
	  if (isset(self::$timers[$name]['start'])){
	    $stop = microtime(TRUE);
	    $diff = round(($stop - self::$timers[$name]['start']) * 1000, 2);

	    if (isset(self::$timers[$name]['time'])) {
	      $diff += self::$timers[$name]['time'];
	    }
	    return $diff;
	  }
	  return "Timer ".$name.":".self::$timers[$name]['time'];
	}

    public static function timerStart($name) {
	  	self::$timers[$name]['start'] = microtime(TRUE);
	  	self::$timers[$name]['count'] = isset(self::$timers[$name]['count']) ? ++self::$timers[$name]['count'] : 1;
		self::addLog("Timer ".$name." Started");
	}

    public static function timerStop($name) {

	  if (isset(self::$timers[$name]['start'])) {
	    $stop = microtime(TRUE);
	    $diff = round(($stop - self::$timers[$name]['start']) * 1000, 2);
	    if (isset(self::$timers[$name]['time'])) {
	      self::$timers[$name]['time'] += $diff;
	    }
	    else {
	      self::$timers[$name]['time'] = $diff;
	    }
	    unset(self::$timers[$name]['start']);
	  }
	  self::addLog("Timer ".$name." Stopped");
	  return self::$timers[$name];
	}

	public static function addUpdateLog($text,$user){
		 $file=Core::$settings['rootpath'].'data/updatefeed.xml';
          if (file_exists($file)){
          // load the xml file and setup the array. 
                $thisfile = file_get_contents($file);
            } else {
                $thisfile = '<?xml version="1.0" encoding="utf-8"?><root></root>';
          }

        $data = simplexml_load_string($thisfile);
		$item = $data->addChild('item');
		$item->addChild('time', date('U'));
		$item->addChild('user', $user);	
		$item->addChild('desc', $text);
		return $data->asXML($file);
	}

	public static function getUpdateLog($num = 10){
		$log = array();
		$file=Core::$settings['rootpath'].'data/updatefeed.xml';
		if (file_exists($file)){
          // load the xml file and setup the array. 
                $thisfile = file_get_contents($file);
            } else {
                $thisfile = '<?xml version="1.0" encoding="utf-8"?><root></root>';
          }

            $data = simplexml_load_string($thisfile);
            $components = @$data->item;
            if (count($components) != 0) {
                foreach ($components as $component) {
                $name=(string)$component->name;
                   $log[(string)$component->time] = array(
                        'user' => (string)$component->user, 
                        'time' => (string)$component->time,
                        'desc' => (string)$component->desc
                        );
                }
            }
            return array_slice(array_reverse($log), 0, $num); ;
	}


}


?>
