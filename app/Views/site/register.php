<?php
declare(strict_types=1);
$errors = $errors ?? [];
$old = $old ?? [];
ob_start();
?>
<section class="py-5" style="max-width: 520px; margin: 0 auto;">
    <h1 class="h3 fw-bold mb-4 text-center">Hesap Oluştur</h1>
    <form method="post" action="/register">
        <input type="hidden" name="_token" value="<?= $escape($csrf_token); ?>">
        <div class="mb-3">
            <label class="form-label" for="name">Ad Soyad</label>
            <input class="form-control" type="text" name="name" id="name" required value="<?= $escape($old['name'] ?? ''); ?>">
            <?php if (!empty($errors['name'])): ?><div class="text-danger small"><?= $escape($errors['name']); ?></div><?php endif; ?>
        </div>
        <div class="mb-3">
            <label class="form-label" for="email">E-posta</label>
            <input class="form-control" type="email" name="email" id="email" required value="<?= $escape($old['email'] ?? ''); ?>">
            <?php if (!empty($errors['email'])): ?><div class="text-danger small"><?= $escape($errors['email']); ?></div><?php endif; ?>
        </div>
        <div class="mb-3">
            <label class="form-label" for="phone">Telefon</label>
            <input class="form-control" type="tel" name="phone" id="phone" required value="<?= $escape($old['phone'] ?? ''); ?>">
            <?php if (!empty($errors['phone'])): ?><div class="text-danger small"><?= $escape($errors['phone']); ?></div><?php endif; ?>
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">Şifre</label>
            <input class="form-control" type="password" name="password" id="password" required>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="terms" id="terms" value="1" required>
            <label class="form-check-label" for="terms">KVKK ve Çerez politikasını kabul ediyorum.</label>
        </div>
        <button class="btn btn-primary w-100" type="submit">Kayıt Ol</button>
    </form>
</section>
<?php
$content = ob_get_clean();
include __DIR__ . '/../_layout/layout.php';
