<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/api/gamba.php';

$race = gamba_race();
$top  = array_slice($race['competitors'], 0, 3);

$PAGE_TITLE = SITE_NAME . ' — Exclusive ' . SPONSOR_NAME . ' Bonuses & Leaderboard';
$PAGE_DESC  = 'Play on ' . SPONSOR_NAME . ' with code ' . SPONSOR_CODE . ' for instant rakeback and a '
            . money($race['pool'], 0) . ' monthly leaderboard.';
$BODY_CLASS = 'home';
$ACTIVE     = 'index';

require __DIR__ . '/includes/header.php';
?>

<section class="banner">
    <div class="container">
        <video autoplay loop muted playsinline class="banner-video">
            <source src="images/bg.webm" type="video/webm">
        </video>

        <div class="col-lg-9 mx-lg-auto">
            <div class="banner-content d-flex flex-column align-items-center" data-reveal-stagger>
                <div class="text-center mb-3">
                    <img src="images/logo.png" alt="<?= e(SITE_NAME) ?>" class="img-fluid hero-logo">
                </div>

                <h1 class="mb-0 font-litp-blk text-center text-white">
                    Better Bonuses. <span>Real</span> Payouts.
                </h1>

                <div class="hero-partner d-flex align-items-center justify-content-center gap-2 mt-3">
                    <span>Official partner</span>
                    <img src="<?= e(SPONSOR_LOGO) ?>" alt="<?= e(SPONSOR_NAME) ?>" width="364" height="112">
                </div>

                <div class="banner-content d-flex justify-content-center align-items-center flex-wrap gap-3 mt-4">
                    <a href="<?= e(SPONSOR_LINK) ?>" target="_blank" rel="noopener sponsored"
                       class="rewards-btn font-pop-exbd">CLAIM BONUS</a>
                    <a href="leaderboard" class="rewards-btn rewards-btn-1 font-pop-exbd">LEADERBOARD</a>
                </div>

            </div>
        </div>
    </div>

    <!-- Pinned to the bottom edge of the hero rather than flowing under the
         buttons, so the hero can be exactly one screen tall. -->
    <a href="#leaderboard" class="scroll-cue" aria-label="Scroll to the leaderboard" data-reveal="fade">
        <i class="fa-solid fa-angle-down"></i>
    </a>
</section>

<!-- The live race. Drawn in CSS so every figure stays selectable text. -->
<section id="leaderboard" class="gm-section">
    <div class="container">
        <div class="gm-section-head" data-reveal>
            <span class="gm-eyebrow"><i class="fa-solid fa-trophy"></i> Live Now</span>
            <h2 class="font-litp-blk"><?= e($race['name']) ?></h2>
            <p class="font-pop-bld">
                <?= e(money($race['pool'], 0)) ?> <?= e($race['currency']) ?> &middot;
                top <?= count($race['prizes']) ?: 10 ?> get paid
            </p>
        </div>

        <?php if ($race['ends_at'] && $race['ends_at'] > time()): ?>
            <div class="gm-countdown" id="gm-countdown" data-reveal-stagger data-ends="<?= (int) $race['ends_at'] ?>">
                <?php foreach (['days' => 'Days', 'hours' => 'Hours', 'minutes' => 'Mins', 'seconds' => 'Secs'] as $k => $label): ?>
                    <div class="gm-cd-cell">
                        <span class="gm-cd-value" data-unit="<?= e($k) ?>">--</span>
                        <span class="gm-cd-label"><?= e($label) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="mt-5">
            <?php if (!$top): ?>
                <div class="gm-empty" data-reveal>
                    <i class="fa-solid fa-trophy"></i>
                    <h3>Board's still empty</h3>
                    <p>First wager under code <strong><?= e($race['code']) ?></strong> takes the lead.</p>
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
                <div class="gm-podium" data-reveal-stagger>
                    <?php
                    // Rendered 2 – 1 – 3 so the winner sits in the middle of the step.
                    $order = [1 => $top[1] ?? null, 0 => $top[0] ?? null, 2 => $top[2] ?? null];
                    foreach ($order as $idx => $p):
                        if (!$p) continue;
                        $place = $idx + 1;
                        ?>
                        <div class="gm-pod gm-pod-<?= $place ?>">
                            <?php if ($place === 1): ?><i class="fa-solid fa-crown gm-pod-crown"></i><?php endif; ?>
                            <span class="gm-pod-rank"><?= $place ?></span>
                            <h3 class="gm-pod-name"><?= e($p['username']) ?></h3>
                            <p class="gm-pod-wager">Wagered <strong class="gm-num"><?= e(money($p['wagered'])) ?></strong></p>
                            <span class="gm-pod-prize gm-num"><?= e(money($p['prize'], 0)) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="text-center">
                    <a href="leaderboard" class="rewards-btn rewards-btn-1 font-pop-exbd">FULL LEADERBOARD</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Partner offers -->
<section id="offers" class="gm-section pt-0">
    <div class="container">
        <div class="gm-section-head" data-reveal>
            <h2 class="font-litp-blk">Our Offers</h2>
        </div>

        <div class="row justify-content-center gy-4" data-reveal-stagger>
            <?php foreach (content('offers') as $offer): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="our-offers-list-item text-center h-100">
                        <img src="<?= e($offer['logo']) ?>" alt="<?= e($offer['name']) ?>" class="img-fluid" loading="lazy">
                        <span class="d-block text-white font-pop-sbld"><?= e($offer['name']) ?></span>
                        <p class="mb-0 text-center text-white bg-text font-pop-exbd"><?= e($offer['bonus']) ?></p>
                        <a target="_blank" rel="noopener sponsored" href="<?= e($offer['link']) ?>"
                           class="claim-btn d-block font-pop-exbd">Claim Offer</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Running total -->
<section id="amount-given-away" class="gm-section pt-0">
    <div class="container">
        <div class="gm-section-head" data-reveal>
            <h2 class="font-litp-blk">Total Given Away</h2>
        </div>

        <div class="giveways-counter position-relative">
            <ul class="ps-0 mb-0 d-flex justify-content-center align-items-center flex-wrap" data-reveal-stagger>
                <li class="giveways-counter-dollar"><p class="mb-0 d-inline-block font-pop-exbd">$</p></li>
                <?php
                $digits = str_split((string) (int) content('given_away'));
                foreach ($digits as $key => $digit) {
                    echo '<li class="giveways-counter-item"><p class="mb-0 d-inline-block text-white font-pop-exbd">' . e($digit) . '</p></li>';
                    if ($key === 2 && count($digits) > 3) {
                        echo '<li class="giveways-counter-comma"><p class="mb-0 d-inline-block text-white font-pop-exbd">,</p></li>';
                    }
                }
                ?>
            </ul>

            <div class="giveways-chip-1 position-absolute d-lg-block d-none"><img src="images/chip-1.png" alt="" class="img-fluid"></div>
            <div class="giveways-chip-2 position-absolute d-lg-block d-none"><img src="images/chip-2.png" alt="" class="img-fluid"></div>
            <div class="giveways-chip-3 position-absolute d-lg-block d-none"><img src="images/chip-3.png" alt="" class="img-fluid"></div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
