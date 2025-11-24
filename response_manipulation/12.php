<?php
// Lab 12: Rate Limit Bypass
// Vulnerability: Rate limit bypass via response manipulation

session_start();

$message = '';
$response_data = [];

// Simulate rate limit bypass via response manipulation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'check_rate_limit') {
        $user_id = $_POST['user_id'] ?? '';
        $request_count = $_POST['request_count'] ?? '';
        
        if (!empty($user_id) && !empty($request_count)) {
            // Simulate rate limit check
            $is_rate_limited = ($request_count > 10);
            
            $response_data = [
                'user_id' => $user_id,
                'request_count' => $request_count,
                'rate_limited' => $is_rate_limited,
                'rate_limit_exceeded' => $is_rate_limited,
                'rate_limit_status' => $is_rate_limited ? 'exceeded' : 'within_limit',
                'status' => $is_rate_limited ? 'error' : 'success',
                'message' => $is_rate_limited ? 'Rate limit exceeded' : 'Rate limit within bounds',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the rate limit response.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide user ID and request count.</div>';
        }
    } elseif ($action === 'check_quota') {
        $user_id = $_POST['user_id'] ?? '';
        $quota_used = $_POST['quota_used'] ?? '';
        
        if (!empty($user_id) && !empty($quota_used)) {
            // Simulate quota check
            $is_quota_exceeded = ($quota_used > 100);
            
            $response_data = [
                'user_id' => $user_id,
                'quota_used' => $quota_used,
                'quota_exceeded' => $is_quota_exceeded,
                'quota_status' => $is_quota_exceeded ? 'exceeded' : 'within_limit',
                'status' => $is_quota_exceeded ? 'error' : 'success',
                'message' => $is_quota_exceeded ? 'Quota exceeded' : 'Quota within bounds',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the quota response.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide user ID and quota used.</div>';
        }
    } elseif ($action === 'check_throttle') {
        $user_id = $_POST['user_id'] ?? '';
        $throttle_level = $_POST['throttle_level'] ?? '';
        
        if (!empty($user_id) && !empty($throttle_level)) {
            // Simulate throttle check
            $is_throttled = ($throttle_level > 5);
            
            $response_data = [
                'user_id' => $user_id,
                'throttle_level' => $throttle_level,
                'throttled' => $is_throttled,
                'throttle_status' => $is_throttled ? 'throttled' : 'normal',
                'status' => $is_throttled ? 'error' : 'success',
                'message' => $is_throttled ? 'User throttled' : 'User not throttled',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the throttle response.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide user ID and throttle level.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 12: Rate Limit Bypass - Response Manipulation Labs</title>
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

        .rate-rules {
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

        .status-exceeded {
            color: var(--accent-red);
            font-weight: bold;
        }

        .status-within-limit {
            color: var(--accent-green);
            font-weight: bold;
        }

        .status-throttled {
            color: var(--accent-red);
            font-weight: bold;
        }

        .status-normal {
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
            <h1 class="hero-title">Lab 12: Rate Limit Bypass</h1>
            <p class="hero-subtitle">Rate limit bypass via response manipulation vulnerabilities</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Medium</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates rate limit bypass vulnerabilities where attackers can use Burp Suite to modify rate limit responses and bypass rate restrictions.</p>
            <p><strong>Objective:</strong> Understand how rate limit bypass attacks work and how to exploit them using Burp Suite.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-speedometer2 me-2"></i>Rate Limit System
                    </div>
                    <div class="card-body">
                        <div class="vulnerable-form">
                            <h5>Check Rate Limit</h5>
                            <p>Test rate limiting with response manipulation:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_rate_limit">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">User ID</label>
                                    <input type="text" class="form-control" id="user_id" name="user_id" placeholder="user123" required>
                                </div>
                                <div class="mb-3">
                                    <label for="request_count" class="form-label">Request Count</label>
                                    <input type="number" class="form-control" id="request_count" name="request_count" placeholder="15" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check Rate Limit</button>
                            </form>
                            
                            <hr>
                            
                            <h5>Check Quota</h5>
                            <p>Test quota with response manipulation:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_quota">
                                <div class="mb-3">
                                    <label for="user_id2" class="form-label">User ID</label>
                                    <input type="text" class="form-control" id="user_id2" name="user_id" placeholder="user123" required>
                                </div>
                                <div class="mb-3">
                                    <label for="quota_used" class="form-label">Quota Used</label>
                                    <input type="number" class="form-control" id="quota_used" name="quota_used" placeholder="150" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check Quota</button>
                            </form>
                            
                            <hr>
                            
                            <h5>Check Throttle</h5>
                            <p>Test throttling with response manipulation:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_throttle">
                                <div class="mb-3">
                                    <label for="user_id3" class="form-label">User ID</label>
                                    <input type="text" class="form-control" id="user_id3" name="user_id" placeholder="user123" required>
                                </div>
                                <div class="mb-3">
                                    <label for="throttle_level" class="form-label">Throttle Level</label>
                                    <input type="number" class="form-control" id="throttle_level" name="throttle_level" placeholder="8" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check Throttle</button>
                            </form>
                            
                            <?php echo $message; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-exclamation me-2"></i>Rate Limit Bypass Tester
                    </div>
                    <div class="card-body">
                        <div class="auth-warning">
                            <h5>⚠️ Rate Limit Bypass Warning</h5>
                            <p>This lab demonstrates rate limit bypass vulnerabilities:</p>
                            <ul>
                                <li><code>Rate Limit Bypass</code> - Bypass rate restrictions</li>
                                <li><code>Quota Bypass</code> - Bypass quota limits</li>
                                <li><code>Throttle Bypass</code> - Bypass throttling</li>
                                <li><code>Limit Manipulation</code> - Manipulate rate limits</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Burp Suite Rules</h5>
                            <p>Use these Burp Suite Match and Replace rules:</p>
                            <ul>
                                <li><code>"rate_limited":true</code> → <code>"rate_limited":false</code></li>
                                <li><code>"quota_exceeded":true</code> → <code>"quota_exceeded":false</code></li>
                                <li><code>"throttled":true</code> → <code>"throttled":false</code></li>
                                <li><code>"rate_limit_status":"exceeded"</code> → <code>"rate_limit_status":"within_limit"</code></li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testRateBypass()" class="btn btn-primary">Test Rate Bypass</button>
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
                                        <strong>Rate Limited:</strong> <span class="status-<?php echo isset($response_data['rate_limited']) && $response_data['rate_limited'] ? 'exceeded' : 'within-limit'; ?>"><?php echo isset($response_data['rate_limited']) ? ($response_data['rate_limited'] ? 'true' : 'false') : 'N/A'; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Quota Exceeded:</strong> <span class="status-<?php echo isset($response_data['quota_exceeded']) && $response_data['quota_exceeded'] ? 'exceeded' : 'within-limit'; ?>"><?php echo isset($response_data['quota_exceeded']) ? ($response_data['quota_exceeded'] ? 'true' : 'false') : 'N/A'; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Throttled:</strong> <span class="status-<?php echo isset($response_data['throttled']) && $response_data['throttled'] ? 'throttled' : 'normal'; ?>"><?php echo isset($response_data['throttled']) ? ($response_data['throttled'] ? 'true' : 'false') : 'N/A'; ?></span>
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
                        <i class="bi bi-code-square me-2"></i>Rate Limit Bypass Rules
                    </div>
                    <div class="card-body">
                        <div class="rate-rules">
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
                                <div class="rule-title">Quota Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"quota_exceeded\":true",
  "string_replace": "\"quota_exceeded\":false"
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Throttle Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"throttled\":true",
  "string_replace": "\"throttled\":false"
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
                                <div class="rule-title">Quota Status Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"quota_status\":\"exceeded\"",
  "string_replace": "\"quota_status\":\"within_limit\""
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Throttle Status Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"throttle_status\":\"throttled\"",
  "string_replace": "\"throttle_status\":\"normal\""
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
                        <li><strong>Type:</strong> Rate Limit Bypass</li>
                        <li><strong>Severity:</strong> Medium</li>
                        <li><strong>Method:</strong> Burp Suite Match and Replace</li>
                        <li><strong>Issue:</strong> Client-side trust of rate limit responses</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>Rate Limit Bypass:</strong> Bypass rate restrictions</li>
                        <li><strong>Quota Bypass:</strong> Bypass quota limits</li>
                        <li><strong>Throttle Bypass:</strong> Bypass throttling</li>
                        <li><strong>Limit Manipulation:</strong> Manipulate rate limits</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Rate Limit Bypass Examples</h5>
            <p>Use these Burp Suite Match and Replace rules to exploit rate limit bypass vulnerabilities:</p>
            
            <h6>1. Rate Limit Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"rate_limited\":true",
  "string_replace": "\"rate_limited\":false"
}

// This rule bypasses rate limits
// Example: "rate_limited":true becomes "rate_limited":false</div>

            <h6>2. Quota Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"quota_exceeded\":true",
  "string_replace": "\"quota_exceeded\":false"
}

// This rule bypasses quota limits
// Example: "quota_exceeded":true becomes "quota_exceeded":false</div>

            <h6>3. Throttle Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"throttled\":true",
  "string_replace": "\"throttled\":false"
}

// This rule bypasses throttling
// Example: "throttled":true becomes "throttled":false</div>

            <h6>4. Rate Limit Status Bypass:</h6>
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

            <h6>5. Quota Status Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"quota_status\":\"exceeded\"",
  "string_replace": "\"quota_status\":\"within_limit\""
}

// This rule bypasses quota status
// Example: "quota_status":"exceeded" becomes "quota_status":"within_limit"</div>

            <h6>6. Throttle Status Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"throttle_status\":\"throttled\"",
  "string_replace": "\"throttle_status\":\"normal\""
}

// This rule bypasses throttle status
// Example: "throttle_status":"throttled" becomes "throttle_status":"normal"</div>

            <h6>7. Rate Limit Exceeded Bypass:</h6>
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

            <h6>8. Limit Exceeded Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"limit_exceeded\":true",
  "string_replace": "\"limit_exceeded\":false"
}

// This rule bypasses limit exceeded
// Example: "limit_exceeded":true becomes "limit_exceeded":false</div>

            <h6>9. Rate Limit Hit Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"rate_limit_hit\":true",
  "string_replace": "\"rate_limit_hit\":false"
}

// This rule bypasses rate limit hit
// Example: "rate_limit_hit":true becomes "rate_limit_hit":false</div>

            <h6>10. Rate Limit Reached Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"rate_limit_reached\":true",
  "string_replace": "\"rate_limit_reached\":false"
}

// This rule bypasses rate limit reached
// Example: "rate_limit_reached":true becomes "rate_limit_reached":false</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Complete rate limit bypass and resource abuse</li>
                <li>Quota bypass and unauthorized usage</li>
                <li>Throttle bypass and performance issues</li>
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
                    <li>Implement server-side rate limit validation</li>
                    <li>Use response integrity checks and signatures</li>
                    <li>Implement proper rate controls</li>
                    <li>Use secure coding practices</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual rate patterns</li>
                    <li>Implement proper input validation</li>
                    <li>Use multi-factor authentication</li>
                    <li>Implement proper error handling</li>
                    <li>Educate users about security threats</li>
                    <li>Use rate bypass detection tools</li>
                    <li>Implement proper audit trails</li>
                    <li>Use secure rate limiting</li>
                    <li>Implement proper monitoring</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testRateBypass() {
            alert('Rate Limit Bypass test initiated. Use Burp Suite Match and Replace rules above.');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
