<?php
$title = 'Sıkça Sorulan Sorular';
ob_start();
?>
<section class="py-5">
    <div class="container">
        <h1>SSS</h1>
        <div class="accordion" id="faqPage">
            <div class="accordion-item">
                <h2 class="accordion-header" id="q1">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#a1">SMS kodu ne kadar sürede gelir?</button>
                </h2>
                <div id="a1" class="accordion-collapse collapse show" data-bs-parent="#faqPage">
                    <div class="accordion-body">Ortalama 45 saniye içerisinde kod teslim edilir.</div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
