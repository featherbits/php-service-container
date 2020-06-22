<?php

namespace Featherbits\ServiceContainer\Contract;

interface InstanceGetter
{
    public function get(GetterContext $context): object;
}
