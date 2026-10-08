<?php
require_once __DIR__ . '/../includes/config.php';

$PAGE_TITLE = 'Page not found — ' . SITE_NAME;
$PAGE_DESC  = 'That page does not exist.';
$BODY_CLASS = 'not-found';
$ACTIVE     = '';

require __DIR__ . '/../includes/header.php';
?>

<section class="gm-lb-hero">
    <div class="container" data-reveal-stagger>
        <span class="gm-eyebrow"><i class="fa-solid fa-compass"></i> 404</span>

        <h1 class="gm-lb-title font-litp-blk">Page not found</h1>
        <p class="gm-lb-sub font-pop-bld">That page doesn't exist, or it moved.</p>

        <div class="banner-content d-flex justify-content-center align-items-center flex-wrap gap-3">
            <a href="/" class="rewards-btn font-pop-exbd">HOME</a>
            <a href="/leaderboard" class="rewards-btn rewards-btn-1 font-pop-exbd">LEADERBOARD</a>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
