<?php

declare(strict_types=1);

namespace App\Core\Config;

use RuntimeException;

class ConfigRepository
{
    /** @var array<string, mixed> */
    private array $items = [];

    public function __construct(private readonly string $configPath)
    {
        $this->load();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $segments = explode('.', $key);
        $value = $this->items;

        foreach ($segments as $segment) {
            if (! is_array($value) || ! array_key_exists($segment, $value)) {
                return $default;
            }

            $value = $value[$segment];
        }

        return $value;
    }

    public function set(string $key, mixed $value): void
    {
        $segments = explode('.', $key);
        $array =& $this->items;

        foreach ($segments as $segment) {
            if (! isset($array[$segment]) || ! is_array($array[$segment])) {
                $array[$segment] = [];
            }

            $array =& $array[$segment];
        }

        $array = $value;
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->items;
    }

    private function load(): void
    {
        $files = glob($this->configPath . '/*.php');
        if ($files === false) {
            throw new RuntimeException('Unable to read configuration directory');
        }

        foreach ($files as $file) {
            $name = basename($file, '.php');
            $this->items[$name] = require $file;
        }
    }
}
