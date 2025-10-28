<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\CSRF;
use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Core\View;
use App\Repositories\UserRepository;

final class UsersController extends Controller
{
    private UserRepository $users;

    public function __construct(View $view, Session $session, CSRF $csrf)
    {
        parent::__construct($view, $session, $csrf);
        $this->users = new UserRepository();
    }

    public function index(): Response
    {
        return $this->render('admin/users_index', [
            'users' => $this->users->all(),
        ]);
    }
}
