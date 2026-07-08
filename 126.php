<?php
// LFI Lab 126 — Grafana Path Traversal in Plugin Static Files
// HackerOne Report #1419213 (Medium — MariaDB / grafana.mariadb.org)
// CVE context: Grafana 8.x path traversal (CVE-2021-43798)
// Vulnerable endpoint: GET /public/plugins/{plugin}/?file={path}

// ── LFI handler ────────────────────────────────────────────────────────────
$plugin = isset($_GET['plugin']) ? $_GET['plugin'] : null;
$file   = isset($_GET['file'])   ? $_GET['file']   : null;

// Normalize a path with ../ segments, bounded to fake FS root (never escapes /)
function normalizePath($path) {
    $parts = explode('/', str_replace('\\', '/', $path));
    $stack = [];
    foreach ($parts as $part) {
        if ($part === '' || $part === '.') continue;
        if ($part === '..') {
            if (!empty($stack)) array_pop($stack);
            // If stack is already empty, stay at root (can't go above /)
        } else {
            $stack[] = $part;
        }
    }
    return '/' . implode('/', $stack);
}

if ($plugin !== null && $file !== null) {
    $fsRoot = __DIR__ . '/lab126_fs';

    // Resolve ../ traversal via normalization — sandboxed to fake FS
    $normalized = normalizePath($file);
    $absPath    = $fsRoot . $normalized;

    if (!file_exists($absPath) || is_dir($absPath)) {
        http_response_code(404);
        header('Content-Type: text/plain; charset=utf-8');
        echo '404 page not found';
        exit;
    }

    http_response_code(200);
    header('Content-Type: text/plain; charset=utf-8');
    header('X-Content-Type-Options: nosniff');
    readfile($absPath);
    exit;
}

// ── Landing page ───────────────────────────────────────────────────────────
function esc($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

$plugins = ['alertlist','graph','table','text','stat','gauge','bargauge','dashlist','news','piechart'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Grafana - grafana.mariadb.org</title>
<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;background:#111217;color:#d8d9da;font-size:13px;display:flex;flex-direction:column;min-height:100vh;}

/* ── Top nav ──────────────────────────────────────────────────────────────── */
.topnav{background:#161719;border-bottom:1px solid #22252b;height:40px;display:flex;align-items:center;padding:0 12px;gap:0;flex-shrink:0;z-index:100;}
.topnav-logo{display:flex;align-items:center;gap:8px;padding:0 12px 0 0;border-right:1px solid #22252b;margin-right:8px;}
.topnav-logo svg{width:24px;height:24px;}
.topnav-logo-text{font-size:.85rem;font-weight:700;color:#ff7e00;letter-spacing:.01em;}
.topnav-breadcrumb{font-size:.78rem;color:#aaa;display:flex;align-items:center;gap:6px;}
.topnav-breadcrumb span{color:#d8d9da;}
.topnav-right{margin-left:auto;display:flex;align-items:center;gap:2px;}
.topnav-btn{background:none;border:none;color:#aaa;cursor:pointer;padding:6px 8px;border-radius:3px;font-size:.75rem;display:flex;align-items:center;gap:5px;transition:background .15s;}
.topnav-btn:hover{background:#22252b;color:#d8d9da;}
.topnav-btn svg{width:14px;height:14px;fill:currentColor;}
.user-chip{background:#22252b;border-radius:2px;padding:3px 10px;font-size:.72rem;color:#d8d9da;cursor:pointer;display:flex;align-items:center;gap:5px;}
.user-chip:hover{background:#2c2f36;}

/* ── Layout ──────────────────────────────────────────────────────────────── */
.layout{display:flex;flex:1;overflow:hidden;}
.sidebar{width:55px;background:#161719;border-right:1px solid #22252b;display:flex;flex-direction:column;align-items:center;padding:8px 0;gap:2px;flex-shrink:0;}
.sidebar-icon{width:40px;height:40px;display:flex;align-items:center;justify-content:center;border-radius:3px;cursor:pointer;color:#7b7d82;transition:all .15s;}
.sidebar-icon:hover{background:#22252b;color:#d8d9da;}
.sidebar-icon.active{background:#22252b;color:#ff7e00;}
.sidebar-icon svg{width:18px;height:18px;fill:currentColor;}
.sidebar-divider{width:28px;border-top:1px solid #22252b;margin:4px 0;}
.main{flex:1;overflow-y:auto;padding:16px;}

/* ── Dashboard header ─────────────────────────────────────────────────────── */
.dash-header{display:flex;align-items:center;gap:10px;margin-bottom:16px;flex-wrap:wrap;}
.dash-title{font-size:1.15rem;font-weight:700;color:#d8d9da;}
.dash-star{color:#7b7d82;cursor:pointer;font-size:1rem;}
.dash-star:hover{color:#ff7e00;}
.dash-controls{margin-left:auto;display:flex;align-items:center;gap:6px;}
.time-picker{background:#22252b;border:1px solid #2c2f36;border-radius:3px;padding:4px 10px;font-size:.72rem;color:#d8d9da;cursor:pointer;display:flex;align-items:center;gap:5px;}
.time-picker svg{width:12px;height:12px;fill:#aaa;}
.refresh-btn{background:#22252b;border:1px solid #2c2f36;border-radius:3px;padding:4px 8px;font-size:.72rem;color:#d8d9da;cursor:pointer;}
.refresh-btn:hover{background:#2c2f36;}

/* ── Panel grid ───────────────────────────────────────────────────────────── */
.panel-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;}
.panel-grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-bottom:10px;}
.panel-full{margin-bottom:10px;}
.panel{background:#1c1f26;border:1px solid #22252b;border-radius:3px;overflow:hidden;}
.panel-header{padding:7px 12px 0;display:flex;align-items:center;gap:6px;}
.panel-title{font-size:.76rem;color:#aaa;font-weight:500;flex:1;}
.panel-menu{color:#555;cursor:pointer;font-size:.8rem;padding:2px 4px;border-radius:2px;}
.panel-menu:hover{color:#aaa;background:#22252b;}
.panel-body{padding:6px 12px 12px;}

/* ── Sparkline ───────────────────────────────────────────────────────────── */
.sparkline-wrap{height:70px;position:relative;overflow:hidden;}
.sparkline-wrap svg{width:100%;height:100%;}
.metric-big{font-size:1.6rem;font-weight:700;color:#d8d9da;text-align:center;padding:14px 0 4px;}
.metric-label{font-size:.65rem;color:#aaa;text-align:center;}
.metric-unit{font-size:.75rem;color:#ff7e00;font-weight:700;}
.stat-row{display:flex;justify-content:space-between;align-items:center;padding:4px 0;border-bottom:1px solid #22252b;font-size:.72rem;}
.stat-row:last-child{border-bottom:none;}
.stat-lbl{color:#aaa;}
.stat-val{font-weight:700;color:#d8d9da;}
.stat-ok{color:#4caf50;}
.stat-warn{color:#ff9800;}
.stat-err{color:#f44336;}

/* ── Gauge bar ───────────────────────────────────────────────────────────── */
.gauge-bar{height:6px;background:#22252b;border-radius:3px;margin:8px 0 3px;overflow:hidden;}
.gauge-fill{height:100%;border-radius:3px;background:linear-gradient(90deg,#4caf50,#ff9800);}

/* ── Plugin explorer panel ────────────────────────────────────────────────── */
.explorer-panel{background:#1c1f26;border:1px solid #2c2f36;border-radius:3px;overflow:hidden;}
.explorer-header{background:#1f2229;padding:9px 14px;border-bottom:1px solid #22252b;display:flex;align-items:center;gap:8px;}
.explorer-title{font-size:.8rem;font-weight:700;color:#d8d9da;}
.explorer-badge{font-size:.62rem;background:#2c2f36;color:#aaa;padding:1px 6px;border-radius:2px;}
.explorer-body{padding:14px;}
.field-row{display:flex;gap:8px;align-items:flex-end;flex-wrap:wrap;margin-bottom:10px;}
.field-group{display:flex;flex-direction:column;gap:3px;}
.field-label{font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#7b7d82;}
.field-input{background:#111217;border:1px solid #2c2f36;border-radius:3px;padding:6px 9px;font-size:.76rem;color:#d8d9da;outline:none;font-family:inherit;}
.field-input:focus{border-color:#ff7e00;box-shadow:0 0 0 2px rgba(255,126,0,.15);}
.field-select{background:#111217;border:1px solid #2c2f36;border-radius:3px;padding:6px 9px;font-size:.76rem;color:#d8d9da;outline:none;cursor:pointer;min-width:130px;}
.field-select:focus{border-color:#ff7e00;}
.exec-btn{background:#ff7e00;color:#fff;border:none;padding:7px 18px;font-size:.76rem;font-weight:700;border-radius:3px;cursor:pointer;font-family:inherit;white-space:nowrap;align-self:flex-end;}
.exec-btn:hover{background:#e06e00;}
.url-preview{background:#111217;border:1px solid #22252b;border-radius:3px;padding:8px 10px;font-family:monospace;font-size:.7rem;color:#aaa;margin-bottom:10px;word-break:break-all;line-height:1.6;}
.url-preview .p-scheme{color:#4fc3f7;}
.url-preview .p-host{color:#81c784;}
.url-preview .p-path{color:#aaa;}
.url-preview .p-plugin{color:#ffb74d;}
.url-preview .p-file{color:#f48fb1;}

/* ── Response viewer ─────────────────────────────────────────────────────── */
.response-wrap{display:none;margin-top:4px;}
.response-top{background:#22252b;padding:6px 10px;display:flex;align-items:center;gap:8px;font-size:.68rem;border-radius:3px 3px 0 0;}
.response-200{background:#1b5e20;color:#a5d6a7;padding:1px 7px;border-radius:2px;font-weight:700;}
.response-404{background:#b71c1c;color:#ef9a9a;padding:1px 7px;border-radius:2px;font-weight:700;}
.response-ct{color:#7b7d82;}
.response-body{background:#111217;border:1px solid #22252b;border-top:none;border-radius:0 0 3px 3px;padding:10px;font-family:'Courier New',monospace;font-size:.7rem;color:#a8ff78;line-height:1.55;max-height:360px;overflow:auto;white-space:pre-wrap;word-break:break-all;}

/* ── URL structure box ───────────────────────────────────────────────────── */
.url-struct{background:#111217;border:1px solid #22252b;border-radius:3px;padding:11px 13px;font-family:monospace;font-size:.7rem;line-height:2;word-break:break-all;margin-bottom:10px;}
.us-comment{color:#555;font-style:italic;}
.us-host{color:#81c784;}
.us-base{color:#aaa;}
.us-plugin{color:#ffb74d;}
.us-sep{color:#555;}
.us-trav{color:#f48fb1;font-weight:700;}
.us-file{color:#4fc3f7;}
.section-label{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#7b7d82;margin-bottom:6px;}

/* ── Footer ──────────────────────────────────────────────────────────────── */
footer{background:#161719;border-top:1px solid #22252b;padding:8px 16px;font-size:.65rem;color:#555;display:flex;justify-content:space-between;flex-wrap:wrap;gap:4px;flex-shrink:0;}
footer a{color:#555;text-decoration:none;}
footer a:hover{color:#ff7e00;}
</style>
</head>
<body>

<!-- Top nav -->
<nav class="topnav">
  <div class="topnav-logo">
    <svg viewBox="0 0 24 24" fill="none">
      <circle cx="12" cy="12" r="10" fill="#ff7e00" opacity=".18"/>
      <path d="M12 4C7.58 4 4 7.58 4 12s3.58 8 8 8 8-3.58 8-8-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6s2.69-6 6-6 6 2.69 6 6-2.69 6-6 6z" fill="#ff7e00"/>
      <circle cx="12" cy="12" r="3" fill="#ff7e00"/>
    </svg>
    <span class="topnav-logo-text">Grafana</span>
  </div>
  <div class="topnav-breadcrumb">
    <svg viewBox="0 0 24 24" style="width:14px;height:14px;fill:#555;"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
    <span style="color:#555;">/</span>
    <span>MariaDB Server Metrics</span>
  </div>
  <div class="topnav-right">
    <button class="topnav-btn">
      <svg viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27A6.47 6.47 0 0016 9.5 6.5 6.5 0 109.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
    </button>
    <button class="topnav-btn">
      <svg viewBox="0 0 24 24"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
    </button>
    <div class="user-chip">
      <svg viewBox="0 0 24 24" style="width:12px;height:12px;fill:currentColor;"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
      admin
      <svg viewBox="0 0 24 24" style="width:10px;height:10px;fill:currentColor;"><path d="M7 10l5 5 5-5z"/></svg>
    </div>
  </div>
</nav>

<div class="layout">

  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="sidebar-icon active" title="Dashboards">
      <svg viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
    </div>
    <div class="sidebar-icon" title="Explore">
      <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
    </div>
    <div class="sidebar-icon" title="Alerting">
      <svg viewBox="0 0 24 24"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
    </div>
    <div class="sidebar-divider"></div>
    <div class="sidebar-icon" title="Configuration">
      <svg viewBox="0 0 24 24"><path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/></svg>
    </div>
    <div class="sidebar-icon" title="Server Admin">
      <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
    </div>
  </aside>

  <!-- Main content -->
  <main class="main">

    <!-- Dashboard header -->
    <div class="dash-header">
      <span class="dash-star">☆</span>
      <span class="dash-title">MariaDB Server Metrics</span>
      <span style="font-size:.7rem;color:#555;">grafana.mariadb.org</span>
      <div class="dash-controls">
        <div class="time-picker">
          <svg viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67V7z"/></svg>
          Last 6 hours
        </div>
        <button class="refresh-btn">⟳ Refresh</button>
      </div>
    </div>

    <!-- Stat row -->
    <div class="panel-grid-3">
      <div class="panel">
        <div class="panel-header"><span class="panel-title">Queries / sec</span><span class="panel-menu">⋮</span></div>
        <div class="panel-body">
          <div class="metric-big">2,847 <span class="metric-unit">q/s</span></div>
          <div class="metric-label">avg last 6h</div>
          <div class="sparkline-wrap" style="margin-top:6px;">
            <svg viewBox="0 0 200 50" preserveAspectRatio="none">
              <polyline fill="none" stroke="#ff7e00" stroke-width="1.5"
                points="0,35 20,28 40,32 60,18 80,22 100,15 120,20 140,10 160,14 180,8 200,12"/>
              <polyline fill="rgba(255,126,0,.12)" stroke="none"
                points="0,35 20,28 40,32 60,18 80,22 100,15 120,20 140,10 160,14 180,8 200,12 200,50 0,50"/>
            </svg>
          </div>
        </div>
      </div>
      <div class="panel">
        <div class="panel-header"><span class="panel-title">Active Connections</span><span class="panel-menu">⋮</span></div>
        <div class="panel-body">
          <div class="metric-big">43 <span class="metric-unit">/ 151</span></div>
          <div class="metric-label">max connections</div>
          <div class="gauge-bar"><div class="gauge-fill" style="width:28%;"></div></div>
          <div style="font-size:.65rem;color:#4caf50;">28% — Healthy</div>
        </div>
      </div>
      <div class="panel">
        <div class="panel-header"><span class="panel-title">InnoDB Buffer Hit Rate</span><span class="panel-menu">⋮</span></div>
        <div class="panel-body">
          <div class="metric-big" style="color:#4caf50;">99.2<span class="metric-unit">%</span></div>
          <div class="metric-label">buffer pool efficiency</div>
          <div class="gauge-bar"><div class="gauge-fill" style="width:99%;background:linear-gradient(90deg,#4caf50,#66bb6a);"></div></div>
        </div>
      </div>
    </div>

    <!-- Second row -->
    <div class="panel-grid">
      <div class="panel">
        <div class="panel-header"><span class="panel-title">Replication Lag</span><span class="panel-menu">⋮</span></div>
        <div class="panel-body">
          <div class="stat-row"><span class="stat-lbl">Primary</span><span class="stat-val stat-ok">0 ms</span></div>
          <div class="stat-row"><span class="stat-lbl">Replica 1</span><span class="stat-val stat-ok">12 ms</span></div>
          <div class="stat-row"><span class="stat-lbl">Replica 2</span><span class="stat-val stat-warn">84 ms</span></div>
          <div class="stat-row"><span class="stat-lbl">Status</span><span class="stat-val stat-ok">Running</span></div>
        </div>
      </div>
      <div class="panel">
        <div class="panel-header"><span class="panel-title">Slow Queries (last 1h)</span><span class="panel-menu">⋮</span></div>
        <div class="panel-body">
          <div class="sparkline-wrap">
            <svg viewBox="0 0 200 60" preserveAspectRatio="none">
              <rect x="0" y="48" width="18" height="12" fill="#ff7e00" opacity=".7"/>
              <rect x="22" y="42" width="18" height="18" fill="#ff7e00" opacity=".7"/>
              <rect x="44" y="50" width="18" height="10" fill="#ff7e00" opacity=".7"/>
              <rect x="66" y="36" width="18" height="24" fill="#ff7e00" opacity=".7"/>
              <rect x="88" y="44" width="18" height="16" fill="#ff7e00" opacity=".7"/>
              <rect x="110" y="52" width="18" height="8" fill="#ff7e00" opacity=".7"/>
              <rect x="132" y="38" width="18" height="22" fill="#ff7e00" opacity=".7"/>
              <rect x="154" y="46" width="18" height="14" fill="#ff7e00" opacity=".7"/>
              <rect x="176" y="30" width="18" height="30" fill="#ff9800" opacity=".9"/>
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Plugin File Explorer -->
    <div class="explorer-panel">
      <div class="explorer-header">
        <svg viewBox="0 0 24 24" style="width:15px;height:15px;fill:#ff7e00;"><path d="M9.4 16.6L4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0l4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z"/></svg>
        <span class="explorer-title">Plugin Static File Endpoint</span>
        <span class="explorer-badge">/public/plugins/{plugin}/{file}</span>
      </div>
      <div class="explorer-body">

        <div class="section-label" style="margin-bottom:8px;">Endpoint Structure</div>
        <div class="url-struct">
<span class="us-comment">// Grafana serves static plugin assets — no authentication required</span>
<span class="us-host">https://grafana.mariadb.org</span><span class="us-base">/public/plugins/</span><span class="us-plugin">{plugin-name}</span><span class="us-sep">/</span><span class="us-file">{file-path}</span>

<span class="us-comment">// File path is NOT sanitized — directory traversal works:</span>
<span class="us-host">https://grafana.mariadb.org</span><span class="us-base">/public/plugins/</span><span class="us-plugin">alertlist</span><span class="us-sep">/</span><span class="us-trav">../../../../../../../../../../../../../../../../../../../</span><span class="us-file">etc/passwd</span>
        </div>

        <div class="section-label">Send Request</div>
        <div class="field-row">
          <div class="field-group">
            <label class="field-label">Plugin Name</label>
            <select class="field-select" id="pluginSel">
              <?php foreach($plugins as $p): ?>
              <option value="<?=esc($p)?>"><?=esc($p)?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="field-group" style="flex:1;min-width:200px;">
            <label class="field-label">File Path</label>
            <input type="text" class="field-input" id="filePath" value="../../../../../../../../../../etc/passwd" placeholder="../../etc/passwd" style="width:100%;">
          </div>
          <button class="exec-btn" onclick="sendRequest()">Execute →</button>
        </div>

        <div class="url-preview" id="urlPreview">
          <span class="p-scheme">https://</span><span class="p-host">grafana.mariadb.org</span><span class="p-path">/public/plugins/</span><span class="p-plugin" id="prevPlugin">alertlist</span><span class="p-path">/</span><span class="p-file" id="prevFile">../../../../../../../../../../etc/passwd</span>
        </div>

        <div class="response-wrap" id="respWrap">
          <div class="response-top">
            <span id="respStatus"></span>
            <span class="response-ct">Content-Type: text/plain</span>
            <span style="margin-left:auto;font-size:.65rem;color:#555;" id="respSize"></span>
          </div>
          <pre class="response-body" id="respBody"></pre>
        </div>
      </div>
    </div>

  </main>
</div><!-- .layout -->

<footer>
  <span>Grafana v8.2.2 · grafana.mariadb.org · MariaDB Foundation</span>
  <span>Based on <a href="https://hackerone.com/reports/1419213" target="_blank" rel="noopener">HackerOne #1419213</a></span>
</footer>

<script>
var pluginSel = document.getElementById('pluginSel');
var filePath  = document.getElementById('filePath');
var prevPlugin = document.getElementById('prevPlugin');
var prevFile   = document.getElementById('prevFile');

function updatePreview() {
    prevPlugin.textContent = pluginSel.value || 'alertlist';
    prevFile.textContent   = filePath.value  || '';
}

pluginSel.addEventListener('change', updatePreview);
filePath.addEventListener('input',  updatePreview);

async function sendRequest() {
    var plugin = pluginSel.value;
    var file   = filePath.value.trim();
    if (!file) return;

    updatePreview();

    var url = '126.php?plugin=' + encodeURIComponent(plugin) + '&file=' + encodeURIComponent(file);

    try {
        var resp = await fetch(url);
        var text = await resp.text();

        var wrap     = document.getElementById('respWrap');
        var statusEl = document.getElementById('respStatus');
        var bodyEl   = document.getElementById('respBody');
        var sizeEl   = document.getElementById('respSize');

        wrap.style.display = 'block';
        bodyEl.textContent = text;
        sizeEl.textContent = text.length + ' bytes';

        if (resp.status === 200) {
            statusEl.className = 'response-200';
            statusEl.textContent = 'HTTP 200 OK';
        } else {
            statusEl.className = 'response-404';
            statusEl.textContent = 'HTTP ' + resp.status;
        }
    } catch(e) {
        document.getElementById('respWrap').style.display = 'block';
        document.getElementById('respStatus').className = 'response-404';
        document.getElementById('respStatus').textContent = 'Error';
        document.getElementById('respBody').textContent = e.message;
    }
}

filePath.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') sendRequest();
});
</script>

</body>
</html>
