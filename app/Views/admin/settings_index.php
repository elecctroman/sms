<?php
declare(strict_types=1);
ob_start();
?>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h1 class="h4 fw-bold mb-3">Genel Ayarlar</h1>
                <form method="post" action="/admin/settings">
                    <input type="hidden" name="_token" value="<?= $escape($csrf_token); ?>">
                    <div class="mb-3">
                        <label class="form-label" for="site_title">Site Başlığı</label>
                        <input class="form-control" type="text" id="site_title" name="site_title" value="<?= $escape($settings['theme']['site_title'] ?? 'SMS Onay'); ?>" required>
                        <?php if (!empty($errors['site_title'])): ?><div class="text-danger small"><?= $escape($errors['site_title']); ?></div><?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="primary_color">Ana Renk</label>
                        <input class="form-control" type="color" id="primary_color" name="primary_color" value="<?= $escape($settings['theme']['primary_color'] ?? '#4f46e5'); ?>">
                    </div>
                    <button class="btn btn-primary" type="submit">Kaydet</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h2 class="h5 fw-semibold mb-3">Aktif Tema Önizleme</h2>
                <div class="p-4 rounded" style="background: <?= $escape($settings['theme']['primary_color'] ?? '#4f46e5'); ?>; color: #fff;">
                    <h3 class="h6 mb-1"><?= $escape($settings['theme']['site_title'] ?? 'SMS Onay'); ?></h3>
                    <p class="text-white-50">Profesyonel SMS doğrulama deneyimi.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
