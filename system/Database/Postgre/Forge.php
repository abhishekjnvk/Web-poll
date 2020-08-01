<?php
namespace CodeIgniter\Database\Postgre;

/**
 * Forge for Postgre
 */
class Forge extends \CodeIgniter\Database\Forge
{

	/**
	 * CHECK DATABASE EXIST statement
	 *
	 * @var string
	 */
	protected $checkDatabaseExistStr = 'SELECT 1 FROM pg_database WHERE datname = ?';

	/**
	 * DROP CONSTRAINT statement
	 *
	 * @var string
	 */
	protected $dropConstraintStr = 'ALTER TABLE %s DROP CONSTRAINT %s';

	/**
	 * UNSIGNED support
	 *
	 * @var array
	 */
	protected $_unsigned = [
		'INT2'     => 'INTEGER',
		'SMALLINT' => 'INTEGER',
		'INT'      => 'BIGINT',
		'INT4'     => 'BIGINT',
		'INTEGER'  => 'BIGINT',
		'INT8'     => 'NUMERIC',
		'BIGINT'   => 'NUMERIC',
		'REAL'     => 'DOUBLE PRECISION',
		'FLOAT'    => 'DOUBLE PRECISION',
	];

	/**
	 * NULL value representation in CREATE/ALTER TABLE statements
	 *
	 * @var string
	 */
	protected $_null = 'NULL';

	//--------------------------------------------------------------------

	/**
	 * CREATE TABLE attributes
	 *
	 * @param  array $attributes Associative array of table attributes
	 * @return string
	 */
	protected function _createTableAttributes(array $attributes): string
	{
		return '';
	}

	//--------------------------------------------------------------------

	/**
	 * ALTER TABLE
	 *
	 * @param string $alter_type ALTER type
	 * @param string $table      Table name
	 * @param mixed  $field      Column definition
	 *
	 * @return string|array
	 */
	protected function _alterTable(string $alter_type, string $table, $field)
	{
		if (in_array($alter_type, ['DROP', 'ADD'], true))
		{
			return parent::_alterTable($alter_type, $table, $field);
		}

		$sql  = 'ALTER TABLE ' . $this->db->escapeIdentifiers($table);
		$sqls = [];
		foreach ($field as $data)
		{
			if ($data['_literal'] !== false)
			{
				return false;
			}

			if (version_compare($this->db->getVersion(), '8', '>=') && isset($data['type']))
			{
				$sqls[] = $sql . ' ALTER COLUMN ' . $this->db->escapeIdentifiers($data['name'])
						. " TYPE {$data['type']}{$data['length']}";
			}

			if (! empty($data['default']))
			{
				$sqls[] = $sql . ' ALTER COLUMN ' . $this->db->escapeIdentifiers($data['name'])
						. " SET DEFAULT {$data['default']}";
			}

			if (isset($data['null']))
			{
				$sqls[] = $sql . ' ALTER COLUMN ' . $this->db->escapeIdentifiers($data['name'])
						. ($data['null'] === true ? ' DROP' : ' SET') . ' NOT NULL';
			}

			if (! empty($data['new_name']))
			{
				$sqls[] = $sql . ' RENAME COLUMN ' . $this->db->escapeIdentifiers($data['name'])
						. ' TO ' . $this->db->escapeIdentifiers($data['new_name']);
			}

			if (! empty($data['comment']))
			{
				$sqls[] = 'COMMENT ON COLUMN' . $this->db->escapeIdentifiers($table)
						. '.' . $this->db->escapeIdentifiers($data['name'])
						. " IS {$data['comment']}";
			}
		}

		return $sqls;
	}

		//--------------------------------------------------------------------

	/**
	 * Process column
	 *
	 * @param  array $field
	 * @return string
	 */
	protected function _processColumn(array $field): string
	{
		return $this->db->escapeIdentifiers($field['name'])
				. ' ' . $field['type'] . $field['length']
				. $field['default']
				. $field['null']
				. $field['auto_increment']
				. $field['unique'];
	}

	//--------------------------------------------------------------------

	/**
	 * Field attribute TYPE
	 *
	 * Performs a data type mapping between different databases.
	 *
	 * @param array &$attributes
	 *
	 * @return void
	 */
	protected function _attributeType(array &$attributes)
	{
		// Reset field lengths for data types that don't support it
		if (isset($attributes['CONSTRAINT']) && stripos($attributes['TYPE'], 'int') !== false)
		{
			$attributes['CONSTRAINT'] = null;
		}

		switch (strtoupper($attributes['TYPE']))
		{
			case 'TINYINT':
				$attributes['TYPE']     = 'SMALLINT';
				$attributes['UNSIGNED'] = false;
				break;
			case 'MEDIUMINT':
				$attributes['TYPE']     = 'INTEGER';
				$attributes['UNSIGNED'] = false;
				break;
			case 'DATETIME':
				$attributes['TYPE'] = 'TIMESTAMP';
				break;
			default:
				break;
		}
	}

	//--------------------------------------------------------------------

	/**
	 * Field attribute AUTO_INCREMENT
	 *
	 * @param array &$attributes
	 * @param array &$field
	 *
	 * @return void
	 */
	protected function _attributeAutoIncrement(array &$attributes, array &$field)
	{
		if (! empty($attributes['AUTO_INCREMENT']) && $attributes['AUTO_INCREMENT'] === true)
		{
			$field['type'] = $field['type'] === 'NUMERIC' ? 'BIGSERIAL' : 'SERIAL';
		}
	}

	//--------------------------------------------------------------------

	/**
	 * Drop Table
	 *
	 * Generates a platform-specific DROP TABLE string
	 *
	 * @param string  $table     Table name
	 * @param boolean $if_exists Whether to add an IF EXISTS condition
	 * @param boolean $cascade
	 *
	 * @return string
	 */
	protected function _dropTable(string $table, bool $if_exists, bool $cascade): string
	{
		$sql = parent::_dropTable($table, $if_exists, $cascade);

		if ($cascade === true)
		{
			$sql .= ' CASCADE';
		}

		return $sql;
	}

	//--------------------------------------------------------------------

}
