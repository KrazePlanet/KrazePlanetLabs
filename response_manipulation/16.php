<?php
// Lab 16: Business Logic Bypass
// Vulnerability: Business logic bypass via response manipulation

session_start();

$message = '';
$response_data = [];

// Simulate business logic bypass via response manipulation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'check_purchase') {
        $user_id = $_POST['user_id'] ?? '';
        $amount = $_POST['amount'] ?? '';
        $balance = $_POST['balance'] ?? '';
        
        if (!empty($user_id) && !empty($amount) && !empty($balance)) {
            // Simulate purchase check
            $can_purchase = ($balance >= $amount);
            
            $response_data = [
                'user_id' => $user_id,
                'amount' => $amount,
                'balance' => $balance,
                'can_purchase' => $can_purchase,
                'purchase_allowed' => $can_purchase,
                'purchase_status' => $can_purchase ? 'allowed' : 'denied',
                'status' => $can_purchase ? 'success' : 'error',
                'message' => $can_purchase ? 'Purchase allowed' : 'Insufficient balance',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the purchase response.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide user ID, amount, and balance.</div>';
        }
    } elseif ($action === 'check_quota') {
        $user_id = $_POST['user_id'] ?? '';
        $quota_used = $_POST['quota_used'] ?? '';
        $quota_limit = $_POST['quota_limit'] ?? '';
        
        if (!empty($user_id) && !empty($quota_used) && !empty($quota_limit)) {
            // Simulate quota check
            $within_quota = ($quota_used <= $quota_limit);
            
            $response_data = [
                'user_id' => $user_id,
                'quota_used' => $quota_used,
                'quota_limit' => $quota_limit,
                'within_quota' => $within_quota,
                'quota_exceeded' => !$within_quota,
                'quota_status' => $within_quota ? 'within_limit' : 'exceeded',
                'status' => $within_quota ? 'success' : 'error',
                'message' => $within_quota ? 'Within quota' : 'Quota exceeded',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the quota response.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide user ID, quota used, and quota limit.</div>';
        }
    } elseif ($action === 'check_workflow') {
        $user_id = $_POST['user_id'] ?? '';
        $workflow_step = $_POST['workflow_step'] ?? '';
        $required_step = $_POST['required_step'] ?? '';
        
        if (!empty($user_id) && !empty($workflow_step) && !empty($required_step)) {
            // Simulate workflow check
            $workflow_valid = ($workflow_step >= $required_step);
            
            $response_data = [
                'user_id' => $user_id,
                'workflow_step' => $workflow_step,
                'required_step' => $required_step,
                'workflow_valid' => $workflow_valid,
                'workflow_complete' => $workflow_valid,
                'workflow_status' => $workflow_valid ? 'complete' : 'incomplete',
                'status' => $workflow_valid ? 'success' : 'error',
                'message' => $workflow_valid ? 'Workflow complete' : 'Workflow incomplete',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the workflow response.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide user ID, workflow step, and required step.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 16: Business Logic Bypass - Response Manipulation Labs</title>
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

        .business-rules {
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

        .status-exceeded {
            color: var(--accent-red);
            font-weight: bold;
        }

        .status-within-limit {
            color: var(--accent-green);
            font-weight: bold;
        }

        .status-incomplete {
            color: var(--accent-red);
            font-weight: bold;
        }

        .status-complete {
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
            <h1 class="hero-title">Lab 16: Business Logic Bypass</h1>
            <p class="hero-subtitle">Business logic bypass via response manipulation vulnerabilities</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates business logic bypass vulnerabilities where attackers can use Burp Suite to modify business logic responses and bypass business rules.</p>
            <p><strong>Objective:</strong> Understand how business logic bypass attacks work and how to exploit them using Burp Suite.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-diagram-3 me-2"></i>Business Logic System
                    </div>
                    <div class="card-body">
                        <div class="vulnerable-form">
                            <h5>Check Purchase</h5>
                            <p>Test purchase logic with response manipulation:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_purchase">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">User ID</label>
                                    <input type="text" class="form-control" id="user_id" name="user_id" placeholder="user123" required>
                                </div>
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input type="number" class="form-control" id="amount" name="amount" placeholder="1000" required>
                                </div>
                                <div class="mb-3">
                                    <label for="balance" class="form-label">Balance</label>
                                    <input type="number" class="form-control" id="balance" name="balance" placeholder="500" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check Purchase</button>
                            </form>
                            
                            <hr>
                            
                            <h5>Check Quota</h5>
                            <p>Test quota logic with response manipulation:</p>
                            
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
                                <div class="mb-3">
                                    <label for="quota_limit" class="form-label">Quota Limit</label>
                                    <input type="number" class="form-control" id="quota_limit" name="quota_limit" placeholder="100" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check Quota</button>
                            </form>
                            
                            <hr>
                            
                            <h5>Check Workflow</h5>
                            <p>Test workflow logic with response manipulation:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_workflow">
                                <div class="mb-3">
                                    <label for="user_id3" class="form-label">User ID</label>
                                    <input type="text" class="form-control" id="user_id3" name="user_id" placeholder="user123" required>
                                </div>
                                <div class="mb-3">
                                    <label for="workflow_step" class="form-label">Workflow Step</label>
                                    <input type="number" class="form-control" id="workflow_step" name="workflow_step" placeholder="2" required>
                                </div>
                                <div class="mb-3">
                                    <label for="required_step" class="form-label">Required Step</label>
                                    <input type="number" class="form-control" id="required_step" name="required_step" placeholder="5" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check Workflow</button>
                            </form>
                            
                            <?php echo $message; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-exclamation me-2"></i>Business Logic Bypass Tester
                    </div>
                    <div class="card-body">
                        <div class="auth-warning">
                            <h5>⚠️ Business Logic Bypass Warning</h5>
                            <p>This lab demonstrates business logic bypass vulnerabilities:</p>
                            <ul>
                                <li><code>Purchase Bypass</code> - Bypass purchase restrictions</li>
                                <li><code>Quota Bypass</code> - Bypass quota limits</li>
                                <li><code>Workflow Bypass</code> - Bypass workflow requirements</li>
                                <li><code>Logic Manipulation</code> - Manipulate business logic</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Burp Suite Rules</h5>
                            <p>Use these Burp Suite Match and Replace rules:</p>
                            <ul>
                                <li><code>"can_purchase":false</code> → <code>"can_purchase":true</code></li>
                                <li><code>"within_quota":false</code> → <code>"within_quota":true</code></li>
                                <li><code>"workflow_valid":false</code> → <code>"workflow_valid":true</code></li>
                                <li><code>"purchase_status":"denied"</code> → <code>"purchase_status":"allowed"</code></li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testBusinessBypass()" class="btn btn-primary">Test Business Bypass</button>
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
                                        <strong>Can Purchase:</strong> <span class="status-<?php echo isset($response_data['can_purchase']) && $response_data['can_purchase'] ? 'allowed' : 'denied'; ?>"><?php echo isset($response_data['can_purchase']) ? ($response_data['can_purchase'] ? 'true' : 'false') : 'N/A'; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Within Quota:</strong> <span class="status-<?php echo isset($response_data['within_quota']) && $response_data['within_quota'] ? 'within-limit' : 'exceeded'; ?>"><?php echo isset($response_data['within_quota']) ? ($response_data['within_quota'] ? 'true' : 'false') : 'N/A'; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Workflow Valid:</strong> <span class="status-<?php echo isset($response_data['workflow_valid']) && $response_data['workflow_valid'] ? 'complete' : 'incomplete'; ?>"><?php echo isset($response_data['workflow_valid']) ? ($response_data['workflow_valid'] ? 'true' : 'false') : 'N/A'; ?></span>
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
                        <i class="bi bi-code-square me-2"></i>Business Logic Bypass Rules
                    </div>
                    <div class="card-body">
                        <div class="business-rules">
                            <div class="rule-card">
                                <div class="rule-title">Purchase Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"can_purchase\":false",
  "string_replace": "\"can_purchase\":true"
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Quota Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"within_quota\":false",
  "string_replace": "\"within_quota\":true"
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Workflow Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"workflow_valid\":false",
  "string_replace": "\"workflow_valid\":true"
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Purchase Status Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"purchase_status\":\"denied\"",
  "string_replace": "\"purchase_status\":\"allowed\""
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
                                <div class="rule-title">Workflow Status Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"workflow_status\":\"incomplete\"",
  "string_replace": "\"workflow_status\":\"complete\""
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
                        <li><strong>Type:</strong> Business Logic Bypass</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Method:</strong> Burp Suite Match and Replace</li>
                        <li><strong>Issue:</strong> Client-side trust of business logic responses</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>Purchase Bypass:</strong> Bypass purchase restrictions</li>
                        <li><strong>Quota Bypass:</strong> Bypass quota limits</li>
                        <li><strong>Workflow Bypass:</strong> Bypass workflow requirements</li>
                        <li><strong>Logic Manipulation:</strong> Manipulate business logic</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Business Logic Bypass Examples</h5>
            <p>Use these Burp Suite Match and Replace rules to exploit business logic bypass vulnerabilities:</p>
            
            <h6>1. Purchase Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"can_purchase\":false",
  "string_replace": "\"can_purchase\":true"
}

// This rule bypasses purchase restrictions
// Example: "can_purchase":false becomes "can_purchase":true</div>

            <h6>2. Quota Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"within_quota\":false",
  "string_replace": "\"within_quota\":true"
}

// This rule bypasses quota limits
// Example: "within_quota":false becomes "within_quota":true</div>

            <h6>3. Workflow Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"workflow_valid\":false",
  "string_replace": "\"workflow_valid\":true"
}

// This rule bypasses workflow requirements
// Example: "workflow_valid":false becomes "workflow_valid":true</div>

            <h6>4. Purchase Status Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"purchase_status\":\"denied\"",
  "string_replace": "\"purchase_status\":\"allowed\""
}

// This rule bypasses purchase status
// Example: "purchase_status":"denied" becomes "purchase_status":"allowed"</div>

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

            <h6>6. Workflow Status Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"workflow_status\":\"incomplete\"",
  "string_replace": "\"workflow_status\":\"complete\""
}

// This rule bypasses workflow status
// Example: "workflow_status":"incomplete" becomes "workflow_status":"complete"</div>

            <h6>7. Purchase Allowed Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"purchase_allowed\":false",
  "string_replace": "\"purchase_allowed\":true"
}

// This rule bypasses purchase allowed
// Example: "purchase_allowed":false becomes "purchase_allowed":true</div>

            <h6>8. Quota Exceeded Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"quota_exceeded\":true",
  "string_replace": "\"quota_exceeded\":false"
}

// This rule bypasses quota exceeded
// Example: "quota_exceeded":true becomes "quota_exceeded":false</div>

            <h6>9. Workflow Complete Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"workflow_complete\":false",
  "string_replace": "\"workflow_complete\":true"
}

// This rule bypasses workflow complete
// Example: "workflow_complete":false becomes "workflow_complete":true</div>

            <h6>10. Business Logic Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"business_logic_valid\":false",
  "string_replace": "\"business_logic_valid\":true"
}

// This rule bypasses business logic
// Example: "business_logic_valid":false becomes "business_logic_valid":true</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Complete business logic bypass and unauthorized access</li>
                <li>Purchase bypass and financial fraud</li>
                <li>Quota bypass and resource abuse</li>
                <li>Workflow bypass and process disruption</li>
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
                    <li>Implement server-side business logic validation</li>
                    <li>Use response integrity checks and signatures</li>
                    <li>Implement proper business controls</li>
                    <li>Use secure coding practices</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual business patterns</li>
                    <li>Implement proper input validation</li>
                    <li>Use multi-factor authentication</li>
                    <li>Implement proper error handling</li>
                    <li>Educate users about security threats</li>
                    <li>Use business logic bypass detection tools</li>
                    <li>Implement proper audit trails</li>
                    <li>Use secure business logic management</li>
                    <li>Implement proper rate limiting</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testBusinessBypass() {
            alert('Business Logic Bypass test initiated. Use Burp Suite Match and Replace rules above.');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
