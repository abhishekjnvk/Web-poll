<?php
namespace CodeIgniter\Test;

use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Testable response from a controller
 */
class ControllerResponse {

	/**
	 * The request.
	 *
	 * @var \CodeIgniter\HTTP\IncomingRequest
	 */
	protected $request;

	/**
	 * The response.
	 *
	 * @var \CodeIgniter\HTTP\Response
	 */
	protected $response;

	/**
	 * The message payload.
	 *
	 * @var string
	 */
	protected $body;

	/**
	 * DOM for the body.
	 *
	 * @var DOMParser
	 */
	protected $dom;

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		$this->dom = new DOMParser();
	}

	//--------------------------------------------------------------------
	// Getters / Setters
	//--------------------------------------------------------------------

	/**
	 * Set the body & DOM.
	 *
	 * @param string $body
	 *
	 * @return $this
	 */
	public function setBody(string $body)
	{
		$this->body = $body;

		if (! empty($body))
		{
			$this->dom = $this->dom->withString($body);
		}

		return $this;
	}

	/**
	 * Retrieve the body.
	 *
	 * @return string
	 */
	public function getBody()
	{
		return $this->body;
	}

	/**
	 * Set the request.
	 *
	 * @param \CodeIgniter\HTTP\RequestInterface $request
	 *
	 * @return $this
	 */
	public function setRequest(RequestInterface $request)
	{
		$this->request = $request;

		return $this;
	}

	/**
	 * Set the response.
	 *
	 * @param \CodeIgniter\HTTP\ResponseInterface $response
	 *
	 * @return $this
	 */
	public function setResponse(ResponseInterface $response)
	{
		$this->response = $response;

		$this->setBody($response->getBody() ?? '');

		return $this;
	}

	/**
	 * Request accessor.
	 *
	 * @return \CodeIgniter\HTTP\IncomingRequest
	 */
	public function request()
	{
		return $this->request;
	}

	/**
	 * Response accessor.
	 *
	 * @return \CodeIgniter\HTTP\Response
	 */
	public function response()
	{
		return $this->response;
	}

	//--------------------------------------------------------------------
	// Simple Response Checks
	//--------------------------------------------------------------------

	/**
	 * Boils down the possible responses into a boolean valid/not-valid
	 * response type.
	 *
	 * @return boolean
	 */
	public function isOK(): bool
	{
		// Only 200 and 300 range status codes
		// are considered valid.
		if ($this->response->getStatusCode() >= 400 || $this->response->getStatusCode() < 200)
		{
			return false;
		}

		// Empty bodies are not considered valid.
		if (empty($this->response->getBody()))
		{
			return false;
		}

		return true;
	}

	/**
	 * Returns whether or not the Response was a redirect response
	 *
	 * @return boolean
	 */
	public function isRedirect(): bool
	{
		return $this->response instanceof RedirectResponse;
	}

	//--------------------------------------------------------------------
	// Utility
	//--------------------------------------------------------------------

	/**
	 * Forward any unrecognized method calls to our DOMParser instance.
	 *
	 * @param  string $function Method name
	 * @param  mixed  $params   Any method parameters
	 * @return mixed
	 */
	public function __call($function, $params)
	{
		if (method_exists($this->dom, $function))
		{
			return $this->dom->{$function}(...$params);
		}
	}

}
