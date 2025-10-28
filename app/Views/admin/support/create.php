<?php
$title = 'Yeni Destek Bileti';
$page_title = 'Yeni Destek Bileti';
ob_start();
?>
<div class="card p-4">
    <h2 class="h4 mb-3">Yeni Destek Bileti</h2>
    <form>
        <div class="mb-3">
            <label class="form-label">Konu</label>
            <input type="text" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Öncelik</label>
            <select class="form-select">
                <option>Normal</option>
                <option>Yüksek</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Mesaj</label>
            <textarea class="form-control" rows="4" required></textarea>
        </div>
        <button class="btn btn-primary">Gönder</button>
    </form>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
