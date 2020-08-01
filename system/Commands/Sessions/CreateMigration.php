<?php
namespace CodeIgniter\Commands\Sessions;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\App;

/**
 * Creates a migration file for database sessions.
 *
 * @package CodeIgniter\Commands
 */

class CreateMigration extends BaseCommand
{

	/**
	 * The group the command is lumped under
	 * when listing commands.
	 *
	 * @var string
	 */
	protected $group = 'CodeIgniter';

	/**
	 * The Command's name
	 *
	 * @var string
	 */
	protected $name = 'session:migration';

	/**
	 * the Command's short description
	 *
	 * @var string
	 */
	protected $description = 'Generates the migration file for database sessions.';

	/**
	 * the Command's usage
	 *
	 * @var string
	 */
	protected $usage = 'session:migration';

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
		'-n' => 'Set migration namespace',
		'-g' => 'Set database group',
		'-t' => 'Set table name',
	];

	/**
	 * Creates a new migration file with the current timestamp.
	 *
	 * @param array $params
	 */
	public function run(array $params = [])
	{
		$config = new App();

		$tableName = CLI::getOption('t') ?? 'ci_sessions';

		$path = APPPATH . 'Database/Migrations/' . date('YmdHis_') . 'create_' . $tableName . '_table' . '.php';

		$data = [
			'namespace' => CLI::getOption('n') ?? APP_NAMESPACE ?? 'App',
			'DBGroup'   => CLI::getOption('g'),
			'tableName' => $tableName,
			'matchIP'   => $config->sessionMatchIP ?? false,
		];

		$template = view('\CodeIgniter\Commands\Sessions\Views\migration.tpl.php', $data, ['debug' => false]);
		$template = str_replace('@php', '<?php', $template);

		// Write the file out.
		helper('filesystem');
		if (! write_file($path, $template))
		{
			CLI::error(lang('Migrations.writeError', [$path]));
			return;
		}

		CLI::write('Created file: ' . CLI::color(str_replace(APPPATH, 'APPPATH/', $path), 'green'));
	}

}
