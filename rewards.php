<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/api/gamba.php';

$race = gamba_race();

$PAGE_TITLE = 'Rewards — ' . SITE_NAME;
$PAGE_DESC  = 'Hit a wager milestone on ' . SPONSOR_NAME . ' with code ' . SPONSOR_CODE
            . ' and claim a cash bonus.';
$BODY_CLASS = 'home';
$ACTIVE     = 'rewards';

require __DIR__ . '/includes/header.php';
?>

<section class="gm-lb-hero">
    <div class="container" data-reveal-stagger>
        <span class="gm-eyebrow"><i class="fa-solid fa-gift"></i> Wager Milestones</span>

        <h1 class="gm-lb-title font-litp-blk">Rewards</h1>
        <p class="gm-lb-sub font-pop-bld">
            Hit a wager total on <?= e(SPONSOR_NAME) ?> under code <strong><?= e(SPONSOR_CODE) ?></strong>
            and claim the bonus. Milestones reset each month.
        </p>
    </div>
</section>

<section class="gm-section pt-0">
    <div class="container">
        <div class="gm-tiers" data-reveal-stagger>
            <?php foreach (content('rewards') as $i => $tier): ?>
                <div class="gm-tier gm-tier-<?= $i + 1 ?>">
                    <span class="gm-tier-name"><?= e($tier['name']) ?></span>
                    <span class="gm-tier-bonus gm-num"><?= e(money((float) $tier['bonus'], 0)) ?></span>
                    <span class="gm-tier-wager">
                        Wager <strong class="gm-num"><?= e(money((float) $tier['wager'], 0)) ?></strong>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="gm-claim" data-reveal>
            <i class="fa-brands fa-discord"></i>
            <div>
                <h3>Claiming</h3>
                <p>
                    Milestones are paid by hand. Open a ticket in the Discord with your
                    <?= e(SPONSOR_NAME) ?> username and we'll sort it in the same month.
                </p>
            </div>
            <a href="<?= e(SITE_DISCORD) ?>" target="_blank" rel="noopener"
               class="rewards-btn font-pop-exbd">OPEN A TICKET</a>
        </div>
    </div>
</section>

<section class="gm-section pt-0">
    <div class="container">
        <div class="gm-panel mx-auto" data-reveal style="max-width:720px">
            <h3><i class="fa-solid fa-circle-info me-2" style="color:var(--gm-green)"></i>How wagers are counted</h3>
            <p class="font-pop-bld" style="color:var(--gm-muted); font-size:14px">
                Low house-edge games count for less, which is what stops the board being farmed.
            </p>
            <?php $rtp = $race['rtp']; ?>
            <?php if ($rtp): ?>
                <ul class="gm-rules mb-0">
                    <?php foreach ($rtp as $tier): ?>
                        <?php $r = $tier['range'] ?? []; if (count($r) !== 2) continue; ?>
                        <li>
                            House edge <?= e($r[0]) ?>%&ndash;<?= e($r[1]) ?>%
                            &rarr; <?= e((float) $tier['contribution']) ?>% counted
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
