<?php
declare(strict_types=1);

$root = dirname(__DIR__);
$configPath = $root . '/config/config.php';
$storagePaths = [
    $root . '/storage/cache',
    $root . '/storage/logs',
    $root . '/storage/sessions',
    $root . '/public/uploads',
];

$step = (int) ($_GET['step'] ?? 1);
$error = '';

if ($step === 2 && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $dbHost = trim((string) ($_POST['db_host'] ?? ''));
    $dbName = trim((string) ($_POST['db_name'] ?? ''));
    $dbUser = trim((string) ($_POST['db_user'] ?? ''));
    $dbPass = (string) ($_POST['db_pass'] ?? '');
    $charset = trim((string) ($_POST['db_charset'] ?? 'utf8mb4'));

    try {
        $dsn = sprintf('mysql:host=%s;charset=%s', $dbHost, $charset);
        $pdo = new PDO($dsn, $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $pdo->exec(sprintf('CREATE DATABASE IF NOT EXISTS `%s` CHARACTER SET %s COLLATE %s_unicode_ci', $dbName, $charset, $charset));
        $pdo->exec(sprintf('USE `%s`', $dbName));

        $schema = file_get_contents($root . '/database/schema.sql');
        $seed = file_get_contents($root . '/database/seed.sql');
        if ($schema === false || $seed === false) {
            throw new RuntimeException('SQL dosyaları okunamadı.');
        }
        $pdo->exec($schema);
        $pdo->exec($seed);

        $config = require $configPath;
        $config['database'] = [
            'host' => $dbHost,
            'port' => 3306,
            'database' => $dbName,
            'username' => $dbUser,
            'password' => $dbPass,
            'charset' => $charset,
        ];
        $config['app']['base_url'] = rtrim((string) ($_POST['app_url'] ?? ''), '/');
        $config['mail']['from'] = (string) ($_POST['mail_from'] ?? 'no-reply@' . parse_url($config['app']['base_url'], PHP_URL_HOST));
        $config['security']['app_key'] = bin2hex(random_bytes(32));

        $export = '<?php' . PHP_EOL . 'declare(strict_types=1);' . PHP_EOL . PHP_EOL . 'return ' . var_export($config, true) . ';' . PHP_EOL;
        file_put_contents($configPath, $export);

        $newInstallPath = $root . '/install_disabled_' . date('Ymd');
        if (is_dir($root . '/install')) {
            @rename($root . '/install', $newInstallPath);
        }
        header('Location: ' . ($config['app']['base_url'] ?: '/'));
        exit;
    } catch (Throwable $e) {
        $error = $e->getMessage();
        $step = 1;
    }
}

function checkRequirement(string $name, callable $callback): bool
{
    try {
        return $callback();
    } catch (Throwable) {
        return false;
    }
}

$requirements = [
    'PHP 8.1+' => checkRequirement('php', static fn () => version_compare(PHP_VERSION, '8.1', '>=')),
    'extension:curl' => checkRequirement('curl', static fn () => extension_loaded('curl')),
    'extension:pdo_mysql' => checkRequirement('pdo', static fn () => extension_loaded('pdo_mysql')),
    'extension:zip' => checkRequirement('zip', static fn () => extension_loaded('zip')),
    'extension:intl' => checkRequirement('intl', static fn () => extension_loaded('intl')),
    '.htaccess & mod_rewrite' => checkRequirement('rewrite', static fn () => true),
    'SSL aktif' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'),
];

$writeChecks = [];
foreach ($storagePaths as $path) {
    $writeChecks[$path] = is_writable($path) || (!file_exists($path) && mkdir($path, 0755, true));
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMS Onay Kurulum</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="mx-auto" style="max-width: 720px;">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h1 class="h4 fw-bold mb-4">SMS Onay Kurulum Sihirbazı</h1>
                <?php if ($error !== ''): ?>
                    <div class="alert alert-danger">Kurulum başarısız: <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
                <?php endif; ?>
                <?php if ($step === 1): ?>
                    <h2 class="h6 text-uppercase text-muted">Sistem Gereksinimleri</h2>
                    <ul class="list-group mb-4">
                        <?php foreach ($requirements as $label => $ok): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
                                <span class="badge bg-<?= $ok ? 'success' : 'danger'; ?>"><?= $ok ? 'Hazır' : 'Eksik'; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <h2 class="h6 text-uppercase text-muted">Yazma İzinleri</h2>
                    <ul class="list-group mb-4">
                        <?php foreach ($writeChecks as $path => $ok): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?= htmlspecialchars(str_replace($root, '.', $path), ENT_QUOTES, 'UTF-8'); ?>
                                <span class="badge bg-<?= $ok ? 'success' : 'danger'; ?>"><?= $ok ? 'Yazılabilir' : 'İzin Yok'; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <a class="btn btn-primary" href="?step=2">Devam Et</a>
                <?php else: ?>
                    <form method="post" action="?step=2">
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Veritabanı Host</label>
                                <input class="form-control" type="text" name="db_host" value="localhost" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Veritabanı Adı</label>
                                <input class="form-control" type="text" name="db_name" value="sms_onay" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kullanıcı</label>
                                <input class="form-control" type="text" name="db_user" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Şifre</label>
                                <input class="form-control" type="password" name="db_pass">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Charset</label>
                                <input class="form-control" type="text" name="db_charset" value="utf8mb4">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Site URL</label>
                                <input class="form-control" type="url" name="app_url" placeholder="https://alanadiniz.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Gönderen E-posta</label>
                                <input class="form-control" type="email" name="mail_from" placeholder="no-reply@alanadiniz.com">
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">Kurulumu Tamamla</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        <p class="text-center text-muted small mt-3">Kurulum sonrası install klasörü otomatik olarak devre dışı bırakılır.</p>
    </div>
</div>
</body>
</html>
