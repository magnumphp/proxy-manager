<?php

namespace Magnum\ProxyManager\Tests\Fixture;

use Magnum\ProxyManager\StaticProxy;

class TestProxy
	extends StaticProxy
{
	/**
	 * @var ProxyClass
	 */
	protected static $instance;

	public static function setInstance(ProxyClass $instance)
	{
		self::instance($instance);
	}

	public static function getInstanceIdentifier(): string
	{
		return ProxyClass::class;
	}

	public static function clear()
	{
		static::instance(null, true);
		static::$container = null;
	}
}