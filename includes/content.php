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
            'bonus' => '200% Welcome Bonus',
            'logo'  => 'images/gamba-logo-light.png',
            'link'  => 'https://gamba.com/?c=RiiSki',
        ],
        [
            'name'  => 'Packdraw',
            'bonus' => '5% Deposit Bonus',
            'logo'  => 'images/packdraw1.webp',
            'link'  => 'https://packdraw.com/?ref=riiski',
        ],
        [
            'name'  => 'Shuffle',
            'bonus' => 'Instant Rakeback',
            'logo'  => 'images/shuffle1.webp',
            'link'  => 'https://shuffle.com/?r=riiski',
        ],
        [
            'name'  => 'Rollbit',
            'bonus' => 'Free Case on Signup',
            'logo'  => 'images/rollbit1.webp',
            'link'  => 'https://rollbit.com/referral/riiski',
        ],
        [
            'name'  => 'CSGORoll',
            'bonus' => '3 Free Cases',
            'logo'  => 'images/csgoroll.webp',
            'link'  => 'https://csgoroll.com/r/riiski',
        ],
    ],

    // Wager milestones. Claimed by hand through Discord.
    'rewards' => [
        ['name' => 'Bronze',   'bonus' => 10,  'wager' => 1000],
        ['name' => 'Silver',   'bonus' => 25,  'wager' => 5000],
        ['name' => 'Gold',     'bonus' => 75,  'wager' => 25000],
        ['name' => 'Platinum', 'bonus' => 200, 'wager' => 100000],
        ['name' => 'Diamond',  'bonus' => 500, 'wager' => 250000],
    ],

    // Running total shown on the home page.
    'given_away' => 284500,
];
