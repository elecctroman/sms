<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\CSRF;
use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Core\View;
use App\Lib\Validation;
use App\Repositories\SettingsRepository;

final class SettingsController extends Controller
{
    private SettingsRepository $settings;

    public function __construct(View $view, Session $session, CSRF $csrf)
    {
        parent::__construct($view, $session, $csrf);
        $this->settings = new SettingsRepository();
    }

    public function index(): Response
    {
        return $this->render('admin/settings_index', [
            'settings' => $this->settings->getAll(),
            'csrf_token' => $this->csrf->token('settings_update'),
        ]);
    }

    public function update(array $request): Response
    {
        if (!$this->csrf->validate('settings_update', $request['body']['_token'] ?? null)) {
            return new Response('Geçersiz CSRF token', 400);
        }

        $data = $request['body'];
        $errors = Validation::validate($data, [
            'site_title' => ['required'],
        ]);

        if ($errors !== []) {
            return $this->render('admin/settings_index', [
                'settings' => $this->settings->getAll(),
                'errors' => $errors,
                'csrf_token' => $this->csrf->token('settings_update'),
            ]);
        }

        $this->settings->update('theme', [
            'site_title' => (string) $data['site_title'],
            'primary_color' => (string) $data['primary_color'],
        ]);

        return $this->redirect('/admin/settings');
    }
}
