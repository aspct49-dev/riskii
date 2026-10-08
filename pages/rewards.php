<?php
require_once __DIR__ . '/../includes/config.php';

$PAGE_TITLE = 'Rewards - ' . SITE_NAME;
$PAGE_DESC  = 'Play on ' . SPONSOR_NAME . ' with code ' . SPONSOR_CODE
            . ': a $1,000 monthly leaderboard, instant lossback up to 20%, rank-up bonuses and 95% affiliate commission.';
$BODY_CLASS = 'rewards-page';
$ACTIVE     = 'rewards';

require __DIR__ . '/../includes/header.php';

/** "[95%] Affiliate Commission" -> the bracketed part in the accent colour. */
function perk_html(string $text): string
{
    return preg_replace('/\[(.+?)\]/', '<span>$1</span>', e($text));
}
?>

<section class="gm-lb-hero">
    <div class="container" data-reveal-stagger>
        <div class="gm-lb-sponsor">
            <span>Exclusive with</span>
            <img src="<?= e(SPONSOR_LOGO) ?>" alt="<?= e(SPONSOR_NAME) ?>" width="364" height="112">
        </div>

        <h1 class="gm-lb-title font-litp-blk">Rewards</h1>
        <p class="gm-lb-sub font-pop-bld">
            Everything you get for playing on <?= e(SPONSOR_NAME) ?> under code
            <strong><?= e(SPONSOR_CODE) ?></strong>.
        </p>
    </div>
</section>

<section class="gm-section pt-0">
    <div class="container">
        <div class="gm-perks" data-reveal-stagger>
            <?php foreach (content('gamba_perks') as $perk): ?>
                <?php $tag = !empty($perk['link']) ? 'a' : 'div'; ?>
                <<?= $tag ?> class="gm-perk"<?= $tag === 'a' ? ' href="' . e($perk['link']) . '"' : '' ?>>
                    <span class="gm-perk-icon"><i class="fa-solid <?= e($perk['icon']) ?>"></i></span>
                    <h3 class="gm-perk-text"><?= perk_html($perk['text']) ?></h3>
                    <?php if ($tag === 'a'): ?>
                        <i class="fa-solid fa-arrow-right gm-perk-go" aria-hidden="true"></i>
                    <?php endif; ?>
                </<?= $tag ?>>
            <?php endforeach; ?>
        </div>

        <div class="gm-perks-cta" data-reveal>
            <div class="gm-code" data-code="<?= e(SPONSOR_CODE) ?>">
                <span class="gm-code-label">Code</span>
                <span class="gm-code-value"><?= e(SPONSOR_CODE) ?></span>
                <i class="fa-regular fa-copy"></i>
            </div>
            <a href="<?= e(SPONSOR_LINK) ?>" target="_blank" rel="noopener sponsored"
               class="rewards-btn font-pop-exbd">CLAIM ON <?= e(strtoupper(SPONSOR_NAME)) ?></a>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
