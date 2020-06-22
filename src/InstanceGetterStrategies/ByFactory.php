<?php

namespace Featherbits\ServiceContainer\InstanceGetterStrategies;

use Featherbits\ServiceContainer\Contract\InstanceGetter;
use Featherbits\ServiceContainer\Contract\ResolutionContext;

class ByFactory implements InstanceGetter
{
    private $factory;

    function __construct(callable $factory)
    {
        $this->factory = $factory;
    }

    public function get(ResolutionContext $context): object
    {
        return call_user_func($this->factory, $context);
    }
}
