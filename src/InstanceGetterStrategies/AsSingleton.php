<?php

namespace Featherbits\ServiceContainer\InstanceGetterStrategies;

use Featherbits\ServiceContainer\Contract\InstanceGetter;
use Featherbits\ServiceContainer\Contract\GetterContext;

class AsSingleton implements InstanceGetter
{
    private InstanceGetter $getter;
    private object $instance;

    function __construct(InstanceGetter $getter)
    {
        $this->getter = $getter;
    }

    public function get(GetterContext $context): object
    {
        return $this->instance ?? ($this->instance = $this->getter->get($context));
    }
}
