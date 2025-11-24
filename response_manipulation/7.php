<?php
// Lab 7: Email Verification Bypass
// Vulnerability: Email verification bypass via response manipulation

session_start();

$message = '';
$response_data = [];

// Simulate email verification bypass via response manipulation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'verify_email') {
        $email = $_POST['email'] ?? '';
        $verification_code = $_POST['verification_code'] ?? '';
        
        if (!empty($email) && !empty($verification_code)) {
            // Simulate email verification check
            $is_verified = ($verification_code === '123456');
            
            $response_data = [
                'email' => $email,
                'verification_code' => $verification_code,
                'verified' => $is_verified,
                'email_verified' => $is_verified,
                'verification_status' => $is_verified ? 'verified' : 'unverified',
                'status' => $is_verified ? 'success' : 'error',
                'message' => $is_verified ? 'Email verified successfully' : 'Invalid verification code',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the email verification response.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide email and verification code.</div>';
        }
    } elseif ($action === 'check_verification') {
        $email = $_POST['email'] ?? '';
        
        if (!empty($email)) {
            // Simulate verification status check
            $is_verified = false; // Default to unverified
            
            $response_data = [
                'email' => $email,
                'verified' => $is_verified,
                'email_verified' => $is_verified,
                'verification_status' => $is_verified ? 'verified' : 'unverified',
                'status' => $is_verified ? 'success' : 'error',
                'message' => $is_verified ? 'Email is verified' : 'Email is not verified',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the verification status response.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide email address.</div>';
        }
    } elseif ($action === 'resend_verification') {
        $email = $_POST['email'] ?? '';
        
        if (!empty($email)) {
            // Simulate resend verification
            $response_data = [
                'email' => $email,
                'verification_sent' => true,
                'status' => 'success',
                'message' => 'Verification email sent successfully',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the resend verification response.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide email address.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 7: Email Verification Bypass - Response Manipulation Labs</title>
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

        .verification-rules {
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

        .status-unverified {
            color: var(--accent-red);
            font-weight: bold;
        }

        .status-verified {
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
            <h1 class="hero-title">Lab 7: Email Verification Bypass</h1>
            <p class="hero-subtitle">Email verification bypass via response manipulation vulnerabilities</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Medium</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates email verification bypass vulnerabilities where attackers can use Burp Suite to modify verification responses and bypass email verification requirements.</p>
            <p><strong>Objective:</strong> Understand how email verification bypass attacks work and how to exploit them using Burp Suite.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-envelope-check me-2"></i>Email Verification System
                    </div>
                    <div class="card-body">
                        <div class="vulnerable-form">
                            <h5>Verify Email</h5>
                            <p>Test email verification with response manipulation:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="verify_email">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="user@example.com" required>
                                </div>
                                <div class="mb-3">
                                    <label for="verification_code" class="form-label">Verification Code</label>
                                    <input type="text" class="form-control" id="verification_code" name="verification_code" placeholder="123456" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Verify Email</button>
                            </form>
                            
                            <hr>
                            
                            <h5>Check Verification Status</h5>
                            <p>Check email verification status:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_verification">
                                <div class="mb-3">
                                    <label for="email2" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email2" name="email" placeholder="user@example.com" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check Status</button>
                            </form>
                            
                            <hr>
                            
                            <h5>Resend Verification</h5>
                            <p>Resend verification email:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="resend_verification">
                                <div class="mb-3">
                                    <label for="email3" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email3" name="email" placeholder="user@example.com" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Resend Verification</button>
                            </form>
                            
                            <?php echo $message; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-exclamation me-2"></i>Email Verification Bypass Tester
                    </div>
                    <div class="card-body">
                        <div class="auth-warning">
                            <h5>⚠️ Email Verification Bypass Warning</h5>
                            <p>This lab demonstrates email verification bypass vulnerabilities:</p>
                            <ul>
                                <li><code>Verification Bypass</code> - Bypass email verification</li>
                                <li><code>Status Manipulation</code> - Manipulate verification status</li>
                                <li><code>Code Bypass</code> - Bypass verification codes</li>
                                <li><code>Resend Bypass</code> - Bypass resend restrictions</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Burp Suite Rules</h5>
                            <p>Use these Burp Suite Match and Replace rules:</p>
                            <ul>
                                <li><code>"verified":false</code> → <code>"verified":true</code></li>
                                <li><code>"email_verified":false</code> → <code>"email_verified":true</code></li>
                                <li><code>"verification_status":"unverified"</code> → <code>"verification_status":"verified"</code></li>
                                <li><code>"status":"error"</code> → <code>"status":"success"</code></li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testEmailBypass()" class="btn btn-primary">Test Email Bypass</button>
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
                                        <strong>Verified:</strong> <span class="status-<?php echo isset($response_data['verified']) && $response_data['verified'] ? 'verified' : 'unverified'; ?>"><?php echo isset($response_data['verified']) ? ($response_data['verified'] ? 'true' : 'false') : 'N/A'; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Email Verified:</strong> <span class="status-<?php echo isset($response_data['email_verified']) && $response_data['email_verified'] ? 'verified' : 'unverified'; ?>"><?php echo isset($response_data['email_verified']) ? ($response_data['email_verified'] ? 'true' : 'false') : 'N/A'; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Status:</strong> <span class="status-<?php echo isset($response_data['status']) && $response_data['status'] === 'success' ? 'success' : 'error'; ?>"><?php echo isset($response_data['status']) ? $response_data['status'] : 'N/A'; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Verification Status:</strong> <span class="status-<?php echo isset($response_data['verification_status']) && $response_data['verification_status'] === 'verified' ? 'verified' : 'unverified'; ?>"><?php echo isset($response_data['verification_status']) ? $response_data['verification_status'] : 'N/A'; ?></span>
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
                        <i class="bi bi-code-square me-2"></i>Email Verification Bypass Rules
                    </div>
                    <div class="card-body">
                        <div class="verification-rules">
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
                                <div class="rule-title">Email Verified Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"email_verified\":false",
  "string_replace": "\"email_verified\":true"
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Verification Status Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"verification_status\":\"unverified\"",
  "string_replace": "\"verification_status\":\"verified\""
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Status Success Bypass</div>
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
  "string_match": "\"message\":\"Invalid verification code\"",
  "string_replace": "\"message\":\"Email verified successfully\""
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Verification Sent Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"verification_sent\":false",
  "string_replace": "\"verification_sent\":true"
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
                        <li><strong>Type:</strong> Email Verification Bypass</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Method:</strong> Burp Suite Match and Replace</li>
                        <li><strong>Issue:</strong> Client-side trust of verification responses</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>Verification Bypass:</strong> Bypass email verification</li>
                        <li><strong>Status Manipulation:</strong> Manipulate verification status</li>
                        <li><strong>Code Bypass:</strong> Bypass verification codes</li>
                        <li><strong>Resend Bypass:</strong> Bypass resend restrictions</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Email Verification Bypass Examples</h5>
            <p>Use these Burp Suite Match and Replace rules to exploit email verification bypass vulnerabilities:</p>
            
            <h6>1. Verification Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"verified\":false",
  "string_replace": "\"verified\":true"
}

// This rule bypasses email verification
// Example: "verified":false becomes "verified":true</div>

            <h6>2. Email Verified Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"email_verified\":false",
  "string_replace": "\"email_verified\":true"
}

// This rule bypasses email verification status
// Example: "email_verified":false becomes "email_verified":true</div>

            <h6>3. Verification Status Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"verification_status\":\"unverified\"",
  "string_replace": "\"verification_status\":\"verified\""
}

// This rule bypasses verification status
// Example: "verification_status":"unverified" becomes "verification_status":"verified"</div>

            <h6>4. Status Success Bypass:</h6>
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

            <h6>5. Message Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"message\":\"Invalid verification code\"",
  "string_replace": "\"message\":\"Email verified successfully\""
}

// This rule bypasses error messages
// Example: "message":"Invalid verification code" becomes "message":"Email verified successfully"</div>

            <h6>6. Verification Sent Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"verification_sent\":false",
  "string_replace": "\"verification_sent\":true"
}

// This rule bypasses verification sent status
// Example: "verification_sent":false becomes "verification_sent":true</div>

            <h6>7. Code Validation Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"code_valid\":false",
  "string_replace": "\"code_valid\":true"
}

// This rule bypasses code validation
// Example: "code_valid":false becomes "code_valid":true</div>

            <h6>8. Email Status Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"email_status\":\"unverified\"",
  "string_replace": "\"email_status\":\"verified\""
}

// This rule bypasses email status
// Example: "email_status":"unverified" becomes "email_status":"verified"</div>

            <h6>9. Verification Code Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"code_verified\":false",
  "string_replace": "\"code_verified\":true"
}

// This rule bypasses code verification
// Example: "code_verified":false becomes "code_verified":true</div>

            <h6>10. Email Confirmation Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"email_confirmed\":false",
  "string_replace": "\"email_confirmed\":true"
}

// This rule bypasses email confirmation
// Example: "email_confirmed":false becomes "email_confirmed":true</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Complete email verification bypass and unauthorized access</li>
                <li>Account takeover and identity theft</li>
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
                    <li>Implement server-side email verification validation</li>
                    <li>Use response integrity checks and signatures</li>
                    <li>Implement proper verification controls</li>
                    <li>Use secure coding practices</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual verification patterns</li>
                    <li>Implement proper input validation</li>
                    <li>Use multi-factor authentication</li>
                    <li>Implement proper error handling</li>
                    <li>Educate users about security threats</li>
                    <li>Use email verification bypass detection tools</li>
                    <li>Implement proper audit trails</li>
                    <li>Use secure verification codes</li>
                    <li>Implement proper rate limiting</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testEmailBypass() {
            alert('Email Verification Bypass test initiated. Use Burp Suite Match and Replace rules above.');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
