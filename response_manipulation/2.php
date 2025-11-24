<?php
// Lab 2: Status Code Manipulation
// Vulnerability: Status code manipulation vulnerabilities

session_start();

$message = '';
$response_data = [];

// Simulate status code manipulation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'check_authentication') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (!empty($username) && !empty($password)) {
            // Simulate authentication check
            $authenticated = ($username === 'admin' && $password === 'admin123');
            
            $response_data = [
                'username' => $username,
                'authenticated' => $authenticated,
                'status' => $authenticated ? 200 : 401,
                'message' => $authenticated ? 'Authentication successful' : 'Authentication failed',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the status code.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide username and password.</div>';
        }
    } elseif ($action === 'check_authorization') {
        $user_id = $_POST['user_id'] ?? '';
        $resource = $_POST['resource'] ?? '';
        
        if (!empty($user_id) && !empty($resource)) {
            // Simulate authorization check
            $authorized = ($user_id === 'admin' || $user_id === 'user1');
            
            $response_data = [
                'user_id' => $user_id,
                'resource' => $resource,
                'authorized' => $authorized,
                'status' => $authorized ? 200 : 403,
                'message' => $authorized ? 'Access granted' : 'Access denied',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the status code.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide user ID and resource.</div>';
        }
    } elseif ($action === 'check_validation') {
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        
        if (!empty($email) && !empty($phone)) {
            // Simulate validation check
            $valid_email = filter_var($email, FILTER_VALIDATE_EMAIL);
            $valid_phone = preg_match('/^\+?[1-9]\d{1,14}$/', $phone);
            
            $response_data = [
                'email' => $email,
                'phone' => $phone,
                'email_valid' => $valid_email,
                'phone_valid' => $valid_phone,
                'status' => ($valid_email && $valid_phone) ? 200 : 400,
                'message' => ($valid_email && $valid_phone) ? 'Validation successful' : 'Validation failed',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the status code.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide email and phone.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 2: Status Code Manipulation - Response Manipulation Labs</title>
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

        .status-warning {
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

        .status-codes {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }

        .status-card {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            border: 1px solid #334155;
        }

        .status-title {
            color: var(--accent-green);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .status-demo {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 4px;
            padding: 0.5rem;
            margin: 0.5rem 0;
            font-family: 'Courier New', monospace;
            font-size: 12px;
        }

        .status-200 {
            color: var(--accent-green);
            font-weight: bold;
        }

        .status-401 {
            color: var(--accent-red);
            font-weight: bold;
        }

        .status-403 {
            color: var(--accent-orange);
            font-weight: bold;
        }

        .status-400 {
            color: var(--accent-blue);
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
            <h1 class="hero-title">Lab 2: Status Code Manipulation</h1>
            <p class="hero-subtitle">Status code manipulation vulnerabilities</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Low</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates status code manipulation vulnerabilities where attackers can use Burp Suite to modify HTTP status codes in server responses and bypass security controls.</p>
            <p><strong>Objective:</strong> Understand how status code manipulation attacks work and how to exploit them using Burp Suite.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-123 me-2"></i>Status Code Generator
                    </div>
                    <div class="card-body">
                        <div class="vulnerable-form">
                            <h5>Authentication Check</h5>
                            <p>Test authentication with status code manipulation:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_authentication">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="admin" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="admin123" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check Authentication</button>
                            </form>
                            
                            <hr>
                            
                            <h5>Authorization Check</h5>
                            <p>Test authorization with status code manipulation:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_authorization">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">User ID</label>
                                    <input type="text" class="form-control" id="user_id" name="user_id" placeholder="user123" required>
                                </div>
                                <div class="mb-3">
                                    <label for="resource" class="form-label">Resource</label>
                                    <input type="text" class="form-control" id="resource" name="resource" placeholder="admin_panel" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check Authorization</button>
                            </form>
                            
                            <hr>
                            
                            <h5>Validation Check</h5>
                            <p>Test validation with status code manipulation:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_validation">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="test@example.com" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="+1234567890" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check Validation</button>
                            </form>
                            
                            <?php echo $message; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-exclamation me-2"></i>Status Code Manipulation Tester
                    </div>
                    <div class="card-body">
                        <div class="status-warning">
                            <h5>⚠️ Status Code Manipulation Warning</h5>
                            <p>This lab demonstrates status code manipulation vulnerabilities:</p>
                            <ul>
                                <li><code>HTTP Status Codes</code> - Manipulate HTTP status codes</li>
                                <li><code>Authentication Bypass</code> - Bypass authentication checks</li>
                                <li><code>Authorization Bypass</code> - Bypass authorization checks</li>
                                <li><code>Validation Bypass</code> - Bypass validation checks</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Burp Suite Rules</h5>
                            <p>Use these Burp Suite Match and Replace rules:</p>
                            <ul>
                                <li><code>"status":401</code> → <code>"status":200</code></li>
                                <li><code>"status":403</code> → <code>"status":200</code></li>
                                <li><code>"status":400</code> → <code>"status":200</code></li>
                                <li><code>"status":404</code> → <code>"status":200</code></li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testStatusManipulation()" class="btn btn-primary">Test Status Manipulation</button>
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
                                        <strong>Status Code:</strong> <span class="status-<?php echo $response_data['status']; ?>"><?php echo $response_data['status']; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Message:</strong> <?php echo htmlspecialchars($response_data['message']); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Authenticated:</strong> <?php echo isset($response_data['authenticated']) ? ($response_data['authenticated'] ? 'Yes' : 'No') : 'N/A'; ?>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Authorized:</strong> <?php echo isset($response_data['authorized']) ? ($response_data['authorized'] ? 'Yes' : 'No') : 'N/A'; ?>
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
                        <i class="bi bi-code-square me-2"></i>Status Code Manipulation Rules
                    </div>
                    <div class="card-body">
                        <div class="status-codes">
                            <div class="status-card">
                                <div class="status-title">Authentication Bypass</div>
                                <div class="status-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"status\":401",
  "string_replace": "\"status\":200"
}</div>
                            </div>
                            
                            <div class="status-card">
                                <div class="status-title">Authorization Bypass</div>
                                <div class="status-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"status\":403",
  "string_replace": "\"status\":200"
}</div>
                            </div>
                            
                            <div class="status-card">
                                <div class="status-title">Validation Bypass</div>
                                <div class="status-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"status\":400",
  "string_replace": "\"status\":200"
}</div>
                            </div>
                            
                            <div class="status-card">
                                <div class="status-title">Not Found Bypass</div>
                                <div class="status-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"status\":404",
  "string_replace": "\"status\":200"
}</div>
                            </div>
                            
                            <div class="status-card">
                                <div class="status-title">Server Error Bypass</div>
                                <div class="status-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"status\":500",
  "string_replace": "\"status\":200"
}</div>
                            </div>
                            
                            <div class="status-card">
                                <div class="status-title">Rate Limit Bypass</div>
                                <div class="status-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"status\":429",
  "string_replace": "\"status\":200"
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
                        <li><strong>Type:</strong> Status Code Manipulation</li>
                        <li><strong>Severity:</strong> Medium</li>
                        <li><strong>Method:</strong> Burp Suite Match and Replace</li>
                        <li><strong>Issue:</strong> Client-side trust of status codes</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>Authentication Bypass:</strong> Change 401 to 200</li>
                        <li><strong>Authorization Bypass:</strong> Change 403 to 200</li>
                        <li><strong>Validation Bypass:</strong> Change 400 to 200</li>
                        <li><strong>Error Bypass:</strong> Change error codes to 200</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Status Code Manipulation Examples</h5>
            <p>Use these Burp Suite Match and Replace rules to exploit status code manipulation vulnerabilities:</p>
            
            <h6>1. Authentication Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"status\":401",
  "string_replace": "\"status\":200"
}

// This rule bypasses authentication
// Example: "status":401 becomes "status":200</div>

            <h6>2. Authorization Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"status\":403",
  "string_replace": "\"status\":200"
}

// This rule bypasses authorization
// Example: "status":403 becomes "status":200</div>

            <h6>3. Validation Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"status\":400",
  "string_replace": "\"status\":200"
}

// This rule bypasses validation
// Example: "status":400 becomes "status":200</div>

            <h6>4. Not Found Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"status\":404",
  "string_replace": "\"status\":200"
}

// This rule bypasses not found errors
// Example: "status":404 becomes "status":200</div>

            <h6>5. Server Error Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"status\":500",
  "string_replace": "\"status\":200"
}

// This rule bypasses server errors
// Example: "status":500 becomes "status":200</div>

            <h6>6. Rate Limit Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"status\":429",
  "string_replace": "\"status\":200"
}

// This rule bypasses rate limiting
// Example: "status":429 becomes "status":200</div>

            <h6>7. Payment Error Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"status\":402",
  "string_replace": "\"status\":200"
}

// This rule bypasses payment errors
// Example: "status":402 becomes "status":200</div>

            <h6>8. Conflict Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"status\":409",
  "string_replace": "\"status\":200"
}

// This rule bypasses conflict errors
// Example: "status":409 becomes "status":200</div>

            <h6>9. Unprocessable Entity Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"status\":422",
  "string_replace": "\"status\":200"
}

// This rule bypasses unprocessable entity errors
// Example: "status":422 becomes "status":200</div>

            <h6>10. Too Many Requests Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"status\":429",
  "string_replace": "\"status\":200"
}

// This rule bypasses too many requests errors
// Example: "status":429 becomes "status":200</div>

            <h6>11. Service Unavailable Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"status\":503",
  "string_replace": "\"status\":200"
}

// This rule bypasses service unavailable errors
// Example: "status":503 becomes "status":200</div>

            <h6>12. Gateway Timeout Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"status\":504",
  "string_replace": "\"status\":200"
}

// This rule bypasses gateway timeout errors
// Example: "status":504 becomes "status":200</div>

            <h6>13. HTTP Version Not Supported Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"status\":505",
  "string_replace": "\"status\":200"
}

// This rule bypasses HTTP version not supported errors
// Example: "status":505 becomes "status":200</div>

            <h6>14. Variant Also Negotiates Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"status\":506",
  "string_replace": "\"status\":200"
}

// This rule bypasses variant also negotiates errors
// Example: "status":506 becomes "status":200</div>

            <h6>15. Insufficient Storage Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"status\":507",
  "string_replace": "\"status\":200"
}

// This rule bypasses insufficient storage errors
// Example: "status":507 becomes "status":200</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Complete authentication and authorization bypass</li>
                <li>Financial fraud and payment manipulation</li>
                <li>Compliance violations and legal issues</li>
                <li>Data manipulation and integrity issues</li>
                <li>Business process disruption and operational impact</li>
                <li>Security control bypass and privilege escalation</li>
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
                    <li>Use status code manipulation detection tools</li>
                    <li>Implement proper audit trails</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testStatusManipulation() {
            alert('Status Code Manipulation test initiated. Use Burp Suite Match and Replace rules above.');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
