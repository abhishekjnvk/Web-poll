<?php
namespace CodeIgniter\Log\Handlers;

/**
 * Expected behavior for a Log handler
 */
interface HandlerInterface
{

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
	 */
	public function handle($level, $message): bool;

	//--------------------------------------------------------------------

	/**
	 * Checks whether the Handler will handle logging items of this
	 * log Level.
	 *
	 * @param string $level
	 *
	 * @return boolean
	 */
	public function canHandle(string $level): bool;

	//--------------------------------------------------------------------

	/**
	 * Sets the preferred date format to use when logging.
	 *
	 * @param string $format
	 *
	 * @return HandlerInterface
	 */
	public function setDateFormat(string $format);

	//--------------------------------------------------------------------
}
