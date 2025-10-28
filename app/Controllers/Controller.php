<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Config\ConfigRepository;
use App\Core\Localization\Translator;
use App\Core\Session\SessionManager;
use App\Core\View\ViewRenderer;
use Symfony\Component\HttpFoundation\Response;

abstract class Controller
{
    protected ViewRenderer $view;

    public function __construct(
        protected readonly ConfigRepository $config,
        protected readonly SessionManager $session,
        protected readonly Translator $translator
    ) {
        $this->view = new ViewRenderer($config, __DIR__ . '/../Views');
        $this->view->extendWithTranslator($translator);
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function render(string $template, array $data = []): Response
    {
        return new Response($this->view->render($template, $data));
    }

    protected function redirect(string $url): Response
    {
        return new Response('', Response::HTTP_FOUND, ['Location' => $url]);
    }
}
