<?php

namespace Featherbits\ServiceContainer\InstanceGetterStrategies;

use Featherbits\ServiceContainer\Contract\InstanceGetter;
use Featherbits\ServiceContainer\Contract\ResolutionContext;

class ByType implements InstanceGetter
{
    private string $type;

    function __construct(string $type)
    {
        $this->type = $type;
    }

    public function get(ResolutionContext $context): object
    {
        return $context->getActivator()->instantiate($this->type);
    }
}
