<?php

use PHPUnit\Framework\TestCase;
use Featherbits\ServiceContainer\Container;
use Featherbits\ServiceContainer\InstanceGetterStrategies\AsSingleton;
use Featherbits\ServiceContainer\InstanceGetterStrategies\ByFactory;
use Featherbits\ServiceContainer\InstanceGetterStrategies\ByType;

interface TestInterface
{
}
class TestClass implements TestInterface
{
}

final class InstanceGetterTest extends TestCase
{
    public function testGetInstanceByType()
    {
        $container = new Container();
        $container->set(TestInterface::class, new ByType(TestClass::class));

        $this->assertInstanceOf(TestClass::class, $container->get(TestInterface::class));
    }

    public function testGetInstanceByFactory()
    {
        $container = new Container();
        $container->set(TestInterface::class, new ByFactory(function () {
            return new TestClass;
        }));
        $this->assertInstanceOf(TestClass::class, $container->get(TestInterface::class));
    }

    public function testGetInstanceAsSingleton()
    {
        $container = new Container();
        $container->set(TestInterface::class, new AsSingleton(new ByType(TestClass::class)));
        $instance1 = $container->get(TestInterface::class);
        $instance2 = $container->get(TestInterface::class);
        $this->assertSame($instance1, $instance2);
    }
}
