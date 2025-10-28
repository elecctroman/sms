<?php
declare(strict_types=1);
ob_start();
?>
<section class="py-5">
    <h1 class="h3 fw-bold mb-4">Kontrol Paneli</h1>
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <p class="text-muted mb-1">Bakiye</p>
                    <h2 class="h4 fw-semibold"><?= $escape(number_format((float) ($wallet['balance_decimal'] ?? 0), 2)); ?> <?= $escape($wallet['currency'] ?? 'TRY'); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <p class="text-muted mb-1">Son Sipariş</p>
                    <h2 class="h4 fw-semibold"><?= $escape($recentOrders[0]['status'] ?? 'Henüz yok'); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <p class="text-muted mb-1">Favori Servis</p>
                    <h2 class="h4 fw-semibold"><?= $escape($favorites[0]['name'] ?? 'Henüz seçilmedi'); ?></h2>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h2 class="h5 fw-semibold mb-3">Son Siparişler</h2>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Servis</th>
                            <th>Durum</th>
                            <th>Tutar</th>
                            <th>Tarih</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentOrders as $order): ?>
                            <tr>
                                <td><?= $escape((string) $order['id']); ?></td>
                                <td><?= $escape((string) $order['service_id']); ?></td>
                                <td><span class="badge bg-primary"><?= $escape((string) $order['status']); ?></span></td>
                                <td><?= $escape((string) $order['charge']); ?> <?= $escape((string) $order['currency']); ?></td>
                                <td><?= $escape((string) $order['created_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h2 class="h5 fw-semibold mb-3">Son 30 Gün Cirosu</h2>
            <canvas id="dailyChart" height="160"></canvas>
        </div>
    </div>
</section>
<script>
if (window.Chart) {
    const ctx = document.getElementById('dailyChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($dailyRevenue, 'day')); ?>,
                datasets: [{
                    label: 'Ciro',
                    data: <?= json_encode(array_map('floatval', array_column($dailyRevenue, 'total'))); ?>,
                    backgroundColor: '#0ea5e9'
                }]
            },
            options: {responsive: true, maintainAspectRatio: false}
        });
    }
}
</script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../_layout/layout.php';
