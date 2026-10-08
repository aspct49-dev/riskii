<?php
/**
 * Site content.
 *
 * This used to live in MySQL behind an admin dashboard, which was a database,
 * a login and 147 uploaded files to maintain about ten rows that change a few
 * times a year. Editing this file and redeploying is the whole workflow now.
 *
 * Logos are paths under images/.
 */

return [

    // Partner offers, in the order they appear on the home page.
    'offers' => [
        [
            'name'  => 'Gamba',
            'bonus' => '$1K Leaderboard',
            'logo'  => '/images/gamba-logo-light.png',
            'link'  => 'https://gamba.com/?c=RiiSki',
        ],
        [
            'name'  => 'Packdraw',
            'bonus' => '5% Deposit Bonus',
            'logo'  => '/images/packdraw1.webp',
            'link'  => 'https://packdraw.com/?ref=riiski',
        ],
        [
            'name'  => 'Shuffle',
            'bonus' => 'Instant Rakeback',
            'logo'  => '/images/shuffle1.webp',
            'link'  => 'https://shuffle.com/?r=riiski',
        ],
        [
            'name'  => 'Rollbit',
            'bonus' => 'Free Case on Signup',
            'logo'  => '/images/rollbit1.webp',
            'link'  => 'https://rollbit.com/referral/riiski',
        ],
        [
            'name'  => 'CSGORoll',
            'bonus' => '3 Free Cases',
            'logo'  => '/images/csgoroll.webp',
            'link'  => 'https://csgoroll.com/r/riiski',
        ],
        [
            'name'  => 'CSGOEmpire',
            'bonus' => 'Free Case',
            'logo'  => '/images/csgoempire-logo.png',
            'link'  => 'https://csgoempire.com/r/riiski',
        ],
        [
            'name'  => 'Roobet',
            'bonus' => 'INSTANT Roowards',
            'logo'  => '/images/roobet-logo.png',
            'link'  => 'https://roobet.com/?ref=riiski',
        ],
        [
            'name'  => 'Datdrop',
            'bonus' => '5% Deposit Bonus',
            'logo'  => '/images/datdrop-logo.webp',
            'link'  => 'https://datdrop.com/p/riiski',
        ],
    ],


    // What playing on Gamba under the code gets you, shown on /rewards.
    // Text in [brackets] is drawn in the accent colour.
    'gamba_perks' => [
        ['icon' => 'fa-trophy',          'text' => '[$1,000] Monthly Leaderboard', 'link' => '/leaderboard'],
        ['icon' => 'fa-bolt',            'text' => 'INSTANT Lossback up to [20%]'],
        ['icon' => 'fa-ranking-star',    'text' => 'Rank-Up + Level Bonuses'],
        ['icon' => 'fa-hand-holding-dollar', 'text' => '[95%] Affiliate Commission to you, the player'],
    ],

    // Running total shown on the home page.
    'given_away' => 158400,
];
