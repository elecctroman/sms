<?php
declare(strict_types=1);
$trans = $trans ?? static fn (string $key): string => $key;
$user = $session->get('user');
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">SMS Onay</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Menüyü aç">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="/faq">SSS</a></li>
                <li class="nav-item"><a class="nav-link" href="/blog">Blog</a></li>
                <li class="nav-item"><a class="nav-link" href="/announcements">Duyurular</a></li>
            </ul>
            <div class="d-flex align-items-center gap-2">
                <?php if ($user === null): ?>
                    <a class="btn btn-outline-primary" href="/login"><?= $escape($trans('login')); ?></a>
                    <a class="btn btn-primary" href="/register"><?= $escape($trans('register')); ?></a>
                <?php else: ?>
                    <a class="btn btn-outline-secondary" href="/dashboard"><?= $escape($trans('dashboard')); ?></a>
                    <a class="btn btn-secondary" href="/support"><?= $escape($trans('support')); ?></a>
                    <a class="btn btn-danger" href="/logout"><?= $escape($trans('logout')); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
<div style="height: 80px"></div>
