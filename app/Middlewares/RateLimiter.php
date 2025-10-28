<?php
declare(strict_types=1);

namespace App\Middlewares;

use App\Core\Config;
use App\Core\Response;
use App\Lib\SimpleCache;

final class RateLimiter
{
    private SimpleCache $cache;

    public function __construct()
    {
        $this->cache = new SimpleCache(__DIR__ . '/../../storage/cache/rate');
    }

    /**
     * @param array<string, mixed> $request
     */
    public function __invoke(array $request): ?Response
    {
        $settings = Config::get('security.rate_limit', ['window' => 60, 'max_attempts' => 20]);
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'guest';
        $key = 'rate:' . ($request['params']['route'] ?? 'global') . ':' . $ip;
        $attempts = $this->cache->increment($key, (int) ($settings['window'] ?? 60));
        if ($attempts > (int) ($settings['max_attempts'] ?? 20)) {
            return new Response('Çok fazla istek. Lütfen daha sonra tekrar deneyin.', 429);
        }

        return null;
    }
}
