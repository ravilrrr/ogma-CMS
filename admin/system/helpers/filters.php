<?php
 /**
 *  OGMA CMS Shortcodes Module
 *
 *  @package ogmaCMS
 *  @author Mike Swan / n00dles101
 *  @copyright 2013 Mike Swan / n00dles101
 *  @since 1.0.0
 *
 */

class Filters{
    
	public static $filters = array();

    public function __construct() {
    		$this->filters = array();
    }

    /**
	 * Add Filter
	 *
	 * <code>
	 * 		Filter::addFilter($filterName, $addedFunction);
	 * </code>
	 *
	 * @param string $filterName Filter Name
	 * @param string $addedFunction Function name to run when hook initiated
	 */
	public static function addFilter($filterName, $addedFunction) {
		Filters::$filters[] = array(
			'filter' => $filterName,
			'function' => $addedFunction,
			'active' => false
		);
	}

	/**
    * Execute Filter
    *
    * Calls any functions registered against the filter argument
    *
    * <code>
    *      Component::show($filterName);
    * </code>
    *
    * @param string $filterName Component Name
    * @param array $data arguments to pass to called function.
    */
	public static function execFilter($filterName,$data=array()) {
		foreach (Filters::$filters as $filter)	{
			if ($filter['filter'] == $filterName) {
				$key = array_search($filterName,Filters::$filters);
				if (!Filters::$filters[$key]['active']) {
					Filters::$filters[$key]['active'] = true;
					$data = call_user_func_array($filter['function'], array($data));
					Filters::$filters[$key]['active'] = false;
				}
			}
		}
		return $data;
	}
}