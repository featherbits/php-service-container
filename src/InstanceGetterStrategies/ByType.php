<?php

namespace Featherbits\ServiceContainer\InstanceGetterStrategies;

use Featherbits\ServiceContainer\Contract\InstanceGetter;
use Featherbits\ServiceContainer\Contract\GetterContext;

class ByType implements InstanceGetter
{
    private string $type;

    function __construct(string $type)
    {
        $this->type = $type;
    }

    public function get(GetterContext $context): object
    {
        return $context->getActivator()->instantiate($this->type);
    }
}
