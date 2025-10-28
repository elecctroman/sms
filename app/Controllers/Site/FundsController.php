<?php
declare(strict_types=1);

namespace App\Controllers\Site;

use App\Core\Controller;
use App\Core\Response;

final class FundsController extends Controller
{
    public function index(): Response
    {
        return $this->render('site/add_funds', [
            'csrf_token' => $this->csrf->token('funds'),
        ]);
    }
}
