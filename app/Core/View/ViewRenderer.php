<?php

declare(strict_types=1);

namespace App\Core\View;

use App\Core\Config\ConfigRepository;
use App\Core\Localization\Translator;
use InvalidArgumentException;

class ViewRenderer
{
    public function __construct(
        private readonly ConfigRepository $config,
        private readonly string $viewPath,
        private readonly Translator $translator
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public function render(string $template, array $data = []): string
    {
        $file = $this->resolvePath($template);

        $view = $this;
        $app = [
            'name' => $this->config->get('app.name'),
            'url' => $this->config->get('app.url'),
        ];

        extract($data, EXTR_SKIP);

        ob_start();
        include $file;

        return (string) ob_get_clean();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function partial(string $template, array $data = []): string
    {
        return $this->render($template, $data);
    }

    public function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    public function translate(string $key, array $replace = []): string
    {
        return $this->translator->get($key, $replace);
    }

    private function resolvePath(string $template): string
    {
        $template = str_replace(['..', '\\'], ['', '/'], $template);
        $path = rtrim($this->viewPath, '/\\') . '/' . ltrim($template, '/');
        if (! str_ends_with($path, '.php')) {
            $path .= '.php';
        }

        if (! is_file($path)) {
            throw new InvalidArgumentException('View not found: ' . $template);
        }

        return $path;
    }
}
