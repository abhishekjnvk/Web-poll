<?php
namespace CodeIgniter\Database\MySQLi;

use CodeIgniter\Database\BaseUtils;
use CodeIgniter\Database\Exceptions\DatabaseException;

/**
 * Utils for MySQLi
 */
class Utils extends BaseUtils
{

	/**
	 * List databases statement
	 *
	 * @var string
	 */
	protected $listDatabases = 'SHOW DATABASES';

	/**
	 * OPTIMIZE TABLE statement
	 *
	 * @var string
	 */
	protected $optimizeTable = 'OPTIMIZE TABLE %s';

	//--------------------------------------------------------------------

	/**
	 * Platform dependent version of the backup function.
	 *
	 * @param array|null $prefs
	 *
	 * @return mixed
	 */
	public function _backup(array $prefs = null)
	{
		throw new DatabaseException('Unsupported feature of the database platform you are using.');
	}

	//--------------------------------------------------------------------
}
