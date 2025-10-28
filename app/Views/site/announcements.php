<?php
declare(strict_types=1);
ob_start();
?>
<section class="py-5">
    <h1 class="h3 fw-bold mb-4">Duyurular</h1>
    <div class="list-group">
        <?php foreach ($announcements as $announcement): ?>
            <a class="list-group-item list-group-item-action" href="#">
                <div class="d-flex w-100 justify-content-between">
                    <h2 class="h6 mb-1"><?= $escape($announcement['title']); ?></h2>
                    <small class="text-muted"><?= $escape((string) $announcement['published_at']); ?></small>
                </div>
                <p class="mb-1 text-muted"><?= $escape(substr((string) $announcement['body'], 0, 120)); ?>...</p>
            </a>
        <?php endforeach; ?>
    </div>
</section>
<?php
$content = ob_get_clean();
include __DIR__ . '/../_layout/layout.php';
