<?php

declare(strict_types=1);

namespace App\Core\View;

use App\Core\Config\ConfigRepository;
use App\Core\Localization\Translator;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;

class ViewRenderer
{
    private Environment $twig;

    public function __construct(ConfigRepository $config, string $viewPath)
    {
        $loader = new FilesystemLoader($viewPath);
        $this->twig = new Environment($loader, [
            'cache' => false,
            'strict_variables' => false,
            'autoescape' => 'html',
        ]);

        $this->twig->addGlobal('app', [
            'name' => $config->get('app.name'),
            'url' => $config->get('app.url'),
        ]);
    }

    public function extendWithTranslator(Translator $translator): void
    {
        $this->twig->addFilter(new TwigFilter('trans', static fn (string $key): string => $translator->get($key)));
    }

    /**
     * @param array<string, mixed> $data
     */
    public function render(string $template, array $data = []): string
    {
        return $this->twig->render($template, $data);
    }

    public function getEnvironment(): Environment
    {
        return $this->twig;
    }
}
