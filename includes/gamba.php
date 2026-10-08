<?php
/**
 * Reads the live standings for our Gamba exclusive race.
 *
 * Gamba's site is a Nuxt SPA, so there is nothing to scrape out of the HTML —
 * the page boots and then asks their GraphQL gateway for the race. We ask the
 * same gateway the same question and cache the answer, rather than shipping a
 * browser to the server or hand-copying standings into the database.
 */

require_once __DIR__ . '/config.php';

/** The document Gamba's own client sends, used when the persisted hash misses. */
const GAMBA_RACE_QUERY = <<<'GQL'
query getRaceById($raceId: Int!) {
  getRaceById(raceId: $raceId) {
    id
    prize_pool
    start_date
    end_date
    race_name
    style
    competitors { id position display_name total_wagered avatar vip_level_name }
    currency { id code }
    sponsor { id username display_name }
    eligibility { id code usage_count total_wagered }
    prize_distribution { position percentage amount }
    wager_contribution_multipliers { INHOUSE CASINO SPORTS }
    rtp_contribution { range contribution }
  }
}
GQL;

function gamba_http(string $url, ?string $postBody = null): ?array
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        // Two attempts (persisted query, then full query) must both fit
        // inside the 15s function limit set in vercel.json.
        CURLOPT_TIMEOUT        => 6,
        CURLOPT_CONNECTTIMEOUT => 4,
        CURLOPT_FOLLOWLOCATION => true,
        // The gateway is behind Cloudflare and answers browser-shaped requests.
        CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        CURLOPT_HTTPHEADER     => [
            'Accept: application/json',
            'Content-Type: application/json',
            'Origin: https://gamba.com',
            'Referer: ' . GAMBA_RACE_URL,
        ],
    ]);
    if ($postBody !== null) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postBody);
    }

    $raw    = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($raw === false || $status !== 200) {
        return null;
    }
    $json = json_decode($raw, true);
    return is_array($json) ? $json : null;
}

/** Hits Gamba and returns the raw `getRaceById` node, or null if unreachable. */
function gamba_fetch_race(int $raceId): ?array
{
    $vars = json_encode(['raceId' => $raceId]);

    // Preferred path: the persisted query, which is what their client uses and
    // what the gateway is tuned for.
    $url = GAMBA_API . '?' . http_build_query([
        'operationName' => 'getRaceById',
        'variables'     => $vars,
        'extensions'    => json_encode([
            'persistedQuery' => ['version' => 1, 'sha256Hash' => GAMBA_QUERY_HASH],
        ]),
    ]);
    $res = gamba_http($url);

    $missed = $res === null
        || isset($res['errors'])
        || !isset($res['data']['getRaceById']);

    if ($missed) {
        // Gamba rebuilt and the hash moved. Send the whole document instead.
        $res = gamba_http(GAMBA_API, json_encode([
            'operationName' => 'getRaceById',
            'query'         => GAMBA_RACE_QUERY,
            'variables'     => ['raceId' => $raceId],
        ]));
    }

    return $res['data']['getRaceById'] ?? null;
}

/**
 * The race as the site renders it: cached, normalised, and with the prize table
 * already married to the standings.
 *
 * Never throws. If Gamba can't be reached, falls back in order to:
 *   1. this instance's last good copy (temp-dir cache);
 *   2. the snapshot committed with the site (includes/gamba-race.snapshot.json),
 *      so a cold serverless instance that can't reach Gamba still shows the
 *      real pool, prizes and dates rather than a $0 race;
 *   3. an empty race.
 * Anything other than a live answer is marked `stale`, and the page says so.
 */
function gamba_race(bool $force = false): array
{
    $cacheFile = GAMBA_CACHE_FILE;
    $cached    = null;

    if (is_readable($cacheFile)) {
        $cached = json_decode((string) file_get_contents($cacheFile), true);
        $fresh  = is_array($cached)
            && (time() - (int) ($cached['fetched_at'] ?? 0)) < GAMBA_CACHE_TTL;
        if (!$force && $fresh) {
            return $cached;
        }
    }

    $race = gamba_fetch_race(GAMBA_RACE_ID);

    if ($race === null) {
        if (is_array($cached)) {
            $cached['stale'] = true;
            return $cached;
        }
        $snapshot = json_decode((string) @file_get_contents(GAMBA_SNAPSHOT_FILE), true);
        if (is_array($snapshot) && !empty($snapshot['found'])) {
            $snapshot['stale'] = true;
            return $snapshot;
        }
        return gamba_normalise(null);
    }

    $data = gamba_normalise($race);
    if (!is_dir(dirname($cacheFile))) {
        @mkdir(dirname($cacheFile), 0775, true);
    }
    @file_put_contents($cacheFile, json_encode($data), LOCK_EX);

    return $data;
}

/** Writes the committed fallback from a live fetch. Run by hand, never per request. */
function gamba_write_snapshot(): bool
{
    $race = gamba_fetch_race(GAMBA_RACE_ID);
    if ($race === null) {
        fwrite(STDERR, "Gamba unreachable; snapshot not written.
");
        return false;
    }
    $data = gamba_normalise($race);
    file_put_contents(GAMBA_SNAPSHOT_FILE, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "
");
    echo 'Snapshot written: ', $data['name'], ', ', money($data['pool'], 0), ', ', count($data['competitors']), " competitors
";
    return true;
}

/** Flattens Gamba's payload into the handful of fields the templates use. */
function gamba_normalise(?array $race): array
{
    $prizes = [];
    foreach ($race['prize_distribution'] ?? [] as $row) {
        $prizes[(int) $row['position']] = (float) $row['amount'];
    }

    // Standings arrive unordered often enough to be worth sorting ourselves.
    $raw = $race['competitors'] ?? [];
    usort($raw, function ($a, $b) {
        $pa = $a['position'] ?? null;
        $pb = $b['position'] ?? null;
        if ($pa !== null && $pb !== null && $pa != $pb) {
            return $pa <=> $pb;
        }
        return (float) ($b['total_wagered'] ?? 0) <=> (float) ($a['total_wagered'] ?? 0);
    });

    $competitors = [];
    foreach ($raw as $i => $c) {
        $pos = (int) ($c['position'] ?? ($i + 1));
        $competitors[] = [
            'position' => $pos,
            // Gamba exposes only the display name — players who hide theirs come
            // through blank, and the board shows the placeholder rather than an
            // empty cell.
            'username' => trim((string) ($c['display_name'] ?? '')) ?: 'Hidden Player',
            'wagered'  => (float) ($c['total_wagered'] ?? 0),
            'avatar'   => $c['avatar'] ?? null,
            'vip'      => $c['vip_level_name'] ?? null,
            // There is no per-competitor prize field: what a place pays is the
            // distribution table, which is the same for everyone in that slot.
            'prize'    => (float) ($prizes[$pos] ?? 0),
        ];
    }

    $end = $race['end_date'] ?? null;

    return [
        'fetched_at'  => time(),
        'stale'       => false,
        'found'       => $race !== null,
        'id'          => (int) ($race['id'] ?? GAMBA_RACE_ID),
        'name'        => (string) ($race['race_name'] ?? 'Gamba Leaderboard'),
        'pool'        => (float) ($race['prize_pool'] ?? 0),
        'currency'    => (string) ($race['currency']['code'] ?? 'USD'),
        'start_date'  => $race['start_date'] ?? null,
        'end_date'    => $end,
        // Gamba report race times in UTC; the countdown needs an absolute epoch.
        'ends_at'     => $end ? strtotime($end . ' UTC') : null,
        'code'        => $race['eligibility'][0]['code'] ?? SPONSOR_CODE,
        'prizes'      => $prizes,
        'competitors' => $competitors,
        'multipliers' => $race['wager_contribution_multipliers'] ?? [],
        'rtp'         => $race['rtp_contribution'] ?? [],
    ];
}

/** `$1,234.56` — money is formatted in exactly one place. */
function money(float $n, int $dp = 2): string
{
    return '$' . number_format($n, $dp);
}
