<?php
namespace CodeIgniter\Database;

/**
 * Class Migration
 */
abstract class Migration
{

	/**
	 * The name of the database group to use.
	 *
	 * @var string
	 */
	protected $DBGroup;

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

	//--------------------------------------------------------------------

	/**
	 * Constructor.
	 *
	 * @param \CodeIgniter\Database\Forge $forge
	 */
	public function __construct(Forge $forge = null)
	{
		$this->forge = ! is_null($forge) ? $forge : \Config\Database::forge($this->DBGroup ?? config('Database')->defaultGroup);

		$this->db = $this->forge->getConnection();
	}

	//--------------------------------------------------------------------

	/**
	 * Returns the database group name this migration uses.
	 *
	 * @return string
	 */
	public function getDBGroup(): ?string
	{
		return $this->DBGroup;
	}

	//--------------------------------------------------------------------

	/**
	 * Perform a migration step.
	 */
	abstract public function up();

	//--------------------------------------------------------------------

	/**
	 * Revert a migration step.
	 */
	abstract public function down();

	//--------------------------------------------------------------------
}
