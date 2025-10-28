<?php

declare(strict_types=1);

namespace App\Core\Localization;

class Translator
{
    /** @var array<string, array<string, mixed>> */
    private array $messages = [];

    public function __construct(
        private string $locale,
        private readonly string $fallback,
        string $resourcePath
    ) {
        $this->loadResources($resourcePath);
    }

    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @param array<string, string|int|float> $replace
     */
    public function get(string $key, array $replace = [], ?string $locale = null): string
    {
        $localeToUse = $locale ?? $this->locale;
        $line = $this->locateLine($localeToUse, $key)
            ?? $this->locateLine($localeToUse, 'messages.' . $key)
            ?? $this->locateLine($this->fallback, $key)
            ?? $this->locateLine($this->fallback, 'messages.' . $key)
            ?? $key;

        foreach ($replace as $search => $value) {
            $line = str_replace(':' . $search, (string) $value, $line);
        }

        return $line;
    }

    private function locateLine(string $locale, string $key): ?string
    {
        $segments = explode('.', $key);
        $messages = $this->messages[$locale] ?? null;
        if ($messages === null) {
            return null;
        }

        if (array_key_exists($key, $messages) && is_string($messages[$key])) {
            return $messages[$key];
        }

        $value = $messages;
        foreach ($segments as $segment) {
            if (! is_array($value) || ! array_key_exists($segment, $value)) {
                return null;
            }

            $value = $value[$segment];
        }

        return is_string($value) ? $value : null;
    }

    private function loadResources(string $resourcePath): void
    {
        $directories = glob(rtrim($resourcePath, '/\\') . '/*', GLOB_ONLYDIR);
        if ($directories === false) {
            return;
        }

        foreach ($directories as $directory) {
            $locale = basename($directory);
            $files = glob($directory . '/*.php');
            if ($files === false) {
                continue;
            }

            foreach ($files as $file) {
                $key = basename($file, '.php');
                $messages = include $file;
                if (! isset($this->messages[$locale])) {
                    $this->messages[$locale] = [];
                }

                if (is_array($messages)) {
                    $this->messages[$locale][$key] = $messages;
                }
            }
        }
    }
}
