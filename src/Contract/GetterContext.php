<?php

namespace Featherbits\ServiceContainer\Contract;

interface GetterContext
{
    public function getContainer(): Container;
    public function getActivator(): Activator;
    public function getType(): string;
}
