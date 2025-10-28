<?php
declare(strict_types=1);
/** @var array{app?: array<string, mixed>} $app */
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $escape($app['name'] ?? 'SMS Onay Platformu'); ?></title>
    <meta name="description" content="SMS Onay Scripti">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link rel="stylesheet" href="/assets/css/app.min.css">
</head>
<body class="bg-light" style="font-family: 'Inter', sans-serif;">
<?php include __DIR__ . '/partials/nav.php'; ?>
<main class="py-5">
    <div class="container">
        <?php include __DIR__ . '/partials/alerts.php'; ?>
        <?= $content ?? ''; ?>
    </div>
</main>
<?php include __DIR__ . '/partials/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>if (window.AOS) { AOS.init({ once: true }); }</script>
<script src="/assets/js/app.min.js"></script>
</body>
</html>
