<?php
namespace CodeIgniter\Debug\Toolbar\Collectors;

use CodeIgniter\CodeIgniter;
use Config\App;
use Config\Services;

/**
 * Debug toolbar configuration
 */
class Config
{
	/**
	 * Return toolbar config values as an array.
	 *
	 * @return array
	 */
	public static function display(): array
	{
		$config = config(App::class);

		return [
			'ciVersion'   => CodeIgniter::CI_VERSION,
			'phpVersion'  => phpversion(),
			'phpSAPI'     => php_sapi_name(),
			'environment' => ENVIRONMENT,
			'baseURL'     => $config->baseURL,
			'timezone'    => app_timezone(),
			'locale'      => Services::request()->getLocale(),
			'cspEnabled'  => $config->CSPEnabled,
		];
	}
}
