<?php

declare(strict_types=1);

namespace App\Core\Cache;

use App\Core\Config\ConfigRepository;

class CacheManager
{
    public function __construct(private readonly ConfigRepository $config)
    {
    }

    public function driver(?string $name = null): CacheInterface
    {
        $name ??= $this->config->get('cache.default', 'file');

        return match ($name) {
            'file' => new FileCache($this->config->get('cache.stores.file.path')),
            default => throw new \RuntimeException('Unsupported cache driver'),
        };
    }
}
