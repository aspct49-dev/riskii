<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/gamba.php';

$race = gamba_race();

$competitors = $race['competitors'];
$podium      = array_slice($competitors, 0, 3);
$rest        = array_slice($competitors, 3, 7);   // places 4–10
$live        = $race['ends_at'] !== null && $race['ends_at'] > time();
$started     = $race['start_date'] === null || strtotime($race['start_date'] . ' UTC') <= time();

$PAGE_TITLE  = 'Leaderboard - ' . SITE_NAME;
$PAGE_DESC   = 'Wager on ' . SPONSOR_NAME . ' with code ' . $race['code'] . ' and compete for the '
             . money($race['pool'], 0) . ' ' . $race['name'] . ' prize pool.';
$BODY_CLASS  = 'leaderboard';
$ACTIVE      = 'leaderboard';

require __DIR__ . '/../includes/header.php';
?>

<section class="gm-lb-hero">
    <div class="container" data-reveal-stagger>

        <div class="gm-lb-sponsor">
            <span>Powered by</span>
            <img src="<?= e(SPONSOR_LOGO) ?>" alt="<?= e(SPONSOR_NAME) ?>" width="364" height="112">
        </div>

        <h1 class="gm-lb-title font-litp-blk">
            <?= e($race['name']) ?> <span>Leaderboard</span>
        </h1>
        <p class="gm-lb-sub font-pop-bld">
            Wager on <?= e(SPONSOR_NAME) ?> under code <strong><?= e($race['code']) ?></strong>.
            Top <?= count($race['prizes']) ?: 10 ?> get paid.
        </p>

        <?php if ($race['stale']): ?>
            <div class="gm-stale">
                <i class="fa-solid fa-triangle-exclamation"></i>
Can't reach <?= e(SPONSOR_NAME) ?> — showing the last standings we received.
            </div>
        <?php endif; ?>

        <div class="gm-pool">
            <span class="gm-pool-label">Prize Pool</span>
            <span class="gm-pool-value"><?= e(money($race['pool'], 0)) ?></span>
            <span class="gm-pool-cur"><?= e($race['currency']) ?></span>
        </div>

        <?php if ($live): ?>
            <div class="gm-countdown" id="gm-countdown" data-ends="<?= (int) $race['ends_at'] ?>">
                <?php foreach (['days' => 'Days', 'hours' => 'Hours', 'minutes' => 'Mins', 'seconds' => 'Secs'] as $k => $label): ?>
                    <div class="gm-cd-cell">
                        <span class="gm-cd-value" data-unit="<?= e($k) ?>">--</span>
                        <span class="gm-cd-label"><?= e($label) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
            <p class="gm-cd-note">
                Ends <?= e(date('j M Y, H:i', $race['ends_at'])) ?> UTC
            </p>
        <?php else: ?>
            <p class="gm-cd-note">This race has ended. Final standings below.</p>
        <?php endif; ?>

        <div class="mt-3">
            <a href="<?= e(SPONSOR_LINK) ?>" target="_blank" rel="noopener sponsored"
               class="rewards-btn font-pop-exbd">JOIN ON <?= e(strtoupper(SPONSOR_NAME)) ?></a>
        </div>
    </div>
</section>

<section class="gm-section pt-0">
    <div class="container">

        <?php if (!$competitors): ?>

            <div class="gm-empty" data-reveal>
                <i class="fa-solid fa-trophy"></i>
                <h3><?= $started ? "Board's still empty" : 'Not started yet' ?></h3>
                <p>
                    <?php if ($started): ?>
                        First wager under code <strong><?= e($race['code']) ?></strong> takes the lead.
                    <?php else: ?>
                        Opens <?= e(date('j M', strtotime($race['start_date'] . ' UTC'))) ?>. Sign up with
                        code <strong><?= e($race['code']) ?></strong> now so day one counts.
                    <?php endif; ?>
                </p>

                <div class="gm-code" data-code="<?= e($race['code']) ?>">
                    <span class="gm-code-label">Code</span>
                    <span class="gm-code-value"><?= e($race['code']) ?></span>
                    <i class="fa-regular fa-copy"></i>
                </div>

                <div class="mt-4">
                    <a href="<?= e(SPONSOR_LINK) ?>" target="_blank" rel="noopener sponsored"
                       class="rewards-btn font-pop-exbd">TAKE TOP SPOT</a>
                </div>
            </div>

        <?php else: ?>

            <?php if ($podium): ?>
                <div class="gm-podium" data-reveal-stagger>
                    <?php
                    // Rendered 2 – 1 – 3 so the winner sits in the middle of the step.
                    $order = [1 => $podium[1] ?? null, 0 => $podium[0] ?? null, 2 => $podium[2] ?? null];
                    foreach ($order as $idx => $p):
                        if (!$p) continue;
                        $place = $idx + 1;
                        ?>
                        <div class="gm-pod gm-pod-<?= $place ?>">
                            <?php if ($place === 1): ?>
                                <i class="fa-solid fa-crown gm-pod-crown"></i>
                            <?php endif; ?>
                            <span class="gm-pod-rank"><?= $place ?></span>
                            <h3 class="gm-pod-name"><?= e($p['username']) ?></h3>
                            <p class="gm-pod-wager">
                                Wagered <strong class="gm-num"><?= e(money($p['wagered'])) ?></strong>
                            </p>
                            <span class="gm-pod-prize gm-num"><?= e(money($p['prize'], 0)) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($rest): ?>
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
                            <?php foreach ($rest as $p): ?>
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
            <?php endif; ?>

        <?php endif; ?>
    </div>
</section>

<!-- Prize split + how it is scored -->
<section class="gm-section pt-0">
    <div class="container">
        <div class="gm-split" data-reveal-stagger>

            <div class="gm-panel">
                <h3><i class="fa-solid fa-coins me-2" style="color:var(--gm-green)"></i>Prize Distribution</h3>
                <ul class="gm-prize-list">
                    <?php foreach ($race['prizes'] as $pos => $amt): ?>
                        <li>
                            <span class="pos"><?= (int) $pos ?><?= e(ordinal_suffix((int) $pos)) ?> place</span>
                            <span class="amt"><?= e(money((float) $amt, 0)) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="gm-panel">
                <h3><i class="fa-solid fa-circle-info me-2" style="color:var(--gm-green)"></i>How It Works</h3>
                <ul class="gm-rules">
                    <li>Sign up with code <strong><?= e($race['code']) ?></strong>. Wagers only count while
                        it is on your account.</li>
                    <li>Counts from <?= e(date('j M', strtotime($race['start_date'] . ' UTC'))) ?>
                        to <?= $race['ends_at'] ? e(date('j M Y', $race['ends_at'])) : 'close' ?>.</li>
                    <?php if ($race['multipliers']): ?>
                        <li>By category:
                            <?php
                            $parts = [];
                            foreach (['CASINO' => 'Casino', 'INHOUSE' => 'In-house', 'SPORTS' => 'Sports'] as $k => $label) {
                                if (isset($race['multipliers'][$k])) {
                                    $parts[] = $label . ' ' . (float) $race['multipliers'][$k] . '%';
                                }
                            }
                            echo e(implode(' · ', $parts));
                            ?>.
                        </li>
                    <?php endif; ?>
                    <?php if ($race['rtp']): ?>
                        <li>Low house edge counts for less:
                            <?php
                            $parts = [];
                            foreach ($race['rtp'] as $tier) {
                                $r = $tier['range'] ?? [];
                                if (count($r) === 2) {
                                    $parts[] = $r[0] . '–' . $r[1] . '% house edge → ' . (float) $tier['contribution'] . '%';
                                }
                            }
                            echo e(implode('; ', $parts));
                            ?>.
                        </li>
                    <?php endif; ?>
                    <li>Standings come live from <?= e(SPONSOR_NAME) ?>. Their numbers are final.</li>
                </ul>

                <a href="<?= e(GAMBA_RACE_URL) ?>" target="_blank" rel="noopener"
                   class="font-pop-exbd d-inline-block mt-2" style="color:var(--gm-green)">
                    View on <?= e(SPONSOR_NAME) ?> <i class="fa-solid fa-arrow-up-right-from-square ms-1" style="font-size:12px"></i>
                </a>
            </div>

        </div>
    </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
