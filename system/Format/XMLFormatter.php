<?php
namespace CodeIgniter\Format;

use CodeIgniter\Format\Exceptions\FormatException;

/**
 * XML data formatter
 */
class XMLFormatter implements FormatterInterface
{

	/**
	 * Takes the given data and formats it.
	 *
	 * @param $data
	 *
	 * @return string|boolean (XML string | false)
	 */
	public function format($data)
	{
		// SimpleXML is installed but default
		// but best to check, and then provide a fallback.
		if (! extension_loaded('simplexml'))
		{
			// never thrown in travis-ci
			// @codeCoverageIgnoreStart
			throw FormatException::forMissingExtension();
			// @codeCoverageIgnoreEnd
		}

		$output = new \SimpleXMLElement('<?xml version="1.0"?><response></response>');

		$this->arrayToXML((array)$data, $output);

		return $output->asXML();
	}

	//--------------------------------------------------------------------

	/**
	 * A recursive method to convert an array into a valid XML string.
	 *
	 * Written by CodexWorld. Received permission by email on Nov 24, 2016 to use this code.
	 *
	 * @see http://www.codexworld.com/convert-array-to-xml-in-php/
	 *
	 * @param array             $data
	 * @param \SimpleXMLElement $output
	 */
	protected function arrayToXML(array $data, &$output)
	{
		foreach ($data as $key => $value)
		{
			if (is_array($value))
			{
				if (! is_numeric($key))
				{
					$subnode = $output->addChild("$key");
					$this->arrayToXML($value, $subnode);
				}
				else
				{
					$subnode = $output->addChild("item{$key}");
					$this->arrayToXML($value, $subnode);
				}
			}
			else
			{
				$output->addChild("$key", htmlspecialchars("$value"));
			}
		}
	}

	//--------------------------------------------------------------------
}
