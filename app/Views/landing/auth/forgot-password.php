<?php
$title = 'Şifre Sıfırlama';
ob_start();
?>
<section class="py-5">
    <div class="container" style="max-width:480px;">
        <h1 class="mb-4">Şifre Sıfırlama</h1>
        <form method="post" action="/forgot-password">
            <div class="mb-3">
                <label class="form-label">E-posta</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <button class="btn btn-primary w-100">Sıfırlama bağlantısı gönder</button>
        </form>
    </div>
</section>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
