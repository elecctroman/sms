<?php
declare(strict_types=1);

namespace App\Core;

use App\Core\Security as Sec;

final class View
{
    private string $basePath;
    private array $shared = [];

    public function __construct(string $basePath)
    {
        $this->basePath = rtrim($basePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    public function share(string $key, mixed $value): void
    {
        $this->shared[$key] = $value;
    }

    public function render(string $template, array $data = []): string
    {
        $path = $this->basePath . str_replace('.', DIRECTORY_SEPARATOR, $template) . '.php';
        if (!is_file($path)) {
            throw new \RuntimeException('View not found: ' . $path);
        }

        $data = array_merge($this->shared, $data);
        extract($data, EXTR_SKIP);
        $escape = static fn (string $value): string => Sec::escape($value);

        ob_start();
        include $path;

        return (string) ob_get_clean();
    }
}
