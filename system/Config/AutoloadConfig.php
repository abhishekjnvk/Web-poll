<?php
namespace CodeIgniter\Config;

/**
 * AUTO-LOADER
 *
 * This file defines the namespaces and class maps so the Autoloader
 * can find the files as needed.
 */
class AutoloadConfig
{

	/**
	 * Array of namespaces for autoloading.
	 *
	 * @var array
	 */
	public $psr4 = [];

	/**
	 * Map of class names and locations
	 *
	 * @var array
	 */
	public $classmap = [];

	//--------------------------------------------------------------------

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		/**
		 * -------------------------------------------------------------------
		 * Namespaces
		 * -------------------------------------------------------------------
		 * This maps the locations of any namespaces in your application
		 * to their location on the file system. These are used by the
		 * Autoloader to locate files the first time they have been instantiated.
		 *
		 * The '/application' and '/system' directories are already mapped for
		 * you. You may change the name of the 'App' namespace if you wish,
		 * but this should be done prior to creating any namespaced classes,
		 * else you will need to modify all of those classes for this to work.
		 *
		 * DO NOT change the name of the CodeIgniter namespace or your application
		 * WILL break. *
		 * Prototype:
		 *
		 *   $Config['psr4'] = [
		 *       'CodeIgniter' => SYSPATH
		 *   `];
		 */
		$this->psr4 = [
			'CodeIgniter' => realpath(SYSTEMPATH),
		];

		if (isset($_SERVER['CI_ENVIRONMENT']) && $_SERVER['CI_ENVIRONMENT'] === 'testing')
		{
			$this->psr4['Tests\Support'] = SUPPORTPATH;
		}

		/**
		 * -------------------------------------------------------------------
		 * Class Map
		 * -------------------------------------------------------------------
		 * The class map provides a map of class names and their exact
		 * location on the drive. Classes loaded in this manner will have
		 * slightly faster performance because they will not have to be
		 * searched for within one or more directories as they would if they
		 * were being autoloaded through a namespace.
		 *
		 * Prototype:
		 *
		 *   $Config['classmap'] = [
		 *       'MyClass'   => '/path/to/class/file.php'
		 *   ];
		 */
		$this->classmap = [
			'Psr\Log\AbstractLogger'           => SYSTEMPATH . 'ThirdParty/PSR/Log/AbstractLogger.php',
			'Psr\Log\InvalidArgumentException' => SYSTEMPATH . 'ThirdParty/PSR/Log/InvalidArgumentException.php',
			'Psr\Log\LoggerAwareInterface'     => SYSTEMPATH . 'ThirdParty/PSR/Log/LoggerAwareInterface.php',
			'Psr\Log\LoggerAwareTrait'         => SYSTEMPATH . 'ThirdParty/PSR/Log/LoggerAwareTrait.php',
			'Psr\Log\LoggerInterface'          => SYSTEMPATH . 'ThirdParty/PSR/Log/LoggerInterface.php',
			'Psr\Log\LoggerTrait'              => SYSTEMPATH . 'ThirdParty/PSR/Log/LoggerTrait.php',
			'Psr\Log\LogLevel'                 => SYSTEMPATH . 'ThirdParty/PSR/Log/LogLevel.php',
			'Psr\Log\NullLogger'               => SYSTEMPATH . 'ThirdParty/PSR/Log/NullLogger.php',
			'Laminas\Escaper\Escaper'          => SYSTEMPATH . 'ThirdParty/Escaper/Escaper.php',
		];

		if (isset($_SERVER['CI_ENVIRONMENT']) && $_SERVER['CI_ENVIRONMENT'] === 'testing')
		{
			$this->classmap['CodeIgniter\Log\TestLogger'] = SYSTEMPATH . 'Test/TestLogger.php';
			$this->classmap['CIDatabaseTestCase']         = SYSTEMPATH . 'Test/CIDatabaseTestCase.php';
		}
	}

	//--------------------------------------------------------------------
}
