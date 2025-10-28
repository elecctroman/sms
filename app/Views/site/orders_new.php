<?php
declare(strict_types=1);
$errors = $errors ?? [];
ob_start();
?>
<section class="py-5">
    <h1 class="h3 fw-bold mb-4">Yeni Sipariş</h1>
    <form method="post" action="/orders">
        <input type="hidden" name="_token" value="<?= $escape($csrf_token); ?>">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="service_id">Servis</label>
                    <select class="form-select" id="service_id" name="service_id" required>
                        <option value="">Seçiniz</option>
                        <?php foreach ($services as $service): ?>
                            <option value="<?= $escape((string) $service['id']); ?>"><?= $escape($service['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="country_id">Ülke</label>
                    <select class="form-select" id="country_id" name="country_id" required>
                        <option value="">Seçiniz</option>
                        <?php foreach ($countries as $country): ?>
                            <option value="<?= $escape((string) $country['id']); ?>"><?= $escape($country['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="operator_id">Operatör</label>
                    <input class="form-control" type="text" name="operator_id" id="operator_id" placeholder="Opsiyonel">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="supplier">Tedarikçi</label>
                    <select class="form-select" id="supplier" name="supplier" required>
                        <option value="">Seçiniz</option>
                        <option value="nessa">Nessa Demo</option>
                        <option value="providerx">Provider X</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kupon</label>
                    <input class="form-control" type="text" name="coupon_code" placeholder="Opsiyonel">
                </div>
                <div class="mb-3">
                    <button class="btn btn-outline-secondary w-100" type="button" id="quoteButton">Fiyat Sorgula</button>
                </div>
                <div class="alert alert-info d-none" id="quoteResult"></div>
            </div>
        </div>
        <div class="d-flex justify-content-end gap-3 mt-4">
            <a class="btn btn-outline-secondary" href="/orders">İptal</a>
            <button class="btn btn-primary" type="submit">Sipariş Oluştur</button>
        </div>
    </form>
</section>
<script>
const quoteButton = document.getElementById('quoteButton');
if (quoteButton) {
    quoteButton.addEventListener('click', async () => {
        const form = quoteButton.closest('form');
        if (!form) return;
        const data = new FormData(form);
        const response = await fetch('/orders/quote', {method: 'POST', body: data});
        const result = await response.json();
        const box = document.getElementById('quoteResult');
        if (!box) return;
        if (result.quote) {
            box.classList.remove('d-none');
            box.textContent = `Satış fiyatı: ${result.quote.final_price} ${result.quote.currency}`;
        } else {
            box.classList.remove('d-none');
            box.textContent = result.error || 'Fiyat bulunamadı';
        }
    });
}
</script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../_layout/layout.php';
