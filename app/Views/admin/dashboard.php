<?php
declare(strict_types=1);
ob_start();
?>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <p class="text-muted mb-1">Son 30 Gün</p>
                <h2 class="h4 fw-semibold"><?= count($daily); ?> günlük kayıt</h2>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h2 class="h5 fw-semibold mb-3">Günlük Ciro</h2>
                <canvas id="adminDaily" height="160"></canvas>
            </div>
        </div>
    </div>
</div>
<div class="card shadow-sm border-0 mt-4">
    <div class="card-body">
        <h2 class="h5 fw-semibold mb-3">Aylık Ciro</h2>
        <canvas id="adminMonthly" height="160"></canvas>
    </div>
</div>
<script>
if (window.Chart) {
    const daily = document.getElementById('adminDaily');
    if (daily) {
        new Chart(daily, {
            type: 'line',
            data: {
                labels: <?= json_encode(array_column($daily, 'day')); ?>,
                datasets: [{
                    label: 'Ciro',
                    data: <?= json_encode(array_map('floatval', array_column($daily, 'total'))); ?>,
                    borderColor: '#ffffff',
                    backgroundColor: 'rgba(255,255,255,0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {responsive: true, maintainAspectRatio: false}
        });
    }
    const monthly = document.getElementById('adminMonthly');
    if (monthly) {
        new Chart(monthly, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($monthly, 'month')); ?>,
                datasets: [{
                    label: 'Ciro',
                    data: <?= json_encode(array_map('floatval', array_column($monthly, 'total'))); ?>,
                    backgroundColor: '#4f46e5'
                }]
            },
            options: {responsive: true, maintainAspectRatio: false}
        });
    }
}
</script>
<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
