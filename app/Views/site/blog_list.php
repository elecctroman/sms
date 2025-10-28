<?php
declare(strict_types=1);
ob_start();
?>
<section class="py-5">
    <h1 class="h3 fw-bold mb-4">Blog</h1>
    <div class="row g-4">
        <?php foreach ($posts as $post): ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h2 class="h5"><?= $escape($post['title']); ?></h2>
                        <p class="text-muted"><?= $escape(substr((string) strip_tags((string) $post['body_html']), 0, 120)); ?>...</p>
                        <a class="text-decoration-none" href="/blog/<?= $escape($post['slug']); ?>">Devamını oku</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php
$content = ob_get_clean();
include __DIR__ . '/../_layout/layout.php';
