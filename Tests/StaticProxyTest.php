<?php

namespace Magnum\ProxyManager\Tests;

use Magnum\Container\Builder;
use Magnum\ProxyManager\StaticProxy;
use Magnum\ProxyManager\Tests\Fixture\BadProxy;
use Magnum\ProxyManager\Tests\Fixture\ProxyClass;
use Magnum\ProxyManager\Tests\Fixture\TestProxy;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;

class StaticProxyTest
	extends TestCase
{
	public function testSetContainerKnowsBuilder()
	{
		$b = new Builder();

		StaticProxy::setContainer($b);

		$class      = new \ReflectionClass(StaticProxy::class);
		$properties = $class->getStaticProperties();
		self::assertSame($b->container(), $properties['container']);
	}

	public function testSetContainerThrowsException()
	{
		$this->expectException(\TypeError::class);

		StaticProxy::setContainer(new \stdClass());
	}

	public function testGetInstanceThrowsExceptionWithoutContainerAndInstance()
	{
		$this->expectException(\RuntimeException::class);
		$this->expectExceptionMessage('The Proxy Subject cannot be retrieved because the Container is not set.');

		TestProxy::clear();
		TestProxy::getInstance();
	}

	public function testGetInstanceReturnsFromContainer()
	{
		TestProxy::clear();
		TestProxy::setContainer($c = new Container());
		$c->set(ProxyClass::class, new \stdClass());

		self::assertInstanceOf(\stdClass::class, TestProxy::getInstance());
	}

	public function testGetInstanceIdentifierThrowsException()
	{
		$this->expectException(\BadMethodCallException::class);
		$this->expectExceptionMessage(
			"TheReStatic\\StaticProxy::getInstanceIdentifier " .
			"method must be implemented by a subclass."
		);

		BadProxy::getInstance();
	}

	public function testMagicMethodsAreProxied()
	{
		TestProxy::clear();
		TestProxy::setInstance($pc = new ProxyClass());
		TestProxy::test();

		self::assertTrue($pc->test);
	}
}