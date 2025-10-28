<?php
declare(strict_types=1);
ob_start();
?>
<section class="py-5">
    <h1 class="h2 fw-bold mb-3"><?= $escape($post['title']); ?></h1>
    <p class="text-muted">Yayın tarihi: <?= $escape((string) $post['published_at']); ?></p>
    <article class="mt-4">
        <?= $post['body_html']; ?>
    </article>
</section>
<?php
$content = ob_get_clean();
include __DIR__ . '/../_layout/layout.php';
