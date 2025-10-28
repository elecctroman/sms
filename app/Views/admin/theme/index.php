<?php
$title = 'Tema Yönetimi';
$page_title = 'Tema Yönetimi';
$themes = $themes ?? [
    ['name' => 'Varsayılan Landing', 'slug' => 'landing/default'],
    ['name' => 'Koyu Admin', 'slug' => 'admin/dark'],
];
$activeTheme = $activeTheme ?? 'landing/default';
ob_start();
?>
<div class="card p-4">
    <h2 class="h4 mb-3">Tema Yönetimi</h2>
    <p>Aktif tema: <strong><?= $view->escape($activeTheme); ?></strong></p>
    <div class="row g-3">
        <?php foreach ($themes as $theme): ?>
            <div class="col-md-4">
                <div class="card p-3 h-100">
                    <h5><?= $view->escape($theme['name'] ?? 'Tema'); ?></h5>
                    <p class="text-muted">Slug: <?= $view->escape($theme['slug'] ?? ''); ?></p>
                    <button class="btn btn-outline-primary">Aktif Et</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
