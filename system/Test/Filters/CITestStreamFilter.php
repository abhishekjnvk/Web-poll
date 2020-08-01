<?php
namespace CodeIgniter\Test\Filters;

/**
 * Class to extract an output snapshot.
 * Used to capture output during unit testing, so that it can
 * be used in assertions.
 */

class CITestStreamFilter extends \php_user_filter
{

	/**
	 * Buffer to capture stream content.
	 *
	 * @var type
	 */
	public static $buffer = '';

	/**
	 * Output filtering - catch it all.
	 *
	 * @param  type $in
	 * @param  type $out
	 * @param  type $consumed
	 * @param  type $closing
	 * @return type
	 */
	public function filter($in, $out, &$consumed, $closing)
	{
		while ($bucket = stream_bucket_make_writeable($in))
		{
			static::$buffer .= $bucket->data;
			$consumed       += $bucket->datalen;
		}
		return PSFS_PASS_ON;
	}

}

// @codeCoverageIgnoreStart
stream_filter_register('CITestStreamFilter', 'CodeIgniter\Test\Filters\CITestStreamFilter');
// @codeCoverageIgnoreEnd
