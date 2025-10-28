<?php
$title = 'Blog';
ob_start();
?>
<section class="py-5">
    <div class="container">
        <h1>Blog</h1>
        <div class="row g-4">
            <div class="col-md-4">
                <article class="card h-100 p-4">
                    <h3>SMS Onayda 2024 Trendleri</h3>
                    <p>Güvenliğiniz için en iyi uygulamaları keşfedin.</p>
                    <a href="#" class="btn btn-link">Devamını oku</a>
                </article>
            </div>
        </div>
    </div>
</section>
<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
