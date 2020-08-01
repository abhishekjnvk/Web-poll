<?php
namespace CodeIgniter\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

/**
 * Debug toolbar filter
 */
class DebugToolbar implements FilterInterface
{
	/**
	 * We don't need to do anything here.
	 *
	 * @param RequestInterface|\CodeIgniter\HTTP\IncomingRequest $request
	 *
	 * @return void
	 */
	public function before(RequestInterface $request)
	{
	}

	//--------------------------------------------------------------------

	/**
	 * If the debug flag is set (CI_DEBUG) then collect performance
	 * and debug information and display it in a toolbar.
	 *
	 * @param RequestInterface|\CodeIgniter\HTTP\IncomingRequest $request
	 * @param ResponseInterface|\CodeIgniter\HTTP\Response       $response
	 *
	 * @return void
	 */
	public function after(RequestInterface $request, ResponseInterface $response)
	{
		Services::toolbar()->prepare($request, $response);
	}

	//--------------------------------------------------------------------
}
