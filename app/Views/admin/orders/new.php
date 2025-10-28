<?php
$title = 'Yeni Sipariş';
$page_title = 'Yeni Sipariş Oluştur';
$services = $services ?? [];
ob_start();
?>
<div class="card p-4">
    <h2 class="h4 mb-4">Yeni Sipariş Oluştur</h2>
    <div class="mb-3">
        <input type="search" class="form-control" placeholder="Servis ara..." id="serviceSearch">
    </div>
    <div class="row g-3" id="serviceList">
        <?php foreach ($services as $service): ?>
            <?php
            $serviceName = (string) ($service['name'] ?? '');
            $normalizedName = function_exists('mb_strtolower') ? mb_strtolower($serviceName) : strtolower($serviceName);
            ?>
            <div class="col-md-4 service-card" data-name="<?= $view->escape($normalizedName); ?>">
                <div class="card p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi <?= $view->escape($service['icon'] ?? 'bi-chat-dots'); ?> fs-2" style="color: <?= $view->escape($service['color'] ?? '#0d6efd'); ?>"></i>
                            <h5 class="mt-2"><?= $view->escape($serviceName !== '' ? $serviceName : 'Servis'); ?></h5>
                        </div>
                        <button class="btn btn-sm btn-outline-warning"><i class="bi bi-star"></i></button>
                    </div>
                    <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#orderModal" data-service="<?= $view->escape($serviceName); ?>">Sipariş Ver</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<div class="modal fade" id="orderModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Servis Seçimi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Ülke</label>
                        <select class="form-select">
                            <option>Türkiye</option>
                            <option>Amerika</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Operatör</label>
                        <select class="form-select">
                            <option>Turkcell</option>
                            <option>Vodafone</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kupon</label>
                        <input type="text" class="form-control" placeholder="Kupon kodu">
                    </div>
                </form>
                <div class="alert alert-info">Ücret anlık tedarikçiden alınacaktır.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">İptal</button>
                <button type="button" class="btn btn-primary">Ödemeyi Tamamla</button>
            </div>
        </div>
    </div>
</div>
<script>
const searchInput = document.getElementById('serviceSearch');
const cards = document.querySelectorAll('.service-card');
searchInput?.addEventListener('input', () => {
  const value = (searchInput.value || '').toLowerCase();
  cards.forEach(card => {
    card.classList.toggle('d-none', !(card.dataset.name || '').includes(value));
  });
});
</script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
