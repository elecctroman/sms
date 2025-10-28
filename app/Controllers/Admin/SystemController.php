<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Response;

final class SystemController extends Controller
{
    public function check(): Response
    {
        $root = dirname(__DIR__, 3);
        $requirements = [
            'php_version' => PHP_VERSION,
            'extensions' => [
                'curl' => extension_loaded('curl'),
                'pdo_mysql' => extension_loaded('pdo_mysql'),
                'zip' => extension_loaded('zip'),
                'intl' => extension_loaded('intl'),
            ],
            'writable' => [
                'storage/cache' => is_writable($root . '/storage/cache'),
                'storage/logs' => is_writable($root . '/storage/logs'),
                'storage/sessions' => is_writable($root . '/storage/sessions'),
            ],
        ];

        return $this->render('admin/system_check', [
            'requirements' => $requirements,
        ]);
    }
}
