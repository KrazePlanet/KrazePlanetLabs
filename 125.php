<?php
// LFI Lab 125 — DoD Jolokia JMX Bridge
// HackerOne Report #2778380 (High — U.S. Dept of Defense)
// Unauthenticated LFI via ! path separator in compilerDirectivesAdd endpoint

// ── PATH_INFO API handler ──────────────────────────────────────────────────
$pathInfo = $_SERVER['PATH_INFO'] ?? '';
$marker   = '/compilerDirectivesAdd/';

if (strpos($pathInfo, $marker) !== false) {
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-cache');

    $afterMarker = substr($pathInfo, strpos($pathInfo, $marker) + strlen($marker));

    // Replace ! with / (the Jolokia ! separator trick), then normalize slashes
    $filePath = preg_replace('#/+#', '/', str_replace('!', '/', $afterMarker));

    // Ensure path starts with /
    if (substr($filePath, 0, 1) !== '/') {
        $filePath = '/' . $filePath;
    }

    $fsRoot  = __DIR__ . '/lab125_fs';
    $absPath = realpath($fsRoot . $filePath);

    $mbean     = 'com.sun.management:type=DiagnosticCommand';
    $timestamp = 1728704522; // Oct 12 2024 04:22:02 UTC
    $argument  = $filePath;

    if ($absPath === false || strpos($absPath, realpath($fsRoot)) !== 0) {
        http_response_code(404);
        echo json_encode([
            "request" => [
                "mbean"     => $mbean,
                "arguments" => [$argument],
                "type"      => "exec",
                "operation" => "compilerDirectivesAdd"
            ],
            "error_type" => "java.io.FileNotFoundException",
            "error"      => "java.io.FileNotFoundException : File not found: " . $argument,
            "status"     => 404,
            "timestamp"  => $timestamp
        ], JSON_PRETTY_PRINT);
        exit;
    }

    $content = file_get_contents($absPath);

    echo json_encode([
        "request" => [
            "mbean"     => $mbean,
            "arguments" => [$argument],
            "type"      => "exec",
            "operation" => "compilerDirectivesAdd"
        ],
        "value"     => $content,
        "timestamp" => $timestamp,
        "status"    => 200
    ], JSON_PRETTY_PRINT);
    exit;
}

// ── Landing page only for non-API requests ─────────────────────────────────
function esc($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Jolokia — JMX HTTP Bridge</title>
<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',sans-serif;background:#f0f2f5;color:#1a1a2e;font-size:13px;min-height:100vh;display:flex;flex-direction:column;}

/* ── Top bar ──────────────────────────────────────────────────────────────── */
.topbar{background:#1a1a2e;color:rgba(255,255,255,.6);font-size:.65rem;padding:4px 20px;display:flex;align-items:center;gap:6px;}
.topbar svg{width:11px;height:11px;fill:rgba(255,255,255,.5);}

/* ── Header ──────────────────────────────────────────────────────────────── */
.site-header{background:#fff;border-bottom:3px solid #e8a000;box-shadow:0 1px 3px rgba(0,0,0,.1);}
.site-header-inner{max-width:1140px;margin:0 auto;padding:10px 20px;display:flex;align-items:center;gap:14px;}
.jolokia-logo{width:40px;height:40px;background:linear-gradient(135deg,#e8a000,#c47c00);border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.jolokia-logo svg{width:22px;height:22px;fill:#fff;}
.header-title{flex:1;}
.header-title-main{font-size:1.1rem;font-weight:800;color:#1a1a2e;letter-spacing:-.01em;}
.header-title-sub{font-size:.68rem;color:#888;margin-top:1px;}
.header-version{font-size:.68rem;background:#e8f4e8;color:#2d7d32;border:1px solid #c8e6c9;padding:2px 8px;border-radius:10px;font-weight:700;}

/* ── Content ─────────────────────────────────────────────────────────────── */
.content-wrap{max-width:1140px;margin:0 auto;padding:16px 20px;flex:1;display:grid;grid-template-columns:1fr 260px;gap:16px;}

/* ── Panel ───────────────────────────────────────────────────────────────── */
.panel{background:#fff;border:1px solid #e0e0e0;border-radius:4px;overflow:hidden;box-shadow:0 1px 2px rgba(0,0,0,.06);}
.panel-header{background:#2c3e50;color:#fff;padding:9px 14px;display:flex;align-items:center;gap:8px;}
.panel-header-icon{width:16px;height:16px;fill:none;stroke:#e8a000;stroke-width:2;}
.panel-title{font-size:.8rem;font-weight:700;letter-spacing:.02em;}
.panel-sub{font-size:.62rem;color:rgba(255,255,255,.5);margin-left:auto;}
.panel-body{padding:14px;}

/* ── Status banner ───────────────────────────────────────────────────────── */
.status-banner{background:#f8fffe;border:1px solid #c8e6c9;border-radius:3px;padding:10px 14px;display:flex;align-items:center;gap:20px;margin-bottom:14px;flex-wrap:wrap;}
.status-dot{width:8px;height:8px;background:#4caf50;border-radius:50%;box-shadow:0 0 0 2px rgba(76,175,80,.3);animation:pulse 2s infinite;}
@keyframes pulse{0%,100%{box-shadow:0 0 0 2px rgba(76,175,80,.3);}50%{box-shadow:0 0 0 4px rgba(76,175,80,.1);}}
.status-label{font-size:.72rem;font-weight:700;color:#2d7d32;}
.status-meta{font-size:.7rem;color:#666;display:flex;align-items:center;gap:4px;}
.status-meta strong{color:#333;}

/* ── Op table ────────────────────────────────────────────────────────────── */
.op-section{margin-bottom:16px;}
.op-section-title{font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#666;margin-bottom:8px;padding-bottom:4px;border-bottom:2px solid #e8a000;}
.op-table{width:100%;border-collapse:collapse;}
.op-table th{background:#f7f7f7;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;padding:6px 10px;text-align:left;color:#555;border-bottom:1px solid #e8e8e8;}
.op-table td{padding:8px 10px;border-bottom:1px solid #f5f5f5;font-size:.76rem;vertical-align:top;}
.op-table tr:last-child td{border-bottom:none;}
.op-table tr:hover td{background:#fafafa;}
.op-name{font-family:monospace;font-weight:700;color:#1a1a2e;font-size:.75rem;}
.op-desc{color:#666;line-height:1.4;}
.op-path{font-family:monospace;font-size:.68rem;color:#888;margin-top:2px;word-break:break-all;}
.badge-exec{background:#e3f2fd;color:#1565c0;font-size:.62rem;padding:1px 5px;border-radius:2px;font-weight:700;}
.badge-read{background:#f3e5f5;color:#6a1b9a;font-size:.62rem;padding:1px 5px;border-radius:2px;font-weight:700;}

/* ── URL builder ─────────────────────────────────────────────────────────── */
.url-box{background:#1a1a2e;border-radius:3px;padding:12px;font-family:monospace;font-size:.72rem;line-height:1.9;word-break:break-all;}
.url-scheme{color:#a8ff78;}
.url-host{color:#ffd700;}
.url-path{color:#87ceeb;}
.url-endpoint{color:#ff9f43;}
.url-arg{color:#ff6b6b;}
.url-comment{color:#666;font-style:italic;}

/* ── Try it box ──────────────────────────────────────────────────────────── */
.try-box{background:#f7f8fa;border:1px solid #e0e0e0;border-radius:3px;padding:12px;margin-top:12px;}
.try-title{font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#666;margin-bottom:8px;}
.try-input-row{display:flex;gap:8px;align-items:stretch;}
.try-prefix{font-family:monospace;font-size:.72rem;background:#fff;border:1px solid #ccc;border-right:none;padding:5px 8px;border-radius:2px 0 0 2px;color:#888;white-space:nowrap;display:flex;align-items:center;}
.try-input{flex:1;font-family:monospace;font-size:.75rem;border:1px solid #ccc;padding:5px 8px;border-radius:0;outline:none;border-radius:0;}
.try-btn{background:#e8a000;color:#fff;border:none;padding:5px 14px;font-size:.75rem;font-weight:700;cursor:pointer;border-radius:0 2px 2px 0;font-family:inherit;white-space:nowrap;}
.try-btn:hover{background:#c47c00;}
.try-note{font-size:.65rem;color:#888;margin-top:5px;}

/* ── Response viewer ─────────────────────────────────────────────────────── */
.response-box{margin-top:10px;display:none;}
.response-header{background:#2c3e50;color:#fff;padding:6px 10px;font-size:.68rem;font-weight:700;display:flex;align-items:center;gap:8px;border-radius:2px 2px 0 0;}
.response-status-ok{background:#27ae60;color:#fff;padding:1px 6px;border-radius:2px;font-size:.65rem;}
.response-status-err{background:#c0392b;color:#fff;padding:1px 6px;border-radius:2px;font-size:.65rem;}
.response-body{background:#1a1a2e;border:1px solid #333;border-top:none;border-radius:0 0 2px 2px;padding:10px;font-family:monospace;font-size:.7rem;color:#a8ff78;line-height:1.5;max-height:340px;overflow:auto;white-space:pre-wrap;word-break:break-all;}

/* ── Sidebar ─────────────────────────────────────────────────────────────── */
.sidebar-card{background:#fff;border:1px solid #e0e0e0;border-radius:4px;overflow:hidden;margin-bottom:12px;box-shadow:0 1px 2px rgba(0,0,0,.06);}
.sidebar-card-header{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;padding:7px 11px;border-bottom:1px solid #f0f0f0;}
.sidebar-card-body{padding:10px 11px;font-size:.72rem;line-height:1.7;color:#555;}
.info-row{display:flex;gap:6px;margin-bottom:4px;}
.info-lbl{color:#999;min-width:70px;flex-shrink:0;font-size:.7rem;}
.info-val{font-weight:700;color:#1a1a2e;font-size:.7rem;}

/* ── Footer ──────────────────────────────────────────────────────────────── */
footer{background:#2c3e50;color:#7f8c8d;font-size:.65rem;padding:10px 20px;display:flex;justify-content:space-between;flex-wrap:wrap;gap:4px;border-top:3px solid #e8a000;margin-top:auto;}
footer a{color:#7f8c8d;text-decoration:none;}
footer a:hover{color:#e8a000;}
</style>
</head>
<body>

<div class="topbar">
  <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15v-4H7l5-8v4h4l-5 8z"/></svg>
  JVM Management Interface · MBean Server · Apache Tomcat 9.0.58
</div>

<header class="site-header">
  <div class="site-header-inner">
    <div class="jolokia-logo">
      <svg viewBox="0 0 24 24"><path d="M12 2a10 10 0 100 20A10 10 0 0012 2zm0 2a8 8 0 110 16A8 8 0 0112 4zm-1 3v5l4 2-1 1.73-5-2.73V7h2z"/></svg>
    </div>
    <div class="header-title">
      <div class="header-title-main">Jolokia</div>
      <div class="header-title-sub">JMX-HTTP Bridge · Remote JMX with JSON over HTTP</div>
    </div>
    <span class="header-version">v1.7.2</span>
  </div>
</header>

<div class="content-wrap">

  <!-- Main panel -->
  <div>

    <!-- Status -->
    <div class="panel" style="margin-bottom:14px;">
      <div class="panel-header">
        <svg class="panel-header-icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <span class="panel-title">Agent Status</span>
        <span class="panel-sub">com.sun.management</span>
      </div>
      <div class="panel-body">
        <div class="status-banner">
          <div class="status-dot"></div>
          <span class="status-label">Running</span>
          <span class="status-meta"><strong>MBean Count:</strong> 1,247</span>
          <span class="status-meta"><strong>JVM:</strong> OpenJDK 11.0.21</span>
          <span class="status-meta"><strong>Host:</strong> ████████.mil:8778</span>
          <span class="status-meta"><strong>Auth:</strong> <span style="color:#e53935;font-weight:700;">None</span></span>
        </div>
      </div>
    </div>

    <!-- Operations -->
    <div class="panel" style="margin-bottom:14px;">
      <div class="panel-header">
        <svg class="panel-header-icon" viewBox="0 0 24 24"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
        <span class="panel-title">Available Operations — DiagnosticCommand</span>
        <span class="panel-sub">com.sun.management:type=DiagnosticCommand</span>
      </div>
      <div class="panel-body">
        <div class="op-section">
          <div class="op-section-title">MBean: com.sun.management:type=DiagnosticCommand</div>
          <table class="op-table">
            <thead>
              <tr>
                <th>Operation</th>
                <th>Type</th>
                <th>Description</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <div class="op-name">compilerDirectivesAdd</div>
                  <div class="op-path">/jolokia/exec/com.sun.management:type=DiagnosticCommand/compilerDirectivesAdd/{argument}</div>
                </td>
                <td><span class="badge-exec">EXEC</span></td>
                <td class="op-desc">Add compiler directives from file. Takes a file path argument. Uses <code>!</code> as directory separator in URL path.</td>
              </tr>
              <tr>
                <td>
                  <div class="op-name">compilerDirectivesClear</div>
                  <div class="op-path">/jolokia/exec/com.sun.management:type=DiagnosticCommand/compilerDirectivesClear</div>
                </td>
                <td><span class="badge-exec">EXEC</span></td>
                <td class="op-desc">Remove all compiler directives.</td>
              </tr>
              <tr>
                <td>
                  <div class="op-name">compilerDirectivesPrint</div>
                  <div class="op-path">/jolokia/exec/com.sun.management:type=DiagnosticCommand/compilerDirectivesPrint</div>
                </td>
                <td><span class="badge-read">READ</span></td>
                <td class="op-desc">Print current compiler directives to stdout.</td>
              </tr>
              <tr>
                <td>
                  <div class="op-name">vmSystemProperties</div>
                  <div class="op-path">/jolokia/read/java.lang:type=Runtime/SystemProperties</div>
                </td>
                <td><span class="badge-read">READ</span></td>
                <td class="op-desc">Get all JVM system properties.</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- URL structure -->
        <div class="op-section">
          <div class="op-section-title">URL Structure</div>
          <div class="url-box">
<span class="url-comment">// Base endpoint</span>
<span class="url-scheme">https://</span><span class="url-host">████████.mil</span><span class="url-path">/jolokia/exec/com.sun.management:type=DiagnosticCommand/</span><span class="url-endpoint">compilerDirectivesAdd</span><span class="url-path">/</span><span class="url-arg">{argument}</span>

<span class="url-comment">// {argument} uses ! as directory separator</span>
<span class="url-comment">// Example: read /etc/passwd</span>
<span class="url-path">/jolokia/exec/com.sun.management:type=DiagnosticCommand/</span><span class="url-endpoint">compilerDirectivesAdd</span><span class="url-path">/</span><span class="url-arg">!/etc!/passwd</span>
          </div>
        </div>

        <!-- Try it -->
        <div class="try-box">
          <div class="try-title">Send Request</div>
          <div class="try-input-row">
            <div class="try-prefix">125.php/jolokia/exec/com.sun.management:type=DiagnosticCommand/compilerDirectivesAdd/</div>
            <input type="text" class="try-input" id="tryInput" placeholder="!/etc!/passwd" value="!/etc!/passwd">
            <button class="try-btn" onclick="sendRequest()">Execute</button>
          </div>
          <div class="try-note">Use <code>!</code> as directory separator. Example: <code>!/etc!/passwd</code> → reads <code>/etc/passwd</code></div>

          <div class="response-box" id="responseBox">
            <div class="response-header">
              <span>HTTP Response</span>
              <span id="responseStatus"></span>
            </div>
            <pre class="response-body" id="responseBody"></pre>
          </div>
        </div>
      </div>
    </div>

    <!-- Other endpoints -->
    <div class="panel">
      <div class="panel-header">
        <svg class="panel-header-icon" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
        <span class="panel-title">Other Jolokia Endpoints</span>
      </div>
      <div class="panel-body">
        <table class="op-table">
          <thead><tr><th>Path</th><th>Method</th><th>Description</th></tr></thead>
          <tbody>
            <tr><td class="op-name">/jolokia/version</td><td><span class="badge-read">GET</span></td><td class="op-desc">Returns agent version and server info</td></tr>
            <tr><td class="op-name">/jolokia/list</td><td><span class="badge-read">GET</span></td><td class="op-desc">Lists all registered MBeans and their attributes</td></tr>
            <tr><td class="op-name">/jolokia/read/{mbean}/{attr}</td><td><span class="badge-read">GET</span></td><td class="op-desc">Read a specific MBean attribute value</td></tr>
            <tr><td class="op-name">/jolokia/search/{pattern}</td><td><span class="badge-read">GET</span></td><td class="op-desc">Search MBeans matching a pattern</td></tr>
          </tbody>
        </table>
      </div>
    </div>

  </div><!-- end main -->

  <!-- Sidebar -->
  <div>
    <div class="sidebar-card">
      <div class="sidebar-card-header" style="background:#2c3e50;color:#fff;">System Info</div>
      <div class="sidebar-card-body">
        <div class="info-row"><span class="info-lbl">Agent</span><span class="info-val">Jolokia 1.7.2</span></div>
        <div class="info-row"><span class="info-lbl">Protocol</span><span class="info-val">HTTP / JSON</span></div>
        <div class="info-row"><span class="info-lbl">JVM</span><span class="info-val">OpenJDK 11.0.21</span></div>
        <div class="info-row"><span class="info-lbl">Server</span><span class="info-val">Tomcat 9.0.58</span></div>
        <div class="info-row"><span class="info-lbl">Auth</span><span style="color:#e53935;font-weight:700;font-size:.7rem;">Disabled</span></div>
        <div class="info-row"><span class="info-lbl">Port</span><span class="info-val">8778</span></div>
      </div>
    </div>

    <div class="sidebar-card">
      <div class="sidebar-card-header" style="background:#4a5e2f;color:#fff;">Recent Requests</div>
      <div class="sidebar-card-body" style="font-size:.7rem;line-height:1.7;">
        <div style="color:#aaa;font-size:.65rem;">Oct 12, 2024 · 04:22 UTC</div>
        <div>exec compilerDirectivesAdd</div>
        <div style="color:#aaa;font-size:.65rem;margin-top:5px;">Oct 12, 2024 · 04:21 UTC</div>
        <div>read SystemProperties</div>
        <div style="color:#aaa;font-size:.65rem;margin-top:5px;">Oct 12, 2024 · 04:18 UTC</div>
        <div>list MBeans</div>
      </div>
    </div>

    <div class="sidebar-card">
      <div class="sidebar-card-header" style="background:#6b6b6b;color:#fff;">About Jolokia</div>
      <div class="sidebar-card-body" style="font-size:.7rem;line-height:1.7;color:#666;">
        Jolokia is an HTTP/JSON bridge for remote JMX access. It provides a RESTful interface to MBeans registered in the JVM. The <code>DiagnosticCommand</code> MBean exposes low-level JVM diagnostic operations.
      </div>
    </div>
  </div>

</div><!-- .content-wrap -->

<footer>
  <span>Jolokia JMX-HTTP Bridge v1.7.2 · Apache Tomcat 9.0.58 · OpenJDK 11.0.21</span>
  <span>Based on <a href="https://hackerone.com/reports/2778380" target="_blank" rel="noopener">HackerOne #2778380</a></span>
</footer>

<script>
async function sendRequest() {
    var arg      = document.getElementById('tryInput').value.trim();
    var box      = document.getElementById('responseBox');
    var statusEl = document.getElementById('responseStatus');
    var bodyEl   = document.getElementById('responseBody');

    if (!arg) return;

    // PATH_INFO request — matches the real report URL structure
    var url = '125.php/jolokia/exec/com.sun.management:type=DiagnosticCommand/compilerDirectivesAdd/' + encodeURIComponent(arg).replace(/%2F/g, '/').replace(/%21/g, '!');

    try {
        var resp = await fetch(url);
        var text = await resp.text();

        // Try to pretty-print JSON
        try {
            var data = JSON.parse(text);
            text = JSON.stringify(data, null, 2);
        } catch(e) {}

        box.style.display = 'block';
        if (resp.status === 200) {
            statusEl.className = 'response-status-ok';
            statusEl.textContent = '200 OK';
        } else {
            statusEl.className = 'response-status-err';
            statusEl.textContent = resp.status + ' ERROR';
        }
        bodyEl.textContent = text;
    } catch(e) {
        box.style.display = 'block';
        statusEl.className = 'response-status-err';
        statusEl.textContent = 'Network Error';
        bodyEl.textContent = e.message;
    }
}

document.getElementById('tryInput').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') sendRequest();
});
</script>

</body>
</html>
