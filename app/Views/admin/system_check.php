<?php
declare(strict_types=1);
ob_start();
?>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <h1 class="h4 fw-bold mb-4">Sistem Kontrol</h1>
        <h2 class="h6 text-uppercase text-muted">Sürüm</h2>
        <p class="mb-4">PHP Sürümü: <?= $escape((string) $requirements['php_version']); ?></p>
        <h2 class="h6 text-uppercase text-muted">Eklentiler</h2>
        <ul class="list-group mb-4">
            <?php foreach ($requirements['extensions'] as $extension => $available): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= strtoupper($escape($extension)); ?>
                    <span class="badge bg-<?= $available ? 'success' : 'danger'; ?>"><?= $available ? 'Hazır' : 'Eksik'; ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
        <h2 class="h6 text-uppercase text-muted">Yazma İzinleri</h2>
        <ul class="list-group">
            <?php foreach ($requirements['writable'] as $path => $isWritable): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= $escape($path); ?>
                    <span class="badge bg-<?= $isWritable ? 'success' : 'danger'; ?>"><?= $isWritable ? 'Yazılabilir' : 'İzin Yok'; ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
