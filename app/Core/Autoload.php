<?php
declare(strict_types=1);

namespace App\Core;

final class Autoload
{
    private string $baseDir;

    public function __construct(string $baseDir)
    {
        $this->baseDir = rtrim($baseDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    public static function register(string $baseDir): self
    {
        $instance = new self($baseDir);
        spl_autoload_register([$instance, 'loadClass']);

        return $instance;
    }

    public function loadClass(string $class): void
    {
        if (strpos($class, 'App\\') !== 0) {
            return;
        }

        $relative = substr($class, 4);
        $relativePath = str_replace('\\', DIRECTORY_SEPARATOR, $relative) . '.php';
        $file = $this->baseDir . 'app' . DIRECTORY_SEPARATOR . $relativePath;

        if (is_file($file)) {
            require_once $file;
        }
    }
}
