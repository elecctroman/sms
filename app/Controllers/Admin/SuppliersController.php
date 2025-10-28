<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\CSRF;
use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Core\View;
use App\Repositories\SupplierRepository;

final class SuppliersController extends Controller
{
    private SupplierRepository $suppliers;

    public function __construct(View $view, Session $session, CSRF $csrf)
    {
        parent::__construct($view, $session, $csrf);
        $this->suppliers = new SupplierRepository();
    }

    public function index(): Response
    {
        return $this->render('admin/suppliers_index', [
            'suppliers' => $this->suppliers->allEnabled(),
        ]);
    }
}
