<?php
// Lab 9: User Role Manipulation
// Vulnerability: User role manipulation via response manipulation

session_start();

$message = '';
$response_data = [];

// Simulate user role manipulation via response manipulation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'check_role') {
        $user_id = $_POST['user_id'] ?? '';
        $role = $_POST['role'] ?? '';
        
        if (!empty($user_id) && !empty($role)) {
            // Simulate role check
            $has_role = ($user_id === 'admin' && $role === 'admin');
            
            $response_data = [
                'user_id' => $user_id,
                'role' => $role,
                'has_role' => $has_role,
                'is_admin' => $has_role,
                'is_user' => !$has_role,
                'role_status' => $has_role ? 'authorized' : 'unauthorized',
                'status' => $has_role ? 'success' : 'error',
                'message' => $has_role ? 'Role authorized successfully' : 'Role unauthorized',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the role response.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide user ID and role.</div>';
        }
    } elseif ($action === 'check_permissions') {
        $user_id = $_POST['user_id'] ?? '';
        $permission = $_POST['permission'] ?? '';
        
        if (!empty($user_id) && !empty($permission)) {
            // Simulate permission check
            $has_permission = ($user_id === 'admin');
            
            $response_data = [
                'user_id' => $user_id,
                'permission' => $permission,
                'has_permission' => $has_permission,
                'can_read' => $has_permission,
                'can_write' => $has_permission,
                'can_delete' => $has_permission,
                'permission_status' => $has_permission ? 'granted' : 'denied',
                'status' => $has_permission ? 'success' : 'error',
                'message' => $has_permission ? 'Permission granted successfully' : 'Permission denied',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the permission response.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide user ID and permission.</div>';
        }
    } elseif ($action === 'check_access') {
        $user_id = $_POST['user_id'] ?? '';
        $resource = $_POST['resource'] ?? '';
        
        if (!empty($user_id) && !empty($resource)) {
            // Simulate access check
            $has_access = ($user_id === 'admin');
            
            $response_data = [
                'user_id' => $user_id,
                'resource' => $resource,
                'has_access' => $has_access,
                'access_granted' => $has_access,
                'access_level' => $has_access ? 'admin' : 'user',
                'access_status' => $has_access ? 'granted' : 'denied',
                'status' => $has_access ? 'success' : 'error',
                'message' => $has_access ? 'Access granted successfully' : 'Access denied',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the access response.</div>';
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
    <title>Lab 9: User Role Manipulation - Response Manipulation Labs</title>
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

        .role-rules {
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

        .status-unauthorized {
            color: var(--accent-red);
            font-weight: bold;
        }

        .status-authorized {
            color: var(--accent-green);
            font-weight: bold;
        }

        .status-denied {
            color: var(--accent-red);
            font-weight: bold;
        }

        .status-granted {
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
            <h1 class="hero-title">Lab 9: User Role Manipulation</h1>
            <p class="hero-subtitle">User role manipulation via response manipulation vulnerabilities</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates user role manipulation vulnerabilities where attackers can use Burp Suite to modify role responses and bypass role restrictions.</p>
            <p><strong>Objective:</strong> Understand how user role manipulation attacks work and how to exploit them using Burp Suite.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-person-badge me-2"></i>User Role System
                    </div>
                    <div class="card-body">
                        <div class="vulnerable-form">
                            <h5>Check Role</h5>
                            <p>Test user role with response manipulation:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_role">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">User ID</label>
                                    <input type="text" class="form-control" id="user_id" name="user_id" placeholder="user123" required>
                                </div>
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    <input type="text" class="form-control" id="role" name="role" placeholder="admin" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check Role</button>
                            </form>
                            
                            <hr>
                            
                            <h5>Check Permissions</h5>
                            <p>Check user permissions:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_permissions">
                                <div class="mb-3">
                                    <label for="user_id2" class="form-label">User ID</label>
                                    <input type="text" class="form-control" id="user_id2" name="user_id" placeholder="user123" required>
                                </div>
                                <div class="mb-3">
                                    <label for="permission" class="form-label">Permission</label>
                                    <input type="text" class="form-control" id="permission" name="permission" placeholder="delete_users" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check Permissions</button>
                            </form>
                            
                            <hr>
                            
                            <h5>Check Access</h5>
                            <p>Check user access to resources:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_access">
                                <div class="mb-3">
                                    <label for="user_id3" class="form-label">User ID</label>
                                    <input type="text" class="form-control" id="user_id3" name="user_id" placeholder="user123" required>
                                </div>
                                <div class="mb-3">
                                    <label for="resource" class="form-label">Resource</label>
                                    <input type="text" class="form-control" id="resource" name="resource" placeholder="admin_panel" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check Access</button>
                            </form>
                            
                            <?php echo $message; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-exclamation me-2"></i>User Role Manipulation Tester
                    </div>
                    <div class="card-body">
                        <div class="auth-warning">
                            <h5>⚠️ User Role Manipulation Warning</h5>
                            <p>This lab demonstrates user role manipulation vulnerabilities:</p>
                            <ul>
                                <li><code>Role Bypass</code> - Bypass role restrictions</li>
                                <li><code>Permission Bypass</code> - Bypass permission checks</li>
                                <li><code>Access Bypass</code> - Bypass access controls</li>
                                <li><code>Privilege Escalation</code> - Escalate user privileges</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Burp Suite Rules</h5>
                            <p>Use these Burp Suite Match and Replace rules:</p>
                            <ul>
                                <li><code>"has_role":false</code> → <code>"has_role":true</code></li>
                                <li><code>"is_admin":false</code> → <code>"is_admin":true</code></li>
                                <li><code>"has_permission":false</code> → <code>"has_permission":true</code></li>
                                <li><code>"has_access":false</code> → <code>"has_access":true</code></li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testRoleBypass()" class="btn btn-primary">Test Role Bypass</button>
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
                                        <strong>Has Role:</strong> <span class="status-<?php echo isset($response_data['has_role']) && $response_data['has_role'] ? 'authorized' : 'unauthorized'; ?>"><?php echo isset($response_data['has_role']) ? ($response_data['has_role'] ? 'true' : 'false') : 'N/A'; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Is Admin:</strong> <span class="status-<?php echo isset($response_data['is_admin']) && $response_data['is_admin'] ? 'authorized' : 'unauthorized'; ?>"><?php echo isset($response_data['is_admin']) ? ($response_data['is_admin'] ? 'true' : 'false') : 'N/A'; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Has Permission:</strong> <span class="status-<?php echo isset($response_data['has_permission']) && $response_data['has_permission'] ? 'granted' : 'denied'; ?>"><?php echo isset($response_data['has_permission']) ? ($response_data['has_permission'] ? 'true' : 'false') : 'N/A'; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Has Access:</strong> <span class="status-<?php echo isset($response_data['has_access']) && $response_data['has_access'] ? 'granted' : 'denied'; ?>"><?php echo isset($response_data['has_access']) ? ($response_data['has_access'] ? 'true' : 'false') : 'N/A'; ?></span>
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
                        <i class="bi bi-code-square me-2"></i>User Role Manipulation Rules
                    </div>
                    <div class="card-body">
                        <div class="role-rules">
                            <div class="rule-card">
                                <div class="rule-title">Role Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"has_role\":false",
  "string_replace": "\"has_role\":true"
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Admin Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"is_admin\":false",
  "string_replace": "\"is_admin\":true"
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Permission Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"has_permission\":false",
  "string_replace": "\"has_permission\":true"
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Access Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"has_access\":false",
  "string_replace": "\"has_access\":true"
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Role Status Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"role_status\":\"unauthorized\"",
  "string_replace": "\"role_status\":\"authorized\""
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Permission Status Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"permission_status\":\"denied\"",
  "string_replace": "\"permission_status\":\"granted\""
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
                        <li><strong>Type:</strong> User Role Manipulation</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Method:</strong> Burp Suite Match and Replace</li>
                        <li><strong>Issue:</strong> Client-side trust of role responses</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>Role Bypass:</strong> Bypass role restrictions</li>
                        <li><strong>Permission Bypass:</strong> Bypass permission checks</li>
                        <li><strong>Access Bypass:</strong> Bypass access controls</li>
                        <li><strong>Privilege Escalation:</strong> Escalate user privileges</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>User Role Manipulation Examples</h5>
            <p>Use these Burp Suite Match and Replace rules to exploit user role manipulation vulnerabilities:</p>
            
            <h6>1. Role Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"has_role\":false",
  "string_replace": "\"has_role\":true"
}

// This rule bypasses role restrictions
// Example: "has_role":false becomes "has_role":true</div>

            <h6>2. Admin Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"is_admin\":false",
  "string_replace": "\"is_admin\":true"
}

// This rule bypasses admin restrictions
// Example: "is_admin":false becomes "is_admin":true</div>

            <h6>3. Permission Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"has_permission\":false",
  "string_replace": "\"has_permission\":true"
}

// This rule bypasses permission checks
// Example: "has_permission":false becomes "has_permission":true</div>

            <h6>4. Access Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"has_access\":false",
  "string_replace": "\"has_access\":true"
}

// This rule bypasses access controls
// Example: "has_access":false becomes "has_access":true</div>

            <h6>5. Role Status Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"role_status\":\"unauthorized\"",
  "string_replace": "\"role_status\":\"authorized\""
}

// This rule bypasses role status
// Example: "role_status":"unauthorized" becomes "role_status":"authorized"</div>

            <h6>6. Permission Status Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"permission_status\":\"denied\"",
  "string_replace": "\"permission_status\":\"granted\""
}

// This rule bypasses permission status
// Example: "permission_status":"denied" becomes "permission_status":"granted"</div>

            <h6>7. Access Level Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"access_level\":\"user\"",
  "string_replace": "\"access_level\":\"admin\""
}

// This rule bypasses access level
// Example: "access_level":"user" becomes "access_level":"admin"</div>

            <h6>8. User Type Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"user_type\":\"regular\"",
  "string_replace": "\"user_type\":\"admin\""
}

// This rule bypasses user type
// Example: "user_type":"regular" becomes "user_type":"admin"</div>

            <h6>9. Privilege Level Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"privilege_level\":\"low\"",
  "string_replace": "\"privilege_level\":\"high\""
}

// This rule bypasses privilege level
// Example: "privilege_level":"low" becomes "privilege_level":"high"</div>

            <h6>10. Role Assignment Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"role_assigned\":false",
  "string_replace": "\"role_assigned\":true"
}

// This rule bypasses role assignment
// Example: "role_assigned":false becomes "role_assigned":true</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Complete role bypass and unauthorized access</li>
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
                    <li>Implement server-side role validation</li>
                    <li>Use response integrity checks and signatures</li>
                    <li>Implement proper role controls</li>
                    <li>Use secure coding practices</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual role patterns</li>
                    <li>Implement proper input validation</li>
                    <li>Use multi-factor authentication</li>
                    <li>Implement proper error handling</li>
                    <li>Educate users about security threats</li>
                    <li>Use role bypass detection tools</li>
                    <li>Implement proper audit trails</li>
                    <li>Use role-based access control (RBAC)</li>
                    <li>Implement proper privilege management</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testRoleBypass() {
            alert('User Role Manipulation test initiated. Use Burp Suite Match and Replace rules above.');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
