<?php
declare(strict_types=1);
ob_start();
?>
<section class="py-5 text-center">
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 480px;">
        <div class="card-body py-5">
            <div class="display-4 mb-3">🎉</div>
            <h1 class="h4 fw-bold mb-3">Siparişiniz Oluşturuldu</h1>
            <p class="text-muted">Telefon numaranız: <strong><?= $escape($order['phone_number']); ?></strong></p>
            <a class="btn btn-primary mt-4" href="/orders">Siparişlerime Dön</a>
        </div>
    </div>
</section>
<?php
$content = ob_get_clean();
include __DIR__ . '/../_layout/layout.php';
