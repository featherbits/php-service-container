<?php

namespace Featherbits\ServiceContainer\Contract;

interface Activator
{
    public function call(callable $callable);
    public function instantiate(string $type): object;
}
