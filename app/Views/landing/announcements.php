<?php
$title = 'Duyurular';
ob_start();
?>
<section class="py-5">
    <div class="container">
        <h1>Duyurular</h1>
        <div class="list-group">
            <a href="#" class="list-group-item list-group-item-action">
                <div class="d-flex justify-content-between">
                    <h5>Bakım Duyurusu</h5>
                    <small><?= date('d.m.Y'); ?></small>
                </div>
                <p class="mb-0">Planlı bakım 02:00 - 04:00 arasında yapılacaktır.</p>
            </a>
        </div>
    </div>
</section>
<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
