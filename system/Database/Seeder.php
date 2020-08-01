<?php
namespace CodeIgniter\Database;

use CodeIgniter\CLI\CLI;
use CodeIgniter\Config\BaseConfig;

/**
 * Class Seeder
 */
class Seeder
{

	/**
	 * The name of the database group to use.
	 *
	 * @var string
	 */
	protected $DBGroup;

	/**
	 * Where we can find the Seed files.
	 *
	 * @var string
	 */
	protected $seedPath;

	/**
	 * An instance of the main Database configuration
	 *
	 * @var BaseConfig
	 */
	protected $config;

	/**
	 * Database Connection instance
	 *
	 * @var BaseConnection
	 */
	protected $db;

	/**
	 * Database Forge instance.
	 *
	 * @var Forge
	 */
	protected $forge;

	/**
	 * If true, will not display CLI messages.
	 *
	 * @var boolean
	 */
	protected $silent = false;

	//--------------------------------------------------------------------

	/**
	 * Seeder constructor.
	 *
	 * @param BaseConfig     $config
	 * @param BaseConnection $db
	 */
	public function __construct(BaseConfig $config, BaseConnection $db = null)
	{
		$this->seedPath = $config->filesPath ?? APPPATH . 'Database/';

		if (empty($this->seedPath))
		{
			throw new \InvalidArgumentException('Invalid filesPath set in the Config\Database.');
		}

		$this->seedPath = rtrim($this->seedPath, '/') . '/Seeds/';

		if (! is_dir($this->seedPath))
		{
			throw new \InvalidArgumentException('Unable to locate the seeds directory. Please check Config\Database::filesPath');
		}

		$this->config = & $config;

		if (is_null($db))
		{
			$db = \Config\Database::connect($this->DBGroup);
		}

		$this->db = & $db;

		$this->forge = \Config\Database::forge($this->DBGroup);
	}

	//--------------------------------------------------------------------

	/**
	 * Loads the specified seeder and runs it.
	 *
	 * @param string $class
	 *
	 * @throws \InvalidArgumentException
	 */
	public function call(string $class)
	{
		if (empty($class))
		{
			throw new \InvalidArgumentException('No Seeder was specified.');
		}

		$path = str_replace('.php', '', $class) . '.php';

		// If we have namespaced class, simply try to load it.
		if (strpos($class, '\\') !== false)
		{
			$seeder = new $class($this->config);
		}
		// Otherwise, try to load the class manually.
		else
		{
			$path = $this->seedPath . $path;

			if (! is_file($path))
			{
				throw new \InvalidArgumentException('The specified Seeder is not a valid file: ' . $path);
			}

			// Assume the class has the correct namespace
			$class = APP_NAMESPACE . '\Database\Seeds\\' . $class;

			if (! class_exists($class, false))
			{
				require_once $path;
			}

			$seeder = new $class($this->config);
		}

		$seeder->setSilent($this->silent);
		$seeder->run();

		unset($seeder);

		if (is_cli() && ! $this->silent)
		{
			CLI::write("Seeded: {$class}", 'green');
		}
	}

	//--------------------------------------------------------------------

	/**
	 * Sets the location of the directory that seed files can be located in.
	 *
	 * @param string $path
	 *
	 * @return Seeder
	 */
	public function setPath(string $path)
	{
		$this->seedPath = rtrim($path, '/') . '/';

		return $this;
	}

	//--------------------------------------------------------------------

	/**
	 * Sets the silent treatment.
	 *
	 * @param boolean $silent
	 *
	 * @return Seeder
	 */
	public function setSilent(bool $silent)
	{
		$this->silent = $silent;

		return $this;
	}

	//--------------------------------------------------------------------

	/**
	 * Run the database seeds. This is where the magic happens.
	 *
	 * Child classes must implement this method and take care
	 * of inserting their data here.
	 *
	 * @return mixed
	 */
	public function run()
	{
	}

	//--------------------------------------------------------------------
}
