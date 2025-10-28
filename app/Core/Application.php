<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Config\ConfigRepository;
use App\Core\Localization\Translator;
use App\Core\Routing\Router;
use App\Core\Session\SessionManager;
use App\Core\Logger\Logger;
use App\Http\Request;
use App\Http\Response;

class Application
{
    public function __construct(
        private readonly string $rootPath,
        private readonly Container $container,
        private readonly ConfigRepository $config,
        private readonly Router $router,
        private readonly SessionManager $sessionManager,
        private readonly Translator $translator,
        private readonly Logger $logger
    ) {
    }

    public function run(): void
    {
        $request = Request::fromGlobals();
        $response = $this->router->dispatch($request);
        $this->sendResponse($response);
    }

    public function cli(array $argv): void
    {
        $kernel = new Console\Kernel($this->container, $this->config, $this->logger);
        $kernel->handle($argv);
    }

    private function sendResponse(Response $response): void
    {
        $response->send();
    }

    public function getRootPath(): string
    {
        return $this->rootPath;
    }

    public function getSessionManager(): SessionManager
    {
        return $this->sessionManager;
    }

    public function getTranslator(): Translator
    {
        return $this->translator;
    }

    public function getLogger(): Logger
    {
        return $this->logger;
    }

    public function getContainer(): Container
    {
        return $this->container;
    }

    public function getConfig(): ConfigRepository
    {
        return $this->config;
    }
}
