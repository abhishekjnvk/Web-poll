<?php
namespace CodeIgniter\Commands\Database;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * Does a rollback followed by a latest to refresh the current state
 * of the database.
 *
 * @package CodeIgniter\Commands
 */
class MigrateRefresh extends BaseCommand
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
	protected $name = 'migrate:refresh';

	/**
	 * the Command's short description
	 *
	 * @var string
	 */
	protected $description = 'Does a rollback followed by a latest to refresh the current state of the database.';

	/**
	 * the Command's usage
	 *
	 * @var string
	 */
	protected $usage = 'migrate:refresh [Options]';

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
		'-n'   => 'Set migration namespace',
		'-g'   => 'Set database group',
		'-all' => 'Set latest for all namespace, will ignore (-n) option',
		'-f'   => 'Force command - this option allows you to bypass the confirmation question when running this command in a production environment',
	];

	/**
	 * Does a rollback followed by a latest to refresh the current state
	 * of the database.
	 *
	 * @param array $params
	 */
	public function run(array $params = [])
	{
		$params = ['-b' => 0];

		if (ENVIRONMENT === 'production')
		{
			$force = $params['-f'] ?? CLI::getOption('f');
			if (is_null($force) && CLI::prompt(lang('Migrations.refreshConfirm'), ['y', 'n']) === 'n')
			{
				return;
			}

			$params['-f'] = '';
		}

		$this->call('migrate:rollback', $params);
		$this->call('migrate');
	}

}
