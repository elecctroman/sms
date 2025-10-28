<?php
declare(strict_types=1);

namespace App\Controllers\Site;

use App\Core\Controller;
use App\Core\Response;

final class RentController extends Controller
{
    public function index(): Response
    {
        return $this->render('site/rent_index', [
            'csrf_token' => $this->csrf->token('rent'),
        ]);
    }
}
