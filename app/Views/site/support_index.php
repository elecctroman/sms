<?php
declare(strict_types=1);
$errors = $errors ?? [];
ob_start();
?>
<section class="py-5">
    <div class="row g-4">
        <div class="col-md-6">
            <h1 class="h3 fw-bold mb-4">Destek Taleplerim</h1>
            <div class="list-group shadow-sm">
                <?php foreach ($tickets as $ticket): ?>
                    <div class="list-group-item">
                        <h2 class="h6 mb-1"><?= $escape($ticket['subject']); ?></h2>
                        <p class="mb-1 text-muted">Durum: <?= $escape($ticket['status']); ?> · Öncelik: <?= $escape($ticket['priority']); ?></p>
                        <small class="text-muted"><?= $escape((string) $ticket['created_at']); ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h2 class="h5 fw-semibold mb-3">Yeni Destek Talebi</h2>
                    <form method="post" action="/support">
                        <input type="hidden" name="_token" value="<?= $escape($csrf_token); ?>">
                        <div class="mb-3">
                            <label class="form-label" for="subject">Konu</label>
                            <input class="form-control" type="text" id="subject" name="subject" required>
                            <?php if (!empty($errors['subject'])): ?><div class="text-danger small"><?= $escape($errors['subject']); ?></div><?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="message">Mesaj</label>
                            <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                            <?php if (!empty($errors['message'])): ?><div class="text-danger small"><?= $escape($errors['message']); ?></div><?php endif; ?>
                        </div>
                        <button class="btn btn-primary" type="submit">Gönder</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
$content = ob_get_clean();
include __DIR__ . '/../_layout/layout.php';
