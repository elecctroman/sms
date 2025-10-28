<?php
declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected View $view;
    protected Session $session;
    protected CSRF $csrf;

    public function __construct(View $view, Session $session, CSRF $csrf)
    {
        $this->view = $view;
        $this->session = $session;
        $this->csrf = $csrf;
    }

    protected function render(string $template, array $data = []): Response
    {
        return new Response($this->view->render($template, $data));
    }

    protected function redirect(string $url): Response
    {
        $response = new Response('', 302);
        $response->setHeader('Location', $url);

        return $response;
    }

    protected function json(array $payload, int $status = 200): Response
    {
        return new Response(json_encode($payload, JSON_THROW_ON_ERROR), $status, ['Content-Type' => 'application/json']);
    }
}
