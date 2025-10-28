<?php

declare(strict_types=1);

namespace App\Core;

use Closure;
use InvalidArgumentException;

class Container
{
    /** @var array<string, Closure|object|string> */
    private array $bindings = [];

    /**
     * @param class-string $abstract
     */
    public function bind(string $abstract, Closure|string $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    /**
     * @param class-string $abstract
     */
    public function instance(string $abstract, object $instance): void
    {
        $this->bindings[$abstract] = $instance;
    }

    /**
     * @template T
     * @param class-string<T> $abstract
     * @return T
     */
    public function make(string $abstract): object
    {
        if (! array_key_exists($abstract, $this->bindings)) {
            return $this->resolve($abstract);
        }

        $concrete = $this->bindings[$abstract];

        if ($concrete instanceof Closure) {
            return $concrete($this);
        }

        if (is_string($concrete)) {
            return $this->resolve($concrete);
        }

        return $concrete;
    }

    private function resolve(string $abstract): object
    {
        if (! class_exists($abstract)) {
            throw new InvalidArgumentException("Class {$abstract} does not exist");
        }

        $reflection = new \ReflectionClass($abstract);

        if (! $reflection->isInstantiable()) {
            throw new InvalidArgumentException("Class {$abstract} is not instantiable");
        }

        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return new $abstract();
        }

        $parameters = [];
        foreach ($constructor->getParameters() as $parameter) {
            $type = $parameter->getType();
            if ($type instanceof \ReflectionNamedType && ! $type->isBuiltin()) {
                $parameters[] = $this->make($type->getName());
                continue;
            }

            if ($parameter->isDefaultValueAvailable()) {
                $parameters[] = $parameter->getDefaultValue();
                continue;
            }

            throw new InvalidArgumentException('Cannot resolve class dependency ' . $parameter->getName());
        }

        return $reflection->newInstanceArgs($parameters);
    }
}
