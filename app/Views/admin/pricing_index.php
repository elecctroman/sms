<?php
declare(strict_types=1);
ob_start();
?>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h4 fw-bold">Fiyatlandırma</h1>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary" type="button">Toplu Marj</button>
                <button class="btn btn-primary" type="button">Senkron Et</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Servis</th>
                        <th>Ülke</th>
                        <th>Satış</th>
                        <th>Alış</th>
                        <th>Stok</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $service): ?>
                        <tr>
                            <td><?= $escape($service['name']); ?></td>
                            <td>Global</td>
                            <td>0.95 USD</td>
                            <td>0.65 USD</td>
                            <td>25</td>
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
