<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Provides a driver-based interface for finding, creating, and deleting cached
 * resources. Caches are identified by a unique string. Tagging of caches is
 * also supported, and caches can be found and deleted by id or tag.
 *
 * $Id: Cache.php 4605 2009-09-14 17:22:21Z kiall $
 *
 * @package    Cache
 * @author     Kohana Team
 * @copyright  (c) 2007-2009 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
class Cache_Controller extends Controller {

	public $cache_config = array(
		'driver'=>'file',
		//'params'  => array('gc_probability' => 1000), gc_probability doesnt seem to do anything for the file driver
		'lifetime' 	=> 1800, //seconds
	);
	public $cache_lifetime = NULL;
	
	public $cache = TRUE;
	public $cache_tag = NULL;
	
	/*public function before(){
		parent::before();
	}*/
	
	/*public function __construct(request $request){
		//gentime2();
		parent::__construct($request);
	}*/
	
	public function before(){
		//echo gentime2();
		//gentime();
		parent::before();
		
		if($this->cache === TRUE && $this->request->status === 200 && $this->session->user->manage_dash_mods === null){
			$cache = new Cache($this->cache_config);
			$result = $cache->get($this->request->uri);
			
			if($result !== NULL){
				$this->request->send_headers();
				echo $result;
				//echo gentime();
				//echo View::factory('profiler/stats');
				exit;
			}
		}
	}
	
	public function after(){
		parent::after();
		
		if($this->cache === TRUE && $this->request->status === 200 && $this->session->user->manage_dash_mods === null){
			$cache = new Cache($this->cache_config);
			$cache->set($this->request->uri . ' :: ' . $this->session->group, $this->request->response, $this->cache_tag, $this->cache_lifetime);
		}
	}

} // End Cache Library

/*
function gentime() {
    static $a;
    if($a == 0) $a = microtime(true);
    else return (string)(microtime(true)-$a);
}
function gentime2() {
    static $a;
    if($a == 0) $a = microtime(true);
    else return (string)(microtime(true)-$a);
}*/