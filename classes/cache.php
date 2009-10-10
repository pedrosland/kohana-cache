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
class Cache {

	private $driver;
	
	public $config = array();
	
	/**
	 * Loads the configured driver and validates it.
	 *
	 * @param   array|string  custom configuration or config group name
	 * @return  void
	 */
	public function __construct(array $config)
	{
		$this->config = $config;
		$driver = 'cache_driver_'.$config['driver'];
		$this->driver = new $driver($config);
	}

	/**
	 * Set cache items
	 */
	public function set($key, $value = NULL, $tags = NULL, $lifetime = NULL)
	{
		if ($lifetime === NULL)
		{
			$lifetime = $this->config['lifetime'];
		}

		if (is_array($key) === FALSE)
		{
			$key = array($key => $value);
		}

		return $this->driver->set($key, $tags, $lifetime);
	}

	/**
	 * Get a cache items by key
	 */
	public function get($keys)
	{
		$single = FALSE;

		if (is_array($keys) === FALSE)
		{
			$keys = array($keys);
			$single = TRUE;
		}

		return $this->driver->get($keys, $single);
	}

	/**
	 * Get cache items by tags
	 */
	public function get_tag($tags)
	{
		if (is_array($tags) === FALSE)
		{
			$tags = array($tags);
		}

		return $this->driver->get_tag($tags);
	}

	/**
	 * Delete cache item by key
	 */
	public function delete($keys)
	{
		if (is_array($keys) === FALSE)
		{
			$keys = array($keys);
		}

		return $this->driver->delete($keys);
	}

	/**
	 * Delete cache items by tag
	 */
	public function delete_tag($tags)
	{
		if (is_array($tags) === FALSE)
		{
			$tags = array($tags);
		}

		return $this->driver->delete_tag($tags);
	}

	/**
	 * Empty the cache
	 */
	public function delete_all()
	{
		return $this->driver->delete_all();
	}
} // End Cache Library