<?php
require_once __DIR__ . '/includes/config.php';

$PAGE_TITLE = 'Giveaways — ' . SITE_NAME;
$PAGE_DESC  = 'Giveaways are coming back to ' . SITE_NAME . '. Join the Discord to hear first.';
$BODY_CLASS = 'giveways';
$ACTIVE     = 'giveaways';

require __DIR__ . '/includes/header.php';
?>

<section class="gm-lb-hero">
    <div class="container" data-reveal-stagger>
        <span class="gm-eyebrow"><i class="fa-solid fa-gift"></i> Coming Soon</span>

        <h1 class="gm-lb-title font-litp-blk">Giveaways</h1>
        <p class="gm-lb-sub font-pop-bld">
            We're putting the next round together. Join the Discord and you'll hear
            the moment it opens.
        </p>

        <div class="banner-content d-flex justify-content-center align-items-center flex-wrap gap-3">
            <a href="<?= e(SITE_DISCORD) ?>" target="_blank" rel="noopener"
               class="rewards-btn font-pop-exbd">
                <i class="fa-brands fa-discord me-2"></i>JOIN THE DISCORD
            </a>
            <a href="leaderboard" class="rewards-btn rewards-btn-1 font-pop-exbd">LEADERBOARD</a>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
