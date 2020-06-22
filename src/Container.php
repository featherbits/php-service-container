<?php

namespace Featherbits\ServiceContainer;

use Featherbits\ServiceContainer\Contract\Container as ContainerContract;
use Featherbits\ServiceContainer\Contract\InstanceGetter;
use Featherbits\ServiceContainer\Exceptions\ContainerException;
use Featherbits\ServiceContainer\Exceptions\NotFoundException;
use Throwable;
use Featherbits\ServiceContainer\Contract\Activator as ActivatorContract;

class Container implements ContainerContract
{
    /**
     * @var InstanceGetter[]
     */
    private array $entries = [];

    private ActivatorContract $activator;

    public function __construct()
    {
        $this->activator = new Activator($this);
    }

    public function has(string $type): bool
    {
        return array_key_exists($type, $this->entries);
    }

    public function get(string $type): object
    {
        if ($this->has($type)) {
            try {
                return $this->entries[$type]->get(new GetterContext($this, $this->activator, $type));
            } catch (Throwable $e) {
                throw new ContainerException('Failed to obtain instance of type ' . $type, 0, $e);
            }
        }

        throw new NotFoundException($type);
    }

    public function set(string $type, InstanceGetter $getter): void
    {
        $this->entries[$type] = $getter;
    }
}
