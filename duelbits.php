<?php
/**
 * Duelbits was the sponsor until Gamba took over. The URL is still linked from
 * old videos, Discord pins and search results, so it redirects rather than 404s.
 */
require_once __DIR__ . '/includes/config.php';

header('Location: /gamba', true, 301);
exit;
