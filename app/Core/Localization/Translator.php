<?php

declare(strict_types=1);

namespace App\Core\Localization;

use Symfony\Component\Translation\Loader\PhpFileLoader;
use Symfony\Component\Translation\Translator as SymfonyTranslator;

class Translator
{
    private SymfonyTranslator $translator;

    public function __construct(
        string $locale,
        string $fallback,
        string $resourcePath
    ) {
        $this->translator = new SymfonyTranslator($locale);
        $this->translator->setFallbackLocales([$fallback]);
        $this->translator->addLoader('php', new PhpFileLoader());

        foreach (glob($resourcePath . '/*/*.php') as $file) {
            if ($file === false) {
                continue;
            }

            $parts = explode('/', str_replace('\\', '/', (string) $file));
            $count = count($parts);
            if ($count < 2) {
                continue;
            }

            $localeKey = $parts[$count - 2];
            $filename = basename($file, '.php');

            $this->translator->addResource('php', $file, $localeKey, $filename);
        }
    }

    public function get(string $key, array $replace = [], ?string $locale = null): string
    {
        $message = $this->translator->trans($key, $replace, null, $locale);

        return is_string($message) ? $message : (string) $message;
    }
}
