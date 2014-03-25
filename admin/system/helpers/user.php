<?php 

 /**
 *  ogmaCMS User Module
 *
 *  @package ogmaCMS
 *  @author Mike Swan / n00dles101
 *  @copyright 2013 Mike Swan / n00dles101
 *  @since 1.0.0
 *
 */

class User {
    
    public static $username = '';
    public $role = '';
    public static $userinfo = array();

    public  function __construct() {
         
    }
    
    /**
     * Logs ina user and sets cookies 
     */
    public static function login($username = "", $password=""){
        //$me=$this;
        $users = new Query('users');
        $users->getCache();
        $allUsers = $users->find('username = '.$username)->get();
        if ($users->getNumRows() == 1 && $allUsers[0]['password']==hash('sha1',$allUsers[0]['salt'].$password)){
            Session::set('username', $username);
            Session::set('id', $allUsers[0]['id']);
            Session::set('role',$allUsers[0]['role']);
            Session::set('perms',$allUsers[0]['perms']);
            Session::set('lang',$allUsers[0]['language']);
            Session::set('email',$allUsers[0]['email']);
            Session::set('authenticated', true);
            Session::set('timeout', time());
            User::$userinfo = $allUsers[0];
            if ($allUsers[0]['reset']!='') {
                User::removeReset($allUsers[0]['id']);
            }
            User::$username = $username;
            Debug::addUpdateLog($username." logged in.",$username, date('U'));
            return true;
        } else {
            Session::set('username' , '');
            Session::set('id' , '');
            Session::set('role' , '');
            Session::set('perms' , '');
            Session::set('lang' , '');
            Session::set('email' , '');
            Session::set('authenticated' ,  false);
            Session::set('timeout', ''); 
            Session::set('url', ''); 
            return false;
        }
       
    }
    
    public static function resetSession(){
            Session::set('username' , '');
            Session::set('id' , '');
            Session::set('role' , '');
            Session::set('perms' , '');
            Session::set('lang' , '');
            Session::set('email' , '');
            Session::set('authenticated' ,  false);
            Session::set('timeout', ''); 
            Session::set('url', ''); 
            return true;
    }


    /**
     * Check if password reset flag is not set on successful login
     * if it is reset it and save user file again.
     */
    public static function removeReset($id){
        $users = new Query('users');
        $userinfo = $users->getFullRecord($id);
        if ($userinfo['reset']!='') { 
            $userinfo['reset']='';
            $users->saveRecord($userinfo, $id);
        }


        print_r($userinfo);
    }

    /**
     * Returns Username
     */
    public static function getUsername(){
        return  Session::get('username');
    }

    /**
     * Returns User ID
     */
    public static function getUserID(){
        return  Session::get('id');
    }

    /**
     * Returns User Full Name
     */
    public static function getFullname(){
        return  Session::get('username');
    }

    /**
     * Returns User Full Name
     */
    public static function getLanguage(){
        return  Session::get('lang');
    }

    public static function getEmail(){
         return  Session::get('email');
    }

    /**
     * Returns User Role
     */
    public static function getRole(){
        return  Session::get('role');
    }

    /**
     * Checks to see if a user has permissions to view a page
     * Admin users have all permissions
     */
    public static function hasPerms($page=""){
        if (!User::isAdmin()) {
            $perms = explode(",", Session::get('perms'));
            $perms[] = "dashboard";
            if ($page=="" ) $page = Core::getFilenameId();
            if (in_array($page, $perms)){
                return true;
            } else {
                return false;
            }
        } else {
            return true;        // user is admin, they have permission
        }
    }

    /**
     * Check to see if the user is logged in
     */
    public static function isLoggedIn(){
        if (Session::get('authenticated') == true && Session::get('timeout')+172800 > time()){
            Session::set('timeout', time());            
            return true;
        } else {
            return false;
        }

        //return  Session::get('authenticated') == true ? true : false;
    }

    /**
     * Returns true if the current logged in user is an Admin user
     */
    public static function isAdmin(){
        return  Session::get('role') == 'admin' ? true : false;
    }
        
    public static function getGravatar(  $s = 20, $d = 'mm', $r = 'g', $img = true, $atts = array() ) {
    $email = User::getEmail();
    $url = 'http://www.gravatar.com/avatar/';
    $url .= md5( strtolower( trim( $email ) ) );
    $url .= "?s=$s&d=$d&r=$r";
    if ( $img ) {
        $url = '<img src="' . $url . '"';
        foreach ( $atts as $key => $val )
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
    return $url;
}
        
}

