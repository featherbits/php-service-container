<?php

namespace Featherbits\ServiceContainer\Contract;

interface Container
{
    public function has(string $type): bool;
    public function get(string $type): object;
    public function set(string $type, InstanceGetter $getter): void;
}
