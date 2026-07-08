<?php
/**
 * FRIDA Data Portal — download.php
 * Serves data product files for authorized download.
 *
 * VULNERABILITY: filePathDownload is checked for valid prefix but
 * ../ sequences are NOT stripped, allowing path traversal after prefix.
 *
 * Reported via DoD VDP: HackerOne #1639364 (July 2022)
 * Status: RESOLVED (July 29, 2022) — replaced with UUID-based IDs
 */

$filePathDownload = $_GET['filePathDownload'] ?? '';

// ── "Validation" — checks prefix but NOT path traversal ──────────────────
$validPrefixes = ['data_products/', 'reports/', 'docs/'];
$authorized    = false;

foreach ($validPrefixes as $prefix) {
    if (strpos($filePathDownload, $prefix) === 0) {
        $authorized = true;
        break;
    }
}

if (!$authorized) {
    http_response_code(403);
    header('Content-Type: text/plain');
    die('Access Denied: Invalid or unauthorized file path.');
}

// ── BUG: ../  not sanitized — traversal possible ─────────────────────────
$dataRoot = '/var/www/html/';
$fullPath = $dataRoot . $filePathDownload;   // e.g. /var/www/html/data_products/MISC/frida_cal/../../../etc/passwd
                                              //       resolves to: /etc/passwd

if (!file_exists($fullPath) || is_dir($fullPath)) {
    http_response_code(404);
    header('Content-Type: text/plain');
    die('File not found.');
}

$filename = basename($fullPath);
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . filesize($fullPath));
readfile($fullPath);
