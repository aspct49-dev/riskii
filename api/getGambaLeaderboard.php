<?php
/**
 * JSON view of the race, for the leaderboard page's auto-refresh and for cron.
 *
 * GET /api/getGambaLeaderboard          -> cached (<= GAMBA_CACHE_TTL old)
 * GET /api/getGambaLeaderboard?force=1  -> bypass the cache and refetch
 */

require_once __DIR__ . '/../includes/gamba.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: public, max-age=0, s-maxage=60, stale-while-revalidate=300');

$race = gamba_race(isset($_GET['force']));

echo json_encode([
    'ok'          => $race['found'],
    'stale'       => $race['stale'],
    'fetched_at'  => $race['fetched_at'],
    'name'        => $race['name'],
    'pool'        => $race['pool'],
    'currency'    => $race['currency'],
    'ends_at'     => $race['ends_at'],
    'code'        => $race['code'],
    'prizes'      => $race['prizes'],
    'competitors' => $race['competitors'],
], JSON_UNESCAPED_SLASHES);
