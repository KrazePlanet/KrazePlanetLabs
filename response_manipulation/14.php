<?php
// Lab 14: API Response Manipulation
// Vulnerability: API response manipulation via response manipulation

session_start();

$message = '';
$response_data = [];

// Simulate API response manipulation via response manipulation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'check_api_key') {
        $api_key = $_POST['api_key'] ?? '';
        $endpoint = $_POST['endpoint'] ?? '';
        
        if (!empty($api_key) && !empty($endpoint)) {
            // Simulate API key check
            $is_valid = ($api_key === 'valid_api_key');
            
            $response_data = [
                'api_key' => $api_key,
                'endpoint' => $endpoint,
                'is_valid' => $is_valid,
                'api_key_valid' => $is_valid,
                'api_status' => $is_valid ? 'authorized' : 'unauthorized',
                'status' => $is_valid ? 'success' : 'error',
                'message' => $is_valid ? 'API key is valid' : 'API key is invalid',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the API response.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide API key and endpoint.</div>';
        }
    } elseif ($action === 'check_rate_limit') {
        $api_key = $_POST['api_key'] ?? '';
        $requests_made = $_POST['requests_made'] ?? '';
        
        if (!empty($api_key) && !empty($requests_made)) {
            // Simulate rate limit check
            $is_rate_limited = ($requests_made > 100);
            
            $response_data = [
                'api_key' => $api_key,
                'requests_made' => $requests_made,
                'rate_limited' => $is_rate_limited,
                'rate_limit_exceeded' => $is_rate_limited,
                'rate_limit_status' => $is_rate_limited ? 'exceeded' : 'within_limit',
                'status' => $is_rate_limited ? 'error' : 'success',
                'message' => $is_rate_limited ? 'Rate limit exceeded' : 'Rate limit within bounds',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the rate limit response.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide API key and requests made.</div>';
        }
    } elseif ($action === 'check_permissions') {
        $api_key = $_POST['api_key'] ?? '';
        $permission = $_POST['permission'] ?? '';
        
        if (!empty($api_key) && !empty($permission)) {
            // Simulate permission check
            $has_permission = ($api_key === 'admin_api_key');
            
            $response_data = [
                'api_key' => $api_key,
                'permission' => $permission,
                'has_permission' => $has_permission,
                'permission_granted' => $has_permission,
                'permission_status' => $has_permission ? 'granted' : 'denied',
                'status' => $has_permission ? 'success' : 'error',
                'message' => $has_permission ? 'Permission granted' : 'Permission denied',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the permission response.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide API key and permission.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 14: API Response Manipulation - Response Manipulation Labs</title>
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

        .api-rules {
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

        .status-unauthorized {
            color: var(--accent-red);
            font-weight: bold;
        }

        .status-authorized {
            color: var(--accent-green);
            font-weight: bold;
        }

        .status-exceeded {
            color: var(--accent-red);
            font-weight: bold;
        }

        .status-within-limit {
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
            <h1 class="hero-title">Lab 14: API Response Manipulation</h1>
            <p class="hero-subtitle">API response manipulation via response manipulation vulnerabilities</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates API response manipulation vulnerabilities where attackers can use Burp Suite to modify API responses and bypass API controls.</p>
            <p><strong>Objective:</strong> Understand how API response manipulation attacks work and how to exploit them using Burp Suite.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-gear me-2"></i>API Response System
                    </div>
                    <div class="card-body">
                        <div class="vulnerable-form">
                            <h5>Check API Key</h5>
                            <p>Test API key validation with response manipulation:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_api_key">
                                <div class="mb-3">
                                    <label for="api_key" class="form-label">API Key</label>
                                    <input type="text" class="form-control" id="api_key" name="api_key" placeholder="invalid_api_key" required>
                                </div>
                                <div class="mb-3">
                                    <label for="endpoint" class="form-label">Endpoint</label>
                                    <input type="text" class="form-control" id="endpoint" name="endpoint" placeholder="/api/users" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check API Key</button>
                            </form>
                            
                            <hr>
                            
                            <h5>Check Rate Limit</h5>
                            <p>Test API rate limiting with response manipulation:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_rate_limit">
                                <div class="mb-3">
                                    <label for="api_key2" class="form-label">API Key</label>
                                    <input type="text" class="form-control" id="api_key2" name="api_key" placeholder="api_key_123" required>
                                </div>
                                <div class="mb-3">
                                    <label for="requests_made" class="form-label">Requests Made</label>
                                    <input type="number" class="form-control" id="requests_made" name="requests_made" placeholder="150" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check Rate Limit</button>
                            </form>
                            
                            <hr>
                            
                            <h5>Check Permissions</h5>
                            <p>Test API permissions with response manipulation:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_permissions">
                                <div class="mb-3">
                                    <label for="api_key3" class="form-label">API Key</label>
                                    <input type="text" class="form-control" id="api_key3" name="api_key" placeholder="api_key_123" required>
                                </div>
                                <div class="mb-3">
                                    <label for="permission" class="form-label">Permission</label>
                                    <input type="text" class="form-control" id="permission" name="permission" placeholder="admin_access" required>
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
                        <i class="bi bi-shield-exclamation me-2"></i>API Response Manipulation Tester
                    </div>
                    <div class="card-body">
                        <div class="auth-warning">
                            <h5>⚠️ API Response Manipulation Warning</h5>
                            <p>This lab demonstrates API response manipulation vulnerabilities:</p>
                            <ul>
                                <li><code>API Key Bypass</code> - Bypass API key validation</li>
                                <li><code>Rate Limit Bypass</code> - Bypass API rate limits</li>
                                <li><code>Permission Bypass</code> - Bypass API permissions</li>
                                <li><code>Status Manipulation</code> - Manipulate API status</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Burp Suite Rules</h5>
                            <p>Use these Burp Suite Match and Replace rules:</p>
                            <ul>
                                <li><code>"is_valid":false</code> → <code>"is_valid":true</code></li>
                                <li><code>"rate_limited":true</code> → <code>"rate_limited":false</code></li>
                                <li><code>"has_permission":false</code> → <code>"has_permission":true</code></li>
                                <li><code>"api_status":"unauthorized"</code> → <code>"api_status":"authorized"</code></li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testApiBypass()" class="btn btn-primary">Test API Bypass</button>
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
                                        <strong>Rate Limited:</strong> <span class="status-<?php echo isset($response_data['rate_limited']) && $response_data['rate_limited'] ? 'exceeded' : 'within-limit'; ?>"><?php echo isset($response_data['rate_limited']) ? ($response_data['rate_limited'] ? 'true' : 'false') : 'N/A'; ?></span>
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
                        <i class="bi bi-code-square me-2"></i>API Response Manipulation Rules
                    </div>
                    <div class="card-body">
                        <div class="api-rules">
                            <div class="rule-card">
                                <div class="rule-title">API Key Bypass</div>
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
                                <div class="rule-title">Rate Limit Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"rate_limited\":true",
  "string_replace": "\"rate_limited\":false"
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
                                <div class="rule-title">API Status Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"api_status\":\"unauthorized\"",
  "string_replace": "\"api_status\":\"authorized\""
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Rate Limit Status Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"rate_limit_status\":\"exceeded\"",
  "string_replace": "\"rate_limit_status\":\"within_limit\""
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
                        <li><strong>Type:</strong> API Response Manipulation</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Method:</strong> Burp Suite Match and Replace</li>
                        <li><strong>Issue:</strong> Client-side trust of API responses</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>API Key Bypass:</strong> Bypass API key validation</li>
                        <li><strong>Rate Limit Bypass:</strong> Bypass API rate limits</li>
                        <li><strong>Permission Bypass:</strong> Bypass API permissions</li>
                        <li><strong>Status Manipulation:</strong> Manipulate API status</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>API Response Manipulation Examples</h5>
            <p>Use these Burp Suite Match and Replace rules to exploit API response manipulation vulnerabilities:</p>
            
            <h6>1. API Key Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"is_valid\":false",
  "string_replace": "\"is_valid\":true"
}

// This rule bypasses API key validation
// Example: "is_valid":false becomes "is_valid":true</div>

            <h6>2. Rate Limit Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"rate_limited\":true",
  "string_replace": "\"rate_limited\":false"
}

// This rule bypasses API rate limits
// Example: "rate_limited":true becomes "rate_limited":false</div>

            <h6>3. Permission Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"has_permission\":false",
  "string_replace": "\"has_permission\":true"
}

// This rule bypasses API permissions
// Example: "has_permission":false becomes "has_permission":true</div>

            <h6>4. API Status Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"api_status\":\"unauthorized\"",
  "string_replace": "\"api_status\":\"authorized\""
}

// This rule bypasses API status
// Example: "api_status":"unauthorized" becomes "api_status":"authorized"</div>

            <h6>5. Rate Limit Status Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"rate_limit_status\":\"exceeded\"",
  "string_replace": "\"rate_limit_status\":\"within_limit\""
}

// This rule bypasses rate limit status
// Example: "rate_limit_status":"exceeded" becomes "rate_limit_status":"within_limit"</div>

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

            <h6>7. API Key Valid Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"api_key_valid\":false",
  "string_replace": "\"api_key_valid\":true"
}

// This rule bypasses API key validity
// Example: "api_key_valid":false becomes "api_key_valid":true</div>

            <h6>8. Rate Limit Exceeded Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"rate_limit_exceeded\":true",
  "string_replace": "\"rate_limit_exceeded\":false"
}

// This rule bypasses rate limit exceeded
// Example: "rate_limit_exceeded":true becomes "rate_limit_exceeded":false</div>

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

            <h6>10. API Access Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"api_access\":false",
  "string_replace": "\"api_access\":true"
}

// This rule bypasses API access
// Example: "api_access":false becomes "api_access":true</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Complete API bypass and unauthorized access</li>
                <li>Rate limit bypass and resource abuse</li>
                <li>Permission bypass and privilege escalation</li>
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
                    <li>Implement server-side API validation</li>
                    <li>Use response integrity checks and signatures</li>
                    <li>Implement proper API controls</li>
                    <li>Use secure coding practices</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual API patterns</li>
                    <li>Implement proper input validation</li>
                    <li>Use multi-factor authentication</li>
                    <li>Implement proper error handling</li>
                    <li>Educate users about security threats</li>
                    <li>Use API bypass detection tools</li>
                    <li>Implement proper audit trails</li>
                    <li>Use secure API management</li>
                    <li>Implement proper rate limiting</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testApiBypass() {
            alert('API Response Manipulation test initiated. Use Burp Suite Match and Replace rules above.');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
