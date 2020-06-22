<?php

namespace Featherbits\ServiceContainer\InstanceGetterStrategies;

use Featherbits\ServiceContainer\Contract\InstanceGetter;
use Featherbits\ServiceContainer\Contract\GetterContext;

class ByFactory implements InstanceGetter
{
    private $factory;

    function __construct(callable $factory)
    {
        $this->factory = $factory;
    }

    public function get(GetterContext $context): object
    {
        return call_user_func($this->factory, $context);
    }
}
