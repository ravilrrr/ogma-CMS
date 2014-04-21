<?php

/**
 *  OGMA CMS Actions Module
 *
 *  @package ogmaCMS
 *  @author Mike Swan / n00dles101
 *  @copyright 2013 Mike Swan / n00dles101
 *  @since 1.0.0
 *
 */

class Actions {
    
    public static $actions = array();
    
    public function __construct() {
        // nothing			
    }
    
    /**
     * Add Action
     *
     * <code>
     * 		Actions::addAction($hookname, $addedFunction, $priority, $args);
     * </code>
     *
     * @param string $hooKName Hook Name
     * @param string $addedFunction Function name to run when hook initiated
     * @param int $priority What order to run 
     * @param array $args Array of argument to pass to the calling function
     */
    public static function addAction($hookName, $addedFunction, $priority = 10, $args = array()) {
        Actions::$actions[] = array(
            'hook' => $hookName,
            'function' => $addedFunction,
            'priority' => $priority,
            'args' => (array) $args
        );
    }
    
    /**
     * Execute Action
     *
     * Calls and functions registered against the hookName argument
     *
     * <code>
     * 		Actions::executeAction($a);
     * </code>
     *
     * @param string $name Hook Name
     */
    public static function executeAction($name) {
        Debug::addLog(__("LOG_FIREACTION") . $name);
        foreach (Actions::$actions as $action) {
            if ($action['hook'] == $name) {
                call_user_func_array($action['function'], $action['args']);
            }
        }
    }
    
    
}