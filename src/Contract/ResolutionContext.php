<?php

namespace Featherbits\ServiceContainer\Contract;

interface ResolutionContext
{
    public function getContainer(): Container;
    public function getActivator(): Activator;
    public function getType(): string;
}
