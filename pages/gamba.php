<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/gamba.php';

$race = gamba_race();

$PAGE_TITLE = SPONSOR_NAME . ' — ' . SITE_NAME;
$PAGE_DESC  = 'Sign up to ' . SPONSOR_NAME . ' with code ' . SPONSOR_CODE
            . ' for instant lossback, rank-up bonuses and entry to the '
            . money($race['pool'], 0) . ' monthly leaderboard.';
$BODY_CLASS = 'home sponsor-page';
$ACTIVE     = 'gamba';

require __DIR__ . '/../includes/header.php';
?>

<section class="gm-lb-hero">
    <div class="container" data-reveal-stagger>
        <span class="gm-eyebrow"><i class="fa-solid fa-bolt"></i> Official Partner</span>

        <div class="gm-lb-sponsor mb-4">
            <img src="<?= e(SPONSOR_LOGO) ?>" alt="<?= e(SPONSOR_NAME) ?>" width="364" height="112" style="height:46px; width:auto">
        </div>

        <h1 class="gm-lb-title font-litp-blk">
            <?= e(SPONSOR_NAME) ?> <span>&times;</span> <?= e(SITE_NAME) ?>
        </h1>
        <p class="gm-lb-sub font-pop-bld">
            Instant lossback, rank-up bonuses and a <?= e(money($race['pool'], 0)) ?>
            monthly leaderboard. One code unlocks all of it.
        </p>

        <div class="d-flex justify-content-center mb-4">
            <div class="gm-code" data-code="<?= e(SPONSOR_CODE) ?>">
                <span class="gm-code-label">Code</span>
                <span class="gm-code-value"><?= e(SPONSOR_CODE) ?></span>
                <i class="fa-regular fa-copy"></i>
            </div>
        </div>

        <div class="banner-content d-flex justify-content-center align-items-center flex-wrap gap-3">
            <a href="<?= e(SPONSOR_LINK) ?>" target="_blank" rel="noopener sponsored"
               class="rewards-btn font-pop-exbd">CLAIM BONUSES</a>
            <a href="/leaderboard" class="rewards-btn rewards-btn-1 font-pop-exbd">LEADERBOARD</a>
        </div>
    </div>
</section>

<section class="gm-section pt-0">
    <div class="container">
        <div class="gm-section-head" data-reveal>
            <h2 class="font-litp-blk">How to Start</h2>
            <p class="font-pop-bld">About two minutes.</p>
        </div>

        <div class="gm-steps" data-reveal-stagger>
            <div class="gm-step">
                <div class="gm-step-n">1</div>
                <h3>Sign up with the code</h3>
                <p>
                    Use the button below, or enter <strong style="color:var(--gm-green)"><?= e(SPONSOR_CODE) ?></strong>
                    at signup. Without the code, none of the rest applies.
                </p>
            </div>

            <div class="gm-step">
                <div class="gm-step-n">2</div>
                <h3>Unlock your rewards</h3>
                <p>
                    Instant lossback up to 20%, rank-up and level bonuses, and 95% affiliate
                    commission. <a href="/rewards" style="color:var(--gm-green)">See all rewards</a>
                </p>
            </div>

            <div class="gm-step">
                <div class="gm-step-n">3</div>
                <h3>Climb the leaderboard</h3>
                <p>
                    Every wager counts toward the <?= e(money($race['pool'], 0)) ?> race.
                    Top <?= count($race['prizes']) ?: 10 ?> get paid.
                </p>
            </div>
        </div>

        <div class="text-center mt-5" data-reveal>
            <a href="<?= e(SPONSOR_LINK) ?>" target="_blank" rel="noopener sponsored"
               class="rewards-btn font-pop-exbd">VISIT <?= e(strtoupper(SPONSOR_NAME)) ?></a>
        </div>
    </div>
</section>

<!-- Live standings teaser -->
<section class="gm-section pt-0">
    <div class="container">
        <div class="gm-section-head" data-reveal>
            <h2 class="font-litp-blk">Current Standings</h2>
            <p class="font-pop-bld"><?= e($race['name']) ?> &middot; <?= e(money($race['pool'], 0)) ?> <?= e($race['currency']) ?></p>
        </div>

        <?php $top = array_slice($race['competitors'], 0, 5); ?>

        <?php if (!$top): ?>
            <div class="gm-empty" data-reveal>
                <i class="fa-solid fa-trophy"></i>
                <h3>Board's still empty</h3>
                <p>First wager under the code takes the lead.</p>
                <a href="/leaderboard" class="rewards-btn font-pop-exbd">SEE THE LEADERBOARD</a>
            </div>
        <?php else: ?>
            <div class="gm-table-wrap" data-reveal>
                <table class="gm-table">
                    <thead>
                        <tr>
                            <th class="gm-col-rank">Rank</th>
                            <th>Player</th>
                            <th class="gm-col-wager">Wagered</th>
                            <th class="gm-col-prize">Prize</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($top as $p): ?>
                            <tr>
                                <td class="gm-col-rank"><span class="gm-rank-pill"><?= (int) $p['position'] ?></span></td>
                                <td class="gm-name"><?= e($p['username']) ?></td>
                                <td class="gm-col-wager gm-wager"><?= e(money($p['wagered'])) ?></td>
                                <td class="gm-col-prize gm-prize"><?= e(money($p['prize'], 0)) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="text-center mt-4" data-reveal>
                <a href="/leaderboard" class="rewards-btn rewards-btn-1 font-pop-exbd">FULL LEADERBOARD</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
