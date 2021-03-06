<?php

namespace Featherbits\ServiceContainer;

use Featherbits\ServiceContainer\Contract\Activator as ActivatorContract;
use Featherbits\ServiceContainer\Contract\GetterContext as GetterContextContract;
use Featherbits\ServiceContainer\Contract\Container;

class GetterContext implements GetterContextContract
{
    private Container $container;
    private string $type;
    private ActivatorContract $activator;

    public function __construct(Container $container, ActivatorContract $activator, string $type)
    {
        $this->container = $container;
        $this->activator = $activator;
        $this->type = $type;
    }

    public function getContainer(): Container
    {
        return $this->container;
    }

    public function getActivator(): ActivatorContract
    {
        return $this->activator;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
