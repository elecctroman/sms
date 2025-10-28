<?php
$title = 'Yönetim Paneli';
$page_title = 'Dashboard';
$stats = $stats ?? ['balance' => '0.00', 'spent' => '0.00', 'orders' => 0];
$favoriteServices = $favoriteServices ?? [];
ob_start();
?>
<div class="row g-4">
    <div class="col-md-4">
        <div class="card p-4">
            <h5>Bakiye</h5>
            <p class="display-6 text-primary">₺<?= $view->escape((string) $stats['balance']); ?></p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-4">
            <h5>Harcanan</h5>
            <p class="display-6 text-danger">₺<?= $view->escape((string) $stats['spent']); ?></p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-4">
            <h5>Sipariş</h5>
            <p class="display-6 text-success"><?= $view->escape((string) $stats['orders']); ?></p>
        </div>
    </div>
</div>
<section class="mt-5">
    <h2 class="h4 mb-3">Hızlı Bağlantılar</h2>
    <div class="d-flex gap-3 flex-wrap">
        <a href="/add-funds" class="btn btn-outline-primary">Bakiye Ekle</a>
        <a href="/support" class="btn btn-outline-primary">Destek</a>
        <a href="/orders" class="btn btn-outline-primary">Siparişlerim</a>
        <a href="/faq" class="btn btn-outline-primary">SSS</a>
    </div>
</section>
<section class="mt-5">
    <h2 class="h4 mb-3">Favori Servisler</h2>
    <div class="row g-3">
        <?php if ($favoriteServices === []): ?>
            <div class="col-12">
                <div class="alert alert-light text-center">Henüz favori servis yok.</div>
            </div>
        <?php else: ?>
            <?php foreach ($favoriteServices as $service): ?>
                <div class="col-md-3">
                    <div class="card p-3 text-center" style="border-top:4px solid <?= $view->escape($service['color'] ?? '#0d6efd'); ?>;">
                        <i class="bi <?= $view->escape($service['icon'] ?? 'bi-star'); ?> fs-1" style="color: <?= $view->escape($service['color'] ?? '#0d6efd'); ?>"></i>
                        <h5 class="mt-2"><?= $view->escape($service['name'] ?? 'Servis'); ?></h5>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
