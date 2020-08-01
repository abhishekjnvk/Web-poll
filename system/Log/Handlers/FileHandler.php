<?php
namespace CodeIgniter\Log\Handlers;

/**
 * Log error messages to file system
 */
class FileHandler extends BaseHandler implements HandlerInterface
{

	/**
	 * Folder to hold logs
	 *
	 * @var string
	 */
	protected $path;

	/**
	 * Extension to use for log files
	 *
	 * @var string
	 */
	protected $fileExtension;

	/**
	 * Permissions for new log files
	 *
	 * @var integer
	 */
	protected $filePermissions;

	//--------------------------------------------------------------------

	/**
	 * Constructor
	 *
	 * @param array $config
	 */
	public function __construct(array $config = [])
	{
		parent::__construct($config);

		$this->path = empty($config['path']) ? WRITEPATH . 'logs/' : $config['path'];

		$this->fileExtension = empty($config['fileExtension']) ? 'log' : $config['fileExtension'];
		$this->fileExtension = ltrim($this->fileExtension, '.');

		$this->filePermissions = $config['filePermissions'] ?? 0644;
	}

	//--------------------------------------------------------------------

	/**
	 * Handles logging the message.
	 * If the handler returns false, then execution of handlers
	 * will stop. Any handlers that have not run, yet, will not
	 * be run.
	 *
	 * @param $level
	 * @param $message
	 *
	 * @return boolean
	 * @throws \Exception
	 */
	public function handle($level, $message): bool
	{
		$filepath = $this->path . 'log-' . date('Y-m-d') . '.' . $this->fileExtension;

		$msg = '';

		if (! is_file($filepath))
		{
			$newfile = true;

			// Only add protection to php files
			if ($this->fileExtension === 'php')
			{
				$msg .= "<?php defined('SYSTEMPATH') || exit('No direct script access allowed'); ?>\n\n";
			}
		}

		if (! $fp = @fopen($filepath, 'ab'))
		{
			return false;
		}

		// Instantiating DateTime with microseconds appended to initial date is needed for proper support of this format
		if (strpos($this->dateFormat, 'u') !== false)
		{
			$microtime_full  = microtime(true);
			$microtime_short = sprintf('%06d', ($microtime_full - floor($microtime_full)) * 1000000);
			$date            = new \DateTime(date('Y-m-d H:i:s.' . $microtime_short, $microtime_full));
			$date            = $date->format($this->dateFormat);
		}
		else
		{
			$date = date($this->dateFormat);
		}

		$msg .= strtoupper($level) . ' - ' . $date . ' --> ' . $message . "\n";

		flock($fp, LOCK_EX);

		for ($written = 0, $length = strlen($msg); $written < $length; $written += $result)
		{
			if (($result = fwrite($fp, substr($msg, $written))) === false)
			{
				// if we get this far, we'll never see this during travis-ci
				// @codeCoverageIgnoreStart
				break;
				// @codeCoverageIgnoreEnd
			}
		}

		flock($fp, LOCK_UN);
		fclose($fp);

		if (isset($newfile) && $newfile === true)
		{
			chmod($filepath, $this->filePermissions);
		}

		return is_int($result);
	}

	//--------------------------------------------------------------------
}