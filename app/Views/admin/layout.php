<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $view->escape($title ?? 'Yönetim Paneli'); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/app.css">
</head>
<body>
<div class="d-flex">
    <aside class="admin-sidebar p-4">
        <h2 class="fs-4">SMS Verify</h2>
        <nav class="nav flex-column mt-4">
            <a class="nav-link text-white-50" href="/dashboard">Dashboard</a>
            <a class="nav-link text-white-50" href="/orders">Siparişler</a>
            <a class="nav-link text-white-50" href="/orders/new">Yeni Sipariş</a>
            <a class="nav-link text-white-50" href="/rent-number">Numara Kirala</a>
            <a class="nav-link text-white-50" href="/add-funds">Bakiye Ekle</a>
            <a class="nav-link text-white-50" href="/support">Destek</a>
            <a class="nav-link text-white-50" href="/admin/themes">Temalar</a>
        </nav>
    </aside>
    <main class="flex-fill bg-light" style="min-height:100vh;">
        <header class="bg-white shadow-sm py-3 px-4 d-flex justify-content-between align-items-center sticky-top">
            <h1 class="h4 mb-0"><?= $view->escape($page_title ?? 'Panel'); ?></h1>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-success">2FA Aktif</span>
                <button class="btn btn-outline-secondary btn-sm">Çıkış Yap</button>
            </div>
        </header>
        <div class="container-fluid py-4">
            <?= $content ?? '' ?>
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/app.js"></script>
</body>
</html>
