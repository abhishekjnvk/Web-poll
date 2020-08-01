<?php
namespace CodeIgniter\Database\SQLite3;

use CodeIgniter\Database\BaseUtils;
use CodeIgniter\Database\Exceptions\DatabaseException;

/**
 * Utils for SQLite3
 */
class Utils extends BaseUtils
{

	/**
	 * OPTIMIZE TABLE statement
	 *
	 * @var string
	 */
	protected $optimizeTable = 'REINDEX %s';

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
