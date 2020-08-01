<?php
namespace CodeIgniter\CLI;

use CodeIgniter\CodeIgniter;

/**
 * Console
 */
class Console
{

	/**
	 * Main CodeIgniter instance.
	 *
	 * @var CodeIgniter
	 */
	protected $app;

	//--------------------------------------------------------------------

	/**
	 * Console constructor.
	 *
	 * @param \CodeIgniter\CodeIgniter $app
	 */
	public function __construct(CodeIgniter $app)
	{
		$this->app = $app;
	}

	//--------------------------------------------------------------------

	/**
	 * Runs the current command discovered on the CLI.
	 *
	 * @param boolean $useSafeOutput
	 *
	 * @return \CodeIgniter\HTTP\RequestInterface|\CodeIgniter\HTTP\Response|\CodeIgniter\HTTP\ResponseInterface|mixed
	 * @throws \Exception
	 */
	public function run(bool $useSafeOutput = false)
	{
		$path = CLI::getURI() ?: 'list';

		// Set the path for the application to route to.
		$this->app->setPath("ci{$path}");

		return $this->app->useSafeOutput($useSafeOutput)->run();
	}

	//--------------------------------------------------------------------

	/**
	 * Displays basic information about the Console.
	 */
	public function showHeader()
	{
		CLI::newLine(1);

		CLI::write(CLI::color('CodeIgniter CLI Tool', 'green')
				. ' - Version ' . CodeIgniter::CI_VERSION
				. ' - Server-Time: ' . date('Y-m-d H:i:sa'));

		CLI::newLine(1);
	}

	//--------------------------------------------------------------------
}
