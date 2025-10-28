<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Config;

final class TranslationService
{
    private array $messages = [];

    public function __construct()
    {
        $locale = (string) Config::get('app.default_locale', 'tr');
        $fallback = (string) Config::get('app.fallback_locale', 'en');
        $this->messages = $this->loadLocale($fallback);
        $this->messages = array_replace($this->messages, $this->loadLocale($locale));
    }

    public function trans(string $key, array $replace = []): string
    {
        $message = $this->messages[$key] ?? $key;
        foreach ($replace as $search => $value) {
            $message = str_replace(':' . $search, (string) $value, $message);
        }

        return $message;
    }

    private function loadLocale(string $locale): array
    {
        $file = __DIR__ . '/../i18n/' . $locale . '.php';
        if (is_file($file)) {
            /** @var array<string, string> $messages */
            $messages = require $file;
            return $messages;
        }

        return [];
    }
}
