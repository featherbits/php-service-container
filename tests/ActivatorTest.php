<?php

use PHPUnit\Framework\TestCase;
use Featherbits\ServiceContainer\Container;
use Featherbits\ServiceContainer\InstanceGetterStrategies\ByFactory;
use Featherbits\ServiceContainer\Activator;
use Featherbits\ServiceContainer\InstanceGetterStrategies\ByType;

class ClassWithTestInterfaceParameter
{
    public TestInterface $instance;

    public function __construct(TestInterface $instance)
    {
        $this->instance = $instance;
    }
}

class ClassWithTestClassParameter
{
    public ?TestClass $instance = null;
    
    public function __construct(TestClass $instance)
    {
        $this->instance = $instance;
    }
}

final class ActivatorTest extends TestCase
{
    public function testCallCallableWithResolvedParameterFromContainer()
    {
        $instance  = new TestClass;
        $container = new Container();
        $container->set(TestInterface::class, new ByFactory(function () use ($instance) {
            return $instance;
        }));

        $activator = new Activator($container);
        $instance2 = $activator->call(function (TestInterface $instance) {
            return $instance;
        });

        $this->assertSame($instance, $instance2);
    }

    public function testInstantiateClassWithResolvedParameterFromContainer()
    {
        $instance  = new TestClass;
        $container = new Container();
        $container->set(TestInterface::class, new ByFactory(function () use ($instance) {
            return $instance;
        }));

        $activator = new Activator($container);
        $instance2 = $activator->instantiate(ClassWithTestInterfaceParameter::class)->instance;

        $this->assertSame($instance, $instance2);
    }

    public function testInjectClassInstanceAsDependency()
    {
        $activator = new Activator(new Container());
        $instance = $activator->instantiate(ClassWithTestClassParameter::class)->instance;
        $this->assertInstanceOf(TestClass::class, $instance);
    }

    public function testInstantiateInterfaceWhenImplementationAddedToContainer()
    {
        $container = new Container();
        $container->set(TestInterface::class, new ByType(TestClass::class));
        $activator = new Activator($container);
        $this->assertInstanceOf(TestInterface::class, $activator->instantiate(TestInterface::class));
    }
}
