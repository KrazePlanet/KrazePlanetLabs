<?php
// Lab 6: Authorization Bypass
// Vulnerability: Authorization bypass via response manipulation

session_start();

$message = '';
$response_data = [];

// Simulate authorization bypass via response manipulation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'check_access') {
        $user_id = $_POST['user_id'] ?? '';
        $resource = $_POST['resource'] ?? '';
        
        if (!empty($user_id) && !empty($resource)) {
            // Simulate authorization check
            $has_access = ($user_id === 'admin' || $user_id === 'user1');
            
            $response_data = [
                'user_id' => $user_id,
                'resource' => $resource,
                'authorized' => $has_access,
                'can_read' => $has_access,
                'can_write' => ($user_id === 'admin'),
                'can_delete' => ($user_id === 'admin'),
                'can_admin' => ($user_id === 'admin'),
                'status' => $has_access ? 'allowed' : 'denied',
                'message' => $has_access ? 'Access granted' : 'Access denied',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the authorization response.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide user ID and resource.</div>';
        }
    } elseif ($action === 'check_role') {
        $user_id = $_POST['user_id'] ?? '';
        $role = $_POST['role'] ?? '';
        
        if (!empty($user_id) && !empty($role)) {
            // Simulate role check
            $has_role = ($user_id === 'admin' && $role === 'admin');
            
            $response_data = [
                'user_id' => $user_id,
                'role' => $role,
                'has_role' => $has_role,
                'authorized' => $has_role,
                'admin' => $has_role,
                'status' => $has_role ? 'authorized' : 'unauthorized',
                'message' => $has_role ? 'Role authorized' : 'Role unauthorized',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the role response.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide user ID and role.</div>';
        }
    } elseif ($action === 'check_permission') {
        $user_id = $_POST['user_id'] ?? '';
        $permission = $_POST['permission'] ?? '';
        
        if (!empty($user_id) && !empty($permission)) {
            // Simulate permission check
            $has_permission = ($user_id === 'admin');
            
            $response_data = [
                'user_id' => $user_id,
                'permission' => $permission,
                'granted' => $has_permission,
                'authorized' => $has_permission,
                'status' => $has_permission ? 'granted' : 'denied',
                'message' => $has_permission ? 'Permission granted' : 'Permission denied',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the permission response.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide user ID and permission.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 6: Authorization Bypass - Response Manipulation Labs</title>
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

        .status-denied {
            color: var(--accent-red);
            font-weight: bold;
        }

        .status-allowed {
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
            <h1 class="hero-title">Lab 6: Authorization Bypass</h1>
            <p class="hero-subtitle">Authorization bypass via response manipulation vulnerabilities</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Medium</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates authorization bypass vulnerabilities where attackers can use Burp Suite to modify authorization responses and bypass access controls.</p>
            <p><strong>Objective:</strong> Understand how authorization bypass attacks work and how to exploit them using Burp Suite.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-shield-lock me-2"></i>Authorization System
                    </div>
                    <div class="card-body">
                        <div class="vulnerable-form">
                            <h5>Check Access</h5>
                            <p>Test authorization with response manipulation:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_access">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">User ID</label>
                                    <input type="text" class="form-control" id="user_id" name="user_id" placeholder="user123" required>
                                </div>
                                <div class="mb-3">
                                    <label for="resource" class="form-label">Resource</label>
                                    <input type="text" class="form-control" id="resource" name="resource" placeholder="admin_panel" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check Access</button>
                            </form>
                            
                            <hr>
                            
                            <h5>Check Role</h5>
                            <p>Check user role authorization:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_role">
                                <div class="mb-3">
                                    <label for="user_id2" class="form-label">User ID</label>
                                    <input type="text" class="form-control" id="user_id2" name="user_id" placeholder="user123" required>
                                </div>
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    <input type="text" class="form-control" id="role" name="role" placeholder="admin" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check Role</button>
                            </form>
                            
                            <hr>
                            
                            <h5>Check Permission</h5>
                            <p>Check user permission authorization:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_permission">
                                <div class="mb-3">
                                    <label for="user_id3" class="form-label">User ID</label>
                                    <input type="text" class="form-control" id="user_id3" name="user_id" placeholder="user123" required>
                                </div>
                                <div class="mb-3">
                                    <label for="permission" class="form-label">Permission</label>
                                    <input type="text" class="form-control" id="permission" name="permission" placeholder="delete_users" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check Permission</button>
                            </form>
                            
                            <?php echo $message; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-exclamation me-2"></i>Authorization Bypass Tester
                    </div>
                    <div class="card-body">
                        <div class="auth-warning">
                            <h5>⚠️ Authorization Bypass Warning</h5>
                            <p>This lab demonstrates authorization bypass vulnerabilities:</p>
                            <ul>
                                <li><code>Access Control</code> - Bypass access controls</li>
                                <li><code>Role Bypass</code> - Bypass role restrictions</li>
                                <li><code>Permission Bypass</code> - Bypass permission checks</li>
                                <li><code>Resource Bypass</code> - Bypass resource restrictions</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Burp Suite Rules</h5>
                            <p>Use these Burp Suite Match and Replace rules:</p>
                            <ul>
                                <li><code>"authorized":false</code> → <code>"authorized":true</code></li>
                                <li><code>"can_read":false</code> → <code>"can_read":true</code></li>
                                <li><code>"can_write":false</code> → <code>"can_write":true</code></li>
                                <li><code>"can_delete":false</code> → <code>"can_delete":true</code></li>
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
                                        <strong>Authorized:</strong> <span class="status-<?php echo isset($response_data['authorized']) && $response_data['authorized'] ? 'allowed' : 'denied'; ?>"><?php echo isset($response_data['authorized']) ? ($response_data['authorized'] ? 'true' : 'false') : 'N/A'; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Status:</strong> <span class="status-<?php echo isset($response_data['status']) && in_array($response_data['status'], ['allowed', 'authorized', 'granted']) ? 'allowed' : 'denied'; ?>"><?php echo isset($response_data['status']) ? $response_data['status'] : 'N/A'; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Can Read:</strong> <?php echo isset($response_data['can_read']) ? ($response_data['can_read'] ? 'Yes' : 'No') : 'N/A'; ?>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Can Write:</strong> <?php echo isset($response_data['can_write']) ? ($response_data['can_write'] ? 'Yes' : 'No') : 'N/A'; ?>
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
                        <i class="bi bi-code-square me-2"></i>Authorization Bypass Rules
                    </div>
                    <div class="card-body">
                        <div class="auth-rules">
                            <div class="rule-card">
                                <div class="rule-title">Access Control Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"authorized\":false",
  "string_replace": "\"authorized\":true"
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Read Permission Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"can_read\":false",
  "string_replace": "\"can_read\":true"
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Write Permission Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"can_write\":false",
  "string_replace": "\"can_write\":true"
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Delete Permission Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"can_delete\":false",
  "string_replace": "\"can_delete\":true"
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Admin Permission Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"can_admin\":false",
  "string_replace": "\"can_admin\":true"
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Status Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"status\":\"denied\"",
  "string_replace": "\"status\":\"allowed\""
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
                        <li><strong>Type:</strong> Authorization Bypass</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Method:</strong> Burp Suite Match and Replace</li>
                        <li><strong>Issue:</strong> Client-side trust of authorization responses</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>Access Control Bypass:</strong> Bypass access controls</li>
                        <li><strong>Permission Bypass:</strong> Bypass permission checks</li>
                        <li><strong>Role Bypass:</strong> Bypass role restrictions</li>
                        <li><strong>Resource Bypass:</strong> Bypass resource restrictions</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Authorization Bypass Examples</h5>
            <p>Use these Burp Suite Match and Replace rules to exploit authorization bypass vulnerabilities:</p>
            
            <h6>1. Access Control Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"authorized\":false",
  "string_replace": "\"authorized\":true"
}

// This rule bypasses access controls
// Example: "authorized":false becomes "authorized":true</div>

            <h6>2. Read Permission Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"can_read\":false",
  "string_replace": "\"can_read\":true"
}

// This rule bypasses read permissions
// Example: "can_read":false becomes "can_read":true</div>

            <h6>3. Write Permission Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"can_write\":false",
  "string_replace": "\"can_write\":true"
}

// This rule bypasses write permissions
// Example: "can_write":false becomes "can_write":true</div>

            <h6>4. Delete Permission Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"can_delete\":false",
  "string_replace": "\"can_delete\":true"
}

// This rule bypasses delete permissions
// Example: "can_delete":false becomes "can_delete":true</div>

            <h6>5. Admin Permission Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"can_admin\":false",
  "string_replace": "\"can_admin\":true"
}

// This rule bypasses admin permissions
// Example: "can_admin":false becomes "can_admin":true</div>

            <h6>6. Status Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"status\":\"denied\"",
  "string_replace": "\"status\":\"allowed\""
}

// This rule bypasses status restrictions
// Example: "status":"denied" becomes "status":"allowed"</div>

            <h6>7. Role Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"has_role\":false",
  "string_replace": "\"has_role\":true"
}

// This rule bypasses role checks
// Example: "has_role":false becomes "has_role":true</div>

            <h6>8. Permission Grant Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"granted\":false",
  "string_replace": "\"granted\":true"
}

// This rule bypasses permission grants
// Example: "granted":false becomes "granted":true</div>

            <h6>9. Resource Access Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"access_granted\":false",
  "string_replace": "\"access_granted\":true"
}

// This rule bypasses resource access
// Example: "access_granted":false becomes "access_granted":true</div>

            <h6>10. Feature Access Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"feature_enabled\":false",
  "string_replace": "\"feature_enabled\":true"
}

// This rule bypasses feature access
// Example: "feature_enabled":false becomes "feature_enabled":true</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Complete authorization bypass and unauthorized access</li>
                <li>Privilege escalation and admin access</li>
                <li>Data manipulation and integrity issues</li>
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
                    <li>Implement server-side authorization validation</li>
                    <li>Use response integrity checks and signatures</li>
                    <li>Implement proper access controls</li>
                    <li>Use secure coding practices</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual authorization patterns</li>
                    <li>Implement proper input validation</li>
                    <li>Use role-based access control (RBAC)</li>
                    <li>Implement proper error handling</li>
                    <li>Educate users about security threats</li>
                    <li>Use multi-factor authentication</li>
                    <li>Implement proper logging and monitoring</li>
                    <li>Use authorization bypass detection tools</li>
                    <li>Implement proper audit trails</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testAuthBypass() {
            alert('Authorization Bypass test initiated. Use Burp Suite Match and Replace rules above.');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
