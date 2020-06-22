<?php

namespace Featherbits\ServiceContainer\InstanceGetterStrategies;

use Featherbits\ServiceContainer\Contract\InstanceGetter;
use Featherbits\ServiceContainer\Contract\ResolutionContext;

class AsSingleton implements InstanceGetter
{
    private InstanceGetter $getter;
    private object $instance;

    function __construct(InstanceGetter $getter)
    {
        $this->getter = $getter;
    }

    public function get(ResolutionContext $context): object
    {
        return $this->instance ?? ($this->instance = $this->getter->get($context));
    }
}
