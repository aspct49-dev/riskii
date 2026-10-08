<?php
/**
 * Shared page chrome. Pages set $PAGE_TITLE, $PAGE_DESC, $BODY_CLASS and
 * $ACTIVE before including this; everything else is the same on every page.
 */
require_once __DIR__ . '/config.php';

$PAGE_TITLE = $PAGE_TITLE ?? SITE_NAME;
$PAGE_DESC  = $PAGE_DESC  ?? 'Exclusive ' . SPONSOR_NAME . ' bonuses and a monthly leaderboard with real payouts.';
$BODY_CLASS = $BODY_CLASS ?? '';
$ACTIVE     = $ACTIVE     ?? '';
// Page-specific stylesheets, e.g. $PAGE_CSS = ['css/rewards.css'];
$PAGE_CSS   = $PAGE_CSS   ?? [];

$NAV = [
    'index'       => 'Home',
    'gamba'       => SPONSOR_NAME,
    'leaderboard' => 'Leaderboard',
    'giveaways'   => 'Giveaways',
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="<?= e($PAGE_DESC) ?>">
    <meta name="keywords" content="roguerewards, riiski, gamba, gamba leaderboard, riiski gamba code">
    <meta name="author" content="Riiski">
    <meta name="theme-color" content="#0B0E14">

    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= e($PAGE_TITLE) ?>">
    <meta property="og:description" content="<?= e($PAGE_DESC) ?>">
    <meta property="og:image" content="images/logo.png">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@ROGUERewards">
    <meta name="twitter:title" content="<?= e($PAGE_TITLE) ?>">
    <meta name="twitter:description" content="<?= e($PAGE_DESC) ?>">

    <title><?= e($PAGE_TITLE) ?></title>
    <link rel="icon" type="image/png" href="/images/favicon.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/responsive.css">
    <!-- Loaded last: the rebrand layer retints the tokens the two files above use. -->
    <link rel="stylesheet" href="/css/refresh.css">
    <?php foreach ($PAGE_CSS as $sheet): ?>
        <link rel="stylesheet" href="<?= e($sheet) ?>">
    <?php endforeach; ?>

    <!-- Runs before first paint so reveal targets start hidden instead of
         flashing in and then vanishing. If js/reveal.js never arrives, the
         timeout puts everything back rather than leaving the page blank. -->
    <script>
        (function (d) {
            if (window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches) return;
            d.classList.add('js-reveal');
            setTimeout(function () {
                if (!d.classList.contains('reveal-ready')) d.classList.remove('js-reveal');
            }, 2500);
        })(document.documentElement);
    </script>
</head>

<body class="<?= e($BODY_CLASS) ?>">

<header class="header site-header">
    <div class="container header-bar">
        <div class="menu-left">
            <div class="logo-container">
                <a href="/" aria-label="<?= e(SITE_NAME) ?> home">
                    <!-- The poster is the video's own first frame, same size and
                         padding, so the swap when the video starts is invisible.
                         The old poster (logo.png) was cropped tight to the
                         wordmark, so the logo drew ~19% larger until the video
                         loaded and then shrank — a zoom on every page change. -->
                    <video class="logo-video" width="1018" height="158" loop muted autoplay playsinline
                           preload="auto" poster="/images/logo-poster.webp" aria-hidden="true">
                        <source src="/images/logo.webm" type="video/webm">
                    </video>
                </a>
            </div>
        </div>

        <div class="menu-right">
            <nav class="menu-list" id="site-nav" aria-label="Main">
                <ul class="ps-0 mb-0">
                    <?php foreach ($NAV as $slug => $label): ?>
                        <?php $isActive = $ACTIVE === $slug; ?>
                        <li>
                            <a href="<?= $slug === 'index' ? './' : e($slug) ?>"
                               class="<?= $isActive ? 'active' : '' ?><?= $slug === 'gamba' ? ' nav-sponsor' : '' ?>"
                               <?= $isActive ? 'aria-current="page"' : '' ?>>
                                <?= e($label) ?>
                                <?php if ($isActive): ?>
                                    <!-- Carries a view-transition-name, so between pages it
                                         slides to the new link instead of blinking. -->
                                    <span class="nav-indicator" aria-hidden="true"></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>

        <a href="/rewards" class="claim-rewards-btn<?= $ACTIVE === 'rewards' ? ' is-current' : '' ?>"
           <?= $ACTIVE === 'rewards' ? 'aria-current="page"' : '' ?>>
            <i class="fa-solid fa-gift"></i> Rewards
        </a>

        <button type="button" class="nav-toggle" aria-controls="site-nav" aria-expanded="false" aria-label="Open menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>
