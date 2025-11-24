<?php
// Lab 17: Advanced Response Manipulation
// Vulnerability: Advanced response manipulation via response manipulation

session_start();

$message = '';
$response_data = [];

// Simulate advanced response manipulation via response manipulation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'check_advanced') {
        $user_id = $_POST['user_id'] ?? '';
        $feature = $_POST['feature'] ?? '';
        $level = $_POST['level'] ?? '';
        
        if (!empty($user_id) && !empty($feature) && !empty($level)) {
            // Simulate advanced check
            $is_advanced = ($level >= 5);
            
            $response_data = [
                'user_id' => $user_id,
                'feature' => $feature,
                'level' => $level,
                'is_advanced' => $is_advanced,
                'advanced_enabled' => $is_advanced,
                'feature_enabled' => $is_advanced,
                'access_level' => $is_advanced ? 'advanced' : 'basic',
                'status' => $is_advanced ? 'success' : 'error',
                'message' => $is_advanced ? 'Advanced feature enabled' : 'Advanced feature disabled',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the advanced response.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide user ID, feature, and level.</div>';
        }
    } elseif ($action === 'check_security') {
        $user_id = $_POST['user_id'] ?? '';
        $security_level = $_POST['security_level'] ?? '';
        $required_level = $_POST['required_level'] ?? '';
        
        if (!empty($user_id) && !empty($security_level) && !empty($required_level)) {
            // Simulate security check
            $security_valid = ($security_level >= $required_level);
            
            $response_data = [
                'user_id' => $user_id,
                'security_level' => $security_level,
                'required_level' => $required_level,
                'security_valid' => $security_valid,
                'security_passed' => $security_valid,
                'security_status' => $security_valid ? 'passed' : 'failed',
                'status' => $security_valid ? 'success' : 'error',
                'message' => $security_valid ? 'Security check passed' : 'Security check failed',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the security response.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide user ID, security level, and required level.</div>';
        }
    } elseif ($action === 'check_permissions') {
        $user_id = $_POST['user_id'] ?? '';
        $permission = $_POST['permission'] ?? '';
        $role = $_POST['role'] ?? '';
        
        if (!empty($user_id) && !empty($permission) && !empty($role)) {
            // Simulate permission check
            $has_permission = ($role === 'admin');
            
            $response_data = [
                'user_id' => $user_id,
                'permission' => $permission,
                'role' => $role,
                'has_permission' => $has_permission,
                'permission_granted' => $has_permission,
                'permission_status' => $has_permission ? 'granted' : 'denied',
                'status' => $has_permission ? 'success' : 'error',
                'message' => $has_permission ? 'Permission granted' : 'Permission denied',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the permission response.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide user ID, permission, and role.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 17: Advanced Response Manipulation - Response Manipulation Labs</title>
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

        .advanced-rules {
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

        .status-disabled {
            color: var(--accent-red);
            font-weight: bold;
        }

        .status-enabled {
            color: var(--accent-green);
            font-weight: bold;
        }

        .status-failed {
            color: var(--accent-red);
            font-weight: bold;
        }

        .status-passed {
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
            <h1 class="hero-title">Lab 17: Advanced Response Manipulation</h1>
            <p class="hero-subtitle">Advanced response manipulation via response manipulation vulnerabilities</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Expert</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates advanced response manipulation vulnerabilities where attackers can use Burp Suite to modify complex responses and bypass multiple security controls.</p>
            <p><strong>Objective:</strong> Understand how advanced response manipulation attacks work and how to exploit them using Burp Suite.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-gear-fill me-2"></i>Advanced Response System
                    </div>
                    <div class="card-body">
                        <div class="vulnerable-form">
                            <h5>Check Advanced</h5>
                            <p>Test advanced features with response manipulation:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_advanced">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">User ID</label>
                                    <input type="text" class="form-control" id="user_id" name="user_id" placeholder="user123" required>
                                </div>
                                <div class="mb-3">
                                    <label for="feature" class="form-label">Feature</label>
                                    <input type="text" class="form-control" id="feature" name="feature" placeholder="premium_features" required>
                                </div>
                                <div class="mb-3">
                                    <label for="level" class="form-label">Level</label>
                                    <input type="number" class="form-control" id="level" name="level" placeholder="3" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check Advanced</button>
                            </form>
                            
                            <hr>
                            
                            <h5>Check Security</h5>
                            <p>Test security levels with response manipulation:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_security">
                                <div class="mb-3">
                                    <label for="user_id2" class="form-label">User ID</label>
                                    <input type="text" class="form-control" id="user_id2" name="user_id" placeholder="user123" required>
                                </div>
                                <div class="mb-3">
                                    <label for="security_level" class="form-label">Security Level</label>
                                    <input type="number" class="form-control" id="security_level" name="security_level" placeholder="3" required>
                                </div>
                                <div class="mb-3">
                                    <label for="required_level" class="form-label">Required Level</label>
                                    <input type="number" class="form-control" id="required_level" name="required_level" placeholder="5" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check Security</button>
                            </form>
                            
                            <hr>
                            
                            <h5>Check Permissions</h5>
                            <p>Test permissions with response manipulation:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_permissions">
                                <div class="mb-3">
                                    <label for="user_id3" class="form-label">User ID</label>
                                    <input type="text" class="form-control" id="user_id3" name="user_id" placeholder="user123" required>
                                </div>
                                <div class="mb-3">
                                    <label for="permission" class="form-label">Permission</label>
                                    <input type="text" class="form-control" id="permission" name="permission" placeholder="admin_access" required>
                                </div>
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    <input type="text" class="form-control" id="role" name="role" placeholder="user" required>
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
                        <i class="bi bi-shield-exclamation me-2"></i>Advanced Response Manipulation Tester
                    </div>
                    <div class="card-body">
                        <div class="auth-warning">
                            <h5>⚠️ Advanced Response Manipulation Warning</h5>
                            <p>This lab demonstrates advanced response manipulation vulnerabilities:</p>
                            <ul>
                                <li><code>Advanced Bypass</code> - Bypass advanced features</li>
                                <li><code>Security Bypass</code> - Bypass security controls</li>
                                <li><code>Permission Bypass</code> - Bypass permission checks</li>
                                <li><code>Multi-Layer Bypass</code> - Bypass multiple controls</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Burp Suite Rules</h5>
                            <p>Use these Burp Suite Match and Replace rules:</p>
                            <ul>
                                <li><code>"is_advanced":false</code> → <code>"is_advanced":true</code></li>
                                <li><code>"security_valid":false</code> → <code>"security_valid":true</code></li>
                                <li><code>"has_permission":false</code> → <code>"has_permission":true</code></li>
                                <li><code>"access_level":"basic"</code> → <code>"access_level":"advanced"</code></li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testAdvancedBypass()" class="btn btn-primary">Test Advanced Bypass</button>
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
                                        <strong>Is Advanced:</strong> <span class="status-<?php echo isset($response_data['is_advanced']) && $response_data['is_advanced'] ? 'enabled' : 'disabled'; ?>"><?php echo isset($response_data['is_advanced']) ? ($response_data['is_advanced'] ? 'true' : 'false') : 'N/A'; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Security Valid:</strong> <span class="status-<?php echo isset($response_data['security_valid']) && $response_data['security_valid'] ? 'passed' : 'failed'; ?>"><?php echo isset($response_data['security_valid']) ? ($response_data['security_valid'] ? 'true' : 'false') : 'N/A'; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Has Permission:</strong> <span class="status-<?php echo isset($response_data['has_permission']) && $response_data['has_permission'] ? 'granted' : 'denied'; ?>"><?php echo isset($response_data['has_permission']) ? ($response_data['has_permission'] ? 'true' : 'false') : 'N/A'; ?></span>
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
                        <i class="bi bi-code-square me-2"></i>Advanced Response Manipulation Rules
                    </div>
                    <div class="card-body">
                        <div class="advanced-rules">
                            <div class="rule-card">
                                <div class="rule-title">Advanced Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"is_advanced\":false",
  "string_replace": "\"is_advanced\":true"
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Security Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"security_valid\":false",
  "string_replace": "\"security_valid\":true"
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
                                <div class="rule-title">Access Level Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"access_level\":\"basic\"",
  "string_replace": "\"access_level\":\"advanced\""
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Security Status Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"security_status\":\"failed\"",
  "string_replace": "\"security_status\":\"passed\""
}</div>
                            </div>
                            
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
                        <li><strong>Type:</strong> Advanced Response Manipulation</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Method:</strong> Burp Suite Match and Replace</li>
                        <li><strong>Issue:</strong> Client-side trust of complex responses</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>Advanced Bypass:</strong> Bypass advanced features</li>
                        <li><strong>Security Bypass:</strong> Bypass security controls</li>
                        <li><strong>Permission Bypass:</strong> Bypass permission checks</li>
                        <li><strong>Multi-Layer Bypass:</strong> Bypass multiple controls</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Advanced Response Manipulation Examples</h5>
            <p>Use these Burp Suite Match and Replace rules to exploit advanced response manipulation vulnerabilities:</p>
            
            <h6>1. Advanced Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"is_advanced\":false",
  "string_replace": "\"is_advanced\":true"
}

// This rule bypasses advanced features
// Example: "is_advanced":false becomes "is_advanced":true</div>

            <h6>2. Security Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"security_valid\":false",
  "string_replace": "\"security_valid\":true"
}

// This rule bypasses security controls
// Example: "security_valid":false becomes "security_valid":true</div>

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

            <h6>4. Access Level Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"access_level\":\"basic\"",
  "string_replace": "\"access_level\":\"advanced\""
}

// This rule bypasses access level
// Example: "access_level":"basic" becomes "access_level":"advanced"</div>

            <h6>5. Security Status Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"security_status\":\"failed\"",
  "string_replace": "\"security_status\":\"passed\""
}

// This rule bypasses security status
// Example: "security_status":"failed" becomes "security_status":"passed"</div>

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

            <h6>7. Advanced Enabled Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"advanced_enabled\":false",
  "string_replace": "\"advanced_enabled\":true"
}

// This rule bypasses advanced enabled
// Example: "advanced_enabled":false becomes "advanced_enabled":true</div>

            <h6>8. Security Passed Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"security_passed\":false",
  "string_replace": "\"security_passed\":true"
}

// This rule bypasses security passed
// Example: "security_passed":false becomes "security_passed":true</div>

            <h6>9. Permission Granted Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"permission_granted\":false",
  "string_replace": "\"permission_granted\":true"
}

// This rule bypasses permission granted
// Example: "permission_granted":false becomes "permission_granted":true</div>

            <h6>10. Feature Enabled Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"feature_enabled\":false",
  "string_replace": "\"feature_enabled\":true"
}

// This rule bypasses feature enabled
// Example: "feature_enabled":false becomes "feature_enabled":true</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Complete advanced bypass and unauthorized access</li>
                <li>Security bypass and privilege escalation</li>
                <li>Permission bypass and unauthorized operations</li>
                <li>Multi-layer bypass and complete system compromise</li>
                <li>Compliance violations and legal issues</li>
                <li>Business process disruption and operational impact</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement server-side advanced validation</li>
                    <li>Use response integrity checks and signatures</li>
                    <li>Implement proper advanced controls</li>
                    <li>Use secure coding practices</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual advanced patterns</li>
                    <li>Implement proper input validation</li>
                    <li>Use multi-factor authentication</li>
                    <li>Implement proper error handling</li>
                    <li>Educate users about security threats</li>
                    <li>Use advanced bypass detection tools</li>
                    <li>Implement proper audit trails</li>
                    <li>Use secure advanced management</li>
                    <li>Implement proper rate limiting</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testAdvancedBypass() {
            alert('Advanced Response Manipulation test initiated. Use Burp Suite Match and Replace rules above.');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
