<?php
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Yönetim Paneli</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/app.min.css">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="/admin">SMS Onay Yönetim</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav" aria-controls="adminNav" aria-expanded="false" aria-label="Menü">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="/admin">Gösterge</a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/users">Kullanıcılar</a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/suppliers">Tedarikçiler</a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/pricing">Fiyatlandırma</a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/settings">Ayarlar</a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/system-check">Sistem Kontrol</a></li>
            </ul>
            <a class="btn btn-outline-light" href="/">Siteye Dön</a>
        </div>
    </div>
</nav>
<main class="container-fluid py-5">
    <?= $content ?? ''; ?>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="/assets/js/app.min.js"></script>
</body>
</html>
