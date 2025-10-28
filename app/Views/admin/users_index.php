<?php
declare(strict_types=1);
ob_start();
?>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <h1 class="h4 fw-bold mb-4">Kullanıcılar</h1>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ad</th>
                        <th>E-posta</th>
                        <th>Rol</th>
                        <th>Durum</th>
                        <th>Oluşturma</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $escape((string) $user['id']); ?></td>
                            <td><?= $escape($user['name']); ?></td>
                            <td><?= $escape($user['email']); ?></td>
                            <td><span class="badge bg-secondary"><?= $escape($user['role']); ?></span></td>
                            <td><span class="badge bg-success"><?= $escape($user['status']); ?></span></td>
                            <td><?= $escape((string) $user['created_at']); ?></td>
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
