<?php

namespace Featherbits\ServiceContainer;

use Featherbits\ServiceContainer\Contract\Container as ContainerContract;
use Closure;
use Featherbits\ServiceContainer\Exceptions\ContainerException;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionFunctionAbstract;
use ReflectionParameter;
use ReflectionClass;
use Featherbits\ServiceContainer\Contract\Activator as ActivatorContract;


class Activator implements ActivatorContract
{
    private ContainerContract $container;
    private ?ResolutionSequence $sequence = null;

    public function __construct(ContainerContract $container)
    {
        $this->container = $container;
    }

    public function call(callable $callable)
    {
        $reflection = self::getCallableReflectionFunctionAbstract($callable);
        return $this->track(self::getFunctionIdentifier($reflection), function () use ($reflection, $callable) {
            return call_user_func_array($callable, $this->getArguments($reflection));
        });
    }

    public function instantiate(string $type): object
    {
        return $this->track($type, function () use ($type) {
            if ($this->container->has($type)) {
                return $this->container->get($type);
            }

            $reflection = new ReflectionClass($type);

            if ($reflection->isInterface() or $reflection->isAbstract()) {
                throw new ContainerException('Type [' . $type . '] is not instantiable.');
            }

            return ($constructor = $reflection->getConstructor())
                ? $reflection->newInstanceArgs($this->getArguments($constructor))
                : $reflection->newInstance();
        });
    }

    private function track(string $identifier, callable $callable)
    {
        $previous = $this->sequence;
        $this->sequence = $previous ? $previous->add($identifier) : ResolutionSequence::create($identifier);

        try {
            return $callable();
        } finally {
            $this->sequence = $previous;
        }
    }

    private static function getCallableReflectionFunctionAbstract(callable $callable): ReflectionFunctionAbstract
    {
        if (is_array($callable)) {
            return new ReflectionMethod($callable[0], $callable[1]);
        }

        if (is_string($callable)) {
            return new ReflectionMethod($callable);
        }

        if ($callable instanceof Closure) {
            return new ReflectionFunction($callable);
        }

        throw new ContainerException('ReflectionFunctionAbstract for given callable type not implemented.');
    }

    private static function getFunctionIdentifier(ReflectionFunctionAbstract $reflection): string
    {
        if ($reflection instanceof ReflectionFunction) {
            return $reflection->isClosure()
                ? spl_object_hash($reflection->getClosure())
                : $reflection->getName();
        }

        if ($reflection instanceof ReflectionMethod) {
            return $reflection->getDeclaringClass()->getName() . '::' . $reflection->getName();
        }

        throw new ContainerException('Function identifier not implemented for reflection of type ' . get_class($reflection) . '.');
    }

    private function getArguments(ReflectionFunctionAbstract $reflection): array
    {
        $parameters = $reflection->getParameters();
        $arguments = [];

        foreach ($parameters as $parameter) {
            $arguments[] = $this->getArgument($parameter);
        }

        return $arguments;
    }

    private function getArgument(ReflectionParameter $parameter)
    {
        /** @var \ReflectionNamedType */
        $type = $parameter->getType();
        $typeName = $type->getName();

        if ($this->container->has($typeName)) {
            return $this->container->get($typeName);
        }

        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        if (class_exists($typeName)) {
            return $this->instantiate($typeName);
        }

        throw new ContainerException('Cannot resolve value for parameter [' . $parameter->getName() . '] with type [' . $typeName . '].');
    }
}
