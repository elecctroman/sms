<?php
declare(strict_types=1);
$errors = $errors ?? [];
ob_start();
?>
<section class="py-5" style="max-width: 420px; margin: 0 auto;">
    <h1 class="h3 fw-bold mb-4 text-center">Doğrulama Kodu</h1>
    <p class="text-muted">E-posta adresinize gönderilen 6 haneli kodu giriniz.</p>
    <form method="post" action="/login/otp">
        <input type="hidden" name="_token" value="<?= $escape($csrf_token); ?>">
        <div class="mb-3">
            <label class="form-label" for="code">Kod</label>
            <input class="form-control" type="text" name="code" id="code" maxlength="6" required>
            <?php if (!empty($errors['code'])): ?><div class="text-danger small"><?= $escape($errors['code']); ?></div><?php endif; ?>
        </div>
        <button class="btn btn-primary w-100" type="submit">Onayla</button>
    </form>
</section>
<?php
$content = ob_get_clean();
include __DIR__ . '/../_layout/layout.php';
