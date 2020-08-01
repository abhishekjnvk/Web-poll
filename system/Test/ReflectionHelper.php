<?php
namespace CodeIgniter\Test;

use ReflectionClass;
use ReflectionMethod;
use ReflectionObject;

/**
 * Testing helper.
 */
trait ReflectionHelper
{
	/**
	 * Find a private method invoker.
	 *
	 * @param object|string $obj    object or class name
	 * @param string        $method method name
	 *
	 * @return \Closure
	 * @throws \ReflectionException
	 */
	public static function getPrivateMethodInvoker($obj, $method)
	{
		$ref_method = new ReflectionMethod($obj, $method);
		$ref_method->setAccessible(true);
		$obj = (gettype($obj) === 'object') ? $obj : null;

		return function () use ($obj, $ref_method) {
			$args = func_get_args();
			return $ref_method->invokeArgs($obj, $args);
		};
	}

	/**
	 * Find an accessible property.
	 *
	 * @param object $obj
	 * @param string $property
	 *
	 * @return \ReflectionProperty
	 * @throws \ReflectionException
	 */
	private static function getAccessibleRefProperty($obj, $property)
	{
		if (is_object($obj))
		{
			$ref_class = new ReflectionObject($obj);
		}
		else
		{
			$ref_class = new ReflectionClass($obj);
		}

		$ref_property = $ref_class->getProperty($property);
		$ref_property->setAccessible(true);

		return $ref_property;
	}

	/**
	 * Set a private property.
	 *
	 * @param object|string $obj      object or class name
	 * @param string        $property property name
	 * @param mixed         $value    value
	 *
	 * @throws \ReflectionException
	 */
	public static function setPrivateProperty($obj, $property, $value)
	{
		$ref_property = self::getAccessibleRefProperty($obj, $property);
		$ref_property->setValue($obj, $value);
	}

	/**
	 * Retrieve a private property.
	 *
	 * @param object|string $obj      object or class name
	 * @param string        $property property name
	 *
	 * @return mixed value
	 * @throws \ReflectionException
	 */
	public static function getPrivateProperty($obj, $property)
	{
		$ref_property = self::getAccessibleRefProperty($obj, $property);
		return $ref_property->getValue($obj);
	}

}
