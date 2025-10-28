<?php
declare(strict_types=1);
ob_start();
?>
<section class="py-5">
    <h1 class="h3 fw-bold mb-4">Bakiye Ekle</h1>
    <form method="post" action="/add-funds">
        <input type="hidden" name="_token" value="<?= $escape($csrf_token); ?>">
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label" for="amount">Tutar</label>
                <input class="form-control" type="number" min="10" step="0.01" name="amount" id="amount" required>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="method">Yöntem</label>
                <select class="form-select" id="method" name="method" required>
                    <option value="shopier">Shopier (Mock)</option>
                    <option value="iyzico">iyzico (Mock)</option>
                    <option value="paytr">PayTR (Mock)</option>
                </select>
            </div>
        </div>
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-body">
                <p class="mb-2 text-muted">Komisyon &amp; Bonus</p>
                <p id="summary">Tutar giriniz.</p>
                <button class="btn btn-primary" type="submit">Ödeme Başlat</button>
            </div>
        </div>
    </form>
</section>
<script>
const amountInput = document.getElementById('amount');
const summary = document.getElementById('summary');
if (amountInput && summary) {
    amountInput.addEventListener('input', () => {
        const amount = parseFloat(amountInput.value || '0');
        if (amount > 0) {
            const fee = amount * 0.02;
            const bonus = amount >= 1000 ? amount * 0.05 : 0;
            summary.textContent = `Komisyon: ${fee.toFixed(2)} TL, Bonus: ${bonus.toFixed(2)} TL`;
        } else {
            summary.textContent = 'Tutar giriniz.';
        }
    });
}
</script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../_layout/layout.php';
