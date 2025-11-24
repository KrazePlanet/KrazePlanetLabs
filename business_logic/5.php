<?php
// Lab 5: Advanced Business Logic
// Vulnerability: Advanced business logic vulnerabilities

session_start();

$message = '';
$users = $_SESSION['users'] ?? [
    ['id' => 1, 'name' => 'Admin User', 'role' => 'admin', 'balance' => 10000.00],
    ['id' => 2, 'name' => 'Regular User', 'role' => 'user', 'balance' => 1000.00],
    ['id' => 3, 'name' => 'Premium User', 'role' => 'premium', 'balance' => 5000.00]
];
$transactions = $_SESSION['transactions'] ?? [];
$transaction_id = 1;

// Simulate advanced business logic processing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'transfer') {
        $from_user = intval($_POST['from_user'] ?? 0);
        $to_user = intval($_POST['to_user'] ?? 0);
        $amount = floatval($_POST['amount'] ?? 0);
        $transfer_type = $_POST['transfer_type'] ?? 'standard';
        
        if ($from_user > 0 && $to_user > 0 && $amount > 0) {
            $from_user_data = null;
            $to_user_data = null;
            
            foreach ($users as &$user) {
                if ($user['id'] == $from_user) {
                    $from_user_data = &$user;
                }
                if ($user['id'] == $to_user) {
                    $to_user_data = &$user;
                }
            }
            
            if ($from_user_data && $to_user_data) {
                // Vulnerable: No proper business logic validation
                if ($from_user_data['balance'] >= $amount) {
                    $old_from_balance = $from_user_data['balance'];
                    $old_to_balance = $to_user_data['balance'];
                    
                    $from_user_data['balance'] -= $amount;
                    $to_user_data['balance'] += $amount;
                    
                    $transaction = [
                        'id' => $transaction_id++,
                        'from_user' => $from_user,
                        'to_user' => $to_user,
                        'amount' => $amount,
                        'type' => $transfer_type,
                        'from_balance_before' => $old_from_balance,
                        'from_balance_after' => $from_user_data['balance'],
                        'to_balance_before' => $old_to_balance,
                        'to_balance_after' => $to_user_data['balance'],
                        'timestamp' => date('Y-m-d H:i:s')
                    ];
                    $transactions[] = $transaction;
                    $_SESSION['transactions'] = $transactions;
                    $_SESSION['users'] = $users;
                    $_SESSION['transaction_id'] = $transaction_id;
                    
                    $message = '<div class="alert alert-success">✅ Transfer successful! Amount: $' . number_format($amount, 2) . '</div>';
                } else {
                    $message = '<div class="alert alert-danger">❌ Insufficient funds for transfer.</div>';
                }
            } else {
                $message = '<div class="alert alert-danger">❌ Invalid user IDs.</div>';
            }
        } else {
            $message = '<div class="alert alert-danger">❌ Invalid transfer data.</div>';
        }
    } elseif ($action === 'advanced_bypass') {
        $bypass_type = $_POST['bypass_type'] ?? '';
        $bypass_data = $_POST['bypass_data'] ?? '';
        
        if (!empty($bypass_type) && !empty($bypass_data)) {
            $message = '<div class="alert alert-warning">⚠️ Advanced bypass attempt detected: ' . htmlspecialchars($bypass_type) . ' - ' . htmlspecialchars($bypass_data) . '</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 5: Advanced Business Logic - Business Logic Labs</title>
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

        .advanced-warning {
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

        .users-display {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border: 1px solid #334155;
        }

        .user-item {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
            border: 1px solid #334155;
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

        .advanced-techniques {
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

        .role-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .role-admin {
            background: var(--accent-red);
            color: #e2e8f0;
        }

        .role-user {
            background: var(--accent-blue);
            color: #e2e8f0;
        }

        .role-premium {
            background: var(--accent-orange);
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
            <h1 class="hero-title">Lab 5: Advanced Business Logic</h1>
            <p class="hero-subtitle">Advanced business logic vulnerabilities</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates advanced business logic vulnerabilities that combine multiple techniques like complex authorization bypass, multi-step attacks, and sophisticated business rule exploitation.</p>
            <p><strong>Objective:</strong> Understand how advanced business logic attacks work and how to exploit them.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-gear me-2"></i>Vulnerable Transfer System
                    </div>
                    <div class="card-body">
                        <div class="vulnerable-form">
                            <h5>Money Transfer</h5>
                            <p>This system allows money transfers. Try to exploit advanced business logic:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="transfer">
                                <div class="mb-3">
                                    <label for="from_user" class="form-label">From User</label>
                                    <select class="form-select" id="from_user" name="from_user" required>
                                        <option value="">Select sender</option>
                                        <?php foreach ($users as $user): ?>
                                        <option value="<?php echo $user['id']; ?>"><?php echo $user['name']; ?> (<?php echo $user['role']; ?>) - $<?php echo number_format($user['balance'], 2); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="to_user" class="form-label">To User</label>
                                    <select class="form-select" id="to_user" name="to_user" required>
                                        <option value="">Select recipient</option>
                                        <?php foreach ($users as $user): ?>
                                        <option value="<?php echo $user['id']; ?>"><?php echo $user['name']; ?> (<?php echo $user['role']; ?>) - $<?php echo number_format($user['balance'], 2); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input type="number" class="form-control" id="amount" name="amount" placeholder="100.00" step="0.01" min="0.01" required>
                                </div>
                                <div class="mb-3">
                                    <label for="transfer_type" class="form-label">Transfer Type</label>
                                    <select class="form-select" id="transfer_type" name="transfer_type">
                                        <option value="standard">Standard</option>
                                        <option value="priority">Priority</option>
                                        <option value="instant">Instant</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Transfer Money</button>
                            </form>
                            
                            <?php echo $message; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-gear me-2"></i>Advanced Bypass Tester
                    </div>
                    <div class="card-body">
                        <div class="advanced-warning">
                            <h5>⚠️ Advanced Business Logic Warning</h5>
                            <p>This lab demonstrates advanced business logic vulnerabilities:</p>
                            <ul>
                                <li><code>Complex Authorization</code> - Multi-layered authorization bypass</li>
                                <li><code>Business Rule Exploitation</code> - Exploit business rules</li>
                                <li><code>Multi-Step Attacks</code> - Complex attack sequences</li>
                                <li><code>State Manipulation</code> - Manipulate application state</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Advanced Techniques</h5>
                            <p>These techniques can be used for advanced bypass:</p>
                            <ul>
                                <li><code>Authorization Bypass</code> - Bypass complex authorization</li>
                                <li><code>Business Rule Exploitation</code> - Exploit business rules</li>
                                <li><code>Multi-Step Attacks</code> - Complex attack sequences</li>
                                <li><code>State Manipulation</code> - Manipulate application state</li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testAdvancedBypass()" class="btn btn-primary">Test Advanced Bypass</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-people me-2"></i>Users List
                    </div>
                    <div class="card-body">
                        <div class="users-display">
                            <?php foreach ($users as $user): ?>
                            <div class="user-item">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <strong><?php echo $user['name']; ?></strong>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="role-badge role-<?php echo $user['role']; ?>"><?php echo ucfirst($user['role']); ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="text-success">Balance: $<?php echo number_format($user['balance'], 2); ?></span>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="text-muted">ID: <?php echo $user['id']; ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
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
                                    <div class="col-md-1">
                                        <strong>#<?php echo $transaction['id']; ?></strong>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="text-muted">From: <?php echo $transaction['from_user']; ?></span>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="text-muted">To: <?php echo $transaction['to_user']; ?></span>
                                    </div>
                                    <div class="col-md-1">
                                        <span class="text-success">$<?php echo number_format($transaction['amount'], 2); ?></span>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="text-info"><?php echo ucfirst($transaction['type']); ?></span>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="text-muted"><?php echo $transaction['timestamp']; ?></span>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="text-warning">From: $<?php echo number_format($transaction['from_balance_after'], 2); ?></span>
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
                        <i class="bi bi-code-square me-2"></i>Advanced Business Logic Techniques
                    </div>
                    <div class="card-body">
                        <div class="advanced-techniques">
                            <div class="technique-card">
                                <div class="technique-title">Authorization Bypass</div>
                                <div class="technique-demo">// Bypass complex authorization
{
  "from_user": 1,
  "to_user": 2,
  "amount": 1000,
  "transfer_type": "admin",
  "admin_override": true,
  "skip_authorization": true
}</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Business Rule Exploitation</div>
                                <div class="technique-demo">// Exploit business rules
{
  "from_user": 1,
  "to_user": 2,
  "amount": 1000,
  "transfer_type": "instant",
  "fee_waiver": true,
  "limit_override": true
}</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Multi-Step Attacks</div>
                                <div class="technique-demo">// Complex attack sequence
// Step 1: Escalate privileges
// Step 2: Bypass limits
// Step 3: Execute transfer
// Step 4: Cover tracks</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">State Manipulation</div>
                                <div class="technique-demo">// Manipulate application state
{
  "from_user": 1,
  "to_user": 2,
  "amount": 1000,
  "user_role": "admin",
  "balance_override": true
}</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Parameter Pollution</div>
                                <div class="technique-demo">// Parameter pollution
{
  "from_user": 1,
  "to_user": 2,
  "amount": 1000,
  "from_user": 2,  // Override
  "amount": 0.01   // Override
}</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Logic Bomb</div>
                                <div class="technique-demo">// Logic bomb
{
  "from_user": 1,
  "to_user": 2,
  "amount": 1000,
  "condition": "if admin then amount = 0",
  "admin_check": false
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
                        <li><strong>Type:</strong> Advanced Business Logic</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Method:</strong> Multiple techniques</li>
                        <li><strong>Issue:</strong> Complex vulnerabilities</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>Authorization Bypass:</strong> Bypass complex authorization</li>
                        <li><strong>Business Rule Exploitation:</strong> Exploit business rules</li>
                        <li><strong>Multi-Step Attacks:</strong> Complex attack sequences</li>
                        <li><strong>State Manipulation:</strong> Manipulate application state</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Advanced Business Logic Examples</h5>
            <p>Use these techniques to exploit advanced business logic vulnerabilities:</p>
            
            <h6>1. Authorization Bypass:</h6>
            <div class="code-block">// Bypass complex authorization
{
  "from_user": 1,
  "to_user": 2,
  "amount": 1000,
  "transfer_type": "admin",
  "admin_override": true,
  "skip_authorization": true,
  "user_role": "admin",
  "permissions": ["transfer", "admin", "override"]
}</div>

            <h6>2. Business Rule Exploitation:</h6>
            <div class="code-block">// Exploit business rules
{
  "from_user": 1,
  "to_user": 2,
  "amount": 1000,
  "transfer_type": "instant",
  "fee_waiver": true,
  "limit_override": true,
  "business_rule_bypass": true,
  "validation_skip": true
}</div>

            <h6>3. Multi-Step Attack:</h6>
            <div class="code-block">// Step 1: Escalate privileges
POST /escalate_privileges
{
  "user_id": 1,
  "target_role": "admin",
  "escalation_reason": "emergency"
}

// Step 2: Bypass limits
POST /bypass_limits
{
  "user_id": 1,
  "limit_type": "transfer",
  "new_limit": 999999
}

// Step 3: Execute transfer
POST /transfer
{
  "from_user": 1,
  "to_user": 2,
  "amount": 1000,
  "admin_override": true
}</div>

            <h6>4. State Manipulation:</h6>
            <div class="code-block">// Manipulate application state
{
  "from_user": 1,
  "to_user": 2,
  "amount": 1000,
  "user_role": "admin",
  "balance_override": true,
  "state_manipulation": true,
  "session_hijack": true
}</div>

            <h6>5. Parameter Pollution:</h6>
            <div class="code-block">// Parameter pollution
{
  "from_user": 1,
  "to_user": 2,
  "amount": 1000,
  "from_user": 2,  // Override
  "amount": 0.01,  // Override
  "transfer_type": "admin",
  "admin_override": true
}</div>

            <h6>6. Logic Bomb:</h6>
            <div class="code-block">// Logic bomb
{
  "from_user": 1,
  "to_user": 2,
  "amount": 1000,
  "condition": "if admin then amount = 0",
  "admin_check": false,
  "logic_bomb": true,
  "exploit_condition": true
}</div>

            <h6>7. Race Condition Exploitation:</h6>
            <div class="code-block">// Exploit race conditions
const promises = [];
for (let i = 0; i < 10; i++) {
    promises.push(
        fetch('/transfer', {
            method: 'POST',
            body: JSON.stringify({
                from_user: 1,
                to_user: 2,
                amount: 1000,
                admin_override: true
            })
        })
    );
}
Promise.all(promises);</div>

            <h6>8. Session Hijacking:</h6>
            <div class="code-block">// Session hijacking
const adminSession = 'admin_session_token';
fetch('/transfer', {
    method: 'POST',
    headers: {'Cookie': `session_id=${adminSession}`},
    body: JSON.stringify({
        from_user: 1,
        to_user: 2,
        amount: 1000
    })
});</div>

            <h6>9. API Endpoint Manipulation:</h6>
            <div class="code-block">// Manipulate API endpoints
// Use different endpoints
fetch('/admin/transfer', {method: 'POST', body: 'amount=1000'});
fetch('/internal/transfer', {method: 'POST', body: 'amount=1000'});
fetch('/bypass/transfer', {method: 'POST', body: 'amount=1000'});</div>

            <h6>10. Business Logic Bypass:</h6>
            <div class="code-block">// Bypass business logic
{
  "from_user": 1,
  "to_user": 2,
  "amount": 1000,
  "business_logic_override": true,
  "validation_skip": true,
  "rule_bypass": true,
  "admin_override": true
}</div>

            <h6>11. Complex Authorization Bypass:</h6>
            <div class="code-block">// Complex authorization bypass
{
  "from_user": 1,
  "to_user": 2,
  "amount": 1000,
  "authorization": {
    "level": "admin",
    "permissions": ["transfer", "admin", "override"],
    "bypass": true,
    "skip_checks": true
  }
}</div>

            <h6>12. Business Rule Manipulation:</h6>
            <div class="code-block">// Manipulate business rules
{
  "from_user": 1,
  "to_user": 2,
  "amount": 1000,
  "business_rules": {
    "max_transfer": 999999,
    "fee_waiver": true,
    "limit_override": true,
    "validation_skip": true
  }
}</div>

            <h6>13. State Machine Bypass:</h6>
            <div class="code-block">// Bypass state machine
{
  "from_user": 1,
  "to_user": 2,
  "amount": 1000,
  "state_machine": {
    "current_state": "completed",
    "target_state": "completed",
    "skip_states": ["pending", "approved"],
    "state_override": true
  }
}</div>

            <h6>14. Workflow Bypass:</h6>
            <div class="code-block">// Bypass workflow
{
  "from_user": 1,
  "to_user": 2,
  "amount": 1000,
  "workflow": {
    "skip_approval": true,
    "admin_override": true,
    "workflow_bypass": true,
    "direct_execution": true
  }
}</div>

            <h6>15. Advanced Parameter Manipulation:</h6>
            <div class="code-block">// Advanced parameter manipulation
{
  "from_user": 1,
  "to_user": 2,
  "amount": 1000,
  "parameters": {
    "admin_override": true,
    "skip_validation": true,
    "bypass_limits": true,
    "direct_execution": true,
    "privilege_escalation": true
  }
}</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Sophisticated financial fraud</li>
                <li>Complex authorization bypass</li>
                <li>Advanced business rule exploitation</li>
                <li>Multi-step attack sequences</li>
                <li>State manipulation and corruption</li>
                <li>Advanced persistent threats</li>
                <li>Corporate espionage</li>
                <li>Nation-state attacks</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement comprehensive business logic validation</li>
                    <li>Use secure authorization and access controls</li>
                    <li>Implement proper state management</li>
                    <li>Use secure coding practices</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual business logic patterns</li>
                    <li>Implement proper input validation</li>
                    <li>Use secure session management</li>
                    <li>Implement proper error handling</li>
                    <li>Educate users about security threats</li>
                    <li>Use multi-factor authentication</li>
                    <li>Implement proper logging and monitoring</li>
                    <li>Use business logic verification systems</li>
                    <li>Implement proper audit trails</li>
                    <li>Use threat intelligence and monitoring</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testAdvancedBypass() {
            alert('Advanced Bypass test initiated. Try the advanced bypass techniques above.');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
