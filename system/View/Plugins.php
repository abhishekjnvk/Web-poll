<?php
namespace CodeIgniter\View;

 /**
  * View plugins
  */
class Plugins
{

	/**
	 * Wrap helper function to use as view plugin.
	 *
	 * @return string|\CodeIgniter\HTTP\URI
	 */
	public static function currentURL()
	{
		return current_url();
	}

	//--------------------------------------------------------------------

	/**
	 * Wrap helper function to use as view plugin.
	 *
	 * @return \CodeIgniter\HTTP\URI|mixed|string
	 */
	public static function previousURL()
	{
		return previous_url();
	}

	//--------------------------------------------------------------------

	/**
	 * Wrap helper function to use as view plugin.
	 *
	 * @param array $params
	 *
	 * @return string
	 */
	public static function mailto(array $params = []): string
	{
		$email = $params['email'] ?? '';
		$title = $params['title'] ?? '';
		$attrs = $params['attributes'] ?? '';

		return mailto($email, $title, $attrs);
	}

	//--------------------------------------------------------------------

	/**
	 * Wrap helper function to use as view plugin.
	 *
	 * @param array $params
	 *
	 * @return string
	 */
	public static function safeMailto(array $params = []): string
	{
		$email = $params['email'] ?? '';
		$title = $params['title'] ?? '';
		$attrs = $params['attributes'] ?? '';

		return safe_mailto($email, $title, $attrs);
	}

	//--------------------------------------------------------------------

	/**
	 * Wrap helper function to use as view plugin.
	 *
	 * @param array $params
	 *
	 * @return string
	 */
	public static function lang(array $params = []): string
	{
		$line = array_shift($params);

		return lang($line, $params);
	}

	//--------------------------------------------------------------------

	/**
	 * Wrap helper function to use as view plugin.
	 *
	 * @param array $params
	 *
	 * @return string
	 */
	public static function ValidationErrors(array $params = []): string
	{
		$validator = \Config\Services::validation();
		if (empty($params))
		{
			return $validator->listErrors();
		}

		return $validator->showError($params['field']);
	}

	//--------------------------------------------------------------------

	/**
	 * Wrap helper function to use as view plugin.
	 *
	 * @param array $params
	 *
	 * @return string|false
	 */
	public static function route(array $params = [])
	{
		return route_to(...$params);
	}

	//--------------------------------------------------------------------

	/**
	 * Wrap helper function to use as view plugin.
	 *
	 * @param array $params
	 *
	 * @return string
	 */
	public static function siteURL(array $params = []): string
	{
		return site_url(...$params);
	}
}
