<?php 

 /**
 *	ogmaCMS Session Module
 *
 *	@package ogmaCMS
 *	@author Mike Swan / n00dles101
 *	@copyright 2013 Mike Swan / n00dles101
 *	@since 1.0.0
 *
 */

class Session {

	public static function startSession(){
		session_start();
	}

	public static function stopSession(){
		if (isset($_SESSION)) session_destroy();
	}
        
    public static function set($name, $value){
        return $_SESSION[$name] = $value;
    }
    
    public static function get($name){
        return isset($_SESSION[$name]) ? $_SESSION[$name] : '';
    }
	
}
