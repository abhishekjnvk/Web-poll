<?php
namespace CodeIgniter\Commands\Database;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Services;

/**
 * Displays a list of all migrations and whether they've been run or not.
 *
 * @package CodeIgniter\Commands
 */
class MigrateStatus extends BaseCommand
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
	protected $name = 'migrate:status';

	/**
	 * the Command's short description
	 *
	 * @var string
	 */
	protected $description = 'Displays a list of all migrations and whether they\'ve been run or not.';

	/**
	 * the Command's usage
	 *
	 * @var string
	 */
	protected $usage = 'migrate:status [Options]';

	/**
	 * the Command's Arguments
	 *
	 * @var array
	 */
	protected $arguments = [];

	/**
	 * the Command's Options
	 *
	 * @var array
	 */
	protected $options = [
		'-g' => 'Set database group',
	];

	/**
	 * Namespaces to ignore when looking for migrations.
	 *
	 * @var type
	 */
	protected $ignoredNamespaces = [
		'CodeIgniter',
		'Config',
		'Tests\Support',
		'Kint',
		'Laminas\ZendFrameworkBridge',
		'Laminas\Escaper',
		'Psr\Log',
	];

	/**
	 * Displays a list of all migrations and whether they've been run or not.
	 *
	 * @param array $params
	 */
	public function run(array $params = [])
	{
		$runner = Services::migrations();

		$group = $params['-g'] ?? CLI::getOption('g');

		if (! is_null($group))
		{
			$runner->setGroup($group);
		}

		// Get all namespaces
		$namespaces = Services::autoloader()->getNamespace();

		// Determines whether any migrations were found
		$found = false;

		// Loop for all $namespaces
		foreach ($namespaces as $namespace => $path)
		{
			if (in_array($namespace, $this->ignoredNamespaces))
			{
				continue;
			}

			$runner->setNamespace($namespace);
			$migrations = $runner->findMigrations();

			if (empty($migrations))
			{
				continue;
			}

			$found   = true;
			$history = $runner->getHistory();

			CLI::write($namespace);

			ksort($migrations);

			$max = 0;
			foreach ($migrations as $version => $migration)
			{
				$file                       = substr($migration->name, strpos($migration->name, $version . '_'));
				$migrations[$version]->name = $file;

				$max = max($max, strlen($file));
			}

			CLI::write('  ' . str_pad(lang('Migrations.filename'), $max + 4) . lang('Migrations.on'), 'yellow');

			foreach ($migrations as $uid => $migration)
			{
				$date = '';
				foreach ($history as $row)
				{
					if ($runner->getObjectUid($row) !== $uid)
					{
						continue;
					}

					$date = date('Y-m-d H:i:s', $row->time);
				}
				CLI::write(str_pad('  ' . $migration->name, $max + 6) . ($date ? $date : '---'));
			}
		}

		if (! $found)
		{
			CLI::error(lang('Migrations.noneFound'));
		}
	}

}
