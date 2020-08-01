<?php
namespace CodeIgniter\Format;

/**
 * Formatter interface
 */
interface FormatterInterface
{

	/**
	 * Takes the given data and formats it.
	 *
	 * @param string|array $data
	 *
	 * @return mixed
	 */
	public function format($data);
}
