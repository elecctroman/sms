<?php
$title = 'Giriş Yap';
ob_start();
?>
<section class="py-5">
    <div class="container" style="max-width:480px;">
        <h1 class="mb-4">Giriş Yap</h1>
        <form method="post" action="/login">
            <div class="mb-3">
                <label class="form-label">E-posta</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Şifre</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="kvkk" id="kvkk" required>
                    <label class="form-check-label" for="kvkk">KVKK metnini kabul ediyorum</label>
                </div>
                <a href="/forgot-password">Şifremi Unuttum</a>
            </div>
            <button class="btn btn-primary w-100">Giriş Yap</button>
        </form>
    </div>
</section>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
