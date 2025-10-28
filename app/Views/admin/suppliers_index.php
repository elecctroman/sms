<?php
declare(strict_types=1);
ob_start();
?>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h4 fw-bold">Tedarikçiler</h1>
            <button class="btn btn-outline-primary" type="button">Tek Tuşla Senkron</button>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Ad</th>
                        <th>Durum</th>
                        <th>Para Birimi</th>
                        <th>Marj</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($suppliers as $supplier): ?>
                        <tr>
                            <td><?= $escape($supplier['name']); ?></td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td><?= $escape($supplier['currency']); ?></td>
                            <td><?= $escape((string) $supplier['price_markup_percent']); ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
