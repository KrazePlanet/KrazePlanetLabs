<?php
// Lab 1619 — Symfony Profiler Debug Mode (Information Disclosure)
// Real-world finding: Symfony Web Framework with profiler debug mode enabled
// Endpoints: /app_dev.php, /_profiler/phpinfo, /_profiler, /_profiler/open?file=app/config/parameters.yml
// Reference: https://symfony.com/doc/current/profiler.html

$path = isset($_GET['path']) ? $_GET['path'] : '';
$response_code = null;
$response_body = '';
$response_headers = [];
$simulated = false;
$response_note = '';

function esc($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function simulateSymfony($rawPath) {
    // Parse the path — the full URL path after /app_dev.php
    // Example: /app_dev.php/_profiler/phpinfo
    //          /app_dev.php/_profiler/open?file=app/config/parameters.yml

    $path = $rawPath;

    // ── Case 1: /app_dev.php (with or without trailing path) ─────────────────
    if ($path === '/app_dev.php' || $path === '/app_dev.php/') {
        return [
            'code' => 200,
            'headers' => [
                'Server' => 'nginx/1.18.0',
                'Content-Type' => 'text/html; charset=utf-8',
                'X-Debug-Token' => 'a1b2c3d4e5',
                'X-Debug-Token-Link' => '/_profiler/a1b2c3d4e5',
                'Symfony-Version' => '3.4.48',
            ],
            'body' => '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Symfony Application - Development</title>
    <style>
        body { font-family: "Helvetica Neue", Arial, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
        .welcome { max-width: 800px; margin: 40px auto; background: #fff; border-radius: 8px; padding: 40px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; font-size: 28px; }
        .badge { display: inline-block; background: #4e4e4e; color: #fff; padding: 4px 12px; border-radius: 4px; font-size: 12px; margin-right: 6px; }
        .badge.dev { background: #8e44ad; }
        .badge.debug { background: #27ae60; }
        .info { color: #666; line-height: 1.6; }
        .toolbar-icon { display: inline-block; width: 16px; height: 16px; background: #8e44ad; border-radius: 3px; vertical-align: middle; }
        .debug-bar { background: #2c3e50; color: #fff; padding: 8px 16px; font-size: 12px; display: flex; gap: 20px; margin-top: 20px; border-radius: 4px; }
        .debug-bar span { color: #95a5a6; }
        .debug-bar strong { color: #fff; }
    </style>
</head>
<body>
    <div class="welcome">
        <h1>🎯 Welcome to the Symfony Application</h1>
        <p class="info">
            <span class="badge dev">DEV</span>
            <span class="badge debug">DEBUG</span>
            Symfony 3.4.48 &bull; PHP 7.4.33 &bull; MySQL 5.7
        </p>
        <hr style="border:none;border-top:1px solid #eee;margin:20px 0;">
        <p class="info">
            You are running Symfony in <strong>development mode</strong>.
            The web profiler toolbar is displayed at the bottom of all pages.
        </p>
        <p class="info">
            <strong>➡ Available Profiler URLs:</strong><br>
            <code style="background:#f0f0f0;padding:2px 6px;border-radius:3px;">/_profiler/</code> &mdash; Dashboard<br>
            <code style="background:#f0f0f0;padding:2px 6px;border-radius:3px;">/_profiler/phpinfo</code> &mdash; PHP Configuration<br>
            <code style="background:#f0f0f0;padding:2px 6px;border-radius:3px;">/_profiler/open?file=</code> &mdash; File Viewer
        </p>
        <div class="debug-bar">
            <span class="toolbar-icon"></span>
            <strong>DEV</strong> &bull; <span>10 ms</span> &bull; <span>3.5 MB</span> &bull;
            <strong>POST /app_dev.php/</strong> &bull;
            <span>200 OK</span> &bull;
            <a href="/_profiler/a1b2c3d4e5" style="color:#3498db;">token: a1b2c3d4e5</a>
        </div>
        <div style="margin-top:16px;font-size:11px;color:#999;text-align:center;">
            Symfony Profiler &mdash; Development Mode &mdash; X-Debug-Token: a1b2c3d4e5
        </div>
    </div>
</body>
</html>',
            'simulated' => true,
            'note' => 'Symfony app_dev.php response — X-Debug-Token header present.',
        ];
    }

    // ── Case 2: /_profiler/phpinfo (phpinfo disclosure) ──────────────────────
    if (strpos($path, '/_profiler/phpinfo') !== false) {
        return [
            'code' => 200,
            'headers' => [
                'Server' => 'nginx/1.18.0',
                'Content-Type' => 'text/html; charset=utf-8',
                'X-Debug-Token' => 'b2c3d4e5f6',
                'X-Debug-Token-Link' => '/_profiler/b2c3d4e5f6',
            ],
            'body' => '<!DOCTYPE html>
<html>
<head><title>phpinfo() — Symfony Profiler</title></head>
<body style="background:#fff;font-family:monospace;">
<div style="background:#8e44ad;color:#fff;padding:12px 20px;font-size:14px;font-weight:bold;">
    📋 PHP Info &mdash; Symfony Profiler &mdash; DEBUG MODE
</div>
<pre style="padding:20px;font-size:12px;line-height:1.5;">
<span style="color:#8e44ad;font-weight:bold;">PHP Version =&gt; 7.4.33</span>
System =&gt; Linux symfony-prod-01 5.4.0-x86_64 #1 SMP x86_64 GNU/Linux
Build Date =&gt; Feb 15 2023 14:22:08
Server API =&gt; FPM/FastCGI
Virtual Directory Support =&gt; disabled
Configuration File (php.ini) Path =&gt; /etc/php/7.4/fpm
Loaded Configuration File =&gt; /etc/php/7.4/fpm/php.ini
Scan this dir for additional .ini files =&gt; /etc/php/7.4/fpm/conf.d
Additional .ini files parsed =&gt; /etc/php/7.4/fpm/conf.d/10-mysql.ini,
    /etc/php/7.4/fpm/conf.d/10-opcache.ini,
    /etc/php/7.4/fpm/conf.d/15-xdebug.ini

<span style="color:#8e44ad;font-weight:bold;">=== Symfony Profiler Info ===</span>
<span style="color:#e74c3c;font-weight:bold;">$_SERVER[\'DATABASE_URL\']</span> =&gt; mysql://symfony_app:Symf0ny_DB_P@ss!2024@db01.internal:3306/symfony_production
<span style="color:#e74c3c;font-weight:bold;">$_SERVER[\'MAILER_URL\']</span> =&gt; smtp://mailer@symfony.local:S3cr3t_M@il_Pass@mailhog.internal:1025
<span style="color:#e74c3c;font-weight:bold;">$_SERVER[\'SECRET\']</span> =&gt; a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6
<span style="color:#e74c3c;font-weight:bold;">$_SERVER[\'APP_SECRET\']</span> =&gt; dev_secret_token_12345
<span style="color:#e74c3c;font-weight:bold;">$_ENV[\'REDIS_HOST\']</span> =&gt; redis.internal
<span style="color:#e74c3c;font-weight:bold;">$_ENV[\'REDIS_PASSWORD\']</span> =&gt; r3d1s_4uth_t0k3n
<span style="color:#e74c3c;font-weight:bold;">$_ENV[\'ELASTICSEARCH_HOST\']</span> =&gt; elastic.internal:9200
<span style="color:#e74c3c;font-weight:bold;">$_ENV[\'ELASTICSEARCH_API_KEY\']</span> =&gt; es_api_key_a1b2c3d4e5f6g7h8

<span style="color:#8e44ad;font-weight:bold;">=== profiler token info (from phpinfo) ===</span>
profiler token =&gt; b2c3d4e5f6
profiler token last used =&gt; 2023-02-15 14:30:22
<span style="color:#e74c3c;">[!] The profiler token can be used to access profiler data!</span>

<span style="color:#8e44ad;font-weight:bold;">=== Installed PHP Extensions ===</span>
PDO =&gt; enabled
pdo_mysql =&gt; enabled
mysqli =&gt; enabled
curl =&gt; enabled
mbstring =&gt; enabled
xml =&gt; enabled
json =&gt; enabled
session =&gt; enabled
openssl =&gt; enabled
fileinfo =&gt; enabled
zip =&gt; enabled
gd =&gt; enabled
intl =&gt; enabled
xdebug =&gt; enabled (with profiler)

<span style="color:#8e44ad;font-weight:bold;">=== Environment Variables ===</span>
APP_ENV =&gt; dev
APP_DEBUG =&gt; 1
DATABASE_URL =&gt; mysql://symfony_app:Symf0ny_DB_P@ss!2024@db01.internal:3306/symfony_production
MAILER_URL =&gt; smtp://mailer@symfony.local:S3cr3t_M@il_Pass@mailhog.internal:1025
</pre>
<div style="background:#fdf2e9;border-top:3px solid #e74c3c;padding:16px 20px;font-size:12px;color:#c0392b;">
    ⚠ WARNING: The Symfony Profiler exposes phpinfo() with full environment details.
    This includes database credentials, mailer passwords, and secret keys!
</div>
</body>
</html>',
            'simulated' => true,
            'note' => '🚨 SENSITIVE DATA LEAK! Database credentials and secrets exposed via phpinfo().',
        ];
    }

    // ── Case 3: /_profiler (dashboard — request history) ────────────────────
    if (preg_match('#^/(_profiler)/?$#', $path) || $path === '/_profiler' || $path === '/app_dev.php/_profiler' || $path === '/app_dev.php/_profiler/') {
        return [
            'code' => 200,
            'headers' => [
                'Server' => 'nginx/1.18.0',
                'Content-Type' => 'text/html; charset=utf-8',
                'X-Debug-Token' => 'c3d4e5f6a7',
            ],
            'body' => '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Symfony Profiler Dashboard</title>
    <style>
        body { font-family: "Helvetica Neue", Arial, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
        .header { background: #8e44ad; color: #fff; padding: 16px 24px; border-radius: 6px 6px 0 0; margin: -20px -20px 0; }
        .header h1 { margin: 0; font-size: 20px; }
        .header p { margin: 4px 0 0; opacity: 0.8; font-size: 13px; }
        table { width: 100%; border-collapse: collapse; background: #fff; margin-top: 20px; border-radius: 6px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.08); }
        th { background: #f8f9fa; text-align: left; padding: 12px 16px; font-size: 12px; text-transform: uppercase; color: #666; border-bottom: 2px solid #dee2e6; }
        td { padding: 10px 16px; font-size: 13px; border-bottom: 1px solid #eee; }
        tr:hover td { background: #f8f0ff; }
        .token { font-family: monospace; background: #f0f0f0; padding: 2px 8px; border-radius: 3px; font-size: 12px; }
        .method { display: inline-block; padding: 2px 8px; border-radius: 3px; font-size: 11px; font-weight: 600; }
        .method.GET { background: #d4edda; color: #155724; }
        .method.POST { background: #cce5ff; color: #004085; }
        .method.DELETE { background: #f8d7da; color: #721c24; }
        .status { font-weight: 600; }
        .status.\32 00 { color: #28a745; }
        .status.\33 02 { color: #856404; }
        .status.\34 03 { color: #e67e22; }
        .status.\35 00 { color: #dc3545; }
        .badge-sf { background: #8e44ad; color: #fff; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 600; }
        .link { color: #8e44ad; text-decoration: none; font-family: monospace; font-size: 12px; }
        .link:hover { text-decoration: underline; }
        .info-msg { background: #e8f5e9; color: #2e7d32; padding: 12px 16px; border-radius: 6px; margin-top: 16px; font-size: 13px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔬 Symfony Profiler Dashboard</h1>
        <p>Request History &mdash; Last 25 requests</p>
    </div>

    <table>
        <tr>
            <th>Token</th>
            <th>Method</th>
            <th>Path</th>
            <th>Status</th>
            <th>Time</th>
            <th>Memory</th>
        </tr>
        <tr>
            <td><span class="token">b2c3d4e5f6</span></td>
            <td><span class="method GET">GET</span></td>
            <td>/_profiler/phpinfo</td>
            <td><span class="status 200">200</span></td>
            <td>24 ms</td>
            <td>4.2 MB</td>
        </tr>
        <tr>
            <td><span class="token">a1b2c3d4e5</span></td>
            <td><span class="method GET">GET</span></td>
            <td>/app_dev.php</td>
            <td><span class="status 200">200</span></td>
            <td>12 ms</td>
            <td>3.8 MB</td>
        </tr>
        <tr>
            <td><span class="token">z9y8x7w6v5</span></td>
            <td><span class="method POST">POST</span></td>
            <td>/app_dev.php/login</td>
            <td><span class="status 302">302</span></td>
            <td>45 ms</td>
            <td>5.1 MB</td>
        </tr>
        <tr>
            <td><span class="token">u4t3s2r1q0</span></td>
            <td><span class="method GET">GET</span></td>
            <td>/app_dev.php/admin/dashboard</td>
            <td><span class="status 200">200</span></td>
            <td>18 ms</td>
            <td>4.5 MB</td>
        </tr>
        <tr>
            <td><span class="token">p9o8i7u6y5</span></td>
            <td><span class="method POST">POST</span></td>
            <td>/app_dev.php/api/orders</td>
            <td><span class="status 201">201</span></td>
            <td>156 ms</td>
            <td>6.2 MB</td>
        </tr>
        <tr>
            <td><span class="token">t4r3e2w1q0</span></td>
            <td><span class="method DELETE">DELETE</span></td>
            <td>/app_dev.php/api/users/42</td>
            <td><span class="status 403">403</span></td>
            <td>8 ms</td>
            <td>3.1 MB</td>
        </tr>
        <tr>
            <td><span class="token">s7d6f5g4h3</span></td>
            <td><span class="method GET">GET</span></td>
            <td>/app_dev.php/products?page=2</td>
            <td><span class="status 200">200</span></td>
            <td>32 ms</td>
            <td>4.8 MB</td>
        </tr>
        <tr>
            <td><span class="token">j2k1l0m9n8</span></td>
            <td><span class="method GET">GET</span></td>
            <td>/app_dev.php/_profiler</td>
            <td><span class="status 200">200</span></td>
            <td>6 ms</td>
            <td>2.9 MB</td>
        </tr>
        <tr>
            <td><span class="token">b7v6c5x4z3</span></td>
            <td><span class="method GET">GET</span></td>
            <td>/app_dev.php/assets/css/app.css</td>
            <td><span class="status 200">200</span></td>
            <td>4 ms</td>
            <td>2.1 MB</td>
        </tr>
    </table>

    <div class="info-msg">
        <strong>🔍 Profiler Tokens:</strong> Each request gets a unique <code>X-Debug-Token</code>.
        Tokens can be used to access detailed profiler data via <code>/_profiler/{token}</code>.
        <br><br>
        <strong>⚠ Security Issue:</strong> The profiler is accessible without authentication.
        An attacker can enumerate request history and find admin endpoints, API routes,
        and valid session tokens.
    </div>
</body>
</html>',
            'simulated' => true,
            'note' => 'Profiler dashboard exposed — reveals request patterns, admin URLs, and API endpoints.',
        ];
    }

    // ── Case 4: /_profiler/open?file=app/config/parameters.yml ──────────────
    if (strpos($path, '_profiler/open') !== false) {
        // Extract the file parameter
        $fileParam = '';
        if (preg_match('/file=([^&\s]+)/', $path, $m)) {
            $fileParam = urldecode($m[1]);
        }

        if (strpos($fileParam, 'parameters.yml') !== false || strpos($fileParam, 'parameters.yaml') !== false) {
            return [
                'code' => 200,
                'headers' => [
                    'Server' => 'nginx/1.18.0',
                    'Content-Type' => 'text/plain; charset=utf-8',
                    'X-Debug-Token' => 'd4e5f6a7b8',
                    'X-Debug-Token-Link' => '/_profiler/d4e5f6a7b8',
                    'X-Exploit-Demo' => 'symfony_profiler_file_read',
                ],
                'body' => '# This file is the configuration of the Symfony application.
# It contains all the parameters used by the application.
#
# WARNING: This file contains SENSITIVE INFORMATION!
# In production, these values should be set via environment variables.
# In debug mode, the profiler exposes them via the file viewer.

parameters:
    database_host: db01.internal
    database_port: 3306
    database_name: symfony_production
    database_user: symfony_app
    database_password: "Symf0ny_DB_P@ss!2024"
    database_driver: pdo_mysql
    database_charset: UTF8

    mailer_transport: smtp
    mailer_host: mailhog.internal
    mailer_port: 1025
    mailer_user: "mailer@symfony.local"
    mailer_password: "S3cr3t_M@il_Pass"
    mailer_encryption: ~

    secret: "a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6"

    locale: en
    debug: true
    debug_mode: true

    # Redis session storage
    session_handler: redis
    redis_host: redis.internal
    redis_port: 6379
    redis_prefix: "symfony_sess_"
    redis_auth: "r3d1s_4uth_t0k3n"

    # Elasticsearch
    elasticsearch_host: elastic.internal:9200
    elasticsearch_index_prefix: "symfony_prod"

    # API Keys
    google_recaptcha_site_key: "6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"
    google_recaptcha_secret_key: "6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe"
    stripe_public_key: "pk_test_51H4h8aKJjJ3kD2F1"
    stripe_secret_key: "sk_test_51H4h8aKJjJ3kD2F1_aBcDeFgHiJkLmNoPqRsTuVwX"
    aws_access_key_id: "AKIAIOSFODNN7EXAMPLE"
    aws_secret_access_key: "wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY"
    aws_region: us-east-1
    aws_s3_bucket: "symfony-assets-production"

    # OAuth
    github_client_id: "Iv1.a1b2c3d4e5f6g7h8"
    github_client_secret: "a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6a1b2c3d4"
    google_client_id: "123456789012-abc123def456.apps.googleusercontent.com"
    google_client_secret: "GOCSPX-a1b2c3d4e5f6g7h8i9j0k1l2m"

    # Logging
    log_level: debug
    log_path: /var/log/symfony/
    monolog_webhook_url: "https://hooks.slack.com/services/T0ABCDEFG/B0HIJKLMN/abcdef1234567890"

# Security configuration
security:
    encoders:
        App\Entity\User:
            algorithm: bcrypt
            cost: 12
    providers:
        database:
            entity:
                class: App\Entity\User
                property: email

# Framework configuration
framework:
    secret: "%secret%"
    session:
        handler_id: session.handler.redis
        cookie_secure: false
        cookie_httponly: false
    profiler:
        only_exceptions: false
        enabled: true
        dsn: "file:/var/log/symfony/profiler"
    router:
        strict_requirements: true

# Twig configuration (debug mode)
twig:
    debug: true
    strict_variables: true
    globals:
        ga_tracking: "UA-XXXXXXXXX-1"
        app_version: "3.4.48-dev"

# Doctrine ORM
doctrine:
    dbal:
        driver: pdo_mysql
        host: "%database_host%"
        port: "%database_port%"
        dbname: "%database_name%"
        user: "%database_user%"
        password: "%database_password%"
        charset: UTF8
    orm:
        auto_generate_proxy_classes: true
        auto_mapping: true

# SwiftMailer
swiftmailer:
    transport: "%mailer_transport%"
    host: "%mailer_host%"
    port: "%mailer_port%"
    username: "%mailer_user%"
    password: "%mailer_password%"
    spool: { type: memory }
',
                'simulated' => true,
                'note' => '🚨 CRITICAL DATA LEAK! Full Symfony parameters.yml with DB credentials, API keys, AWS secrets, and OAuth tokens!',
            ];
        }

        // Generic file open via _profiler/open
        return [
            'code' => 200,
            'headers' => [
                'Server' => 'nginx/1.18.0',
                'Content-Type' => 'text/plain; charset=utf-8',
                'X-Debug-Token' => 'e5f6a7b8c9',
                'X-Exploit-Demo' => 'symfony_profiler_file_read',
            ],
            'body' => "[File: " . esc($fileParam) . "]\n\n[!] File opened via Symfony Profiler file viewer.\n[!] The profiler reads files with the permissions of the web server.\n\nTry: app/config/parameters.yml\n",
            'simulated' => true,
            'note' => 'File viewer exposed — arbitrary file read via Symfony Profiler.',
        ];
    }

    // ── Fallback ───────────────────────────────────────────────────────────
    return [
        'code' => 404,
        'headers' => [
            'Server' => 'nginx/1.18.0',
            'Content-Type' => 'text/html; charset=utf-8',
        ],
        'body' => '<html>
<head><title>404 Not Found</title></head>
<body style="font-family:sans-serif;text-align:center;padding:80px;background:#f5f5f5;">
    <h1 style="color:#8e44ad;">404</h1>
    <p style="color:#666;">Page not found — <code>' . esc($path) . '</code></p>
    <p style="font-size:12px;color:#999;">Symfony 3.4.48 &mdash; Development Mode</p>
</body>
</html>',
        'simulated' => true,
        'note' => '404 — Endpoint not found on the Symfony application.',
    ];
}

// Process the request
if ($path !== '') {
    $result = simulateSymfony($path);
    $response_code = $result['code'];
    $response_headers = $result['headers'];
    $response_body = $result['body'];
    $simulated = $result['simulated'];
    $response_note = $result['note'];
} else {
    $response_code = 200;
    $response_headers = [
        'Server' => 'nginx/1.18.0',
        'Content-Type' => 'text/html; charset=utf-8',
    ];
    $response_body = '<!DOCTYPE html><html><head><title>Symfony Profiler — Developer Console</title></head><body style="background:#0a0e17;color:#e2e8f0;font-family:monospace;padding:2rem;"><h1>⚡ Symfony Profiler Developer Console</h1><p>Send a request using the <code>?path=</code> parameter.</p></body></html>';
}

// ── Render the page ─────────────────────────────────────────────────────
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Symfony Profiler — Developer Console</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body {
    background: #0a0e17;
    color: #c9d1d9;
    font-family: 'SF Mono', 'Fira Code', 'Cascadia Code', monospace;
    min-height: 100vh;
    overflow-x: hidden;
  }
  ::-webkit-scrollbar { width: 8px; height: 8px; }
  ::-webkit-scrollbar-track { background: #161b22; }
  ::-webkit-scrollbar-thumb { background: #30363d; border-radius: 4px; }
  ::-webkit-scrollbar-thumb:hover { background: #484f58; }

  .topbar {
    background: #161b22;
    border-bottom: 1px solid #30363d;
    padding: 10px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    z-index: 100;
  }
  .topbar-brand {
    display: flex;
    align-items: center;
    gap: 12px;
    color: #f0f6fc;
    font-weight: 600;
    font-size: 15px;
  }
  .topbar-brand .logo {
    width: 32px; height: 32px;
    background: linear-gradient(135deg, #8e44ad, #5b2c6f);
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; color: #fff;
  }
  .topbar-badge {
    background: #21262d;
    color: #8b949e;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    border: 1px solid #30363d;
  }
  .topbar-badge.warning {
    background: #3d2e00;
    color: #d29922;
    border-color: #bb8009;
  }
  .topbar-info {
    display: flex;
    align-items: center;
    gap: 16px;
  }
  .server-meta {
    font-size: 11px;
    color: #8b949e;
  }
  .server-meta strong { color: #58a6ff; }

  .main-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    min-height: calc(100vh - 53px);
  }

  /* Sidebar */
  .sidebar {
    background: #0d1117;
    border-right: 1px solid #21262d;
    padding: 16px;
  }
  .sidebar-section {
    margin-bottom: 20px;
  }
  .sidebar-section h6 {
    color: #8b949e;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 8px;
    font-weight: 600;
  }
  .payload-btn {
    display: block;
    width: 100%;
    text-align: left;
    padding: 8px 12px;
    margin-bottom: 4px;
    background: #161b22;
    border: 1px solid #21262d;
    border-radius: 6px;
    color: #c9d1d9;
    font-size: 12px;
    font-family: 'SF Mono', monospace;
    cursor: pointer;
    transition: all 0.15s;
  }
  .payload-btn:hover {
    background: #1c2333;
    border-color: #8e44ad;
    color: #f0f6fc;
  }
  .payload-btn .method {
    display: inline-block;
    background: #1f6feb;
    color: #fff;
    padding: 1px 6px;
    border-radius: 3px;
    font-size: 10px;
    font-weight: 700;
    margin-right: 6px;
  }
  .payload-btn .method.exploit {
    background: #da3633;
  }
  .payload-btn .method.info {
    background: #8e44ad;
  }
  .payload-btn .desc {
    display: block;
    font-size: 10px;
    color: #8b949e;
    margin-top: 3px;
  }

  /* Content */
  .content {
    padding: 20px;
  }

  .request-panel, .response-panel {
    background: #0d1117;
    border: 1px solid #21262d;
    border-radius: 8px;
    margin-bottom: 16px;
    overflow: hidden;
  }
  .panel-header {
    background: #161b22;
    padding: 10px 16px;
    border-bottom: 1px solid #21262d;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .panel-header .label {
    font-size: 12px;
    font-weight: 600;
    color: #f0f6fc;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .panel-header .label .method-tag {
    background: #1f6feb;
    color: #fff;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 11px;
  }
  .panel-header .label .method-tag.exploit {
    background: #da3633;
  }
  .panel-header .label .method-tag.info {
    background: #8e44ad;
  }

  .panel-body {
    padding: 16px;
    overflow-x: auto;
  }
  .panel-body pre {
    margin: 0;
    font-size: 12px;
    line-height: 1.6;
    color: #c9d1d9;
    white-space: pre-wrap;
    word-break: break-all;
  }

  .url-bar {
    display: flex;
    align-items: center;
    background: #161b22;
    border: 1px solid #30363d;
    border-radius: 6px;
    padding: 8px 12px;
    margin-bottom: 16px;
    font-size: 13px;
    color: #c9d1d9;
    font-family: 'SF Mono', monospace;
    flex-wrap: wrap;
    word-break: break-all;
  }
  .url-bar .proto { color: #8b949e; }
  .url-bar .host { color: #58a6ff; }
  .url-bar .path { color: #c9d1d9; }
  .url-bar .param { color: #d2a8ff; }
  .url-bar .exploit-part { color: #ff7b72; font-weight: bold; }
  .url-bar .token-hl { color: #d29922; font-weight: bold; }

  .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
  }
  .status-badge.success { background: #1b3823; color: #3fb950; border: 1px solid #238636; }
  .status-badge.error { background: #3d1418; color: #f85149; border: 1px solid #da3633; }
  .status-badge.info { background: #1a2b3d; color: #58a6ff; border: 1px solid #1f6feb; }
  .status-badge.warning { background: #3d2e00; color: #d29922; border: 1px solid #bb8009; }

  .info-banner {
    background: #1a2b3d;
    border: 1px solid #1f6feb;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 16px;
    font-size: 13px;
    line-height: 1.6;
  }
  .info-banner.success {
    background: #1b3823;
    border-color: #238636;
  }
  .info-banner.error {
    background: #3d1418;
    border-color: #da3633;
  }
  .info-banner.warning {
    background: #3d2e00;
    border-color: #bb8009;
  }
  .info-banner h5 {
    font-size: 14px;
    margin-bottom: 8px;
    font-weight: 600;
  }
  .info-banner code {
    background: rgba(255,255,255,0.08);
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 12px;
    color: #ffa657;
  }

  .header-row {
    display: flex;
    gap: 4px;
    font-size: 12px;
    padding: 2px 0;
  }
  .header-key { color: #79c0ff; }
  .header-key.warning { color: #d29922; }
  .header-sep { color: #484f58; }
  .header-val { color: #c9d1d9; }
  .header-val.warning { color: #d29922; font-weight: bold; }

  .vuln-footer {
    margin-top: 24px;
    padding: 20px;
    background: #0d1117;
    border: 1px solid #21262d;
    border-radius: 8px;
  }
  .vuln-footer h5 {
    color: #ff7b72;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 12px;
  }
  .vuln-footer code {
    background: rgba(255,255,255,0.08);
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 12px;
  }

  @media (max-width: 768px) {
    .main-layout { grid-template-columns: 1fr; }
    .sidebar { display: none; }
  }
</style>
</head>
<body>

<!-- Top Bar -->
<div class="topbar">
  <div class="topbar-brand">
    <div class="logo">🔬</div>
    <span>Symfony Profiler Console</span>
    <span class="topbar-badge">Symfony 3.4</span>
    <span class="topbar-badge warning">⚠ DEBUG: ON</span>
  </div>
  <div class="topbar-info">
    <span class="server-meta"><strong>PHP</strong> 7.4.33</span>
    <span class="server-meta"><strong>host</strong> example.com</span>
  </div>
</div>

<div class="main-layout">
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="sidebar-section">
      <h6>📡 Symfony Endpoints</h6>
      <button class="payload-btn" onclick="loadPath('/app_dev.php')">
        <span class="method info">GET</span> /app_dev.php
        <span class="desc">Application entry — X-Debug-Token header</span>
      </button>
      <button class="payload-btn" onclick="loadPath('/app_dev.php/_profiler/phpinfo')">
        <span class="method exploit">GET</span> /_profiler/phpinfo
        <span class="desc">🚨 phpinfo() — DB credentials exposed!</span>
      </button>
      <button class="payload-btn" onclick="loadPath('/app_dev.php/_profiler')">
        <span class="method info">GET</span> /_profiler
        <span class="desc">Profiler dashboard — request history</span>
      </button>
      <button class="payload-btn" onclick="loadPath('/app_dev.php/_profiler/open?file=app/config/parameters.yml')">
        <span class="method exploit">GET</span> /_profiler/open
        <span class="desc">🚀 EXPLOIT — Read parameters.yml (all secrets!)</span>
      </button>
    </div>
    <div class="sidebar-section" style="margin-top:20px;padding-top:16px;border-top:1px solid #21262d;">
      <h6>🔬 Vulnerability Info</h6>
      <div style="font-size:11px;color:#8b949e;line-height:1.6;">
        <p><strong style="color:#f0f6fc;">CVE:</strong> N/A (Debug mode misconfig)</p>
        <p><strong style="color:#f0f6fc;">Impact:</strong> Information disclosure — DB creds, API keys, secrets</p>
        <p><strong style="color:#f0f6fc;">Version:</strong> Symfony 3.4.x</p>
        <p><strong style="color:#f0f6fc;">Setting:</strong> <code style="background:rgba(255,255,255,0.08);padding:1px 4px;border-radius:3px;">app_dev.php</code> accessible</p>
        <p style="margin-top:8px;">
          <a href="https://symfony.com/doc/current/profiler.html" target="_blank" style="color:#58a6ff;">📖 Symfony Profiler docs →</a>
        </p>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="content">
    <?php if ($path === ''): ?>
      <!-- Landing state — no request made yet -->
      <div class="info-banner warning">
        <h5>🔬 Symfony Profiler — Debug Mode Enabled</h5>
        <p style="color:#d29922;margin:0;">
          This is a simulation of a Symfony 3.4 application running in <strong>debug mode</strong>
          with the profiler exposed. The <code>app_dev.php</code> front controller is accessible,
          allowing anyone to interact with the Symfony profiler endpoints.
        </p>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px;">
        <div class="request-panel" style="border-color:#8e44ad;">
          <div class="panel-header" style="border-color:#8e44ad;">
            <span class="label" style="color:#c39bd3;">📋 Info</span>
          </div>
          <div class="panel-body" style="text-align:center;padding:24px;">
            <div style="font-size:32px;margin-bottom:8px;">🔍</div>
            <div style="font-size:12px;color:#8b949e;">Profiler endpoints are accessible</div>
            <div style="font-size:10px;color:#484f58;margin-top:4px;">200 OK + X-Debug-Token</div>
          </div>
        </div>
        <div class="request-panel" style="border-color:#da3633;">
          <div class="panel-header" style="border-color:#da3633;">
            <span class="label" style="color:#f85149;">🚨 Exploit</span>
          </div>
          <div class="panel-body" style="text-align:center;padding:24px;">
            <div style="font-size:32px;margin-bottom:8px;">💀</div>
            <div style="font-size:12px;color:#8b949e;">Credentials and secrets exposed!</div>
            <div style="font-size:10px;color:#484f58;margin-top:4px;">Full parameters.yml disclosure</div>
          </div>
        </div>
      </div>

      <div class="vuln-footer">
        <h5>🔓 How Symfony Profiler Debug Mode Exploitation Works</h5>
        <p style="font-size:13px;line-height:1.7;color:#c9d1d9;">
          When a Symfony application is deployed with <code>app_dev.php</code> accessible
          (the development front controller), attackers can access the <strong>Symfony Profiler</strong>
          — a powerful debugging tool that exposes sensitive information.
        </p>
        <p style="font-size:13px;line-height:1.7;color:#c9d1d9;">
          <strong>Key endpoints to fuzz:</strong><br>
          <code style="font-size:14px;color:#ff7b72;">/app_dev.php</code> &mdash; Front controller (debug mode)<br>
          <code style="font-size:14px;color:#ff7b72;">/_profiler/phpinfo</code> &mdash; PHP configuration with env vars<br>
          <code style="font-size:14px;color:#ff7b72;">/_profiler/</code> &mdash; Request history dashboard<br>
          <code style="font-size:14px;color:#ff7b72;">/_profiler/open?file=app/config/parameters.yml</code> &mdash; File viewer!
        </p>
        <p style="font-size:13px;line-height:1.7;color:#c9d1d9;margin-bottom:0;">
          The <code>/_profiler/open</code> endpoint reads arbitrary files from the server's filesystem,
          exposing <strong>database credentials, API keys, secret tokens, and AWS keys</strong>.
        </p>
      </div>

    <?php else: ?>
      <!-- Results view -->
      <?php
        $isSuccess = $response_code === 200;
        $isExploit = strpos($path, 'parameters.yml') !== false || strpos($path, 'phpinfo') !== false;
        $isSensitive = strpos($path, 'phpinfo') !== false;
        $isDashboard = strpos($path, '_profiler') !== false && strpos($path, 'phpinfo') === false && strpos($path, 'open') === false;
        $isNormal = $isSuccess && !$isExploit && !$isSensitive && !$isDashboard;
      ?>

      <!-- Info Banner -->
      <?php if ($isExploit && strpos($path, 'parameters.yml') !== false): ?>
        <div class="info-banner error">
          <h5>💀 CRITICAL DATA LEAK — Full parameters.yml Exposed!</h5>
          <p style="margin:0;color:#ffa198;">
            The Symfony Profiler file viewer <code>/_profiler/open</code> returned the full
            <code>parameters.yml</code> configuration file. This file contains <strong>database credentials,
            mailer passwords, API keys, AWS secrets, and OAuth tokens</strong>.
          </p>
        </div>
      <?php elseif ($isSensitive): ?>
        <div class="info-banner warning">
          <h5>🚨 Sensitive Data Disclosure — phpinfo()</h5>
          <p style="margin:0;color:#d29922;">
            The <code>/_profiler/phpinfo</code> endpoint exposes the full PHP configuration,
            including environment variables with <strong>DATABASE_URL</strong>,
            <strong>MAILER_URL</strong>, and <strong>APP_SECRET</strong>.
            Look for the "profiler token" in the phpinfo output!
          </p>
        </div>
      <?php elseif ($isDashboard): ?>
        <div class="info-banner">
          <h5>📋 Profiler Dashboard — Request History</h5>
          <p style="margin:0;color:#8b949e;">
            The profiler dashboard reveals all recent requests including admin panel URLs,
            API endpoints, and session tokens. No authentication required.
          </p>
        </div>
      <?php elseif ($isNormal): ?>
        <div class="info-banner">
          <h5>✅ Application Loaded — Debug Mode Active</h5>
          <p style="margin:0;color:#8b949e;">
            The Symfony application loaded in development mode. Note the
            <code>X-Debug-Token</code> header in the response — this token is used
            to access detailed profiler data.
          </p>
        </div>
      <?php endif; ?>

      <!-- URL Bar -->
      <div class="url-bar">
        <span class="proto">https://</span>
        <span class="host">example.com</span>
        <?php
          $displayPath = $path;
          // Highlight the query parameter part
          if (strpos($displayPath, '?') !== false) {
              $parts = explode('?', $displayPath, 2);
              echo '<span class="path">' . esc($parts[0]) . '</span>';
              echo '?<span class="param">' . esc($parts[1]) . '</span>';
          } else {
              echo '<span class="path">' . esc($displayPath) . '</span>';
          }
        ?>
      </div>

      <!-- Status -->
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
        <span class="status-badge <?= $isExploit ? 'error' : ($isSensitive ? 'warning' : ($isSuccess ? 'success' : 'info')) ?>">
          <?= $response_code ?> <?= $response_code === 200 ? 'OK' : ($response_code === 400 ? 'Bad Request' : 'Not Found') ?>
        </span>
        <span style="font-size:11px;color:#8b949e;">
          <?= esc($response_note) ?>
        </span>
      </div>

      <!-- Request Panel -->
      <div class="request-panel">
        <div class="panel-header">
          <span class="label">
            <span class="method-tag <?= $isExploit ? 'exploit' : ($isSensitive ? 'info' : '') ?>">
              <?= $isExploit ? 'EXPLOIT' : ($isSensitive ? 'LEAK' : 'GET') ?>
            </span>
            Request
          </span>
          <span style="font-size:11px;color:#8b949e;">Raw HTTP</span>
        </div>
        <div class="panel-body">
          <pre><?= esc("GET {$path} HTTP/1.1") . "\n" ?><span style="color:#8b949e;">Host: example.com
User-Agent: Mozilla/5.0 (X11; Linux x86_64) rv:102.0
Accept: text/html,application/xhtml+xml
Accept-Language: en-US,en;q=0.5
Connection: keep-alive</span></pre>
        </div>
      </div>

      <!-- Response Panel -->
      <div class="response-panel">
        <div class="panel-header">
          <span class="label">
            Response
            <span style="font-size:11px;color:#8b949e;font-weight:400;">
              — <?= $response_code ?> <?= $response_code === 200 ? 'OK' : ($response_code === 400 ? 'Bad Request' : 'Not Found') ?>
            </span>
          </span>
        </div>
        <div class="panel-body">
          <?php
            $status_text = $response_code === 200 ? 'OK' : ($response_code === 400 ? 'Bad Request' : 'Not Found');
            $header_lines = '<span style="color:#8b949e;">HTTP/1.1 ' . $response_code . ' ' . $status_text . '</span>' . "\n";
            foreach ($response_headers as $key => $val) {
                $isTokenHeader = ($key === 'X-Debug-Token' || $key === 'X-Debug-Token-Link');
                $keyClass = $isTokenHeader ? 'warning' : '';
                $valClass = $isTokenHeader ? 'warning' : '';
                $header_lines .= '<span class="header-row"><span class="header-key ' . $keyClass . '">' . esc($key) . '</span><span class="header-sep">: </span><span class="header-val ' . $valClass . '">' . esc($val) . '</span></span>';
            }
            $header_lines .= "\n" . '<span style="color:#484f58;">—</span>' . "\n";
            // Append the response body but don't double-escape HTML content
            // Check if body is HTML (starts with <!DOCTYPE or <html)
            $bodyTrimmed = trim($response_body);
            if (preg_match('/^<!DOCTYPE/i', $bodyTrimmed) || preg_match('/^<html/i', $bodyTrimmed)) {
                // HTML content — just dump it as-is (it's already escaped/safe)
                $header_lines .= $bodyTrimmed;
            } else {
                $header_lines .= esc($bodyTrimmed);
            }
          ?><pre><?= $header_lines ?></pre>
        </div>
      </div>

      <!-- Vulnerability Explanation (shown on exploit payloads) -->
      <?php if ($isExploit || $isSensitive): ?>
      <div class="vuln-footer">
        <h5>🔓 Exploit Analysis — Symfony Profiler Information Disclosure</h5>
        <p style="font-size:13px;line-height:1.7;color:#c9d1d9;">
          <strong>Request:</strong><br>
          <code>GET <?= esc($path) ?> HTTP/1.1</code><br>
          <code>Host: example.com</code>
        </p>
        <p style="font-size:13px;line-height:1.7;color:#c9d1d9;">
          <strong>Why it works:</strong><br>
          Symfony's <code>app_dev.php</code> front controller enables the <strong>Web Profiler</strong>
          and <strong>Debug Toolbar</strong>. In production, the <code>app.php</code> front controller
          should be used instead. When <code>app_dev.php</code> is left accessible:
        </p>
        <ul style="font-size:13px;line-height:1.8;color:#c9d1d9;padding-left:20px;">
          <li><code>/_profiler/phpinfo</code> — Exposes <strong>phpinfo()</strong> with all environment variables including database credentials.</li>
          <li><code>/_profiler/</code> — Shows <strong>full request history</strong> revealing admin URLs, API routes, and profiler tokens.</li>
          <li><code>/_profiler/open?file=...</code> — <strong>Arbitrary file read</strong> via the profiler's file viewer component.</li>
        </ul>
        <p style="font-size:13px;line-height:1.7;color:#c9d1d9;margin-bottom:0;">
          <strong>Impact:</strong> Full compromise of application secrets — database credentials,
          API keys, AWS access keys, OAuth tokens, and mailer passwords. This data can be used
          for lateral movement and data exfiltration.
        </p>
      </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>

<script>
function loadPath(path) {
  window.location.href = '?path=' + encodeURIComponent(path);
}
</script>
</body>
</html>
