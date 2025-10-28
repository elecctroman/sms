<?php
$title = 'Bakiye Ekle';
$page_title = 'Bakiye Ekle';
$methods = $methods ?? [
    ['name' => 'Kredi Kartı', 'fee' => 0.029, 'min' => 50],
    ['name' => 'Havale/EFT', 'fee' => 0.0, 'min' => 100],
];
ob_start();
?>
<div class="card p-4">
    <h2 class="h4 mb-4">Bakiye Ekle</h2>
    <div class="row g-4">
        <?php foreach ($methods as $method): ?>
            <div class="col-md-4">
                <div class="card p-3 h-100">
                    <h3 class="h5"><?= $view->escape($method['name'] ?? 'Yöntem'); ?></h3>
                    <p>Komisyon: %<?= $view->escape(number_format((float) ($method['fee'] ?? 0) * 100, 1)); ?></p>
                    <p>Minimum: ₺<?= $view->escape((string) ($method['min'] ?? 0)); ?></p>
                    <button class="btn btn-primary">Devam Et</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
