<?php

use Featherbits\ServiceContainer\Container;
use Featherbits\ServiceContainer\Contract\InstanceGetter;
use Featherbits\ServiceContainer\Contract\GetterContext;
use PHPUnit\Framework\TestCase;

final class ContainerTest extends TestCase
{
    public function testSetInstanceGetter()
    {
        $container = new Container();
        $this->assertFalse($container->has(stdClass::class));

        $container->set(stdClass::class, new class implements InstanceGetter
        {
            public function get(GetterContext $context): object
            {
                return new stdClass;
            }
        });

        $this->assertTrue($container->has(stdClass::class));
    }

    public function testGetInstance()
    {
        $container = new Container();
        $instance = new stdClass;
        $container->set(stdClass::class, new class ($instance) implements InstanceGetter
        {
            public function __construct($instance)
            {
                $this->instance = $instance;
            }
            public function get(GetterContext $context): object
            {
                return $this->instance;
            }
        });
        $this->assertSame($instance, $container->get(stdClass::class));
    }
}
