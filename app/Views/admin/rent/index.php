<?php
$title = 'Numara Kirala';
$page_title = 'Numara Kirala';
$options = $options ?? [
    ['period' => 'hourly', 'label' => 'Saatlik', 'price' => 10],
    ['period' => 'daily', 'label' => 'Günlük', 'price' => 60],
    ['period' => 'weekly', 'label' => 'Haftalık', 'price' => 350],
];
$activeRental = $activeRental ?? ['number' => '+90 555 555 55 55', 'ends_at' => '2023-10-12 18:00'];
ob_start();
?>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card p-4">
            <h2 class="h5">Numara Kirala</h2>
            <form>
                <?php foreach ($options as $index => $option): ?>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="period" id="period_<?= $view->escape($option['period'] ?? $index); ?>" <?= $index === 0 ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="period_<?= $view->escape($option['period'] ?? $index); ?>"><?= $view->escape($option['label'] ?? ''); ?> - ₺<?= $view->escape((string) ($option['price'] ?? 0)); ?></label>
                    </div>
                <?php endforeach; ?>
                <button class="btn btn-primary w-100 mt-3">Kirala</button>
            </form>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card p-4">
            <h2 class="h5">Aktif Kiralama</h2>
            <p class="mb-1">Numara: <strong><?= $view->escape($activeRental['number'] ?? '-'); ?></strong></p>
            <p class="mb-3">Bitiş: <?= $view->escape($activeRental['ends_at'] ?? '-'); ?></p>
            <div class="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar" style="width: 60%;">Kalan süre %60</div>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
