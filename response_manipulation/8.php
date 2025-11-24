<?php
// Lab 8: Payment Status Manipulation
// Vulnerability: Payment status manipulation via response manipulation

session_start();

$message = '';
$response_data = [];

// Simulate payment status manipulation via response manipulation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'check_payment') {
        $payment_id = $_POST['payment_id'] ?? '';
        $amount = $_POST['amount'] ?? '';
        
        if (!empty($payment_id) && !empty($amount)) {
            // Simulate payment status check
            $is_paid = ($amount > 0);
            
            $response_data = [
                'payment_id' => $payment_id,
                'amount' => $amount,
                'paid' => $is_paid,
                'payment_status' => $is_paid ? 'completed' : 'pending',
                'status' => $is_paid ? 'success' : 'error',
                'message' => $is_paid ? 'Payment completed successfully' : 'Payment pending',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the payment status response.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide payment ID and amount.</div>';
        }
    } elseif ($action === 'verify_payment') {
        $payment_id = $_POST['payment_id'] ?? '';
        
        if (!empty($payment_id)) {
            // Simulate payment verification
            $is_verified = false; // Default to unverified
            
            $response_data = [
                'payment_id' => $payment_id,
                'verified' => $is_verified,
                'payment_verified' => $is_verified,
                'verification_status' => $is_verified ? 'verified' : 'unverified',
                'status' => $is_verified ? 'success' : 'error',
                'message' => $is_verified ? 'Payment verified successfully' : 'Payment verification failed',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the payment verification response.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide payment ID.</div>';
        }
    } elseif ($action === 'process_payment') {
        $payment_id = $_POST['payment_id'] ?? '';
        $amount = $_POST['amount'] ?? '';
        
        if (!empty($payment_id) && !empty($amount)) {
            // Simulate payment processing
            $is_processed = ($amount > 0);
            
            $response_data = [
                'payment_id' => $payment_id,
                'amount' => $amount,
                'processed' => $is_processed,
                'payment_processed' => $is_processed,
                'processing_status' => $is_processed ? 'completed' : 'failed',
                'status' => $is_processed ? 'success' : 'error',
                'message' => $is_processed ? 'Payment processed successfully' : 'Payment processing failed',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $message = '<div class="alert alert-info">ℹ️ Response generated. Use Burp Suite to manipulate the payment processing response.</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please provide payment ID and amount.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 8: Payment Status Manipulation - Response Manipulation Labs</title>
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

        .payment-rules {
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

        .status-pending {
            color: var(--accent-orange);
            font-weight: bold;
        }

        .status-completed {
            color: var(--accent-green);
            font-weight: bold;
        }

        .status-failed {
            color: var(--accent-red);
            font-weight: bold;
        }

        .status-success {
            color: var(--accent-green);
            font-weight: bold;
        }

        .status-error {
            color: var(--accent-red);
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
            <h1 class="hero-title">Lab 8: Payment Status Manipulation</h1>
            <p class="hero-subtitle">Payment status manipulation via response manipulation vulnerabilities</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates payment status manipulation vulnerabilities where attackers can use Burp Suite to modify payment responses and bypass payment requirements.</p>
            <p><strong>Objective:</strong> Understand how payment status manipulation attacks work and how to exploit them using Burp Suite.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-credit-card me-2"></i>Payment System
                    </div>
                    <div class="card-body">
                        <div class="vulnerable-form">
                            <h5>Check Payment</h5>
                            <p>Test payment status with response manipulation:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_payment">
                                <div class="mb-3">
                                    <label for="payment_id" class="form-label">Payment ID</label>
                                    <input type="text" class="form-control" id="payment_id" name="payment_id" placeholder="PAY-123456" required>
                                </div>
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input type="number" class="form-control" id="amount" name="amount" placeholder="100.00" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check Payment</button>
                            </form>
                            
                            <hr>
                            
                            <h5>Verify Payment</h5>
                            <p>Verify payment status:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="verify_payment">
                                <div class="mb-3">
                                    <label for="payment_id2" class="form-label">Payment ID</label>
                                    <input type="text" class="form-control" id="payment_id2" name="payment_id" placeholder="PAY-123456" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Verify Payment</button>
                            </form>
                            
                            <hr>
                            
                            <h5>Process Payment</h5>
                            <p>Process payment status:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="process_payment">
                                <div class="mb-3">
                                    <label for="payment_id3" class="form-label">Payment ID</label>
                                    <input type="text" class="form-control" id="payment_id3" name="payment_id" placeholder="PAY-123456" required>
                                </div>
                                <div class="mb-3">
                                    <label for="amount2" class="form-label">Amount</label>
                                    <input type="number" class="form-control" id="amount2" name="amount" placeholder="100.00" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Process Payment</button>
                            </form>
                            
                            <?php echo $message; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-exclamation me-2"></i>Payment Status Manipulation Tester
                    </div>
                    <div class="card-body">
                        <div class="auth-warning">
                            <h5>⚠️ Payment Status Manipulation Warning</h5>
                            <p>This lab demonstrates payment status manipulation vulnerabilities:</p>
                            <ul>
                                <li><code>Payment Bypass</code> - Bypass payment requirements</li>
                                <li><code>Status Manipulation</code> - Manipulate payment status</li>
                                <li><code>Verification Bypass</code> - Bypass payment verification</li>
                                <li><code>Processing Bypass</code> - Bypass payment processing</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Burp Suite Rules</h5>
                            <p>Use these Burp Suite Match and Replace rules:</p>
                            <ul>
                                <li><code>"paid":false</code> → <code>"paid":true</code></li>
                                <li><code>"payment_status":"pending"</code> → <code>"payment_status":"completed"</code></li>
                                <li><code>"verified":false</code> → <code>"verified":true</code></li>
                                <li><code>"processed":false</code> → <code>"processed":true</code></li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testPaymentBypass()" class="btn btn-primary">Test Payment Bypass</button>
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
                                        <strong>Paid:</strong> <span class="status-<?php echo isset($response_data['paid']) && $response_data['paid'] ? 'completed' : 'pending'; ?>"><?php echo isset($response_data['paid']) ? ($response_data['paid'] ? 'true' : 'false') : 'N/A'; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Payment Status:</strong> <span class="status-<?php echo isset($response_data['payment_status']) && $response_data['payment_status'] === 'completed' ? 'completed' : 'pending'; ?>"><?php echo isset($response_data['payment_status']) ? $response_data['payment_status'] : 'N/A'; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Verified:</strong> <span class="status-<?php echo isset($response_data['verified']) && $response_data['verified'] ? 'completed' : 'pending'; ?>"><?php echo isset($response_data['verified']) ? ($response_data['verified'] ? 'true' : 'false') : 'N/A'; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Processed:</strong> <span class="status-<?php echo isset($response_data['processed']) && $response_data['processed'] ? 'completed' : 'pending'; ?>"><?php echo isset($response_data['processed']) ? ($response_data['processed'] ? 'true' : 'false') : 'N/A'; ?></span>
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
                        <i class="bi bi-code-square me-2"></i>Payment Status Manipulation Rules
                    </div>
                    <div class="card-body">
                        <div class="payment-rules">
                            <div class="rule-card">
                                <div class="rule-title">Payment Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"paid\":false",
  "string_replace": "\"paid\":true"
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Payment Status Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"payment_status\":\"pending\"",
  "string_replace": "\"payment_status\":\"completed\""
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Payment Verified Bypass</div>
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
                                <div class="rule-title">Payment Processed Bypass</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"processed\":false",
  "string_replace": "\"processed\":true"
}</div>
                            </div>
                            
                            <div class="rule-card">
                                <div class="rule-title">Payment Status Success</div>
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
                                <div class="rule-title">Payment Verification Status</div>
                                <div class="rule-demo">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"verification_status\":\"unverified\"",
  "string_replace": "\"verification_status\":\"verified\""
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
                        <li><strong>Type:</strong> Payment Status Manipulation</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Method:</strong> Burp Suite Match and Replace</li>
                        <li><strong>Issue:</strong> Client-side trust of payment responses</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>Payment Bypass:</strong> Bypass payment requirements</li>
                        <li><strong>Status Manipulation:</strong> Manipulate payment status</li>
                        <li><strong>Verification Bypass:</strong> Bypass payment verification</li>
                        <li><strong>Processing Bypass:</strong> Bypass payment processing</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Payment Status Manipulation Examples</h5>
            <p>Use these Burp Suite Match and Replace rules to exploit payment status manipulation vulnerabilities:</p>
            
            <h6>1. Payment Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"paid\":false",
  "string_replace": "\"paid\":true"
}

// This rule bypasses payment requirements
// Example: "paid":false becomes "paid":true</div>

            <h6>2. Payment Status Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"payment_status\":\"pending\"",
  "string_replace": "\"payment_status\":\"completed\""
}

// This rule bypasses payment status
// Example: "payment_status":"pending" becomes "payment_status":"completed"</div>

            <h6>3. Payment Verified Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"verified\":false",
  "string_replace": "\"verified\":true"
}

// This rule bypasses payment verification
// Example: "verified":false becomes "verified":true</div>

            <h6>4. Payment Processed Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"processed\":false",
  "string_replace": "\"processed\":true"
}

// This rule bypasses payment processing
// Example: "processed":false becomes "processed":true</div>

            <h6>5. Payment Status Success:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"status\":\"error\"",
  "string_replace": "\"status\":\"success\""
}

// This rule bypasses payment errors
// Example: "status":"error" becomes "status":"success"</div>

            <h6>6. Payment Verification Status:</h6>
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

            <h6>7. Payment Processing Status:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"processing_status\":\"failed\"",
  "string_replace": "\"processing_status\":\"completed\""
}

// This rule bypasses processing status
// Example: "processing_status":"failed" becomes "processing_status":"completed"</div>

            <h6>8. Payment Amount Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"amount_paid\":0",
  "string_replace": "\"amount_paid\":100"
}

// This rule bypasses payment amount
// Example: "amount_paid":0 becomes "amount_paid":100</div>

            <h6>9. Payment Method Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"payment_method\":\"none\"",
  "string_replace": "\"payment_method\":\"credit_card\""
}

// This rule bypasses payment method
// Example: "payment_method":"none" becomes "payment_method":"credit_card"</div>

            <h6>10. Payment Confirmation Bypass:</h6>
            <div class="code-block">{
  "comment": "Response Manipulation",
  "enabled": true,
  "is_simple_match": false,
  "rule_type": "response_body",
  "string_match": "\"payment_confirmed\":false",
  "string_replace": "\"payment_confirmed\":true"
}

// This rule bypasses payment confirmation
// Example: "payment_confirmed":false becomes "payment_confirmed":true</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Complete payment bypass and unauthorized access</li>
                <li>Financial fraud and monetary loss</li>
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
                    <li>Implement server-side payment validation</li>
                    <li>Use response integrity checks and signatures</li>
                    <li>Implement proper payment controls</li>
                    <li>Use secure coding practices</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual payment patterns</li>
                    <li>Implement proper input validation</li>
                    <li>Use multi-factor authentication</li>
                    <li>Implement proper error handling</li>
                    <li>Educate users about security threats</li>
                    <li>Use payment bypass detection tools</li>
                    <li>Implement proper audit trails</li>
                    <li>Use secure payment processing</li>
                    <li>Implement proper rate limiting</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testPaymentBypass() {
            alert('Payment Status Manipulation test initiated. Use Burp Suite Match and Replace rules above.');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
