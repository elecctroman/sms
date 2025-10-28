<?php
declare(strict_types=1);
ob_start();
?>
<section class="py-5">
    <h1 class="h3 fw-bold mb-4">Numara Kiralama</h1>
    <form method="post" action="/rent-number">
        <input type="hidden" name="_token" value="<?= $escape($csrf_token); ?>">
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label" for="duration">Süre</label>
                <select class="form-select" id="duration" name="duration" required>
                    <option value="hourly">Saatlik</option>
                    <option value="daily">Günlük</option>
                    <option value="weekly">Haftalık</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="service">Servis</label>
                <input class="form-control" type="text" id="service" name="service" required>
            </div>
        </div>
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-body">
                <p class="text-muted">Kalan süre: <span id="remainingTime">-</span></p>
                <button class="btn btn-primary" type="submit">Kirala</button>
            </div>
        </div>
    </form>
</section>
<script>
const remaining = document.getElementById('remainingTime');
if (remaining) {
    let seconds = 0;
    setInterval(() => {
        seconds += 1;
        remaining.textContent = new Date(seconds * 1000).toISOString().substr(11, 8);
    }, 1000);
}
</script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../_layout/layout.php';
