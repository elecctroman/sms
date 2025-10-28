<?php
$title = 'Destek';
$page_title = 'Destek Biletleri';
$tickets = $tickets ?? [];
ob_start();
?>
<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h4 mb-0">Destek Biletleri</h2>
        <a href="/support/create" class="btn btn-primary">Yeni Bilet</a>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Konu</th>
                <th>Durum</th>
                <th>Öncelik</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tickets as $ticket): ?>
                <tr>
                    <td><?= $view->escape((string) ($ticket['id'] ?? '')); ?></td>
                    <td><?= $view->escape($ticket['subject'] ?? ''); ?></td>
                    <td><span class="badge bg-info text-uppercase"><?= $view->escape($ticket['status'] ?? ''); ?></span></td>
                    <td><span class="badge bg-warning text-uppercase"><?= $view->escape($ticket['priority'] ?? ''); ?></span></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
