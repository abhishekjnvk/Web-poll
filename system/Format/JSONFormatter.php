<?php
namespace CodeIgniter\Format;

use CodeIgniter\Format\Exceptions\FormatException;

/**
 * JSON data formatter
 */
class JSONFormatter implements FormatterInterface
{

	/**
	 * Takes the given data and formats it.
	 *
	 * @param $data
	 *
	 * @return string|boolean (JSON string | false)
	 */
	public function format($data)
	{
		$options = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR;

		$options = ENVIRONMENT === 'production' ? $options : $options | JSON_PRETTY_PRINT;

		$result = json_encode($data, $options, 512);

		if ( ! in_array(json_last_error(), [JSON_ERROR_NONE, JSON_ERROR_RECURSION]))
		{
			throw FormatException::forInvalidJSON(json_last_error_msg());
		}

		return $result;
	}

	//--------------------------------------------------------------------
}
