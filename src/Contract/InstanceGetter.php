<?php

namespace Featherbits\ServiceContainer\Contract;

interface InstanceGetter
{
    public function get(ResolutionContext $context): object;
}
