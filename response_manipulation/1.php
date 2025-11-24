<?php
// Lab 1: Basic Response Manipulation
// Vulnerability: Basic response manipulation vulnerabilities

session_start();

$message = '';
$response_data = [];

// Simulate basic response manipulation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'check_status') {
        $user_id = $_POST['user_id'] ?? '';
        $status = $_POST['status'] ?? '';
        
        if (!empty($user_id) && !empty($status)) {
            // Simulate server response
            $response_data = [
                'user_id' => $user_id,
                'status' => $status,
                'verified' => false,
                'admin' => false,
                'message' => 'Status check completed',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the response.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide user ID and status.</div>';
        }
    } elseif ($action === 'verify_user') {
        $user_id = $_POST['user_id'] ?? '';
        
        if (!empty($user_id)) {
            // Simulate server response
            $response_data = [
                'user_id' => $user_id,
                'verified' => false,
                'admin' => false,
                'role' => 'user',
                'permissions' => ['read'],
                'message' => 'User verification failed',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the response.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide user ID.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 1: Basic Response Manipulation - Response Manipulation Labs</title>
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

        .response-warning {
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

        .burp-rules {
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
            <h1 class="hero-title">Lab 1: Basic Response Manipulation</h1>
            <p class="hero-subtitle">Basic response manipulation vulnerabilities</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Low</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates basic response manipulation vulnerabilities where attackers can use Burp Suite's Match and Replace functionality to modify server responses and bypass security controls.</p>
            <p><strong>Objective:</strong> Understand how basic response manipulation attacks work and how to exploit them using Burp Suite.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-arrow-repeat me-2"></i>Response Generator
                    </div>
                    <div class="card-body">
                        <div class="vulnerable-form">
                            <h5>Generate Response</h5>
                            <p>Generate a response that can be manipulated with Burp Suite:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_status">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">User ID</label>
                                    <input type="text" class="form-control" id="user_id" name="user_id" placeholder="12345" required>
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="">Select status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="pending">Pending</option>
                                        <option value="suspended">Suspended</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Generate Response</button>
                            </form>
                            
                            <hr>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="verify_user">
                                <div class="mb-3">
                                    <label for="user_id2" class="form-label">User ID for Verification</label>
                                    <input type="text" class="form-control" id="user_id2" name="user_id" placeholder="12345" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Verify User</button>
                            </form>
                            
                            <?php echo $message; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-exclamation me-2"></i>Response Manipulation Tester
                    </div>
                    <div class="card-body">
                        <div class="response-warning">
                            <h5>⚠️ Response Manipulation Warning</h5>
                            <p>This lab demonstrates response manipulation vulnerabilities:</p>
                            <ul>
                                <li><code>Client-Side Trust</code> - Client trusts server responses</li>
                                <li><code>No Validation</code> - No response validation</li>
                                <li><code>Weak Controls</code> - Weak security controls</li>
                                <li><code>No Integrity</code> - No response integrity checks</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Burp Suite Rules</h5>
                            <p>Use these Burp Suite Match and Replace rules:</p>
                            <ul>
                                <li><code>":false</code> → <code>":true</code></li>
                                <li><code>"status":0</code> → <code>"status":1</code></li>
                                <li><code>"verified":false</code> → <code>"verified":true</code></li>
                                <li><code>"admin":false</code> → <code>"admin":true</code></li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testResponseManipulation()" class="btn btn-primary">Test Response Manipulation</button>
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
                                        <strong>User ID:</strong> <?php echo htmlspecialchars($response_data['user_id']); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Status:</strong> <?php echo htmlspecialchars($response_data['status'] ?? 'N/A'); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Verified:</strong> <span class="status-<?php echo $response_data['verified'] ? 'true' : 'false'; ?>"><?php echo $response_data['verified'] ? 'true' : 'false'; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Admin:</strong> <span class="status-<?php echo $response_data['admin'] ? 'true' : 'false'; ?>"><?php echo $response_data['admin'] ? 'true' : 'false'; ?></span>
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
                        <i class="bi bi-code-square me-2"></i>Burp Suite Match and Replace Rules
                    </div>
                    <div class="card-body">
                        <div class="burp-rules">
                            <div class="rule-card">
                                <div class="rule-title">Boolean Value Manipulation</div>
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
                                <div class="rule-title">Status Code Manipulation</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"status\":0",
  "string_replace": "\"status\":1"
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
                                <div class="rule-title">Role Manipulation</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"role\":\"user\"",
  "string_replace": "\"role\":\"admin\""
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Permission Escalation</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "[\"read\"]",
  "string_replace": "[\"read\",\"write\",\"admin\"]"
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
                        <li><strong>Type:</strong> Basic Response Manipulation</li>
                        <li><strong>Severity:</strong> Medium</li>
                        <li><strong>Method:</strong> Burp Suite Match and Replace</li>
                        <li><strong>Issue:</strong> Client-side trust of server responses</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>Boolean Manipulation:</strong> Change false to true</li>
                        <li><strong>Status Manipulation:</strong> Change status codes</li>
                        <li><strong>Verification Bypass:</strong> Bypass verification checks</li>
                        <li><strong>Privilege Escalation:</strong> Escalate user privileges</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Basic Response Manipulation Examples</h5>
            <p>Use these Burp Suite Match and Replace rules to exploit basic response manipulation vulnerabilities:</p>
            
            <h6>1. Boolean Value Manipulation:</h6>
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

            <h6>2. Status Code Manipulation:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"status\":0",
  "string_replace": "\"status\":1"
}

// This rule changes status from 0 to 1
// Example: "status":0 becomes "status":1</div>

            <h6>3. Verification Bypass:</h6>
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

            <h6>4. Admin Privilege Escalation:</h6>
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

            <h6>5. Role Manipulation:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"role\":\"user\"",
  "string_replace": "\"role\":\"admin\""
}

// This rule changes user role to admin
// Example: "role":"user" becomes "role":"admin"</div>

            <h6>6. Permission Escalation:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "[\"read\"]",
  "string_replace": "[\"read\",\"write\",\"admin\"]"
}

// This rule escalates permissions
// Example: ["read"] becomes ["read","write","admin"]</div>

            <h6>7. Error Message Manipulation:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"error\":\"Access denied\"",
  "string_replace": "\"success\":\"Access granted\""
}

// This rule changes error messages to success
// Example: "error":"Access denied" becomes "success":"Access granted"</div>

            <h6>8. HTTP Status Code Manipulation:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"status\":401",
  "string_replace": "\"status\":200"
}

// This rule changes HTTP status codes
// Example: "status":401 becomes "status":200</div>

            <h6>9. Validation Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"valid\":false",
  "string_replace": "\"valid\":true"
}

// This rule bypasses validation checks
// Example: "valid":false becomes "valid":true</div>

            <h6>10. Feature Flag Manipulation:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"feature_enabled\":false",
  "string_replace": "\"feature_enabled\":true"
}

// This rule enables disabled features
// Example: "feature_enabled":false becomes "feature_enabled":true</div>

            <h6>11. Session Status Manipulation:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"session_expired\":true",
  "string_replace": "\"session_expired\":false"
}

// This rule prevents session expiration
// Example: "session_expired":true becomes "session_expired":false</div>

            <h6>12. Payment Status Manipulation:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"payment_status\":\"failed\"",
  "string_replace": "\"payment_status\":\"success\""
}

// This rule changes payment status
// Example: "payment_status":"failed" becomes "payment_status":"success"</div>

            <h6>13. Rate Limit Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"rate_limited\":true",
  "string_replace": "\"rate_limited\":false"
}

// This rule bypasses rate limiting
// Example: "rate_limited":true becomes "rate_limited":false</div>

            <h6>14. API Response Manipulation:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"api_error\":\"Invalid request\"",
  "string_replace": "\"api_success\":\"Request processed\""
}

// This rule manipulates API responses
// Example: "api_error":"Invalid request" becomes "api_success":"Request processed"</div>

            <h6>15. Business Logic Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"business_rule_violation\":true",
  "string_replace": "\"business_rule_violation\":false"
}

// This rule bypasses business logic
// Example: "business_rule_violation":true becomes "business_rule_violation":false</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Complete security control bypass</li>
                <li>Unauthorized access and privilege escalation</li>
                <li>Financial fraud and payment manipulation</li>
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
                    <li>Use response manipulation detection tools</li>
                    <li>Implement proper audit trails</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testResponseManipulation() {
            alert('Response Manipulation test initiated. Use Burp Suite Match and Replace rules above.');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
