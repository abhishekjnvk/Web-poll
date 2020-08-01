<?php
namespace CodeIgniter\Config;

/**
 * Class Config
 *
 * @package CodeIgniter\Config
 */
class Config
{
	/**
	 * Cache for instance of any configurations that
	 * have been requested as "shared" instance.
	 *
	 * @var array
	 */
	static private $instances = [];

	//--------------------------------------------------------------------

	/**
	 * Create new configuration instances or return
	 * a shared instance
	 *
	 * @param string  $name      Configuration name
	 * @param boolean $getShared Use shared instance
	 *
	 * @return mixed|null
	 */
	public static function get(string $name, bool $getShared = true)
	{
		$class = $name;
		if (($pos = strrpos($name, '\\')) !== false)
		{
			$class = substr($name, $pos + 1);
		}

		if (! $getShared)
		{
			return self::createClass($name);
		}

		if (! isset( self::$instances[$class] ))
		{
			self::$instances[$class] = self::createClass($name);
		}
		return self::$instances[$class];
	}

	//--------------------------------------------------------------------

	/**
	 * Helper method for injecting mock instances while testing.
	 *
	 * @param string   $class
	 * @param $instance
	 */
	public static function injectMock(string $class, $instance)
	{
		self::$instances[$class] = $instance;
	}

	//--------------------------------------------------------------------

	/**
	 * Resets the instances array
	 */
	public static function reset()
	{
		static::$instances = [];
	}

	//--------------------------------------------------------------------

	/**
	 * Find configuration class and create instance
	 *
	 * @param string $name Classname
	 *
	 * @return mixed|null
	 */
	private static function createClass(string $name)
	{
		if (class_exists($name))
		{
			return new $name();
		}

		$locator = Services::locator();
		$file    = $locator->locateFile($name, 'Config');

		if (empty($file))
		{
			// No file found - check if the class was namespaced
			if (strpos($name, '\\') !== false)
			{
				// Class was namespaced and locateFile couldn't find it
				return null;
			}

			// Check all namespaces
			$files = $locator->search('Config/' . $name);
			if (empty($files))
			{
				return null;
			}

			// Get the first match (prioritizes user and framework)
			$file = reset($files);
		}

		$name = $locator->getClassname($file);

		if (empty($name))
		{
			return null;
		}

		return new $name();
	}

	//--------------------------------------------------------------------
}
