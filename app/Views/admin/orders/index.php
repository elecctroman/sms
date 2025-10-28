<?php
$title = 'Siparişler';
$page_title = 'Siparişlerim';
$orders = $orders ?? [];
ob_start();
?>
<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h4 mb-0">Siparişlerim</h2>
        <a href="/orders/new" class="btn btn-primary">Yeni Sipariş</a>
    </div>
    <table class="table align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Servis</th>
                <th>Durum</th>
                <th>Kod</th>
                <th>Tarih</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= $view->escape((string) ($order['id'] ?? '')); ?></td>
                    <td><?= $view->escape($order['service'] ?? ''); ?></td>
                    <td><span class="badge badge-status <?= $view->escape($order['status'] ?? ''); ?> text-uppercase"><?= $view->escape($order['status'] ?? ''); ?></span></td>
                    <td>
                        <?php if (! empty($order['code'])): ?>
                            <button class="btn btn-sm btn-outline-secondary" data-copy="<?= $view->escape($order['code']); ?>"><?= $view->escape($order['code']); ?></button>
                        <?php else: ?>
                            <span class="text-muted">Bekleniyor</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $view->escape($order['created_at'] ?? ''); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
