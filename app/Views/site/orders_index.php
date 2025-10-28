<?php
declare(strict_types=1);
ob_start();
?>
<section class="py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold">Siparişlerim</h1>
        <a class="btn btn-primary" href="/orders/new">Yeni Sipariş</a>
    </div>
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Servis</th>
                            <th>Durum</th>
                            <th>Numara</th>
                            <th>Kod</th>
                            <th>Ücret</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?= $escape((string) $order['id']); ?></td>
                                <td><?= $escape((string) $order['service_id']); ?></td>
                                <td><span class="badge bg-info text-dark"><?= $escape((string) $order['status']); ?></span></td>
                                <td><?= $escape((string) $order['phone_number']); ?></td>
                                <td>
                                    <?php if (!empty($order['received_code'])): ?>
                                        <code><?= $escape((string) $order['received_code']); ?></code>
                                    <?php else: ?>
                                        <span class="text-muted">Bekleniyor</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $escape((string) $order['charge']); ?> <?= $escape((string) $order['currency']); ?></td>
                                <td>
                                    <form method="post" action="/orders/cancel" class="d-inline">
                                        <input type="hidden" name="_token" value="<?= $escape($csrf_token); ?>">
                                        <input type="hidden" name="order_id" value="<?= $escape((string) $order['id']); ?>">
                                        <button class="btn btn-sm btn-outline-danger" type="submit">İptal</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<?php
$content = ob_get_clean();
include __DIR__ . '/../_layout/layout.php';
