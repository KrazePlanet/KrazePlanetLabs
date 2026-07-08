<?php
// LFI Lab 127 — DoD FRIDA Data Portal download.php
// HackerOne Report #1639364 (Medium — U.S. Dept of Defense)
// Vulnerable parameter: filePathDownload (GET)
// Key: path traversal only works when a valid prefix precedes ../

// ── Path normalizer — processes ../ without OS-level escape ───────────────
function normalizePath($path) {
    $parts = explode('/', str_replace('\\', '/', $path));
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

// ── Download handler ───────────────────────────────────────────────────────
$filePathDownload = isset($_GET['filePathDownload']) ? $_GET['filePathDownload'] : null;

if ($filePathDownload !== null) {
    // Valid prefixes — server checks these but NOT what comes after
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
        header('Content-Type: text/plain; charset=utf-8');
        echo 'Access Denied: Invalid or unauthorized file path.';
        exit;
    }

    // BUG: ../ not stripped — traversal possible after prefix
    $fsRoot      = __DIR__ . '/lab127_fs';
    $normalized  = normalizePath($filePathDownload);
    $absPath     = $fsRoot . $normalized;

    if (!file_exists($absPath) || is_dir($absPath)) {
        http_response_code(404);
        header('Content-Type: text/plain; charset=utf-8');
        echo 'File not found.';
        exit;
    }

    $filename = basename($absPath);
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . addslashes($filename) . '"');
    header('Content-Length: ' . filesize($absPath));
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
<title>FRIDA Data Portal</title>
<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;background:#07111f;color:#c9cdd4;font-size:13px;display:flex;flex-direction:column;min-height:100vh;}

/* ── Top banner ──────────────────────────────────────────────────────────── */
.dod-banner{background:#0a1c35;border-bottom:1px solid #1a2f4a;padding:3px 20px;font-size:.6rem;color:#6a8aab;text-align:center;letter-spacing:.04em;text-transform:uppercase;}

/* ── Header ──────────────────────────────────────────────────────────────── */
.site-header{background:linear-gradient(180deg,#0d2040 0%,#0a1628 100%);border-bottom:3px solid #c8a84b;box-shadow:0 2px 8px rgba(0,0,0,.5);}
.header-inner{max-width:1200px;margin:0 auto;padding:12px 20px;display:flex;align-items:center;gap:14px;}
.dod-seal{width:52px;height:52px;background:radial-gradient(circle,#1a3060,#0a1628);border:2px solid #c8a84b;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.dod-seal svg{width:28px;height:28px;fill:#c8a84b;}
.header-text{flex:1;}
.header-agency{font-size:.6rem;font-weight:700;color:#c8a84b;letter-spacing:.12em;text-transform:uppercase;margin-bottom:2px;}
.header-title{font-size:1.05rem;font-weight:800;color:#e8edf3;letter-spacing:-.01em;}
.header-sub{font-size:.65rem;color:#6a8aab;margin-top:2px;}
.header-class{font-size:.6rem;background:#0a1628;border:1px solid #c8a84b;color:#c8a84b;padding:3px 10px;border-radius:2px;font-weight:700;letter-spacing:.1em;white-space:nowrap;}

/* ── Nav ─────────────────────────────────────────────────────────────────── */
.site-nav{background:#0a1628;border-bottom:1px solid #1a2f4a;}
.nav-inner{max-width:1200px;margin:0 auto;padding:0 20px;display:flex;gap:0;}
.nav-link{font-size:.72rem;font-weight:600;color:#6a8aab;padding:9px 14px;text-decoration:none;letter-spacing:.03em;border-bottom:2px solid transparent;transition:all .15s;}
.nav-link:hover{color:#c9cdd4;border-bottom-color:#4a6a8a;}
.nav-link.active{color:#c8a84b;border-bottom-color:#c8a84b;}

/* ── Content ─────────────────────────────────────────────────────────────── */
.content-wrap{max-width:1200px;margin:0 auto;padding:18px 20px;flex:1;display:grid;grid-template-columns:1fr 280px;gap:16px;}

/* ── Card ────────────────────────────────────────────────────────────────── */
.card{background:#0d1e33;border:1px solid #1a2f4a;border-radius:3px;overflow:hidden;margin-bottom:14px;}
.card-header{background:#0a1628;border-bottom:1px solid #1a2f4a;padding:9px 14px;display:flex;align-items:center;gap:8px;}
.card-header-icon{width:15px;height:15px;fill:none;stroke:#c8a84b;stroke-width:2;}
.card-title{font-size:.78rem;font-weight:700;color:#c9cdd4;letter-spacing:.02em;}
.card-sub{font-size:.62rem;color:#4a6a8a;margin-left:auto;}
.card-body{padding:14px;}

/* ── File table ──────────────────────────────────────────────────────────── */
.file-table{width:100%;border-collapse:collapse;}
.file-table th{background:#071526;font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;padding:7px 10px;text-align:left;color:#4a6a8a;border-bottom:1px solid #1a2f4a;}
.file-table td{padding:9px 10px;border-bottom:1px solid #0f1e30;font-size:.75rem;vertical-align:middle;}
.file-table tr:last-child td{border-bottom:none;}
.file-table tr:hover td{background:#0a1628;}
.file-icon{display:inline-flex;align-items:center;gap:6px;}
.file-icon svg{width:14px;height:14px;fill:#4a6a8a;}
.file-name{color:#a8c4e0;font-weight:600;}
.file-path{font-family:monospace;font-size:.65rem;color:#4a6a8a;}
.file-size{color:#4a6a8a;text-align:right;}
.file-date{color:#4a6a8a;}
.btn-download{background:#0a1c35;border:1px solid #2a4a6a;color:#6a9ac4;font-size:.68rem;font-weight:700;padding:4px 10px;border-radius:2px;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:4px;transition:all .15s;font-family:inherit;}
.btn-download:hover{background:#1a2f4a;color:#c8a84b;border-color:#c8a84b;}
.btn-download svg{width:11px;height:11px;fill:currentColor;}
.path-badge{background:#071526;border:1px solid #1a2f4a;border-radius:2px;padding:1px 5px;font-family:monospace;font-size:.62rem;color:#6a9ac4;}

/* ── Download form ───────────────────────────────────────────────────────── */
.dl-form-wrap{background:#071526;border:1px solid #1a2f4a;border-radius:3px;padding:13px;}
.dl-form-label{font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#4a6a8a;margin-bottom:6px;display:block;}
.dl-url-bar{background:#07111f;border:1px solid #1a2f4a;border-radius:3px;padding:7px 10px;font-family:monospace;font-size:.7rem;color:#aaa;margin-bottom:8px;word-break:break-all;line-height:1.5;}
.dl-url-bar .u-scheme{color:#6a9ac4;}
.dl-url-bar .u-host{color:#81c784;}
.dl-url-bar .u-path{color:#aaa;}
.dl-url-bar .u-param{color:#c8a84b;}
.dl-url-bar .u-val{color:#f48fb1;}
.dl-input-row{display:flex;gap:6px;}
.dl-input{flex:1;background:#07111f;border:1px solid #1a2f4a;border-radius:3px 0 0 3px;padding:6px 9px;font-family:monospace;font-size:.73rem;color:#c9cdd4;outline:none;}
.dl-input:focus{border-color:#c8a84b;box-shadow:0 0 0 2px rgba(200,168,75,.1);}
.dl-go{background:#c8a84b;color:#07111f;border:none;padding:6px 14px;font-size:.73rem;font-weight:800;border-radius:0 3px 3px 0;cursor:pointer;font-family:inherit;letter-spacing:.03em;}
.dl-go:hover{background:#e0c060;}
.dl-note{font-size:.63rem;color:#4a6a8a;margin-top:6px;line-height:1.5;}
.dl-note code{background:#07111f;border:1px solid #1a2f4a;padding:1px 4px;border-radius:2px;font-size:.62rem;color:#6a9ac4;}

/* ── Response viewer ─────────────────────────────────────────────────────── */
.resp-box{display:none;margin-top:10px;}
.resp-bar{background:#0a1628;padding:6px 10px;display:flex;align-items:center;gap:8px;font-size:.68rem;border-radius:3px 3px 0 0;}
.resp-200{background:#1b4d2e;color:#81c784;padding:2px 7px;border-radius:2px;font-weight:700;}
.resp-403{background:#4d1b1b;color:#ef9a9a;padding:2px 7px;border-radius:2px;font-weight:700;}
.resp-404{background:#3d2b00;color:#ffcc80;padding:2px 7px;border-radius:2px;font-weight:700;}
.resp-ct{color:#4a6a8a;font-size:.65rem;}
.resp-body{background:#050d18;border:1px solid #1a2f4a;border-top:none;border-radius:0 0 3px 3px;padding:10px;font-family:'Courier New',monospace;font-size:.7rem;color:#a8ff78;line-height:1.55;max-height:340px;overflow:auto;white-space:pre-wrap;word-break:break-all;}
.resp-body.error-body{color:#ef9a9a;}

/* ── Sidebar ─────────────────────────────────────────────────────────────── */
.sidebar-card{background:#0d1e33;border:1px solid #1a2f4a;border-radius:3px;overflow:hidden;margin-bottom:12px;}
.sb-header{font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;padding:7px 11px;border-bottom:1px solid #1a2f4a;color:#6a8aab;}
.sb-body{padding:10px 11px;font-size:.72rem;line-height:1.7;}
.info-row{display:flex;gap:6px;margin-bottom:3px;}
.info-lbl{color:#4a6a8a;min-width:72px;flex-shrink:0;font-size:.68rem;}
.info-val{font-weight:700;color:#c9cdd4;font-size:.68rem;}

/* ── Access log ──────────────────────────────────────────────────────────── */
.log-entry{font-family:monospace;font-size:.64rem;color:#4a6a8a;padding:3px 0;border-bottom:1px solid #0f1e30;line-height:1.4;}
.log-entry:last-child{border-bottom:none;}
.log-200{color:#4caf50;}
.log-403{color:#ef5350;}
.log-ip{color:#6a9ac4;}
.log-path{color:#aaa;word-break:break-all;}

/* ── Footer ──────────────────────────────────────────────────────────────── */
footer{background:#071526;border-top:3px solid #c8a84b;padding:10px 20px;font-size:.62rem;color:#2a4a6a;display:flex;justify-content:space-between;flex-wrap:wrap;gap:4px;flex-shrink:0;}
footer a{color:#2a4a6a;text-decoration:none;}
footer a:hover{color:#c8a84b;}
</style>
</head>
<body>

<div class="dod-banner">U.S. Department of Defense — Authorized Use Only — All Activity Monitored</div>

<header class="site-header">
  <div class="header-inner">
    <div class="dod-seal">
      <svg viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
    </div>
    <div class="header-text">
      <div class="header-agency">U.S. Department of Defense · Naval Research Laboratory</div>
      <div class="header-title">FRIDA Data Portal</div>
      <div class="header-sub">Field Research Integrated Data Archive — Measurement &amp; Calibration Division</div>
    </div>
    <div class="header-class">UNCLASSIFIED // FOUO</div>
  </div>
</header>

<nav class="site-nav">
  <div class="nav-inner">
    <a href="127.php" class="nav-link active">Home</a>
    <a href="#" class="nav-link">Data Products</a>
    <a href="#" class="nav-link">Reports</a>
    <a href="#" class="nav-link">Documentation</a>
    <a href="#" class="nav-link">About</a>
  </div>
</nav>

<div class="content-wrap">

  <!-- Main -->
  <div>

    <!-- File catalog -->
    <div class="card">
      <div class="card-header">
        <svg class="card-header-icon" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        <span class="card-title">Available Data Products</span>
        <span class="card-sub">5 files · 3 directories</span>
      </div>
      <div class="card-body" style="padding:0;">
        <table class="file-table">
          <thead>
            <tr>
              <th>Filename</th>
              <th>Directory</th>
              <th>Size</th>
              <th>Modified</th>
              <th style="text-align:right;">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <span class="file-icon">
                  <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/><polyline points="14 2 14 8 20 8"/></svg>
                  <span class="file-name">README.txt</span>
                </span>
              </td>
              <td><span class="path-badge">data_products/MISC/frida_cal/</span></td>
              <td class="file-size">1.2 KB</td>
              <td class="file-date">2022-07-05</td>
              <td style="text-align:right;">
                <a href="127.php?filePathDownload=data_products/MISC/frida_cal/README.txt" class="btn-download">
                  <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                  Download
                </a>
              </td>
            </tr>
            <tr>
              <td>
                <span class="file-icon">
                  <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/><polyline points="14 2 14 8 20 8"/></svg>
                  <span class="file-name">cal_2022_Q2.csv</span>
                </span>
              </td>
              <td><span class="path-badge">data_products/MISC/frida_cal/</span></td>
              <td class="file-size">2.4 KB</td>
              <td class="file-date">2022-07-05</td>
              <td style="text-align:right;">
                <a href="127.php?filePathDownload=data_products/MISC/frida_cal/cal_2022_Q2.csv" class="btn-download">
                  <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                  Download
                </a>
              </td>
            </tr>
            <tr>
              <td>
                <span class="file-icon">
                  <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/><polyline points="14 2 14 8 20 8"/></svg>
                  <span class="file-name">frida_report.txt</span>
                </span>
              </td>
              <td><span class="path-badge">data_products/MISC/frida_cal/</span></td>
              <td class="file-size">3.1 KB</td>
              <td class="file-date">2022-07-06</td>
              <td style="text-align:right;">
                <a href="127.php?filePathDownload=data_products/MISC/frida_cal/frida_report.txt" class="btn-download">
                  <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                  Download
                </a>
              </td>
            </tr>
            <tr>
              <td>
                <span class="file-icon">
                  <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/><polyline points="14 2 14 8 20 8"/></svg>
                  <span class="file-name">annual_2022.txt</span>
                </span>
              </td>
              <td><span class="path-badge">reports/</span></td>
              <td class="file-size">1.8 KB</td>
              <td class="file-date">2023-01-15</td>
              <td style="text-align:right;">
                <a href="127.php?filePathDownload=reports/annual_2022.txt" class="btn-download">
                  <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                  Download
                </a>
              </td>
            </tr>
            <tr>
              <td>
                <span class="file-icon">
                  <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/><polyline points="14 2 14 8 20 8"/></svg>
                  <span class="file-name">user_guide.txt</span>
                </span>
              </td>
              <td><span class="path-badge">docs/</span></td>
              <td class="file-size">1.5 KB</td>
              <td class="file-date">2022-03-10</td>
              <td style="text-align:right;">
                <a href="127.php?filePathDownload=docs/user_guide.txt" class="btn-download">
                  <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                  Download
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Manual download form -->
    <div class="card">
      <div class="card-header">
        <svg class="card-header-icon" viewBox="0 0 24 24"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
        <span class="card-title">download.php — Direct File Request</span>
        <span class="card-sub">GET parameter: filePathDownload</span>
      </div>
      <div class="card-body">
        <div class="dl-form-wrap">
          <label class="dl-form-label">filePathDownload</label>
          <div class="dl-url-bar" id="urlBar">
            <span class="u-scheme">https://</span><span class="u-host">████.mil</span><span class="u-path">/download.php?</span><span class="u-param">filePathDownload</span>=<span class="u-val" id="urlVal">data_products/MISC/frida_cal/README.txt</span>
          </div>
          <div class="dl-input-row">
            <input type="text" class="dl-input" id="dlInput" value="data_products/MISC/frida_cal/README.txt" placeholder="data_products/MISC/frida_cal/README.txt">
            <button class="dl-go" onclick="sendRequest()">Fetch →</button>
          </div>
          <div class="dl-note">
            Authorized prefixes: <code>data_products/</code> &nbsp;|&nbsp; <code>reports/</code> &nbsp;|&nbsp; <code>docs/</code><br>
            Paths not starting with a valid prefix return <code>403 Access Denied</code>.
          </div>

          <div class="resp-box" id="respBox">
            <div class="resp-bar">
              <span id="respStatus"></span>
              <span class="resp-ct" id="respCt"></span>
              <span style="margin-left:auto;font-size:.63rem;color:#2a4a6a;" id="respSize"></span>
            </div>
            <pre class="resp-body" id="respBody"></pre>
          </div>
        </div>
      </div>
    </div>

  </div><!-- end main -->

  <!-- Sidebar -->
  <div>

    <div class="sidebar-card">
      <div class="sb-header" style="background:#0a1628;">System Status</div>
      <div class="sb-body">
        <div class="info-row"><span class="info-lbl">Portal</span><span class="info-val" style="color:#4caf50;">Online</span></div>
        <div class="info-row"><span class="info-lbl">Version</span><span class="info-val">2.1.4</span></div>
        <div class="info-row"><span class="info-lbl">Host</span><span class="info-val">████.mil</span></div>
        <div class="info-row"><span class="info-lbl">Script</span><span class="info-val">/download.php</span></div>
        <div class="info-row"><span class="info-lbl">Auth</span><span class="info-val" style="color:#ef9a9a;">None (public)</span></div>
        <div class="info-row"><span class="info-lbl">Endpoint</span><span class="info-val">GET filePathDownload</span></div>
      </div>
    </div>

    <div class="sidebar-card">
      <div class="sb-header" style="background:#1a2f0a;">Access Log</div>
      <div class="sb-body" style="padding:8px 10px;">
        <div class="log-entry">
          <span class="log-ip">89.144.38.201</span> <span class="log-200">200</span><br>
          <span class="log-path">data_products/MISC/frida_cal/README.txt</span>
        </div>
        <div class="log-entry" style="margin-top:5px;">
          <span class="log-ip">89.144.38.201</span> <span class="log-200">200</span><br>
          <span class="log-path">data_products/MISC/frida_cal/cal_2022_Q2.csv</span>
        </div>
        <div class="log-entry" style="margin-top:5px;">
          <span class="log-ip">89.144.38.201</span> <span class="log-403">403</span><br>
          <span class="log-path">../../../../etc/passwd</span>
        </div>
        <div class="log-entry" style="margin-top:5px;">
          <span class="log-ip">89.144.38.201</span> <span class="log-200">200</span><br>
          <span class="log-path">data_products/MISC/frida_cal/../../../../../../../../etc/passwd</span>
        </div>
      </div>
    </div>

    <div class="sidebar-card">
      <div class="sb-header" style="background:#2a1a0a;">About FRIDA</div>
      <div class="sb-body" style="color:#6a8aab;line-height:1.7;">
        The Field Research Integrated Data Archive (FRIDA) stores calibration data and reports from DoD environmental monitoring stations across the continental United States. Access is governed by DoD Instruction 8500.01.
      </div>
    </div>

  </div>

</div><!-- .content-wrap -->

<footer>
  <span>FRIDA Data Portal v2.1.4 · Naval Research Laboratory · U.S. Dept of Defense</span>
  <span>Based on <a href="https://hackerone.com/reports/1639364" target="_blank" rel="noopener">HackerOne #1639364</a></span>
</footer>

<script>
var dlInput = document.getElementById('dlInput');
var urlVal  = document.getElementById('urlVal');

dlInput.addEventListener('input', function() {
    urlVal.textContent = dlInput.value;
});

async function sendRequest() {
    var path = dlInput.value.trim();
    if (!path) return;

    urlVal.textContent = path;

    var url = '127.php?filePathDownload=' + encodeURIComponent(path);

    var box      = document.getElementById('respBox');
    var statusEl = document.getElementById('respStatus');
    var ctEl     = document.getElementById('respCt');
    var bodyEl   = document.getElementById('respBody');
    var sizeEl   = document.getElementById('respSize');

    try {
        var resp = await fetch(url);
        var text = await resp.text();

        box.style.display = 'block';
        sizeEl.textContent = text.length + ' bytes';

        if (resp.status === 200) {
            statusEl.className   = 'resp-200';
            statusEl.textContent = 'HTTP 200 OK';
            ctEl.textContent     = 'application/octet-stream';
            bodyEl.className     = 'resp-body';
        } else if (resp.status === 403) {
            statusEl.className   = 'resp-403';
            statusEl.textContent = 'HTTP 403 Forbidden';
            ctEl.textContent     = 'text/plain';
            bodyEl.className     = 'resp-body error-body';
        } else {
            statusEl.className   = 'resp-404';
            statusEl.textContent = 'HTTP ' + resp.status;
            ctEl.textContent     = 'text/plain';
            bodyEl.className     = 'resp-body error-body';
        }
        bodyEl.textContent = text;
    } catch(e) {
        box.style.display    = 'block';
        statusEl.className   = 'resp-403';
        statusEl.textContent = 'Network Error';
        bodyEl.className     = 'resp-body error-body';
        bodyEl.textContent   = e.message;
    }
}

dlInput.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') sendRequest();
});
</script>

</body>
</html>
