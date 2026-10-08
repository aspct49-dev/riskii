<?php
/**
 * Single source of truth for the things that change when a sponsor changes.
 *
 * Before this file, the sponsor name, affiliate link and promo code were typed
 * out by hand in every page — which is how the site ended up still saying
 * "Duelbits" in places long after the deal had moved on.
 */

// --- Sponsor -----------------------------------------------------------------
define('SPONSOR_NAME',  'Gamba');
define('SPONSOR_CODE',  'RiiSki');
define('SPONSOR_LINK',  'https://gamba.com/?c=RiiSki');
define('SPONSOR_LOGO',  'images/gamba-logo-light.png');

// --- The leaderboard we mirror -----------------------------------------------
// Gamba hosts the race; we render its standings. The id is the one in the URL
// of https://gamba.com/promotions/exclusive-leaderboards/22969
define('GAMBA_RACE_ID',  22969);
define('GAMBA_RACE_URL', 'https://gamba.com/promotions/exclusive-leaderboards/22969');

// Gamba's public GraphQL gateway speaks automatic persisted queries: the query
// itself is never sent, only the sha256 of the document their client ships. The
// hash below is `getRaceById` as of their current build. If Gamba rebuild and
// change that document, the gateway answers PersistedQueryNotFound and
// api/gamba.php falls back to posting the full query instead.
define('GAMBA_API',        'https://gamba.com/_api/@');
define('GAMBA_QUERY_HASH', 'fce626ac48edaaf1714f52415711e5dae485413957763c994722e034350e8e29');

// The cache lives in the system temp dir because that is the only writable
// path on Vercel — the deployment itself is read-only. Each serverless
// instance keeps its own copy, which is fine: the file is a hot cache, and a
// miss just means one more call to Gamba.
define('GAMBA_CACHE_FILE', sys_get_temp_dir() . '/rogue-gamba-race.json');
define('GAMBA_CACHE_TTL',  120); // seconds

// --- Site --------------------------------------------------------------------
define('SITE_NAME',    'RogueRewards');
define('SITE_TWITTER', 'https://twitter.com/ROGUERewards');
define('SITE_INSTA',   'https://instagram.com/ROGUERewards');
define('SITE_DISCORD', 'https://discord.gg/riiski');

/**
 * Site content — offers, reward tiers, the counter. Read once per request.
 *
 * `content('offers')` for one key, `content()` for the lot.
 */
function content(?string $key = null)
{
    static $data = null;
    if ($data === null) {
        $data = require __DIR__ . '/content.php';
    }
    return $key === null ? $data : ($data[$key] ?? null);
}

function e(?string $s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}

/** 1st, 2nd, 3rd, 4th … — used by the leaderboard's prize table. */
function ordinal_suffix(int $n): string
{
    if ($n % 100 >= 11 && $n % 100 <= 13) {
        return 'th';
    }
    return ['th', 'st', 'nd', 'rd'][$n % 10] ?? 'th';
}
