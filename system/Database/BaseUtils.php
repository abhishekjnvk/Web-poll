<?php
namespace CodeIgniter\Database;

use CodeIgniter\Database\Exceptions\DatabaseException;

/**
 * Class BaseUtils
 */
abstract class BaseUtils
{

	/**
	 * Database object
	 *
	 * @var object
	 */
	protected $db;

	//--------------------------------------------------------------------

	/**
	 * List databases statement
	 *
	 * @var string
	 */
	protected $listDatabases = false;

	/**
	 * OPTIMIZE TABLE statement
	 *
	 * @var string
	 */
	protected $optimizeTable = false;

	/**
	 * REPAIR TABLE statement
	 *
	 * @var string
	 */
	protected $repairTable = false;

	//--------------------------------------------------------------------

	/**
	 * Class constructor
	 *
	 * @param ConnectionInterface|object $db
	 */
	public function __construct(ConnectionInterface &$db)
	{
		$this->db = & $db;
	}

	//--------------------------------------------------------------------

	/**
	 * List databases
	 *
	 * @return array|boolean
	 * @throws \CodeIgniter\Database\Exceptions\DatabaseException
	 */
	public function listDatabases()
	{
		// Is there a cached result?
		if (isset($this->db->dataCache['db_names']))
		{
			return $this->db->dataCache['db_names'];
		}
		elseif ($this->listDatabases === false)
		{
			if ($this->db->DBDebug)
			{
				throw new DatabaseException('Unsupported feature of the database platform you are using.');
			}
			return false;
		}

		$this->db->dataCache['db_names'] = [];

		$query = $this->db->query($this->listDatabases);
		if ($query === false)
		{
			return $this->db->dataCache['db_names'];
		}

		for ($i = 0, $query = $query->getResultArray(), $c = count($query); $i < $c; $i ++)
		{
			$this->db->dataCache['db_names'][] = current($query[$i]);
		}

		return $this->db->dataCache['db_names'];
	}

	//--------------------------------------------------------------------

	/**
	 * Determine if a particular database exists
	 *
	 * @param  string $database_name
	 * @return boolean
	 */
	public function databaseExists(string $database_name): bool
	{
		return in_array($database_name, $this->listDatabases());
	}

	//--------------------------------------------------------------------

	/**
	 * Optimize Table
	 *
	 * @param  string $table_name
	 * @return mixed
	 * @throws \CodeIgniter\Database\Exceptions\DatabaseException
	 */
	public function optimizeTable(string $table_name)
	{
		if ($this->optimizeTable === false)
		{
			if ($this->db->DBDebug)
			{
				throw new DatabaseException('Unsupported feature of the database platform you are using.');
			}
			return false;
		}

		$query = $this->db->query(sprintf($this->optimizeTable, $this->db->escapeIdentifiers($table_name)));
		if ($query !== false)
		{
			$query = $query->getResultArray();
			return current($query);
		}

		return false;
	}

	//--------------------------------------------------------------------

	/**
	 * Optimize Database
	 *
	 * @return mixed
	 * @throws \CodeIgniter\Database\Exceptions\DatabaseException
	 */
	public function optimizeDatabase()
	{
		if ($this->optimizeTable === false)
		{
			if ($this->db->DBDebug)
			{
				throw new DatabaseException('Unsupported feature of the database platform you are using.');
			}
			return false;
		}

		$result = [];
		foreach ($this->db->listTables() as $table_name)
		{
			$res = $this->db->query(sprintf($this->optimizeTable, $this->db->escapeIdentifiers($table_name)));
			if (is_bool($res))
			{
				return $res;
			}

			// Build the result array...

			$res = $res->getResultArray();

			// Postgre & SQLite3 returns empty array
			if (empty($res))
			{
				$key = $table_name;
			}
			else
			{
				$res  = current($res);
				$key  = str_replace($this->db->database . '.', '', current($res));
				$keys = array_keys($res);
				unset($res[$keys[0]]);
			}

			$result[$key] = $res;
		}

		return $result;
	}

	//--------------------------------------------------------------------

	/**
	 * Repair Table
	 *
	 * @param  string $table_name
	 * @return mixed
	 * @throws \CodeIgniter\Database\Exceptions\DatabaseException
	 */
	public function repairTable(string $table_name)
	{
		if ($this->repairTable === false)
		{
			if ($this->db->DBDebug)
			{
				throw new DatabaseException('Unsupported feature of the database platform you are using.');
			}
			return false;
		}

		$query = $this->db->query(sprintf($this->repairTable, $this->db->escapeIdentifiers($table_name)));
		if (is_bool($query))
		{
			return $query;
		}

		$query = $query->getResultArray();
		return current($query);
	}

	//--------------------------------------------------------------------

	/**
	 * Generate CSV from a query result object
	 *
	 * @param ResultInterface $query     Query result object
	 * @param string          $delim     Delimiter (default: ,)
	 * @param string          $newline   Newline character (default: \n)
	 * @param string          $enclosure Enclosure (default: ")
	 *
	 * @return string
	 */
	public function getCSVFromResult(ResultInterface $query, string $delim = ',', string $newline = "\n", string $enclosure = '"')
	{
		$out = '';
		// First generate the headings from the table column names
		foreach ($query->getFieldNames() as $name)
		{
			$out .= $enclosure . str_replace($enclosure, $enclosure . $enclosure, $name) . $enclosure . $delim;
		}

		$out = substr($out, 0, -strlen($delim)) . $newline;

		// Next blast through the result array and build out the rows
		while ($row = $query->getUnbufferedRow('array'))
		{
			$line = [];
			foreach ($row as $item)
			{
				$line[] = $enclosure . str_replace($enclosure, $enclosure . $enclosure, $item) . $enclosure;
			}
			$out .= implode($delim, $line) . $newline;
		}

		return $out;
	}

	//--------------------------------------------------------------------

	/**
	 * Generate XML data from a query result object
	 *
	 * @param ResultInterface $query  Query result object
	 * @param array           $params Any preferences
	 *
	 * @return string
	 */
	public function getXMLFromResult(ResultInterface $query, array $params = []): string
	{
		// Set our default values
		foreach (['root' => 'root', 'element' => 'element', 'newline' => "\n", 'tab' => "\t"] as $key => $val)
		{
			if (! isset($params[$key]))
			{
				$params[$key] = $val;
			}
		}

		// Create variables for convenience
		extract($params);

		// Load the xml helper
		helper('xml');
		// Generate the result
		$xml = '<' . $root . '>' . $newline;
		while ($row = $query->getUnbufferedRow())
		{
			$xml .= $tab . '<' . $element . '>' . $newline;
			foreach ($row as $key => $val)
			{
				$val  = (! empty($val)) ? xml_convert($val) : '';
				$xml .= $tab . $tab . '<' . $key . '>' . $val . '</' . $key . '>' . $newline;
			}
			$xml .= $tab . '</' . $element . '>' . $newline;
		}

		return $xml . '</' . $root . '>' . $newline;
	}

	//--------------------------------------------------------------------

	/**
	 * Database Backup
	 *
	 * @param  array|string $params
	 * @return mixed
	 * @throws \CodeIgniter\Database\Exceptions\DatabaseException
	 */
	public function backup($params = [])
	{
		// If the parameters have not been submitted as an
		// array then we know that it is simply the table
		// name, which is a valid short cut.
		if (is_string($params))
		{
			$params = ['tables' => $params];
		}

		// Set up our default preferences
		$prefs = [
			'tables'             => [],
			'ignore'             => [],
			'filename'           => '',
			'format'             => 'gzip', // gzip, txt
			'add_drop'           => true,
			'add_insert'         => true,
			'newline'            => "\n",
			'foreign_key_checks' => true,
		];

		// Did the user submit any preferences? If so set them....
		if (! empty($params))
		{
			foreach ($prefs as $key => $val)
			{
				if (isset($params[$key]))
				{
					$prefs[$key] = $params[$key];
				}
			}
		}

		// Are we backing up a complete database or individual tables?
		// If no table names were submitted we'll fetch the entire table list
		if (empty($prefs['tables']))
		{
			$prefs['tables'] = $this->db->listTables();
		}

		// Validate the format
		if (! in_array($prefs['format'], ['gzip', 'txt'], true))
		{
			$prefs['format'] = 'txt';
		}

		// Is the encoder supported? If not, we'll either issue an
		// error or use plain text depending on the debug settings
		if ($prefs['format'] === 'gzip' && ! function_exists('gzencode'))
		{
			if ($this->db->DBDebug)
			{
				throw new DatabaseException('The file compression format you chose is not supported by your server.');
			}

			$prefs['format'] = 'txt';
		}

		if ($prefs['format'] === 'txt') // Was a text file requested?
		{
			return $this->_backup($prefs);
		}

		return gzencode($this->_backup($prefs));
	}

	//--------------------------------------------------------------------

	/**
	 * Platform dependent version of the backup function.
	 *
	 * @param array|null $prefs
	 *
	 * @return mixed
	 */
	abstract public function _backup(array $prefs = null);

	//--------------------------------------------------------------------
}
