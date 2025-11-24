<?php
// Lab 11: Error Message Manipulation
// Vulnerability: Error message manipulation via response manipulation

session_start();

$message = '';
$response_data = [];

// Simulate error message manipulation via response manipulation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'check_error') {
        $error_code = $_POST['error_code'] ?? '';
        $error_message = $_POST['error_message'] ?? '';
        
        if (!empty($error_code) && !empty($error_message)) {
            // Simulate error check
            $is_error = ($error_code !== '200');
            
            $response_data = [
                'error_code' => $error_code,
                'error_message' => $error_message,
                'is_error' => $is_error,
                'error_status' => $is_error ? 'error' : 'success',
                'status' => $is_error ? 'error' : 'success',
                'message' => $is_error ? 'Error occurred' : 'Success',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the error response.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide error code and message.</div>';
        }
    } elseif ($action === 'check_validation') {
        $field_name = $_POST['field_name'] ?? '';
        $field_value = $_POST['field_value'] ?? '';
        
        if (!empty($field_name) && !empty($field_value)) {
            // Simulate validation check
            $is_valid = ($field_value !== 'invalid');
            
            $response_data = [
                'field_name' => $field_name,
                'field_value' => $field_value,
                'is_valid' => $is_valid,
                'validation_status' => $is_valid ? 'valid' : 'invalid',
                'status' => $is_valid ? 'success' : 'error',
                'message' => $is_valid ? 'Validation passed' : 'Validation failed',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the validation response.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide field name and value.</div>';
        }
    } elseif ($action === 'check_authentication') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (!empty($username) && !empty($password)) {
            // Simulate authentication check
            $is_authenticated = ($username === 'admin' && $password === 'admin');
            
            $response_data = [
                'username' => $username,
                'is_authenticated' => $is_authenticated,
                'auth_status' => $is_authenticated ? 'authenticated' : 'unauthenticated',
                'status' => $is_authenticated ? 'success' : 'error',
                'message' => $is_authenticated ? 'Authentication successful' : 'Authentication failed',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the authentication response.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide username and password.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 11: Error Message Manipulation - Response Manipulation Labs</title>
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

        .error-rules {
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

        .status-error {
            color: var(--accent-red);
            font-weight: bold;
        }

        .status-success {
            color: var(--accent-green);
            font-weight: bold;
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
            <h1 class="hero-title">Lab 11: Error Message Manipulation</h1>
            <p class="hero-subtitle">Error message manipulation via response manipulation vulnerabilities</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Medium</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates error message manipulation vulnerabilities where attackers can use Burp Suite to modify error responses and bypass error handling.</p>
            <p><strong>Objective:</strong> Understand how error message manipulation attacks work and how to exploit them using Burp Suite.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-exclamation-triangle me-2"></i>Error Message System
                    </div>
                    <div class="card-body">
                        <div class="vulnerable-form">
                            <h5>Check Error</h5>
                            <p>Test error handling with response manipulation:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_error">
                                <div class="mb-3">
                                    <label for="error_code" class="form-label">Error Code</label>
                                    <input type="text" class="form-control" id="error_code" name="error_code" placeholder="404" required>
                                </div>
                                <div class="mb-3">
                                    <label for="error_message" class="form-label">Error Message</label>
                                    <input type="text" class="form-control" id="error_message" name="error_message" placeholder="Not Found" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check Error</button>
                            </form>
                            
                            <hr>
                            
                            <h5>Check Validation</h5>
                            <p>Test validation with response manipulation:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_validation">
                                <div class="mb-3">
                                    <label for="field_name" class="form-label">Field Name</label>
                                    <input type="text" class="form-control" id="field_name" name="field_name" placeholder="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="field_value" class="form-label">Field Value</label>
                                    <input type="text" class="form-control" id="field_value" name="field_value" placeholder="invalid" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check Validation</button>
                            </form>
                            
                            <hr>
                            
                            <h5>Check Authentication</h5>
                            <p>Test authentication with response manipulation:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_authentication">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="user123" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="password123" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check Authentication</button>
                            </form>
                            
                            <?php echo $message; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-exclamation me-2"></i>Error Message Manipulation Tester
                    </div>
                    <div class="card-body">
                        <div class="auth-warning">
                            <h5>⚠️ Error Message Manipulation Warning</h5>
                            <p>This lab demonstrates error message manipulation vulnerabilities:</p>
                            <ul>
                                <li><code>Error Bypass</code> - Bypass error handling</li>
                                <li><code>Validation Bypass</code> - Bypass validation errors</li>
                                <li><code>Authentication Bypass</code> - Bypass authentication errors</li>
                                <li><code>Status Manipulation</code> - Manipulate error status</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Burp Suite Rules</h5>
                            <p>Use these Burp Suite Match and Replace rules:</p>
                            <ul>
                                <li><code>"is_error":true</code> → <code>"is_error":false</code></li>
                                <li><code>"error_status":"error"</code> → <code>"error_status":"success"</code></li>
                                <li><code>"is_valid":false</code> → <code>"is_valid":true</code></li>
                                <li><code>"is_authenticated":false</code> → <code>"is_authenticated":true</code></li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testErrorBypass()" class="btn btn-primary">Test Error Bypass</button>
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
                                        <strong>Is Error:</strong> <span class="status-<?php echo isset($response_data['is_error']) && $response_data['is_error'] ? 'error' : 'success'; ?>"><?php echo isset($response_data['is_error']) ? ($response_data['is_error'] ? 'true' : 'false') : 'N/A'; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Error Status:</strong> <span class="status-<?php echo isset($response_data['error_status']) && $response_data['error_status'] === 'error' ? 'error' : 'success'; ?>"><?php echo isset($response_data['error_status']) ? $response_data['error_status'] : 'N/A'; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Is Valid:</strong> <span class="status-<?php echo isset($response_data['is_valid']) && $response_data['is_valid'] ? 'valid' : 'invalid'; ?>"><?php echo isset($response_data['is_valid']) ? ($response_data['is_valid'] ? 'true' : 'false') : 'N/A'; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Is Authenticated:</strong> <span class="status-<?php echo isset($response_data['is_authenticated']) && $response_data['is_authenticated'] ? 'authenticated' : 'unauthenticated'; ?>"><?php echo isset($response_data['is_authenticated']) ? ($response_data['is_authenticated'] ? 'true' : 'false') : 'N/A'; ?></span>
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
                        <i class="bi bi-code-square me-2"></i>Error Message Manipulation Rules
                    </div>
                    <div class="card-body">
                        <div class="error-rules">
                            <div class="rule-card">
                                <div class="rule-title">Error Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"is_error\":true",
  "string_replace": "\"is_error\":false"
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Error Status Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"error_status\":\"error\"",
  "string_replace": "\"error_status\":\"success\""
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Validation Bypass</div>
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
                                <div class="rule-title">Status Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"status\":\"error\"",
  "string_replace": "\"status\":\"success\""
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Message Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"message\":\"Error occurred\"",
  "string_replace": "\"message\":\"Success\""
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
                        <li><strong>Type:</strong> Error Message Manipulation</li>
                        <li><strong>Severity:</strong> Medium</li>
                        <li><strong>Method:</strong> Burp Suite Match and Replace</li>
                        <li><strong>Issue:</strong> Client-side trust of error responses</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>Error Bypass:</strong> Bypass error handling</li>
                        <li><strong>Validation Bypass:</strong> Bypass validation errors</li>
                        <li><strong>Authentication Bypass:</strong> Bypass authentication errors</li>
                        <li><strong>Status Manipulation:</strong> Manipulate error status</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Error Message Manipulation Examples</h5>
            <p>Use these Burp Suite Match and Replace rules to exploit error message manipulation vulnerabilities:</p>
            
            <h6>1. Error Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"is_error\":true",
  "string_replace": "\"is_error\":false"
}

// This rule bypasses error handling
// Example: "is_error":true becomes "is_error":false</div>

            <h6>2. Error Status Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"error_status\":\"error\"",
  "string_replace": "\"error_status\":\"success\""
}

// This rule bypasses error status
// Example: "error_status":"error" becomes "error_status":"success"</div>

            <h6>3. Validation Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"is_valid\":false",
  "string_replace": "\"is_valid\":true"
}

// This rule bypasses validation errors
// Example: "is_valid":false becomes "is_valid":true</div>

            <h6>4. Authentication Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"is_authenticated\":false",
  "string_replace": "\"is_authenticated\":true"
}

// This rule bypasses authentication errors
// Example: "is_authenticated":false becomes "is_authenticated":true</div>

            <h6>5. Status Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"status\":\"error\"",
  "string_replace": "\"status\":\"success\""
}

// This rule bypasses status errors
// Example: "status":"error" becomes "status":"success"</div>

            <h6>6. Message Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"message\":\"Error occurred\"",
  "string_replace": "\"message\":\"Success\""
}

// This rule bypasses error messages
// Example: "message":"Error occurred" becomes "message":"Success"</div>

            <h6>7. Validation Status Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"validation_status\":\"invalid\"",
  "string_replace": "\"validation_status\":\"valid\""
}

// This rule bypasses validation status
// Example: "validation_status":"invalid" becomes "validation_status":"valid"</div>

            <h6>8. Auth Status Bypass:</h6>
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

            <h6>9. Error Code Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"error_code\":\"404\"",
  "string_replace": "\"error_code\":\"200\""
}

// This rule bypasses error codes
// Example: "error_code":"404" becomes "error_code":"200"</div>

            <h6>10. Error Message Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"error_message\":\"Not Found\"",
  "string_replace": "\"error_message\":\"Success\""
}

// This rule bypasses error messages
// Example: "error_message":"Not Found" becomes "error_message":"Success"</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Complete error bypass and unauthorized access</li>
                <li>Validation bypass and data integrity issues</li>
                <li>Authentication bypass and unauthorized access</li>
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
                    <li>Implement server-side error validation</li>
                    <li>Use response integrity checks and signatures</li>
                    <li>Implement proper error controls</li>
                    <li>Use secure coding practices</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual error patterns</li>
                    <li>Implement proper input validation</li>
                    <li>Use multi-factor authentication</li>
                    <li>Implement proper error handling</li>
                    <li>Educate users about security threats</li>
                    <li>Use error bypass detection tools</li>
                    <li>Implement proper audit trails</li>
                    <li>Use secure error management</li>
                    <li>Implement proper rate limiting</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testErrorBypass() {
            alert('Error Message Manipulation test initiated. Use Burp Suite Match and Replace rules above.');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
