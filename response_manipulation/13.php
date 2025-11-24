<?php
// Lab 13: Session Status Manipulation
// Vulnerability: Session status manipulation via response manipulation

session_start();

$message = '';
$response_data = [];

// Simulate session status manipulation via response manipulation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'check_session') {
        $user_id = $_POST['user_id'] ?? '';
        $session_id = $_POST['session_id'] ?? '';
        
        if (!empty($user_id) && !empty($session_id)) {
            // Simulate session check
            $is_valid = ($session_id === 'valid_session');
            
            $response_data = [
                'user_id' => $user_id,
                'session_id' => $session_id,
                'is_valid' => $is_valid,
                'session_valid' => $is_valid,
                'session_status' => $is_valid ? 'active' : 'expired',
                'status' => $is_valid ? 'success' : 'error',
                'message' => $is_valid ? 'Session is valid' : 'Session is invalid',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the session response.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide user ID and session ID.</div>';
        }
    } elseif ($action === 'check_authentication') {
        $user_id = $_POST['user_id'] ?? '';
        $token = $_POST['token'] ?? '';
        
        if (!empty($user_id) && !empty($token)) {
            // Simulate authentication check
            $is_authenticated = ($token === 'valid_token');
            
            $response_data = [
                'user_id' => $user_id,
                'token' => $token,
                'is_authenticated' => $is_authenticated,
                'auth_status' => $is_authenticated ? 'authenticated' : 'unauthenticated',
                'status' => $is_authenticated ? 'success' : 'error',
                'message' => $is_authenticated ? 'User is authenticated' : 'User is not authenticated',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the authentication response.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide user ID and token.</div>';
        }
    } elseif ($action === 'check_authorization') {
        $user_id = $_POST['user_id'] ?? '';
        $resource = $_POST['resource'] ?? '';
        
        if (!empty($user_id) && !empty($resource)) {
            // Simulate authorization check
            $is_authorized = ($user_id === 'admin');
            
            $response_data = [
                'user_id' => $user_id,
                'resource' => $resource,
                'is_authorized' => $is_authorized,
                'authorization_status' => $is_authorized ? 'authorized' : 'unauthorized',
                'status' => $is_authorized ? 'success' : 'error',
                'message' => $is_authorized ? 'User is authorized' : 'User is not authorized',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the authorization response.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide user ID and resource.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 13: Session Status Manipulation - Response Manipulation Labs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-dark: #1a1f36;
            --accent-green: #48bb78;
            --accent-blue: #4299e1;
            --accent-orange: #ed8936;
            --accent-red: #f56565;
        }
        
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #e2e8f0;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--accent-green) !important;
        }

        .nav-link {
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: var(--accent-green) !important;
        }

        .hero-section {
            background: linear-gradient(rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 0.9)), 
                        url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="%231e293b"/><path d="M0 0L100 100M100 0L0 100" stroke="%23374151" stroke-width="1"/></svg>');
            padding: 2rem 0;
            border-bottom: 1px solid #2d3748;
            margin-bottom: 2rem;
        }

        .hero-title {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(90deg, #48bb78, #4299e1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        .hero-subtitle {
            font-size: 1rem;
            color: #cbd5e0;
        }

        .section-title {
            margin-top: 30px;
            margin-bottom: 20px;
            font-weight: 700;
            font-size: 1.5rem;
            position: relative;
            padding-bottom: 10px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-green), var(--accent-blue));
            border-radius: 2px;
        }

        .card {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 12px;
            border: 1px solid #334155;
            color: #e2e8f0;
        }

        .card-header {
            background: rgba(15, 23, 42, 0.5);
            border-bottom: 1px solid #334155;
            font-weight: 600;
            padding: 1rem 1.5rem;
        }

        .form-control, .form-select {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid #334155;
            color: #e2e8f0;
            padding: 0.75rem 1rem;
        }

        .form-control:focus, .form-select:focus {
            background: rgba(30, 41, 59, 0.9);
            border-color: var(--accent-green);
            box-shadow: 0 0 0 0.2rem rgba(72, 187, 120, 0.25);
            color: #e2e8f0;
        }

        .form-label {
            font-weight: 500;
            color: #cbd5e0;
        }

        .btn-primary {
            background: linear-gradient(90deg, var(--accent-green), var(--accent-blue));
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(72, 187, 120, 0.3);
        }

        .vulnerability-info {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 12px;
            border: 1px solid #334155;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid var(--accent-orange);
        }

        .payload-examples {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 12px;
            border: 1px solid #334155;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid var(--accent-blue);
        }

        .danger-zone {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 12px;
            border: 1px solid #334155;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid var(--accent-red);
        }

        pre {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            color: #e2e8f0;
            border: 1px solid #334155;
            overflow-x: auto;
        }

        .lab-info {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 12px;
            border: 1px solid #334155;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .lab-badge {
            background: linear-gradient(90deg, var(--accent-green), var(--accent-blue));
            color: #1a202c;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .result-display {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border-left: 4px solid var(--accent-green);
        }

        .test-urls {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--accent-blue);
        }

        .input-info {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
        }

        .sensitive-data {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
            border-left: 4px solid var(--accent-red);
        }

        .code-block {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
            border-left: 4px solid var(--accent-blue);
            font-family: 'Courier New', monospace;
            white-space: pre-wrap;
        }

        .auth-warning {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
            border-left: 4px solid var(--accent-orange);
        }

        .vulnerable-form {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border: 1px solid #334155;
        }

        .response-display {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border: 1px solid #334155;
        }

        .response-item {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
            border: 1px solid #334155;
        }

        .session-rules {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }

        .rule-card {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            border: 1px solid #334155;
        }

        .rule-title {
            color: var(--accent-green);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .rule-demo {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 4px;
            padding: 0.5rem;
            margin: 0.5rem 0;
            font-family: 'Courier New', monospace;
            font-size: 12px;
        }

        .status-invalid {
            color: var(--accent-red);
            font-weight: bold;
        }

        .status-valid {
            color: var(--accent-green);
            font-weight: bold;
        }

        .status-unauthenticated {
            color: var(--accent-red);
            font-weight: bold;
        }

        .status-authenticated {
            color: var(--accent-green);
            font-weight: bold;
        }

        .status-unauthorized {
            color: var(--accent-red);
            font-weight: bold;
        }

        .status-authorized {
            color: var(--accent-green);
            font-weight: bold;
        }

        .status-error {
            color: var(--accent-red);
            font-weight: bold;
        }

        .status-success {
            color: var(--accent-green);
            font-weight: bold;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark sticky-top" style="background-color: var(--primary-dark);">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-arrow-left me-2"></i>Back to Response Manipulation Labs
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="../about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="../contact">Contact Us</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="hero-section">
        <div class="container">
            <h1 class="hero-title">Lab 13: Session Status Manipulation</h1>
            <p class="hero-subtitle">Session status manipulation via response manipulation vulnerabilities</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates session status manipulation vulnerabilities where attackers can use Burp Suite to modify session responses and bypass session controls.</p>
            <p><strong>Objective:</strong> Understand how session status manipulation attacks work and how to exploit them using Burp Suite.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-person-check me-2"></i>Session Status System
                    </div>
                    <div class="card-body">
                        <div class="vulnerable-form">
                            <h5>Check Session</h5>
                            <p>Test session status with response manipulation:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_session">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">User ID</label>
                                    <input type="text" class="form-control" id="user_id" name="user_id" placeholder="user123" required>
                                </div>
                                <div class="mb-3">
                                    <label for="session_id" class="form-label">Session ID</label>
                                    <input type="text" class="form-control" id="session_id" name="session_id" placeholder="invalid_session" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check Session</button>
                            </form>
                            
                            <hr>
                            
                            <h5>Check Authentication</h5>
                            <p>Test authentication status:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_authentication">
                                <div class="mb-3">
                                    <label for="user_id2" class="form-label">User ID</label>
                                    <input type="text" class="form-control" id="user_id2" name="user_id" placeholder="user123" required>
                                </div>
                                <div class="mb-3">
                                    <label for="token" class="form-label">Token</label>
                                    <input type="text" class="form-control" id="token" name="token" placeholder="invalid_token" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check Authentication</button>
                            </form>
                            
                            <hr>
                            
                            <h5>Check Authorization</h5>
                            <p>Test authorization status:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_authorization">
                                <div class="mb-3">
                                    <label for="user_id3" class="form-label">User ID</label>
                                    <input type="text" class="form-control" id="user_id3" name="user_id" placeholder="user123" required>
                                </div>
                                <div class="mb-3">
                                    <label for="resource" class="form-label">Resource</label>
                                    <input type="text" class="form-control" id="resource" name="resource" placeholder="admin_panel" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check Authorization</button>
                            </form>
                            
                            <?php echo $message; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-exclamation me-2"></i>Session Status Manipulation Tester
                    </div>
                    <div class="card-body">
                        <div class="auth-warning">
                            <h5>⚠️ Session Status Manipulation Warning</h5>
                            <p>This lab demonstrates session status manipulation vulnerabilities:</p>
                            <ul>
                                <li><code>Session Bypass</code> - Bypass session validation</li>
                                <li><code>Authentication Bypass</code> - Bypass authentication checks</li>
                                <li><code>Authorization Bypass</code> - Bypass authorization checks</li>
                                <li><code>Status Manipulation</code> - Manipulate session status</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Burp Suite Rules</h5>
                            <p>Use these Burp Suite Match and Replace rules:</p>
                            <ul>
                                <li><code>"is_valid":false</code> → <code>"is_valid":true</code></li>
                                <li><code>"is_authenticated":false</code> → <code>"is_authenticated":true</code></li>
                                <li><code>"is_authorized":false</code> → <code>"is_authorized":true</code></li>
                                <li><code>"session_status":"expired"</code> → <code>"session_status":"active"</code></li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testSessionBypass()" class="btn btn-primary">Test Session Bypass</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($response_data)): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-code-square me-2"></i>Generated Response
                    </div>
                    <div class="card-body">
                        <div class="response-display">
                            <h5>Server Response (JSON):</h5>
                            <pre><?php echo json_encode($response_data, JSON_PRETTY_PRINT); ?></pre>
                            
                            <h5>Response Analysis:</h5>
                            <div class="response-item">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <strong>Is Valid:</strong> <span class="status-<?php echo isset($response_data['is_valid']) && $response_data['is_valid'] ? 'valid' : 'invalid'; ?>"><?php echo isset($response_data['is_valid']) ? ($response_data['is_valid'] ? 'true' : 'false') : 'N/A'; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Is Authenticated:</strong> <span class="status-<?php echo isset($response_data['is_authenticated']) && $response_data['is_authenticated'] ? 'authenticated' : 'unauthenticated'; ?>"><?php echo isset($response_data['is_authenticated']) ? ($response_data['is_authenticated'] ? 'true' : 'false') : 'N/A'; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Is Authorized:</strong> <span class="status-<?php echo isset($response_data['is_authorized']) && $response_data['is_authorized'] ? 'authorized' : 'unauthorized'; ?>"><?php echo isset($response_data['is_authorized']) ? ($response_data['is_authorized'] ? 'true' : 'false') : 'N/A'; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Status:</strong> <span class="status-<?php echo isset($response_data['status']) && $response_data['status'] === 'success' ? 'success' : 'error'; ?>"><?php echo isset($response_data['status']) ? $response_data['status'] : 'N/A'; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-code-square me-2"></i>Session Status Manipulation Rules
                    </div>
                    <div class="card-body">
                        <div class="session-rules">
                            <div class="rule-card">
                                <div class="rule-title">Session Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"is_valid\":false",
  "string_replace": "\"is_valid\":true"
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Authentication Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"is_authenticated\":false",
  "string_replace": "\"is_authenticated\":true"
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Authorization Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"is_authorized\":false",
  "string_replace": "\"is_authorized\":true"
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Session Status Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"session_status\":\"expired\"",
  "string_replace": "\"session_status\":\"active\""
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Auth Status Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"auth_status\":\"unauthenticated\"",
  "string_replace": "\"auth_status\":\"authenticated\""
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Authorization Status Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"authorization_status\":\"unauthorized\"",
  "string_replace": "\"authorization_status\":\"authorized\""
}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="vulnerability-info">
                    <h5><i class="bi bi-bug me-2"></i>Vulnerability Details</h5>
                    <ul>
                        <li><strong>Type:</strong> Session Status Manipulation</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Method:</strong> Burp Suite Match and Replace</li>
                        <li><strong>Issue:</strong> Client-side trust of session responses</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>Session Bypass:</strong> Bypass session validation</li>
                        <li><strong>Authentication Bypass:</strong> Bypass authentication checks</li>
                        <li><strong>Authorization Bypass:</strong> Bypass authorization checks</li>
                        <li><strong>Status Manipulation:</strong> Manipulate session status</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Session Status Manipulation Examples</h5>
            <p>Use these Burp Suite Match and Replace rules to exploit session status manipulation vulnerabilities:</p>
            
            <h6>1. Session Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"is_valid\":false",
  "string_replace": "\"is_valid\":true"
}

// This rule bypasses session validation
// Example: "is_valid":false becomes "is_valid":true</div>

            <h6>2. Authentication Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"is_authenticated\":false",
  "string_replace": "\"is_authenticated\":true"
}

// This rule bypasses authentication checks
// Example: "is_authenticated":false becomes "is_authenticated":true</div>

            <h6>3. Authorization Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"is_authorized\":false",
  "string_replace": "\"is_authorized\":true"
}

// This rule bypasses authorization checks
// Example: "is_authorized":false becomes "is_authorized":true</div>

            <h6>4. Session Status Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"session_status\":\"expired\"",
  "string_replace": "\"session_status\":\"active\""
}

// This rule bypasses session status
// Example: "session_status":"expired" becomes "session_status":"active"</div>

            <h6>5. Auth Status Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"auth_status\":\"unauthenticated\"",
  "string_replace": "\"auth_status\":\"authenticated\""
}

// This rule bypasses auth status
// Example: "auth_status":"unauthenticated" becomes "auth_status":"authenticated"</div>

            <h6>6. Authorization Status Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"authorization_status\":\"unauthorized\"",
  "string_replace": "\"authorization_status\":\"authorized\""
}

// This rule bypasses authorization status
// Example: "authorization_status":"unauthorized" becomes "authorization_status":"authorized"</div>

            <h6>7. Session Valid Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"session_valid\":false",
  "string_replace": "\"session_valid\":true"
}

// This rule bypasses session validity
// Example: "session_valid":false becomes "session_valid":true</div>

            <h6>8. Token Valid Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"token_valid\":false",
  "string_replace": "\"token_valid\":true"
}

// This rule bypasses token validity
// Example: "token_valid":false becomes "token_valid":true</div>

            <h6>9. Session Active Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"session_active\":false",
  "string_replace": "\"session_active\":true"
}

// This rule bypasses session activity
// Example: "session_active":false becomes "session_active":true</div>

            <h6>10. User Logged In Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"user_logged_in\":false",
  "string_replace": "\"user_logged_in\":true"
}

// This rule bypasses user login status
// Example: "user_logged_in":false becomes "user_logged_in":true</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Complete session bypass and unauthorized access</li>
                <li>Authentication bypass and identity theft</li>
                <li>Authorization bypass and privilege escalation</li>
                <li>Compliance violations and legal issues</li>
                <li>Business process disruption and operational impact</li>
                <li>Resource abuse and unauthorized operations</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement server-side session validation</li>
                    <li>Use response integrity checks and signatures</li>
                    <li>Implement proper session controls</li>
                    <li>Use secure coding practices</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual session patterns</li>
                    <li>Implement proper input validation</li>
                    <li>Use multi-factor authentication</li>
                    <li>Implement proper error handling</li>
                    <li>Educate users about security threats</li>
                    <li>Use session bypass detection tools</li>
                    <li>Implement proper audit trails</li>
                    <li>Use secure session management</li>
                    <li>Implement proper rate limiting</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testSessionBypass() {
            alert('Session Status Manipulation test initiated. Use Burp Suite Match and Replace rules above.');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
