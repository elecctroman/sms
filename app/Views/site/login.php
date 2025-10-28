<?php
declare(strict_types=1);
$errors = $errors ?? [];
$old = $old ?? [];
ob_start();
?>
<section class="py-5" style="max-width: 420px; margin: 0 auto;">
    <h1 class="h3 fw-bold mb-4 text-center">Giriş Yap</h1>
    <form method="post" action="/login">
        <input type="hidden" name="_token" value="<?= $escape($csrf_token); ?>">
        <div class="mb-3">
            <label class="form-label" for="email">E-posta</label>
            <input class="form-control" type="email" name="email" id="email" required value="<?= $escape($old['email'] ?? ''); ?>">
            <?php if (!empty($errors['email'])): ?><div class="text-danger small"><?= $escape($errors['email']); ?></div><?php endif; ?>
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">Şifre</label>
            <input class="form-control" type="password" name="password" id="password" required>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="/forgot-password">Şifremi unuttum</a>
        </div>
        <button class="btn btn-primary w-100" type="submit">Devam et</button>
    </form>
</section>
<?php
$content = ob_get_clean();
include __DIR__ . '/../_layout/layout.php';
