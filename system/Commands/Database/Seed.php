<?php
namespace CodeIgniter\Commands\Database;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\Seeder;

/**
 * Runs the specified Seeder file to populate the database
 * with some data.
 *
 * @package CodeIgniter\Commands
 */
class Seed extends BaseCommand
{

	/**
	 * The group the command is lumped under
	 * when listing commands.
	 *
	 * @var string
	 */
	protected $group = 'Database';

	/**
	 * The Command's name
	 *
	 * @var string
	 */
	protected $name = 'db:seed';

	/**
	 * the Command's short description
	 *
	 * @var string
	 */
	protected $description = 'Runs the specified seeder to populate known data into the database.';

	/**
	 * the Command's usage
	 *
	 * @var string
	 */
	protected $usage = 'db:seed [seeder_name]';

	/**
	 * the Command's Arguments
	 *
	 * @var array
	 */
	protected $arguments = [
		'seeder_name' => 'The seeder name to run',
	];

	/**
	 * the Command's Options
	 *
	 * @var array
	 */
	protected $options = [];

	/**
	 * Passes to Seeder to populate the database.
	 *
	 * @param array $params
	 */
	public function run(array $params = [])
	{
		$seeder = new Seeder(new \Config\Database());

		$seedName = array_shift($params);

		if (empty($seedName))
		{
			$seedName = CLI::prompt(lang('Migrations.migSeeder'), 'DatabaseSeeder');
		}

		if (empty($seedName))
		{
			CLI::error(lang('Migrations.migMissingSeeder'));
			return;
		}

		try
		{
			$seeder->call($seedName);
		}
		catch (\Exception $e)
		{
			$this->showError($e);
		}
	}

}
