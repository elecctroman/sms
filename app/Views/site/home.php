<?php
declare(strict_types=1);
$trans = $trans ?? static fn (string $key): string => $key;
ob_start();
?>
<section class="text-center py-5" data-aos="fade-up">
    <h1 class="display-4 fw-bold mb-3"><?= $escape(($trans)('welcome_title')); ?></h1>
    <p class="lead text-muted mb-4"><?= $escape(($trans)('welcome_subtitle')); ?></p>
    <div class="d-flex justify-content-center gap-3">
        <a class="btn btn-primary btn-lg" href="/register">Hemen Başla</a>
        <a class="btn btn-outline-secondary btn-lg" href="/faq">Nasıl Çalışır?</a>
    </div>
</section>
<section class="row g-4 py-5" data-aos="fade-up" data-aos-delay="150">
    <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <h3 class="h5 fw-semibold">Anlık Onay</h3>
                <p class="text-muted">Gerçek zamanlı tedarikçi bağlantısı ile saniyeler içinde doğrulama kodu.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <h3 class="h5 fw-semibold">Global Stok</h3>
                <p class="text-muted">50+ ülke ve yüzlerce operatör desteği ile kapsamlı sanal numara havuzu.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <h3 class="h5 fw-semibold">Güvenli Ödeme</h3>
                <p class="text-muted">Cüzdan yönetimi, kuponlar ve rol tabanlı güvenlik kontrolleri.</p>
            </div>
        </div>
    </div>
</section>
<section class="py-5" data-aos="fade-up" data-aos-delay="200">
    <h2 class="h3 fw-bold mb-4">Popüler Servisler</h2>
    <div class="row g-4">
        <?php foreach ($services as $service): ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <div class="display-6">📱</div>
                        <h3 class="h6 mt-3"><?= $escape($service['name']); ?></h3>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<section class="py-5" data-aos="fade-up" data-aos-delay="250">
    <div class="row g-4 align-items-center">
        <div class="col-md-6">
            <h2 class="h3 fw-bold">Müşterilerimiz Ne Diyor?</h2>
            <p class="text-muted">“SMS Onay platformu sayesinde küresel kampanyalarımızı güvenle doğruluyoruz.”</p>
            <p class="fw-semibold">Efe K. – Growth Lead</p>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <canvas id="metricsChart" height="180"></canvas>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="py-5" data-aos="fade-up" data-aos-delay="300">
    <h2 class="h3 fw-bold mb-4">Blog</h2>
    <div class="row g-4">
        <?php foreach ($posts as $post): ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h3 class="h5"><?= $escape($post['title']); ?></h3>
                        <p class="text-muted"><?= $escape(substr((string) strip_tags((string) $post['body_html']), 0, 100)); ?>...</p>
                        <a class="text-decoration-none" href="/blog/<?= $escape($post['slug']); ?>">Devamını oku</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<script>
if (window.Chart) {
    const ctx = document.getElementById('metricsChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt', 'Paz'],
                datasets: [{
                    label: 'Sipariş',
                    data: [12, 19, 15, 22, 30, 25, 28],
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79,70,229,0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {responsive: true, maintainAspectRatio: false}
        });
    }
}
</script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../_layout/layout.php';
